<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use App\QuizCategory;
use App\LmsSeries;
use App\LmsContent;
use App\Http\Requests;

use App\Quiz;
use App\Subject;
use App\QuestionBank;
use App\QuizResult;

use Yajra\Datatables\Datatables;
use Auth;
use App\User;
use App\LmsTrack;
use DB;

use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;

// use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\LengthAwarePaginator;

class LMSfrontViewController extends Controller
{

     public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

     public function viewCategories()
     {

		$user = Auth::user();
		$data['subjects'] = Subject::get();
		$categories   = QuizCategory::where( 'category_status', '=', 'active' )->orderBy( 'category_order', 'asc' )->paginate( FRONT_PAGE_LENGTH );
     	$data['categories']   = $categories;
		$data['title'] = getPhrase('Pathway Courses');

     	return view('lms-forntview.course-categories-list-page',$data);
     }



     public function showCourse( $slug )
     {
     	
		$course_details   = LmsSeries::select(['lmsseries.*', 'qc.category', 'qc.slug AS cat_slug'])
		->join('quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id')
		->where('lmsseries.slug','=',$slug )
		->where('lmsseries.status','=','active' )
		->where('qc.category_status','=','active' )
		->first();

		if( ! $course_details )
		{
			prepareBlockUserMessage();
			return back();
		}

		if ( $course_details->privacy == 'loginrequired' && ! Auth::check() ) {
			prepareBlockUserMessage();
			return back();
		}

		if ( Auth::check() ) {
			$add_mycourse = TRUE;
			if ( $course_details->is_paid == 1 && $course_details->cost > 0 ) {
				if ( ! isItemPurchased( $course_details->id, 'lms' ) ) {
					$add_mycourse = FALSE;
				}
			}
			/**
			 * If the course is paid course, It should buy to add it to my courses
			 */
			if ( $add_mycourse ) {
				$check = App\MyCourses::where('user_id', '=', Auth::User()->id)->where( 'course_id', '=', $course_details->id );

				if ( $check->count() == 0 ) {
					$record = new App\MyCourses();
					$record->user_id = Auth::User()->id;
					$record->course_id = $course_details->id;
					$record->save();
				}
			}
		}

     	$data['course_details']    = $course_details;
		$data['title']    = $course_details->title;
		$data['layout']      = getLayout();
     	return view('lms-forntview.other-views.show-course',$data);
     }

	 // lms/course-lessons/{slug}
	 public function viewLesson( $slug )
     {
		$data = array();
		$record = LmsSeries::getRecordWithSlug($slug);
		
		if ( $record->is_paid == 1 && $record->cost > 0 ) {
			if ( ! isItemPurchased( $record->id, 'lms' ) ) {
				prepareBlockUserMessage( 'buy_this_course_to_continue' );
				return back();
			}
		}
		$data['item']      = $record;
		$data['parent_course']      = FALSE;
		if ( $record->parent_id > 0 ) {
			$data['parent_course'] = LmsSeries::where('id', '=',$record->parent_id )->first();
		}
		$data['category'] = QuizCategory::getRecordWithId( $record->lms_category_id );
		$data['contents']     = $record->getContents( '', TRUE );
		$data['title']        = $record->title;
		$data['sub_title']    = '';
		if ( ! empty( $record->sub_title ) ) {
			$data['sub_title'] = $record->sub_title;
		}

        $data['layout']      = getLayout();
		return view('lms-forntview.course-lesson-page',$data);
     }

	 public function singleLesson( $series_slug, $slug, $piece_slug = '' )
     {
		$data = array();
		$record = Lmscontent::getRecordWithSlug( $slug );
		$data['item']      = $record;
		$data['display_item']      = $record;
		$data['current_piece'] = FALSE;
		if( ! empty( $piece_slug ) ) {
			$data['current_piece']  = Lmscontent::getRecordWithSlug( $piece_slug );
			$data['display_item']      = $data['current_piece'];
		}

		$series_details = LmsSeries::getRecordWithSlug($series_slug);
		
		if ( $series_details->parent_id == 0 && $series_details->is_paid == 1 && $series_details->cost > 0 ) {
			if ( ! isLessonFree( $series_details->id, $record->id ) ) {
				prepareBlockUserMessage( 'buy_this_course_to_continue' );
				return back();
			}
		}
		
		$data['series_details'] = $series_details;
		$data['parent_course']      = FALSE;
		if ( $series_details->parent_id > 0 ) {
			$parent_course = LmsSeries::where('id', '=',$series_details->parent_id )->first();
			$data['parent_course'] = $parent_course;
			if ( $parent_course->is_paid == 1 && $parent_course->cost > 0 ) {
				if ( ! isLessonFree( $parent_course->id, $record->id ) ) {
					prepareBlockUserMessage( 'buy_this_course_to_continue' );
					return back();
				}
			}
		}
		$data['category'] = QuizCategory::getRecordWithId( $series_details->lms_category_id );
		$data['layout']      = getLayout();
		$data['left'] = 'no';
		$data['right'] = 'no';
		return view( 'lms-forntview.single-lesson', $data );
     }

	 public function instructions( $id )
	 {

		if ( ! Auth::check() ) {
			 $message = getPhrase( 'Please login to take exam' );
			 return json_encode( array( 'status' => '0', 'reason' => 'not_login', 'message' => $message, 'redirect_to' => '' ) );
		}


		$instruction_page = '';
		$record = Quiz::getRecordWithId( $id );

		if( ! $record ) {
			$message = getPhrase( 'Record not valid' );
			// $redirect_to = Request::server('HTTP_REFERER');
			$redirect_to = $_SERVER['HTTP_REFERER'];
			return json_encode( array( 'status' => '0', 'reason' => 'not_valid', 'message' => $message, 'redirect_to' => $redirect_to ) );
		}

		if($record->instructions_page_id)
		$instruction_page = App\Instruction::where('id',$record->instructions_page_id)->first();

		$data['instruction_data'] = '';

		if($instruction_page){
		$data['instruction_data'] = $instruction_page->content;
		$data['instruction_title'] = $instruction_page->title;
		}


		//If Other than student tries to attempt the exam
		//Restrict the access to that exam
		if( ! checkRole(['student']) )
		{
			$message = getPhrase( 'You dont have access this page' );
			$redirect_to = $this->getReturnUrl();
			return json_encode( array( 'status' => '0', 'reason' => 'no_access', 'message' => $message, 'redirect_to' => '' ) );
		}

		$data['record']       	  = $record;
		$data['active_class']     = 'exams';
		$data['layout']           = 'layouts.full-width-no-menu';
		$data['title']          = $record->title;
		$data['additional_css'] = 'icononly';
		$data['right'] = 'no';
		// $data['block_navigation']          = TRUE;
		$html = view('student.exams.instructions')->with( $data )->render();
		return response()->json( array( 'html' => $html ) );
		//return view('student.exams.instructions', $data);
	 }

	 public function saveData( Request $request )
     {
		if ( Auth::check() ) {
			$user = Auth::user();
			$message = $type = $type_sub = '';
			$course_id = $request->course_id;
			$module_id = $request->module_id;
			
			if ( $request->action == 'lms_track' ) {
				$group_id = $request->group_id;
				$parts = explode('-', $request->type);
				$type = $parts[0];
				$type_sub = '';
				if ( ! empty( $parts[1] ) ) {
					$type_sub = $parts[1];
				}

				if ( $type_sub == 'uncomplete' ) {
					$record = LmsTrack::where('content_id', '=',$request->content_id )
						->where('user_id', '=', $user->id)
						->where('type', '=', $type)
						//->where('course_id', '=', $course_id)
						//->where('module_id', '=', $module_id)
						->whereIn('content_type', array('course', 'group') )
						//->where('group_id', '=', $group_id)
						->delete();
					$message = getPhrase('marked_as_un_completed');
				} else {
					$record = new LmsTrack();
					$record->content_id = $request->content_id;
					$record->user_id = $user->id;
					$record->status = 'completed';
					$record->type = $request->type;
					$record->course_id = $course_id;
					$record->module_id = $module_id;
					$record->content_type = $request->content_type;
					$record->group_id = $group_id;
					$record->save();
					$message = getPhrase('marked_as_completed');
				}

				if ( ! empty( $course_id ) && in_array( $request->content_type, array( 'course', 'group' ) ) ) {
					$course = LmsSeries::getRecordWithId( $course_id );
					if ( ! empty( $course ) ) {
						$check = App\MyCourses::where('user_id', '=', $user->id)->where( 'course_id', '=', $request->course_id );
						if ( $check->count() == 0 ) {
							$record = new App\MyCourses();
							$record->user_id = $user->id;
							$record->course_id = $course_id;
							$record->save();
						}
						if ( is_course_completed_new( $course_id ) ) {
							mark_as_completed_course( $course_id );
						} else {
							if ( $check->count() > 0 ) {
								$check->update( array(
									'course_status' => 'running',
									'course_completed' => NULL,
								) );
							}
						}
					}
				}
			} elseif ( $request->action == 'add_posttogroup' ) {
				$record = array(
					'group_id' => $request->group_id,
					'content_id' => $request->post_id,
					'content_type' => 'post',					
				);
				DB::table('lmsgroups_contents')->insert( $record );
				$message = getPhrase('post_added_successfully');
				return json_encode( array( 'status' => '1', 'message' => $message, 'post_id' => $record['post_id'] ) );
			} elseif ( $request->action == 'save_groupcomments' ) {
				$record = array(
					'content_id' => 0,
					'user_id' => get_current_user_id(),
					'comments_notes' => $request->comments,
					'type' => 'groupcomments',
					'group_id' => $request->group_id,
				);
				DB::table('lmscontents_comments')->insert( $record );
				$message = getPhrase('your_comments_submitted_successfully');
			} elseif ( $request->action == 'save_comments' ) {
				$record = new App\LmsComments();
				$record->content_id = $request->content_id;
				$record->user_id = $user->id;
				$record->comments_notes = $request->comments;
				$record->type = $request->type;
				if ( 'groupcomments' === $request->type ) {
					$record->group_id = $request->c_g_id;
				}
				$record->save();

				if ( ! empty( $request->course_id ) ) {
					$course = LmsSeries::getRecordWithId( $request->course_id );
					if ( ! empty( $course ) ) {
						$check = App\MyCourses::where('user_id', '=', $user->id)->where( 'course_id', '=', $request->course_id );
						if ( $check->count() == 0 ) {
							$record = new App\MyCourses();
							$record->user_id = $user->id;
							$record->course_id = $request->course_id;
							$record->save();
						}
					}
				}
				$message = getPhrase('your_comments_submitted_successfully');
			} else if ( $request->action == 'save_notes' ) {
				$record = new App\LmsComments();
				$record->content_id = $request->content_id;
				$record->user_id = $user->id;
				$record->comments_notes = $request->notes;
				$record->type = 'notes';
				$record->save();
				$message = getPhrase('notes_saved_successfully');
			} else if ( $request->action == 'save_issue' ) {
				$record = new App\SiteIssue();
				$record->user_id = $user->id;
				$record->issue_description = $request->issue_description;
				$record->url = $_SERVER['HTTP_REFERER'];
				$record->user_agent = $request->notes;
				$record->ip_address = Request::ip();
				$record->save();
				$message = getPhrase('apologies for the inconvenience caused. we will get back to you soon');
			} else if ( $request->action == 'make_my_course' ) {
				$record = new App\MyCourses();
				$record->user_id = $user->id;
				$record->course_id = $request->course_id;
				$record->save();
				$message = getPhrase('Course added to your courses list.');
			} elseif ( $request->action == 'save_translation' ) {
				$record = array();
				$record['slug'] = str_random(40);
				$record['content_id'] = $request->content_id;
				$record['conten_type'] = 'lesson';
				$record['user_id'] = $request->user_id;
				$record['full_name'] = $request->full_name;
				$record['email'] = $request->email;
				$record['description'] = $request->description;
				$record['url'] = $_SERVER['HTTP_REFERER'];
				$record['user_agent'] = $request->header('User-Agent');
				$record['ip_address'] = $request->ip();
				$record['type'] = 'translation';
				$ret = DB::table( 'translation_siteissues' )->insert( $record );
				$message = getPhrase('We received your request we will get back to you soon');
				return json_encode( array( 'status' => '1', 'message' => $message ) );
			} elseif ( $request->action == 'save_siteissue' ) {
				$record = array();
				$record['slug'] = str_random(40);
				if ( Auth::check() ) {
					$record['user_id'] = Auth::User()->id;
					$record['full_name'] = Auth::User()->name;
					$record['email'] = Auth::User()->email;
				} else {
					$record['user_id'] = 0;
					$record['full_name'] = $request->full_name;
					$record['email'] = $request->email;
				}
				$record['description'] = $request->issue_description;
				$record['url'] = $_SERVER['HTTP_REFERER'];
				$record['user_agent'] = $request->header('User-Agent');
				$record['ip_address'] = $request->ip();
				$record['type'] = 'siteissue';
				$ret = DB::table( 'translation_siteissues' )->insert( $record );
				$message = getPhrase('We received your request we will get back to you soon');
				return json_encode( array( 'status' => '1', 'message' => $message ) );
			}
			// $id, $content_type = '', $type = 'content', $user_id = '', $course_id = '', $module_id = ''
			
			
			$is_completed = is_lesson_piece_completed( $request->content_id, $course_id, $module_id ); // To change the overall status icon.
			if ( $is_completed ) {
				$is_completed = 'yes';
			} else {
				$is_completed = 'no';
			}
			return json_encode( array( 'status' => '1', 'message' => $message, 'is_completed' => $is_completed, 'type' => $type, 'type_sub' => $type_sub  ) );
		} else {
			if ( $request->action == 'save_translation' ) {

				$record = array();
				$record['slug'] = str_random(40);
				$record['content_id'] = $request->content_id;
				$record['conten_type'] = 'lesson';
				$record['user_id'] = $request->user_id;
				$record['full_name'] = $request->full_name;
				$record['email'] = $request->email;
				$record['description'] = $request->description;
				$record['url'] = $_SERVER['HTTP_REFERER'];
				$record['user_agent'] = $request->header('User-Agent');
				$record['ip_address'] = $request->ip();
				$record['type'] = 'translation';
				$ret = DB::table( 'translation_siteissues' )->insert( $record );
				$message = getPhrase('We received your request we will get back to you soon');
				return json_encode( array( 'status' => '1', 'message' => $message ) );
			}

			if ( $request->action == 'save_siteissue' ) {
				$record = array();
				$record['slug'] = str_random(40);
				if ( Auth::check() ) {
					$record['user_id'] = Auth::User()->id;
					$record['full_name'] = Auth::User()->name;
					$record['email'] = Auth::User()->email;
				} else {
					$record['user_id'] = 0;
					$record['full_name'] = $request->full_name;
					$record['email'] = $request->email;
				}
				$record['description'] = $request->issue_description;

				$record['url'] = $request->issue_url;
				$record['user_agent'] = $request->header('User-Agent');
				$record['ip_address'] = $request->ip();
				$record['type'] = 'siteissue';
				$ret = DB::table( 'translation_siteissues' )->insert( $record );
				$message = getPhrase('We received your request we will get back to you soon');
				return json_encode( array( 'status' => '1', 'message' => $message ) );
			}
		}
     }

	 public function getData( Request $request )
     {
		if ( $request->action == 'fetch_lessons' ) {
			$data = array();
			$slug = $request->slug;
			$record = LmsSeries::getRecordWithSlug($slug);
			$data['item']      = $record;
			$data['category'] = QuizCategory::getRecordWithId( $record->lms_category_id );
			$data['contents']     = $record->getContents();
			$data['title']        = $record->title;
			$data['sub_title']    = '';
			if ( ! empty( $record->sub_title ) ) {
				$data['sub_title'] = $record->sub_title;
			}

		$data['right'] = 'no';
			$html = view('lms-forntview.lessons-modal-content')->with( $data )->render();
			return response()->json( array( 'html' => $html ) );
		} elseif ( $request->action == 'fetch_recommended' ) {
			$data = array();
			/*
			$serieses   = LmsSeries::where('status','=','active')
				->orderBy( 'display_order', 'asc' )
				->orderBy( 'updated_at', 'desc' )
				->get(); */
			$serieses   = LmsSeries::select('lmsseries.*', 's.color_class')
						->join( 'quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id' )
						->join( 'subjects AS s', 's.id', '=', 'lmsseries.subject_id' )
						->where('lmsseries.status','=','active')
						->where('qc.category_status','=','active')
						->where( 'parent_id', '=', 0 );
			$serieses = $serieses->orderBy( 'title', 'asc')->paginate( FRONT_PAGE_LENGTH );

			$data['contents'] = $serieses;
			$data['title']        = getPhrase( 'Recommended Courses' );
			$html = view('lms-forntview.recommended-courses-modal-content')->with( $data )->render();
			return response()->json( array( 'html' => $html ) );
		} elseif ( $request->action == 'fetch_messages' ) {
			$currentUserId = Auth::user()->id;
			$threads = Thread::forUserWithNewMessages($currentUserId)->latest('updated_at')->paginate( FRONT_PAGE_LENGTH );
			$data['currentUserId']= $currentUserId;
			$data['threads'] 	  = $threads;
			$data['title']        = getPhrase('create_message');

			$html = view('lms-forntview.messages-modal')->with( $data )->render();
			return response()->json( array( 'html' => $html ) );
		} elseif ( $request->action == 'fetch_coachform' ) {
			$currentUserId = Auth::user()->id;
			$threads = Thread::forUser($currentUserId)->latest('updated_at')->paginate( FRONT_PAGE_LENGTH );
			$data['currentUserId']= $currentUserId;
			$data['threads'] 	  = $threads;
			$data['title']        = getPhrase('create_message');

			$html = view('lms-forntview.coachform-modal')->with( $data )->render();
			return response()->json( array( 'html' => $html ) );
		} elseif ( $request->action == 'withdraw_coachform' ) {			
			$data['title']        = getPhrase('withdraw_coach_request');
			$html = view('lms-forntview.withdraw-coachform-modal')->with( $data )->render();
			return response()->json( array( 'html' => $html ) );
		} elseif ( $request->action == 'add_friend' ) {
			$currentUserId = Auth::user()->id;
			$threads = Thread::forUser($currentUserId)->latest('updated_at')->paginate( FRONT_PAGE_LENGTH );
			$data['currentUserId']= $currentUserId;
			$data['threads'] 	  = $threads;
			$data['title']        = getPhrase('create_message');

			$html = view('lms-forntview.add-friend-modal')->with( $data )->render();
			return response()->json( array( 'html' => $html ) );
		} elseif ( $request->action == 'fetch_courses' ) {
			$data = array();
			$slug = $request->slug;
			$category   = QuizCategory::where('slug','=',$slug)->first();
			$serieses   = LmsSeries::where('lms_category_id','=',$category->id)->get();
			$data['category']   = $category;
			$data['serieses']    = $serieses;
			ob_start();
			?>
			<div class="row mt-1">
			<?php
			foreach($serieses as $series) {
				?>
				<div class="col-sm-6 col-md-4 mb-4">
					<div class="card h-100 text-center">
						<?php if( $series->image != '' ) : ?>
						<img class="card-img-top" src="<?php echo IMAGE_PATH_UPLOAD_LMS_SERIES.$series->image; ?>" alt="">
						<?php else : ?>
						<img class="card-img-top" src="<?php echo IMAGE_PATH_UPLOAD_LMS_DEFAULT; ?>" alt="">
						<?php endif; ?>
						<div class="card-body">
							<h4 class="card-title">
								<?php /* ?>
								<a class="text-green" href="<?php echo URL_FRONTEND_LMSLESSON . $series->slug; ?>"><?php echo $series->title; ?></a>
								<?php */ ?>
								<a class="text-green" href="<?php echo URL_FRONTEND_COURSE_LIST . $slug; ?>"><?php echo $series->title; ?></a>
							</h4>
							<?php if ( ! empty( $series->sub_title ) ) : ?>
							<h6 class="card-subtitle mb-2 text-green"><?php echo $series->sub_title; ?></h6>
							<?php endif; ?>
							<p class="card-text"><?php echo $series->short_description; ?></p>
						</div>
						<?php
						$contents = App\LmsSeries::getAllContents( $series->id );
						if ( $contents->count() > 0 ) {
							$total_contents = $contents->count();
							$completed = 0;
						?>
						<div class="card-footer">
							<ul class="course-finished-path">
								<?php
								foreach( $contents as $content ) :
								$class = '';
								if ( is_completed( $content->id ) ) {
									$class = 'completed';
									$completed++;
								}
								?>
								<li class="<?php echo $class; ?>"><i class="icon icon-pointer-white" title="<?php echo $content->title; ?>"></i></li>
								<?php endforeach; ?>
							</ul>
						</div>
						<?php } ?>
					</div>
				</div>
				<?php
			}
			?>
			</div>
			<?php
			$str = ob_get_clean();
			return json_encode( array( 'html' => $str ) );
		} elseif ( $request->action == 'sharecontent' ) {
			ob_start();
			$lesson = LmsContent::where( 'id', '=', $request->content_id )->first();
			if ( $lesson ) {
				$course = LmsSeries::where( 'id', '=', $request->course )->first();

				$title = $lesson->title;
				$link = $_SERVER['HTTP_REFERER'];
				$site_title = getSetting('site_title','site_settings');
				if ( $course ) {
					$link = URL_FRONTEND_LMSSINGLELESSON . $course->slug . '/' . $lesson->slug;
				}
				$video_background_image = IMAGES . '900x400.png';
                if ( ! empty( $lesson->video_background_image ) && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $lesson->video_background_image ) ) {
                    $video_background_image = IMAGE_PATH_UPLOAD_LMS_CONTENTS . $lesson->video_background_image;
                }
			?>
			<ul class="socialshare">
				<li><a href="https://twitter.com/intent/tweet?text=<?php  echo htmlspecialchars( urlencode( html_entity_decode( $title, ENT_COMPAT, 'UTF-8' ) ), ENT_COMPAT, 'UTF-8' ) . '&url=' . urlencode( $link ) . '&via=' . urlencode( $site_title ); ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-twitter"></i></a></li>

				<li><a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode( $link ); ?>&title=<?php echo urlencode( $title ); ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-facebook"></i></a></li>

				<li><a href="http://pinterest.com/pin/create/button/?url=<?php echo $link . '&amp;media=' . $video_background_image . '&description=' . urlencode( $title ); ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-pinterest"></i></a></li>


				<li><a href="http://plus.google.com/share?url=<?php echo  $link; ?>" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0' ); return false;"><i class="fa fa-google-plus"></i></a></li>

			</ul>
			<?php
			} else {
				echo getPhrase( 'Wrong Operation' );
			}
			$str = ob_get_clean();
			return json_encode( array( 'html' => $str ) );
		} elseif ( $request->action == 'get_groupcomments' ) {
			$data['title']        = getPhrase('withdraw_coach_request');
			$data['item_id'] = $request->group_id;
			$group = DB::table('lmsgroups')->select('lmsgroups.*', 'users.slug AS user_slug')
			->join('users', 'users.id', '=', 'lmsgroups.user_id')
			->where('lmsgroups.id', '=', $request->group_id)->first();
			$data['group_slug'] = $group->slug;
			$data['user_slug'] = $group->user_slug;
			$data['group_owner'] = $group->user_id;
			$html = view('lms-groups.comments-modal')->with( $data )->render();
			return response()->json( array( 'html' => $html ) );
		} else {
		$records = array();
		if ( $request->action == 'get_groupcomments' ) {
			if ( Auth::check() ) {
				$records = App\LmsComments::select( ['lmscontents_comments.*', 'users.image', 'users.name'] )
				->join( 'users', 'users.id', '=', 'lmscontents_comments.user_id' )
						// ->where( 'user_id', '=', Auth::User()->id )
						->where( 'group_id', '=', $request->group_id )
						->where('type', '=', 'groupcomments')
						->orderBy( 'lmscontents_comments.created_at', 'desc' )
						->get();
			}
		} elseif ( $request->action == 'notes' ) {
			if ( Auth::check() ) {
				$records = App\LmsComments::select( ['lmscontents_comments.*', 'users.image', 'users.name'] )
				->join( 'users', 'users.id', '=', 'lmscontents_comments.user_id' )
						->where( 'user_id', '=', Auth::User()->id )
						->where( 'content_id', '=', $request->content_id )
						->where('type', '=', $request->action)
						->orderBy( 'lmscontents_comments.created_at', 'desc' )
						->get();
			}
		} else {
			$records = App\LmsComments::select( ['lmscontents_comments.*', 'users.image', 'users.name'] )
			->join( 'users', 'users.id', '=', 'lmscontents_comments.user_id' )
			->where('type', '=', $request->action)
			->where( 'content_id', '=', $request->content_id )
			->orderBy( 'lmscontents_comments.created_at', 'desc' )
			->get();
		}

		if ( 'comments' === $request->action ) {
			$data['title']        = getPhrase('comments');
			
			$page_name = $request->page_name;
			if ( empty( $page_name )) {
				$page_name = 'lessons';
			}
			$content_id = $request->content_id;
			if ( empty( $group_id ) ) {
				$group_id = 0;
			}
			$data['page_name'] = $page_name;
			$data['course_id'] = $request->course_id;
			$data['module_id'] = $request->module_id;
			$content = LmsContent::where('id', '=', $content_id)->first();
			$data['content_slug'] = $content->slug;
			$data['item_id'] = $content_id;
			$html = view('lms-forntview.other-views.comments-list')->with( $data )->render();
			return response()->json( array( 'html' => $html, 'group_id' => $group_id, 'content_id' => $content_id ) );
		} else {
		$content_id = $request->content_id;
		$group_id = 0;
			$str = '';
			if ( ! empty( $records ) ) {
				$str = '<ul class="ag-media-list">';
				if ( $records->count() > 0 ) :
				foreach ( $records as $record ) {
					$image = getProfilePath($record->image);
					$str .= '<li class="media"><img class="d-flex mr-3 icn-size" src="'.$image.'"   title="'.$record->name.'"/>&nbsp;' . $record->comments_notes . '&nbsp;<small>'.$record->created_at->diffForHumans().'</small></li>';
				}
				else :
				if ( $request->action == 'comments' ) {
					$str .= '<li class="media">'.getPhrase('No Comments').'</li>';
				}
				endif;
				$str .= '</ul>';
			}
			return json_encode( array( 'html' => $str, 'group_id' => $group_id, 'content_id' => $content_id ) );
		}
		}
	 }

	public function getReturnUrl()
    {
    	if ( array_key_exists('HTTP_REFERER', $_SERVER ) ) {
			return $_SERVER["HTTP_REFERER"];
		} else {
			return URL_FRONTEND_LMSCATEGORIES;
		}
    }

	public function myCourses( $slug = '' )
	{
		if ( ! Auth::check() ) {
			prepareBlockUserMessage();
			return back();
		}

		if ( ! in_array( $slug, array( 'running', 'completed' ) ) ) {
			if ( ! empty( $slug ) ) {
				$record = DB::table( 'lmsseries AS course' )->select( 'my_course.*' )->
				join( 'users_my_courses AS my_course', 'course.id', '=', 'my_course.course_id' )
				->where('course.slug', $slug)->first();
				if ( $record ) {
					DB::table( 'users_completed_courses' )->where( 'course_id', '=', $record->course_id )->where( 'user_id', '=', Auth::User()->id )->delete();

					DB::table( 'lmscontents_track' )->where( 'course_id', '=', $record->course_id )->where( 'user_id', '=', Auth::User()->id )->delete();

					DB::table( 'users_my_courses' )->where( 'course_id', '=', $record->course_id )->where( 'user_id', '=', Auth::User()->id )->delete();

					flash('Success', getPhrase('course_removed_successfully'), 'success');
					return redirect( URL_STUDENT_MY_COURSES );
				}
			}
		}

		$user = Auth::user();
		$data['user'] = $user;
		/*
		$data['my_courses'] = App\MyCourses::select(['lmsseries.*', 'subjects.subject_title', 'subjects.color_class'])
		->join( 'lmsseries', 'lmsseries.id', '=', 'users_my_courses.course_id' )
		->join( 'subjects', 'subjects.id', '=', 'lmsseries.subject_id' )
		->where('lmsseries.parent_id', '=', 0)
		->where( 'lmsseries.status', '=', 'active' )
		->where('user_id', '=', $user->id )
		->orderBy('users_my_courses.created_at', 'asc')
		->paginate( FRONT_PAGE_LENGTH );
		*/
		if ( 'running' === $slug ) {
			$data['my_courses'] = attempted_courses_new( 'records',
			array( 'paginate' => FRONT_PAGE_LENGTH,
					'order_by' => array(
								'column' => 'users_my_courses.created_at',
								'order' => 'asc'
							),
					'course_status' => 'running',
			)
			);
		} elseif ( 'completed' === $slug ) {
			$data['my_courses'] = attempted_courses_new( 'records',
			array( 'paginate' => FRONT_PAGE_LENGTH,
					'order_by' => array(
								'column' => 'users_my_courses.created_at',
								'order' => 'asc'
							),
					'course_status' => 'completed',
			)
			);
		} else {
		$data['my_courses'] = attempted_courses_new( 'records',
			array( 'paginate' => FRONT_PAGE_LENGTH,
					'order_by' => array(
								'column' => 'users_my_courses.created_at',
								'order' => 'asc'
							)
			)
			);
		}
		$data['title'] = getPhrase('my_courses');
		$data['slug'] = $slug;
		return view('lms-forntview.my-courses', $data);
	}

	public function subjectCourses( $type = 'pathway', $slug )
	{
		if( empty( $slug ) ) {
			prepareBlockUserMessage();
			return back();
		}
		if ( 'category' === $type ) {
			$subject_details = App\QuizCategory::where( 'category_status', '=', 'active' )->where('slug', '=', $slug)->first();
		} elseif ( 'author' === $type ) {
			$subject_details = App\User::where( 'status', '=', 'activated' )->where('slug', '=', $slug)->first();
		} else {
			$subject_details = App\Subject::getRecordWithSlug( $slug );
		}
		if( empty( $subject_details ) ) {
			return back();
		}
		$user = Auth::user();
		$data['user'] = $user;
		$data['subject'] = $subject_details;
		$data['title'] = $subject_details->subject_title . ' ' . getPhrase( 'courses' );
		if ( 'category' === $type ) {
			/**
			 * Here the '$subject_details' will be the category record
			 */
		$data['subject_courses'] =	LmsSeries::select( 'lmsseries.*', 'qc.category', 'users.name AS author', 'subjects.subject_title' )
		->join( 'quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id' )
		->join( 'users', 'users.id', '=', 'lmsseries.created_by' )
		->join( 'subjects', 'lmsseries.subject_id', '=', 'subjects.id' )
		->where( 'qc.category_status', '=', 'active' )
		->where( 'lmsseries.status', '=', 'active' )
		->where( 'lmsseries.parent_id', '=', 0 )
		->where('lmsseries.lms_category_id', '=', $subject_details->id )
		->paginate( FRONT_PAGE_LENGTH );
		} elseif ( 'author' === $type ) {
			/**
			 * Here the '$subject_details' will be the user record
			 */
			$data['subject_courses'] =	LmsSeries::select( 'lmsseries.*', 'qc.category', 'users.name AS author', 'subjects.subject_title' )
		->join( 'quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id' )
		->join( 'users', 'users.id', '=', 'lmsseries.created_by' )
		->join( 'subjects', 'lmsseries.subject_id', '=', 'subjects.id' )
		->where( 'qc.category_status', '=', 'active' )
		->where( 'lmsseries.status', '=', 'active' )
		->where( 'lmsseries.parent_id', '=', 0 )
		->where('lmsseries.created_by', '=', $subject_details->id )
		->paginate( FRONT_PAGE_LENGTH );
		} else {
			/*
			$data['subject_courses'] = LmsSeries::select(['lmsseries.*','subjects.subject_title' ])->
			join( 'subjects', 'lmsseries.subject_id', '=', 'subjects.id' )
		->where('lmsseries.subject_id', '=', $subject_details->id )->paginate( FRONT_PAGE_LENGTH );
		*/

		$data['subject_courses'] =	LmsSeries::select( 'lmsseries.*', 'qc.category', 'users.name AS author', 'subjects.subject_title' )
		->join( 'quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id' )
		->join( 'users', 'users.id', '=', 'lmsseries.created_by' )
		->join( 'subjects', 'lmsseries.subject_id', '=', 'subjects.id' )
		->where( 'qc.category_status', '=', 'active' )
		->where( 'lmsseries.status', '=', 'active' )
		->where( 'lmsseries.parent_id', '=', 0 )
		->where('lmsseries.subject_id', '=', $subject_details->id )
		->paginate( FRONT_PAGE_LENGTH );
		}

		return view('lms-forntview.subject-courses', $data);
	}

	public function recommendedCourses( $slug = '' )
	{
		if ( ! Auth::check() ) {
			prepareBlockUserMessage();
			return back();
		}
		$data['category'] = FALSE;
		if ( ! empty( $slug ) ) {
			$category = QuizCategory::getRecordWithSlug( $slug );
			if ( $category->category_status == 'active' ) {
				$data['category'] = $category;
			} else {
				prepareBlockUserMessage( 'category_not_active' );
				return back();
			}
		}
		$query_string = \Request::getQueryString();
		$search = '';
		if (strpos($query_string, 's=') !== false) {
			$search = str_replace( 's=', '', \Request::getQueryString() );
		}
		$data['search'] = $search;
		if ( Auth::check() ) {
			$mycourses = array_pluck( App\MyCourses::where( 'user_id', '=', Auth::User()->id )->get(), 'course_id', 'course_id' );
			$mycourses_array = array();
			if ( ! empty( $mycourses ) ) {
				foreach( $mycourses as $key => $val ) {
					array_push($mycourses_array, $val );
				}
			}

			$serieses  = LmsSeries::select('lmsseries.*', 's.color_class')
			->join( 'quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id' )
			->join( 'subjects AS s', 's.id', '=', 'lmsseries.subject_id' )
			->where('lmsseries.status','=','active')
			->where('qc.category_status','=','active')
				->whereNotIn( 'lmsseries.id', $mycourses_array )
				->where( 'parent_id', '=', 0 );
			if ( ! empty( $search ) ) {
				$serieses = $serieses->where( 'title', 'like', "%$search%");
			}
			$serieses = $serieses->orderBy( 'title', 'asc')
				->paginate( FRONT_PAGE_LENGTH );
		} else {
		$serieses   = LmsSeries::select('lmsseries.*', 's.color_class')
		->join( 'quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id' )
		->join( 'subjects AS s', 's.id', '=', 'lmsseries.subject_id' )
		->where('lmsseries.status','=','active')
		->where('qc.category_status','=','active')
		->where( 'parent_id', '=', 0 );
		if ( ! empty( $search ) ) {
				$serieses = $serieses->where( 'title', 'like', "%$search%");
			}
		$serieses = $serieses->orderBy( 'title', 'asc')->paginate( FRONT_PAGE_LENGTH );
		}
		if ( $serieses->count() == 0 ) {
			// flash('Ooops...!', 'No other courses available for now', 'error');
			// back();
		}

		$data['contents'] = $serieses;
		$data['title'] = getPhrase('Recommended Courses');
		return view('lms-forntview.recommended-courses', $data);
	}
	/**
	 * Course Category List Page (3b)
	 */
	public function courseList( $slug )
	{
		$data = array();
		$record = QuizCategory::getRecordWithSlug($slug);
		$data['item']      = $record;
		$data['title']        = $record->category . ' ' . getPhrase('Course List');
		$data['courses'] = LmsSeries::select(['lmsseries.*', 'subjects.color_class', 'subjects.subject_title', 'quizcategories.slug AS catslug', 'quizcategories.category'])
		->join('quizcategories', 'quizcategories.id','=', 'lmsseries.lms_category_id' )
		->join('subjects', 'subjects.id','=', 'lmsseries.subject_id' )
		->orderBy( 'lmsseries.display_order', 'asc' )
		->where( 'lmsseries.status', '=', 'active' )
		->where( 'lmsseries.parent_id', '=', '0' )
		->where( 'lmsseries.lms_category_id', '=', $record->id );

		$data['subjects'] = Subject::get();

        $data['layout']      = getLayout();
     	return view('lms-forntview.course-list',$data);
	}

	public function inviteOtherFriends()
	{
		if ( ! Auth::check() ) {
			flash('Ooops...!', 'Please login to access this page', 'error');
			return redirect( URL_USERS_LOGIN );
		}
		$data['title'] = getPhrase('add_a_friend');
		$data['layout']      = getLayout();
     	return view('lms-forntview.invite-other-friends',$data);
	}

	public function sendMailOtherFriends( Request $request )
	{
		$rules = [
         'recipients'          	   => 'bail|required',
		 // 'subject'          	   => 'bail|required',
		 'message'          	   => 'bail|required',
         ];
        $this->validate($request, $rules);
		$emails = $request->recipients;
		if ( ! empty( $emails ) ) {
			$emails = explode(',', $emails);
			if ( ! empty( $emails ) ) {
				foreach( $emails as $email )
				{
					if (!filter_var(trim( $email ), FILTER_VALIDATE_EMAIL) === false) {
						$user = Auth::User();
						// Let us send and notification email to user set in profile email addresses
						sendEmail('invitation email', array('sender_name' => $user->name, 'to_email' => $email, 'custom_message' => $request->message, 'register_url' => URL_USERS_REGISTER, 'login_url' => URL_USERS_LOGIN ));
					}
				}
				flash('Success...!', 'Invitation sent successfully', 'success');
				return redirect( URL_USERS_DASHBOARD );
			} else {
				flash('Ooops...!', 'No Emails found to send invitation', 'error');
			return redirect( URL_USERS_DASHBOARD );
			}
		} else {
			flash('Ooops...!', 'No Emails found to send invitation', 'error');
			return redirect( URL_USERS_DASHBOARD );
		}
	}

	/**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getCourses( $category_slug )
    {
        $data = array();
		$record = QuizCategory::getRecordWithSlug( $category_slug );

		if( ! $record ) {
			$message = getPhrase( 'Record not valid' );
			$redirect_to = $_SERVER['HTTP_REFERER'];
			return json_encode( array( 'status' => '0', 'reason' => 'not_valid', 'message' => $message, 'redirect_to' => $redirect_to ) );
		}
		$data['title'] = getPhrase( 'courses' );
		$data['record'] = $record;
		$data['layout']    = 'layouts.full-width-no-menu';
		$data['category'] = $category_slug;
		$html = view('lms-forntview.other-views.list')->with( $data )->render();
		return response()->json( array( 'html' => $html ) );
    }


	public function getCoursesList( $category_slug )
	{
        $records = array();

		$records = LmsSeries::join('quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id')
		->join('subjects', 'subjects.id', '=', 'lmsseries.subject_id')
		->select(['lmsseries.title', 'lmsseries.sub_title', 'lmsseries.image', 'lmsseries.total_items','lmsseries.slug', 'lmsseries.id', 'lmsseries.updated_at', 'lmsseries.lms_category_id', 'lmsseries.subject_id', 'lmsseries.parent_id', 'qc.category', 'subjects.subject_title', 'lmsseries.privacy' ])
		->where( 'qc.slug', '=', $category_slug )
		->where( 'parent_id', '=', '0' )
		->where( 'lmsseries.status', '=', 'active' )
		->where( 'qc.category_status', '=', 'active' )
		->orderBy('updated_at', 'desc');


        return Datatables::of($records)
        ->editColumn('title', function($records)
        {
        	$url = URL_FRONTEND_LMSLESSON . $records->slug;
			$modules = LmsSeries::where( 'parent_id', '=', $records->id )->count();
			if( $modules > 0 ) {
				$url = URL_FRONTEND_LMSSERIES . $records->slug;
			}
			$url = URL_FRONTEND_LMSSERIES . $records->slug;
			if ( $records->privacy == 'loginrequired' && ! Auth::check() ) {
				$str = '<a href="#" onclick="open_login_modal(\'' . $url . '\')">' . $records->title . '&nbsp;<i class="fa fa-lock" aria-hidden="true"></i></a>';
			} else {
				$str = '<a href="' . $url . '">' . $records->title . '</a>';
			}
			if ( $records->sub_title != '' ) {
				$str .= '<br><small>' . $records->sub_title . '</small>';
			}
			$cat = '<br>' . getPhrase( 'category: ' ) . $records->category;
			$sub = '<br>' . getPhrase( 'pathway: ' ) . $records->subject_title;
			return $str . $sub . $cat;
        })
        ->editColumn('image', function($records)
        {
          $image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
          if($records->image)
            $image_path = IMAGE_PATH_UPLOAD_LMS_SERIES.$records->image;

            return '<img src="' . $image_path . '" height="60" width="60"  />';
        })
        ->editColumn('total_items', function( $records )
        {
		   if ( $records->total_items > 0 ) {
			   if ( $records->privacy == 'loginrequired' && ! Auth::check() ) {
					$str = getPhrase( 'lessons: ' ) . '<a href="#" onclick="open_login_modal(\'' . URL_FRONTEND_LMSSERIES . $records->slug . '\')">' . $records->total_items . '</a>';
			   } else {
					$str = getPhrase( 'lessons: ' ) . '<a href="' . URL_FRONTEND_LMSSERIES . $records->slug . '">' . $records->total_items . '</a>';
			   }

			   $modules = LmsSeries::where( 'parent_id', '=', $records->id )->where( 'status', '=', 'active' )->count();
			   if ( $modules > 0 ) {
				if ( $records->privacy == 'loginrequired' && ! Auth::check() ) {
					$str .= '<br>' . getPhrase( 'modules: ' ) . '<a href="#" onclick="open_login_modal(\'' . URL_FRONTEND_LMSSERIES . $records->slug . '\')">' . $modules . '</a>';
				} else {
				$str .= '<br>' . getPhrase( 'modules: ' ) . '<a href="' . URL_FRONTEND_LMSSERIES . $records->slug . '">' . $modules . '</a>';
				}
			   }
		   } else {
			   $modules = LmsSeries::where( 'parent_id', '=', $records->id )->where( 'status', '=', 'active' )->count();
			   if ( $records->privacy == 'loginrequired' && Auth::check() ) {
				$str = getPhrase( 'modules: ' ) . '<a href="#" onclick="open_login_modal(\'' . URL_FRONTEND_LMSSERIES . $records->slug . '\')">' . $modules . '</a>';
			   } else {
				$str = getPhrase( 'modules: ' ) . '<a href="' . URL_FRONTEND_LMSSERIES . $records->slug . '">' . $modules . '</a>';
			   }
		   }

		   return $str;
        })

        ->removeColumn( 'id' )
		->removeColumn( 'sub_title' )
        ->removeColumn( 'slug' )
        ->removeColumn( 'updated_at' )
		->removeColumn( 'lms_category_id' )
		->removeColumn( 'subject_id' )
		->removeColumn( 'parent_id' )

		->removeColumn( 'category' )
		->removeColumn( 'subject_title' )
        ->make();
	}

	// For Serieses Start
	public function getSerieses( $category_slug )
	{
		$data = array();
		$record = QuizCategory::getRecordWithSlug( $category_slug );

		if( ! $record ) {
			$message = getPhrase( 'Record not valid' );
			$redirect_to = $_SERVER['HTTP_REFERER'];
			return json_encode( array( 'status' => '0', 'reason' => 'not_valid', 'message' => $message, 'redirect_to' => $redirect_to ) );
		}
		$data['title'] = getPhrase( 'Serieses' );
		$data['layout']    = 'layouts.full-width-no-menu';
		$data['category'] = $category_slug;
		$html = view('lms.lmsseries_master.list')->with( $data )->render();
		return response()->json( array( 'html' => $html ) );
	}

	public function showCourses( $category_slug, $series_slug = '' )
     {
     	$category   = QuizCategory::where('slug','=',$category_slug)->where( 'category_status', '=', 'active' )->first();

		if( ! $category ) {
    		flash('Ooops...!', getPhrase("category_not_found"), 'error');
			return redirect( URL_FRONTEND_LMSCATEGORIES );
		}
		$data['category']  = $category;
		$data['subjects']  = Subject::all();
		$data['layout']    = getLayout();

		if ( ! empty( $series_slug ) ) { // If the Series Slug is available we will show Courses
			$series   = App\LmsSeriesMaster::where('slug', '=', $series_slug)->first();
			if( ! $series ) {
				flash('Ooops...!', getPhrase("series_not_found"), 'error');
				return redirect( URL_FRONTEND_LMSCATEGORIES );
			}
			$courses   = LmsSeries::select(['lmsseries.*', 's.color_class', 's.subject_title'])->join('subjects AS s', 's.id', '=', 'lmsseries.subject_id')->where('lms_category_id','=',$category->id)
			->where( 'lms_series_master_id', '=', $series->id )
			->paginate( FRONT_PAGE_LENGTH );
			$data['item']    = $series;
			$data['series']    = $series;
			$data['breadcrumb_title'] = getPhrase( 'courses' );
			$data['title']  = getPhrase( 'courses' );
			$data['courses']   = $courses;
			return view('lms-forntview.newviews.courses',$data);
		} else { // Let us display Serieses
			$data['breadcrumb_title'] = getPhrase( 'serieses' );
			$data['title']  = getPhrase( 'category:' ) . $category->category . '->' . getPhrase( 'serieses' );
			$serieses   = App\LmsSeriesMaster::select(['lmsseries_master.*', 's.color_class', 's.subject_title'])->join('subjects AS s', 's.id', '=', 'lmsseries_master.subject_id')->where('lms_category_id','=',$category->id)
			->paginate( FRONT_PAGE_LENGTH );

			$data['serieses']   = $serieses;
			$data['item']    = $category;
			return view('lms-forntview.newviews.serieses',$data);
		}
     }

	 public function showLessonsModules( $course_slug )
	 {
		$data = array();
		$record = LmsSeries::getRecordWithSlug( $course_slug );
		if ( ! $record ) {
			flash('Ooops...!', getPhrase("course_not_found"), 'error');
			return redirect( URL_FRONTEND_LMSCATEGORIES );
		}
		$category   = QuizCategory::where('id','=',$record->lms_category_id)->first();
		if( ! $category ) {
    		flash('Ooops...!', getPhrase("category_not_found"), 'error');
			return redirect( URL_FRONTEND_LMSCATEGORIES );
		}
		$series   = App\LmsSeriesMaster::where('id', '=', $record->lms_series_master_id)->first();
		if( ! $series ) {
    		flash('Ooops...!', getPhrase("series_not_found"), 'error');
			return redirect( URL_FRONTEND_LMSCATEGORIES );
		}
		$data['item']      = $record;
		$data['course'] = $record;
		$data['category'] = $category;
		$data['series'] = $series;
		$data['contents']     = $record->getContents();
		$modules = LmsSeries::where('parent_id', '=', $record->id )->get();
		$data['modules'] = $modules;
		$data['title']        = getPhrase( 'lessons' );
		if ( $modules->count() > 0  ) {
			$data['title']        = getPhrase( 'lessons and modules' );
		}
		$data['sub_title']    = '';
		if ( ! empty( $record->sub_title ) ) {
			$data['sub_title'] = $record->sub_title;
		}
		$data['operation']      = 'modules_lessons';
        $data['layout']      = getLayout();

		return view('lms-forntview.newviews.coursemodules-lessons',$data);
	 }
	 // For Serieses End

	 public function sendTranslationRequest( $type = 'lesson', $id = '' )
	 {
		$data['lessons'] = array();
		if ( $type == 'post' ) {
			$data['lessons'] = array_pluck( \Corcel\Model\Post::type('post')->published()->newest()->get(), 'post_title', 'ID' );
		} else {
			$data['lessons'] = array_pluck( LmsContent::all(), 'title', 'id' );
		}
		$data['type'] = $type;
		$data['selected'] = $id;
		$data['title'] = getPhrase( 'send_translation_request' );
		$data['layout']      = getLayout();
		return view('lms-forntview.send-translation-request',$data);
	 }

	 public function processTranslationRequest(Request $request)
	 {
		 $rules = [
			'lesson'          	   => 'required',
         ];
		 if ( ! Auth::check() ) {
			 $rules['full_name'] = 'required|max:60';
			 $rules['email'] = 'required|email';
		 }
		 $rules['message'] = 'required';
        $this->validate($request, $rules);
		$record = array();
		$record['slug'] = str_random(40);
		$record['content_id'] = $request->lesson;
		$record['conten_type'] = $request->conten_type;
		if ( Auth::check() ) {
			$record['user_id'] = Auth::User()->id;
			$record['full_name'] = Auth::User()->name;
			$record['email'] = Auth::User()->email;
		} else {
			$record['user_id'] = 0;
			$record['full_name'] = $request->full_name;
			$record['email'] = $request->email;
		}
		$record['description'] = $request->message;
		$record['url'] = $_SERVER['HTTP_REFERER'];
		$record['user_agent'] = $request->header('User-Agent');
		$record['ip_address'] = $request->ip();
		$record['type'] = 'translation';
		$ret = DB::table( 'translation_siteissues' )->insert( $record );
		flash('Success...!', 'request sent successfully', 'success');
		return redirect( $_SERVER['HTTP_REFERER'] );
	 }

	 public function startCourse( Request $request )
	 {

		$start_course_slug = $request->start_course_slug;
		 if ( $start_course_slug == '' ) {
			 flash('Ooops...!', getPhrase("wrong_operation"), 'error');
			return redirect( URL_FRONTEND_LMSCATEGORIES );
		 }

		 $record = LmsSeries::getRecordWithSlug( $start_course_slug );

		 if ( ! $record ) {
			flash('Ooops...!', getPhrase("course_not_found"), 'error');
			return redirect( URL_FRONTEND_LMSCATEGORIES );
		}

		$category   = QuizCategory::where('id','=',$record->lms_category_id)->first();
		if( ! $category ) {
    		flash('Ooops...!', getPhrase("category_not_found"), 'error');
			return redirect( URL_FRONTEND_LMSCATEGORIES );
		}

		if ( ! empty( $request->recipients ) ) {
			$thread = Thread::create( [
					'subject' => getPhrase( 'course_started' ),
				]
			);

			// Message
			$message = 'Please join with me for the course <b><a href="' .URL_FRONTEND_LMSSERIES . $record->slug . '" title="' . $record->title . '">'.$record->title.'</a></b>';
			Message::create(
				[
					'thread_id' => $thread->id,
					'user_id'   => Auth::user()->id,
					'body'      => $message,
				]
			);

			// Sender
			Participant::create(
				[
					'thread_id' => $thread->id,
					'user_id'   => Auth::user()->id,
					'last_read' => new Carbon,
				]
			);

			$recipients = $request->recipients;
			if ( $request->has('notify_author') ) {
				$recipients[] = $record->created_by;
			}
			// Recipients
			$thread->addParticipant($recipients);
		}

		$check = App\MyCourses::where('user_id', '=', Auth::user()->id)->where( 'course_id', '=', $record->id );

		if ( $check->count() == 0 ) {
			$mycourserecord = new App\MyCourses();
			$mycourserecord->user_id = Auth::user()->id;
			$mycourserecord->course_id = $record->id;
			$mycourserecord->save();
		}
		return redirect( URL_FRONTEND_LMSSERIES . $record->slug );
	 }

	 public function searchCourse( Request $request )
	 {
		 $search = $request->s;
	 }

	 public function globalSearch( Request $request, $type = '', $slug = '' )
	 {
		$data['title'] = getPhrase( 'search:' );
		$data['layout']      = getLayout();

		$query_string = \Request::getQueryString();
		if ($request->isMethod('post')) {
			$search_term = $request->s;
			$is_posts = $request->posts;
			$is_courses = $request->courses;
			$is_series = $request->series;
			$is_articles = $request->articles;
		} else {
			$search_term = Input::get('s', false);
			$is_posts = Input::get('posts', false);
			$is_courses = Input::get('courses', false);
			$is_series = Input::get('series', false);
			$is_articles = Input::get('articles', false);
		}
		if ( ! $search_term && ! $is_posts && ! $is_courses && ! $is_series && ! $is_articles ) {
			$is_posts = $is_courses = $is_series = $is_articles = 'true';
		}

		if ( $is_posts == 'true' ) {
			$is_posts = TRUE;
		}
		if ( $is_courses == 'true' ) {
			$is_courses = TRUE;
		}
		if ( $is_series == 'true' ) {
			$is_series = TRUE;
		}
		if ( $is_articles == 'true' ) {
			$is_articles = TRUE;
		}

		if ( gettype( $search_term ) === 'NULL' ) {
			// $is_posts = $is_courses = $is_series = $is_articles = TRUE;
		}

		$page_items = '';

		$perPage = 5;
		$posts = '';
		if ( $is_posts || $is_articles ) {
			$posts = \Corcel\Model\Post::type('post')
			->published()
			->newest();
			if ( ! empty( $search_term ) ) {
				$posts = $posts->where( 'post_title', 'like', "%$search_term%" );
			}
			if ( ! empty( $type ) && ! empty( $slug ) ) {
				if ( $type == 'author' ) {
					$select_author = \Corcel\Model\User::where( 'user_login', '=', $slug )->first();
					if ( $select_author ) {
						$posts = $posts->where( 'post_author', '=', $select_author->ID );
					} else {
						$posts = $posts->where( 'post_author', '=', 0 ); // To say that author is not found in WP

					}
				}
				if ( $type == 'category' ) {
						$posts = $posts->taxonomy( 'category', $slug );
				}
			}
			if ( $is_articles && $is_posts ) {

			} else {
				if ( $is_articles ) {
					$posts = $posts->hasMeta(['custom_post_type' => 'article']);
				}
				if ( $is_posts ) {
					$posts = $posts->hasMeta(['custom_post_type' => 'post']);
				}
			}
			 $posts = $posts->orderBy( 'post_date', 'desc' )->paginate( $perPage / 2 );
		}

		$courses = '';
		if ( $is_courses ) {
			$courses = LmsSeries::select( 'lmsseries.*', 'qc.category', 'users.name AS author', 'users.username', 'qc.slug AS category_slug', 'lmsseries.created_at AS post_date' )
			->join( 'quizcategories AS qc', 'qc.id', '=', 'lmsseries.lms_category_id' )
			->join( 'users', 'users.id', '=', 'lmsseries.created_by' )
			->where( 'qc.category_status', '=', 'active' )
			->where( 'lmsseries.status', '=', 'active' )
			->where( 'lmsseries.parent_id', '=', 0 );
			if ( ! empty( $search_term ) ) {
				$courses = $courses->where( 'lmsseries.title', 'like', "%$search_term%" );
			}
			if ( ! empty( $type ) && ! empty( $slug ) ) {
				if ( $type == 'author' ) {
					$courses = $courses->where( 'users.username', 'like', "%$slug%" );
				}
				if ( $type == 'category' ) {
					$courses = $courses->where( 'qc.slug', 'like', "%$slug%" );
				}
			}
			$courseLength = ( $posts instanceof LengthAwarePaginator ) ? $perPage - count($posts->items()) : $perPage;
			// dd( $courses->toSql() );
			$courses = $courses->orderBy( 'created_at', 'desc' )->paginate( $courseLength );
		}
		if ( $posts instanceof LengthAwarePaginator && $courses instanceof LengthAwarePaginator ) {
			$page_items = $this->PaginationMerger( $posts, $courses );
		} else {
			if ( $courses == '' ) {
				$items = $posts;
			} else {
				$items = $courses;
			}
			if ( $items instanceof LengthAwarePaginator ) {
				$page_items = $this->PaginationMerger1( $items );
			}
		}
		// $page_items = $this->PaginationMerger( $posts, $courses );

		$perPage = 5;

		$data['search_results'] = $page_items;
		if ( $data['search_results'] != '' ) {
			$data['search_results']->setPath( url()->current() );
		}

		$search_string = $search_term;
		$type_search = '';
		if ( ! empty( $type ) && ! empty( $slug ) ) {
			if ( ! empty( $search_term ) ) {
				$search_string = $type . ' : ' . $slug . ' - ' . $search_term;
			} else {
				$search_string = $type . ' : ' . $slug;
			}
		}
		$data['search_term'] = $search_term;
		$data['search_string'] = $search_string;

		/**
		 * Search terms posts, articles, courses
		 */
		$remove_search_term = '';
		if ( ! empty( $search_term ) ) {
			$remove_search_term = URL_GLOBAL_SEARCH;
			if ( ! empty( $type ) && ! empty( $slug ) ) {
				$remove_search_term .= '/' . $type . '/' . $slug;
			}
			$remove_search_term .= '?';
			if ( $is_posts ) {
				$remove_search_term .= '&posts=true';
			}
			if ( $is_articles ) {
				$remove_search_term .= '&articles=true';
			}
			if ( $is_courses ) {
				$remove_search_term .= '&courses=true';
			}
			$remove_search_term = '<a href="'.$remove_search_term.'">' . $search_term . ' <i class="fa fa-window-close" aria-hidden="true"></i> </a>';
		}
		$data['remove_search_term'] = $remove_search_term;

		$remove_search_type = '';
		if ( ! empty( $type ) && ! empty( $slug ) ) {
			$remove_search_type = URL_GLOBAL_SEARCH;
			$search_type_title = '';
			if ( ! empty( $type ) && ! empty( $slug ) ) {
				$search_type_title = $type . '-' . $slug;
			}
			$remove_search_type .= '?';
			if ( ! empty( $search_term ) ) {
				$remove_search_type .= '&s=' . $search_term;
			}
			if ( $is_posts ) {
				$remove_search_type .= '&posts=true';
			}
			if ( $is_articles ) {
				$remove_search_type .= '&articles=true';
			}
			if ( $is_courses ) {
				$remove_search_type .= '&courses=true';
			}
			$remove_search_type = '<a href="'.$remove_search_type.'">' . $search_type_title . ' <i class="fa fa-window-close" aria-hidden="true"></i> </a>';
		}
		$data['remove_search_type'] = $remove_search_type;

		$remove_search_posts = '';
		if ( $is_posts ) {
			$remove_search_posts = URL_GLOBAL_SEARCH;
			if ( ! empty( $type ) && ! empty( $slug ) ) {
				$remove_search_posts .= '/' . $type . '/' . $slug;
			}
			$remove_search_posts .= '?';
			$search_type_title = getPhrase( 'posts' );
			if ( ! empty( $search_term ) ) {
				$remove_search_posts .= '&s=' . $search_term;
			}
			if ( $is_articles ) {
				$remove_search_posts .= '&articles=true';
			}
			if ( $is_courses ) {
				$remove_search_posts .= '&courses=true';
			}
			$remove_search_posts = '<a href="'.$remove_search_posts.'">' . $search_type_title . ' <i class="fa fa-window-close" aria-hidden="true"></i> </a>';
		}
		$data['remove_search_posts'] = $remove_search_posts;

		$remove_search_articles = '';
		if ( $is_articles ) {
			$remove_search_articles = URL_GLOBAL_SEARCH;
			if ( ! empty( $type ) && ! empty( $slug ) ) {
				$remove_search_articles .= '/' . $type . '/' . $slug;
			}
			$remove_search_articles .= '?';
			$search_type_title = getPhrase( 'articles' );
			if ( ! empty( $search_term ) ) {
				$remove_search_articles .= '&s=' . $search_term;
			}
			if ( $is_posts ) {
				$remove_search_articles .= '&posts=true';
			}
			if ( $is_courses ) {
				$remove_search_articles .= '&courses=true';
			}
			$remove_search_articles = '<a href="'.$remove_search_articles.'">' . $search_type_title . ' <i class="fa fa-window-close" aria-hidden="true"></i> </a>';
		}
		$data['remove_search_articles'] = $remove_search_articles;

		$remove_search_courses = '';
		if ( $is_courses ) {
			$remove_search_courses = URL_GLOBAL_SEARCH;
			if ( ! empty( $type ) && ! empty( $slug ) ) {
				$remove_search_courses .= '/' . $type . '/' . $slug;
			}
			$remove_search_courses .= '?';
			$search_type_title = getPhrase( 'courses' );
			if ( ! empty( $search_term ) ) {
				$remove_search_courses .= '&s=' . $search_term;
			}
			if ( $is_posts ) {
				$remove_search_courses .= '&posts=true';
			}
			if ( $is_articles ) {
				$remove_search_courses .= '&articles=true';
			}
			$remove_search_courses = '<a href="'.$remove_search_courses.'">' . $search_type_title . ' <i class="fa fa-window-close" aria-hidden="true"></i> </a>';
		}
		$data['remove_search_courses'] = $remove_search_courses;
		/*
		var_dump( $is_posts );
		var_dump( $is_courses );
		var_dump( $is_series );
		var_dump( $is_articles );
		die();
		*/

		$data['is_posts'] = $is_posts;
		$data['is_courses'] = $is_courses;
		$data['is_series'] = $is_series;
		$data['is_articles'] = $is_articles;
		$data['is_search_form'] = TRUE;
		$data['query_string'] = '';
		if ( ! empty( $type ) && ! empty( $slug ) ) {
			$data['query_string'] = '/' . $type . '/' . $slug;
		}
		// $data['search_results'] = FALSE;
		return view('lms-forntview.global-search',$data);
	 }

	 public function sendSiteIssue()
	 {

	 }

	 public function PaginationMerger( LengthAwarePaginator $collection1 = NULL, LengthAwarePaginator $collection2 = NULL )
	 {
		$total = $collection1->total() + $collection2->total();

		$perPage = $collection1->perPage() + $collection2->perPage();

		$items = array_merge($collection1->items(), $collection2->items());

		usort( $items, "compare_values" );

		$paginator = new LengthAwarePaginator($items, $total, $perPage);

		return $paginator;
	 }

	 public function PaginationMerger1( LengthAwarePaginator $collection1 )
	 {
		$total = $collection1->total();

		$perPage = $collection1->perPage();

		$items = $collection1->items();

		usort( $items, "compare_values" );

		$paginator = new LengthAwarePaginator($items, $total, $perPage);

		return $paginator;
	 }

	 public function testSummary() {
		 dd( course_lessons_summary('lessons_pieces', array('course_id' => 9)) );
	 }
	 
	 public function postCoachRequest( Request $request )
	 {
		if ( ! Auth::check() ) {
			flash('Ooops...!', 'Please login to access this page', 'error');
			return redirect( URL_USERS_LOGIN );
		}
		$user = Auth::User();
		if ( 'facilitator' != $user->current_user_role ) {
			flash('Ooops...!', 'You are not eligible to become coach', 'error');
			return redirect( URL_USERS_DASHBOARD );
		}
		$check = DB::table('coach_requests')->where('user_id', '=', $user->id)->first();
		if ( $check ) {
			if ( in_array( $check->status, array( 'rejected', 'deleted' ) ) ) {
				flash('Ooops...!', 'Admin has rejected your request to become coach.', 'error');
				return redirect( URL_USERS_DASHBOARD );
			} elseif ( 'requested' === $check->status ) {
				flash('Ooops...!', 'Your request is under review. Will let you know once its done.', 'success');
				return redirect( URL_USERS_DASHBOARD );
			} elseif ( 'accepted' === $check->status ) {
				flash('Success!', 'Your request already approved. Now you are a Coach', 'success');
				return redirect( URL_USERS_DASHBOARD );
			}
		} else {
			$record = array(
				'user_id' => $user->id,
			);
			DB::table('coach_requests')->insert( $record );
			flash('Success!', 'We received your request. Will review your request and will get back to you soon.', 'success');
			return redirect( URL_USERS_DASHBOARD );
		}
	 }
	 
	 public function withdrawCoachRequest( Request $request )
	 {
		if ( ! Auth::check() ) {
			flash('Ooops...!', 'Please login to access this page', 'error');
			return redirect( URL_USERS_LOGIN );
		}
		$user = Auth::User();
		$check = DB::table('coach_requests')->where('user_id', '=', $user->id)->first();
		
		if ( $check ) {
			DB::table('coach_requests')->where('user_id', '=', $user->id)->delete();
			flash('Success!', 'Your request deleted for a coach.', 'success');
			return redirect( URL_USERS_DASHBOARD );
		} else {
			flash('Ooops...!', 'We have not found your request for a coach', 'error');
			return redirect( URL_USERS_DASHBOARD );
		}
	 }
	 
	 public function getContentComments( $content_slug )
	 {
		 $records = App\LmsComments::select( ['lmscontents_comments.comments_notes', 'users.image', 'users.name', 'lmscontents_comments.created_at', 'lmscontents_comments.user_id'] )
				->join( 'users', 'users.id', '=', 'lmscontents_comments.user_id' )
				->join( 'lmscontents', 'lmscontents.id', '=', 'lmscontents_comments.content_id' )
						->where( 'lmscontents.slug', '=', $content_slug )
						->where('type', '=', 'comments')
						->orderBy( 'lmscontents_comments.created_at', 'desc' )
						;
		return Datatables::of($records)
        
        ->editColumn('name', function($records)
        {
			$image = getProfilePath($records->image);
			/*
			if ( $records->user_id == get_current_user_id() ) {
				$str = '<li class="media">' . $records->comments_notes . '&nbsp;<small>'.$records->created_at->diffForHumans().'</small>&nbsp;<img class="d-flex mr-3 icn-size" src="'.$image.'"   title="'.$records->name.'"/></li>';
			} else {
				$str = '<li class="media"><img class="d-flex mr-3 icn-size" src="'.$image.'"   title="'.$records->name.'"/>&nbsp;' . $records->comments_notes . '&nbsp;<small>'.$records->created_at->diffForHumans().'</small></li>';
			}
			*/
			$str = '<li class="media"><img class="d-flex mr-3 icn-size" src="'.$image.'"   title="'.$records->name.'"/>&nbsp;' . $records->comments_notes . '&nbsp;<small>'.$records->created_at->diffForHumans().'</small></li>';
			
			return $str;
        })                
        ->removeColumn( 'comments_notes' )
		->removeColumn( 'image' )
		->removeColumn('created_at')
		->removeColumn('user_id')
        ->make();
	 }
	 
	 public function saveContentComments( Request $request, $slug )
	{
		$record = array(
			'content_id' => $request->modal_item_id,
			'user_id' => get_current_user_id(),
			'comments_notes' => $request->modal_commnets,
			'type' => 'comments',
			'group_id' => 0,
		);
		DB::table('lmscontents_comments')->insert( $record );
		flash('Success!', 'Commented submitted successfully.', 'success');
		dd($request);
		$course = LmsSeries::where('id', '=', $request->course_id)->first();
		if ( 'showcourse' === $request->page_name ) {
			$module = '';
			if ( ! empty( $request->module_id ) ) {
				$module = LmsSeries::where('id', '=', $request->module_id)->first();
			}
			return redirect( URL_FRONTEND_LMSSERIES . $course->slug );
		}
		
	}
}
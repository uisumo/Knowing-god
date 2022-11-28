<div class="row mt-1" ng-controller="singleLessonCtrl">
    <?php
    $sl_no = 1;
    $content_image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
    $total = $contents->count();
    $course_id = $item->id;
    $module_id = '';
    if ( $item->parent_id > 0 ) {
        $course_id = $item->parent_id;
        $module_id = $item->id;
    }
    ?>
    @foreach($contents as $content)
    <?php
    $active_class = '';
    $url = '#';
    $type = 'File';
    $audio_link = '';

    $paid = $is_paid;
	// Let us check if the lesson is FREE in this course
	if( TRUE === $paid ) {
		$paid = ! isLessonFree( $item->id, $content->id);
	}
    if($content->file_path) {
        switch($content->content_type)
        {
            case 'file':
                $url = VALID_IS_PAID_TYPE.$item->slug.'/'.$content->slug;
                $type = 'File';
                break;
            case 'image':
                $url = IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->slug;
                $type = 'Image';
            case 'url':
                $url = $content->file_path;
                $type = 'URL';
                break;
            case 'video_url':
            case 'video':
            case 'iframe':
                $url = URL_STUDENT_LMS_SERIES_VIEW.$item->slug.'/'.$content->slug;
                $type = 'Video';
                break;
            case 'audio_url':
            case 'audio':
                if ( $content->content_type == 'audio' ) {
                    $url = URL_STUDENT_LMS_SERIES_VIEW.$item->slug.'/'.$content->slug;
                    $audio_link = IMAGE_PATH_UPLOAD_LMS_CONTENTS . $content->file_path;
                } else {
                    $url = $item->file_path;
                    $audio_link = $content->file_path;
                }

                $type = 'Audio';
                break;
        }
    }
    if( $content->image ) {
        $content_image_path = IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->image;
    }

    // $url = URL_FRONTEND_LMSSINGLELESSON . 'category/' . $item->slug . '/' . $content->slug;
	if ( empty( $group_slug ) ) {
		$url = URL_FRONTEND_LMSSINGLELESSON . $item->slug . '/' . $content->slug;
	} else {
		$url = URL_FRONTEND_GROUP_LMSSINGLELESSON . $group_slug . '/' . $content->slug;
	}    
?>

 <?php if($paid) $url = '#';

    $cols = 6;
    $current_layout = DB::table( 'lmsmode' )->first();
    if ( ! $current_layout ) {
        $current_layout = 'bothsidebars';
    } else {
        $current_layout = $current_layout->layout;
    }
    if ( 'nosidebar' === $current_layout ) {
        $cols = 4;
    }
 ?>
    <div class="col-xl-{{$cols}} col-lg-6 mb-4">
        <div class="card h-100 text-center video-list-card">
            <div class="card-content">
                @if ( $item->is_paid == 1 && $item->cost > 0 && ! $paid )
					<div class="label-danger label-band">{{getPhrase('free')}}</div>
				@endif
				<img class="card-img-top" src="<?php echo $content_image_path; ?>" alt="">
                <div class="media title-media">
                    @if($content->content_type=='url')
                    <a target="_blank" href="{{$url}}"
                    @if($paid)
                        onclick="showMessage('Please buy this package to continue');"
                    @endif
                    ><h1>{{$sl_no}}</h1>

                    </a>
                    @else
                    <a href="{{$url}}"
                    @if($paid)
                        onclick="showMessage('Please buy this package to continue');"
                    @endif
                    ><h1>{{$sl_no}}</h1>

                    </a>
                    @endif


                    <div class="media-body">
                    @if($content->content_type=='url')
                    <a target="_blank" href="{{$url}}"
                    @if($paid)
                        onclick="showMessage('Please buy this package to continue');"
                    @endif
                    >{{$content->title}}

                    </a>
                    @else
                    <a href="{{$url}}"
                    @if($paid)
                        onclick="showMessage('Please buy this package to continue');"
                    @endif
                    >{{$content->title}}

                    </a>
                    @endif
                    </div>
                </div>
                <?php
                $class = 'icon icon-map-pointer';
                $total_pieces = App\LmsSeries::getPieces( $content->id );
                $total_pieces_count = $total_pieces->count();
                $total_pieces_count++; // Parent Content added
                $completed = 0;
                if ( $total_pieces_count > 1 ) {
                    if ( is_lesson_piece_completed_new( $content->id, $course_id, $module_id ) ) { // Let us see parent piece completed or not
                        $completed++;
                    }
                    foreach( $total_pieces as $piece ) {
                        if ( is_lesson_piece_completed_new( $piece->id, $course_id, $module_id ) ) {
                            $completed++;
                        }
                    }
                } else {
                    if ( is_lesson_piece_completed_new( $content->id, $course_id, $module_id ) ) {
                        $completed++;
                    }
                }
                if ( $total_pieces_count == $completed ) {
                    $class = 'icon icon-pointer-border';
                }
                ?>
                <div class="video-list-pin fixed-top-right"><i class="<?php echo $class; ?>"></i></div>
            </div>

            <div class="card-footer download-link" style="font-size:1rem;">
                @if( $content->file_pdf != '' && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $content->file_pdf ) )
                <a class="mr-2" href="{{IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->file_pdf}}"><i class="fa fa-file-pdf-o"></i></a>
                @else
                <span class="mr-2"><i class="fa fa-file-pdf-o"></i></span>
                @endif

                @if( $content->file_ppt != '' && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $content->file_ppt ) )
                <a class="mr-2" href="{{IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->file_ppt}}"><i class="fa fa-file-powerpoint-o"></i></a>
                @else
                <span class="mr-2"><i class="fa fa-file-powerpoint-o"></i></span>
                @endif
				

                @if( $content->file_word != '' && file_exists( IMAGE_PATH_UPLOAD_LMS_CONTENTS_PATH . $content->file_word ) )
                <a class="mr-2" href="{{IMAGE_PATH_UPLOAD_LMS_CONTENTS.$content->file_word}}"><i class="fa fa-file-word-o"></i></a>
                @else
                <span class="mr-2"><i class="fa fa-file-word-o"></i></span>
                @endif
                <?php
                $comments = lmscontent_comments( $content->id );
                ?>
                @if( Auth::check() )
                    <a class="mr-2" data-toggle="modal" data-target="#dashboardModal" ng-click="getData({{$content->id}}, 'comments')">
                @else
                    <a class="mr-2" data-toggle="modal" data-target="#loginModal" >
                @endif
                <i class="fa fa-comments-o"></i>
                @if( $comments->count() > 0 )
                    {{$comments->count()}}
                @endif
                </a>
				
                <a class="mr-2" data-toggle="modal" data-target="#shareModal" ng-click="getData({{$content->id}}, 'sharecontent', {{$item->id}})"><i class="fa fa-share"></i></a>
				
                <a class="mr-2" data-toggle="modal" data-target="#translationIssueModal" ng-click="getData({{$content->id}}, 'translation', {{$item->id}})"><i class="fa fa-globe"></i></a>

                <a class="mr-2" href="#"><i class="fa fa-group"></i></a>


                @if ( $content->quiz_id > 0 )
                    <a class="mr-2" href="#quizModal" data-toggle="modal" data-target="#quizModal" ng-click="start_exam({{$content->quiz_id}})"><i class="fa fa-graduation-cap"></i></a>
                @else
                    <i class="fa fa-graduation-cap"></i>
                @endif
            </div>
            @if( $type == 'Audio' )
            <div class="card-footer-actions">
                <audio controls="">
                    <source src="{{$audio_link}}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio><span class="vid-list-count text-green">{{$completed}}/{{$total_pieces_count}}</span>
            </div>
            @endif
        </div>
    </div>
    <?php $sl_no++; ?>
    @endforeach
	
	<div class="row">
		<div class="col-sm-12">
			<div class="custom-pagination pull-right">
				{!! $contents->links() !!}
			</div>
		</div>
	</div>
<?php $item_id = ''; ?>
    <?php /* ?>
	@include('lms-forntview.comments-modal')
	<?php */ ?>
	@include('student.dashboard-modal')
    @include('lms-forntview.login-modal')
    @include('auth.forgot-password-modal')
    @include('lms-forntview.share-modal')
    @include('lms-forntview.translation-modal')
    @include('lms-forntview.quiz-modal')
	@include('common.custom-message-alert')
</div>

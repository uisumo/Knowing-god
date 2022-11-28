@extends('layouts.student.studentlayout')

@section('content')

<?php
$is_paid = $price = FALSE;

if( $course_details->is_paid == 1 && $course_details->cost > 0 ) {
	if ( ! isItemPurchased( $course_details->id, 'lms' ) ) {
		$is_paid = TRUE;
		$price = $course_details->cost;
	}
}

?>
<?php // dd($course_details); ?>
<h2 class="mt-0">{{$course_details->title}}
<?php if ( ! empty( $course_details->sub_title ) ) : ?>
<small>| {{$course_details->sub_title}} </small>
<?php endif; ?>
</h2>

<!-- Page Heading/Breadcrumbs -->
<ol class="breadcrumb mt-3">
	@if( Auth::check() )
	<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}">{{getPhrase( 'dashboard' )}}</a> </li>
	@endif
	@if ( empty( $group_slug ) )
	<li class="breadcrumb-item"><a href="{{URL_FRONTEND_LMSCATEGORIES}}">{{getPhrase('categories')}}</a></li>
	<li class="breadcrumb-item"><a href="{{URL_FRONTEND_COURSE_LIST . $course_details->cat_slug}}">{{$course_details->category}}</a></li>
	@else
	<li class="breadcrumb-item"><a href="{{URL_STUDENT_MY_GROUPS}}">{{getPhrase('Groups')}}</a></li>
	<li class="breadcrumb-item"><a href="{{URL_STUDENT_DASHBOARD_GROUP . $group_details->slug}}">{{getPhrase('Group')}} ({{$group_details->title}})</a></li>
	@endif
	<li class="breadcrumb-item"><strong class="text-green">{{$course_details->title}}</strong></li>
</ol>

                <!-- Intro Content -->
                <div class="row mt-4">
                    <div class="col-lg-6 mb-4">
                        @if($course_details->image!='')
                        <img class="img-fluid rounded" src="{{IMAGE_PATH_UPLOAD_LMS_SERIES.$course_details->image}}" alt="">
                        @else
                        <img class="img-fluid rounded" src="{{IMAGE_PATH_UPLOAD_LMS_DEFAULT}}" alt="">
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <h2 class="pathway_green">{{getPhrase('About')}} {{$course_details->title}}</h2>
                        <p class="mt-2">{!! $course_details->description !!} </p>
						<?php $target = URL_PAYMENTS_CHECKOUT . 'lms/' . $course_details->slug; ?>
						@if( $is_paid )
						<p class="mt-2"><a href="{{$target}}" class="btn btn-primary">{{getCurrencyCode() . ' ' . $price}} {{getPhrase('Buy Now')}}</a></p>
						@endif
                    </div>

                </div>
                <!-- /.row -->

                <?php
				
                $modules = App\LmsSeries::where( 'status', '=', 'active' )->where( 'parent_id', '=', $course_details->id )->paginate( LESSONS_ON_COURSE_PAGE );
                $lessons = DB::table('lmsseries_data')
                ->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
                ->where('parent_id', '=', 0 )
            ->where('lmsseries_id', '=', $course_details->id )
            ->paginate( LESSONS_ON_COURSE_PAGE );
                $sl_no = 1;
                $modules_active = ' active';
                $lessons_active = '';
                $section_title = getPhrase('modules');
                if ( $modules->count() == 0 ) {
                    $modules_active = '';
                    $lessons_active = ' active';
                    $section_title = getPhrase('course_lessons');
                }
                ?>

                <h2 class="mb-3"><i class="icon icon-books text-green"></i> {{$section_title}}</h2>
                @if ( $course_details->privacy == 'infodisplay' && ! Auth :: check() )
                    <div class="alert alert-info">
                      <strong>Info!</strong> If you could log in, you can track your progress. Click <a href="#" onclick="open_login_modal('<?php echo base64_encode( url()->current() ); ?>')">here</a> to login
                    </div>
                @endif
                @if( $modules->count() > 0 && $lessons->count() > 0 )
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link<?php echo $modules_active; ?>" data-toggle="tab" href="#modules" role="tab">{{getPhrase('modules')}}</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link<?php echo $lessons_active; ?>" data-toggle="tab" href="#lessons" role="tab">{{getPhrase('lessons')}}</a>
                  </li>
                </ul>
                @endif

                <div class="tab-content tab-no-lessons">
                    <div class="tab-pane<?php echo $modules_active; ?>" id="modules" role="tabpanel">
                        <div class="row mt-1" ng-controller="singleLessonCtrl">
                       @if ( ! empty( $modules ) )

                        @foreach($modules as $module)
						<?php
						$target = URL_FRONTEND_LMSLESSON . $module->slug;
						if ( $is_paid ) {
							$target = '#';
						}
						?>
                        <div class="col-sm-6 col-md-4 mb-4">
                            <div class="card h-100 text-center">
								<a class="text-green" href="{{$target}}" @if($is_paid)
            onclick="showMessage('Please buy this course to continue');" 
        @endif >
									@if($module->image!='')
									<img class="card-img-top" src="{{IMAGE_PATH_UPLOAD_LMS_SERIES.$module->image}}" alt="">
									@else
									<img class="card-img-top" src="{{IMAGE_PATH_UPLOAD_LMS_DEFAULT}}" alt="">
                                @endif
								</a>
                                <div class="card-body">
                                    <h4 class="card-title">

                                        <a class="text-green" href="{{$target}}" @if($is_paid)
            onclick="showMessage('Please buy this course to continue');" 
        @endif>{{'#' . $sl_no++}}&nbsp;{{$module->title}}</a>
                                    </h4>
                                    <?php if ( ! empty( $module->sub_title ) ) : ?>
                                    <h6 class="card-subtitle mb-2 text-green">{{$module->sub_title}}</h6>
                                    <?php endif; ?>
                                    <p class="card-text">{!! $module->short_description !!}</p>
                                </div>
                                <?php
                                $contents = DB::table('lmsseries_data')
                                ->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
                                ->where('lmsseries_id', '=', $module->id )
                                ->get();
                                if ( $contents->count() > 0 ) {
                                    $total_contents = $contents->count();
                                    $completed = 0;
                                ?>
                                <div class="card-footer">
                                    <ul class="course-finished-path">
                                        <?php
                                        foreach( $contents as $content ) :
                                        $class = '';

										$total_pieces = App\LmsSeries::getPieces( $content->id );
										$total_pieces_count = $total_pieces->count();
										$total_pieces_count++; // Parent Content added
										$pieces_completed = 0;
										if ( $total_pieces_count > 1 ) {
											if ( is_lesson_piece_completed_new( $content->id, $course_details->id,$module->id ) ) { // Let us see parent piece completed or not
												$pieces_completed++;
											}
											foreach( $total_pieces as $piece ) {
												if ( is_lesson_piece_completed_new( $piece->id, $course_details->id, $module->id ) ) {
													$pieces_completed++;
												}
											}
											if ( $total_pieces_count == $pieces_completed ) {
												$class = 'completed';
												$completed++;
											}
										} else {
											if ( is_lesson_piece_completed_new( $content->id, $course_details->id, $module->id ) ) {
												$class = 'completed';
												$completed++;
											}
										}
                                        ?>
                                        <li class="{{$class}}"><i class="icon icon-pointer-white" title="{{$content->title}}"></i></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                     @endforeach
					 <div class="row">
						<div class="col-sm-12">
							<div class="custom-pagination pull-right">
								{!! $modules->links() !!}
							</div>
						</div>
					</div>
                     @endif
                    </div>
                    </div>
                    <div class="tab-pane<?php echo $lessons_active; ?>" id="lessons" role="tabpanel">
                        @if ( ! empty( $lessons ) )
                            <?php							
							if ( empty( $group_slug ) ) {
								$group_slug = FALSE;
							} else {
								$course_details = $group_details;
							}
							?>
							@include('lms-forntview.other-views.lessons', array('contents' => $lessons, 'item' => $course_details, 'is_paid' => $is_paid, 'group_slug' => $group_slug, 'page_name' => 'showcourse'))
                        @endif
                    </div>
                </div>
                <!-- /.row -->
        
		@include('lms-forntview.lessons-modal')
        @if ( ! empty( $lessons ) )
            @include('lms-forntview.login-modal')
            @include('auth.forgot-password-modal')
        @endif
@stop


@section('footer_scripts')
    @include('common.validations')
    @include('lms-forntview.scripts.js-scripts')
	@include('common.custom-message-alert')
@stop

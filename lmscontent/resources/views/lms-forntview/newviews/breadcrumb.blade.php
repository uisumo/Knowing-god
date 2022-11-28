<!-- Page Heading/Breadcrumbs -->
<ol class="breadcrumb mt-3">
	@if( Auth::check() )
	<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
	@endif
	<li class="breadcrumb-item"><a href="{{URL_FRONTEND_LMSCATEGORIES}}">{{getPhrase('categories')}}</a></li>
	
	<li class="breadcrumb-item"><a href="{{URL_FRONTEND_COURSE_LIST . $course->cat_slug}}">{{$course->category}}</a></li>
	
	
	@if ( ! empty( $series ) )
	<li class="breadcrumb-item"><a href="{{URL_LMS_SHOW_COUSES . $category->slug}}">{{getPhrase('series')}}</a>({{$series->title}})</li>
	@endif
	
	@if ( ! empty( $course ) )
	<li class="breadcrumb-item"><a href="{{URL_LMS_SHOW_COUSES . $category->slug . '/' . $series->slug}}">{{getPhrase('courses')}}</a>({{$course->title}} )</li>
	@endif
	
	@if ( ! empty( $lesson ) )
	<li class="breadcrumb-item"><a href="{{URL_LMS_SHOW_MODULES_LESSONS . $course->slug }}">{{getPhrase('lessons')}}</a>({{$lesson->title}} )</li>
	@endif
	
	<li class="breadcrumb-item"><strong class="text-green">
	@if( ! empty( $operation ) && $operation == 'lessons') {{getPhrase('module:')}} @endif 
	{{$title}}</strong></li>
</ol>
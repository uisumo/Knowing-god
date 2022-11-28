<div class="row">
	@if( ! empty( $subjects ) && $subjects->count() > 0 )
	<?php $recommended_courses = array(); ?>
	@foreach( $subjects as $subject )
	<?php
	$ribbon_class = 'corner-ribbon corner-ribbon-small left btn-green';
	$see_more_class = 'btn-green';
	if ( $subject->color_class == 'text-blue' ) {
		$ribbon_class = 'corner-ribbon corner-ribbon-small left btn-blue';
		$see_more_class = 'btn-blue';
	} elseif ( $subject->color_class == 'text-yellow' ) {
		$ribbon_class = 'corner-ribbon corner-ribbon-small left btn-yellow';
		$see_more_class = 'btn-yellow';
	}
	$attempted_courses = attempted_courses();
	if ( ! empty( $recommended_courses ) ) {
		// If same course is in different subjects! Let us skip that course.
		foreach( $recommended_courses as $rc ) {
			array_push( $attempted_courses, $rc );
		}
	}
	// Let us skip to show if there are any completed courses
	if ( ! empty( $attempted_courses ) ) {
		$recommended = DB::table('lmsseries AS ls')
		->select(['ls.*', 'qc.slug AS catslug'])
		->join( 'subjects AS s', 's.id', '=', 'ls.subject_id' )
		//->join( 'lmsseries_data AS ld', 'ld.lmscontent_id', '=', 'lc.id' )
		//->join( 'lmsseries AS ls', 'ls.id', '=', 'ld.lmsseries_id' )
		->join( 'quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
		->where( 'ls.subject_id', '=', $subject->id )
		->where( 'ls.parent_id', '=', '0' )
		->whereNotIn( 'ls.id', $attempted_courses )
		//->groupBy('ld.lmsseries_id')
		->orderBy( 'ls.display_order', 'asc' )
		->orderBy( 'ls.updated_at', 'desc' )
		->first();
		
		if ( ! $recommended ) {
			$recommended = DB::table('lmsseries AS ls')
			->select(['ls.*', 'qc.slug AS catslug'])
			->join( 'subjects AS s', 's.id', '=', 'ls.subject_id' )
			//->join( 'lmsseries_data AS ld', 'ld.lmscontent_id', '=', 'lc.id' )
			//->join( 'lmsseries AS ls', 'ls.id', '=', 'ld.lmsseries_id' )
			->join( 'quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
			->where( 'ls.subject_id', '=', $subject->id )
			->where( 'ls.parent_id', '=', '0' )
			//->groupBy('ld.lmsseries_id')
			->orderBy( 'ls.display_order', 'asc' )
			->orderBy( 'ls.updated_at', 'desc' )
			->first();
		}
	} else {
		$recommended = DB::table('lmsseries AS ls')
			->select(['ls.*', 'qc.slug AS catslug'])
			->join( 'subjects AS s', 's.id', '=', 'ls.subject_id' )
			//->join( 'lmsseries_data AS ld', 'ld.lmscontent_id', '=', 'lc.id' )
			//->join( 'lmsseries AS ls', 'ls.id', '=', 'ld.lmsseries_id' )
			->join( 'quizcategories AS qc', 'qc.id', '=', 'ls.lms_category_id' )
			->where( 'ls.subject_id', '=', $subject->id )
			->where( 'ls.parent_id', '=', '0' )
			//->groupBy('ld.lmsseries_id')
			->orderBy( 'ls.display_order', 'asc' )
			->orderBy( 'ls.updated_at', 'desc' )
			->first();
	}
	// echo $subject->id.'@@';
	?>
	@if ( $recommended )
		<?php
	array_push( $recommended_courses, $recommended->id );
	?>
	<div class="col-sm-6">
		<div class="white-card cs-card mb-4">
			<div class="flow-hidden relative p-3">
				<div class="{{$ribbon_class}}">{{$subject->subject_title}}</div>
				<div class="row">
					<div class="col-sm-6 pr-0">
						@if($recommended->image!='')
						<img class="img-fluid rounded" src="{{IMAGE_PATH_UPLOAD_LMS_SERIES.$recommended->image}}" alt="">
						@else
						<img class="img-fluid rounded" src="http://placehold.it/750x450" alt="">
						@endif
					</div>
					<div class="col-sm-6">
						<h5 class="text-green">{{$recommended->title}}</h5>
						@if( ! empty( $recommended->short_description ) )
						<p class="course-card-text">{!!$recommended->short_description!!}</p>
						@endif
						<?php
						$target = URL_FRONTEND_LMSLESSON . $recommended->slug;
						$modules = App\LmsSeries::where( 'parent_id', '=', $recommended->id );
						if ( $modules->count() > 0 ) {
							$target = URL_FRONTEND_LMSSERIES . $recommended->slug;
						}						
						$target = URL_FRONTEND_LMSSERIES . $recommended->slug;
						?>
						<div class="mt-2">
						@if ( $recommended->privacy == 'loginrequired' && ! Auth::check() )					
							<a href="#" class="btn btn-kg btn-course btn-round {{$see_more_class}}" ng-click="open_login_modal('{{base64_encode($target)}}')">{{getPhrase('Start Course')}}&nbsp;<i class="fa fa-lock" aria-hidden="true"></i></a>
						@else
							<a href="{{$target}}" class="btn btn-kg btn-course btn-round {{$see_more_class}}">{{getPhrase('Start Course')}}</a>
						@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif
	@endforeach
	<div class="col-sm-6">
		<div class="add-course-card cs-card mb-4">
			@if(Auth::check())
			<!--<a href="#" class="text-center" ng-click="show_recommended()">-->
			<a href="{{URL_FRONTEND_RECOMMENDED_COURSES}}" class="text-center">
			@else
			<a href="#" class="text-center" ng-click="open_login_modal('{{base64_encode(URL_FRONTEND_LMSCATEGORIES)}}')">
			@endif
				<div class="join-btn cs-center">
				  <i class="icon icon-plus"></i>
				</div>
				<h6 class="mt-1">{{getPhrase('Add a Different Course')}}</h6>
			</a>
		</div>
	</div>
	@else
		Ooops...! {{getPhrase('No_Categories_available')}}
	@endif
</div>
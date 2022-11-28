<div class="card r-card">
    <div class="card-header card-center-header" role="tab" id="headingTwo">
        <h4 class="mb-0">
            <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <span class="dc-caret"><i class="fa fa-caret-down" aria-hidden="true"></i></span>{{getPhrase('Finish Your.....')}}</a>
        </h4>

    </div>
    <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
        <div class="card-body">
            <?php
            $attempted_courses = attempted_courses_new( 'records', array( 'exclude_completed' => TRUE, 'limit_records' => 4 ) );
            ?>
            <div class="row">
                @if ( ! empty( $attempted_courses ) )
				@foreach( $attempted_courses as $attempted_course)
                <?php
				$completed_percentage = number_format( completed_percentage($attempted_course->id), 2 );
                $style = "background: -webkit-gradient(linear, left top, right top, from(rgba(0, 161, 126, 1)), color-stop($completed_percentage%, rgba(255, 255, 255, 1)), color-stop($completed_percentage%, rgba(255, 255, 255, 1)), to(rgba(255, 255, 255, 1)));";
                $style = "background:linear-gradient(to right, rgba(0, 161, 126, 1) 0%, rgba(255, 255, 255, 1) $completed_percentage%);";
				?>
				<div class="col-sm-3">
                    <a href="{{URL_FRONTEND_LMSSERIES . $attempted_course->slug}}"><h2 class="task-head ctext-left">{{$attempted_course->title}}</h2></a>
					<div class="btn-cfinish btn-outline blue-white-gridient" style="{{$style}}">{{$completed_percentage}}%{{getPhrase('Complete')}} </div>
                </div>
				@endforeach
                
                @else
                <span class='nocourse-started text-center'>{!! getPhrase( sprintf( 'You have not yet start any course  <a href="%s">click here</a> to start', URL_FRONTEND_LMSCATEGORIES ) ) !!}</span>
                @endif
            </div>
        </div>
    </div>
</div>


@extends($layout)

@section('content')
<div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb mt-2">
                            <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
                            @if(checkRole(getUserGrade(2)))
                            <li class="breadcrumb-item"><a href="{{URL_USERS}}">{{ getPhrase('users')}}</a> </li>
                            <li class="breadcrumb-item"><strong class="text-green">{{isset($title) ? $title : ''}}</strong></li>
                            @else
                            <li class="breadcrumb-item"><strong class="text-green">{{$title}}</strong></li>
                            @endif
                        </ol>
                    </div>
                </div>
                    @include('errors.errors')
                <!-- /.row -->

                <?php
                $user_options = null;
                if($record->settings)
                    $user_options = json_decode($record->settings)->user_preferences;
                ?>
                <div class="expand-card card-normal mt-3">
    <div class="card" >
        <div class="card-header">
                    @if(checkRole(getUserGrade(2)))
                        <div class="pull-right messages-buttons">

                            <a href="{{URL_USERS}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>

                        </div>
                        @endif

                            <h4 class="mb-0 mt-3">{{ $title }}</h4>
                        </div>


                    <div class="card-body">

                     <?php $button_name = getPhrase('update'); ?>
                        {{ Form::model($record,
                        array('url' => URL_USERS_SETTINGS.$record->slug,
                        'method'=>'patch','novalidate'=>'','name'=>'formUsers ', 'files'=>'true' )) }}

                    <h5 class="head-bold">{{getPhrase('subjects')}}</h5>

                    <div class="p-3">
                    <div class="row">
                    @foreach($subjects as $subject)
                 <?php

                         $checked = '';
                         if($user_options) {
                             if ( ! empty ( $user_options->subjects ) )
                             {
                                 if(in_array($subject->id,$user_options->subjects))
                                     $checked='checked';
                             }
                         }
                     ?>
                    <div class="col-md-6">
                        <label class="checkbox-inline" >
                            <input type="checkbox" data-toggle="toggle" name="subjects[{{$subject->id}}]" data-onstyle="success" data-offstyle="default" {{$checked}}> {{$subject->subject_title}}
                        </label>
                    </div>
                    @endforeach

                 </div>
                </div>

                     <h5 class="head-bold mt-3">{{getPhrase('categories')}}</h5>

                        <div class="p-3">
                    <div class="row">
                    @foreach($lms_category as $category)
                     <?php

                         $checked = '';
                         if($user_options) {
                             if(count($user_options->lms_categories))
                             {
                                 if(in_array($category->id,$user_options->lms_categories))
                                     $checked='checked';
                             }
                         }
                     ?>
                    <div class="col-md-6">
                        <label class="checkbox-inline">
                            <input     type="checkbox"
                                    data-toggle="toggle"
                                    data-onstyle="success"
                                    data-offstyle="default"
                                    name="lms_categories[{{$category->id}}]"
                                    {{$checked}}
                                    > {{$category->category}}
                        </label>
                    </div>
                    @endforeach

                 </div>
                 </div>

                 <div class="buttons text-center mt-2">
                            <button class="btn btn-lg btn-primary button"
                            >{{ getPhrase('update') }}</button>
                        </div>

                    {!! Form::close() !!}
                    </div>
                </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
@endsection

@section('footer_scripts')
 @include('common.validations');
 <script src="{{JS}}bootstrap-toggle.min.js"></script>
@stop

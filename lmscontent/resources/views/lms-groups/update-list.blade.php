@extends('layouts.lmsgroups-add-content-layout')

 @section('custom_div')

 <div ng-controller="prepareQuestions">

 @stop

@section('content')
<div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row mt-100 mt-r100">
                    <div class="col-lg-12">
                        <ol class="breadcrumb mt-2">
                            <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>

                            <li class="breadcrumb-item"><a href="{{URL_ADMIN_ALL_LMSGROUPS}}">{{ getPhrase('groups')}}</a> </li>

                            <li class="breadcrumb-item"><a href="{{URL_STUDENT_DASHBOARD_GROUP . $record->slug}}">{{getPhrase('Group')}} ({{$record->title}})</a></li>

                            <li class="breadcrumb-item active">{{isset($title) ? $title : ''}}</li>

                        </ol>

                    </div>

                </div>

                    @include('errors.errors')

                <?php $settings = ($record) ? $settings : ''; ?>

                <div class="expand-card card-normal mt-3 ">

                    <div class="card datatable-card" ng-init="initAngData({{$settings}});" >

                    <div class="card-header">

                        <div class="pull-right messages-buttons">

                            <a href="{{URL_STUDENT_MY_GROUPS}}" class="btn  btn-primary button" >{{ getPhrase('my_groups')}}</a>

                        </div>

                        <h4 class="mb-0 mt-3">{{ $title }} </h4>

                    </div>

                                <div class="card-body">

                    <?php $button_name = getPhrase('create'); ?>

                             <div class="row">

                            <fieldset class="form-group col-md-6">

                                {{ Form::label('lms_categories', getphrase('select_pathway')) }}



                                {{Form::select('lms_categories', $categories, null, ['class'=>'form-control', 'ng-model' => 'category_id',

                                'placeholder' => 'Select', 'ng-change'=>'categoryChanged(category_id)' ])}}

                            </fieldset>

                        <?php $lmssettings = getSettings('lms');?>

                            <fieldset class="form-group col-md-6">

                                {{ Form::label('file_type', getphrase('file_type')) }}



                                {{Form::select('file_type', $lmssettings->content_types, null, ['class'=>'form-control', 'ng-model' => 'content_type',

                                'placeholder' => getPhrase('Select')  ])}}

                            </fieldset>



                            {{--     <fieldset class="form-group col-md-12">

                                {{ Form::text('search_term', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('search_term'),

                            'ng-model'=>'search_term'

                            )) }}

                            </fieldset> --}}





                                <div class="col-md-12">

                            <div ng-if="examSeries!=''" class="vertical-scroll" >



                                <h4 ng-if="categoryItems.length>0" class="text-success">{{getPhrase('total_items')}}: @{{ categoryItems.length}} </h4>


<div class="table-responsive">
                                <table

                                  class="table table-hover">



                                    <th>{{getPhrase('title')}}</th>

                                    <th>{{getPhrase('code')}}</th>

                                    <th>{{getPhrase('type')}}</th>





                                    <th>{{getPhrase('action')}}</th>



                                    <tr ng-repeat="item in categoryItems | filter : {content_type: content_type} | filter:search_term  track by $index">



                                        <td

                                        title="@{{item.title}}" >

                                        @{{item.title}}

                                        </td>

                                        <td>@{{item.code}}</td>

                                        <td>@{{item.content_type}}</td>

                                        {{-- <td><img src="{{IMAGE_PATH_UPLOAD_LMS_CONTENTS}}@{{item.image}}" height="50" width="50" /> --}}</td>

                                        <td><a



                                        ng-click="addToBag(item);" class="btn btn-primary" >{{getPhrase('add')}}</a>



                                          </td>



                                    </tr>

                                </table>
                                    </div>

                                </div>





                                 </div>





                             </div>



                    </div>



                </div>
                </div>

            </div>

            <!-- /.container-fluid -->

        </div>

        <!-- /#page-wrapper -->

@stop
@section('footer_scripts')
    @include('lms-groups.scripts.js-scripts')
@stop
@section('custom_div_end')
    </div>
@stop

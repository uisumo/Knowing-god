@extends('layouts.admin.adminlayout')

@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>							
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->
				
			 <div class="panel panel-custom col-lg-8 col-lg-offset-2">
					<div class="panel-heading">
						<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body">
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record, 
						array('url' => URL_SHOW_LMSMODE, 
						'method'=>'patch' ,'novalidate'=>'','name'=>'formSettings', 'files'=>'true')) }}
					@else
						{!! Form::open(array('url' => URL_SETTINGS_ADD, 'method' => 'POST', 
						'novalidate'=>'','name'=>'formSettings ', 'files'=>'true')) !!}
					@endif

					 <fieldset class="form-group">

						

						{{ Form::label('lmsmode', 'LMS ' . getphrase('mode')) }}

						<span class="text-red">*</span>
						<?php
                        $selected = 'default';
                        if($record)
                            $selected = $record->lmsmode;
                        ?>
						{{Form::select('lmsmode', array( 'default' => getPhrase('Default'), 'series' => getPhrase('Series') ), $selected, ['placeholder' => getPhrase('select_option'),'class'=>'form-control',
                            'ng-model'=>'lmsmode',
                            'required'=> 'true',
                            'ng-class'=>'{"has-error": formUsers.lmsmode.$touched && formUsers.lmsmode.$invalid}'
                         ])}}

						  <div class="validation-error" ng-messages="formSettings.title.$error" >
	    					{!! getValidationMessage()!!}

	    					</div>

					</fieldset>
					<div class="buttons text-center">

							<button class="btn btn-lg btn-success button">{{ $button_name }}</button>

						</div>
					 
					{!! Form::close() !!}
					 

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
@stop
@section('footer_scripts')
	@include('common.validations' );	
@stop
 
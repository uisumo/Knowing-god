@extends($layout)
 <link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">
@section('content')

<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li><a href="{{URL_LMS_CONTENT}}">{{ getPhrase('lessons')}}</a></li>
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->
				<?php 
					$settings = ($record) ? $settings : ''; 
				?>

				<div class="panel panel-custom" ng-init="initAngData('{{ $settings }}');" ng-controller="angLmsController">
					<div class="panel-heading"> 
						<div class="pull-right messages-buttons">
							<a href="{{URL_LMS_CONTENT}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
						</div>
					<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body" >
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record, 
						array('url' => URL_LMS_CONTENT_EDIT. $record->slug, 'novalidate'=>'','name'=>'formLms ',
						'method'=>'patch', 'files' => true)) }}
					@else
						{!! Form::open(array('url' => URL_LMS_CONTENT_ADD, 
							'novalidate'=>'','name'=>'formLms ',
						'method' => 'POST', 'files' => true)) !!}
					@endif
					 @include('lms.lmscontents.form_elements', 
					 array('button_name'=> $button_name),
					 array('subjects'=>$subjects, 'record'=>$record, 'contents' => $contents, 'quizzes' => $quizzes))
					 	 	
					{!! Form::close() !!}
					</div> 
  
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
@stop
@section('footer_scripts')   
@include('lms.lmscontents.scripts.js-scripts')
@include('common.validations', array('isLoaded'=>'1'));
@include('common.editor'); 
  @include('common.alertify')
   <script>
 	var file = document.getElementById('image_input');

file.onchange = function(e){
    var ext = this.value.match(/\.([^\.]+)$/)[1];
    switch(ext)
    {
        case 'jpg':
        case 'jpeg':
        case 'png':

     
            break;
        default:
               alertify.error("{{getPhrase('file_type_not_allowed')}}");
            this.value='';
    }
};
 </script>
 
 <script src="{{JS}}select2.js"></script>
    
    <script>
      $('.select2').select2({
       placeholder: "Select Main Lesson",
    });
    </script>
@stop
 
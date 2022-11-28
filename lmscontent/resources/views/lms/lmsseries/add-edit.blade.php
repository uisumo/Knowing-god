@extends('layouts.admin.adminlayout')
<link href="{{CSS}}bootstrap-datepicker.css" rel="stylesheet">	
@section('content')
<?php
if ( empty( $is_module  ) ) {
	$is_module  = 'no';
}
if ( ! empty( $record  ) && $record->parent_id > 0 ) {
	$is_module  = 'yes';
}

if ( empty( $course ) ) {
	$course = FALSE;
}
// echo $parent_id;
?>
<div id="page-wrapper">
			<div class="container-fluid">
				<?php if ( $record ) : ?>
				<ul class="nav nav-tabs" id="myTab" role="tablist">
				  <li class="nav-item">
					<a class="nav-link show active" id="edit-tab" href="{{URL_LMS_SERIES_EDIT.$record->slug}}" aria-selected="true">Edit</a>
				  </li>
				  @if ( isset( $parent_id ) && $parent_id == 0 )
				  <li class="nav-item">
					<a class="nav-link" id="modules-tab" href="{{URL_LMS_MODULES . '/' . $record->slug}}" aria-selected="false">Modules</a>
				  </li>
				  @endif
				  <li class="nav-item">
					<a class="nav-link" id="lessons-tab" href="{{URL_COURSE_LESSONS . $record->slug}}"  aria-selected="false">Lessons</a>
				  </li>
				</ul>
				<?php endif; ?>
								  
				  <!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="/"><i class="mdi mdi-home"></i></a> </li>
							<li><a href="{{URL_LMS_SERIES}}">{{ getPhrase('courses')}}</a></li>
							@if ( $is_module == 'yes' )
								<?php
							$url = URL_LMS_MODULES;
							if ( ! empty( $course ) ) {
								$url = URL_LMS_MODULES . '/' . $course->slug;
							}
							?>
							<li><a href="{{$url}}">{{ getPhrase('modules')}}</a></li>
							@endif
							<li class="active">{!!isset($title) ? $title : ''!!}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->
				 
 <div class="panel panel-custom col-lg-12">
 <div class="panel-heading"> 
	<?php
	$url = URL_LMS_SERIES;
	if ( $is_module === 'yes' ) {
		$url = URL_LMS_MODULES;
		if ( ! empty( $course ) ) {
			$url = URL_LMS_MODULES . '/' . $course->slug;
		}
	}
	if ( empty( $course_type ) ) {
		$course_type = 'regular';
	}
	?>
	<div class="pull-right messages-buttons"> <a href="{{$url}}" class="btn btn-primary button">{{ getPhrase('list')}}</a> </div>
	<h1>{!! $title !!}  </h1>
</div>
 <div class="panel-body">
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record, 
						array('url' => URL_LMS_SERIES_EDIT.$record->slug, 
						'method'=>'patch', 'files' => true, 'name'=>'formLms', 'novalidate'=>'')) }}
					@else
						{!! Form::open(array('url' => URL_LMS_SERIES_ADD, 'method' => 'POST', 'files' => true, 'name'=>'formLms ', 'novalidate'=>'')) !!}
					@endif
					
					 @include('lms.lmsseries.form_elements', 
					 array('button_name'=> $button_name),
					 array('record'=>$record,
					 'categories' => $categories, 'subjects' => $subjects, 'is_module' => $is_module, 'course' => $course, 'course_type' => $course_type ))
					 		
					{!! Form::close() !!}
					</div>

				</div>
				
				  				
				
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
@stop

@section('footer_scripts')
 @include('common.validations');
 @include('common.editor');
 @include('common.alertify')
 @include('common.datatables', array('route'=>'lmscontent.dataTable'))
 @include('common.deletescript-page', array('route' => URL_COUPONS_DELETE))
  <script src="{{JS}}datepicker.min.js"></script>
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
$('.input-daterange').datepicker({
        autoclose: true,
        startDate: "0d",
         format: '{{getDateFormat()}}',
    }); 
 
 function getSerieses()
{
  
	  var category_id = $('#lms_category_id').val();
  route = '{{URL_LMS_SERIES_MASTER_GET_SERIES_CATEGORY}}'+category_id;  

var token = $('[name="_token"]').val();
  
  data= {_method: 'get', '_token':token, 'category_id': category_id};

	$.ajax({
		url:route,
		dataType: 'json',
		data: data,
		success:function(result){
		   $('#lms_series_master_id').empty();
		for(i=0; i<result.length; i++)
		 $('#lms_series_master_id').append('<option value="'+result[i].id+'">'+result[i].text+'</option>');
		}
	});
}
 </script>
@stop
 
 
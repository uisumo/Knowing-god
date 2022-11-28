@extends($layout)
@section('content')<div id="page-wrapper">
<div class="container-fluid">
@if(checkRole(getUserGrade(2)))
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li><a href="{{URL_USERS_DASHBOARD}}"><i class="mdi mdi-home"></i></a> </li>
				<li><a href="{{URL_USERS}}">{{ getPhrase('users')}}</a> </li>
				<li>{{ $title }}</li>
			</ol>
		</div>
	</div>
@else
<ol class="breadcrumb mt-2">
    @if(checkRole(getUserGrade(2)))
        <li class="breadcrumb-item"><a href="{{URL_USERS}}">{{ getPhrase('users')}}</a> </li>
    @else
        <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
    @endif
    <li class="breadcrumb-item"> <strong class="text-green">{{$title}}</strong> </li>
</ol>
@endif
@include('errors.errors')
<!-- /.row -->

<div class="expand-card card-normal">
<div class="card">
<div class="card-header">
@if(checkRole(getUserGrade(2)))
<div class="pull-right messages-buttons"><a href="{{URL_USERS}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a></div>
@endif
    <h4 class="mb-0 mt-3">{{ $title }}  </h4>
</div>

<div class="card-body form-auth-style">
<?php $button_name = getPhrase('create'); 
if ( ! isset( $operation ) ) {
	$operation = 'main';
}
?>
@if ($record)
<?php $button_name = getPhrase('update'); 
$target = URL_USERS_EDIT.$record->slug;
if ( ! empty( $operation ) ) {
	$target .= '/' . $operation;
}
?>
{{ Form::model($record, 
array('url' =>  $target,
'method'=>'patch','novalidate'=>'','name'=>'formUsers ', 'files'=>'true' )) }}
@else
{!! Form::open(array('url' => URL_USERS_ADD, 'method' => 'POST', 'novalidate'=>'','name'=>'formUsers ', 'files'=>'true')) !!}
@endif

@include('users.form_elements', array('button_name'=> $button_name, 'record' => $record, 'operation' => $operation))

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
@include('common.validations')
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
@stop

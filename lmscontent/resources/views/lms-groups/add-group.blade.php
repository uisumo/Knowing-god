@extends($layout)
@section('content')<div id="page-wrapper">
<div class="container-fluid">
<ol class="breadcrumb mt-2">
    @if(checkRole(getUserGrade(2)))
        <li class="breadcrumb-item"><a href="{{URL_ADMIN_ALL_LMSGROUPS}}">{{ getPhrase('groups')}}</a> </li>
    @else
        <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
        <li class="breadcrumb-item"> <a href="{{URL_STUDENT_MY_GROUPS}}"><?php echo getPhrase( 'my_groups' ); ?></a> </li>
    @endif
    <li class="breadcrumb-item active"> <strong class="text-green">{{$title}}</strong> </li>
</ol>
@include('errors.errors')
<!-- /.row -->

<div class="panel panel-custom">
<div class="panel-heading">
@if(checkRole(getUserGrade(2)))
<div class="pull-right messages-buttons"><a href="{{URL_ADMIN_ALL_LMSGROUPS}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a></div>
@else
    @include('lms-groups.group-buttons', array('title' => $title))
@endif
</div>

<div class="panel-body form-auth-style">
<?php $button_name = getPhrase('create'); ?>
@if ($record)
<?php $button_name = getPhrase('update'); ?>
{{ Form::model($record,
array('url' => URL_STUDENT_UPDATE_GROUP.$record->slug,
'method'=>'patch','novalidate'=>'','name'=>'formUsers ', 'files'=>'true' )) }}
@else
{!! Form::open(array('url' => URL_STUDENT_ADD_GROUP, 'method' => 'POST', 'novalidate'=>'','name'=>'formUsers ', 'files'=>'true')) !!}
@endif

@include('lms-groups.add-group-elements', array('button_name'=> $button_name, 'record' => $record))

{!! Form::close() !!}
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

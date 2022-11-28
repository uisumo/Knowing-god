@extends($layout)
@section('content')<div id="page-wrapper">
<div class="container-fluid">

<ol class="breadcrumb">
				<li><a href="{{URL_USERS_DASHBOARD}}"><i class="mdi mdi-home"></i></a> </li>
				<li><a href="{{URL_ADMIN_ALL_LMSGROUPS}}">{{ getPhrase('groups')}}</a> </li>
				<li>{{ $title }}</li>
</ol>
@include('errors.errors')
<!-- /.row -->

<div class="panel panel-custom">
<div class="panel-heading">
@if(checkRole(getUserGrade(2)))
<div class="pull-right messages-buttons"><a href="{{URL_ADMIN_ALL_LMSGROUPS}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a></div>
@endif
<h1>{{ $title }}  </h1>
</div>

<div class="panel-body form-auth-style">
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
@stop

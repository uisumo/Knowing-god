@extends( $layout )
@section('header_scripts')
<!-- <link href="{{CSS}}ajax-datatables.css" rel="stylesheet"> -->
<link href="{{CSS}}materialdesignicons.css" rel="stylesheet" type="text/css">
@stop
@section('content')


<div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">                        						
						<ol class="breadcrumb mt-2">
							@if( Auth::check() )
							<li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD_USER}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>
							@endif							
							<li class="breadcrumb-item"> <strong class="text-green">{{$title}}</strong> </li>
						</ol>
                    </div>
                </div>

                <!-- /.row -->
                <div class="panel panel-custom">
                    @if(checkRole(getUserGrade(5)))
					<div class="panel-heading">
                        @include('lms-groups.group-buttons', array('title' => $title))
                    </div>
					@endif
                    <div class="panel-body packages">
                        <div class="table-responsive"> 
                        <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ getPhrase('title')}}</th>
                                    <th>{{ getPhrase('image')}}</th>
                                    @if($type == 'mygroups')
                                    <th>{{ getPhrase('is_public')}}</th>
                                    @endif
                                    <th>{{ getPhrase('total_lessons')}}</th>
                                    <th>{{ getPhrase('created')}}</th>
                                    <th>{{ getPhrase('action')}}</th>
                                </tr>
                            </thead>

                        </table>
                        </div>

                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
@endsection

@section('footer_scripts')
 @include('student.dashboard-modal')
 
 @include('common.datatables', array('route'=>URL_STUDENT_MY_GROUPS_AJAXLIST . '/' . $type . '/' . $is_joined, 'route_as_url' => TRUE))
 @include('common.deletescript', array('route'=>URL_STUDENT_MY_GROUPS_DELETE, 'update_route' => URL_STUDENT_GROUP_CHANGE_STATUS))
  @include( 'lms-groups.scripts.js-scripts' )

@stop

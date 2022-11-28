@extends( $layout )
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
<link href="{{CSS}}materialdesignicons.css" rel="stylesheet" type="text/css">
@stop
@section('content')


<div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb mt-2">
                            <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>

                            <li class="breadcrumb-item"> <a href="{{URL_STUDENT_MY_GROUPS}}"><?php echo getPhrase( 'my_groups' ); ?></a> </li>

                            <li class="breadcrumb-item"><a href="{{URL_STUDENT_DASHBOARD_GROUP . $group->slug}}">{{getPhrase('Group')}} ({{$group->title}})</a></li>

                            <li class="breadcrumb-item active"><strong class="text-green">{{ $title }}</strong></li>
                        </ol>
                    </div>
                </div>

                <!-- /.row -->
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        @if(Auth::User()->id == $group->user_id )
                        <div class="pull-right messages-buttons">
                            <a href="{{URL_STUDENT_UPDATE_GROUP_INVITATIONS_ADD . $group->slug }}" class="btn btn-primary button" >{{ getPhrase('invite')}}</a>
                        </div>
                        @endif
                        <h1>{{$group->title}} {{ $title }}</h1>
                    </div>
                    <div class="panel-body packages">
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ getPhrase('name')}}</th>
                                    <th>{{ getPhrase('group_joined')}}</th>
                                    @if ( $group->user_id == Auth::User()->id )
                                    <th>{{ getPhrase('action')}}</th>
                                    @endif
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
 @include( 'lms-groups.scripts.js-scripts' )
 @include('common.datatables', array('route'=>URL_STUDENT_UPDATE_GROUP_INVITATIONS_GETLIST . $group->slug . '/' . $invitation_status . '/' . $is_joined, 'route_as_url' => TRUE))
 @include('common.deletescript', array('route'=>URL_STUDENT_MY_GROUPS_DELETE))

@stop

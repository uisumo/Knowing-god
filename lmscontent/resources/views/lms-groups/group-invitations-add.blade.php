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

                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>

                <!-- /.row -->
                <div class="panel panel-custom">
                    <div class="panel-heading">

                        <h1 ng-click="add_remove_group()">{{ $title }} <i>{{$group->title}}</i> </h1>
                    </div>
                    <div class="panel-body packages">
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable table-vertical-data" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ getPhrase('name')}}</th>
                                    <th>{{ getPhrase('joined')}}</th>
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

 @include('common.datatables', array('route'=>URL_STUDENT_UPDATE_GROUP_INVITATIONS_ADD_GETUSERS . $group->slug, 'route_as_url' => TRUE))
 @include( 'lms-groups.scripts.js-scripts' )

@stop

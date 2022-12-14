@extends($layout)

@section('header_scripts')

<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">

@stop

@section('content')





<div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <h4 class="mt-0">{{ $title }}</h4>
                <div class="row">

                    <div class="col-lg-12">

                        <ol class="breadcrumb mt-2">

                            <li class="breadcrumb-item"> <a href="{{URL_USERS_DASHBOARD}}"><?php echo getPhrase( 'Dashboard' ); ?></a> </li>

                            <li class="breadcrumb-item">{{ $title }}</li>

                        </ol>

                    </div>

                </div>



                <!-- /.row -->

                <div class="panel panel-custom">

                   
                    <div class="panel-body packages ">

                        <div class="table-responsive cs-min-height">

                        <table class="table table-striped table-bordered datatable " cellspacing="0" width="100%">

                            <thead>

                                <tr>

                                    <th>{{ getPhrase('course')}}</th>

                                    <th>{{ getPhrase('paid_from')}}</th>

                                    <th>{{ getPhrase('datetime')}}</th>

                                    <th>{{ getPhrase('status')}}</th>

                                    {{-- <th>{{ getPhrase('action')}}</th> --}}



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



 @include('common.datatables', array('route'=>URL_PAYPAL_PAYMENTS_AJAXLIST.$user->slug, 'route_as_url' => TRUE))

 @include('common.deletescript', array('route'=>'/exams/quiz/delete/'))



@stop


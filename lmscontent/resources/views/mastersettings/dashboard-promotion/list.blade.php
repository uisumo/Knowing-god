@extends('layouts.admin.adminlayout')

@section('content')

<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>
								
				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						
						<div class="pull-right messages-buttons">
							<a href="{{URL_ADMIN_DASHBOARDS_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
						</div>
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div > 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>									 
									<th>{{ getPhrase('id')}}</th>
									<th>{{ getPhrase('title')}}</th>
								 	<th>{{ getPhrase('action')}}</th>
								</tr>
								@if ( $dashboards->count() > 0 )
									<?php
									$sl_no = 1;
									// dd( $dashboards );
									?>
									@foreach( $dashboards as $dashboard )
										<tr>
										<td>{{ $sl_no++ }}</td>
										<td>
										{{ $dashboard->title }}<br>
										<?php if ( $dashboard->courses_criteria == 'between' ) {
											?>
											{{getPhrase('Courses:') . ' >= ' . $dashboard->courses_from . ' ' .  getPhrase( 'and' ) . '<=' . $dashboard->courses_to }}
											<?php
										} else { ?>
										{{getPhrase('Courses:') . $dashboard->courses_criteria . ' ' . $dashboard->courses_from}}
										<?php } ?>
										
										<br>
										<?php if ( $dashboard->modules_criteria == 'between' ) {
											?>
											{{getPhrase('Modules:') . ' >= ' . $dashboard->modules_from . ' ' . getPhrase( 'and' ) . '<=' . $dashboard->modules_to }}
											<?php
										} else { ?>
										{{getPhrase('Modules:') . $dashboard->modules_criteria . ' ' . $dashboard->modules_from}}
										<?php } ?>
										
										<br>
										<?php if ( $dashboard->lessons_criteria == 'between' ) {
											?>
											{{getPhrase('Lessons:') . ' >= ' . $dashboard->lessons_from . ' ' . getPhrase( 'and' ) . '<=' . $dashboard->lessons_to }}
											<?php
										} else { ?>
										{{getPhrase('Lessons:') . $dashboard->lessons_criteria . ' ' . $dashboard->lessons_from}}
										<?php } ?>
										
										<br>{{getPhrase('Star Symbol:') . $dashboard->star_symbol . ' ' . getPhrase('Lessons') }}
										
										<br>{{getPhrase('Pathway pin:') . $dashboard->pathway_symbol . ' ' . getPhrase('Lessons') }}
										
										<br>{{getPhrase('Crown Symbol:') . $dashboard->crown_symbol . ' ' . getPhrase('Lessons') }}
										</td>
										<td>
										<div class="dropdown more">
											<a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="mdi mdi-dots-vertical"></i>
											</a>
											<ul class="dropdown-menu" aria-labelledby="dLabel">
											   <li><a href="{{URL_ADMIN_DASHBOARDS_EDIT .'/'. $dashboard->slug}}"><i class="fa fa-pencil"></i>{{getPhrase("edit")}}</a></li>
											 </ul>
										 </div>
										</td>
										</tr>
									@endforeach
								@endif
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
 @include('common.deletescript', array('route'=>URL_SUBJECTS_DELETE))
@stop

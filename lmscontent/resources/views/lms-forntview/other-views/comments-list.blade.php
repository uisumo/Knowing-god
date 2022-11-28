@extends('layouts.lightbox-layout')
@section('content')
<div id="page-wrapper">

	
	<div class="row ">
		<div class="col-sm-12">
			<div class="">				
				<div class="modal-table-cust mt-2 height-overflow"> 
					<table class="table table-striped table-bordered datatable2" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th></th>						  
							</tr>
						</thead>				 
					</table>
				</div>
			</div>
		</div>
	</div>

</div>
@stop
@section('footer_scripts')
@include('common.datatables2', array('route'=>URL_CONTENT_COMMENTS_GETLIST . $content_slug, 'route_as_url' => TRUE))
@stop

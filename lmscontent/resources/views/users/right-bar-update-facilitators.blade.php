<div class="panel-heading">
		<h2>{{getPhrase('saved_items')}}</h2>
	<div ng-if="savedSeries.length>0" class="crearfix selected-questions-details">
		 
		<span class="pull-right">{{getPhrase('total_items')}}: @{{ savedSeries.length }}</span>
	</div>
	<div ng-if="savedSeries.length==0" class="crearfix selected-questions-details">
		<span class="pull-left">{{getPhrase('no_data_available')}}</span>
	</div>	
	</div>
	{!! Form::open(array('url' => URL_LMS_SERIES_UPDATE_SERIES, 'method' => 'POST')) !!}
					 	<input type="hidden" name="saved_series" value="@{{savedSeries}}">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12 clearfix">
				<div ng-if="savedSeries!=''" class="vertical-scroll" >
					 				
					 				<a class="remove-all-questions text-red" ng-click="removeAll()">{{ getPhrase('remove_all')}}</a>
					 				<table  
								  class="table table-hover" id="facilitators_table">
								  <thead>
								  <tr>
									<th>{{getPhrase('Name')}}</th>
									<th>{{getPhrase('Email')}}</th>
									<th></th>	
									</tr>
									</thead>
									<tbody>
										<tr ng-repeat="i in savedSeries track by $index" id="@{{savedSeries[$index].id}}">
										<td>@{{ savedSeries[$index].name}}</td>
										<td title="@{{ savedSeries[$index].email}}">@{{ savedSeries[$index].email  }}</td>
										
										<td><a ng-click="removeItem(savedSeries[$index].coach_id, savedSeries[$index].id)" class="btn-outline btn-close text-red"><i class="fa fa-close"></i></a></td>
										</tr>
									</tbody>
									</table>
					 			</div>

					 			<?php /* ?>
								<div class="buttons text-center" >
							<button class="btn btn-lg btn-success button">{{getPhrase('update')}}</button>
						</div>
						<?php */ ?>
			</div>
		</div>
	</div>

{!! Form::close() !!}
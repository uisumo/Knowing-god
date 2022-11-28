<div class="expand-card card-normal mt-100">


    <div class="card" >
    <div class="card-heading">
      <div class="p-4">
          <h2>{{getPhrase('saved_items')}}</h2>
          <div ng-if="savedSeries.length>0" class="crearfix selected-questions-details">

              <span>{{getPhrase('total_items')}}: @{{ savedSeries.length }}</span>
          </div>
          <div ng-if="savedSeries.length==0" class="crearfix selected-questions-details">
              <span class="pull-left">{{getPhrase('no_data_available')}}</span>
          </div>
      </div>
    </div>

    <div class="card-body">
        {!! Form::open(array('url' => URL_LMS_GROUP_POSTS.$record->slug, 'method' => 'POST')) !!}
        <input type="hidden" name="saved_series" value="@{{savedSeries}}">
        <div class="row">
            <div class="col-md-12 clearfix">
                <div ng-if="savedSeries!=''" class="vertical-scroll" >

                                     <a class="remove-all-questions text-red" ng-click="removeAllCourses()">{{ getPhrase('remove_all')}}</a>
                                     <table
                                  class="table table-hover">
                                  <thead>
                                  <tr>
                                    <th>{{getPhrase('title')}}</th>
                                    <th>{{getPhrase('category')}}</th>
                                    <!--<th>{{getPhrase('code')}}</th>-->
                                    <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="i in savedSeries track by $index">
                                        <td>@{{ savedSeries[$index].title}}</td>
                                        <td title="@{{ savedSeries[$index].content_type}}">@{{ savedSeries[$index].content_type  }}</td>
                                        <!--<td>@{{ savedSeries[$index].code}}</td>-->
                                        <td><a ng-click="removeItem(i)" class="btn-outline btn-close text-red"><i class="fa fa-close"></i></a></td>
                                        </tr>
                                    </tbody>
                                    </table>
                                 </div>
                                 <div class="buttons text-center" >
                            <button class="btn btn-lg btn-success button">{{getPhrase('update')}}</button>
                        </div>
            </div>
        </div>
    </div>
    </div>
</div>

{!! Form::close() !!}

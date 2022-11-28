
@include('common.angular-factory')
<script >

     app.controller('users_controller', function ($scope, $http, httpPreConfig) {

      $scope.parent_login = '';
      $scope.showSearch = false;
      $scope.userDetails = false;
      $scope.parents = [];
      $scope.parent_user_name = '';
      $scope.parent_email = '';
      $scope.parent_user_id = '';
      $scope.parent_name = '';
	  $scope.facilitators = [];
	  
	  $scope.initAngData = function(data) {        
        if(data === undefined)
            return;            
        if(data=='')
        {
            $scope.series   = [];
            return;
        }
        dta = data;
        $scope.savedSeries = dta.contents;
        //$scope.setItem('saved_series', $scope.savedSeries);
        //$scope.setItem('total_items', $scope.total_items);
    }


      $scope.accountAvailable = function (availability)
      {

        if(!availability)
        {
          $scope.userDetails = true;
          $scope.showSearch = false;
          $scope.resetDetails();
        }
        else {
          $scope.resetDetails();
          $scope.showSearch = true;
          $scope.userDetails = false;
        }
        // URL_SEARCH_PARENT_RECORDS
      }

      $scope.resetDetails = function(){
        $scope.parent_user_name = '';
        $scope.parent_name = '';
        $scope.parent_email = '';
        $scope.parent_user_id = '';
        $scope.parents = [];
      }


      $scope.setAsCurrentItem = function (record) {
        $scope.parent_name = record.name;
        $scope.parent_user_name = record.username;
        $scope.parent_email = record.email;
        $scope.parent_user_id = record.id;
         $scope.userDetails = true;
      }

	  $scope.getParentRecords = function (text) {

        route   = '{{URL_SEARCH_PARENT_RECORDS}}';
        data    = {   _method: 'post',
                  '_token':httpPreConfig.getToken(),
                  'search_text': text,
                  'user_id': $scope.current_user_id,
                  };

       httpPreConfig.webServiceCallPost(route, data).then(function(result){
            result = result.data;
        users = [];

        angular.forEach(result, function(value, key) {

            users.push(value);
          })

        $scope.parents = users;

        });
      }
	  
	  $scope.removeItem = function (coach_id, facilitator_id) {
		  
			route   = '{{URL_ASSIGN_FACILITATORS_REMOVEFROMBAG}}';
			data    = {   _method: 'post',
				'_token':httpPreConfig.getToken(),
				'coach_id': coach_id,
				'facilitator_id': facilitator_id,
			};
			
			httpPreConfig.webServiceCallPost(route, data).then(function(result){
				result = result.data;
				console.log( result );
				if ( result.status == 'success' ) {
					jQuery('#' + facilitator_id).remove();
					alertify.success(result.message);
				} else {
					alertify.error(result.message);
				}
			});
      }
 });

function addToBag( coach_id, facilitator_id )
{
	var token = jQuery('[name="csrf_token"]').attr('content');
	var route = '{{URL_ASSIGN_FACILITATORS_ADDTOBAG}}';
	var data= {_method: 'post', '_token':token, coach_id: coach_id, facilitator_id:facilitator_id };
	jQuery.ajax({
		headers: {
			  'X-CSRF-TOKEN': token
		},
		url : route,
		data: data,
		type : 'post',
		success : function( response ) {
			var result = jQuery.parseJSON( response );
			if ( result.status == 'success' ) {
				jQuery('#facilitators_table').append(result.facilitator_tr);
				alertify.success(result.message);
			} else {
				alertify.error(result.message);
			}
			
			return;			
		},
		error: function( response ) {
			console.log(response )
		}
	});
}

function removeItem( coach_id, facilitator_id )
{
	// jQuery('#'+facilitator_id).remove();
	var token = jQuery('[name="csrf_token"]').attr('content');
	var route = '{{URL_ASSIGN_FACILITATORS_REMOVEFROMBAG}}';
	var data= {_method: 'post', '_token':token, coach_id: coach_id, facilitator_id:facilitator_id };
	jQuery.ajax({
		headers: {
			  'X-CSRF-TOKEN': token
		},
		url : route,
		data: data,
		type : 'post',
		success : function( response ) {
			var result = jQuery.parseJSON( response );
			if ( result.status == 'success' ) {
				jQuery('#' + facilitator_id).remove();
				alertify.success(result.message);
			} else {
				alertify.error(result.message);
			}			
			return;			
		},
		error: function( response ) {
			console.log(response )
		}
	});
}

</script>
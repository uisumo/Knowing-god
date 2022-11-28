<script src="{{JS}}angular.js"></script>
 <script src="{{JS}}ngStorage.js"></script>
<script src="{{JS}}angular-messages.js"></script>

<script >
  var app = angular.module('academia', ['ngMessages']);
</script>
@include('common.angular-factory',array('load_module'=> FALSE))

<script>
app.controller('prepareQuestions', function( $scope, $http, httpPreConfig) {
   $scope.savedItems = [];
   $scope.savedSeries =  [];
   $scope.total_items = 0;
   
    $scope.initAngData = function(data) {
        
        if(data === undefined)
            return;
        $scope.removeAll();
    
        if(data=='')
        {
            $scope.series   = [];
            return;
        }

        dta = data;
        $scope.savedSeries = dta.contents;
        $scope.setItem('saved_series', $scope.savedSeries);
        $scope.setItem('total_items', $scope.total_items);
    }
    
     $scope.categoryChanged = function(selected_number) {
        
        if(selected_number=='')
            selected_number = $scope.category_id;
        category_id = selected_number;
        if(category_id === undefined)
            return;
        route = '{{URL_LMS_SERIES_GET_SERIES}}';  
        data= {_method: 'post', '_token':httpPreConfig.getToken(), 'category_id': category_id, 'type':'content'};
       
            httpPreConfig.webServiceCallPost(route, data).then(function(result){
            result = result.data;
            $scope.categoryItems = [];
            $scope.categoryItems = result.items;
            $scope.removeDuplicates();
        });
        }
		
		$scope.categoryChangedCourses = function(selected_number) {        
			if(selected_number=='')
				selected_number = $scope.category_id;
			category_id = selected_number;
			if(category_id === undefined)
				return;
			route = '{{URL_LMS_SERIES_GET_SERIES}}';  
			data= {_method: 'post', '_token':httpPreConfig.getToken(), 'category_id': category_id, 'type':'course'};
		   
				httpPreConfig.webServiceCallPost(route, data).then(function(result){
				result = result.data;
				$scope.categoryItems = [];
				$scope.categoryItems = result.items;
				$scope.removeDuplicates();
			});
        }
		
		$scope.categoryChangedPosts = function(selected_number) {        
			if(selected_number=='')
				selected_number = $scope.category_id;
			category_id = selected_number;
			if(category_id === undefined)
				return;
			route = '{{URL_LMS_SERIES_GET_SERIES}}';  
			data= {_method: 'post', '_token':httpPreConfig.getToken(), 'category_id': category_id, 'type':'posts'};
		   
				httpPreConfig.webServiceCallPost(route, data).then(function(result){
				result = result.data;
				$scope.categoryItems = [];
				$scope.categoryItems = result.items;
				$scope.removeDuplicates();
			});
        }

        $scope.removeDuplicates = function(){
           
            if($scope.savedSeries.length<=0 )
                return;

             angular.forEach($scope.savedSeries,function(value,key){
                    
                    res = httpPreConfig.findIndexInData($scope.categoryItems, 'id', value.id);
                    if(res >= 0)
                    {
                         $scope.categoryItems.splice(res, 1);
                    }
                    
            });
        }
          
        $scope.addToBag = function(item) {
           var record = item; 
            console.log( item.id );
              res = httpPreConfig.findIndexInData($scope.savedSeries, 'id', item.id);
                    if(res == -1) {
                      $scope.savedSeries.push(record); 
                      
                      $scope.removeFromCategoryItems(item);
                    }
                  else 
                    return;

           //Push record to storage
            $scope.setItem('saved_series', $scope.savedSeries);
        }

        $scope.removeFromCategoryItems = function(item) { 
             var index = $scope.categoryItems.indexOf(item);
             $scope.categoryItems.splice(index, 1);     
        }

        $scope.addToCategoryItems = function(item) { 
          
             if($scope.categoryItems.length) {
                
                if($scope.categoryItems[0].subject_id != item.subject_id)
                    return;

                 res = httpPreConfig.findIndexInData($scope.savedSeries, 'id', item.id)
                
                    if(res == -1)
                      $scope.categoryItems.push(item);     
                return;
             }
             $scope.categoryChanged($scope.category_id);
        }


        /**
         * Set item to local storage with the sent key and value
         * @param {[type]} $key   [localstorage key]
         * @param {[type]} $value [value]
         */
        $scope.setItem = function($key, $value){
            localStorage.setItem($key, JSON.stringify($value));
        }

        /**
         * Get item from local storage with the specified key
         * @param  {[type]} $key [localstorage key]
         * @return {[type]}      [description]
         */
        $scope.getItem = function($key){
            return JSON.parse(localStorage.getItem($key));
        }

        /**
         * Remove question with the sent id
         * @param  {[type]} id [description]
         * @return {[type]}    [description]
         */
         

    $scope.removeItem = function(record){
        
          $scope.savedSeries = $scope.savedSeries.filter(function(element){
            if(element.id != record.id)
              return element;
          });
           
          $scope.setItem('saved_series', $scope.savedSeries);
          $scope.addToCategoryItems(record);
        }

        $scope.removeAll = function(){
            $scope.savedSeries = [];
            $scope.totalQuestions       = 0;
            $scope.setItem('saved_questions', $scope.savedSeries);
            $scope.setItem('total_questions', $scope.totalQuestions);
            $scope.categoryChanged($scope.category_id);
        }

		$scope.removeAllCourses = function(){
            $scope.savedSeries = [];
            $scope.totalQuestions       = 0;
            $scope.setItem('saved_questions', $scope.savedSeries);
            $scope.setItem('total_questions', $scope.totalQuestions);
            $scope.categoryChangedCourses($scope.category_id);
        }		

}  );

app.filter('cut', function () {
        return function (value, wordwise, max, tail) {
            if (!value) return '';

            max = parseInt(max, 10);
            if (!max) return value;
            if (value.length <= max) return value;

            value = value.substr(0, max);
            if (wordwise) {
                var lastspace = value.lastIndexOf(' ');
                if (lastspace != -1) {
                  //Also remove . and , so its gives a cleaner result.
                  if (value.charAt(lastspace-1) == '.' || value.charAt(lastspace-1) == ',') {
                    lastspace = lastspace - 1;
                  }
                  value = value.substr(0, lastspace);
                }
            }

            return value + (tail || ' …');
        };
    });

	function add_remove_group( group_id, user_id, action, operation_type )
	{		
		$.ajax({		  
		  method: "POST",
		  url: "{{URL_STUDENT_UPDATE_GROUP_INVITATIONS_ADD_REMOVE_USERS}}",
		  data: { group_id: group_id, user_id: user_id, action:action, _method: 'post', '_token':$('meta[name="csrf_token"]').attr('content'),operation_type:operation_type }
		}).done(function( result ) {
			var result = $.parseJSON( result );
			if (result.status == 'success') {				
				if ( result.operation_type == 'group' ) {
					$('#user_button_' + result.group_id + '_' + result.action ).text( result.button_text );
					$('#user_button_' + result.group_id + '_' + result.action ).prop('disabled', 'disabled');
					if ( result.action == 'accept' ) {
						$('#user_button_' + result.group_id + '_reject' ).hide();
					} else {
						$('#user_button_' + result.group_id + '_accept' ).hide();
					}
					
				} else {
					$('#user_button_' + result.user_id + '_' + result.action ).text( result.button_text );
					$('#user_button_' + result.user_id + '_' + result.action ).prop('disabled', 'disabled');
				}				
				alertify.success(result.message);
			} else {
				$('#user_button_' + result.user_id ).text( result.button_text );
				alertify.error(result.message);
			}			
		  });
	}
	
	function get_group_comment( group_id )
	{
		$('#dashboardModal').modal('toggle');
		$.ajax({		  
		  method: "POST",
		  url: "{{URL_FRONTEND_GET_DATA}}",
		  data: { group_id: group_id, action:'get_groupcomments', _method: 'post', '_token':$('meta[name="csrf_token"]').attr('content') }
		}).done(function( result ) {
			$('#contenthtml').html(result.html);
		});
	}

	function saveComments()
	{
		var group_id = $('#modal_item_id').val();
		var modal_commnets = $('#modal_commnets').val();
		
		$.ajax({		  
		  method: "POST",
		  url: "{{URL_FRONTEND_SAVE_DATA}}",
		  data: { group_id: group_id, comments:modal_commnets, action:'save_groupcomments', _method: 'post', '_token':$('meta[name="csrf_token"]').attr('content') }
		}).done(function( result ) {
			$('#commentsModal').modal('toggle');
			var result = $.parseJSON( result );
			$('#modal_commnets').val( '' );
			// $('#modal_item_id').val( result.group_id );
			if (result.status == '1') {							
				alertify.success(result.message);
			} else {
				alertify.error(result.message);
			}
		});
		
		
		/*
		$.ajax({		  
		  method: "POST",
		  url: "{{URL_FRONTEND_SAVE_DATA}}",
		  data: { group_id: group_id, action:'groupcomments', _method: 'post', '_token':$('meta[name="csrf_token"]').attr('content') }
		}).done(function( result ) {
			var result = $.parseJSON( result );
			if (result.status == 'success') {							
				alertify.success(result.message);
			} else {
				alertify.error(result.message);
			}			
		  });
		  */
	}
	
	function addToBag( post_id, group_id )
	{				
		$.ajax({		  
		  method: "POST",
		  url: "{{URL_FRONTEND_SAVE_DATA}}",
		  data: { post_id:post_id, group_id: group_id, action:'add_posttogroup', _method: 'post', '_token':$('meta[name="csrf_token"]').attr('content') }
		}).done(function( result ) {
			var result = $.parseJSON( result );
			$('#post_' . result.post_id).attr('disabled', true);
			if (result.status == '1') {							
				alertify.success(result.message);
			} else {
				alertify.error(result.message);
			}
		});
	}
</script>
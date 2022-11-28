
<script src="{{JS}}angular.js"></script>
<script src="{{JS}}angular-messages.js"></script>

<script>
var app = angular.module('academia', ['ngMessages']);
app.controller('angLmsController', function($scope, $http) {
    $scope.getToken = function(){
      return  $('[name="_token"]').val();
    }
        
    $scope.initAngData = function(data) {
        if(data=='')
        {
            $scope.series = '';
            $scope.content_type = '';
            return;
        }
         data = JSON.parse(data);
         $scope.content_type    = data.content_type;
    }
	
	$scope.getModules = function() {
		var course_id = $('#course_id').val();
		var req = {
			 method: 'POST',
			 url: '{{URL_GET_COURSE_MODULES}}',
			 data: {
				_method: 'post',
				'_token':$scope.getToken(),
				course_id: course_id
				}
			}
        
       
		$http(req).success(function(result, status) {
			$('#module_id').html( result.html );
        });
	}
});
 
</script>
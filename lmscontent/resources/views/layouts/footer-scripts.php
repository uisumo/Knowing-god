<script src="{{JS}}angular.js"></script>
<script src="{{JS}}angular-messages.js"></script>

<script>
var app = angular.module('academia', []);
app.controller('siteissueCtrl', function($scope, $http) {
	$scope.saveIssue = function() {
		var issue_description = $('#issue_description').val();
		if( issue_description == '' ) {
			alertify.error('{{getPhrase("Please enter your description")}}');
		} else {
			var req = {
			 method: 'POST',
			 url: '{{URL_FRONTEND_SAVE_DATA}}',
			 data: {
				_method: 'post',
				'_token':$scope.getToken(),
				action: 'save_issue',
				issue_description: issue_description
				}
			}
			$http(req).success(function(result, status) {
				$('#issue_description').val('');
				$('#siteissuesModal').modal('toggle');
				alertify.success(result.message);
			});
		}
	};
});
</script>
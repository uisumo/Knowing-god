<!--<script src="{{JS}}angular.js"></script>-->
<script src="{{JS}}angular-messages.js"></script>

<script>
var app = angular.module('academia', []);
app.controller('singleLessonCtrl', function($scope, $http) {
	$scope.comments = [];
	$scope.notes = [];

	$scope.getToken = function(){
      return  $('[name="_token"]').val();
    }
	$scope.open_login_modal = function( redirect_url ) {
		$('#loginModal').modal('toggle');
		if ( typeof( redirect_url ) != 'undefined' ) {
			$('#redirect_url').val( redirect_url );
		}
	}
	$scope.mark_as_complete = function( content_id, type, course_id, module_id, content_type, group_id ) {
		@if(Auth::check())
			var req = {
			 method: 'POST',
			 url: '{{URL_FRONTEND_SAVE_DATA}}',
			 data: {
				_method: 'post',
				'_token':$scope.getToken(),
				action: 'lms_track',
				content_id:content_id,
				type: type,
				course_id: course_id,
				module_id: module_id,
				content_type: content_type,
				group_id: group_id
				}
			}
			$http(req).success(function(result, status) {
				if ( type == 'text-uncomplete' ) {
					$('#text_icon').attr('class','icon icon-tick-double');
					$('#text_icon').parent().closest('button').attr('onclick', '');
				}
				if ( type == 'text' ) {
					$('#text_icon').attr('class','icon icon-tick-border');
					$('#text_icon').parent().closest('button').attr('onclick', '');
				}
				
				if ( type == 'video-uncomplete' ) {
					$('#video_icon').attr('class','icon icon-tick-double');
					$('#video_icon').parent().closest('button').attr('onclick', '');
				}
				if ( type == 'video' ) {
					$('#video_icon').attr('class','icon icon-tick-border');
					$('#video_icon').parent().closest('button').attr('onclick', '');
				}
				
				if ( type == 'quiz-uncomplete' ) {
					$('#quiz_icon').attr('class','icon icon-tick-double');
					$('#quiz_icon').parent().closest('button').attr('onclick', '');
				}
				if ( type == 'quiz' ) {
					$('#quiz_icon').attr('class','icon icon-tick-border');
					$('#quiz_icon').parent().closest('button').attr('onclick', '');
				}
				if ( result.is_completed == 'yes' ) {
					$('#overall_status').attr('class','lesson-pin icon icon-pointer-border');
				} else {
					$('#overall_status').attr('class','lesson-pin icon icon-map-pointer');
				}
				alertify.success(result.message);

			});
		@else
		$('#loginModal').modal('toggle');
		// showMessage('{{getPhrase("Please login to do this operation")}}');
		@endif
	};
	
	$scope.make_my_course = function( course_id ) {
		@if(Auth::check())
			var req = {
			 method: 'POST',
			 url: '{{URL_FRONTEND_SAVE_DATA}}',
			 data: {
				_method: 'post',
				'_token':$scope.getToken(),
				action: 'make_my_course',
				course_id:course_id
				}
			}
			$http(req).success(function(result, status) {
				$('#my_course_icon_' + course_id ).attr('class','fa fa-heart');
				alertify.success(result.message);
			});
		@else
			$('#loginModal').modal('toggle');
		@endif
	};

	$scope.saveComments = function( content_id, type ) {
		
		var comments = $('#comments').val();
		if ( typeof(content_id) == 'undefined' || content_id == '' ) {
			var content_id = $('#item_id').val();
		}
		if( comments == '' ) {
			alertify.error('{{getPhrase("Please enter your comments")}}');
		} else {
			var req = {
			 method: 'POST',
			 url: '{{URL_FRONTEND_SAVE_DATA}}',
			 data: {
				_method: 'post',
				'_token':$scope.getToken(),
				action: 'save_comments',
				content_id:content_id,
				comments: comments,
				type:type
				}
			}
			$http(req).success(function(result, status) {
				$('#comments').val('');
				$('#commentsModal').modal('toggle');
				alertify.success(result.message);
			});
		}
	};

	$scope.saveNotes = function( content_id, type, c_g_id ) {

		if ( typeof(content_id) == 'undefined' || content_id == '' ) {
			var content_id = $('#item_id').val();
		}
		var notes = $('#notes').val();
		if( notes == '' ) {
			alertify.error('{{getPhrase("Please enter your notes")}}');
		} else {
			var req = {
			 method: 'POST',
			 url: '{{URL_FRONTEND_SAVE_DATA}}',
			 data: {
				_method: 'post',
				'_token':$scope.getToken(),
				action: 'save_notes',
				content_id:content_id,
				notes: notes,
				type: type,
				c_g_id: c_g_id
				}
			}
			$http(req).success(function(result, status) {
				$('#notes').val('');
				$('#notesModal').modal('toggle');
				alertify.success(result.message);
			});
		}
	};
	
	$scope.saveRequest = function( content_id ) {
		
		if ( typeof(content_id) == 'undefined' || content_id == '' ) {
			var content_id = $('#item_id').val();
		}
		var description = $('#description').val();
		var user_id = 0;
		@if ( ! Auth::check() )
			var full_name = $('#full_name').val();
			var email = $('#email').val();
			if( full_name == '' ) {
				alertify.error('{{getPhrase("Please enter your full name")}}');
				return false;
			}
			if( email == '' ) {
				alertify.error('{{getPhrase("Please enter your email address")}}');
				return false;
			} else {
				 var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
				if ( ! pattern.test(email) ) {
				alertify.error('{{getPhrase("Please enter valid email address")}}');
				return false;
				}
			}			
		@else
			var full_name = '{{Auth::User()->name}}';
			var email = '{{Auth::User()->email}}';
			user_id = '{{Auth::User()->id}}';
		@endif
		if( description == '' ) {
			alertify.error('{{getPhrase("Please enter your description")}}');
		} else {
			var req = {
			 method: 'POST',
			 url: '{{URL_FRONTEND_SAVE_DATA}}',
			 data: {
				_method: 'post',
				'_token':$scope.getToken(),
				action: 'save_translation',
				content_id:content_id,
				description: description,
				full_name: full_name,
				email: email,
				user_id: user_id
				}
			}
			$http(req).success(function(result, status) {
				
				$('#description').val('');
				@if ( ! Auth::check() )
					$('#full_name').val('');
					$('#email').val('');
				@endif
				$('#translationIssueModal').modal('toggle');
				alertify.success(result.message);
			});
		}
	};

	$scope.getData = function( content_id, type, course ) {
		var route = '{{URL_FRONTEND_GET_DATA}}';
		if ( typeof( course ) == 'undefined' ) {
			var course = '';
		}
		var data= {_method: 'post', '_token':$scope.getToken(), content_id: content_id, action:type, course:course};
		$('#item_id').val( content_id );
		$http.post(route, data).success(function(result, status) {

			if ( type == 'notes' ) {
				$('#notes_list').html( result.html );
			}
			if ( type == 'comments' ) {
				$('#comments_list').html( result.html );
			}
			if ( type == 'sharecontent' ) {
				$('#socialshareContent').html( result.html );
			}
		});
	};

	$scope.ajaxLogin = function() {
		var email = $('#email').val();
		var password = $('#password').val();
		if( email == '' ) {
			alertify.error('{{getPhrase("Please enter your email OR username")}}');
			return;
		}
		if( password == '' ) {
			alertify.error('{{getPhrase("Please enter your password")}}');
			return;
		}
		
		if ( email !== '' && password != ''  ) {
			var route = '{{URL_FRONTEND_AJAXLOGIN}}';
			var data= {_method: 'post', '_token':$scope.getToken(), email: email, password:password, redirect_to: $('#redirect_url').val() };
			$http.post(route, data).success(function(result, status) {
				
				if ( result.status == 1 ) {
					$('#loginModal').modal('toggle');
					alertify.success(result.message, 5);
					window.location = result.redirect_to;
					// window.location = '{{URL_WP_LOGIN}}?wp_user_id=' + result.wp_user_id + '&redirect_to=' + result.redirect_to;
				} else {
					alertify.error(result.message);
					return;
				}


			});
		}
	};
	
	$scope.start_exam = function( quiz_id ) {
		var route = '{{URL_STUDENT_EXAM_INSTRUCTIONS}}/' + quiz_id;
		var data= {_method: 'get', '_token':$scope.getToken(), quiz_id: quiz_id};
		$http.get(route, data).success(function(result, status) {
			if ( result.status == 0 ) {
				if ( result.reason == 'not_login' ) {
					$('#quizModal').modal({show: false});
					$('#loginModal').modal('toggle');
				}
				if ( result.reason == 'exam_aborted' ) {
					window.location = result.redirect_to;
				}
			} else {
				$('#quizContent').html(result.html);
			}
		});
	}
	
	$scope.showSerieses = function( category_slug ) {
		var route = '{{URL_LMS_SHOW_SERIESES}}' + category_slug;
		var data= {_method: 'get', '_token':$scope.getToken(), category_slug: category_slug};
		$http.get(route, data).success(function(result, status) {
			$('#seriesesModal').modal('toggle');
			if ( result.status == 0 ) {
				if ( result.reason == 'not_valid' ) {
					$('#serieses_list').html(result.reason);
				}
				
			} else {
				$('#serieses_list').html(result.html);
			}
		});
	}
	
	$scope.fetch_lessons = function( slug ) {
		var route = '{{URL_FRONTEND_GET_DATA}}';
		var data= {_method: 'post', '_token':$scope.getToken(), slug: slug, action: 'fetch_lessons'};
		$http.post(route, data).success(function(result, status) {			
			$('#lessonsContent').html(result.html);
		});
	};
	/*
	$scope.showCourses = function( category_slug ) {
		var route = '{{URL_FRONTEND_GET_DATA}}';
		var data= {_method: 'post', '_token':$scope.getToken(), slug: category_slug, action: 'fetch_courses'};
		$http.post(route, data).success(function(result, status) {			
			$('#coursesModal').modal('toggle');
			$('#coursesList').html(result.html);
		});
	}
	*/
	
	$scope.showCourses = function( category_slug ) {
		var route = '{{URL_LMS_GET_COURSES}}' + category_slug;
		var data= {_method: 'get', '_token':$scope.getToken(), category: category_slug,};
		$http.get(route, data).success(function(result, status) {			
			$('#coursesModal').modal('toggle');
			$('#coursesList').html(result.html);
		});
	}
	
	$scope.show_recommended = function() {
		var route = '{{URL_FRONTEND_GET_DATA}}';
		var data= {_method: 'post', '_token':$scope.getToken(), action: 'fetch_recommended'};
		$http.post(route, data).success(function(result, status) {			
			$('#coursesModal').modal('toggle');
			$('#coursesList').html(result.html);
		});
	}
	
	$scope.showMessages = function() {
		var route = '{{URL_FRONTEND_GET_DATA}}';
		var data= {_method: 'post', '_token':$scope.getToken(), action: 'fetch_messages'};
		$http.post(route, data).success(function(result, status) {			
			$('#dashboardModal').modal('toggle');
			$('#contenthtml').html(result.html);
		});
	}
	
	$scope.showComments = function( content_id, page_name, course_id, module_id ) {
		var route = '{{URL_FRONTEND_GET_DATA}}';		
		var data= {_method: 'post', '_token':$scope.getToken(), content_id: content_id, action:'comments', page_name:page_name, course_id:course_id, module_id:module_id};
		$http.post(route, data).success(function(result, status) {			
			$('#dashboardModal').modal('toggle');
			$('#contenthtml').html(result.html);
		});
	}
	
	
	$scope.showCoachform = function() {
		var route = '{{URL_FRONTEND_GET_DATA}}';
		var data= {_method: 'post', '_token':$scope.getToken(), action: 'fetch_coachform'};
		$http.post(route, data).success(function(result, status) {			
			$('#dashboardModal').modal('toggle');
			$('#contenthtml').html(result.html);
		});
	}
	
	$scope.withdrawCoachRequest = function() {
		var route = '{{URL_FRONTEND_GET_DATA}}';
		var data= {_method: 'post', '_token':$scope.getToken(), action: 'withdraw_coachform'};
		$http.post(route, data).success(function(result, status) {			
			$('#dashboardModal').modal('toggle');
			$('#contenthtml').html(result.html);
		});
	}
	
	$scope.addFriend = function() {
		var route = '{{URL_FRONTEND_GET_DATA}}';
		var data= {_method: 'post', '_token':$scope.getToken(), action: 'add_friend'};
		$http.post(route, data).success(function(result, status) {			
			$('#dashboardModal').modal('toggle');
			$('#contenthtml').html(result.html);
		});
	}

});

function open_login_modal ( redirect_url ) {
	$('#loginModal').modal('toggle');
	if ( typeof( redirect_url ) != 'undefined' ) {
		$('#redirect_url').val( redirect_url );
	}
}

function ajaxLogin() {
		
		var email = jQuery('#email').val();
		var password = jQuery('#password').val();
		var token = jQuery('#csrf').val();
		if( email == '' ) {
			alertify.error('{{getPhrase("Please enter your email OR username")}}');
			return;
		}
		if( password == '' ) {
			alertify.error('{{getPhrase("Please enter your password")}}');
			return;
		}
		
		if ( email !== '' && password != ''  ) {
			var route = '{{URL_FRONTEND_AJAXLOGIN}}';
			var data= {_method: 'post', '_token':token, email: email, password:password, redirect_to: jQuery('#redirect_url').val() };
			jQuery.ajax({
				headers: {
					  'X-CSRF-TOKEN': token
				},
				url : route,
				data: data,
				type : 'post',
				success : function( response ) {
					var result = jQuery.parseJSON( response );
					if ( result.status == 1 ) {
						jQuery('#loginModal').modal('toggle');
						alertify.success(result.message);
						window.location = result.redirect_to;
						// window.location = '{{URL_WP_LOGIN}}?wp_user_id=' + result.wp_user_id + '&redirect_to=' + result.redirect_to;
					} else {
						alertify.error(result.message);
						return;
					}
				}
			});
		}
	}
	
jQuery( document ).ready(function( $ ){
	$('.btn-course-start').click(function(){
		var slug = $(this).data('slug');
		$('#start_course_slug').val(slug);
	});
});

function make_my_course ( course_id ) {
	@if(Auth::check())
	var route = '{{URL_FRONTEND_SAVE_DATA}}';	
	var token = jQuery('#csrf').val();
	var data= {_method: 'post', '_token':token, action: 'make_my_course', course_id:course_id };
		jQuery.ajax({
			headers: {
				  'X-CSRF-TOKEN': token
			},
			url : route,
			data: data,
			type : 'post',
			success : function( response ) {
				
				var result = jQuery.parseJSON( response );
				jQuery('#my_course_icon_' + course_id ).attr('class','fa fa-heart');
				alertify.success(result.message);
			}
		});
	@else
		$('#loginModal').modal('toggle');
	@endif
};
</script>
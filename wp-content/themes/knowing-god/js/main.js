/*
Copyright (c) 2017
[Master Stylesheet]
Theme Name : Knowing God
Version    : 1.0
Author: Digisamaritan
Support    : digisamaritan@gmail.com
*/
/*---------------------------------------------
Table of Contents
-----------------------------------------------
Owl Corousel
----------------------------------------*/
jQuery(document).ready(function ($) {
    "use strict";
    /*Partner-slide at the bottom- Owl Carousal*/
    $(".ap-partner-slide").owlCarousel({
        items: 6
        , loop: true
        , dots: false
        , autoplay: true
        , responsive: {
            0: {
                items: 1
            }
            , 400: {
                items: 2
            }
            , 560: {
                items: 3
            }
            , 900: {
                items: 4
            }
            , 1200: {
                items: 6
            }
        }
    });

    $('.quizConfirm').click(function(){
        $('#quizModal').modal('toggle');
        var post_id = $(this).data('post_id');
		var course_id = $(this).data('course_id');

        $.ajax({
                url : ajaxurl,
                type : 'post',
                data : {
                    action : 'knowing_god_quiz_modal_confirm',
                    post_id : post_id,
					course_id: course_id
                },
                success : function( response ) {
                    $('#quizConfirmLink').html(response);
                }
            });
    });

  /* $(".kg-nav-menu .menu-item").hover(function(e){
        e.stopPropagation();
        e.preventDefault();
        $(this).toggleClass("focus");
        console.log("succuess");
    });*/
	
	




});

function globalModal( post_id )
{
	jQuery('#globalModal').modal('toggle');
    jQuery.ajax({
                url : ajaxurl,
                type : 'post',
                data : {
                    action : 'knowing_god_global_modal',
                    post_id : post_id
                },
                success : function( response ) {
                    jQuery('#item_id').val(response);
                }
            });
}

function saveRequest( content_id )
{
	if ( typeof(content_id) == 'undefined' || content_id == '' ) {
			var content_id = jQuery('#item_id').val();
	}
	
	var description = jQuery('#description').val();
	var user_id = 0;
	var full_name = '';
	var email = '';
	if ( jQuery('#full_name').length ) {
		full_name = jQuery('#full_name').val();
	}
	if ( jQuery('#email').length ) {
		email = jQuery('#email').val();
	}
	
	if ( jQuery('#full_name').length && jQuery('#full_name').val() == '' ) {
		alertify.error('Please enter your full name');
		return false;
	}
	if ( jQuery('#email').length ) {
		if ( jQuery('#email').val() == '' ) {
			alertify.error('Please enter your email address');
			return false;
		} else {
			var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
			if ( ! pattern.test(email) ) {
				alertify.error('Please enter valid email address');
				return false;
			}
		}
	}
	if( description == '' ) {
			alertify.error('Please enter your description');
			return false;
	}
	
	jQuery.ajax({
                url : ajaxurl,
                type : 'post',
                data : {
                    action : 'knowing_god_global_modal_save',
					content_id:content_id,
					description: description,
					full_name: full_name,
					email: email,
					user_id: user_id,
					siteissue: jQuery('#fromsiteissue').serialize(),
					translation: jQuery('#frmGeneric').serialize(),
                },
                success : function( response ) {
                    jQuery('#description').val('');					
					jQuery('#full_name').val('');
					jQuery('#email').val('');					
					jQuery('#globalModal').modal('toggle');
					alertify.success(response);
                }
            });
}

function show_login( slug ) {
    jQuery('#loginModal').modal('toggle');
    jQuery.ajax({
            url : lms_url + 'get-login-form',
            type : 'get',
            data : {
                action : 'knowing_god_quiz_modal_confirm'
            },
            success : function( response ) {
                // var result = jQuery.parseJSON( response );
                jQuery('#loginModalContent').html(response.html);
            }
        });
}

function saveIssue() {
	var issue_url = jQuery('#issue_url').val();
	var issue_description = jQuery('#issue_description').val();
	var full_name = jQuery('#issue_full_name').val();
	var email = jQuery('#issue_email').val();
	var user_id = jQuery('#issue_current_user_id').val();
	var content_id = 0;
	

	if( issue_url == '' ) {
		alertify.error('Please specify URL where you find issue');
		return;
	}
	if ( user_id == 0 ) {
		if( full_name == '' ) {
			alertify.error('Please enter full name');
			return;
		}
		if ( jQuery('#issue_email').length ) {
			if ( jQuery('#issue_email').val() == '' ) {
				alertify.error('Please enter your email address');
				return false;
			} else {
				var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
				if ( ! pattern.test(email) ) {
					alertify.error('Please enter valid email address');
					return false;
				}
			}
		}
	}
	
	if( issue_description == '' ) {
		alertify.error('Please enter description');
		return;
	}
	
	if ( issue_url !== '' && issue_description != ''  ) {
		jQuery.ajax({
                url : ajaxurl,
                type : 'post',
                data : {
                    action : 'knowing_god_siteissue_modal_save',
					content_id: content_id,
					issue_url:issue_url,
					description: issue_description,
					full_name: full_name,
					email: email,
					user_id: user_id
                },
                success : function( response ) {
                    // jQuery('#issue_url').val('');		
					jQuery('#issue_description').val('');					
					jQuery('#issue_full_name').val('');
					jQuery('#issue_email').val('');					
					jQuery('#siteissuesModal').modal('toggle');
					alertify.success(response);
                }
            });
	}
}

function getData( course_id, lesson_id, action ) {
	jQuery('#genericModal').modal('toggle');
	jQuery.ajax({
            url : ajaxurl,
            type : 'post',
            data : {
                course_id: course_id,
				lesson_id: lesson_id,
				action: action
            },
            success : function( response ) {
                var result = jQuery.parseJSON( response );
				jQuery('#genericModalContent').html(result.html);
				jQuery('#genericModalLabel').html(result.title);
				if ( result.footer ) {
					jQuery('#genericModalFooter').html(result.footer);
				}
				if ( result.generic_other_list ) {
					jQuery('#generic_other_list').html(result.generic_other_list);					
				}
            }
        });
}

function saveGenericForm()
{	
	var description = jQuery('#frmGeneric textarea[name=description]').val();
	var user_id = 0;
	var full_name = '';
	var email = '';
	if ( jQuery('#frmGeneric input[name=full_name]').length ) {
		full_name = jQuery('#frmGeneric input[name=full_name]').val();
	}
	if ( jQuery('#frmGeneric input[name=email]').length ) {
		email = jQuery('#frmGeneric input[name=email]').val();
	}
	
	if ( jQuery('#frmGeneric input[name=full_name]').length && jQuery('#frmGeneric input[name=full_name]').val() == '' ) {
		alertify.error('Please enter your full name');
		return false;
	}
	if ( jQuery('#frmGeneric input[name=email]').length ) {
		if ( jQuery('#frmGeneric input[name=email]').val() == '' ) {
			alertify.error('Please enter your email address');
			return false;
		} else {
			var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
			if ( ! pattern.test(email) ) {
				alertify.error('Please enter valid email address');
				return false;
			}
		}
	}
	if( description == '' ) {
			alertify.error('Please enter your description');
			return false;
	}
	var data = jQuery('#frmGeneric').serialize() + '&action=save_generic_form';
	jQuery.ajax({
                url : ajaxurl,
                type : 'post',
                data,
                success : function( response ) {
                    jQuery('#description').val('');					
					jQuery('#full_name').val('');
					jQuery('#email').val('');					
					jQuery('#genericModal').modal('toggle');
					alertify.success(response);
                }
            });
}

function ajaxLogin()
{
	jQuery.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: { 
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': jQuery('form#formLogin #email').val(), 
                'password': jQuery('form#formLogin #password').val(),
				'security': jQuery('form#formLogin #security').val(),
				'redirect_back': jQuery('form#formLogin #redirecturl').val()
			},
            success: function(data){
                // $('form#formLogin p.status').text(data.message);
				
                if (data.loggedin == true){
                    alertify.success( data.message );
					if ( data.redirecturl ) {
						document.location.href = data.redirecturl;
					} else {
						document.location.href = jQuery('form#formLogin #redirecturl').val();
					}
                } else {
					alertify.error( data.message );
				}
            }
        });
}

function saveNewsletter() {
	
	var email = jQuery('#newsletteremail').val();
	

	if( issue_url == '' ) {
		alertify.error('Please specify URL where you find issue');
		return;
	}
	
	if ( jQuery('#newsletteremail').length ) {
		if ( jQuery('#newsletteremail').val() == '' ) {
			alertify.error('Please enter your email address');
			return false;
		} else {
			var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
			if ( ! pattern.test(email) ) {
				alertify.error('Please enter valid email address');
				return false;
			}
		}
	}
		
	jQuery.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			action : 'knowing_god_newsletter_modal_save',
			email: email
		},
		success : function( response ) {
			jQuery('#newsletteremail').val('');					
			jQuery('#newsletterModal').modal('toggle');
			alertify.success(response);
		}
	});
}

function mark_as_complete( content_id, type, course_id, module_id )
{
	jQuery.ajax({
		url : ajaxurl,
		type : 'post',
		data : {
			action : 'save_generic_form',
			content_id: content_id,
			type: type,
			course_id: course_id,
			module_id: module_id,
			specific_action: 'lms_track'
		},
		success : function( result ) {
			var result = jQuery.parseJSON( result );
			if ( type == 'text-uncomplete' ) {
				jQuery('#text_icon').attr('class','icon icon-tick-double');
				jQuery('#text_icon').parent().closest('button').attr('onclick', '');
			}
			if ( type == 'text' ) {
				jQuery('#text_icon').attr('class','icon icon-tick-border');
				jQuery('#text_icon').parent().closest('button').attr('onclick', '');
			}
			
			if ( type == 'video-uncomplete' ) {
				jQuery('#video_icon').attr('class','icon icon-tick-double');
				jQuery('#video_icon').parent().closest('button').attr('onclick', '');
			}
			if ( type == 'video' ) {
				jQuery('#video_icon').attr('class','icon icon-tick-border');
				jQuery('#video_icon').parent().closest('button').attr('onclick', '');
			}
			
			if ( type == 'quiz-uncomplete' ) {
				jQuery('#quiz_icon').attr('class','icon icon-tick-double');
				jQuery('#quiz_icon').parent().closest('button').attr('onclick', '');
			}
			if ( type == 'quiz' ) {
				jQuery('#quiz_icon').attr('class','icon icon-tick-border');
				jQuery('#quiz_icon').parent().closest('button').attr('onclick', '');
			}
			if ( result.is_completed == 'yes' ) {
				jQuery('#overall_status').attr('class','lesson-pin icon icon-pointer-border');
			} else {
				jQuery('#overall_status').attr('class','lesson-pin icon icon-map-pointer');
			}
			alertify.success(result.message);
		}
	});
}

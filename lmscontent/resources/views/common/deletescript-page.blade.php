

<script>



	function deleteRecordModule(slug, type) {

	swal({

		  title: "{{getPhrase('are_you_sure')}}?",

		  text: "{{getPhrase('you_will_not_be_able_to_recover_this_record')}}!",

		  type: "warning",

		  showCancelButton: true,

		  confirmButtonClass: "btn-danger",

		  confirmButtonText: "{{getPhrase('yes').', '.getPhrase('delete_it')}}!",

		  cancelButtonText: "{{getPhrase('no').', '.getPhrase('cancel_please')}}!",

		  closeOnConfirm: false,

		  closeOnCancel: false

		},

		function(isConfirm) {

		  if (isConfirm) {

		  	  var token = '{{ csrf_token()}}';

		  	route = '{{URL_LMS_SERIES_DELETE}}' + slug +'/editcourse';  

		    $.ajax({

		        url:route,

		        type: 'post',

		        data: {_method: 'delete', _token :token},

		        success:function(msg){



		        	result = $.parseJSON(msg);
                    
		        	if(typeof result == 'object')

		        	{

		        		status_message = '{{getPhrase('deleted')}}';

		        		status_symbox = 'success';

		        		status_prefix_message = '';

		        		if(!result.status) {

		        			status_message = '{{getPhrase('sorry')}}';

		        			status_prefix_message = '{{getPhrase("cannot_delete_this_record_as")}}\n';

		        			status_symbox = 'info';

		        		}

		        		// swal(status_message+"!", status_prefix_message+result.message, status_symbox);

		        	}

		        	else {

		        	// swal("{{getPhrase('deleted')}}!", "{{getPhrase('your_record_has_been_deleted')}}", "success");

		        	}

		        	// tableObj.ajax.reload();
					document.location = '{{url()->current()}}' + '?tab=modules';

		        }

		    });



		  } else {

		    swal("{{getPhrase('cancelled')}}", "{{getPhrase('your_record_is_safe')}} :)", "error");

		  }

	});

	}
	
	function deleteRecordLesson(slug) {

	swal({

		  title: "{{getPhrase('are_you_sure')}}?",

		  text: "{{getPhrase('you_will_not_be_able_to_recover_this_record')}}!",

		  type: "warning",

		  showCancelButton: true,

		  confirmButtonClass: "btn-danger",

		  confirmButtonText: "{{getPhrase('yes').', '.getPhrase('delete_it')}}!",

		  cancelButtonText: "{{getPhrase('no').', '.getPhrase('cancel_please')}}!",

		  closeOnConfirm: false,

		  closeOnCancel: false

		},

		function(isConfirm) {

		  if (isConfirm) {

		  	  var token = '{{ csrf_token()}}';

		  	route = '{{URL_LMS_CONTENT_DELETE}}'+slug;  

		    $.ajax({

		        url:route,

		        type: 'post',

		        data: {_method: 'delete', _token :token},

		        success:function(msg){



		        	result = $.parseJSON(msg);
                    
		        	if(typeof result == 'object')

		        	{

		        		status_message = '{{getPhrase('deleted')}}';

		        		status_symbox = 'success';

		        		status_prefix_message = '';

		        		if(!result.status) {

		        			status_message = '{{getPhrase('sorry')}}';

		        			status_prefix_message = '{{getPhrase("cannot_delete_this_record_as")}}\n';

		        			status_symbox = 'info';

		        		}

		        		swal(status_message+"!", status_prefix_message+result.message, status_symbox);

		        	}

		        	else {

		        	swal("{{getPhrase('deleted')}}!", "{{getPhrase('your_record_has_been_deleted')}}", "success");

		        	}

		        	// tableObj.ajax.reload();
					document.location = '{{url()->current()}}' + '?tab=lessons';

		        }

		    });



		  } else {

		    swal("{{getPhrase('cancelled')}}", "{{getPhrase('your_record_is_safe')}} :)", "error");

		  }

	});

	}

</script>
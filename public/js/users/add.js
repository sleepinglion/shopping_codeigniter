!function(a){a.fn.datepicker.dates.ko={days:["일요일","월요일","화요일","수요일","목요일","금요일","토요일"],daysShort:["일","월","화","수","목","금","토"],daysMin:["일","월","화","수","목","금","토"],months:["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],monthsShort:["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],today:"오늘",clear:"삭제",format:"yyyy-mm-dd",titleFormat:"yyyy년mm월",weekStart:0}}(jQuery);

$(document).ready(function() {
	var email_check = false;
	
	function formValidate(message) {
		var validator = new FormValidator('user_form', [{
			name: 'email',
			display: $("#sl_email").parent().parent().find('label').text(),
			rules: 'required|trim|valid_email'
		}, {
			name: 'password',
			display: $("#sl_password").parent().parent().find('label').text(),
			rules: 'required|trim|min_length[5]|max_length[40]|matches[password_confirm]'
		}, {
			name: 'password_confirm',
			display: $("#sl_password_confirm").parent().parent().find('label').text(),
			rules: 'required|min_length[5]|max_length[40]|trim'
		}, {
			name: 'name',
			display: $("#sl_name").parent().parent().find('label').text(),
			rules: 'required|trim|min_length[2]|max_length[60]'
		}, {
			name: 'phone',
			display: $("#sl_phone").parent().parent().find('label').text(),
			rules: 'required|trim|min_length[5]'
		}, {
			name: 'birthday',
			display: $("#sl_birthday").parent().parent().find('label').text(),
			rules: 'required|trim|check_date'
		}, {
			name: 'height',
			display: $("#sl_height").parent().parent().find('label').text(),
			rules: 'numeric|greater_than[20]|less_than[300]'
		}, {
			name: 'weight',
			display: $("#sl_weight").parent().parent().find('label').text(),
			rules: 'numeric|greater_than[20]|less_than[300]'
		}, {
			name: 'agree[over_age_18]',
			display: '"'+$("input[name='agree[over_age_18]']").parent().find('span').text()+'"',
			rules: 'required'
		}, {
			name: 'agree[service]',
			display: '"'+$("input[name='agree[service]']").parent().find('span').text()+'"',
			rules: 'required'
		}, {
			name: 'agree[information]',
			display: '"'+$("input[name='agree[information]']").parent().find('span').text()+'"',
			rules: 'required'
		}], 
		
		function(errors, event) {
			$("#user_form .has-error").removeClass('has-error');
			if (errors.length > 0) {
				$.each(errors,function(index,error){
					if(error.name=='agree[over_age_18]' || error.name=='agree[service]' || error.name=='agree[information]') {
						$(error.element).parent().parent().parent().addClass('has-error');
					} else {
						$(error.element).parent().parent().addClass('has-error');
    				}
    			});
    			
    			var errorString = '';
    			
    			for (var i = 0, errorLength = errors.length; i < errorLength; i++) {
    				errorString += errors[i].message + '<br />';
    			}
    			
    			$('#sl_messages').html('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+errorString+'</div>').focus();
    		}
    	});
    	
    	validator.registerCallback('check_date', function(value) {
    		//console.log(value);
    		var dates=value.split('-');
    		
    		if(date.length<2)
    		return false;
    		
    		return checkDate(dates[0],dates[1],dates[2]);
    	}).setMessage('check_date', 'invalid date');
	
		validator.setMessage('required',message['required']);
		validator.setMessage('min_length',message['min_length']);
		validator.setMessage('max_length',message['max_length']);
		validator.setMessage('numeric',message['numeric']);
		validator.setMessage('is_unique',message['is_unique']);
		validator.setMessage('matches',message['matches']);
		validator.setMessage('greater_than',message['greater_than']);
		validator.setMessage('less_than',message['less_than']);
		validator.setMessage('valid_email',message['valid_email']);
	}
	
	$.getJSON(base_url+'home/get_json_error_message', function(data) {
		if(data.result=='success') {
			formValidate(data.message);
		} else {
			
		}
	});
	
	$('.datepicker').attr('readonly','readonly').datepicker({
		startView: 'years',
		defaultViewDate : {'year':1981},
		format: 'yyyy-mm-dd',
		autoclose :true,
		language : 'ko'
	});
	
	$('#check_email_available_button').click(function() {
		var email = $('#sl_email').val();

		if (!$.trim(email).length) {
			display_message($('#message_no_email').val());
			$('#sl_email').focus();
			return false;
		}

		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (!filter.test(email)) {
			display_message($('#message_invalid_email').val());
			$('#sl_email').focus();			
			return false;
		}

		$(this).parent().parent().removeClass('has-error').removeClass('has-success');
		
		$.getJSON(base_url+'users/check_email', {
			'email' : email,
			'json' : true
		}, function(data) {
			if(data.exists) {
				$('#sl_email').parent().parent().addClass('has-error');
				display_message($('#message_exists_email').val());
				email_check=false;
			} else {
				$('#sl_email').parent().parent().addClass('has-success');			
				clear_message();
				display_message($('#message_available_email').val(),'success');					
				email_check=true;
			}
		});
	}).change(function(){
		$(this).parent().parent().removeClass('has-error').removeClass('has-success');
				
	});
});

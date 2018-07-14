$(document).ready(function() {
	
	$("#slboard_find_password_form").submit(function(){
		var suserid=$.trim($("#sl_user_id").val());
		var semail=$.trim($("#sl_email").val());		
		
		if(suserid=='') {
			$(this).find('.slboard_error_message').html('아이디를 입력해주세요');
			return false;
		}
		
		if(semail=='') {
			$(this).find('.slboard_error_message').html('이메일을 입력해주세요');
			return false;
		}
		/*
		$.getJSON('/account/find/find_id.php',{'name':sname,'email':semail,'json':true},function(data){
			if(data.result=='success') {
			
			} else {
				alert(data.error_message);
			}
		});
		return false;
		*/
	});
});
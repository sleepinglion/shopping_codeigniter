$(document).ready(function() {
	
	$("#slboard_find_id_form").submit(function(){
		var sname=$.trim($("#sl_name").val());
		var semail=$.trim($("#sl_email").val());
		
		if(sname=='') {
			$(this).find('.slboard_error_message').html('이름을 입력해주세요');
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
		return false;*/
	}); 
});
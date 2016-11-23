$(document).ready(function() {
	$("#slboard_user_agree_form").submit(function(){
		if(!$("#agree1").is(":checked")) {
			alert('회원 이용약관에 동의해주세요');
			return false;
		}
		
		if(!$("#agree2").is(":checked")) {
			alert('개인정보 수집 및 이용에 대한 안내에 동의해주세요');
			return false;
		}
	});
});
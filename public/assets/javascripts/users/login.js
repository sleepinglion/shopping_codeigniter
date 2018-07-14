$(document).ready(function() {
	var cookie=getCookie('user_email');

	$("#sl_login_form").submit(function(){
		return false;
	});

	if(cookie) {
		$("#sl_user_email").val(cookie);
		$("#remember_email").prop('checked',true);
	}

	function formValidate(message) {
		var validator = new FormValidator('sl_login_form', [{
			name: 'email',
			display: $("#sl_email").parent().parent().find('label').text(),
			rules: 'required|trim|valid_email'
		}, {
			name: 'password',
			display: $("#sl_password").parent().parent().find('label').text(),
			rules: 'required|trim|min_length[5]|max_length[40]'
		}],

		function(errors, event) {
			$("#sl_login_form .has-error").removeClass('has-error');
			if (errors.length > 0) {
				$.each(errors,function(index,error){
					$(error.element).parent().parent().addClass('has-error');
				});

    			var errorString = '';

    			for (var i = 0, errorLength = errors.length; i < errorLength; i++) {
    				errorString += errors[i].message + '<br />';
    			}

    			$('#sl_messages').html('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+errorString+'</div>').focus();
    		} else {
    			ajax_login();
    		}
		});
		validator.setMessage('required',message['required']);
		validator.setMessage('min_length',message['min_length']);
		validator.setMessage('max_length',message['max_length']);
		validator.setMessage('valid_email',message['valid_email']);
	}

	$.getJSON(base_url+'home/get_json_error_message', function(data) {
		if(data.result=='success') {
			formValidate(data.message);
		} else {

		}
	});

	function ajax_login(){
		var userEmail=$.trim($("#sl_email").val());
		var userPassword=$.trim($("#sl_password").val());
		//var token=$(this).find('input[name="token"]').val();

		if($("#remember_email").is(":checked")) {
			setCookie('user_email',userEmail);
		}

		$.post($(this).attr('action'),{'email':userEmail,'password':userPassword,'crypt':true,'json':true},function(data){
			if(data.result=='success') {
				if($('input[name="redirect_url"]').length) {
					location.href=$('input[name="redirect_url"]').val();
				} else {
					location.href=base_url;
				}
			} else {
				display_message(data.message);
			}
		},'json');
		return false;
	};
});

/**
 * 쿠키값 추출
 * @param cookieName 쿠키명
 */
function getCookie( cookieName )
{
 var search = cookieName + "=";
 var cookie = document.cookie;

 // 현재 쿠키가 존재할 경우
 if( cookie.length > 0 )
 {
  // 해당 쿠키명이 존재하는지 검색한 후 존재하면 위치를 리턴.
  startIndex = cookie.indexOf( cookieName );
  // 만약 존재한다면
  if( startIndex != -1 )
  {
   // 값을 얻어내기 위해 시작 인덱스 조절
   startIndex += cookieName.length;

   // 값을 얻어내기 위해 종료 인덱스 추출
   endIndex = cookie.indexOf( ";", startIndex );

   // 만약 종료 인덱스를 못찾게 되면 쿠키 전체길이로 설정
   if( endIndex == -1) endIndex = cookie.length;

   // 쿠키값을 추출하여 리턴
   return cookie.substring( startIndex + 1, endIndex ) ;
  }
  else
  {
   // 쿠키 내에 해당 쿠키가 존재하지 않을 경우
   return false;
  }
 }
 else
 {
  // 쿠키 자체가 없을 경우
  return false;
 }
}


/**
 * 쿠키 설정
 * @param cookieName 쿠키명
 * @param cookieValue 쿠키값
 * @param expireDay 쿠키 유효날짜
 */
function setCookie( cookieName, cookieValue, expireDate )
{
 var today = new Date();
 today.setDate( today.getDate() + parseInt( expireDate ) );
 document.cookie = cookieName + "=" + cookieValue + "; path=/; expires=" + today.toGMTString() + ";";
}


/**
 * 쿠키 삭제
 * @param cookieName 삭제할 쿠키명
*/
function deleteCookie( cookieName )
{
 var expireDate = new Date();

 //어제 날짜를 쿠키 소멸 날짜로 설정한다.
 expireDate.setDate( expireDate.getDate() - 1 );
 document.cookie = cookieName + "= " + "; expires=" + expireDate.toGMTString() + "; path=/";
}

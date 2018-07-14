$(document).ready(function() {
	/*$("#order_form").submit(function(){
		return false;
	}); */
	function formValidate(message) {
		var order_info=$("#sl_order_name").parent().parent().parent().parent().find('h4').text();
		var shipping_info=$("#sl_shipping_name").parent().parent().parent().parent().find('h4').text();
		
		var order_form_validate=[{
			name: 'order[name]',
			display: $("#sl_order_name").parent().parent().find('label').text()+'('+order_info+')',
			rules: 'required'
		},{
			name: 'order[email]',
			display: $("#sl_order_email").parent().parent().find('label').text()+'('+order_info+')',
			rules: 'required|valid_email'
		},{
			name: 'order[phone]',
			display: $("#sl_order_phone").parent().parent().find('label').text()+'('+order_info+')',
			rules: 'required'
		},{
			name: 'shipping[same]',
			display: $('input[name="shipping[same_order]"]:first').parent().find('label').text()+'('+shipping_info+')',
			rules: 'required'
		},{
			name: 'shipping[name]',
			display: $("#sl_shipping_name").parent().parent().find('label').text()+'('+shipping_info+')',
			rules: 'required'
		},{
			name: 'shipping[email]',
			display: $("#sl_shipping_email").parent().parent().find('label').text()+'('+shipping_info+')',
			rules: 'required|valid_email'
		},{
			name: 'shipping[phone]',
			display: $("#sl_shipping_phone").parent().parent().find('label').text()+'('+shipping_info+')',
			rules: 'required'
		},{
			name: 'shipping[zip_code]',
			display: $("#sl_shipping_zip").attr('placeholder')+'('+shipping_info+')',
			rules: 'required|min_length[5]|max_length[5]'
		},{
			name: 'shipping[address_default]',
			display: $("#sl_shipping_address_default").attr('placeholder')+'('+shipping_info+')',
			rules: 'required'
		},{
			name: 'shipping[address_detail]',
			display: $("#sl_shipping_address_detail").attr('placeholder')+'('+shipping_info+')',
			rules: 'required'
		}];
		
		if($('input[name="agree[no_user]"]').length) {
			order_form_validate=order_form_validate.concat([{
				name: 'agree[no_user]',
				display: $('input[name="agree[no_user]"]').parent().find('span').text(),
				rules: 'required'
			}]);
		}
		
		order_form_validate=order_form_validate.concat([{
			name: 'agree[order]',
			display: $('input[name="agree[order]"]').parent().find('span').text(),
			rules: 'required'
		}]);
		
		if(!$('input:radio[name="shipping[same_order]"]').is(':checked')) {
			$('input:radio[name="shipping[same_order]"]:first').prop('checked',true);
		}
		
		var validator = new FormValidator('order_form', order_form_validate,
		
		function(errors, event) {
			$("#order_form .has-error").removeClass('has-error');			
			if (errors.length > 0) {
				$.each(errors,function(index,error){
					if(error.name=='agree[order]' || error.name=='agree[no_user]') {
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
		
		validator.setMessage('required',message['required']);
		validator.setMessage('min_length',message['min_length']);
		validator.setMessage('max_length',message['max_length']);
		validator.setMessage('numeric',message['numeric']);
		validator.setMessage('is_unique',message['is_unique']);
		validator.setMessage('matches',message['matches']);
		validator.setMessage('greater_than',message['greater_than']);
		validator.setMessage('less_than',message['less_than']);
		validator.setMessage('valid_email',message['valid_email']);
	};
	
	$.getJSON(base_url+'home/get_json_error_message', function(data) {
		if(data.result=='success') {
			formValidate(data.message);
		} else {
			
		}
	});
	
	daum.postcode.load(function(){
		$("#find_zip input").click(openDaumPostcode);
	});
	
	$("#btnFoldWrap").click(function(){
		$("#wrap").hide();
	});
	
	$("#sl_order_name").change(function(){
		console.log($('input[name="shipping[same_order]"]').val());
		if($('input[name="shipping[same_order]"]').val()==1) {
			$("#sl_shipping_name").val($(this).val());
		}
	});
	
	$("#sl_order_email").change(function(){
		if($('input[name="shipping[same_order]"]').val()==1) {
			$("#sl_shipping_email").val($(this).val());
		}
	});
	
	$("#sl_order_phone").change(function(){
		if($('input[name="shipping[same_order]"]').val()==1) {
			$("#sl_shipping_phone").val($(this).val());
		}
	});		
	
	$('input[name="shipping[same_order]"]').change(function(){
		if($(this).val()==1) {
			/*if($("#sl_order_name").val()
			$("#sl_order_email").val()
			$("#sl_order_name").val() */
			
			$("#sl_shipping_name").attr('readonly','readonly').val($("#sl_order_name").val());
			$("#sl_shipping_email").attr('readonly','readonly').val($("#sl_order_email").val());
			$("#sl_shipping_phone").attr('readonly','readonly').val($("#sl_order_phone").val());
		} else {
			$("#sl_shipping_name").removeAttr('readonly').val('');
			$("#sl_shipping_email").removeAttr('readonly').val('');
			$("#sl_shipping_phone").removeAttr('readonly').val('');
		}
	});
	
	$('.product input[type="number"]').change(function(){
		var p_index=$('.product input[type="number"]').index($(this));
		
		$('#calculator_table tbody tr:eq('+p_index+') .quantity').text($(this).val());
		$('#calculator_table tfoot .price').text(number_format(calculator_total()));
	});
	
	function calculator_total() {
		var total_price=0;
		$('#calculator_table tbody tr').each(function(){
			var price=Number($(this).find('td:first input').val());
			var shipping_price=Number($(this).find('td:eq(2) input').val());			
			var quantity=Number($(this).find('.quantity').text());
			
			total_price=total_price+price*quantity+shipping_price;
		});
		return total_price;
	}
	
	$("#btnFoldWrap").click(function(){
		$("#wrap").hide();
		$("#sl_shipping_address_detail").removeAttr('readonly');  
	});
	
    var element_wrap = document.getElementById('wrap');

    function openDaumPostcode() {
        // 현재 scroll 위치를 저장해놓는다.
        var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
        new daum.Postcode({
            oncomplete: function(data) {
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = data.address; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 기본 주소가 도로명 타입일때 조합한다.
                if(data.addressType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('sl_shipping_zip').value = data.zonecode; //5자리 새우편번호 사용
                document.getElementById('sl_shipping_address_default').value = fullAddr;

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
               // element_wrap.style.display = 'none';
                $("#btnFoldWrap").click();
                
                // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                document.body.scrollTop = currentScroll;
                
                document.getElementById('sl_shipping_address_detail').focus();
            },
            // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
            onresize : function(size) {
                element_wrap.style.height = size.height+'px';
            },
            width : '100%',
            height : '100%'
        }).embed(element_wrap);

        // iframe을 넣은 element를 보이게 한다.
        element_wrap.style.display = 'block';
        $("#sl_shipping_zip,#sl_shipping_address_default,#sl_shipping_address_detail").attr('readonly','readonly');        
    }	
});
	
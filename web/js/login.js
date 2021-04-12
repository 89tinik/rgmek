/* Preloader */
$(window).on("load", function() {
	
	/* Preload */
	var preload = $('.preloader');
	preload.find('.spinner').fadeOut(function(){
		preload.fadeOut(500);
	});

});

$(function() {
	var width = $(window).width();
	/**
	 Form Styler
	 **/
	styler_func();
	/*tin*/
	// $('.wrong-link a').on('click', function() {
	// 	// 	$.ajax({
	// 	// 		type: "POST",
	// 	// 		url: "http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/registration",
	// 	// 		data: "id=c2afaaff-9e30-11e4-9c77-001e8c2d263f&inn=6234061345&contract=6828&method=0&value=info@edinstvo62.ru",
	// 	// 		complete: function (msg) {
	// 	// 			console.log(msg);
	// 	// 		}
	// 	// 	});
	// 	// 	return false;
	// 	// });
	function setMethod(i){
		if ( i== 1) {
			$('#registerForm input.phone').closest('.form-group').css('display', 'block');
			$('#registerForm input.email').closest('.form-group').css('display', 'none');
		} else {
			$('#registerForm input.phone').closest('.form-group').css('display', 'none');
			$('#registerForm input.email').closest('.form-group').css('display', 'block');
		}
	}
	$('#registerForm input[type=radio]').on('change', function(){
		setMethod($(this).val());
	});
	setMethod($('#registerForm input[type=radio]:checked').val());
	//$('#registerForm input[type=radio]').change();
	//$('#registerForm input[type=radio]').trigger('refresh');

	/*ENDtin*/

	
	
	/*
	* 
	* Validation Forms
	*
	*/

	/**
		Call Form
	**/
	$("#call_form").validate({
		rules: {
			name: {
				required: true
			},
			tel: {
				required: true
			}
		},
		messages: {
			name: {
				required: 'Поле заполненно не корректно'
			},
			tel: {
				required: 'Поле заполненно не корректно'
			}
		},
		success: "valid",
		submitHandler: function() {
			
		}
	});

	/**
		Tel Validate
	**/
	if($("input[name='tel']").length) {
		$("input[name='tel']").mask("999 999 99 99",{placeholder:" "});
	}

});

function styler_func() {
	/*styler*/
	$('input.styler, select.styler').styler();
}
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

	/*tin*/
	// $('.wrong-link a').on('click', function() {
	// 	$.ajax({
	// 		type: "POST",
	// 		url: "http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/registration",
	// 		data: "id=c2afaaff-9e30-11e4-9c77-001e8c2d263f&inn=6234061345&contract=6828&method=0&value=info@edinstvo62.ru",
	// 		complete: function (msg) {
	// 			console.log(msg);
	// 		}
	// 	});
	// 	return false;
	// });
	/*ENDtin*/
	/**
		Form Styler
	**/
	styler_func();
	
	
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
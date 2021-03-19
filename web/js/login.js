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
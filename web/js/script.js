/* Preloader */
$(window).on("load", function() {
		/*tin*/
	$('.profile-details ul li:not(.last)').each(function(){
		if($(this).height()<$(this).find('.value').height()){
			$(this).height($(this).find('.value').height());
		}
	});
	/*ENDtin*/
	/* Preload */
	var preload = $('.preloader');
	preload.find('.spinner').fadeOut(function(){
		preload.fadeOut(500);
	});

});

$(function() {
	var width = $(window).width();
	/**
	 Tabs
	 **/
	$('.tab-menu').on('click', '.tab-btn', function(){
		var tab_bl = $(this).attr('href');

		$(this).closest('.tab-menu').find('li').removeClass('active');
		$(this).closest('li').addClass('active');

		$(this).closest('.tabs').find('.tab-item').hide();
		$(tab_bl).fadeIn();

		return false;
	});

	/*tin*/

	//доступные отчеты в разделе НАЧИСЛЕНИЯ И ПЛАТЕЖИ
	if ($('.sidebar-menu-fw a.active').attr('data-odn') != 'true' && $('.type-order option[value=odn]').length){
		$('.type-order option[value=odn]').attr('disabled', 'disabled');
	}

	//обработка формы НАЧИСЛЕНИЯ И ПЛАТЕЖИ
	$('.get-order-form').on('submit', function(){

		var uid = $('.sidebar-menu-fw a.active').attr('data-uid');
		var dateFrom = $('#from_dialog').val();
		var dateTo = $('#to_dialog').val();
		switch ($('.type-order option:selected').val()) {
			case 'detail':

				$('.detail-report-wrap .title').html('Детализация счета по договору<br/>' + $('.sidebar-menu-fw a.active').attr('data-name') + '<br/>за период ' + dateFrom + '-' + dateTo);

				$('.detail-report-wrap a.print').attr('href', $('.detail-report-wrap a.print').attr('href')+'&uid=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo);
				$('.detail-report-wrap a.download').attr('href', $('.detail-report-wrap a.download').attr('href')+'&uid=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo);

				$('.report-item').hide();
				$('.detail-report-wrap').show();
				break;
			case 'penalty':
				$.ajax({
					type: 'POST',
					url: '/ajax/list-penalty',
					data: 'uidcontracts=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo,
					success: function (msg) {
						try {
							var msgArr = JSON.parse(msg);
						} catch (e) {
							$('.report-item').hide();
							$('.penalty-report-wrap ul').html(msg);
							$('.penalty-report-wrap').show();
						}
						if (msgArr !== undefined){
							alert(msgArr.error);
						}
					}
				});
				break;
			case 'odn':

				$('.odn-report-wrap .title').html('Отчёт по расчёту ОДН <br/> за период ' + dateFrom + '-' + dateTo);

				$('.odn-report-wrap a.print').attr('href', $('.odn-report-wrap a.print').attr('href')+'&uid=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo);
				$('.odn-report-wrap a.download').attr('href', $('.odn-report-wrap a.download').attr('href')+'&uid=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo);

				$('.report-item').hide();
				$('.odn-report-wrap').show();
				break;
			case 'aktpp':
				$.ajax({
					type: 'POST',
					url: '/ajax/list-aktpp',
					data: 'uidcontracts=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo,
					success: function (msg) {
						try {
							var msgArr = JSON.parse(msg);
						} catch (e) {
							$('.report-item').hide();
							$('.aktpp-report-wrap ul').html(msg);
							$('.aktpp-report-wrap').show();
						}
						if (msgArr !== undefined){
							alert(msgArr.error);
						}
					}
				});
				break;
			default:
				alert( "Нет таких значений" );
		}

		return false;
	});

	//переключение типа получения счетов
	function subscribeType () {
		if ($('.subscribe-form input[type=radio]:checked').val() == 1) {
			$('.subscribe-group').css('display', 'block');
		} else {
			$('.subscribe-group').css('display', 'none');
		}
	}
	subscribeType();
	$('.subscribe-form input[type=radio]').on('change', function(){
		subscribeType();
	});


	// выбор контракта для ЭДО
	$('.link-popup-contract-edo').on('click', function(){
		$('.contracts-edo-popup').animate({'top': $(window).scrollTop() + 50}, 450);
		$('.contracts-devices-popup-overlay').fadeIn(250);
		return false;
	});

	$('.contracts-edo-popup .close').on('click', function(){
		$('.contracts-edo-popup').animate({'top': '-3000px'}, 450);
		$('.contracts-devices-popup-overlay').fadeOut(250);
		return false;
	});
	//переключение таба
	var url = window.location.href;
	var arrAncor = url.split('#');
	if($('a.tab-btn').length && typeof arrAncor[1] != "undefined"){
		$('a.tab-btn').each(function(){
			if ($(this).attr('href') == '#'+arrAncor[1]){
				$(this).trigger('click');
			}
		});
	}

	//подтягивание контракта с сайдбара
	$('.name-sidebar').text($('.sidebar-menu-fw a.active').attr('data-name'));

	//получение всех счетов
	$('.aj-all-invoice').on('click', function(){
		$('.preloader').css({'display':'block', 'opacity':'0.5'});
		$('.preloader .spinner').css('display', 'inline-block');
		$(this).css('display', 'none');
		$.ajax({
			type: "GET",
			url: "/main/all-arrear",
			data: 'uid='+$(this).attr('data-uid'),
			success: function(mess) {
				$('.wrap-invoice').html(mess);
				$('.arrear-lists.white-box .white-box-title').text('Все выставленные счета');
				var preload = $('.preloader');
				preload.find('.spinner').fadeOut(function(){
					preload.fadeOut(500);
				});
			}
		});
		return false;
	});

	/*contracts popup*/
	$('.contracts-devices .devices-link').on('click', function(){
		$('.contracts-devices-popup .table').html($(this).next('div').html());
		$('.contracts-devices-popup').animate({'top': $(window).scrollTop() + 50}, 450);
		$('.contracts-devices-popup-overlay').fadeIn(250);
		return false;
	});

	$('.contracts-devices-popup .close').on('click', function(){
		$('.contracts-devices-popup').animate({'top': '-3000px'}, 450);
		$('.contracts-devices-popup-overlay').fadeOut(250);
		return false;
	});

	/**
		Main Carousel
	**/
	var main_carousel = new Swiper('.main-carousel .swiper-container', {
		slidesPerView: 'auto',
		loop: false,
		speed: 1000,
		pagination: {
			el: '.work-gallery-carousel .swiper-pagination',
			type: 'fraction',
		},
		navigation: {
			nextEl: '.main-carousel .swiper-button-next',
			prevEl: '.main-carousel .swiper-button-prev',
		}
	});

	/**
		Mobile Menu
	**/
	$('.header').on('click', '.menu-btn', function(){
		if($(this).closest('.header').hasClass('opened')) {
			$(this).closest('.header').removeClass('opened');
			$(this).removeClass('active');
		}
		else {
			$(this).closest('.header').addClass('opened');
			$(this).addClass('active');
		}
		return false;
	});

	/**
		Menu Dropdown
	**/
	if(width < 1200) {
	$('.menu-popup').on('click', '.dropdown-btn', function(){
		if($(this).closest('li').hasClass('active')) {
			$(this).closest('li').find('> ul').slideUp();
			$(this).closest('li').removeClass('active');
		}
		else {
			$(this).closest('li').find('> ul').slideDown();
			$(this).closest('li').addClass('active');
		}
		return false;
	});
	$('.top-menu').on('click', '.children > a', function(){
		if($(this).closest('li').hasClass('active')) {
			$(this).closest('li').find('> ul').slideUp();
			$(this).closest('li').removeClass('active');
		}
		else {
			$(this).closest('li').find('> ul').slideDown();
			$(this).closest('li').addClass('active');
		}
		return false;
	});
	}

	/**
		Summary Tabs
	**/
	$('.summary-item').on('click', '.show-btn', function(){
		if($(this).closest('.summary-item').hasClass('active')) {
			$(this).closest('.summary-item').find('.info-bottom').slideUp();
			$(this).closest('.summary-item').removeClass('active');
			$(this).removeClass('active');
		}
		else {
			$(this).closest('.summary-item').find('.info-bottom').slideDown();
			$(this).closest('.summary-item').addClass('active');
			$(this).addClass('active');
		}
		return false;
	});

	/**
		more-list
	**/
	$('.more-list').on('click', '.btn', function(){
		if($(this).closest('.more-list').hasClass('active')) {
			$(this).closest('.more-list').find('.more-list-popup').slideUp();
			$(this).closest('.more-list').removeClass('active');
		}
		else {
			$(this).closest('.more-list').find('.more-list-popup').slideDown();
			$(this).closest('.more-list').addClass('active');
		}
		return false;
	});

	/** 
		Contracts 
	**/
	$('.contracts-item').on('click', '.more-link, .name a', function(){
		var item = $(this).closest('.contracts-item');
		var item_body = item.find('.contracts-body');
		var item_more_link = item.find('.more-link');
		var open_text = item_more_link.data('text-open');
		var close_text = item_more_link.data('text-close');

		if ( item.hasClass('open') ) {
			item.removeClass('open');
			item_body.slideUp(500);
			item_more_link.find('span').text(open_text);
		} else {
			item.addClass('open');
			item_body.slideDown(500);
			item_more_link.find('span').text(close_text)
		}

		return false;
	});

	/** 
		Objects 
	**/
	$('.objects-item').on('click', '.more-link, .name a', function(){
		var item = $(this).closest('.objects-item');
		var item_body = item.find('.objects-body');
		var item_more_link = item.find('.more-link');
		var open_text = item_more_link.data('text-open');
		var close_text = item_more_link.data('text-close');

		if ( item.hasClass('open') ) {
			item.removeClass('open');
			item_body.slideUp(500);
			item_more_link.find('span').text(open_text);
		} else {
			item.addClass('open');
			item_body.slideDown(500);
			item_more_link.find('span').text(close_text)
		}

		return false;
	});

	/** 
		Label info
	**/
	$('.wrapper').on('click', '.label-info', function(){
		$(this).parent().find('.label-error').fadeIn();

		return false;
	});
	$('.label-error').on('click', '.close', function(){
		$(this).closest('.label-error').fadeOut();

		return false;
	});

	/** 
		Anketa radio items
	**/
	$('.type-anketa-radio-items input:radio').change(function() {
		var av = $('.type-anketa-fields');
		var v = $(this).attr('data-value');

		$(av).hide();
		$(v).fadeIn();
	});

	$(".inputs").keyup(function () {
		if (this.value.length == this.maxLength) {
			var $next = $(this).next('.inputs');
			if ($next.length) {
				$(this).next('.inputs').focus();
			}
			else {
				$(this).blur();
			}
		}
	});



	/**
		Collapse
	**/
	$('.collapse-item').on('click', '.collapse-btn', function(){
		if($(this).closest('.collapse-item').hasClass('active')) {
			$(this).closest('.collapse-item').find('.collapse-content').slideUp();
			$(this).closest('.collapse-item').removeClass('active');
			$(this).removeClass('active');
		}
		else {
			$(this).closest('.collapse-item').find('.collapse-content').slideDown();
			$(this).closest('.collapse-item').addClass('active');
			$(this).addClass('active');
		}
	});

	/**
		Form Styler
	**/
	styler_func();

	/**
		Gallery
	**/
	$(".gallery-group").fancybox({
		// Options will go here
	});

	/** 
		Rating 
	**/
	if($(".rateit").length){
		$('.rateit').rateit();
	}

	/**
		Popups
	**/
	$('.overlay, .mobile-popup .close, .popup-box .close').on('click', function(){
		$(this).closest('.popup-box').fadeOut();
		$('.overlay').fadeOut(250);
		return false;
	});

	if(width < 1200) {
	$('.sidebar-menu ul li a').on('click', function(){
		$(this).closest('li').find('.popup-box.sidebar-mobile-popup').fadeIn();
		$('.overlay').fadeIn(250);
		return false;
	});
	}

	/**
		Form Anketa 1
	**/
	$("#anketa_tab1_step_1").validate({
		rules: {
			entity_name: {
				required: true
			},
			entity_email: {
				required: true
			},
			entity_name_two: {
				required: true
			},
			entity_orgn: {
				required: true
			},
			entity_address: {
				required: true
			},
			entity_inn: {
				required: true
			},
			entity_fax: {
				required: true
			},
			entity_kpp: {
				required: true
			},
			entity_cod: {
				required: true
			},
			ip_name: {
				required: true
			},
			ip_email: {
				required: true
			},
			ip_name_two: {
				required: true
			},
			ip_orgn: {
				required: true
			},
			ip_address: {
				required: true
			},
			ip_inn: {
				required: true
			},
			ip_fax: {
				required: true
			},
			ip_kpp: {
				required: true
			},
			ip_cod: {
				required: true
			}
		},
		messages: {
			entity_name: {
				required: 'Поле заполненно не корректно'
			},
			entity_email: {
				required: 'Поле заполненно не корректно'
			},
			entity_name_two: {
				required: 'Поле заполненно не корректно'
			},
			entity_orgn: {
				required: 'Поле заполненно не корректно'
			},
			entity_address: {
				required: 'Поле заполненно не корректно'
			},
			entity_inn: {
				required: 'Поле заполненно не корректно'
			},
			entity_fax: {
				required: 'Поле заполненно не корректно'
			},
			entity_kpp: {
				required: 'Поле заполненно не корректно'
			},
			entity_cod: {
				required: 'Поле заполненно не корректно'
			},
			ip_name: {
				required: 'Поле заполненно не корректно'
			},
			ip_email: {
				required: 'Поле заполненно не корректно'
			},
			ip_name_two: {
				required: 'Поле заполненно не корректно'
			},
			ip_orgn: {
				required: 'Поле заполненно не корректно'
			},
			ip_address: {
				required: 'Поле заполненно не корректно'
			},
			ip_inn: {
				required: 'Поле заполненно не корректно'
			},
			ip_fax: {
				required: 'Поле заполненно не корректно'
			},
			ip_kpp: {
				required: 'Поле заполненно не корректно'
			},
			ip_cod: {
				required: 'Поле заполненно не корректно'
			}
		},
		success: "valid",
		submitHandler: function() {
			window.location.href = 'anketa-2.html';
		}
	});

	/**
		Form Anketa 2
	**/
	$("#anketa_tab1_step_2").validate({
		rules: {
			name: {
				required: true
			},
			time: {
				required: true
			},
			address: {
				required: true
			},
			price: {
				required: true
			},
			mkd: {
				required: true
			},
			tel: {
				required: true
			},
			fax: {
				required: true
			},
			email: {
				required: true
			},
			cod: {
				required: true
			},
			deal_radio: {
				required: true
			}
		},
		messages: {
			name: {
				required: 'Поле заполненно не корректно'
			},
			time: {
				required: 'Поле заполненно не корректно'
			},
			address: {
				required: 'Поле заполненно не корректно'
			},
			price: {
				required: 'Поле заполненно не корректно'
			},
			mkd: {
				required: 'Поле заполненно не корректно'
			},
			tel: {
				required: 'Поле заполненно не корректно'
			},
			fax: {
				required: 'Поле заполненно не корректно'
			},
			email: {
				required: 'Поле заполненно не корректно'
			},
			cod: {
				required: 'Поле заполненно не корректно'
			},
			deal_radio: {
				required: 'Поле заполненно не корректно'
			}
		},
		success: "valid",
		submitHandler: function() {
			window.location.href = 'anketa-3.html';
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
	$('input.styler, select.styler').styler({'selectPlaceholder':'Из списка'});

	/*datepicker from-to dialog*/
	var dateFormat = "dd.mm.yy", from = $( "#from_dialog" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
			regional: "ru"
	})
	.on( "change", function() {
		to.datepicker( "option", "minDate", getDate( this ) );
		to.datepicker( "option", "maxDate", getDate( this , '+1y') );
	}),

	to = $( "#to_dialog" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		regional: "ru"
	})
	.on( "change", function() {
		from.datepicker( "option", "maxDate", getDate( this ) );
		from.datepicker( "option", "minDate", getDate( this, '-1y' ) );
	});

	function getDate( element, offset) {
		var date;
		try {
			date = $.datepicker.parseDate( dateFormat, element.value );
		} catch( error ) {
			date = null;
		}
		if (date){
			if (offset == '-1y'){
				date.setFullYear(date.getFullYear() - 1);
			}
			if (offset == '+1y'){
				date.setFullYear(date.getFullYear() + 1);
			}
		}
		return date;
	}
}
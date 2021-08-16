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
	//тип ответа на ФОС
	function setAnswerType(i) {
		if (i == 'phone') {
			$('#feedbackForm input.phone').closest('.group').css('display', 'block');
			$('#feedbackForm input.email').closest('.group').css('display', 'none');
		} else {
			$('#feedbackForm input.phone').closest('.group').css('display', 'none');
			$('#feedbackForm input.email').closest('.group').css('display', 'block');
		}
	}

	$('#feedbackForm .type-answer input[type=radio]').on('change', function () {
		setAnswerType($(this).val());
	});
	setAnswerType($('#feedbackForm .type-answer input[type=radio]:checked').val());

	//попап История показаний
	$('.history').on('click', function(){
		if ($(window).scrollTop() > $('.bg').scrollTop()){
			var topPos = $(window).scrollTop() + 50;
		} else {
			var topPos = $('.bg').scrollTop() + 50;
		}
		$('.link-invoice').attr('href', $('.link-invoice').attr('href')+'&uid='+$(this).attr('data-uidcontract'));
		$('.history-popup').animate({'top': topPos}, 450);
		$('.contracts-devices-popup-overlay').fadeIn(250);
		return false;
	});

	//пересчёт в форме оплаты
	$('.sber-form .value input').on('change', function(){
		if (parseFloat($(this).val()) > parseFloat($(this).attr('max'))){
			$(this).val($(this).attr('max'));
		}
		var all = 0;
		$('.sber-form .value input').each(function(){
			all = all + parseFloat($(this).val());
		});
		if (all > 0) {
			$('.all-price').html(all.toLocaleString() + '₽');
		} else {
			$('.all-price').html('0');
		}
	});
	//прелоадер на ссылки
	$('.ploader').on('click', function(){
		ajaxPreloaderOn();
	});
	//прикрепление фото ПУ
	$('label.computation-pu').on('click', function () {
		if ($(this).hasClass('disabled')){
			return false;
		}
		$(this).addClass('current-label-attach');
		var puid = $(this).closest('.wrap-pu').attr('data-puid');
		if ($('#attachFormPu .puidInput').val() != puid) {
			$('#attachFormPu')[0].reset();
			$('#attachFormPu .puidInput').val(puid);
		}
	});
	$('#attachform-photo').on('change', function() {
		var file = $('#attachform-photo')[0].files[0];
		if (file) {
			$('span.photo-name').remove();
			$('.current-label-attach').after('<span class="photo-name">'+file.name+'</span>');
			$('.current-label-attach').removeClass('current-label-attach');
		}
	});
	$('#attachform-time').on('change', function() {
		var file = $('#attachform-time')[0].files[0];
		if (file) {
			$('span.photo-time').remove();
			$('.current-label-attach').after('<span class="photo-time">'+file.name+'</span>');
			$('.current-label-attach').removeClass('current-label-attach');
		}
	});
	//отправка фото ПУ
	$('button.computation-pu').on('click', function () {
		if ($(window).scrollTop() > $('.bg').scrollTop()){
			var topPos = $(window).scrollTop() + 50;
		} else {
			var topPos = $('.bg').scrollTop() + 50;
		}
		var puid = $(this).closest('.wrap-pu').attr('data-puid');
		if ($('#attachFormPu .puidInput').val() != puid) {
			$('#attachFormPu')[0].reset();
		}

		if ($('#attachform-photo').val() == ''){
			$('.attach-popup .message').text('Прикрепите фото ПУ');

			$('.attach-popup').animate({'top': topPos}, 450);
			$('.contracts-devices-popup-overlay').fadeIn(250);
		} else {
			ajaxPreloaderOn();
			var formData = new FormData($('#attachFormPu')[0]);
			$.ajax({
				type: 'POST',
				url: '/ajax/attach',
				data: formData,
				async: false,
				cache: false,
				contentType: false,
				processData: false,
				success: function (msg) {
					ajaxPreloaderOff();
					$('.attach-popup .message').text(msg);

					$('.attach-popup').animate({'top': topPos}, 450);
					$('.contracts-devices-popup-overlay').fadeIn(250);
				}
			});

		}

	});

	//политика
	$('input.polit').bind('change', function(){
		if($(this).prop("checked")) {
			$(this).closest('form').find('button').removeAttr('disabled');
		} else {
			$(this).closest('form').find('button').attr('disabled', 'disabled');
		}
	});
	//разворачивание объекта по клику на заголовок
	$('.trigger-more').on('click', function(){
		$(this).siblings('.contracts-more').children('.more-link').trigger('click');
	});
	//информация по ценовым категориям
	$('.price-category-btn').on('click', function(){
		$('.price-category-popup').animate({'top': $(window).scrollTop() + 50}, 450);
		$('.contracts-devices-popup-overlay').fadeIn(250);
		return false;
	});
	//подсказка о просмотре информации ПУ
	$('.pu-ask-btn').on('click', function(){
		if ($(window).scrollTop() > $('.bg').scrollTop()){
			var topPos = $(window).scrollTop() + 50;
		} else {
			var topPos = $('.bg').scrollTop() + 50;
		}
		$('.information-pu-popup').animate({'top': topPos}, 450);
		$('.contracts-devices-popup-overlay').fadeIn(250);
		return false;
	});
	//формирование Акта фиксации
	$('.get-act').on('click', function(){
        if ($(this).closest('.wrap-object').find('.wrap-pu.no-result:not(.aiiskue)').length > 0){
            var firstNorersult = $('.wrap-pu.no-result:first');
            if (!firstNorersult.hasClass('active')){
                firstNorersult.children('.collapse-btn').addClass('active');
                firstNorersult.children('.collapse-content').attr('style', '');
            }
            firstNorersult.find('.label-error').show();
            $('html, body').animate({ scrollTop: firstNorersult.offset().top }, 500);
            return false;
        } else {
			//ajaxPreloaderOn();
            var indications = '?uidcontracts='+$('.sidebar-menu-fw a.active').attr('data-uid')+'&uidobject='+$(this).closest('.wrap-object').attr('data-id');
            $(this).closest('.wrap-object').find('.wrap-pu:not(.aiiskue)').each(function(){
                indications += '&'+$(this).attr('data-id')+'='+$(this).attr('data-idication');
            });
			var hrefArr = $(this).attr('href').split('?');
            $(this).attr('href', hrefArr[0]+indications);
            // return false;
        }
	});

	//рассчет показаний ПУ
	$('.curr-val.indication').on('input', function () {
		if ($(this).val().length == $(this).attr('maxlength')) {
			var blockPU = $(this).closest('.wrap-pu');
			blockPU.addClass('no-result');
			var errorBlock = blockPU.find('.label-error');
			var wrapResultBlock = blockPU.find('.consumed-wrap');
			var resultBlock = blockPU.find('.result-pu');
			resultBlock.text('0');
			blockPU.attr('data-result', '0');
			errorBlock.hide();
			wrapResultBlock.hide();

			// var old = '';
			// var curr = '';
			// blockPU.find('.old-num').each(function () {
			// 	old += $(this).val();
			// });
			// blockPU.find('.curr-num').each(function () {
			// 	curr += $(this).val();
			// // });
			// old = parseInt(old);
			// curr = parseInt(curr);
			var old = 0;
			if (blockPU.find('.old-val').val() !== "") {
				old = parseInt(blockPU.find('.old-val').val());
			}
			var curr = parseInt(blockPU.find('.curr-val').val());


			if (old > curr) {
				errorBlock.text('Введённое показание меньше предыдущего!');
				errorBlock.show();
			} else if (isNaN(curr)) {
				errorBlock.text('Не заполнено текущее показание!');
				errorBlock.show();
			} else {
				var consumed = (curr - old) * parseInt(blockPU.attr('data-k'));
				resultBlock.text(consumed);
				blockPU.attr('data-result', consumed);
				blockPU.attr('data-idication', curr);
				wrapResultBlock.show();
				blockPU.removeClass('no-result');

			}
			var objectResult = 0;
			$(this).closest('.wrap-object').find('.wrap-pu:not(.no-result)').each(function () {
				objectResult += parseInt($(this).attr('data-result'));
			});

			$(this).closest('.wrap-object').find('.object-result').text(objectResult);
		}
		return false;
	});

	//отправка показаний объекта
	function transferIndication(btn){
		ajaxPreloaderOn();
		var puArr = [];
		btn.closest('.wrap-object').find('.wrap-pu:not(.no-result, .aiiskue)').each(function () {
			puArr.push({'indications':$(this).attr('data-idication'),'uidtu':$(this).attr('data-id'),'uidpu':$(this).attr('data-puid')});
		});
		$.ajax({
			type: 'POST',
			url: '/ajax/transfer',
			data: 'uidobject='+btn.closest('.wrap-object').attr('data-id')+'&uidcontract='+$('.sidebar-menu-fw a.active').attr('data-uid')+ '&id='+$('.uid-d').attr('data-uid')+'&tu='+JSON.stringify(puArr),
			//data: '{"id":"c222afaaff-9e30-11e4-9c77-001e8c2d263f","uidcontract":"b95aa4a7-9f5e-11e4-9c77-001e8c2d263f","tu":[{"uidtu":"a383457f-19a8-41bd-99af-44c19f7afdb3", "indications":10000},{"uidtu":"8907550c-9e9a-11e4-9c77-001e8c2d263f", "indications":12000}]}',
			success: function (msg){
				$('.transfer-indication-popup h3').text(msg);
				if ($(window).scrollTop() > $('.bg').scrollTop()){
					var topPos = $(window).scrollTop() + 50;
				} else {
					var topPos = $('.bg').scrollTop() + 50;
				}
				$('.transfer-indication-popup').animate({'top': topPos}, 450);
				$('.contracts-devices-popup-overlay').fadeIn(250);
				ajaxPreloaderOff();
			}
		});
	}

	$('.transfer-object').on('click', function () {
		if ($(this).closest('.wrap-object').find('.wrap-pu.no-result:not(.aiiskue)').length > 0){
			// var firstNorersult = $('.wrap-pu.no-result:first');
			// if (!firstNorersult.hasClass('active')){
			// 	firstNorersult.children('.collapse-btn').addClass('active');
			// 	firstNorersult.children('.collapse-content').attr('style', '');
			// }
			// firstNorersult.find('.label-error').show();
			// $('html, body').animate({ scrollTop: firstNorersult.offset().top }, 500);
			var emptyPu = '';
			$(this).closest('.wrap-object').find('.wrap-pu.no-result:not(.aiiskue)').each(function () {
				emptyPu = emptyPu + $(this).find('.sub-objects-btn').text() + '</br>';
			});
			$(this).prev().find('.empty-pu').html(emptyPu);
			$(this).prev().show();
		} else {
			transferIndication($(this));
		}

		return false;
	});

	$('.tranfer-empty').on('click', function(){
		transferIndication($(this));
		$(this).parent().hide();
	});

	$('.back-empty').on('click', function(){
		var firstNorersult = $(this).closest('.wrap-object').find('.wrap-pu.no-result:first');
		if (!firstNorersult.hasClass('active')){
			firstNorersult.children('.collapse-btn').addClass('active');
			firstNorersult.children('.collapse-content').attr('style', '');
		}
		firstNorersult.find('.label-error').show();
		$('html, body').animate({ scrollTop: firstNorersult.offset().top }, 500);
		$(this).parent().hide();
	});

	//обработка формы перехода на оплату
	$('.pay-form').on('submit', function(){
		if ($(this).hasClass('testing')) {
			if ($(window).scrollTop() > $('.bg').scrollTop()) {
				var topPos = $(window).scrollTop() + 50;
			} else {
				var topPos = $('.bg').scrollTop() + 50;
			}
			$('.warning-pay-popup').animate({'top': topPos}, 450);
			$('.contracts-devices-popup-overlay').fadeIn(250);

			return false;
		}
	});

	//показать прелоадер
	function ajaxPreloaderOn(){
		$('.preloader').css({'display':'block', 'opacity':'0.5'});
		$('.preloader .spinner').css('display', 'inline-block');
	}
	//скрыть прелоадер
	function ajaxPreloaderOff() {
		var preload = $('.preloader');
		preload.find('.spinner').fadeOut(function(){
			preload.fadeOut(500);
		});
	}
	//доступные отчеты в разделе НАЧИСЛЕНИЯ И ПЛАТЕЖИ
	if ($('.sidebar-menu-fw a.active').attr('data-odn') != 'true' && $('.type-order option[value=odn]').length){
		$('.type-order option[value=odn]').attr('disabled', 'disabled');
		$('.tr-odn').css('display', 'none');

	}


	//обработка формы НАЧИСЛЕНИЯ И ПЛАТЕЖИ
	$('.loading-report-popup .submit-report').on('click', function(){
		$('.get-order-form').submit();
		$('.loading-report-popup .close').trigger('click');
		return false;
	});
	$('.loading-report-popup .new-date').on('click', function(){
		$('.loading-report-popup .close').trigger('click');
		return false;
	});
	$('.get-order-form').on('submit', function(){
		var dateFrom = $('#from_dialog').val();
		var dateTo = $('#to_dialog').val();


		if ($('.loading-report-popup.open').length == 0) {

			var dateFromArr = dateFrom.split('.');
			var dateToArr = dateTo.split('.');
			var Date1 = new Date (dateFromArr[2], dateFromArr[1], dateFromArr[0]);
			var Date2 = new Date (dateToArr[2], dateToArr[1], dateToArr[0]);
			var Days = Math.floor((Date2.getTime() - Date1.getTime())/(1000*60*60*24));
			if (Days > 125) {
				$('.loading-report-popup').addClass('open');
				$('.loading-report-popup').animate({'top': $(window).scrollTop() + 50}, 450);
				$('.contracts-devices-popup-overlay').fadeIn(250);
				return false;
			}
		}
		var uid = $('.sidebar-menu-fw a.active').attr('data-uid');

		switch ($('.type-order option:selected').val()) {
			case 'detail':

				$('.detail-report-wrap .title').html('Детализация счета по договору<br/>' + $('.sidebar-menu-fw a.active').attr('data-name') + '<br/>за период ' + dateFrom + '-' + dateTo);

				$('.detail-report-wrap a.print').attr('href', $('.detail-report-wrap a.print').attr('href')+'&uid=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo);
				$('.detail-report-wrap a.download').attr('href', $('.detail-report-wrap a.download').attr('href')+'&uid=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo);

				$('.report-item').hide();
				$('.detail-report-wrap').show();
				break;
			case 'penalty':
				ajaxPreloaderOn();
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
						ajaxPreloaderOff();
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
				ajaxPreloaderOn();
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
						ajaxPreloaderOff();
					}
				});
				break;
			case 'accruedpaid':
				ajaxPreloaderOn();
				$.ajax({
					type: 'POST',
					url: '/ajax/accrued-paid',
					data: 'uidcontracts=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo,
					success: function (msg) {
						try {
							var msgArr = JSON.parse(msg);
						} catch (e) {
							$('.report-item').hide();
							$('.accruedpaid-report-wrap').html(msg);
							$('.accruedpaid-report-wrap').show();
						}
						if (msgArr !== undefined){
							alert(msgArr.error);
						}
						ajaxPreloaderOff();
					}
				});
				break;
			case 'invoices':
				ajaxPreloaderOn();
				$.ajax({
					type: 'POST',
					url: '/ajax/list-invoice',
					data: 'uidcontracts=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo,
					success: function (msg) {
						try {
							var msgArr = JSON.parse(msg);
						} catch (e) {
							$('.report-item').hide();
							$('.invoices-report-wrap ul').html(msg);
							$('.invoices-report-wrap').show();
						}
						if (msgArr !== undefined){
							alert(msgArr.error);
						}
						ajaxPreloaderOff();
					}
				});
				break;
			default:
				alert( "Нет таких значений" );
		}

		return false;
	});

	// if ($('.type-order option:selected').val()!=''){
	// 	$('.get-order-form').submit();
	// }

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

	$('.custom-popup .close').on('click', function(){
		$('.custom-popup').animate({'top': '-3000px'}, 450);
		$('.contracts-devices-popup-overlay').fadeOut(250);
		$('.loading-report-popup.open').removeClass('open');
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
		ajaxPreloaderOn();
		$(this).css('display', 'none');
		$.ajax({
			type: "GET",
			url: "/main/all-arrear",
			data: 'uid='+$(this).attr('data-uid'),
			success: function(mess) {
				$('.wrap-invoice').html(mess);
				$('.arrear-lists.white-box .white-box-title').text('Все выставленные счета');
				ajaxPreloaderOff();
			}
		});
		return false;
	});

	/*contracts popup*/
	$('.contracts-devices .devices-link').on('click', function(){
		if ($(window).scrollTop() > $('.bg').scrollTop()){
			var topPos = $(window).scrollTop() + 50;
		} else {
			var topPos = $('.bg').scrollTop() + 50;
		}
		$('.contracts-devices-popup .table').html($(this).next('div').html());
		$('.contracts-devices-popup').animate({'top': topPos}, 450);
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
		slidesPerView: 1,
		spaceBetween:10,
		loop: false,
		speed: 1000,
		pagination: {
			el: '.work-gallery-carousel .swiper-pagination',
			type: 'fraction',
		},
		navigation: {
			nextEl: '.main-carousel .swiper-button-next',
			prevEl: '.main-carousel .swiper-button-prev',
		},
		breakpoints: {
			// when window width is >= 640px
			640: {
				slidesPerView: 'auto'
			}
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
		$('.popup-box.sidebar-mobile-popup').fadeOut();
		$(this).closest('li').find('.popup-box.sidebar-mobile-popup').fadeIn();
		$('.overlay').fadeIn(250);
		//return false; не работаю переходы по ссылкам
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
			changeYear: true,
		numberOfMonths: 1,
			regional: "ru",
			showButtonPanel: true,
			onClose: function(dateText, inst) {
				$(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
			}
	})
	.on( "change", function() {
		to.datepicker( "option", "minDate", getDate( this ) );
		to.datepicker( "option", "maxDate", getDate( this , '+1y') );
	}),

	to = $( "#to_dialog" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		changeYear: true,
		numberOfMonths: 1,
		showButtonPanel: true,
		regional: "ru",
		onClose: function(dateText, inst) {
			$(this).datepicker('setDate', new Date(inst.selectedYear, parseInt(inst.selectedMonth)+1, 0));
		}
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
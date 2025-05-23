/* Preloader */
$(window).on("load", function () {
    /*tin*/
    $('.profile-details ul li:not(.last)').each(function () {
        if ($(this).height() < $(this).find('.value').height()) {
            $(this).height($(this).find('.value').height());
        }
    });
    /*ENDtin*/
    /* Preload */
    var preload = $('.preloader');
    preload.find('.spinner').fadeOut(function () {
        preload.fadeOut(500);
    });

});


$(function () {
    var width = $(window).width();

    /*debug response 1C*/
    $('.show-response').on('click', function(){
        $('.response-1c').show();
    });
    /*ENDdebug response 1C*/

    $('.ajax-c-form .submit-btn').on('click', function(){
        calcPrice();
        if ($(this).closest('form').find('.has-error').length > 0){
            $('.form-tab').removeClass('active');
            $('.form-group.has-error').closest('.form-tab').addClass('active');
            if ($('.draft-contract-form .form-tab:first').hasClass('active')) {
                $(this).addClass('hidden');
            }
            $('.next-btn').removeClass('hidden');
            $('.submit-btn').addClass('hidden');
            return false;
        }
    });

    function calcPrice (){
        function parseFormattedNumber(value) {
            return parseFloat(value.replace(/\s/g, ''));
        }

        let resultInput = $('.calc-result');
        let priceAllInput = $('.calc-price-all');
        let priceOffInput = $('.calc-price-off');

        var priceAll = parseFormattedNumber(priceAllInput.val()) || 0;
        var priceOff = parseFormattedNumber(priceOffInput.val()) || 0;

        if (priceAll < 0){
            priceAllInput.closest('.form-group').addClass('has-error');
            priceAllInput.siblings('.help-block').text('Значение не должно быть отрицательным.');
            priceAllInput.data('yiiActiveForm', null);
        } else {
            priceAllInput.closest('.form-group').removeClass('has-error');
            priceAllInput.siblings('.help-block').text('');
            priceAllInput.addClass('send-a');
        }

        var result = priceAll - priceOff;


        if (result < 0){
            resultInput.closest('.form-group').addClass('has-error');
            resultInput.siblings('.help-block').text('Значение не может быть отрицательным. Пожалуйста, измените цену контракта (договора).');
            resultInput.data('yiiActiveForm', null);
        } else {
            resultInput.closest('.form-group').removeClass('has-error');
            resultInput.siblings('.help-block').text('');
            priceOffInput.closest('.form-group').removeClass('has-error');
            priceOffInput.siblings('.help-block').text('');
            priceOffInput.addClass('send-a');
        }

        resultInput.val(result.toLocaleString('ru-RU', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).replace(',', '.')); // Округляем до 2 знаков после запятой
        resultInput.addClass('send-a');
    }

    $('.generate-draft-pdf').on('click', function (e) {
        e.preventDefault();

        const urlParams = new URLSearchParams(window.location.search);
        let draftId = urlParams.get('id');
        let url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'POST',
            data: { draft: draftId},
            success: function (response) {
                if (response.status === 'success') {
                    // Создаем временную ссылку для скачивания
                    let link = document.createElement('a');
                    link.href = response.pdfUrl;
                    link.download = 'Заявление.pdf'; // Имя файла при скачивании
                    document.body.appendChild(link);
                    link.click(); // Имитируем клик
                    document.body.removeChild(link); // Удаляем ссылку
                } else {
                    alert('Ошибка при генерации PDF');
                }
            },
            error: function () {
                alert('Ошибка при отправке данных');
            }
        });
    });

    function formatNumber(value) {
        const number = parseFloat(value.replace(/\s/g, '').replace(',', '.') || 0); // Убираем пробелы и заменяем ',' на '.'
        return number.toLocaleString('ru-RU', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).replace(',', '.');
    }

    function formatNumberInt(value) {
        const number = parseFloat(value.replace(/\s/g, '').replace(',', '.') || 0); // Убираем пробелы и заменяем ',' на '.'
        return number.toLocaleString('ru-RU', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).replace(',', '.');
    }

    $('.num-format').on('change', function () {
        const formattedValue = formatNumber($(this).val());
        $(this).val(formattedValue);
    });
    $('.num-format').on('focus', function () {
        if ($(this).val() == '0.00'){
            $(this).val('');
        }
    });
    $('.num-format').on('blur', function () {
        if ($(this).val() == ''){
            $(this).val('0.00');
        }
    });

    // Датапикер
    var dateFormatDraft = "dd.mm.yy",
        fromDraft = $(".from-date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            regional: "ru",
        })
            .on("change", function () {
                toDraft.datepicker("option", "minDate", getDateDraft(this));
                $(this).addClass('send-a');
                sendFormAjax();
            }),

        toDraft = $(".to-date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            regional: "ru",
        })
            .on("change", function () {
                fromDraft.datepicker("option", "maxDate", getDateDraft(this));
                $(this).addClass('send-a');
                sendFormAjax();
            });

    function getDateDraft(element) {
        var date;
        try {
            date = $.datepicker.parseDate(dateFormatDraft, element.value);
        } catch (error) {
            date = null;
        }

        return date;
    }


    // Удаление загруженных файлов
    $('body').on('click', '.removeLoadedFile', function () {
        ajaxPreloaderOn();
        var actionForm = $(this).closest('ul').attr('ajax-action');
        var ajaxData = 'draftId=' + $(this).closest('ul').attr('draft-id') + '&fileId=' + $(this).data('idx');
        $.ajax({
            url: actionForm,
            type: 'POST',
            data: ajaxData,
            success: function (response) {
                $('#wrap-uploaded-files').html(response);
                ajaxPreloaderOff();
                console.log('Форма успешно отправлена');
            },
            error: function () {
                ajaxPreloaderOff();
                console.log('Произошла ошибка при отправке формы');
            }
        });
    });

    // Переключение табов
    $('.draft-contract-form .next-btn').on('click', function () {
        let newActive = $('.draft-contract-form .form-tab.active').next('.form-tab');
        $('.draft-contract-form .form-tab').removeClass('active');
        newActive.addClass('active');
        if ($('.draft-contract-form .form-tab:last').hasClass('active')) {
            $(this).addClass('hidden');
            $('.submit-btn').removeClass('hidden');
        }
        $('.prev-btn').removeClass('hidden');
        return false;
    });

    $('.draft-contract-form .prev-btn').on('click', function () {
        let newActive = $('.draft-contract-form .form-tab.active').prev('.form-tab');
        $('.draft-contract-form .form-tab').removeClass('active');
        newActive.addClass('active');
        if ($('.draft-contract-form .form-tab:first').hasClass('active')) {
            $(this).addClass('hidden');
        }
        $('.next-btn').removeClass('hidden');
        $('.submit-btn').addClass('hidden');
        return false;
    });

    // Подсказки
    $('.input-tooltip-js').on('click', function () {
        $('.draft-input-popup p').text($(this).next('div').text());
        $('.draft-input-popup').animate({'top': $(window).scrollTop() + 50}, 450);
        $('.contracts-devices-popup-overlay').fadeIn(250);
        return false;
    });

    // Обрабатываем событие валидации поля
    $('.ajax-c-form').on('afterValidateAttribute', function (event, attribute, messages) {
        if (messages.length === 0 && !($(attribute.input).hasClass('draft-files')) && !($(attribute.input).hasClass('send-contract'))) {
            $(attribute.input).addClass('send-a');
            if ($(attribute.input).hasClass('calc-price')) {
                calcPrice();
            }
            if ($(attribute.input).hasClass('calc-price-all')) {
                let priceperpiece = parseFloat($('.priceperpiece').val().replace(/\s/g, ''));
                if (priceperpiece != 0){
                    let allprice = parseFloat($('.calc-price-all').val().replace(/\s/g, ''));
                    let planValue = allprice/priceperpiece;
                    $('.calc-plane-volume').val(formatNumber(planValue.toString()));
                } else {
                    $('.calc-plane-volume').val(formatNumber('0'));
                }
                $('.calc-plane-volume').addClass('send-a');
            }
            if ($(attribute.input).hasClass('calc-new-price')) {
                let tarif = parseFloat($('.calc-new-volume').data('tarif'));
                if (tarif != 0){
                    let allprice = parseFloat($('.calc-new-price').val().replace(/\s/g, ''));
                    let planValue = allprice/tarif;
                    $('.calc-new-volume').val(formatNumberInt(planValue.toString()));
                } else {
                    $('.calc-new-volume').val(formatNumberInt('0'));
                }
                $('.calc-new-volume').addClass('send-a');
            }
            if ($(attribute.input).hasClass('off-budget-input')) {
                if ($(attribute.input).is(':checked')) {
                    $('.off-budget-section').slideDown();
                } else {
                    $('.off-budget-section').slideUp();
                    $('.field-draftcontractform-off_budget_name input').addClass('send-a').val('');
                    $('.field-draftcontractform-off_budget_value input').addClass('send-a').val(0);
                }
            }
            sendFormAjax();
        }
        if ($(attribute.input).hasClass('send-contract')){
            location.href = $(attribute.input).find('option:selected').data('url');
        }
        if ($(attribute.input).hasClass('off-budget-input')) {
            if ($(attribute.input).is(':checked')) {
                $('.off-budget-section').slideDown();
            } else {
                $('.off-budget-section').slideUp();
                $('.field-draftcontractform-off_budget_name input').val('');
                $('.field-draftcontractform-off_budget_value input').val(0);
            }
        }

    });

    // Функция для отправки формы через AJAX
    function sendFormAjax(files = false) {
        var form = $('.ajax-c-form')[0];
        var ajaxData = new FormData(form);
        var actionForm = form.action;
        var content = false;
        var process = false;
        if (files) {
            ajaxPreloaderOn();
        } else {
            form = $('.ajax-c-form');
            actionForm = form.attr('action');
            content = 'application/x-www-form-urlencoded; charset=UTF-8';
            process = true;
            var formData = form.serializeArray();
            var filteredData = formData.filter(function (input) {
                return form.find('[name="' + input.name + '"]').hasClass('send-a');
            });

            ajaxData = $.param(filteredData);

        }

        $.ajax({
            url: actionForm,
            type: 'POST',
            data: ajaxData,
            processData: process,
            contentType: content,
            success: function (response) {
                if (files) {
                    allDraftFiles = [];
                    updateDraftFileList();
                    $('#wrap-uploaded-files').html(response);
                    $('.draft-files').val('');
                    $('.file-required').parent().removeClass('has-error');
                    $('.file-required').next('.help-block').html('');
                    ajaxPreloaderOff();
                } else {
                    form.find('.send-a').removeClass('send-a');
                }
                console.log('Форма успешно отправлена');
            },
            error: function () {
                if (files) {
                    allDraftFiles = [];
                    updateDraftFileList();
                    ajaxPreloaderOff();
                } else {
                    form.find('.send-a').removeClass('send-a');
                }
                console.log('Произошла ошибка при отправке формы');
            }
        });
    }


    let allDraftFiles = [];

    $('.draft-files').on('change', function (e) {
        sendFormAjax(true);
    });

    // Проверка наличия незагруженных файлов
    function checkFileList() {
        if ($('#filesList li').length > 0) {
            $('.submit-file-btn-js').show();
            $('.draft-files').addClass('input-file-hide-text');
        } else {
            $('.submit-file-btn-js').hide();
            $('.draft-files').removeClass('input-file-hide-text');
        }
    }

    $(document).on('click', '.removeDraftFile', function () {
        let index = $(this).data('index');
        allDraftFiles.splice(index, 1);

        updateDraftFileList();
    });

    function updateDraftFileList() {
        $('#filesList').empty();
        let show = 0;
        for (let i = 0; i < allDraftFiles.length; i++) {
            show = 1;
            $('#filesList').append('<li><span>' + allDraftFiles[i].name +
                '</span> <button class="removeDraftFile" data-index="' + i + '">Х</button></li>');
        }

        checkFileList();
    }

    /**
     Tabs
     **/
    $('.tab-menu').on('click', '.tab-btn', function () {
        var tab_bl = $(this).attr('href');

        $(this).closest('.tab-menu').find('li').removeClass('active');
        $(this).closest('li').addClass('active');

        $(this).closest('.tabs').find('.tab-item').hide();
        $(tab_bl).fadeIn();

        return false;
    });

    $('.ajax-pdf-update').on('click', function (e) {
        e.preventDefault();

        let filesName = [];
        $('#filesList li').each(function ($i) {
            filesName[$i] = $(this).children('span').text();
        });
        let messageId = $(this).attr('message');

        $.ajax({
            url: '/messages/generate-pdf',
            type: 'POST',
            data: {filesuploadnames: filesName.join(', '), message: messageId},
            success: function (response) {
                if (response.status === 'success') {
                    // Создаем временную ссылку для скачивания
                    let link = document.createElement('a');
                    link.href = response.pdfUrl;
                    link.download = 'Обращение.pdf'; // Имя файла при скачивании
                    document.body.appendChild(link);
                    link.click(); // Имитируем клик
                    document.body.removeChild(link); // Удаляем ссылку
                } else {
                    alert('Ошибка при генерации PDF');
                }
            },
            error: function () {
                alert('Ошибка при отправке данных');
            }
        });
    });


    $('.ajax-pdf').on('click', function (e) {
        e.preventDefault();
        let filesName = [];
        $('#filesList li').each(function ($i) {
            filesName[$i] = $(this).children('span').text();
        });
        $('#messages-filesuploadnames').val(filesName.join(', '));
        var formData = new FormData($('.messages-form form')[0]);

        $.ajax({
            url: '/new-message/generate-pdf',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status === 'success') {
                    // Создаем временную ссылку для скачивания
                    let link = document.createElement('a');
                    link.href = response.pdfUrl;
                    link.download = 'Обращение.pdf'; // Имя файла при скачивании
                    document.body.appendChild(link);
                    link.click(); // Имитируем клик
                    document.body.removeChild(link); // Удаляем ссылку
                } else {
                    alert('Ошибка при генерации PDF');
                }
            },
            error: function () {
                alert('Ошибка при отправке данных');
            }
        });
    });

    let allFiles = [];

    $('#messages-filesupload').on('change', function (e) {
        for (let i = 0; i < e.target.files.length; i++) {
            allFiles.push(e.target.files[i]);
        }

        updateFileList();

        $('#messages-filesupload').val('');
    });

    $(document).on('click', '.removeFile', function () {
        let index = $(this).data('index');
        allFiles.splice(index, 1);

        updateFileList();
    });

    function updateFileList() {
        $('#filesList').empty();
        let show = 0;
        for (let i = 0; i < allFiles.length; i++) {
            show = 1;
            $('#filesList').append('<li><span>' + allFiles[i].name +
                '</span> <button class="removeFile" data-index="' + i + '">Х</button></li>');
        }
        if (show > 0) {
            $('.messages-form form button[type=submit]').removeClass('hidden');
            $('#messages-filesupload').addClass('input-file-hide-text');
        } else {
            $('.messages-form form button[type=submit]').addClass('hidden');
            $('#messages-filesupload').removeClass('input-file-hide-text');
        }
    }

    $('.new-message-create #w0, .messages-update #w0').on('submit', function () {
        let dt = new DataTransfer();

        for (let i = 0; i < allFiles.length; i++) {
            dt.items.add(allFiles[i]);
        }

        document.getElementById('messages-filesupload').files = dt.files;
    });


    if ($('#receiptform-ee').length > 0) {
        new Cleave('#receiptform-ee', {
            numeral: true,
            numeralDecimalMark: ',',
            delimiter: ''
        });
    }
    if ($('#receiptform-penalty').length > 0) {
        new Cleave('#receiptform-penalty', {
            numeral: true,
            numeralDecimalMark: ',',
            delimiter: ''
        });
    }

//push
//setTimeout(function(){
//alert(window.userId);
//},5000);
//paginate consumption
    $('.wrap-paginate .more').on('click', function () {
        $('.objects-item.hidden-pag').removeClass('hidden-pag');
        $(this).css('display', 'none');
        return false;
    });

//checkbox ios
    $(window).keyup(function (e) {
        var target = $('.checkbox-ios input:focus');
        if (e.keyCode == 9 && $(target).length) {
            $(target).parent().addClass('focused');
        }
    });

    $('.checkbox-ios input').focusout(function () {
        $(this).parent().removeClass('focused');
    });
    //popup ep
    $('.colculation-popup-link').on('click', function () {
        $.fancybox.open($(this).siblings('.colculation-popup'));
        return false;
    });

//popup отсутстви Пирамиды
    $('.empty-pitrammida').on('click', function () {
        var topPos = $('.bg').scrollTop() + 50;
        if ($(window).scrollTop() > $('.bg').scrollTop()) {
            topPos = $(window).scrollTop() + 50;
        }
        $('.pirammida-empty-popup').animate({'top': topPos}, 450);
        $('.contracts-devices-popup-overlay').fadeIn(250);
        return false;
    });

//popup удалить аккаунт
    $('.remove-akk').on('click', function () {
        $.fancybox.open('<div class="message-del"><h2>Удалить ваш аккаунт?</h2>' +
            '<p>Вы хотите удалить ваш аккаунт безвозвратно?</p>' +
            '<a href="#" class="del">Удалить</a><a href="#" class="can">Отмена</a></div>');
        return false;
    });

    $('body').on('click', '.message-del a.del', function () {
        $.fancybox.close();
        $.fancybox.open('<div class="message-del">' +
            '<p>Ваш аккаунт будет удалён в течение 24 часов</p>' +
            '<a href="#" class="can">Понятно</a></div>');
        return false;
    });
    $('body').on('click', '.message-del a.can', function () {
        $.fancybox.close();
        return false;
    });
    //удаление файлов из ФОС
    $('#feedbackform-file').on('change', function () {
        if ($(this).parent().siblings('.fos-file-close').length == 0) {
            $(this).parent().after('<span class="fos-file-close"></span>');
        }
    });
    $('#feedbackForm').on('click', '.fos-file-close', function () {
        var el = $(this).parent();
        el.wrap('<form>').closest('form').get(0).reset();
        el.unwrap();
        $(this).remove();
    });

    //тип ответа на ФОС
    function setAnswerType(i) {
        if (i == 'phone') {
            $('#feedbackForm input.phone').closest('.group').css('display', 'block');
            $('#feedbackForm input.email').closest('.group').css('display', 'none');
            $('#feedbackForm input.phone').attr('required', 'required');
            $('#feedbackForm input.email').removeAttr('required');
        } else {
            $('#feedbackForm input.phone').closest('.group').css('display', 'none');
            $('#feedbackForm input.email').closest('.group').css('display', 'block');
            $('#feedbackForm input.phone').removeAttr('required');
            $('#feedbackForm input.email').attr('required', 'required');
        }
    }

    $('#feedbackForm .type-answer input[type=radio]').on('change', function () {
        setAnswerType($(this).val());
    });
    setAnswerType($('#feedbackForm .type-answer input[type=radio]:checked').val());

    //попап История показаний
    /*
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
    });*/

    //пересчёт в форме оплаты
    $('.sber-form .value input').on('change', function () {
        if ($(this).val() == '') {
            $(this).val('0');
        }
        if (parseFloat($(this).val()) > parseFloat($(this).attr('max'))) {
            $(this).val($(this).attr('max'));
        }
        var all = 0;
        $('.sber-form .value input').each(function () {
            all = all + parseFloat($(this).val().replace(',', '.'));
        });
        if (all > 0) {
            var op = {};
            if ((all ^ 0) !== all) {
                op = {minimumFractionDigits: 2};
            }
            $('.all-price').html(all.toLocaleString('ru-RU', op) + '₽');
        } else {
            $('.all-price').html('0');
        }
    });
    //прелоадер на ссылки
    $('.ploader').on('click', function () {
        ajaxPreloaderOn();
    });
    //прикрепление фото ПУ
    $('label.computation-pu').on('click', function () {
        if ($(this).hasClass('disabled')) {
            return false;
        }
        $(this).addClass('current-label-attach');
        var puid = $(this).closest('.wrap-pu').attr('data-puid');
        var num = $(this).closest('.wrap-pu').find('.sub-objects-btn').text();
        var contract = $('.sidebar-menu-fw a.active').text();
        var objectid = $(this).closest('.wrap-object').find('.objects-head').find('.name').find('a').text();
        if ($('#attachFormPu .puidInput').val() != puid) {
            $('#attachFormPu')[0].reset();
            $('#attachFormPu .puidInput').val(puid);
            $('#attachFormPu .numInput').val(num);
            $('#attachFormPu .contractInput').val(contract);
            $('#attachFormPu .objectidInput').val(objectid);
        }

    });
    $('#attachform-photo').on('change', function () {
        var file = $('#attachform-photo')[0].files[0];
        if (file) {
            $('span.photo-name').remove();
            $('.current-label-attach').after('<span class="photo-name">' + file.name + '<span data-input="field-attachform-photo"></span></span>');
            $('.current-label-attach').removeClass('current-label-attach');
        }
    });
    $('#attachform-time').on('change', function () {
        var file = $('#attachform-time')[0].files[0];
        if (file) {
            $('span.photo-time').remove();
            $('.current-label-attach').after('<span class="photo-time">' + file.name + '<span data-input="field-attachform-time"></span></span>');
            $('.current-label-attach').removeClass('current-label-attach');
        }
    });
    //отправка фото ПУ
    $('button.computation-pu').on('click', function () {
        var topPos = $('.bg').scrollTop() + 50;
        if ($(window).scrollTop() > $('.bg').scrollTop()) {
            topPos = $(window).scrollTop() + 50;
        }

        var puid = $(this).closest('.wrap-pu').attr('data-puid');
        if ($('#attachFormPu .puidInput').val() != puid) {
            $('#attachFormPu')[0].reset();
        }

        if ($('#attachform-photo').val() == '' && $('#attachform-time').val() == '') {
            $('.attach-popup .message').text('Прикрепите фото ПУ или Почасовые объемы');

            $('.attach-popup').animate({'top': topPos}, 450);
            $('.contracts-devices-popup-overlay').fadeIn(250);
        } else {
            if ($(this).hasClass('load')) {
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
            } else {
                $('.attach-popup .message').text('Для передачи показаний необходимо ввести данные в окно ввода показаний');

                $('.attach-popup').animate({'top': topPos}, 450);
                $('.contracts-devices-popup-overlay').fadeIn(250);
            }

        }

    });
    //удаление прикреплённого фото ПУ
    $('.attach-form').on('click', 'span span', function () {
        var el = '#attachFormPu .' + $(this).attr('data-input');
        $(el).wrap('<form>').closest('form').get(0).reset();
        $(el).unwrap();
        $(this).parent().remove();
    });

    //политика
    $('input.polit').bind('change', function () {
        if ($(this).prop("checked")) {
            $(this).closest('form').find('button').removeAttr('disabled');
        } else {
            $(this).closest('form').find('button').attr('disabled', 'disabled');
        }
    });
    //разворачивание объекта по клику на заголовок
    $('.trigger-more').on('click', function () {
        $(this).siblings('.contracts-more').children('.more-link').trigger('click');
    });
    //информация по ценовым категориям
    $('.price-category-btn').on('click', function () {
        $('.price-category-popup').animate({'top': $(window).scrollTop() + 50}, 450);
        $('.contracts-devices-popup-overlay').fadeIn(250);
        return false;
    });
    //подсказка о просмотре информации ПУ
    $('.pu-ask-btn').on('click', function () {
        if ($(window).scrollTop() > $('.bg').scrollTop()) {
            var topPos = $(window).scrollTop() + 50;
        } else {
            var topPos = $('.bg').scrollTop() + 50;
        }
        $('.information-pu-popup').animate({'top': topPos}, 450);
        $('.contracts-devices-popup-overlay').fadeIn(250);
        return false;
    });
    //формирование Акта фиксации
    $('.get-act').on('click', function () {
        if ($(this).closest('.wrap-object').find('.wrap-pu.no-result:not(.aiiskue)').length > 0) {
            var firstNorersult = $('.wrap-pu.no-result:first');
            if (!firstNorersult.hasClass('active')) {
                firstNorersult.children('.collapse-btn').addClass('active');
                firstNorersult.children('.collapse-content').attr('style', '');
            }
            firstNorersult.find('.label-error').show();
            $('html, body').animate({scrollTop: firstNorersult.offset().top}, 500);
            return false;
        } else {
            //ajaxPreloaderOn();
            var indications = '?uidcontracts=' + $('.sidebar-menu-fw a.active').attr('data-uid') + '&uidobject=' + $(this).closest('.wrap-object').attr('data-id');
            $(this).closest('.wrap-object').find('.wrap-pu:not(.aiiskue)').each(function () {
                indications += '&' + $(this).attr('data-id') + '=' + $(this).attr('data-idication');
            });
            var hrefArr = $(this).attr('href').split('?');
            $(this).attr('href', hrefArr[0] + indications);
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
    function transferIndication(btn) {
        ajaxPreloaderOn();
        var puArr = [];
        btn.closest('.wrap-object').find('.wrap-pu:not(.no-result, .aiiskue)').each(function () {
            puArr.push({
                'indications': $(this).attr('data-idication'),
                'uidtu': $(this).attr('data-id'),
                'uidpu': $(this).attr('data-puid')
            });
            $(this).find('.computation-pu').addClass('load');
        });
        $.ajax({
            type: 'POST',
            url: '/ajax/transfer',
            data: 'uidobject=' + btn.closest('.wrap-object').attr('data-id') + '&uidcontract=' + $('.sidebar-menu-fw a.active').attr('data-uid') + '&id=' + $('.uid-d').attr('data-uid') + '&tu=' + JSON.stringify(puArr),
            //data: '{"id":"c222afaaff-9e30-11e4-9c77-001e8c2d263f","uidcontract":"b95aa4a7-9f5e-11e4-9c77-001e8c2d263f","tu":[{"uidtu":"a383457f-19a8-41bd-99af-44c19f7afdb3", "indications":10000},{"uidtu":"8907550c-9e9a-11e4-9c77-001e8c2d263f", "indications":12000}]}',
            success: function (msg) {
                $('.transfer-indication-popup h3').text(msg);
                var topPos = $('.bg').scrollTop() + 50;
                if ($(window).scrollTop() > $('.bg').scrollTop()) {
                    topPos = $(window).scrollTop() + 50;
                }
                $('.transfer-indication-popup').animate({'top': topPos}, 450);
                $('.contracts-devices-popup-overlay').fadeIn(250);
                ajaxPreloaderOff();
            }
        });
    }

    $('.transfer-object').on('click', function () {
        if ($(this).hasClass('disabled')) return false;
        if ($(this).closest('.wrap-object').find('.wrap-pu.no-result:not(.aiiskue)').length > 0) {
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

    $('.tranfer-empty').on('click', function () {
        transferIndication($(this));
        $(this).parent().hide();
    });

    $('.back-empty').on('click', function () {
        var firstNorersult = $(this).closest('.wrap-object').find('.wrap-pu.no-result:first');
        if (!firstNorersult.hasClass('active')) {
            firstNorersult.children('.collapse-btn').addClass('active');
            firstNorersult.children('.collapse-content').attr('style', '');
        }
        firstNorersult.find('.label-error').show();
        $('html, body').animate({scrollTop: firstNorersult.offset().top}, 500);
        $(this).parent().hide();
    });

    //обработка формы перехода на оплату
    $('.pay-form').on('submit', function () {
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
    function ajaxPreloaderOn() {
        $('.preloader').css({'display': 'block', 'opacity': '0.5'});
        $('.preloader .spinner').css('display', 'inline-block');
        setTimeout(function () {
            if ($('.preloader').is(':visible')) {
                $('.preloader').css({'opacity': '1'});
                $('.preloader .message').css('display', 'block');
            }
        }, 40000);
    }

    //скрыть прелоадер
    function ajaxPreloaderOff() {
        var preload = $('.preloader');
        preload.find('.spinner').fadeOut(function () {
            preload.fadeOut(500);
        });
        $('.preloader .message').css('display', 'none');
    }

    //скрыть прелоадер по клику на кнопку по истечению таймаута
    $('.spiner-close').on('click', function () {
        ajaxPreloaderOff();
        return false;
    });
    //доступные отчеты в разделе НАЧИСЛЕНИЯ И ПЛАТЕЖИ
    if ($('.sidebar-menu-fw a.active').attr('data-odn') != 'true' && $('.type-order option[value=odn]').length) {
        $('.type-order option[value=odn]').attr('disabled', 'disabled');
        $('.tr-odn').css('display', 'none');

    }


    //обработка формы НАЧИСЛЕНИЯ И ПЛАТЕЖИ
    $('.loading-report-popup .submit-report').on('click', function () {
        $('.get-order-form').submit();
        $('.loading-report-popup .close').trigger('click');
        return false;
    });
    $('.loading-report-popup .new-date').on('click', function () {
        $('.loading-report-popup .close').trigger('click');
        return false;
    });
    $('.get-order-form').on('submit', function () {
        var dateFrom = $('#from_dialog').val();
        var dateTo = $('#to_dialog').val();


        if ($('.loading-report-popup.open').length == 0) {

            var dateFromArr = dateFrom.split('.');
            var dateToArr = dateTo.split('.');
            var Date1 = new Date(dateFromArr[2], dateFromArr[1], dateFromArr[0]);
            var Date2 = new Date(dateToArr[2], dateToArr[1], dateToArr[0]);
            var Days = Math.floor((Date2.getTime() - Date1.getTime()) / (1000 * 60 * 60 * 24));
            if (Days > 125) {
                $('.loading-report-popup').addClass('open');
                if ($(window).scrollTop() > $('.bg').scrollTop()) {
                    var topPos = $(window).scrollTop() + 50;
                } else {
                    var topPos = $('.bg').scrollTop() + 50;
                }
                $('.loading-report-popup').animate({'top': topPos}, 450);
                $('.contracts-devices-popup-overlay').fadeIn(250);
                return false;
            }
        }
        var uid = $('.sidebar-menu-fw a.active').attr('data-uid');

        switch ($('.type-order option:selected').val()) {
            case 'detail':

                $('.detail-report-wrap .title').html('Детализация счета по договору<br/>' + $('.sidebar-menu-fw a.active').attr('data-name') + '<br/>за период ' + dateFrom + '-' + dateTo);

                $('.detail-report-wrap a.print').attr('href', $('.detail-report-wrap a.print').attr('href') + '&uid=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo);
                $('.detail-report-wrap a.download').attr('href', $('.detail-report-wrap a.download').attr('href') + '&uid=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo);
                $('.detail-report-wrap a.download-mobile').attr('href', $('.detail-report-wrap a.download-mobile').attr('href') + '&uid=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo);

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
                        if (msgArr !== undefined) {
                            alert(msgArr.error);
                        }
                        ajaxPreloaderOff();
                    }
                });
                break;
            case 'odn':

                $('.odn-report-wrap .title').html('Отчёт по расчёту ОДН <br/> за период ' + dateFrom + '-' + dateTo);

                $('.odn-report-wrap a.print').attr('href', $('.odn-report-wrap a.print').attr('href') + '&uid=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo);
                $('.odn-report-wrap a.download').attr('href', $('.odn-report-wrap a.download').attr('href') + '&uid=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo);
                $('.odn-report-wrap a.download-mobile').attr('href', $('.odn-report-wrap a.download-mobile').attr('href') + '&uid=' + uid + '&withdate=' + dateFrom + '&bydate=' + dateTo);

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
                        if (msgArr !== undefined) {
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
                        if (msgArr !== undefined) {
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
                        if (msgArr !== undefined) {
                            alert(msgArr.error);
                        }
                        ajaxPreloaderOff();
                    }
                });
                break;
            default:
                alert("Нет таких значений");
        }

        return false;
    });

    // if ($('.type-order option:selected').val()!=''){
    // 	$('.get-order-form').submit();
    // }

    //переключение типа получения счетов
    function subscribeType() {
        if ($('.subscribe-form input[type=radio]:checked').val() == 1) {
            $('.subscribe-group').css('display', 'block');
        } else {
            $('.subscribe-group').css('display', 'none');
        }
    }

    subscribeType();
    $('.subscribe-form input[type=radio]').on('change', function () {
        subscribeType();
    });


    // выбор контракта для ЭДО
    $('.link-popup-contract-edo').on('click', function () {
        $('.contracts-edo-popup').animate({'top': $(window).scrollTop() + 50}, 450);
        $('.contracts-devices-popup-overlay').fadeIn(250);
        return false;
    });

    $('.custom-popup .close').on('click', function () {
        $('.custom-popup').animate({'top': '-3000px'}, 450);
        $('.contracts-devices-popup-overlay').fadeOut(250);
        $('.loading-report-popup.open').removeClass('open');
        return false;
    });
    //переключение таба
    var url = window.location.href;
    var arrAncor = url.split('#');
    if ($('a.tab-btn').length && typeof arrAncor[1] != "undefined") {
        $('a.tab-btn').each(function () {
            if ($(this).attr('href') == '#' + arrAncor[1]) {
                $(this).trigger('click');
            }
        });
    }

    //подтягивание контракта с сайдбара
    $('.name-sidebar').text($('.sidebar-menu-fw a.active').attr('data-name'));

    //получение всех счетов
    $('.aj-all-invoice').on('click', function () {
        ajaxPreloaderOn();
        $(this).css('display', 'none');
        $.ajax({
            type: "GET",
            url: "/main/all-arrear",
            data: 'uid=' + $(this).attr('data-uid'),
            success: function (mess) {
                $('.wrap-invoice').html(mess);
                $('.arrear-lists.white-box .white-box-title').text('Все выставленные счета');
                ajaxPreloaderOff();
            }
        });
        return false;
    });

    /*contracts popup*/
    $('.contracts-devices .devices-link').on('click', function () {
        if ($(window).scrollTop() > $('.bg').scrollTop()) {
            var topPos = $(window).scrollTop() + 50;
        } else {
            var topPos = $('.bg').scrollTop() + 50;
        }
        $('.contracts-devices-popup .table').html($(this).next('div').html());
        $('.contracts-devices-popup').animate({'top': topPos}, 450);
        $('.contracts-devices-popup-overlay').fadeIn(250);
        return false;
    });

    $('.contracts-devices-popup .close').on('click', function () {
        $('.contracts-devices-popup').animate({'top': '-3000px'}, 450);
        $('.contracts-devices-popup-overlay').fadeOut(250);
        return false;
    });

    /**
     Main Carousel
     **/
    var main_carousel = new Swiper('.main-carousel .swiper-container', {
        slidesPerView: 1,
        spaceBetween: 10,
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
    $('.header').on('click', '.menu-btn', function () {
        if ($(this).closest('.header').hasClass('opened')) {
            $(this).closest('.header').removeClass('opened');
            $(this).removeClass('active');
        } else {
            $(this).closest('.header').addClass('opened');
            $(this).addClass('active');
        }
        return false;
    });

    /**
     Menu Dropdown
     **/
    if (width < 1200) {
        $('.menu-popup').on('click', '.dropdown-btn', function () {
            if ($(this).closest('li').hasClass('active')) {
                $(this).closest('li').find('> ul').slideUp();
                $(this).closest('li').removeClass('active');
            } else {
                $(this).closest('li').find('> ul').slideDown();
                $(this).closest('li').addClass('active');
            }
            return false;
        });
        $('.top-menu').on('click', '.children > a', function () {
            if ($(this).closest('li').hasClass('active')) {
                $(this).closest('li').find('> ul').slideUp();
                $(this).closest('li').removeClass('active');
            } else {
                $(this).closest('li').find('> ul').slideDown();
                $(this).closest('li').addClass('active');
            }
            return false;
        });
    }

    /**
     Summary Tabs
     **/
    $('.summary-item').on('click', '.show-btn', function () {
        if ($(this).closest('.summary-item').hasClass('active')) {
            $(this).closest('.summary-item').find('.info-bottom').slideUp();
            $(this).closest('.summary-item').removeClass('active');
            $(this).removeClass('active');
        } else {
            $(this).closest('.summary-item').find('.info-bottom').slideDown();
            $(this).closest('.summary-item').addClass('active');
            $(this).addClass('active');
        }
        return false;
    });

    /**
     more-list
     **/
    $('.more-list').on('click', '.btn', function () {
        if ($(this).closest('.more-list').hasClass('active')) {
            $(this).closest('.more-list').find('.more-list-popup').slideUp();
            $(this).closest('.more-list').removeClass('active');
        } else {
            $(this).closest('.more-list').find('.more-list-popup').slideDown();
            $(this).closest('.more-list').addClass('active');
        }
        return false;
    });

    /**
     Contracts
     **/
    $('.contracts-item').on('click', '.more-link, .name a', function () {
        var item = $(this).closest('.contracts-item');
        var item_body = item.find('.contracts-body');
        var item_more_link = item.find('.more-link');
        var open_text = item_more_link.data('text-open');
        var close_text = item_more_link.data('text-close');

        if (item.hasClass('open')) {
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
    $('.objects-item').on('click', '.more-link, .name a', function () {
        var item = $(this).closest('.objects-item');
        var item_body = item.find('.objects-body');
        var item_more_link = item.find('.more-link');
        var open_text = item_more_link.data('text-open');
        var close_text = item_more_link.data('text-close');

        if (item.hasClass('open')) {
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
    $('.wrapper').on('click', '.label-info', function () {
        $(this).parent().find('.label-error').fadeIn();

        return false;
    });
    $('.label-error').on('click', '.close', function () {
        $(this).closest('.label-error').fadeOut();

        return false;
    });

    /**
     Anketa radio items
     **/
    $('.type-anketa-radio-items input:radio').change(function () {
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
            } else {
                $(this).blur();
            }
        }
    });


    /**
     Collapse
     **/
    $('.collapse-item').on('click', '.collapse-btn', function () {
        if ($(this).closest('.collapse-item').hasClass('active')) {
            $(this).closest('.collapse-item').find('.collapse-content').slideUp();
            $(this).closest('.collapse-item').removeClass('active');
            $(this).removeClass('active');
        } else {
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
    if ($(".rateit").length) {
        $('.rateit').rateit();
    }

    /**
     Popups
     **/
    $('.overlay, .mobile-popup .close, .popup-box .close').on('click', function () {
        $('.sidebar-menu-fw').css('overflow', 'scroll');
        $(this).closest('.popup-box').fadeOut();
        $('.overlay').fadeOut(250);
        $('.hover-mobile').removeClass('hover-mobile');
        return false;
    });

    if (width < 1200) {
        $('.sidebar-menu ul li a').on('click', function () {
            $('.sidebar-menu ul li a').removeClass('hover-mobile');
            $(this).addClass('hover-mobile');
            $('.sidebar-menu-fw').css('overflow', 'visible');
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
        submitHandler: function () {
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
        submitHandler: function () {
            window.location.href = 'anketa-3.html';
        }
    });

    /**
     Tel Validate
     **/
    if ($("input[name='tel']").length) {
        $("input[name='tel']").mask("999 999 99 99", {placeholder: " "});
    }

});

function styler_func() {
    /*styler*/
    //$('input.styler, select.styler').styler({'selectPlaceholder':'Из списка'});
    $('input.styler').styler();

    /*datepicker from-to dialog*/
    var dateFormat = "dd.mm.yy", from = $("#from_dialog").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            regional: "ru",
            minDate: new Date(2020, 1 - 1, 1),
            showButtonPanel: true,
            onClose: function (dateText, inst) {
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
        })
            .on("change", function () {
                to.datepicker("option", "minDate", getDate(this));
                to.datepicker("option", "maxDate", getDate(this, '+1y'));
            }),

        to = $("#to_dialog").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            showButtonPanel: true,
            regional: "ru",
            minDate: new Date(2020, 1 - 1, 1),
            onClose: function (dateText, inst) {
                $(this).datepicker('setDate', new Date(inst.selectedYear, parseInt(inst.selectedMonth) + 1, 0));
            }
        })
            .on("change", function () {
                from.datepicker("option", "maxDate", getDate(this));
                from.datepicker("option", "minDate", getDate(this, '-1y'));
            });

    var fromMessage = $("#messagessearch-date_from").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            regional: "ru",
            minDate: new Date(2020, 1 - 1, 1),
            showButtonPanel: true,
        })
            .on("change", function () {
                toMessage.datepicker("option", "minDate", getDate(this));
                toMessage.datepicker("option", "maxDate", getDate(this, '+1y'));
            }),

        toMessage = $("#messagessearch-date_to").datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            showButtonPanel: true,
            regional: "ru",
            minDate: new Date(2020, 1 - 1, 1)
        })
            .on("change", function () {
                fromMessage.datepicker("option", "maxDate", getDate(this));
                fromMessage.datepicker("option", "minDate", getDate(this, '-1y'));
            });

    function getDate(element, offset) {
        var date;
        try {
            date = $.datepicker.parseDate(dateFormat, element.value);
        } catch (error) {
            date = null;
        }
        if (date) {
            if (offset == '-1y') {
                date.setFullYear(date.getFullYear() - 1);
            }
            if (offset == '+1y') {
                date.setFullYear(date.getFullYear() + 1);
            }
        }
        return date;
    }
}
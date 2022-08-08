/* Preloader */
$(window).on("load", function () {

    /* Preload */
    var preload = $('.preloader');
    preload.find('.spinner').fadeOut(function () {
        preload.fadeOut(500);
    });

});

$(function () {
    var width = $(window).width();
    /**
     Form Styler
     **/
    styler_func();
    /**
     Label info
     **/
    $('.c-form').on('click', '.label-info', function(){
        $(this).parent().find('.label-error').fadeIn();

        return false;
    });
    $('.label-error').on('click', '.close', function(){
        $(this).closest('.label-error').fadeOut();

        return false;
    });
    /*tin*/
    //показать прелоадер
    function ajaxPreloaderOn(){
        $('.preloader').css({'display':'block', 'opacity':'0.5'});
        $('.preloader .spinner').css('display', 'inline-block');
    }
    //прелоадер на ссылки
    $('.ploader').on('click', function(){
        ajaxPreloaderOn();
    });
    $('.eye').on('click', function(){
        var field = $(this).siblings('input');
        if($(this).hasClass('active')) {
            field.attr('type', 'password');
        } else {
            field.attr('type', 'text');
        }
        $(this).toggleClass('active');
    });
    $('a.resend').on('click', function () {
        if (!$(this).hasClass('disable')) {
            $(this).addClass('disable').attr('title', 'прошло менее минуты, с момента запроса');
            $.ajax({
                type: 'POST',
                url: '/ajax/re-send-verification',
                success: function (msg) {
                    var msgArr = JSON.parse(msg);
                    if (msgArr.error) {
                        $('.message').addClass('red').text(msgArr.error);
                    } else {
                        $('.message').text(msgArr.success);
                    }
                    setTimeout(function () {
                        $('a.resend.disable').removeAttr('title').removeClass('disable');
                        $('.message').text('');
                    }, 60000);
                }
            });
        }
        return false;
    });

    function setMethod(i) {
        if (i == 1) {
            $('#registerForm input.phone').closest('.form-group').css('display', 'block');
            $('#registerForm input.email').closest('.form-group').css('display', 'none');
        } else {
            $('#registerForm input.phone').closest('.form-group').css('display', 'none');
            $('#registerForm input.email').closest('.form-group').css('display', 'block');
        }
    }

    $('#registerForm input[type=radio]').on('change', function () {
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
        submitHandler: function () {

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
    $('input.styler, select.styler').styler();
}
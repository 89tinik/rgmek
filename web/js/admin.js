const image_upload_handler_callback = (blobInfo, progress) => new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();
    xhr.withCredentials = false;
    xhr.open('POST', 'upload');

    xhr.upload.onprogress = (e) => {
        progress(e.loaded / e.total * 100);
    };

    xhr.onload = () => {
        if (xhr.status === 403) {
            reject({message: 'HTTP Error: ' + xhr.status, remove: true});
            return;
        }

        if (xhr.status < 200 || xhr.status >= 300) {
            reject('HTTP Error: ' + xhr.status);
            return;
        }

        const json = JSON.parse(xhr.responseText);

        if (!json || typeof json.location != 'string') {
            reject('Invalid JSON: ' + xhr.responseText);
            return;
        }

        resolve(json.location);
    };

    xhr.onerror = () => {
        reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
    };

    const formData = new FormData();
    formData.append('file', blobInfo.blob(), blobInfo.filename());

    xhr.send(formData);
});

tinymce.init({
    selector: '#page-content',
    language: 'ru',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [
        {value: 'First.Name', title: 'First Name'},
        {value: 'Email', title: 'Email'},
    ],

    // without images_upload_url set, Upload tab won't show up
    images_upload_url: 'upload',

    // override default upload handler to simulate successful upload
    images_upload_handler: image_upload_handler_callback
});


$(function () {
    $('.datepicker').datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd'
    });

$(document).on('pjax:success', function() {

  $('.datepicker').datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd'
    });

})
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


    $('body').on('click', '.action-to-1c', function () {
        ajaxPreloaderOn();
        var invoiceId = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: 'ajax/add-invoice-to-one-c',
            data: 'invoice='+invoiceId,
            success: function (msg){
            }
        });
    });
});

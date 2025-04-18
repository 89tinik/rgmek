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
    selector: '#messagethemes-content',
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

    let allFiles = [];

    $('#messages-answerfilesupload').on('change', function (e) {
        for (let i = 0; i < e.target.files.length; i++) {
            allFiles.push(e.target.files[i]);
        }

        updateFileList();

        $('#messages-answerfilesupload').val('');
    });

    $(document).on('click', '.removeAnswerFile', function () {
        let index = $(this).data('index');
        allFiles.splice(index, 1);

        updateFileList();
    });

    function updateFileList() {
        $('#answerFilesList').empty();
        let show = 0;
        for (let i = 0; i < allFiles.length; i++) {
            show = 1;
            $('#answerFilesList').append('<li><span>' + allFiles[i].name +
                '</span> <button class="removeAnswerFile" data-index="' + i + '">Х</button></li>');
        }

    }

    $('.operator .messages-form #w0').on('submit', function () {
        let dt = new DataTransfer();

        for (let i = 0; i < allFiles.length; i++) {
            dt.items.add(allFiles[i]);
        }

        document.getElementById('messages-answerfilesupload').files = dt.files;
    });


    $('#admin_public').datepicker({
        showOtherMonths: true,
        selectOtherMonths: true
    });

    $('.messages-index input[name="MessagesSearch[created]"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            "format": "DD.MM.YYYY",
            "separator": " - ",
            "applyLabel": "Применить",
            "cancelLabel": "Очистить",
            "fromLabel": "From",
            "toLabel": "To",
            "customRangeLabel": "Custom",
            "weekLabel": "W",
            "daysOfWeek": [
                "Вс",
                "Пн",
                "Вт",
                "Ср",
                "Чт",
                "Пт",
                "Сб"
            ],
            "monthNames": [
                "Январь",
                "Февраль",
                "Март",
                "Апрель",
                "Май",
                "Июнь",
                "Июль",
                "Август",
                "Сентябрь",
                "Октябрь",
                "Ноябрь",
                "Декабрь"
            ],
            "firstDay": 1
        }
    });

    $('.messages-index input[name="MessagesSearch[created]"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD.MM.YYYY') + ' - ' + picker.endDate.format('DD.MM.YYYY'));
        $(this).change();
    });

    $('.messages-index input[name="MessagesSearch[created]"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $(this).change();
    });


});
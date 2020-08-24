/*!
 * FileInput Georgian Translations
 *
 * This file must be loaded after 'fileinput.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-fileinput
 * @author Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
(function ($) {
    "use strict";

    $.fn.fileinputLocales['ka'] = {
        fileSingle: 'ფაილი',
        filePlural: 'ფაილები',
        browseLabel: 'არჩევა &hellip;',
        removeLabel: 'წაშლა',
        removeTitle: 'არჩეული ფაილების წაშლა',
        cancelLabel: 'გაუქმება',
        cancelTitle: 'მიმდინარე ატვირთვის გაუქმება',
        pauseLabel: 'Pause',
        pauseTitle: 'Pause ongoing upload',
        uploadLabel: 'ატვირთვა',
        uploadTitle: 'არჩეული ფაილების ატვირთვა',
        msgNo: 'არა',
        msgNoFilesSelected: 'ფაილები არ არის არჩეული',
        msgPaused: 'Paused',
        msgCancelled: 'გაუქმებულია',
        msgPlaceholder: 'აირჩიეთ {files} ...',
        msgZoomModalHeading: 'დეტალურად ნახვა',
        msgFileRequired: 'ატვირთვისთვის აუცილებელია ფაილის არჩევა.',
        msgSizeTooSmall: 'ფაილი "{name}" (<b>{size} KB</b>) არის ძალიან პატარა. მისი ზომა უნდა იყოს არანაკლებ <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'ფაილი "{name}" (<b>{size} KB</b>) აჭარბებს მაქსიმალურ დასაშვებ ზომას <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'უნდა აირჩიოთ მინიმუმ <b>{n}</b> {file} ატვირთვისთვის.',
        msgFilesTooMany: 'არჩეული ფაილების რაოდენობა <b>({n})</b> აჭარბებს დასაშვებ ლიმიტს <b>{m}</b>.',
        msgTotalFilesTooMany: 'You can upload a maximum of <b>{m}</b> files (<b>{n}</b> files detected).',
        msgFileNotFound: 'ფაილი "{name}" არ მოიძებნა!',
        msgFileSecured: 'უსაფრთხოებით გამოწვეული შეზღუდვები კრძალავს ფაილის "{name}" წაკითხვას.',
        msgFileNotReadable: 'ფაილის "{name}" წაკითხვა შეუძლებელია.',
        msgFilePreviewAborted: 'პრევიუ გაუქმებულია ფაილისათვის "{name}".',
        msgFilePreviewError: 'დაფიქსირდა შეცდომა ფაილის "{name}" კითხვისას.',
        msgInvalidFileName: 'ნაპოვნია დაუშვებელი სიმბოლოები ფაილის "{name}" სახელში.',
        msgInvalidFileType: 'ფაილს "{name}" გააჩნია დაუშვებელი ტიპი. მხოლოდ "{types}" ტიპის ფაილები არის დაშვებული.',
        msgInvalidFileExtension: 'ფაილს "{name}" გააჩნია დაუშვებელი გაფართოება. მხოლოდ "{extensions}" გაფართოების ფაილები არის დაშვებული.',
        msgFileTypes: {
            'image': 'image',
            'html': 'HTML',
            'text': 'text',
            'video': 'video',
            'audio': 'audio',
            'flash': 'flash',
            'pdf': 'PDF',
            'object': 'object'
        },
        msgUploadAborted: 'ფაილის ატვირთვა შეწყდა',
        msgUploadThreshold: 'მუშავდება &hellip;',
        msgUploadBegin: 'ინიციალიზაცია &hellip;',
        msgUploadEnd: 'დასრულებულია',
        msgUploadResume: 'Resuming upload &hellip;',
        msgUploadEmpty: 'ატვირთვისთვის დაუშვებელი მონაცემები.',
        msgUploadError: 'Upload Error',
        msgDeleteError: 'Delete Error',
        msgProgressError: 'ატვირთვის შეცდომა',
        msgValidationError: 'ვალიდაციის შეცდომა',
        msgLoading: 'ატვირთვა {index} / {files} &hellip;',
        msgProgress: 'ფაილის ატვირთვა დასრულებულია {index} / {files} - {name} - {percent}%.',
        msgSelected: 'არჩეულია {n} {file}',
        msgFoldersNotAllowed: 'დაშვებულია მხოლოდ ფაილების გადმოთრევა! გამოტოვებულია {n} გადმოთრეული ფოლდერი.',
        msgImageWidthSmall: 'სურათის "{name}" სიგანე უნდა იყოს არანაკლებ {size} px.',
        msgImageHeightSmall: 'სურათის "{name}" სიმაღლე უნდა იყოს არანაკლებ {size} px.',
        msgImageWidthLarge: 'სურათის "{name}" სიგანე არ უნდა აღემატებოდეს {size} px-ს.',
        msgImageHeightLarge: 'სურათის "{name}" სიმაღლე არ უნდა აღემატებოდეს {size} px-ს.',
        msgImageResizeError: 'ვერ მოხერხდა სურათის ზომის შეცვლისთვის საჭირო მონაცემების გარკვევა.',
        msgImageResizeException: 'შეცდომა სურათის ზომის შეცვლისას.<pre>{errors}</pre>',
        msgAjaxError: 'დაფიქსირდა შეცდომა ოპერაციის {operation} შესრულებისას. ცადეთ მოგვიანებით!',
        msgAjaxProgressError: 'ვერ მოხერხდა ოპერაციის {operation} შესრულება',
        msgDuplicateFile: 'File "{name}" of same size "{size} KB" has already been selected earlier. Skipping duplicate selection.',
        msgResumableUploadRetriesExceeded:  'Upload aborted beyond <b>{max}</b> retries for file <b>{file}</b>! Error Details: <pre>{error}</pre>',
        msgPendingTime: '{time} remaining',
        msgCalculatingTime: 'calculating time remaining',
        ajaxOperations: {
            deleteThumb: 'ფაილის წაშლა',
            uploadThumb: 'ფაილის ატვირთვა',
            uploadBatch: 'ფაილების ატვირთვა',
            uploadExtra: 'მონაცემების გაგზავნა ფორმიდან'
        },
        dropZoneTitle: 'გადმოათრიეთ ფაილები აქ &hellip;',
        dropZoneClickTitle: '<br>(ან დააჭირეთ რათა აირჩიოთ {files})',
        fileActionSettings: {
            removeTitle: 'ფაილის წაშლა',
            uploadTitle: 'ფაილის ატვირთვა',
            uploadRetryTitle: 'ატვირთვის გამეორება',
            downloadTitle: 'ფაილის ჩამოტვირთვა',
            zoomTitle: 'დეტალურად ნახვა',
            dragTitle: 'გადაადგილება / მიმდევრობის შეცვლა',
            indicatorNewTitle: 'ჯერ არ ატვირთულა',
            indicatorSuccessTitle: 'ატვირთულია',
            indicatorErrorTitle: 'ატვირთვის შეცდომა',
            indicatorPausedTitle: 'Upload Paused',
            indicatorLoadingTitle:  'ატვირთვა &hellip;'
        },
        previewZoomButtonTitles: {
            prev: 'წინა ფაილის ნახვა',
            next: 'შემდეგი ფაილის ნახვა',
            toggleheader: 'სათაურის დამალვა',
            fullscreen: 'მთელ ეკრანზე გაშლა',
            borderless: 'მთელ გვერდზე გაშლა',
            close: 'დახურვა'
        }
    };
})(window.jQuery);

/*!
 * FileInput Bulgarian Translations
 *
 * This file must be loaded after 'fileinput.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-fileinput
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
(function ($) {
    "use strict";

    $.fn.fileinputLocales['bg'] = {
        fileSingle: 'файл',
        filePlural: 'файла',
        browseLabel: 'Избери &hellip;',
        removeLabel: 'Премахни',
        removeTitle: 'Изчисти избраните',
        cancelLabel: 'Откажи',
        cancelTitle: 'Откажи качването',
        uploadLabel: 'Качи',
        uploadTitle: 'Качи избраните файлове',
        msgNo: 'Не',
        msgNoFilesSelected: '',
        msgCancelled: 'Отменен',
        msgZoomModalHeading: 'Детайлен преглед',
        msgSizeTooSmall: 'File "{name}" (<b>{size} KB</b>) is too small and must be larger than <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Файла "{name}" (<b>{size} KB</b>) надвишава максималните разрешени <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Трябва да изберете поне <b>{n}</b> {files} файла.',
        msgFilesTooMany: 'Броя файлове избрани за качване <b>({n})</b> надвишава ограниченито от максимум <b>{m}</b>.',
        msgFileNotFound: 'Файлът "{name}" не може да бъде намерен!',
        msgFileSecured: 'От съображения за сигурност не може да прочетем файла "{name}".',
        msgFileNotReadable: 'Файлът "{name}" не е четим.',
        msgFilePreviewAborted: 'Прегледа на файла е прекратен за "{name}".',
        msgFilePreviewError: 'Грешка при опит за четене на файла "{name}".',
        msgInvalidFileName: 'Invalid or unsupported characters in file name "{name}".',
        msgInvalidFileType: 'Невалиден тип на файла "{name}". Разрешени са само "{types}".',
        msgInvalidFileExtension: 'Невалидно разрешение на "{name}". Разрешени са само "{extensions}".',
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
        msgUploadAborted: 'Качите файла, бе прекратена',
        msgUploadThreshold: 'Processing...',
        msgUploadEmpty: 'No valid data available for upload.',
        msgValidationError: 'утвърждаване грешка',
        msgLoading: 'Зареждане на файл {index} от общо {files} &hellip;',
        msgProgress: 'Зареждане на файл {index} от общо {files} - {name} - {percent}% завършени.',
        msgSelected: '{n} {files} избрани',
        msgFoldersNotAllowed: 'Само пуснати файлове! Пропуснати {n} пуснати папки.',
        msgImageWidthSmall: 'Широчината на изображението "{name}" трябва да е поне {size} px.',
        msgImageHeightSmall: 'Височината на изображението "{name}" трябва да е поне {size} px.',
        msgImageWidthLarge: 'Широчината на изображението "{name}" не може да е по-голяма от {size} px.',
        msgImageHeightLarge: 'Височината на изображението "{name}" нее може да е по-голяма от {size} px.',
        msgImageResizeError: 'Не може да размерите на изображението, за да промените размера.',
        msgImageResizeException: 'Грешка при промяна на размера на изображението.<pre>{errors}</pre>',
        msgAjaxError: 'Something went wrong with the {operation} operation. Please try again later!',
        msgAjaxProgressError: '{operation} failed',
        ajaxOperations: {
            deleteThumb: 'file delete',
            uploadThumb: 'single file upload',
            uploadBatch: 'batch file upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'Пуснете файловете тук &hellip;',
        dropZoneClickTitle: '<br>(or click to select {files})',
        fileActionSettings: {
            removeTitle: 'Махни файл',
            uploadTitle: 'Качване на файл',
            zoomTitle: 'Вижте детайли',
            dragTitle: 'Move / Rearrange',
            indicatorNewTitle: 'Все още не е качил',
            indicatorSuccessTitle: 'Качено',
            indicatorErrorTitle: 'Качи Error',
            indicatorLoadingTitle: 'Качва се ...'
        },
        previewZoomButtonTitles: {
            prev: 'View previous file',
            next: 'View next file',
            toggleheader: 'Toggle header',
            fullscreen: 'Toggle full screen',
            borderless: 'Toggle borderless mode',
            close: 'Close detailed preview'
        }
    };
})(window.jQuery);

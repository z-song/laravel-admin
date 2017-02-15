/*!
 * FileInput Russian Translations
 *
 * This file must be loaded after 'fileinput.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-fileinput
 * @author CyanoFresh <cyanofresh@gmail.com>
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
(function ($) {
    "use strict";

    $.fn.fileinputLocales['ru'] = {
        fileSingle: 'файл',
        filePlural: 'файлы',
        browseLabel: 'Выбрать &hellip;',
        removeLabel: 'Удалить',
        removeTitle: 'Очистить выбранные файлы',
        cancelLabel: 'Отмена',
        cancelTitle: 'Отменить текущую загрузку',
        uploadLabel: 'Загрузить',
        uploadTitle: 'Загрузить выбранные файлы',
        msgNo: 'нет',
        msgNoFilesSelected: '',
        msgCancelled: 'Отменено',
        msgZoomModalHeading: 'Подробное превью',
        msgSizeTooSmall: 'File "{name}" (<b>{size} KB</b>) is too small and must be larger than <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Файл "{name}" (<b>{size} KB</b>) превышает максимальный размер <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Вы должны выбрать как минимум <b>{n}</b> {files} для загрузки.',
        msgFilesTooMany: 'Количество выбранных файлов <b>({n})</b> превышает максимально допустимое количество <b>{m}</b>.',
        msgFileNotFound: 'Файл "{name}" не найден!',
        msgFileSecured: 'Ограничения безопасности запрещают читать файл "{name}".',
        msgFileNotReadable: 'Файл "{name}" невозможно прочитать.',
        msgFilePreviewAborted: 'Предпросмотр отменен для файла "{name}".',
        msgFilePreviewError: 'Произошла ошибка при чтении файла "{name}".',
        msgInvalidFileName: 'Invalid or unsupported characters in file name "{name}".',
        msgInvalidFileType: 'Запрещенный тип файла для "{name}". Только "{types}" разрешены.',
        msgInvalidFileExtension: 'Запрещенное расширение для файла "{name}". Только "{extensions}" разрешены.',
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
        msgUploadAborted: 'Выгрузка файла прервана',
        msgUploadThreshold: 'Обработка...',
        msgValidationError: 'Ошибка проверки',
        msgLoading: 'Загрузка файла {index} из {files} &hellip;',
        msgProgress: 'Загрузка файла {index} из {files} - {name} - {percent}% завершено.',
        msgSelected: 'Выбрано файлов: {n}',
        msgFoldersNotAllowed: 'Разрешено перетаскивание только файлов! Пропущено {n} папок.',
        msgImageWidthSmall: 'Ширина изображения {name} должна быть не меньше {size} px.',
        msgImageHeightSmall: 'Высота изображения {name} должна быть не меньше {size} px.',
        msgImageWidthLarge: 'Ширина изображения "{name}" не может превышать {size} px.',
        msgImageHeightLarge: 'Высота изображения "{name}" не может превышать {size} px.',
        msgImageResizeError: 'Не удалось получить размеры изображения, чтобы изменить размер.',
        msgImageResizeException: 'Ошибка при изменении размера изображения.<pre>{errors}</pre>',
        msgAjaxError: 'Something went wrong with the {operation} operation. Please try again later!',
        msgAjaxProgressError: '{operation} failed',
        ajaxOperations: {
            deleteThumb: 'file delete',
            uploadThumb: 'single file upload',
            uploadBatch: 'batch file upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'Перетащите файлы сюда &hellip;',
        dropZoneClickTitle: '<br>(Или щёлкните, чтобы выбрать {files})',
        fileActionSettings: {
            removeTitle: 'Удалить файл',
            uploadTitle: 'Загрузить файл',
            zoomTitle: 'посмотреть детали',
            dragTitle: 'Move / Rearrange',
            indicatorNewTitle: 'Еще не загружен',
            indicatorSuccessTitle: 'Загружен',
            indicatorErrorTitle: 'Ошибка загрузки',
            indicatorLoadingTitle: 'Загрузка ...'
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

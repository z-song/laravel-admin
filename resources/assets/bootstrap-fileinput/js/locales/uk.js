/*!
 * FileInput Ukrainian Translations
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

    $.fn.fileinputLocales['uk'] = {
        fileSingle: 'файл',
        filePlural: 'файли',
        browseLabel: 'Вибрати &hellip;',
        removeLabel: 'Видалити',
        removeTitle: 'Видалити вибрані файли',
        cancelLabel: 'Скасувати',
        cancelTitle: 'Скасувати поточне відвантаження',
        pauseLabel: 'Pause',
        pauseTitle: 'Pause ongoing upload',
        uploadLabel: 'Відвантажити',
        uploadTitle: 'Відвантажити обрані файли',
        msgNo: 'Немає',
        msgNoFilesSelected: '',
        msgPaused: 'Paused',
        msgCancelled: 'Cкасовано',
        msgPlaceholder: 'Оберіть {files} ...',
        msgZoomModalHeading: 'Детальний превью',
        msgFileRequired: 'Ви повинні обрати файл для завантаження.',
        msgSizeTooSmall: 'Файл "{name}" (<b>{size} KB</b>) занадто малий і повинен бути більший, ніж <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Файл "{name}" (<b>{size} KB</b>) перевищує максимальний розмір <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Ви повинні обрати як мінімум <b>{n}</b> {files} для відвантаження.',
        msgFilesTooMany: 'Кількість обраних файлів <b>({n})</b> перевищує максимально допустиму кількість <b>{m}</b>.',
        msgTotalFilesTooMany: 'You can upload a maximum of <b>{m}</b> files (<b>{n}</b> files detected).',
        msgFileNotFound: 'Файл "{name}" не знайдено!',
        msgFileSecured: 'Обмеження безпеки перешкоджають читанню файла "{name}".',
        msgFileNotReadable: 'Файл "{name}" неможливо прочитати.',
        msgFilePreviewAborted: 'Перегляд скасований для файла "{name}".',
        msgFilePreviewError: 'Сталася помилка під час читання файла "{name}".',
        msgInvalidFileName: 'Недійсні чи непідтримувані символи в імені файлу "{name}".',
        msgInvalidFileType: 'Заборонений тип файла для "{name}". Тільки "{types}" дозволені.',
        msgInvalidFileExtension: 'Заборонене розширення для файла "{name}". Тільки "{extensions}" дозволені.',
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
        msgUploadAborted: 'Вивантаження файлу перервана',
        msgUploadThreshold: 'Обробка &hellip;',
        msgUploadBegin: 'Ініціалізація &hellip;',
        msgUploadEnd: 'Готово',
        msgUploadResume: 'Resuming upload &hellip;',
        msgUploadEmpty: 'Немає доступних даних для відвантаження.',
        msgUploadError: 'Upload Error',
        msgDeleteError: 'Delete Error',
        msgProgressError: 'Помилка',
        msgValidationError: 'Помилка перевірки',
        msgLoading: 'Відвантаження файла {index} із {files} &hellip;',
        msgProgress: 'Відвантаження файла {index} із {files} - {name} - {percent}% завершено.',
        msgSelected: '{n} {files} обрано',
        msgFoldersNotAllowed: 'Дозволено перетягувати тільки файли! Пропущено {n} папок.',
        msgImageWidthSmall: 'Ширина зображення "{name}" повинна бути не менше {size} px.',
        msgImageHeightSmall: 'Висота зображення "{name}" повинна бути не менше {size} px.',
        msgImageWidthLarge: 'Ширина зображення "{name}" не може перевищувати {size} px.',
        msgImageHeightLarge: 'Висота зображення "{name}" не може перевищувати {size} px.',
        msgImageResizeError: 'Не вдалося розміри зображення, щоб змінити розмір.',
        msgImageResizeException: 'Помилка при зміні розміру зображення.<pre>{errors}</pre>',
        msgAjaxError: 'Щось не так із операцією {operation}. Будь ласка, спробуйте пізніше!',
        msgAjaxProgressError: 'помилка {operation}',
        msgDuplicateFile: 'File "{name}" of same size "{size} KB" has already been selected earlier. Skipping duplicate selection.',
        msgResumableUploadRetriesExceeded:  'Upload aborted beyond <b>{max}</b> retries for file <b>{file}</b>! Error Details: <pre>{error}</pre>',
        msgPendingTime: '{time} remaining',
        msgCalculatingTime: 'calculating time remaining',
        ajaxOperations: {
            deleteThumb: 'видалити файл',
            uploadThumb: 'відвантажити файл',
            uploadBatch: 'batch file upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'Перетягніть файли сюди &hellip;',
        dropZoneClickTitle: '<br>(або клацність та оберіть {files})',
        fileActionSettings: {
            removeTitle: 'Видалити файл',
            uploadTitle: 'Відвантажити файл',
            uploadRetryTitle: 'Повторити відвантаження',
            downloadTitle: 'Завантажити файл',
            zoomTitle: 'Подивитися деталі',
            dragTitle: 'Перенести / Переставити',
            indicatorNewTitle: 'Ще не відвантажено',
            indicatorSuccessTitle: 'Відвантажено',
            indicatorErrorTitle: 'Помилка при відвантаженні',
            indicatorPausedTitle: 'Upload Paused',
            indicatorLoadingTitle:  'Завантаження &hellip;'
        },
        previewZoomButtonTitles: {
            prev: 'Переглянути попередній файл',
            next: 'Переглянути наступний файл',
            toggleheader: 'Перемкнути заголовок',
            fullscreen: 'Перемкнути повноекранний режим',
            borderless: 'Перемкнути режим без полів',
            close: 'Закрити детальний перегляд'
        }
    };
})(window.jQuery);

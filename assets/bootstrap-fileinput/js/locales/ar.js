/*!
 * FileInput Arabic Translations
 *
 * This file must be loaded after 'fileinput.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-fileinput
 * @author Yasser Lotfy <y_l@live.com>
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
(function ($) {
    "use strict";

    $.fn.fileinputLocales['ar'] = {
        fileSingle: 'ملف',
        filePlural: 'ملفات',
        browseLabel: 'تصفح &hellip;',
        removeLabel: 'إزالة',
        removeTitle: 'إزالة الملفات المختارة',
        cancelLabel: 'إلغاء',
        cancelTitle: 'إنهاء الرفع الحالي',
        uploadLabel: 'رفع',
        uploadTitle: 'رفع الملفات المختارة',
        msgNo: 'لا',
        msgNoFilesSelected: '',
        msgCancelled: 'ألغيت',
        msgZoomModalHeading: 'معاينة تفصيلية',
        msgSizeTooSmall: 'File "{name}" (<b>{size} KB</b>) is too small and must be larger than <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'الملف "{name}" (<b>{size} ك.ب</b>) تعدى الحد الأقصى المسموح للرفع <b>{maxSize} ك.ب</b>.',
        msgFilesTooLess: 'يجب عليك اختيار <b>{n}</b> {files} على الأقل للرفع.',
        msgFilesTooMany: 'عدد الملفات المختارة للرفع <b>({n})</b> تعدت الحد الأقصى المسموح به لعدد <b>{m}</b>.',
        msgFileNotFound: 'الملف "{name}" غير موجود!',
        msgFileSecured: 'قيود أمنية تمنع قراءة الملف "{name}".',
        msgFileNotReadable: 'الملف "{name}" غير قابل للقراءة.',
        msgFilePreviewAborted: 'تم إلغاء معاينة الملف "{name}".',
        msgFilePreviewError: 'حدث خطأ أثناء قراءة الملف "{name}".',
        msgInvalidFileName: 'Invalid or unsupported characters in file name "{name}".',
        msgInvalidFileType: 'نوعية غير صالحة للملف "{name}". فقط هذه النوعيات مدعومة "{types}".',
        msgInvalidFileExtension: 'امتداد غير صالح للملف "{name}". فقط هذه الملفات مدعومة "{extensions}".',
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
        msgUploadAborted: 'تم إلغاء رفع الملف',
        msgUploadThreshold: 'Processing...',
        msgUploadEmpty: 'No valid data available for upload.',
        msgValidationError: 'خطأ التحقق من صحة',
        msgLoading: 'تحميل ملف {index} من {files} &hellip;',
        msgProgress: 'تحميل ملف {index} من {files} - {name} - {percent}% منتهي.',
        msgSelected: '{n} {files} مختار(ة)',
        msgFoldersNotAllowed: 'اسحب وأفلت الملفات فقط! تم تخطي {n} مجلد(ات).',
        msgImageWidthSmall: 'عرض ملف الصورة "{name}" يجب أن يكون على الأقل {size} px.',
        msgImageHeightSmall: 'طول ملف الصورة "{name}" يجب أن يكون على الأقل {size} px.',
        msgImageWidthLarge: 'عرض ملف الصورة "{name}" لا يمكن أن يتعدى {size} px.',
        msgImageHeightLarge: 'طول ملف الصورة "{name}" لا يمكن أن يتعدى {size} px.',
        msgImageResizeError: 'لم يتمكن من معرفة أبعاد الصورة لتغييرها.',
        msgImageResizeException: 'حدث خطأ أثناء تغيير أبعاد الصورة.<pre>{errors}</pre>',
        msgAjaxError: 'Something went wrong with the {operation} operation. Please try again later!',
        msgAjaxProgressError: '{operation} failed',
        ajaxOperations: {
            deleteThumb: 'file delete',
            uploadThumb: 'single file upload',
            uploadBatch: 'batch file upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'اسحب وأفلت الملفات هنا &hellip;',
        dropZoneClickTitle: '<br>(or click to select {files})',
        fileActionSettings: {
            removeTitle: 'إزالة الملف',
            uploadTitle: 'رفع الملف',
            zoomTitle: 'مشاهدة التفاصيل',
            dragTitle: 'Move / Rearrange',
            indicatorNewTitle: 'لم يتم الرفع بعد',
            indicatorSuccessTitle: 'تم الرفع',
            indicatorErrorTitle: 'خطأ بالرفع',
            indicatorLoadingTitle: 'جارٍ الرفع ...'
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

/*!
 * FileInput Persian Translations
 *
 * This file must be loaded after 'fileinput.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-fileinput
 * @author Milad Nekofar <milad@nekofar.com>
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
(function ($) {
    "use strict";

    $.fn.fileinputLocales['fa'] = {
        fileSingle: 'فایل',
        filePlural: 'فایل',
        browseLabel: 'مرور &hellip;',
        removeLabel: 'حذف',
        removeTitle: 'پاکسازی فایل‌های انتخاب شده',
        cancelLabel: 'لغو',
        cancelTitle: 'لغو بارگزاری جاری',
        uploadLabel: 'بارگذاری',
        uploadTitle: 'بارگذاری فایل‌های انتخاب شده',
        msgNo: 'خیر',
        msgCancelled: 'لغو',
        msgZoomTitle: 'دیدن جزئیات',
        msgZoomModalHeading: 'پیشنمایش مفصل',
        msgSizeTooLarge: 'فایل "{name}" (<b>{size} کیلوبایت</b>) از حداکثر مجاز <b>{maxSize} کیلوبایت</b>.',
        msgFilesTooLess: 'شما باید حداقل <b>{n}</b> {files} فایل برای بارگذاری انتخاب کنید.',
        msgFilesTooMany: 'تعداد فایل‌های انتخاب شده برای بارگذاری <b>({n})</b> از حداکثر مجاز عبور کرده است <b>{m}</b>.',
        msgFileNotFound: 'فایل "{name}" یافت نشد!',
        msgFileSecured: 'محدودیت های امنیتی مانع خواندن فایل "{name}" است.',
        msgFileNotReadable: 'فایل "{name}" قابل نوشتن نیست.',
        msgFilePreviewAborted: 'پیشنمایش فایل "{name}". شکست خورد',
        msgFilePreviewError: 'در هنگام خواندن فایل "{name}" خطایی رخ داد.',
        msgInvalidFileType: 'نوع فایل "{name}" معتبر نیست. فقط "{types}" پشیبانی می‌شود.',
        msgInvalidFileExtension: 'پسوند فایل "{name}" معتبر نیست. فقط "{extensions}" پشتیبانی می‌شود.',
        msgUploadAborted: 'The file upload was aborted',
        msgValidationError: 'خطای اعتبار سنجی',
        msgLoading: 'بارگیری فایل {index} از {files} &hellip;',
        msgProgress: 'بارگیری فایل {index} از {files} - {name} - {percent}% تمام شد.',
        msgSelected: '{n} {files} انتخاب شده',
        msgFoldersNotAllowed: 'فقط فایل‌ها را بکشید و رها کنید! {n} پوشه نادیده گرفته شد.',
        msgImageWidthSmall: 'عرض فایل تصویر "{name}" باید حداقل {size} پیکسل باشد.',
        msgImageHeightSmall: 'ارتفاع فایل تصویر "{name}" باید حداقل {size} پیکسل باشد.',
        msgImageWidthLarge: 'عرض فایل تصویر "{name}" نمیتواند از {size} پیکسل بیشتر باشد.',
        msgImageHeightLarge: 'ارتفاع فایل تصویر "{name}" نمی‌تواند از {size} پیکسل بیشتر باشد.',
        msgImageResizeError: 'یافت نشد ابعاد تصویر را برای تغییر اندازه.',
        msgImageResizeException: 'خطا در هنگام تغییر اندازه تصویر.<pre>{errors}</pre>',
        dropZoneTitle: 'فایل‌ها را بکشید و در اینجا رها کنید &hellip;',
        fileActionSettings: {
            removeTitle: 'حذف فایل',
            uploadTitle: 'آپلود فایل',
            indicatorNewTitle: 'آپلود نشده است',
            indicatorSuccessTitle: 'آپلود شده',
            indicatorErrorTitle: 'بارگذاری خطا',
            indicatorLoadingTitle: 'آپلود ...'
        }
    };
})(window.jQuery);

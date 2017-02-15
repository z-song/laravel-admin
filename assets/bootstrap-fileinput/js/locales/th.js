/*!
 * FileInput Thai Translations
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

    $.fn.fileinputLocales['th'] = {
        fileSingle: 'ไฟล์',
        filePlural: 'ไฟล์',
        browseLabel: 'เลือกดู &hellip;',
        removeLabel: 'ลบทิ้ง',
        removeTitle: 'ลบไฟล์ที่เลือกทิ้ง',
        cancelLabel: 'ยกเลิก',
        cancelTitle: 'ยกเลิกการอัพโหลด',
        uploadLabel: 'อัพโหลด',
        uploadTitle: 'อัพโหลดไฟล์ที่เลือก',
        msgNo: 'ไม่',
        msgNoFilesSelected: '',
        msgCancelled: 'ยกเลิก',
        msgZoomModalHeading: 'ตัวอย่างละเอียด',
        msgSizeTooSmall: 'File "{name}" (<b>{size} KB</b>) is too small and must be larger than <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'ไฟล์ "{name}" (<b>{size} KB</b>) มีขนาดเกินที่ระบบอนุญาตที่ <b>{maxSize} KB</b>, กรุณาลองใหม่อีกครั้ง!',
        msgFilesTooLess: 'คุณต้องเลือกไฟล์จำนวนอย่างน้อย <b>{n}</b> {files} เพื่ออัพโหลด, กรุณาลองใหม่อีกครั้ง!',
        msgFilesTooMany: 'ไฟล์ที่คุณเลือกมีจำนวน <b>({n})</b> ซึ่งเกินกว่าที่ระบบอนุญาตที่ <b>{m}</b>, กรุณาลองใหม่อีกครั้ง!',
        msgFileNotFound: 'ไม่พบไฟล์ "{name}" !',
        msgFileSecured: 'ระบบความปลอดภัยไม่อนุญาตให้อ่านไฟล์ "{name}".',
        msgFileNotReadable: 'ไม่สามารถอ่านไฟล์ "{name}" ได้',
        msgFilePreviewAborted: 'ไฟล์ "{name}" ไม่อนุญาตให้ดูตัวอย่าง',
        msgFilePreviewError: 'พบปัญหาในการดูตัวอย่างไฟล์ "{name}".',
        msgInvalidFileName: 'Invalid or unsupported characters in file name "{name}".',
        msgInvalidFileType: 'ไฟล์ "{name}" เป็นประเภทไฟล์ที่ไม่ถูกต้อง, อนุญาตเฉพาะไฟล์ประเภท "{types}"',
        msgInvalidFileExtension: 'ไฟล์ "{name}" เป็น extension ที่ไมถูกต้อง, อนุญาตเฉพาะไฟล์ extension "{extensions}"',
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
        msgUploadAborted: 'อัปโหลดไฟล์ถูกยกเลิก',
        msgUploadThreshold: 'Processing...',
        msgUploadEmpty: 'No valid data available for upload.',
        msgValidationError: 'ข้อผิดพลาดในการตรวจสอบ',
        msgLoading: 'กำลังโหลดไฟล์ {index} จาก {files} &hellip;',
        msgProgress: 'กำลังโหลดไฟล์ {index} จาก {files} - {name} - {percent}%',
        msgSelected: '{n} {files} ถูกเลือก',
        msgFoldersNotAllowed: 'Drag & drop เฉพาะไฟล์เท่านั้น! ข้าม dropped folder จำนวน {n}',
        msgImageWidthSmall: 'ความกว้างของภาพไฟล์ "{name}" ต้องมีอย่างน้อย {size} px.',
        msgImageHeightSmall: 'ความสูงของภาพไฟล์ "{name}" ต้องมีอย่างน้อย {size} px.',
        msgImageWidthLarge: 'ความกว้างของภาพไฟล์ "{name}" ไม่เกิน {size} พิกเซล.',
        msgImageHeightLarge: 'ความสูงของไฟล์ภาพ "{name}" ไม่เกิน {size} พิกเซล.',
        msgImageResizeError: 'ไม่สามารถรับขนาดภาพเพื่อปรับขนาด',
        msgImageResizeException: 'ข้อผิดพลาดขณะปรับขนาดภาพ<pre>{errors}</pre>',
        msgAjaxError: 'Something went wrong with the {operation} operation. Please try again later!',
        msgAjaxProgressError: '{operation} failed',
        ajaxOperations: {
            deleteThumb: 'file delete',
            uploadThumb: 'single file upload',
            uploadBatch: 'batch file upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'Drag & drop ไฟล์ตรงนี้ &hellip;',
        dropZoneClickTitle: '<br>(or click to select {files})',
        fileActionSettings: {
            removeTitle: 'ลบไฟล์',
            uploadTitle: 'อัปโหลดไฟล์',
            zoomTitle: 'ดูรายละเอียด',
            dragTitle: 'Move / Rearrange',
            indicatorNewTitle: 'ยังไม่ได้อัปโหลด',
            indicatorSuccessTitle: 'อัพโหลด',
            indicatorErrorTitle: 'อัปโหลดข้อผิดพลาด',
            indicatorLoadingTitle: 'อัพโหลด ...'
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
/*!
 * FileInput Vietnamese Translations
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

    $.fn.fileinputLocales['vi'] = {
        fileSingle: 'tập tin',
        filePlural: 'các tập tin',
        browseLabel: 'Duyệt &hellip;',
        removeLabel: 'Gỡ bỏ',
        removeTitle: 'Bỏ tập tin đã chọn',
        cancelLabel: 'Hủy',
        cancelTitle: 'Hủy upload',
        pauseLabel: 'Pause',
        pauseTitle: 'Pause ongoing upload',
        uploadLabel: 'Upload',
        uploadTitle: 'Upload tập tin đã chọn',
        msgNo: 'Không',
        msgNoFilesSelected: 'Không tập tin nào được chọn',
        msgPaused: 'Paused',
        msgCancelled: 'Đã hủy',
        msgPlaceholder: 'Select {files} ...',
        msgZoomModalHeading: 'Chi tiết xem trước',
        msgFileRequired: 'You must select a file to upload.',
        msgSizeTooSmall: 'File "{name}" (<b>{size} KB</b>) is too small and must be larger than <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Tập tin "{name}" (<b>{size} KB</b>) vượt quá kích thước giới hạn cho phép <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Bạn phải chọn ít nhất <b>{n}</b> {files} để upload.',
        msgFilesTooMany: 'Số lượng tập tin upload <b>({n})</b> vượt quá giới hạn cho phép là <b>{m}</b>.',
        msgTotalFilesTooMany: 'You can upload a maximum of <b>{m}</b> files (<b>{n}</b> files detected).',
        msgFileNotFound: 'Không tìm thấy tập tin "{name}"!',
        msgFileSecured: 'Các hạn chế về bảo mật không cho phép đọc tập tin "{name}".',
        msgFileNotReadable: 'Không đọc được tập tin "{name}".',
        msgFilePreviewAborted: 'Đã dừng xem trước tập tin "{name}".',
        msgFilePreviewError: 'Đã xảy ra lỗi khi đọc tập tin "{name}".',
        msgInvalidFileName: 'Invalid or unsupported characters in file name "{name}".',
        msgInvalidFileType: 'Tập tin "{name}" không hợp lệ. Chỉ hỗ trợ loại tập tin "{types}".',
        msgInvalidFileExtension: 'Phần mở rộng của tập tin "{name}" không hợp lệ. Chỉ hỗ trợ phần mở rộng "{extensions}".',
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
        msgUploadAborted: 'Đã dừng upload',
        msgUploadThreshold: 'Đang xử lý &hellip;',
        msgUploadBegin: 'Initializing &hellip;',
        msgUploadEnd: 'Done',
        msgUploadResume: 'Resuming upload &hellip;',
        msgUploadEmpty: 'No valid data available for upload.',
        msgUploadError: 'Upload Error',
        msgDeleteError: 'Delete Error',
        msgProgressError: 'Error',
        msgValidationError: 'Lỗi xác nhận',
        msgLoading: 'Đang nạp {index} tập tin trong số {files} &hellip;',
        msgProgress: 'Đang nạp {index} tập tin trong số {files} - {name} - {percent}% hoàn thành.',
        msgSelected: '{n} {files} được chọn',
        msgFoldersNotAllowed: 'Chỉ kéo thả tập tin! Đã bỏ qua {n} thư mục.',
        msgImageWidthSmall: 'Chiều rộng của hình ảnh "{name}" phải tối thiểu là {size} px.',
        msgImageHeightSmall: 'Chiều cao của hình ảnh "{name}" phải tối thiểu là {size} px.',
        msgImageWidthLarge: 'Chiều rộng của hình ảnh "{name}" không được quá {size} px.',
        msgImageHeightLarge: 'Chiều cao của hình ảnh "{name}" không được quá {size} px.',
        msgImageResizeError: 'Không lấy được kích thước của hình ảnh để resize.',
        msgImageResizeException: 'Resize hình ảnh bị lỗi.<pre>{errors}</pre>',
        msgAjaxError: 'Something went wrong with the {operation} operation. Please try again later!',
        msgAjaxProgressError: '{operation} failed',
        msgDuplicateFile: 'File "{name}" of same size "{size} KB" has already been selected earlier. Skipping duplicate selection.',
        msgResumableUploadRetriesExceeded:  'Upload aborted beyond <b>{max}</b> retries for file <b>{file}</b>! Error Details: <pre>{error}</pre>',
        msgPendingTime: '{time} remaining',
        msgCalculatingTime: 'calculating time remaining',
        ajaxOperations: {
            deleteThumb: 'file delete',
            uploadThumb: 'file upload',
            uploadBatch: 'batch file upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'Kéo thả tập tin vào đây &hellip;',
        dropZoneClickTitle: '<br>(hoặc click để chọn {files})',
        fileActionSettings: {
            removeTitle: 'Gỡ bỏ',
            uploadTitle: 'Upload tập tin',
            uploadRetryTitle: 'Retry upload',
            downloadTitle: 'Download file',
            zoomTitle: 'Phóng lớn',
            dragTitle: 'Di chuyển / Sắp xếp lại',
            indicatorNewTitle: 'Chưa được upload',
            indicatorSuccessTitle: 'Đã upload',
            indicatorErrorTitle: 'Upload bị lỗi',
            indicatorPausedTitle: 'Upload Paused',
            indicatorLoadingTitle:  'Đang upload &hellip;'
        },
        previewZoomButtonTitles: {
            prev: 'Xem tập tin phía trước',
            next: 'Xem tập tin tiếp theo',
            toggleheader: 'Ẩn/hiện tiêu đề',
            fullscreen: 'Bật/tắt toàn màn hình',
            borderless: 'Bật/tắt chế độ không viền',
            close: 'Đóng'
        }
    };
})(window.jQuery);

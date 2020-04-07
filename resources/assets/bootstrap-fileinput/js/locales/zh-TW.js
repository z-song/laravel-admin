/*!
 * FileInput Chinese Traditional Translations
 *
 * This file must be loaded after 'fileinput.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-fileinput
 * @author kangqf <kangqingfei@gmail.com>
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
(function ($) {
    "use strict";

    $.fn.fileinputLocales['zh-TW'] = {
        fileSingle: '單一檔案',
        filePlural: '複選檔案',
        browseLabel: '瀏覽 &hellip;',
        removeLabel: '移除',
        removeTitle: '清除選取檔案',
        cancelLabel: '取消',
        cancelTitle: '取消上傳中檔案',
        pauseLabel: 'Pause',
        pauseTitle: 'Pause ongoing upload',
        uploadLabel: '上傳',
        uploadTitle: '上傳選取檔案',
        msgNo: '沒有',
        msgNoFilesSelected: '未選擇檔案',
        msgPaused: 'Paused',
        msgCancelled: '取消',
        zoomTitle: '詳細資料',
        msgPlaceholder: '選擇 {files}...',
        msgZoomModalHeading: '內容預覽',
        msgFileRequired: '必須選擇壹個文件上傳.',
        msgSizeTooSmall: '檔案 "{name}" (<b>{size} KB</b>) 必須大於限定大小 <b>{minSize} KB</b>.',
        msgSizeTooLarge: '檔案 "{name}" (<b>{size} KB</b>) 大小超過上限 <b>{maxSize} KB</b>.',
        msgFilesTooLess: '最少必須選擇 <b>{n}</b> {files} 來上傳. ',
        msgFilesTooMany: '上傳的檔案數量 <b>({n})</b> 超過最大檔案上傳限制 <b>{m}</b>.',
        msgTotalFilesTooMany: 'You can upload a maximum of <b>{m}</b> files (<b>{n}</b> files detected).',
        msgFileNotFound: '檔案 "{name}" 未發現!',
        msgFileSecured: '安全限制，禁止讀取檔案 "{name}".',
        msgFileNotReadable: '文件 "{name}" 不可讀取.',
        msgFilePreviewAborted: '檔案 "{name}" 預覽中止.',
        msgFilePreviewError: '讀取 "{name}" 發生錯誤.',
        msgInvalidFileName: '附檔名 "{name}" 包含非法字符.',
        msgInvalidFileType: '檔案類型錯誤 "{name}". 只能使用 "{types}" 類型的檔案.',
        msgInvalidFileExtension: '附檔名錯誤 "{name}". 只能使用 "{extensions}" 的檔案.',
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
        msgUploadAborted: '該文件上傳被中止',
        msgUploadThreshold: '處理中...',
        msgUploadBegin: '正在初始化...',
        msgUploadEnd: '完成',
        msgUploadResume: 'Resuming upload...',
        msgUploadEmpty: '無效的文件上傳.',
        msgUploadError: 'Upload Error',
        msgDeleteError: 'Delete Error',
        msgProgressError: '上傳錯誤',
        msgValidationError: '驗證錯誤',
        msgLoading: '載入第 {index} 個檔案，共 {files} &hellip;',
        msgProgress: '載入第 {index} 個檔案，共 {files} - {name} - {percent}% 成功.',
        msgSelected: '{n} {files} 選取',
        msgFoldersNotAllowed: '只支援單檔拖曳! 無法使用 {n} 拖拽的資料夹.',
        msgImageWidthSmall: '圖檔寬度"{name}"必須至少為{size}像素(px).',
        msgImageHeightSmall: '圖檔高度"{name}"必須至少為{size}像素(px).',
        msgImageWidthLarge: '圖檔寬度"{name}"不能超過{size}像素(px).',
        msgImageHeightLarge: '圖檔高度"{name}"不能超過{size}像素(px).',
        msgImageResizeError: '無法獲取的圖像尺寸調整。',
        msgImageResizeException: '錯誤而調整圖像大小。<pre>{errors}</pre>',
        msgAjaxError: '{operation} 發生錯誤. 請重試!',
        msgAjaxProgressError: '{operation} 失敗',
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
        dropZoneTitle: '拖曳檔案至此 &hellip;',
        dropZoneClickTitle: '<br>(或點擊{files}按鈕選擇文件)',
        fileActionSettings: {
            removeTitle: '刪除檔案',
            uploadTitle: '上傳檔案',
            uploadRetryTitle: '重試',
            downloadTitle: '下載檔案',
            zoomTitle: '詳細資料',
            dragTitle: '移動 / 重置',
            indicatorNewTitle: '尚未上傳',
            indicatorSuccessTitle: '上傳成功',
            indicatorErrorTitle: '上傳失敗',
            indicatorPausedTitle: 'Upload Paused',
            indicatorLoadingTitle:  '上傳中 ...'
        },
        previewZoomButtonTitles: {
            prev: '預覽上壹個文件',
            next: '預覽下壹個文件',
            toggleheader: '縮放',
            fullscreen: '全屏',
            borderless: '無邊界模式',
            close: '關閉當前預覽'
        }
    };
})(window.jQuery);

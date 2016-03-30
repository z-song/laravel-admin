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
        uploadLabel: '上傳',
        uploadTitle: '上傳選取檔案',
        msgNo: '沒有',
        msgCancelled: '取消',
        msgZoomTitle: '詳細資料',
        msgZoomModalHeading: '內容預覽',
        msgSizeTooLarge: '檔案 "{name}" (<b>{size} KB</b>) 大小超過上限 <b>{maxSize} KB</b>.',
        msgFilesTooLess: '最少必須選擇 <b>{n}</b> {files} 來上傳. ',
        msgFilesTooMany: '上傳的檔案數量 <b>({n})</b> 超過最大檔案上傳限制 <b>{m}</b>.',
        msgFileNotFound: '檔案 "{name}" 未發現!',
        msgFileSecured: '安全限制，禁止讀取檔案 "{name}".',
        msgFileNotReadable: '文件 "{name}" 不可讀取.',
        msgFilePreviewAborted: '檔案 "{name}" 預覽中止.',
        msgFilePreviewError: '讀取 "{name}" 發生錯誤.',
        msgInvalidFileType: '檔案類型錯誤 "{name}". 只能使用 "{types}" 類型的檔案.',
        msgInvalidFileExtension: '附檔名錯誤 "{name}". 只能使用 "{extensions}" 的檔案.',
        msgUploadAborted: '該文件上傳被中止',
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
        dropZoneTitle: '拖曳檔案至此 &hellip;',
        fileActionSettings: {
            removeTitle: '刪除檔案',
            uploadTitle: '上傳檔案',
            indicatorNewTitle: '尚未上傳',
            indicatorSuccessTitle: '上傳成功',
            indicatorErrorTitle: '上傳失敗',
            indicatorLoadingTitle: '上傳中 ...'
        }
    };
})(window.jQuery);

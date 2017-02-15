/*!
 * FileInput Japanese Translations
 *
 * This file must be loaded after 'fileinput.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-fileinput
 * @author Yuta Hoshina <hoshina@gmail.com>
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 * slugCallback
 *    \u4e00-\u9fa5 : Kanji (Chinese characters)
 *    \u3040-\u309f : Hiragana (Japanese syllabary)
 *    \u30a0-\u30ff\u31f0-\u31ff : Katakana (including phonetic extension)
 *    \u3200-\u32ff : Enclosed CJK Letters and Months
 *    \uff00-\uffef : Halfwidth and Fullwidth Forms
 */
(function ($) {
    "use strict";

    $.fn.fileinputLocales['ja'] = {
        fileSingle: 'ファイル',
        filePlural: 'ファイル',
        browseLabel: 'ファイルを選択&hellip;',
        removeLabel: '削除',
        removeTitle: '選択したファイルを削除',
        cancelLabel: 'キャンセル',
        cancelTitle: 'アップロードをキャンセル',
        uploadLabel: 'アップロード',
        uploadTitle: '選択したファイルをアップロード',
        msgNo: 'いいえ',
        msgNoFilesSelected: 'ファイルが選択されていません',
        msgCancelled: 'キャンセル',
        msgZoomModalHeading: 'プレビュー',
        msgSizeTooSmall: 'File "{name}" (<b>{size} KB</b>) is too small and must be larger than <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'ファイル"{name}" (<b>{size} KB</b>)はアップロード可能な上限容量<b>{maxSize} KB</b>を超えています',
        msgFilesTooLess: '最低<b>{n}</b>個の{files}を選択してください',
        msgFilesTooMany: '選択したファイルの数<b>({n}個)</b>はアップロード可能な上限数<b>({m}個)</b>を超えています',
        msgFileNotFound: 'ファイル"{name}"はありませんでした',
        msgFileSecured: 'ファイル"{name}"は読み取り権限がないため取得できません',
        msgFileNotReadable: 'ファイル"{name}"は読み込めません',
        msgFilePreviewAborted: 'ファイル"{name}"のプレビューを中止しました',
        msgFilePreviewError: 'ファイル"{name}"の読み込み中にエラーが発生しました',
        msgInvalidFileName: 'Invalid or unsupported characters in file name "{name}".',
        msgInvalidFileType: '"{name}"は無効なファイル形式です。"{types}"形式のファイルのみサポートしています',
        msgInvalidFileExtension: '"{name}"は無効なファイル拡張子です。拡張子が"{extensions}"のファイルのみサポートしています',
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
        msgUploadAborted: 'ファイルのアップロードが中止されました',
        msgUploadThreshold: 'Processing...',
        msgUploadEmpty: 'No valid data available for upload.',
        msgValidationError: '検証エラー',
        msgLoading: '{files}個中{index}個目のファイルを読み込み中&hellip;',
        msgProgress: '{files}個中{index}個のファイルを読み込み中 - {name} - {percent}% 完了',
        msgSelected: '{n}個の{files}を選択',
        msgFoldersNotAllowed: 'ドラッグ&ドロップが可能なのはファイルのみです。{n}個のフォルダ－は無視されました',
        msgImageWidthSmall: '画像ファイル"{name}"の幅が小さすぎます。画像サイズの幅は少なくとも{size}px必要です',
        msgImageHeightSmall: '画像ファイル"{name}"の高さが小さすぎます。画像サイズの高さは少なくとも{size}px必要です',
        msgImageWidthLarge: '画像ファイル"{name}"の幅がアップロード可能な画像サイズ({size}px)を超えています',
        msgImageHeightLarge: '画像ファイル"{name}"の高さがアップロード可能な画像サイズ({size}px)を超えています',
        msgImageResizeError: 'リサイズ時に画像サイズが取得できませんでした',
        msgImageResizeException: '画像のリサイズ時にエラーが発生しました。<pre>{errors}</pre>',
        msgAjaxError: 'Something went wrong with the {operation} operation. Please try again later!',
        msgAjaxProgressError: '{operation} failed',
        ajaxOperations: {
            deleteThumb: 'file delete',
            uploadThumb: 'single file upload',
            uploadBatch: 'batch file upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'ファイルをドラッグ&ドロップ&hellip;',
        dropZoneClickTitle: '<br>(または クリックして{files}を選択 )',
        slugCallback: function(text) {
            return text ? text.split(/(\\|\/)/g).pop().replace(/[^\w\u4e00-\u9fa5\u3040-\u309f\u30a0-\u30ff\u31f0-\u31ff\u3200-\u32ff\uff00-\uffef\-.\\\/ ]+/g, '') : '';
        },
        fileActionSettings: {
            removeTitle: 'ファイルを削除',
            uploadTitle: 'ファイルをアップロード',
            zoomTitle: 'プレビュー',
            dragTitle: '移動 / 再配置',
            indicatorNewTitle: 'まだアップロードされていません',
            indicatorSuccessTitle: 'アップロード済み',
            indicatorErrorTitle: 'アップロード失敗',
            indicatorLoadingTitle: 'アップロード中...'
        },
        previewZoomButtonTitles: {
            prev: '前のファイルを表示',
            next: '次のファイルを表示',
            toggleheader: 'ファイル情報の表示/非表示',
            fullscreen: 'フルスクリーン表示の開始/終了',
            borderless: 'フルウィンドウ表示の開始/終了',
            close: 'プレビューを閉じる'
        }
    };
})(window.jQuery);

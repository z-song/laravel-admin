/*!
 * FileInput Korean Translations
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

    $.fn.fileinputLocales['kr'] = {
        fileSingle: '파일',
        filePlural: '파일들',
        browseLabel: '찾아보기 &hellip;',
        removeLabel: '지우기',
        removeTitle: '선택한 파일들 지우기',
        cancelLabel: '취소',
        cancelTitle: '진행중인 업로드 중단',
        pauseLabel: 'Pause',
        pauseTitle: 'Pause ongoing upload',
        uploadLabel: '업로드',
        uploadTitle: '선택한 파일 업로드',
        msgNo: '아니요',
        msgNoFilesSelected: '선택한 파일이 없습니다',
        msgPaused: 'Paused',
        msgCancelled: '취소되었습니다',
        msgPlaceholder: '{files} 선택 &hellip;',
        msgZoomModalHeading: '세부 정보',
        msgFileRequired: '업로드를 위해 반드시 파일을 선택해야 합니다.',
        msgSizeTooSmall: '파일 "{name}" (<b>{size} KB</b>)이 너무 작습니다. <b>{minSize} KB</b>보다 용량이 커야 합니다..',
        msgSizeTooLarge: '파일 "{name}" (<b>{size} KB</b>)이 너무 큽니다. 허용 파일 사이즈는 <b>{maxSize} KB</b>.입니다.',
        msgFilesTooLess: '업로드하기 위해 최소 <b>{n}</b> {files}개의 파일을 선택해야 합니다.',
        msgFilesTooMany: '선택한 파일의 수 <b>({n})</b>가 업로드 허용 최고치인 <b>{m}</b>를 넘었습니다..',
        msgTotalFilesTooMany: 'You can upload a maximum of <b>{m}</b> files (<b>{n}</b> files detected).',
        msgFileNotFound: '파일 "{name}"을 찾을 수 없습니다.!',
        msgFileSecured: '보안상의 이유로 "{name}"을/를 읽을 수 없습니다..',
        msgFileNotReadable: '"{name}"은/는 읽을 수 없습니다.',
        msgFilePreviewAborted: '"{name}"의 미리보기가 중단되었습니다.',
        msgFilePreviewError: '"{name}"을/를 읽는 도중 에러가 발생했습니다.',
        msgInvalidFileName: '파일 이름 "{name}" 중 지원 불가능한 문자가 포함되어 있습니다.',
        msgInvalidFileType: '"{name}"의 타입은 지원하지 않습니다. "{types}" 타입의 파일을 선택해 주십시요.',
        msgInvalidFileExtension: '"{name}"의 확장자는 지원하지 않습니다. "{extensions}" 확장자를 선택해 주십시요.',
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
        msgUploadAborted: '파일 업로드가 중단되었습니다',
        msgUploadThreshold: '처리하는 중 &hellip;',
        msgUploadBegin: '초기화 중 &hellip;',
        msgUploadEnd: '완료',
        msgUploadResume: 'Resuming upload &hellip;',
        msgUploadEmpty: '업로드 가능한 데이터가 존재하지 않습니다.',
        msgUploadError: 'Upload Error',
        msgDeleteError: 'Delete Error',
        msgProgressError: '오류',
        msgValidationError: '유효성 오류',
        msgLoading: '{index}/{files}번째 파일을 불러오는 중입니다. &hellip;',
        msgProgress: '{index}/{files} - {name} - {percent}% 불러오기 완료.',
        msgSelected: '{n} {files}이 선택 되었습니다.',
        msgFoldersNotAllowed: '파일만 마우스로 끌어올 수 있습니다! 끌어온 폴더는 건너뜁니다.',
        msgImageWidthSmall: '"{name}"의 가로는 {size} px 보다 넓어야 합니다.',
        msgImageHeightSmall: '"{name}"의 세로는 {size} px 보다 높아야 합니다.',
        msgImageWidthLarge: '"{name}"의 가로는 {size} px를 넘을 수 없습니다.',
        msgImageHeightLarge: '"{name}"의 세로는 {size} px를 넘을 수 없습니다.',
        msgImageResizeError: '이미지의 치수를 가져올 수 없습니다',
        msgImageResizeException: '이미지 사이즈 재조정이 다음 이유로 실패했습니다.<pre>{errors}</pre>',
        msgAjaxError: '{operation} 실행 도중 실패했습니다. 잠시 후 다시 시도해 주세요!',
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
        dropZoneTitle: '마우스로 파일을 끌어오세요 &hellip;',
        dropZoneClickTitle: '<br>(또는 {files} 선택을 위해 클릭하십시요)',
        fileActionSettings: {
            removeTitle: '파일 지우기',
            uploadTitle: '파일 업로드',
            uploadRetryTitle: '업로드 재시도',
            downloadTitle: '파일 다운로드',
            zoomTitle: '세부 정보 보기',
            dragTitle: '옮기기 / 재배열하기',
            indicatorNewTitle: '아직 업로드 되지 않았습니다',
            indicatorSuccessTitle: '업로드 성공',
            indicatorErrorTitle: '업로드 중 에러 발생',
            indicatorPausedTitle: 'Upload Paused',
            indicatorLoadingTitle:  '업로드 중 &hellip;'
        },
        previewZoomButtonTitles: {
            prev: '이전 파일',
            next: '다음 파일',
            toggleheader: '머릿글 토글',
            fullscreen: '전체화면 토글',
            borderless: '창 테두리 토글',
            close: '세부 정보 닫기'
        }
    };
})(window.jQuery);

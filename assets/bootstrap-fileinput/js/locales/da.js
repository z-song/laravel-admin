/*!
 * FileInput Danish Translations
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
    
    $.fn.fileinputLocales['da'] = {
        fileSingle: 'fil',
        filePlural: 'filer',
        browseLabel: 'Browse &hellip;',
        removeLabel: 'Fjern',
        removeTitle: 'Fjern valgte filer',
        cancelLabel: 'Fortryd',
        cancelTitle: 'Afbryd nuv&aelig;rende upload',
        uploadLabel: 'Upload',
        uploadTitle: 'Upload valgte filer',
        msgNo: 'Ingen',
        msgNoFilesSelected: '',
        msgCancelled: 'aflyst',
        msgZoomModalHeading: 'Detaljeret visning',
        msgSizeTooSmall: 'File "{name}" (<b>{size} KB</b>) is too small and must be larger than <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Fil "{name}" (<b>{size} KB</b>) er st&oslash;rre end de tilladte <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Du skal mindst v&aelig;lge <b>{n}</b> {files} til upload.',
        msgFilesTooMany: '<b>({n})</b> filer valgt til upload, men maks. <b>{m}</b> er tilladt.',
        msgFileNotFound: 'Filen "{name}" blev ikke fundet!',
        msgFileSecured: 'Sikkerhedsrestriktioner forhindrer l&aelig;sning af "{name}".',
        msgFileNotReadable: 'Filen "{name}" kan ikke indl&aelig;ses.',
        msgFilePreviewAborted: 'Filpreview annulleret for "{name}".',
        msgFilePreviewError: 'Der skete en fejl under l&aelig;sningen af filen "{name}".',
        msgInvalidFileName: 'Invalid or unsupported characters in file name "{name}".',
        msgInvalidFileType: 'Ukendt type for filen "{name}". Kun "{types}" kan bruges.',
        msgInvalidFileExtension: 'Ukendt filtype for filen "{name}". Kun "{extensions}" filer kan bruges.',
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
        msgUploadAborted: 'Filupload annulleret',
        msgUploadThreshold: 'Processing...',
        msgUploadEmpty: 'No valid data available for upload.',
        msgValidationError: 'Validering Fejl',
        msgLoading: 'Henter fil {index} af {files} &hellip;',
        msgProgress: 'Henter fil {index} af {files} - {name} - {percent}% f&aelig;rdiggjort.',
        msgSelected: '{n} {files} valgt',
        msgFoldersNotAllowed: 'Drag & drop kun filer! {n} mappe(r) sprunget over.',
        msgImageWidthSmall: 'Bredden af billedet "{name}" skal v&aelig;re p&aring; mindst {size} px.',
        msgImageHeightSmall: 'H&oslash;jden af billedet "{name}" skal v&aelig;re p&aring; mindst {size} px.',
        msgImageWidthLarge: 'Bredden af billedet "{name}" m&aring; ikke v&aelig;re over {size} px.',
        msgImageHeightLarge: 'H&oslash;jden af billedet "{name}" m&aring; ikke v&aelig;re over {size} px.',
        msgImageResizeError: 'Kunne ikke få billedets dimensioner for at ændre størrelsen.',
        msgImageResizeException: 'Fejl ved at ændre størrelsen på billedet.<pre>{errors}</pre>',
        msgAjaxError: 'Something went wrong with the {operation} operation. Please try again later!',
        msgAjaxProgressError: '{operation} failed',
        ajaxOperations: {
            deleteThumb: 'file delete',
            uploadThumb: 'single file upload',
            uploadBatch: 'batch file upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'Drag & drop filer her &hellip;',
        dropZoneClickTitle: '<br>(or click to select {files})',
        fileActionSettings: {
            removeTitle: 'Fjern fil',
            uploadTitle: 'Upload fil',
            zoomTitle: 'Se detaljer',
            dragTitle: 'Move / Rearrange',
            indicatorNewTitle: 'Ikke uploadet endnu',
            indicatorSuccessTitle: 'Uploadet',
            indicatorErrorTitle: 'Upload fejl',
            indicatorLoadingTitle: 'Uploader ...'
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
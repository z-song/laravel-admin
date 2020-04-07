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
        pauseLabel: 'Pause',
        pauseTitle: 'Pause ongoing upload',
        uploadLabel: 'Upload',
        uploadTitle: 'Upload valgte filer',
        msgNo: 'Ingen',
        msgNoFilesSelected: '',
        msgPaused: 'Paused',
        msgCancelled: 'aflyst',
        msgPlaceholder: 'V&aelig;lg {files}...',
        msgZoomModalHeading: 'Detaljeret visning',
        msgFileRequired: 'Du skal v&aelig;lge en fil at uploade.',
        msgSizeTooSmall: 'Fil "{name}" (<b>{size} KB</b>) er for lille og skal v&aelig;re st&oslash;rre end <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Fil "{name}" (<b>{size} KB</b>) er st&oslash;rre end de tilladte <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Du skal mindst v&aelig;lge <b>{n}</b> {files} til upload.',
        msgFilesTooMany: '<b>({n})</b> filer valgt til upload, men maks. <b>{m}</b> er tilladt.',
        msgTotalFilesTooMany: 'You can upload a maximum of <b>{m}</b> files (<b>{n}</b> files detected).',
        msgFileNotFound: 'Filen "{name}" blev ikke fundet!',
        msgFileSecured: 'Sikkerhedsrestriktioner forhindrer l&aelig;sning af "{name}".',
        msgFileNotReadable: 'Filen "{name}" kan ikke indl&aelig;ses.',
        msgFilePreviewAborted: 'Filgennemsyn annulleret for "{name}".',
        msgFilePreviewError: 'Der skete en fejl under l&aelig;sningen af filen "{name}".',
        msgInvalidFileName: 'Ugyldige eller ikke-underst&oslash;ttede tegn i filnavn "{name}".',
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
        msgUploadThreshold: 'Arbejder...',
        msgUploadBegin: 'Initialiserer...',
        msgUploadEnd: 'Udf&oslash;rt',
        msgUploadResume: 'Resuming upload...',
        msgUploadEmpty: 'Ingen gyldig data tilg&aelig;ngelig til upload.',
        msgUploadError: 'Upload Error',
        msgDeleteError: 'Delete Error',
        msgProgressError: 'Fejl',
        msgValidationError: 'Valideringsfejl',
        msgLoading: 'Henter fil {index} af {files} &hellip;',
        msgProgress: 'Henter fil {index} af {files} - {name} - {percent}% f&aelig;rdiggjort.',
        msgSelected: '{n} {files} valgt',
        msgFoldersNotAllowed: 'Drag & drop kun filer! {n} mappe(r) sprunget over.',
        msgImageWidthSmall: 'Bredden af billedet "{name}" skal v&aelig;re p&aring; mindst {size} px.',
        msgImageHeightSmall: 'H&oslash;jden af billedet "{name}" skal v&aelig;re p&aring; mindst {size} px.',
        msgImageWidthLarge: 'Bredden af billedet "{name}" m&aring; ikke v&aelig;re over {size} px.',
        msgImageHeightLarge: 'H&oslash;jden af billedet "{name}" m&aring; ikke v&aelig;re over {size} px.',
        msgImageResizeError: 'Kunne ikke f&aring; billedets dimensioner for at &aelig;ndre st&oslash;rrelsen.',
        msgImageResizeException: 'Fejl ved at &aelig;ndre st&oslash;rrelsen p&aring; billedet.<pre>{errors}</pre>',
        msgAjaxError: 'Noget gik galt med {operation} operationen. Fors&oslash;g venligst senere!',
        msgAjaxProgressError: '{operation} fejlede',
        msgDuplicateFile: 'File "{name}" of same size "{size} KB" has already been selected earlier. Skipping duplicate selection.',
        msgResumableUploadRetriesExceeded:  'Upload aborted beyond <b>{max}</b> retries for file <b>{file}</b>! Error Details: <pre>{error}</pre>',
        msgPendingTime: '{time} remaining',
        msgCalculatingTime: 'calculating time remaining',
        ajaxOperations: {
            deleteThumb: 'fil slet',
            uploadThumb: 'fil upload',
            uploadBatch: 'batchfil upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'Drag & drop filer her &hellip;',
        dropZoneClickTitle: '<br>(eller klik for at v&aelig;lge {files})',
        fileActionSettings: {
            removeTitle: 'Fjern fil',
            uploadTitle: 'Upload fil',
            uploadRetryTitle: 'Fors&aring;g upload igen',
            downloadTitle: 'Download fil',
            zoomTitle: 'Se detaljer',
            dragTitle: 'Flyt / Omarranger',
            indicatorNewTitle: 'Ikke uploadet endnu',
            indicatorSuccessTitle: 'Uploadet',
            indicatorErrorTitle: 'Upload fejl',
            indicatorPausedTitle: 'Upload Paused',
            indicatorLoadingTitle:  'Uploader ...'
        },
        previewZoomButtonTitles: {
            prev: 'Se forrige fil',
            next: 'Se n&aelig;ste fil',
            toggleheader: 'Skift header',
            fullscreen: 'Skift fuld sk&aelig;rm',
            borderless: 'Skift gr&aelig;nsel&oslash;s mode',
            close: 'Luk detaljeret visning'
        }
    };
})(window.jQuery);

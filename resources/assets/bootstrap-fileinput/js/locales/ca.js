/*!
 * FileInput Català Translations
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

    $.fn.fileinputLocales['ca'] = {
        fileSingle: 'arxiu',
        filePlural: 'arxius',
        browseLabel: 'Examinar &hellip;',
        removeLabel: 'Treure',
        removeTitle: 'Treure arxius seleccionats',
        cancelLabel: 'Cancel',
        cancelTitle: 'Avortar la pujada en curs',
        pauseLabel: 'Pause',
        pauseTitle: 'Pause ongoing upload',
        uploadLabel: 'Pujar arxiu',
        uploadTitle: 'Pujar arxius seleccionats',
        msgNo: 'No',
        msgNoFilesSelected: '',
        msgPaused: 'Paused',
        msgCancelled: 'cancel·lat',
        msgPlaceholder: 'Select {files}...',
        msgZoomModalHeading: 'Vista prèvia detallada',
        msgFileRequired: 'You must select a file to upload.',
        msgSizeTooSmall: 'File "{name}" (<b>{size} KB</b>) is too small and must be larger than <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Arxiu "{name}" (<b>{size} KB</b>) excedeix la mida màxima permès de <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Heu de seleccionar almenys <b>{n}</b> {files} a carregar.',
        msgFilesTooMany: 'El nombre d\'arxius seleccionats a carregar <b>({n})</b> excedeix el límit màxim permès de <b>{m}</b>.',
        msgTotalFilesTooMany: 'You can upload a maximum of <b>{m}</b> files (<b>{n}</b> files detected).',
        msgFileNotFound: 'Arxiu "{name}" no trobat.',
        msgFileSecured: 'No es pot accedir a l\'arxiu "{name}" perquè estarà sent usat per una altra aplicació o no tinguem permisos de lectura.',
        msgFileNotReadable: 'No es pot accedir a l\'arxiu "{name}".',
        msgFilePreviewAborted: 'Previsualització de l\'arxiu "{name}" cancel·lada.',
        msgFilePreviewError: 'S\'ha produït un error mentre es llegia el fitxer "{name}".',
        msgInvalidFileName: 'Invalid or unsupported characters in file name "{name}".',
        msgInvalidFileType: 'Tipus de fitxer no vàlid per a "{name}". Només arxius "{types}" són permesos.',
        msgInvalidFileExtension: 'Extensió de fitxer no vàlid per a "{name}". Només arxius "{extensions}" són permesos.',
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
        msgUploadAborted: 'La càrrega d\'arxius s\'ha cancel·lat',
        msgUploadThreshold: 'Processing...',
        msgUploadBegin: 'Initializing...',
        msgUploadEnd: 'Done',
        msgUploadResume: 'Resuming upload...',
        msgUploadEmpty: 'No valid data available for upload.',
        msgUploadError: 'Upload Error',
        msgDeleteError: 'Delete Error',
        msgProgressError: 'Error',
        msgValidationError: 'Error de validació',
        msgLoading: 'Pujant fitxer {index} de {files} &hellip;',
        msgProgress: 'Pujant fitxer {index} de {files} - {name} - {percent}% completat.',
        msgSelected: '{n} {files} seleccionat(s)',
        msgFoldersNotAllowed: 'Arrossegueu i deixeu anar únicament arxius. Omesa(es) {n} carpeta(es).',
        msgImageWidthSmall: 'L\'ample de la imatge "{name}" ha de ser almenys {size} px.',
        msgImageHeightSmall: 'L\'alçada de la imatge "{name}" ha de ser almenys {size} px.',
        msgImageWidthLarge: 'L\'ample de la imatge "{name}" no pot excedir de {size} px.',
        msgImageHeightLarge: 'L\'alçada de la imatge "{name}" no pot excedir de {size} px.',
        msgImageResizeError: 'No s\'ha pogut obtenir les dimensions d\'imatge per canviar la mida.',
        msgImageResizeException: 'Error en canviar la mida de la imatge.<pre>{errors}</pre>',
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
        dropZoneTitle: 'Arrossegueu i deixeu anar aquí els arxius &hellip;',
        dropZoneClickTitle: '<br>(or click to select {files})',
        fileActionSettings: {
            removeTitle: 'Eliminar arxiu',
            uploadTitle: 'Pujar arxiu',
            uploadRetryTitle: 'Retry upload',
            downloadTitle: 'Download file',
            zoomTitle: 'Veure detalls',
            dragTitle: 'Move / Rearrange',
            indicatorNewTitle: 'No pujat encara',
            indicatorSuccessTitle: 'Subido',
            indicatorErrorTitle: 'Pujar Error',
            indicatorPausedTitle: 'Upload Paused',
            indicatorLoadingTitle:  'Pujant ...'
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

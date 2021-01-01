/*!
 * FileInput Norwegian Translations
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

    $.fn.fileinputLocales['no'] = {
        fileSingle: 'fil',
        filePlural: 'filer',
        browseLabel: 'Bla gjennom &hellip;',
        removeLabel: 'Fjern',
        removeTitle: 'Fjern valgte filer',
        cancelLabel: 'Avbryt',
        cancelTitle: 'Stopp pågående opplastninger',
        pauseLabel: 'Pause',
        pauseTitle: 'Pause ongoing upload',
        uploadLabel: 'Last opp',
        uploadTitle: 'Last opp valgte filer',
        msgNo: 'Nei',
        msgNoFilesSelected: 'Ingen filer er valgt',
        msgPaused: 'Paused',
        msgCancelled: 'Avbrutt',
        msgPlaceholder: 'Select {files} ...',
        msgZoomModalHeading: 'Detaljert visning',
        msgFileRequired: 'You must select a file to upload.',
        msgSizeTooSmall: 'Filen "{name}" (<b>{size} KB</b>) er for liten og må være større enn <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Filen "{name}" (<b>{size} KB</b>) er for stor, maksimal filstørrelse er <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Du må velge minst <b>{n}</b> {files} for opplastning.',
        msgFilesTooMany: 'For mange filer til opplastning, <b>({n})</b> overstiger maksantallet som er <b>{m}</b>.',
        msgTotalFilesTooMany: 'You can upload a maximum of <b>{m}</b> files (<b>{n}</b> files detected).',
        msgFileNotFound: 'Fant ikke filen "{name}"!',
        msgFileSecured: 'Sikkerhetsrestriksjoner hindrer lesing av filen "{name}".',
        msgFileNotReadable: 'Filen "{name}" er ikke lesbar.',
        msgFilePreviewAborted: 'Filvisning avbrutt for "{name}".',
        msgFilePreviewError: 'En feil oppstod under lesing av filen "{name}".',
        msgInvalidFileName: 'Ugyldige tegn i filen "{name}".',
        msgInvalidFileType: 'Ugyldig type for filen "{name}". Kun "{types}" filer er tillatt.',
        msgInvalidFileExtension: 'Ugyldig endelse for filen "{name}". Kun "{extensions}" filer støttes.',
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
        msgUploadAborted: 'Filopplastningen ble avbrutt',
        msgUploadThreshold: 'Prosesserer &hellip;',
        msgUploadBegin: 'Initialiserer &hellip;',
        msgUploadEnd: 'Ferdig',
        msgUploadResume: 'Resuming upload &hellip;',
        msgUploadEmpty: 'Ingen gyldige data tilgjengelig for opplastning.',
        msgUploadError: 'Upload Error',
        msgDeleteError: 'Delete Error',
        msgProgressError: 'Error',
        msgValidationError: 'Valideringsfeil',
        msgLoading: 'Laster fil {index} av {files} &hellip;',
        msgProgress: 'Laster fil {index} av {files} - {name} - {percent}% fullført.',
        msgSelected: '{n} {files} valgt',
        msgFoldersNotAllowed: 'Kun Dra & slipp filer! Hoppet over {n} mappe(r).',
        msgImageWidthSmall: 'Bredde på bildefilen "{name}" må være minst {size} px.',
        msgImageHeightSmall: 'Høyde på bildefilen "{name}" må være minst {size} px.',
        msgImageWidthLarge: 'Bredde på bildefilen "{name}" kan ikke overstige {size} px.',
        msgImageHeightLarge: 'Høyde på bildefilen "{name}" kan ikke overstige {size} px.',
        msgImageResizeError: 'Fant ikke dimensjonene som skulle resizes.',
        msgImageResizeException: 'En feil oppstod under endring av størrelse .<pre>{errors}</pre>',
        msgAjaxError: 'Noe gikk galt med {operation} operasjonen. Vennligst prøv igjen senere!',
        msgAjaxProgressError: '{operation} feilet',
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
        dropZoneTitle: 'Dra & slipp filer her &hellip;',
        dropZoneClickTitle: '<br>(eller klikk for å velge {files})',
        fileActionSettings: {
            removeTitle: 'Fjern fil',
            uploadTitle: 'Last opp fil',
            uploadRetryTitle: 'Retry upload',
            zoomTitle: 'Vis detaljer',
            dragTitle: 'Flytt / endre rekkefølge',
            indicatorNewTitle: 'Opplastning ikke fullført',
            indicatorSuccessTitle: 'Opplastet',
            indicatorErrorTitle: 'Opplastningsfeil',
            indicatorPausedTitle: 'Upload Paused',
            indicatorLoadingTitle:  'Laster opp &hellip;'
        },
        previewZoomButtonTitles: {
            prev: 'Vis forrige fil',
            next: 'Vis neste fil',
            toggleheader: 'Vis header',
            fullscreen: 'Åpne fullskjerm',
            borderless: 'Åpne uten kanter',
            close: 'Lukk detaljer'
        }
    };
})(window.jQuery);

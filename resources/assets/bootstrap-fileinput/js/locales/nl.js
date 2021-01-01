/*!
 * FileInput Dutch Translations
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

    $.fn.fileinputLocales['nl'] = {
        fileSingle: 'bestand',
        filePlural: 'bestanden',
        browseLabel: 'Zoek &hellip;',
        removeLabel: 'Verwijder',
        removeTitle: 'Verwijder geselecteerde bestanden',
        cancelLabel: 'Annuleren',
        cancelTitle: 'Annuleer upload',
        pauseLabel: 'Pause',
        pauseTitle: 'Pause ongoing upload',
        uploadLabel: 'Upload',
        uploadTitle: 'Upload geselecteerde bestanden',
        msgNo: 'Nee',
        msgNoFilesSelected: '',
        msgPaused: 'Paused',
        msgCancelled: 'Geannuleerd',
        msgPlaceholder: 'Selecteer {files} ...',
        msgZoomModalHeading: 'Gedetailleerd voorbeeld',
        msgFileRequired: 'U moet een bestand kiezen om te uploaden.',
        msgSizeTooSmall: 'Bestand "{name}" (<b>{size} KB</b>) is te klein en moet groter zijn dan <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Bestand "{name}" (<b>{size} KB</b>) is groter dan de toegestane <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'U moet minstens <b>{n}</b> {files} selecteren om te uploaden.',
        msgFilesTooMany: 'Aantal geselecteerde bestanden <b>({n})</b> is meer dan de toegestane <b>{m}</b>.',
        msgTotalFilesTooMany: 'You can upload a maximum of <b>{m}</b> files (<b>{n}</b> files detected).',
        msgFileNotFound: 'Bestand "{name}" niet gevonden!',
        msgFileSecured: 'Bestand kan niet gelezen worden in verband met beveiligings redenen "{name}".',
        msgFileNotReadable: 'Bestand "{name}" is niet leesbaar.',
        msgFilePreviewAborted: 'Bestand weergaven geannuleerd voor "{name}".',
        msgFilePreviewError: 'Er is een fout opgetreden met het lezen van "{name}".',
        msgInvalidFileName: 'Ongeldige of niet ondersteunde karakters in bestandsnaam "{name}".',
        msgInvalidFileType: 'Geen geldig bestand "{name}". Alleen "{types}" zijn toegestaan.',
        msgInvalidFileExtension: 'Geen geldige extensie "{name}". Alleen "{extensions}" zijn toegestaan.',
        msgFileTypes: {
            'image': 'afbeelding',
            'html': 'HTML',
            'text': 'tekst',
            'video': 'video',
            'audio': 'geluid',
            'flash': 'flash',
            'pdf': 'PDF',
            'object': 'object'
        },
        msgUploadAborted: 'Het uploaden van bestanden is afgebroken',
        msgUploadThreshold: 'Verwerken &hellip;',
        msgUploadBegin: 'Initialiseren &hellip;',
        msgUploadEnd: 'Gedaan',
        msgUploadResume: 'Resuming upload &hellip;',
        msgUploadEmpty: 'Geen geldige data beschikbaar voor upload.',
        msgUploadError: 'Upload Error',
        msgDeleteError: 'Delete Error',
        msgProgressError: 'Error',
        msgValidationError: 'Bevestiging fout',
        msgLoading: 'Bestanden laden {index} van de {files} &hellip;',
        msgProgress: 'Bestanden laden {index} van de {files} - {name} - {percent}% compleet.',
        msgSelected: '{n} {files} geselecteerd',
        msgFoldersNotAllowed: 'Drag & drop alleen bestanden! {n} overgeslagen map(pen).',
        msgImageWidthSmall: 'Breedte van het foto-bestand "{name}" moet minstens {size} px zijn.',
        msgImageHeightSmall: 'Hoogte van het foto-bestand "{name}" moet minstens {size} px zijn.',
        msgImageWidthLarge: 'Breedte van het foto-bestand "{name}" kan niet hoger zijn dan {size} px.',
        msgImageHeightLarge: 'Hoogte van het foto bestand "{name}" kan niet hoger zijn dan {size} px.',
        msgImageResizeError: 'Kon de foto afmetingen niet lezen om te verkleinen.',
        msgImageResizeException: 'Fout bij het verkleinen van de foto.<pre>{errors}</pre>',
        msgAjaxError: 'Er ging iets mis met de {operation} actie. Gelieve later opnieuw te proberen!',
        msgAjaxProgressError: '{operation} mislukt',
        msgDuplicateFile: 'File "{name}" of same size "{size} KB" has already been selected earlier. Skipping duplicate selection.',
        msgResumableUploadRetriesExceeded:  'Upload aborted beyond <b>{max}</b> retries for file <b>{file}</b>! Error Details: <pre>{error}</pre>',
        msgPendingTime: '{time} remaining',
        msgCalculatingTime: 'calculating time remaining',
        ajaxOperations: {
            deleteThumb: 'bestand verwijderen',
            uploadThumb: 'bestand uploaden',
            uploadBatch: 'alle bestanden uploaden',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'Drag & drop bestanden hier &hellip;',
        dropZoneClickTitle: '<br>(of klik hier om {files} te selecteren)',
        fileActionSettings: {
            removeTitle: 'Verwijder bestand',
            uploadTitle: 'bestand uploaden',
            uploadRetryTitle: 'Opnieuw uploaden',
            downloadTitle: 'Download file',
            zoomTitle: 'Bekijk details',
            dragTitle: 'Verplaatsen / herindelen',
            indicatorNewTitle: 'Nog niet geupload',
            indicatorSuccessTitle: 'geupload',
            indicatorErrorTitle: 'fout uploaden',
            indicatorPausedTitle: 'Upload Paused',
            indicatorLoadingTitle:  'uploaden &hellip;'
        },
        previewZoomButtonTitles: {
            prev: 'Toon vorig bestand',
            next: 'Toon volgend bestand',
            toggleheader: 'Toggle header',
            fullscreen: 'Toggle volledig scherm',
            borderless: 'Toggle randloze modus',
            close: 'Sluit gedetailleerde weergave'
        }
    };
})(window.jQuery);

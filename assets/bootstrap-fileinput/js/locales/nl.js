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
        uploadLabel: 'Upload',
        uploadTitle: 'Upload geselecteerde bestanden',
        msgNo: 'Nee',
        msgNoFilesSelected: '',
        msgCancelled: 'Geannuleerd',
        msgZoomModalHeading: 'Gedetailleerd voorbeeld',
        msgSizeTooSmall: 'File "{name}" (<b>{size} KB</b>) is too small and must be larger than <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Bestand "{name}" (<b>{size} KB</b>) is groter dan de toegestane <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'U moet minstens <b>{n}</b> {files} selecteren om te uploaden.',
        msgFilesTooMany: 'Aantal geselecteerde bestanden <b>({n})</b> is meer dan de toegestane <b>{m}</b>.',
        msgFileNotFound: 'Bestand "{name}" niet gevonden!',
        msgFileSecured: 'Bestand kan niet gelezen worden in verband met beveiligings redenen "{name}".',
        msgFileNotReadable: 'Bestand "{name}" is niet leesbaar.',
        msgFilePreviewAborted: 'Bestand weergaven geannuleerd voor "{name}".',
        msgFilePreviewError: 'Er is een fout opgetreden met het lezen van "{name}".',
        msgInvalidFileName: 'Invalid or unsupported characters in file name "{name}".',
        msgInvalidFileType: 'Geen geldig bestand "{name}". Alleen "{types}" zijn toegestaan.',
        msgInvalidFileExtension: 'Geen geldige extensie "{name}". Alleen "{extensions}" zijn toegestaan.',
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
        msgUploadAborted: 'Het uploaden van bestanden is afgebroken',
        msgUploadThreshold: 'Processing...',
        msgUploadEmpty: 'No valid data available for upload.',
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
        msgAjaxError: 'Something went wrong with the {operation} operation. Please try again later!',
        msgAjaxProgressError: '{operation} failed',
        ajaxOperations: {
            deleteThumb: 'file delete',
            uploadThumb: 'single file upload',
            uploadBatch: 'batch file upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'Drag & drop bestanden hier &hellip;',
        dropZoneClickTitle: '<br>(or click to select {files})',
        fileActionSettings: {
            removeTitle: 'Verwijder bestand',
            uploadTitle: 'bestand uploaden',
            zoomTitle: 'Bekijk details',
            dragTitle: 'Move / Rearrange',
            indicatorNewTitle: 'Nog niet geupload',
            indicatorSuccessTitle: 'geupload',
            indicatorErrorTitle: 'fout uploaden',
            indicatorLoadingTitle: 'uploaden ...'
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

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
        msgCancelled: 'Geannuleerd',
        msgZoomTitle: 'Bekijk details',
        msgZoomModalHeading: 'Gedetailleerd voorbeeld',
        msgSizeTooLarge: 'Bestand "{name}" (<b>{size} KB</b>) is groter dan de toegestane <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'U moet minstens <b>{n}</b> {files} selecteren om te uploaden.',
        msgFilesTooMany: 'Aantal geselecteerde bestanden <b>({n})</b> is meer dan de toegestane <b>{m}</b>.',
        msgFileNotFound: 'Bestand "{name}" niet gevonden!',
        msgFileSecured: 'Bestand kan niet gelezen worden in verband met beveiligings redenen "{name}".',
        msgFileNotReadable: 'Bestand "{name}" is niet leesbaar.',
        msgFilePreviewAborted: 'Bestand weergaven geannuleerd voor "{name}".',
        msgFilePreviewError: 'Er is een fout opgetreden met het lezen van "{name}".',
        msgInvalidFileType: 'Geen geldig bestand "{name}". Alleen "{types}" zijn toegestaan.',
        msgInvalidFileExtension: 'Geen geldige extensie "{name}". Alleen "{extensions}" zijn toegestaan.',
        msgUploadAborted: 'Het uploaden van bestanden is afgebroken',
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
        dropZoneTitle: 'Drag & drop bestanden hier &hellip;',
        fileActionSettings: {
            removeTitle: 'Verwijder bestand',
            uploadTitle: 'bestand uploaden',
            indicatorNewTitle: 'Nog niet geupload',
            indicatorSuccessTitle: 'geupload',
            indicatorErrorTitle: 'fout uploaden',
            indicatorLoadingTitle: 'uploaden ...'
        }
    };
})(window.jQuery);

/*!
 * FileInput Croatian Translations
 *
 * This file must be loaded after 'fileinput.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-fileinput
 * @author Milos Stojanovic <stojanovic.loshmi@gmail.com>
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
(function ($) {
    "use strict";

    $.fn.fileinputLocales['cr'] = {
        fileSingle: 'datoteka',
        filePlural: 'datoteke',
        browseLabel: 'Izaberi &hellip;',
        removeLabel: 'Ukloni',
        removeTitle: 'Ukloni označene datoteke',
        cancelLabel: 'Odustani',
        cancelTitle: 'Prekini trenutno otpremanje',
        uploadLabel: 'Otpremi',
        uploadTitle: 'Otpremi označene datoteke',
        msgNo: 'Ne',
        msgCancelled: 'Otkazan',
        msgZoomTitle: 'Pregledavati pojedinosti',
        msgZoomModalHeading: 'Detaljni pregled',
        msgSizeTooLarge: 'Datoteka "{name}" (<b>{size} KB</b>) prekoračuje maksimalnu dozvoljenu veličinu datoteke od <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Morate odabrati najmanje <b>{n}</b> {files} za otpremanje.',
        msgFilesTooMany: 'Broj datoteka označenih za otpremanje <b>({n})</b> prekoračuje maksimalni dozvoljeni limit od <b>{m}</b>.',
        msgFileNotFound: 'Datoteka "{name}" nije pronađena!',
        msgFileSecured: 'Datoteku "{name}" nije moguće pročitati zbog bezbednosnih ograničenja.',
        msgFileNotReadable: 'Datoteku "{name}" nije moguće pročitati.',
        msgFilePreviewAborted: 'Generisanje prikaza nije moguće za "{name}".',
        msgFilePreviewError: 'Došlo je do greške prilikom čitanja datoteke "{name}".',
        msgInvalidFileType: 'Datoteka "{name}" je pogrešnog formata. Dozvoljeni formati su "{types}".',
        msgInvalidFileExtension: 'Ekstenzija datoteke "{name}" nije dozvoljena. Dozvoljene ekstenzije su "{extensions}".',
        msgUploadAborted: 'Prijenos datoteka je prekinut',
        msgValidationError: 'Provjera pogrešaka',
        msgLoading: 'Učitavanje datoteke {index} od {files} &hellip;',
        msgProgress: 'Učitavanje datoteke {index} od {files} - {name} - {percent}% završeno.',
        msgSelected: '{n} {files} je označeno',
        msgFoldersNotAllowed: 'Moguće je prevlačiti samo datoteke! Preskočeno je {n} fascikla.',
        msgImageWidthSmall: 'Širina slikovnu datoteku "{name}" moraju biti najmanje {size} px.',
        msgImageHeightSmall: 'Visina slikovnu datoteku "{name}" moraju biti najmanje {size} px.',
        msgImageWidthLarge: 'Širina slikovnu datoteku "{name}" ne može prelaziti {size} px.',
        msgImageHeightLarge: 'Visina slikovnu datoteku "{name}" ne može prelaziti {size} px.',
        msgImageResizeError: 'Nije mogao dobiti dimenzije slike na veličinu.',
        msgImageResizeException: 'Greška prilikom promjene veličine slike.<pre>{errors}</pre>',
        dropZoneTitle: 'Prevucite datoteke ovde &hellip;',
        fileActionSettings: {
            removeTitle: 'Uklonite datoteku',
            uploadTitle: 'Postavi datoteku',
            indicatorNewTitle: 'Još nije učitao',
            indicatorSuccessTitle: 'Preneseno',
            indicatorErrorTitle: 'Postavi Greška',
            indicatorLoadingTitle: 'Prijenos ...'
        }
    };
})(window.jQuery);

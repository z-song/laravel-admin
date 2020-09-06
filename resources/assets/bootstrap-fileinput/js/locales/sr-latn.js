/*!
 * FileInput Serbian Latin Translations
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

    $.fn.fileinputLocales['sr-latn'] = {
        fileSingle: 'dokument',
        filePlural: 'dokumenti',
        browseLabel: 'Odaberi dokument &hellip;',
        removeLabel: 'Obriši',
        removeTitle: 'Obriši odabrane dokumente',
        cancelLabel: 'Prekini',
        cancelTitle: 'Prekini slanje dokumenata',
        pauseLabel: 'Pauziraj',
        pauseTitle: 'Pauziraj slanje dokumenata',
        uploadLabel: 'Pošalji',
        uploadTitle: 'Pošalji odabrane dokumente',
        msgNo: 'Ne',
        msgNoFilesSelected: 'Nema odabranih dokumenata',
        msgPaused: 'Pauzirano',
        msgCancelled: 'Prekinuto',
        msgPlaceholder: 'Odaberi {files} ...',
        msgZoomModalHeading: 'Pregled detalja',
        msgFileRequired: 'Obavezan odabir dokumenta za slanje.',
        msgSizeTooSmall: 'Dokument "{name}" (<b>{size} KB</b>) mora biti veći od <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Dokument "{name}" (<b>{size} KB</b>) prelazi maksimalnu dozvoljenu veličinu od <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Obavezan odabir minimum <b>{n}</b> dokumenata za slanje.',
        msgFilesTooMany: 'Broj dokumenata odabranih za slanje <b>({n})</b> prelazi maksimalno ograničenje od <b>{m}</b>.',
        msgTotalFilesTooMany: 'Maksimalni broj dokumenata je <b>{m}</b> (<b>{n}</b> je odabrano).',
        msgFileNotFound: 'Dokument "{name}" nije pronađen!',
        msgFileSecured: 'Bezbednosna ograničenja sprečavaju čitanje dokumenta "{name}".',
        msgFileNotReadable: 'Dokument "{name}" nije moguće pročitati.',
        msgFilePreviewAborted: 'Prikaz dokumenta "{name}" je prekinut.',
        msgFilePreviewError: 'Greška u čitanju dokumenta "{name}".',
        msgInvalidFileName: 'Nedozvoljeni karakteri u nazivu dokumenta "{name}".',
        msgInvalidFileType: 'Nedozvoljeni tip dokumenta "{name}". Dozvoljeni tipovi dokumenata su "{types}".',
        msgInvalidFileExtension: 'Nedozvoljena ekstenzija dokumenta "{name}". Dozvoljene ekstenzije dokumenata su "{extensions}".',
        msgFileTypes: {
            'image': 'slika',
            'html': 'HTML',
            'text': 'tekst',
            'video': 'video',
            'audio': 'audio',
            'flash': 'flash',
            'pdf': 'PDF',
            'object': 'objekat'
        },
        msgUploadAborted: 'Prekinuto je slanje dokumenta',
        msgUploadThreshold: 'U obradi &hellip;',
        msgUploadBegin: 'Pokretanje &hellip;',
        msgUploadEnd: 'Završeno',
        msgUploadResume: 'Nastavlja se slanje &hellip;',
        msgUploadEmpty: 'Nema podataka za slanje.',
        msgUploadError: 'Greška u slanju',
        msgDeleteError: 'Greška u brisanju',
        msgProgressError: 'Greška',
        msgValidationError: 'Greška nakon provere podataka',
        msgLoading: 'Učitavanje dokumenta {index} od {files} &hellip;',
        msgProgress: 'Učitavanje dokumenta {index} od {files} - {name} - {percent}% završeno.',
        msgSelected: '{n} dokumenata odabrano',
        msgFoldersNotAllowed: 'Prevlačenje foldera nije dozvoljeno! {n} prevučenih foldera nije dodato.',
        msgImageWidthSmall: 'Širina slike "{name}" mora biti veća od {size} px.',
        msgImageHeightSmall: 'Visina slike "{name}" mora biti veća od {size} px.',
        msgImageWidthLarge: 'Širina slike "{name}" mora biti manja od {size} px.',
        msgImageHeightLarge: 'Visina slike "{name}" mora biti manja od {size} px.',
        msgImageResizeError: 'Greška u čitanju dimenzija slike za promenu veličine.',
        msgImageResizeException: 'Greška u promeni veličine slike.<pre>{errors}</pre>',
        msgAjaxError: 'Greška u {operation} operaciji. Molimo pokušajte ponovo kasnije!',
        msgAjaxProgressError: 'Operacija {operation} nije uspela.',
        msgDuplicateFile: 'Dokument "{name}" iste veličine "{size} KB" je već selektovan. Duplirani dokument je preskočen.',
        msgResumableUploadRetriesExceeded:  'Slanje je prekinuto nakon <b>{max}</b> pokušaja za dokument <b>{file}</b>! Detalji greške: <pre>{error}</pre>',
        msgPendingTime: '{time} preostalo',
        msgCalculatingTime: 'računanje preostalog vremena',
        ajaxOperations: {
            deleteThumb: 'brisanje dokumenta',
            uploadThumb: 'slanje dokumenta',
            uploadBatch: 'slanje grupe dokumenata',
            uploadExtra: 'slanje podataka forme'
        },
        dropZoneTitle: 'Prevuci dokumente ovde &hellip;',
        dropZoneClickTitle: '<br>(ili klikni za odabir dokumenata)',
        fileActionSettings: {
            removeTitle: 'Obriši dokument',
            uploadTitle: 'Pošalji dokument',
            uploadRetryTitle: 'Ponovi slanje',
            downloadTitle: 'Skini dokument',
            zoomTitle: 'Pregled detalja',
            dragTitle: 'Promeni redosled',
            indicatorNewTitle: 'Nije poslato',
            indicatorSuccessTitle: 'Poslato',
            indicatorErrorTitle: 'Greška u slanju',
            indicatorPausedTitle: 'Pauzirano slanje',
            indicatorLoadingTitle:  'Slanje u toku &hellip;'
        },
        previewZoomButtonTitles: {
            prev: 'Prethodni dokument',
            next: 'Sledeći dokument',
            toggleheader: 'Isključi naslov',
            fullscreen: 'Raširi na ceo prozor',
            borderless: 'Isključi ivice',
            close: 'Zatvori pregled detalja'
        }
    };
})(window.jQuery);

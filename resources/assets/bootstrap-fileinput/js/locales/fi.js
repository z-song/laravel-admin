/*!
 * FileInput Finnish Translations
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

    $.fn.fileinputLocales.fi = {
        fileSingle: 'tiedosto',
        filePlural: 'tiedostot',
        browseLabel: 'Selaa &hellip;',
        removeLabel: 'Poista',
        removeTitle: 'Tyhj&auml;nn&auml; valitut tiedostot',
        cancelLabel: 'Peruuta',
        cancelTitle: 'Peruuta lataus',
        pauseLabel: 'Pause',
        pauseTitle: 'Pause ongoing upload',
        uploadLabel: 'Lataa',
        uploadTitle: 'Lataa valitut tiedostot',
        msgNoFilesSelected: '',
        msgFileRequired: 'You must select a file to upload.',
        msgSizeTooSmall: 'File "{name}" (<b>{size} KB</b>) is too small and must be larger than <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Tiedosto "{name}" (<b>{size} Kt</b>) ylitt&auml;&auml; suurimman sallitun tiedoston koon, joka on <b>{maxSize} Kt</b>. Yrit&auml; uudelleen!',
        msgFilesTooLess: 'V&auml;hint&auml;&auml;n <b>{n}</b> {files} tiedostoa on valittava ladattavaksi. Ole hyv&auml; ja yrit&auml; uudelleen!',
        msgFilesTooMany: 'Valittujen tiedostojen lukum&auml;&auml;r&auml; <b>({n})</b> ylitt&auml;&auml; suurimman sallitun m&auml;&auml;r&auml;n <b>{m}</b>. Ole hyv&auml; ja yrit&auml; uudelleen!',
        msgTotalFilesTooMany: 'You can upload a maximum of <b>{m}</b> files (<b>{n}</b> files detected).',
        msgFileNotFound: 'Tiedostoa "{name}" ei l&ouml;ydy!',
        msgFileSecured: 'Tietoturvarajoitukset est&auml;v&auml;t tiedoston "{name}" lukemisen.',
        msgFileNotReadable: 'Tiedosto "{name}" ei ole luettavissa.',
        msgFilePreviewAborted: 'Tiedoston "{name}" esikatselu keskeytetty.',
        msgFilePreviewError: 'Virhe on tapahtunut luettaessa tiedostoa "{name}".',
        msgInvalidFileName: 'Invalid or unsupported characters in file name "{name}".',
        msgInvalidFileType: 'Tiedosto "{name}" on v&auml;&auml;r&auml;n tyyppinen. Ainoastaan tiedostot tyyppi&auml; "{types}" ovat tuettuja.',
        msgInvalidFileExtension: 'Tiedoston "{name}" tarkenne on ep&auml;kelpo. Ainoastaan tarkenteet "{extensions}" ovat tuettuja.',
        msgFileTypes: {
            'image': 'Kuva',
            'html': 'HTML',
            'text': 'Teksti',
            'video': 'Video',
            'audio': 'Ääni',
            'flash': 'Flash',
            'pdf': 'PDF',
            'object': 'Olio'
        },
        msgUploadThreshold: 'Käsitellään &hellip;',
        msgUploadBegin: 'Initializing &hellip;',
        msgUploadEnd: 'Done',
        msgUploadResume: 'Resuming upload &hellip;',
        msgUploadEmpty: 'Ei ladattavaa dataa.',
        msgUploadError: 'Upload Error',
        msgDeleteError: 'Delete Error',
        msgProgressError: 'Error',
        msgValidationError: 'Tiedoston latausvirhe',
        msgLoading: 'Ladataan tiedostoa {index} / {files} &hellip;',
        msgProgress: 'Ladataan tiedostoa {index} / {files} - {name} - {percent}% valmistunut.',
        msgSelected: '{n} tiedostoa valittu',
        msgFoldersNotAllowed: 'Raahaa ja pudota ainoastaan tiedostoja! Ohitettu {n} raahattua kansiota.',
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
        dropZoneTitle: 'Raahaa ja pudota tiedostot t&auml;h&auml;n &hellip;',
        dropZoneClickTitle: '<br>(tai valitse hiirellä {files})',
        fileActionSettings: {
            removeTitle: 'Poista tiedosto',
            uploadTitle: 'Upload file',
            uploadRetryTitle: 'Retry upload',
            downloadTitle: 'Download file',
            zoomTitle: 'Yksityiskohdat',
            dragTitle: 'Siirrä / Järjestele',
            indicatorNewTitle: 'Ei ladattu',
            indicatorSuccessTitle: 'Ladattu',
            indicatorErrorTitle: 'Lataus epäonnistui',
            indicatorPausedTitle: 'Upload Paused',
            indicatorLoadingTitle:  'Ladataan &hellip;'
        },
        previewZoomButtonTitles: {
            prev: 'Seuraava tiedosto',
            next: 'Edellinen tiedosto',
            toggleheader: 'Näytä otsikko',
            fullscreen: 'Kokonäytön tila',
            borderless: 'Rajaton tila',
            close: 'Sulje esikatselu'
        }
    };

    $.extend($.fn.fileinput.defaults, $.fn.fileinputLocales.fi);
})(window.jQuery);

/*!
 * FileInput <_LANG_> Translations
 *
 * This file must be loaded after 'fileinput.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-fileinput
 * @author Mindaugas Varkalys <varkalys.mindaugas@gmail.com>
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
(function ($) {
    "use strict";

    $.fn.fileinputLocales['lt'] = {
        fileSingle: 'failas',
        filePlural: 'failai',
        browseLabel: 'Naršyti &hellip;',
        removeLabel: 'Šalinti',
        removeTitle: 'Pašalinti pasirinktus failus',
        cancelLabel: 'Atšaukti',
        cancelTitle: 'Atšaukti vykstantį įkėlimą',
        pauseLabel: 'Pause',
        pauseTitle: 'Pause ongoing upload',
        uploadLabel: 'Įkelti',
        uploadTitle: 'Įkelti pasirinktus failus',
        msgNo: 'Ne',
        msgNoFilesSelected: 'Nepasirinkta jokių failų',
        msgPaused: 'Paused',
        msgCancelled: 'Atšaukta',
        msgPlaceholder: 'Select {files} ...',
        msgZoomModalHeading: 'Detali Peržiūra',
        msgFileRequired: 'Pasirinkite failą įkėlimui.',
        msgSizeTooSmall: 'Failas "{name}" (<b>{size} KB</b>) yra per mažas ir turi būti didesnis nei <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Failas "{name}" (<b>{size} KB</b>) viršija maksimalų leidžiamą įkeliamo failo dydį <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Turite pasirinkti bent <b>{n}</b> failus įkėlimui.',
        msgFilesTooMany: 'Įkėlimui pasirinktų failų skaičius <b>({n})</b> viršija maksimalų leidžiamą limitą <b>{m}</b>.',
        msgTotalFilesTooMany: 'You can upload a maximum of <b>{m}</b> files (<b>{n}</b> files detected).',
        msgFileNotFound: 'Failas "{name}" nerastas!',
        msgFileSecured: 'Saugumo apribojimai neleidžia perskaityti failo "{name}".',
        msgFileNotReadable: 'Failas "{name}" neperskaitomas.',
        msgFilePreviewAborted: 'Failo peržiūra nutraukta "{name}".',
        msgFilePreviewError: 'Įvyko klaida skaitant failą "{name}".',
        msgInvalidFileName: 'Klaidingi arba nepalaikomi simboliai failo pavadinime "{name}".',
        msgInvalidFileType: 'Klaidingas failo "{name}" tipas. Tik "{types}" tipai yra palaikomi.',
        msgInvalidFileExtension: 'Klaidingas failo "{name}" plėtinys. Tik "{extensions}" plėtiniai yra palaikomi.',
        msgFileTypes: {
            'image': 'paveikslėlis',
            'html': 'HTML',
            'text': 'tekstas',
            'video': 'vaizdo įrašas',
            'audio': 'garso įrašas',
            'flash': 'flash',
            'pdf': 'PDF',
            'object': 'objektas'
        },
        msgUploadAborted: 'Failo įkėlimas buvo nutrauktas',
        msgUploadThreshold: 'Vykdoma &hellip;',
        msgUploadBegin: 'Inicijuojama &hellip;',
        msgUploadEnd: 'Baigta',
        msgUploadResume: 'Resuming upload &hellip;',
        msgUploadEmpty: 'Nėra teisingų duomenų įkėlimui.',
        msgUploadError: 'Upload Error',
        msgDeleteError: 'Delete Error',
        msgProgressError: 'Klaida',
        msgValidationError: 'Validacijos Klaida',
        msgLoading: 'Keliamas failas {index} iš {files} &hellip;',
        msgProgress: 'Keliamas failas {index} iš {files} - {name} - {percent}% baigta.',
        msgSelected: 'Pasirinkti {n} {files}',
        msgFoldersNotAllowed: 'Tempkite tik failus! Praleisti {n} nutempti aplankalas(-i).',
        msgImageWidthSmall: 'Paveikslėlio "{name}" plotis turi būti bent {size} px.',
        msgImageHeightSmall: 'Paveikslėlio "{name}" aukštis turi būti bent {size} px.',
        msgImageWidthLarge: 'Paveikslėlio "{name}" plotis negali viršyti {size} px.',
        msgImageHeightLarge: 'Paveikslėlio "{name}" aukštis negali viršyti {size} px.',
        msgImageResizeError: 'Nepavyksta gauti paveikslėlio matmetų, kad pakeisti jo matmemis.',
        msgImageResizeException: 'Klaida keičiant paveikslėlio matmenis.<pre>{errors}</pre>',
        msgAjaxError: 'Kažkas nutiko vykdant {operation} operaciją. Prašome pabandyti vėliau!',
        msgAjaxProgressError: '{operation} operacija nesėkminga',
        msgDuplicateFile: 'File "{name}" of same size "{size} KB" has already been selected earlier. Skipping duplicate selection.',
        msgResumableUploadRetriesExceeded:  'Upload aborted beyond <b>{max}</b> retries for file <b>{file}</b>! Error Details: <pre>{error}</pre>',
        msgPendingTime: '{time} remaining',
        msgCalculatingTime: 'calculating time remaining',
        ajaxOperations: {
            deleteThumb: 'failo trynimo',
            uploadThumb: 'failo įkėlimo',
            uploadBatch: 'failų rinkinio įkėlimo',
            uploadExtra: 'formos duomenų įkėlimo'
        },
        dropZoneTitle: 'Tempkite failus čia &hellip;',
        dropZoneClickTitle: '<br>(arba paspauskite, kad pasirinktumėte failus)',
        fileActionSettings: {
            removeTitle: 'Šalinti failą',
            uploadTitle: 'Įkelti failą',
            uploadRetryTitle: 'Bandyti įkelti vėl',
            zoomTitle: 'Peržiūrėti detales',
            dragTitle: 'Perstumti',
            indicatorNewTitle: 'Dar neįkelta',
            indicatorSuccessTitle: 'Įkelta',
            indicatorErrorTitle: 'Įkėlimo Klaida',
            indicatorPausedTitle: 'Upload Paused',
            indicatorLoadingTitle:  'Įkeliama &hellip;'
        },
        previewZoomButtonTitles: {
            prev: 'Peržiūrėti ankstesnį failą',
            next: 'Peržiūrėti kitą failą',
            toggleheader: 'Perjungti viršutinę juostą',
            fullscreen: 'Perjungti pilno ekrano rėžimą',
            borderless: 'Perjungti berėmį režimą',
            close: 'Uždaryti detalią peržiūrą'
        }
    };
})(window.jQuery);
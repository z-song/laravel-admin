/*!
 * FileInput Romanian Translations
 *
 * This file must be loaded after 'fileinput.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-fileinput
 * @author Ciprian Voicu <pictoru@autoportret.ro>
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
(function ($) {
    "use strict";

    $.fn.fileinputLocales['ro'] = {
        fileSingle: 'fișier',
        filePlural: 'fișiere',
        browseLabel: 'Răsfoiește &hellip;',
        removeLabel: 'Șterge',
        removeTitle: 'Curăță fișierele selectate',
        cancelLabel: 'Renunță',
        cancelTitle: 'Anulează încărcarea curentă',
        uploadLabel: 'Încarcă',
        uploadTitle: 'Încarcă fișierele selectate',
        msgNo: 'Nu',
        msgNoFilesSelected: '',
        msgCancelled: 'Anulat',
        msgZoomModalHeading: 'Previzualizare detaliată',
        msgSizeTooSmall: 'File "{name}" (<b>{size} KB</b>) is too small and must be larger than <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Fișierul "{name}" (<b>{size} KB</b>) depășește limita maximă de încărcare de <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Trebuie să selectezi cel puțin <b>{n}</b> {files} pentru a încărca.',
        msgFilesTooMany: 'Numărul fișierelor pentru încărcare <b>({n})</b> depășește limita maximă de <b>{m}</b>.',
        msgFileNotFound: 'Fișierul "{name}" nu a fost găsit!',
        msgFileSecured: 'Restricții de securitate previn citirea fișierului "{name}".',
        msgFileNotReadable: 'Fișierul "{name}" nu se poate citi.',
        msgFilePreviewAborted: 'Fișierului "{name}" nu poate fi previzualizat.',
        msgFilePreviewError: 'A intervenit o eroare în încercarea de citire a fișierului "{name}".',
        msgInvalidFileName: 'Invalid or unsupported characters in file name "{name}".',
        msgInvalidFileType: 'Tip de fișier incorect pentru "{name}". Sunt suportate doar fișiere de tipurile "{types}".',
        msgInvalidFileExtension: 'Extensie incorectă pentru "{name}". Sunt suportate doar extensiile "{extensions}".',
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
        msgUploadAborted: 'Fișierul Încărcarea a fost întrerupt',
        msgUploadThreshold: 'Processing...',
        msgUploadEmpty: 'No valid data available for upload.',
        msgValidationError: 'Eroare de validare',
        msgLoading: 'Se încarcă fișierul {index} din {files} &hellip;',
        msgProgress: 'Se încarcă fișierul {index} din {files} - {name} - {percent}% încărcat.',
        msgSelected: '{n} {files} încărcate',
        msgFoldersNotAllowed: 'Se poate doar trăgând fișierele! Se renunță la {n} dosar(e).',
        msgImageWidthSmall: 'Lățimea de fișier de imagine "{name}" trebuie să fie de cel puțin {size} px.',
        msgImageHeightSmall: 'Înălțimea fișier imagine "{name}" trebuie să fie de cel puțin {size} px.',
        msgImageWidthLarge: 'Lățimea de fișier de imagine "{name}" nu poate depăși {size} px.',
        msgImageHeightLarge: 'Înălțimea fișier imagine "{name}" nu poate depăși {size} px.',
        msgImageResizeError: 'Nu a putut obține dimensiunile imaginii pentru a redimensiona.',
        msgImageResizeException: 'Eroare la redimensionarea imaginii.<pre>{errors}</pre>',
        msgAjaxError: 'Something went wrong with the {operation} operation. Please try again later!',
        msgAjaxProgressError: '{operation} failed',
        ajaxOperations: {
            deleteThumb: 'file delete',
            uploadThumb: 'single file upload',
            uploadBatch: 'batch file upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'Trage fișierele aici &hellip;',
        dropZoneClickTitle: '<br>(or click to select {files})',
        fileActionSettings: {
            removeTitle: 'Scoateți fișier',
            uploadTitle: 'Incarca fisier',
            zoomTitle: 'Vezi detalii',
            dragTitle: 'Move / Rearrange',
            indicatorNewTitle: 'Nu a încărcat încă',
            indicatorSuccessTitle: 'încărcat',
            indicatorErrorTitle: 'Încărcați eroare',
            indicatorLoadingTitle: 'Se încarcă ...'
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

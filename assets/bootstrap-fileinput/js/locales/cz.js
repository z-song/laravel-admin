/*!
 * FileInput Czech Translations
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

    $.fn.fileinputLocales['cz'] = {
        fileSingle: 'soubor',
        filePlural: 'soubory',
        browseLabel: 'Vybrat &hellip;',
        removeLabel: 'Odstranit',
        removeTitle: 'Vyčistit vybrané soubory',
        cancelLabel: 'Storno',
        cancelTitle: 'Přerušit  nahrávání',
        uploadLabel: 'Nahrát',
        uploadTitle: 'Nahrát vybrané soubory',
        msgNo: 'Ne',
        msgNoFilesSelected: 'Nevybrány žádné soubory',
        msgCancelled: 'Zrušeno',
        msgZoomModalHeading: 'Detailní náhled',
        msgSizeTooSmall: 'Soubor "{name}" (<b>{size} KB</b>) je příliš malý, musí mít velikost nejméně <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Soubor "{name}" (<b>{size} KB</b>): je příliš velký - maximální povolená velikost <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Musíte vybrat nejméně <b>{n}</b> {files} souborů.',
        msgFilesTooMany: 'Počet vybraných souborů <b>({n})</b> překročil maximální povolený limit <b>{m}</b>.',
        msgFileNotFound: 'Soubor "{name}" nebyl nalezen!',
        msgFileSecured: 'Zabezpečení souboru znemožnilo číst soubor "{name}".',
        msgFileNotReadable: 'Soubor "{name}" není čitelný.',
        msgFilePreviewAborted: 'Náhled souboru byl přerušen pro "{name}".',
        msgFilePreviewError: 'Nastala chyba při načtení souboru "{name}".',
        msgInvalidFileName: 'Neplatné nebo nepovolené znaky ve jménu souboru "{name}".',
        msgInvalidFileType: 'Neplatný typ souboru "{name}". Pouze "{types}" souborů jsou podporovány.',
        msgInvalidFileExtension: 'Neplatná extenze souboru "{name}". Pouze "{extensions}" souborů jsou podporovány.',
        msgUploadAborted: 'Nahrávání souboru bylo přerušeno',
        msgUploadThreshold: 'Zpracovávám...',
        msgValidationError: 'Chyba ověření',
        msgLoading: 'Nahrávání souboru {index} z {files} &hellip;',
        msgProgress: 'Nahrávání souboru {index} z {files} - {name} - {percent}% dokončeno.',
        msgSelected: '{n} {files} vybráno',
        msgFoldersNotAllowed: 'Táhni a pusť pouze soubory! Vynechané {n} pustěné složk(y).',
        msgImageWidthSmall: 'Šířka obrázku "{name}", musí být alespoň {size} px.',
        msgImageHeightSmall: 'Výška obrázku "{name}", musí být alespoň {size} px.',
        msgImageWidthLarge: 'Šířka obrázku "{name}" nesmí být větší než {size} px.',
        msgImageHeightLarge: 'Výška obrázku "{name}" nesmí být větší než {size} px.',
        msgImageResizeError: 'Nelze získat rozměry obrázku pro změnu velikosti.',
        msgImageResizeException: 'Chyba při změně velikosti obrázku.<pre>{errors}</pre>',
        msgAjaxError: 'Something went wrong with the {operation} operation. Please try again later!',
        msgAjaxProgressError: '{operation} failed',
        ajaxOperations: {
            deleteThumb: 'file delete',
            uploadThumb: 'single file upload',
            uploadBatch: 'batch file upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'Přetáhni soubory sem &hellip;',
        dropZoneClickTitle: '<br>(nebo klikni sem a vyber je)',
        fileActionSettings: {
            removeTitle: 'Odstranit soubor',
            uploadTitle: 'nahrát soubor',
            zoomTitle: 'zobrazit podrobnosti',
            dragTitle: 'Posunout / Přeskládat',
            indicatorNewTitle: 'Ještě nenahrál',
            indicatorSuccessTitle: 'Nahraný',
            indicatorErrorTitle: 'Chyba nahrávání',
            indicatorLoadingTitle: 'Nahrávání ...'
        },
        previewZoomButtonTitles: {
            prev: 'Zobrazit předchozí soubor',
            next: 'Zobrazit následující soubor',
            toggleheader: 'Přepnout záhlaví',
            fullscreen: 'Přepnout celoobrazovkové zobrazení',
            borderless: 'Přepnout bezrámečkové zobrazení',
            close: 'Zavřít detailní náhled'
        }
    };
})(window.jQuery);
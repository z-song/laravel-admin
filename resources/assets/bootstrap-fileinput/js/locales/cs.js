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

    $.fn.fileinputLocales['cs'] = {
        fileSingle: 'soubor',
        filePlural: 'soubory',
        browseLabel: 'Vybrat &hellip;',
        removeLabel: 'Odstranit',
        removeTitle: 'Vyčistit vybrané soubory',
        cancelLabel: 'Storno',
        cancelTitle: 'Přerušit  nahrávání',
        pauseLabel: 'Pozastavit',
        pauseTitle: 'Pozastavit probíhající nahrávání',
        uploadLabel: 'Nahrát',
        uploadTitle: 'Nahrát vybrané soubory',
        msgNo: 'Ne',
        msgNoFilesSelected: 'Nevybrány žádné soubory',
        msgPaused: 'Pozastavené',
        msgCancelled: 'Zrušeno',
        msgPlaceholder: 'Vybrat {files} ...',
        msgZoomModalHeading: 'Detailní náhled',
        msgFileRequired: 'Musíte vybrat soubor, který chcete nahrát.',
        msgSizeTooSmall: 'Soubor "{name}" (<b>{size} KB</b>) je příliš malý, musí mít velikost nejméně <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Soubor "{name}" (<b>{size} KB</b>) je příliš velký, maximální povolená velikost <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Musíte vybrat nejméně <b>{n}</b> {files} souborů.',
        msgFilesTooMany: 'Počet vybraných souborů <b>({n})</b> překročil maximální povolený limit <b>{m}</b>.',
        msgTotalFilesTooMany: 'Můžete nahrát maximálně <b>{m}</b> souborů (bylo nalezeno <b>{n}</b> souborů).',
        msgFileNotFound: 'Soubor "{name}" nebyl nalezen!',
        msgFileSecured: 'Zabezpečení souboru znemožnilo číst soubor "{name}".',
        msgFileNotReadable: 'Soubor "{name}" není čitelný.',
        msgFilePreviewAborted: 'Náhled souboru byl přerušen pro "{name}".',
        msgFilePreviewError: 'Nastala chyba při načtení souboru "{name}".',
        msgInvalidFileName: 'Neplatné nebo nepovolené znaky ve jménu souboru "{name}".',
        msgInvalidFileType: 'Neplatný typ souboru "{name}". Pouze "{types}" souborů jsou podporovány.',
        msgInvalidFileExtension: 'Neplatná extenze souboru "{name}". Pouze "{extensions}" souborů jsou podporovány.',
        msgFileTypes: {
            'image': 'obrázek',
            'html': 'HTML',
            'text': 'text',
            'video': 'video',
            'audio': 'audio',
            'flash': 'flash',
            'pdf': 'PDF',
            'object': 'object'
        },
        msgUploadAborted: 'Nahrávání souboru bylo přerušeno',
        msgUploadThreshold: 'Zpracovávám &hellip;',
        msgUploadBegin: 'Inicializujem &hellip;',
        msgUploadEnd: 'Hotovo',
        msgUploadResume: 'Obnovuje se nahrávání &hellip;',
        msgUploadEmpty: 'Pro nahrávání nejsou k dispozici žádné platné údaje.',
        msgUploadError: 'Chyba při nahrávání',
        msgDeleteError: 'Chyba při odstraňování',
        msgProgressError: 'Chyba',
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
        msgAjaxError: 'Došlo k chybě v {operation}. Prosím zkuste to znovu později!',
        msgAjaxProgressError: '{operation} - neúspěšné',
        msgDuplicateFile: 'Soubor "{name}" stejné velikosti "{size} KB" už byl vybrán dříve. Přeskočení duplicitního výběru.',
        msgResumableUploadRetriesExceeded:  'Nahrávání bylo přerušeno po <b>{max}</b> opakováních souboru <b>{file}</b>! Detaily chyby: <pre>{error}</pre>',
        msgPendingTime: '{time} zůstává',
        msgCalculatingTime: 'výpočet zbývajícího času',
        ajaxOperations: {
            deleteThumb: 'odstranit soubor',
            uploadThumb: 'nahrát soubor',
            uploadBatch: 'nahrát várku souborů',
            uploadExtra: 'odesílání dat formuláře'
        },
        dropZoneTitle: 'Přetáhni soubory sem &hellip;',
        dropZoneClickTitle: '<br>(nebo klikni sem a vyber je)',
        fileActionSettings: {
            removeTitle: 'Odstranit soubor',
            uploadTitle: 'Nahrát soubor',
            uploadRetryTitle: 'Opakovat nahrávání',
            downloadTitle: 'Stáhnout soubor',
            zoomTitle: 'Zobrazit podrobnosti',
            dragTitle: 'Posunout / Přeskládat',
            indicatorNewTitle: 'Ještě nenahrál',
            indicatorSuccessTitle: 'Nahraný',
            indicatorErrorTitle: 'Chyba nahrávání',
            indicatorPausedTitle: 'Nahrávání bylo pozastaveno',
            indicatorLoadingTitle:  'Nahrávání &hellip;'
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

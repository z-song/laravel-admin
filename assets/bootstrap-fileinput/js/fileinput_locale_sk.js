/*!
 * FileInput Slovakian Translations
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

    $.fn.fileinputLocales['sk'] = {
        fileSingle: 'súbor',
        filePlural: 'súbory',
        browseLabel: 'Vybrať &hellip;',
        removeLabel: 'Odstrániť',
        removeTitle: 'Vyčistiť vybraté súbory',
        cancelLabel: 'Storno',
        cancelTitle: 'Prerušiť  nahrávanie',
        uploadLabel: 'Nahrať',
        uploadTitle: 'Nahrať vybraté súbory',
        msgNo: 'Nie',
        msgCancelled: 'Zrušené',
        msgZoomTitle: 'Zobraziť podrobnosti',
        msgZoomModalHeading: 'Detailný náhľad',
        msgSizeTooLarge: 'Súbor "{name}" (<b>{size} KB</b>): prekročenie - maximálna povolená veľkosť <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Musíte vybrať najmenej <b>{n}</b> {files} pre nahranie.',
        msgFilesTooMany: 'Počet vybratých súborov pre nahranie <b>({n})</b>: prekročenie - maximálny povolený limit <b>{m}</b>.',
        msgFileNotFound: 'Súbor "{name}" nebol nájdený!',
        msgFileSecured: 'Zabezpečenie súboru znemožnilo čítať súbor "{name}".',
        msgFileNotReadable: 'Súbor "{name}" nie je čitateľný.',
        msgFilePreviewAborted: 'Náhľad súboru bol prerušený pre "{name}".',
        msgFilePreviewError: 'Nastala chyba pri načítaní súboru "{name}".',
        msgInvalidFileType: 'Neplatný typ súboru "{name}". Iba "{types}" súborov sú podporované.',
        msgInvalidFileExtension: 'Neplatná extenzia súboru "{name}". Iba "{extensions}" súborov sú podporované.',
        msgUploadAborted: 'Súbor nahrávania bol prerušený',
        msgValidationError: 'Chyba overenia',
        msgLoading: 'Nahrávanie súboru {index} z {files} &hellip;',
        msgProgress: 'Nahrávanie súboru {index} z {files} - {name} - {percent}% dokončené.',
        msgSelected: '{n} {files} vybraté',
        msgFoldersNotAllowed: 'Tiahni a pusť iba súbory! Vynechané {n} pustené prečinok(y).',
        msgImageWidthSmall: 'Šírka image súboru "{name}", musí byť minimálne {size} px.',
        msgImageHeightSmall: 'Výška image súboru "{name}", musí byť minimálne {size} px.',
        msgImageWidthLarge: 'Šírka image súboru "{name}" nemôže presiahnuť {size} px.',
        msgImageHeightLarge: 'Výška súboru obrazu "{name}" nesmie presiahnuť {size} px.',
        msgImageResizeError: 'Nemožno získať rozmery obrázku zmeniť veľkosť.',
        msgImageResizeException: 'Chyba pri zmene veľkosti obrázka.<pre>{errors}</pre>',
        dropZoneTitle: 'Tiahni a pusť súbory tu &hellip;',
        fileActionSettings: {
            removeTitle: 'odstrániť súbor',
            uploadTitle: 'nahrať súbor',
            indicatorNewTitle: 'Ešte nenahral',
            indicatorSuccessTitle: 'nahral',
            indicatorErrorTitle: 'nahrať Chyba',
            indicatorLoadingTitle: 'nahrávanie ...'
        }
    };
})(window.jQuery);
/*!
 * FileInput Hungarian Translations
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

    $.fn.fileinputLocales['hu'] = {
        fileSingle: 'fájl',
        filePlural: 'fájl',
        browseLabel: 'Böngész &hellip;',
        removeLabel: 'Eltávolít',
        removeTitle: 'Kijelölt fájlok törlése',
        cancelLabel: 'Mégse',
        cancelTitle: 'Feltöltés megszakítása',
        uploadLabel: 'Feltöltés',
        uploadTitle: 'Kijelölt fájlok feltöltése',
        msgNo: 'No',
        msgCancelled: 'Cancelled',
        msgZoomTitle: 'Részletek megtekintése',
        msgZoomModalHeading: 'Részletes Preview',
        msgSizeTooLarge: '"{name}" fájl (<b>{size} KB</b>) mérete nagyobb a megengedettnél <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Legalább <b>{n}</b> {files} ki kell választania a feltöltéshez.',
        msgFilesTooMany: 'A feltölteni kívánt fájlok száma <b>({n})</b> elérte a megengedett maximumot <b>{m}</b>.',
        msgFileNotFound: '"{name}" fájl nem található!',
        msgFileSecured: 'Biztonsági beállítások nem engedik olvasni a fájlt "{name}".',
        msgFileNotReadable: '"{name}" fájl nem olvasható',
        msgFilePreviewAborted: '"{name}" fájl feltöltése megszakítva.',
        msgFilePreviewError: 'Hiba lépett fel a "{name}" fájl olvasása közben.',
        msgInvalidFileType: 'Nem megengedett fájl "{name}". Csak a "{types}" fájl típusok támogatottak.',
        msgInvalidFileExtension: 'Nem megengedett kiterjesztés / fájltípus "{name}". Csak a "{extensions}" kiterjesztés(ek) / fájltípus(ok) támogatottak.',
        msgUploadAborted: 'A fájl feltöltés megszakítva',
        msgValidationError: 'Érvényesítés hiba',
        msgLoading: '{index} / {files} töltése &hellip;',
        msgProgress: 'Feltöltés: {index} / {files} - {name} - {percent}% kész.',
        msgSelected: '{n} {files} kiválasztva.',
        msgFoldersNotAllowed: 'Csak fájlokat húzzon ide! Kihagyva {n} könyvtár.',
        msgImageWidthSmall: 'Szélessége image file "{name}" legalább {size} px.',
        msgImageHeightSmall: 'Magassága image file "{name}" legalább {size} px.',
        msgImageWidthLarge: 'Szélessége image file "{name}" nem haladhatja meg a {size} px.',
        msgImageHeightLarge: 'Magassága image file "{name}" nem haladhatja meg a {size} px.',
        msgImageResizeError: 'Nem lehet megszerezni a kép méretei átméretezni.',
        msgImageResizeException: 'Hiba történt a méretezés.<pre>{errors}</pre>',
        dropZoneTitle: 'Fájlok húzása ide &hellip;',
        fileActionSettings: {
            removeTitle: 'A fájl eltávolítása',
            uploadTitle: 'fájl feltöltése',
            indicatorNewTitle: 'Nem feltöltve',
            indicatorSuccessTitle: 'Feltöltött',
            indicatorErrorTitle: 'Feltöltés Error',
            indicatorLoadingTitle: 'Feltöltése ...'
        }
    };
})(window.jQuery);

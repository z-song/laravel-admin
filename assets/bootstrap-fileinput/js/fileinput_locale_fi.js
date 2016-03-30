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

    $.fn.fileinput.locales.fi = {
        fileSingle: 'tiedosto',
        filePlural: 'tiedostot',
        browseLabel: 'Selaa &hellip;',
        removeLabel: 'Poista',
        removeTitle: 'Tyhj&auml;nn&auml; valitut tiedostot',
        cancelLabel: 'Peruuta',
        cancelTitle: 'Peruuta lataus',
        uploadLabel: 'Lataa',
        uploadTitle: 'Lataa valitut tiedostot',
        msgSizeTooLarge: 'Tiedosto "{name}" (<b>{size} Kt</b>) ylitt&auml;&auml; suurimman sallitun tiedoston koon, joka on <b>{maxSize} Kt</b>. Yrit&auml; uudelleen!',
        msgFilesTooLess: 'V&auml;hint&auml;&auml;n <b>{n}</b> {files} tiedostoa on valittava ladattavaksi. Ole hyv&auml; ja yrit&auml; uudelleen!',
        msgFilesTooMany: 'Valittujen tiedostojen lukum&auml;&auml;r&auml; <b>({n})</b> ylitt&auml;&auml; suurimman sallitun m&auml;&auml;r&auml;n <b>{m}</b>. Ole hyv&auml; ja yrit&auml; uudelleen!',
        msgFileNotFound: 'Tiedostoa "{name}" ei l&ouml;ydy!',
        msgFileSecured: 'Tietoturvarajoitukset est&auml;v&auml;t tiedoston "{name}" lukemisen.',
        msgFileNotReadable: 'Tiedosto "{name}" ei ole luettavissa.',
        msgFilePreviewAborted: 'Tiedoston "{name}" esikatselu keskeytetty.',
        msgFilePreviewError: 'Virhe on tapahtunut luettaessa tiedostoa "{name}".',
        msgInvalidFileType: 'Tiedosto "{name}" on v&auml;&auml;r&auml;n tyyppinen. Ainoastaan tiedostot tyyppi&auml; "{types}" ovat tuettuja.',
        msgInvalidFileExtension: 'Tiedoston "{name}" tarkenne on ep&auml;kelpo. Ainoastaan tarkenteet "{extensions}" ovat tuettuja.',
        msgValidationError: 'Tiedoston latausvirhe',
        msgLoading: 'Ladataan tiedostoa {index} / {files} &hellip;',
        msgProgress: 'Ladataan tiedostoa {index} / {files} - {name} - {percent}% valmistunut.',
        msgSelected: '{n} tiedostoa valittu',
        msgFoldersNotAllowed: 'Raahaa ja pudota ainoastaan tiedostoja! Ohitettu {n} raahattua kansiota.',
        dropZoneTitle: 'Raahaa ja pudota tiedostot t&auml;h&auml;n &hellip;'
    };

    $.extend($.fn.fileinput.defaults, $.fn.fileinput.locales.fi);
})(window.jQuery);
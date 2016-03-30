/*!
 * FileInput Polish Translations
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

    $.fn.fileinputLocales['pl'] = {
        fileSingle: 'plik',
        filePlural: 'pliki',
        browseLabel: 'Przeglądaj &hellip;',
        removeLabel: 'Usuń',
        removeTitle: 'Usuń zaznaczone pliki',
        cancelLabel: 'Przerwij',
        cancelTitle: 'Anuluj wysyłanie',
        uploadLabel: 'Wgraj',
        uploadTitle: 'Wgraj zaznaczone pliki',
        msgNo: 'Nie',
        msgCancelled: 'Odwołany',
        msgZoomTitle: 'Pokaż szczegóły',
        msgZoomModalHeading: 'Szczegółowe Podgląd',
        msgSizeTooLarge: 'Plik o nazwie "{name}" (<b>{size} KB</b>) przekroczył maksymalną dopuszczalną wielkość pliku wynoszącą <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Musisz wybrać przynajmniej <b>{n}</b> {files} do wgrania.',
        msgFilesTooMany: 'Liczba plików wybranych do wgrania w liczbie <b>({n})</b>, przekracza maksymalny dozwolony limit wynoszący <b>{m}</b>.',
        msgFileNotFound: 'Plik "{name}" nie istnieje!',
        msgFileSecured: 'Ustawienia zabezpieczeń uniemożliwiają odczyt pliku "{name}".',
        msgFileNotReadable: 'Plik "{name}" nie jest plikiem do odczytu.',
        msgFilePreviewAborted: 'Podgląd pliku "{name}" został przerwany.',
        msgFilePreviewError: 'Wystąpił błąd w czasie odczytu pliku "{name}".',
        msgInvalidFileType: 'Nieznny typ pliku "{name}". Tylko następujące rodzaje plików "{types}", są obsługiwane.',
        msgInvalidFileExtension: 'Złe rozszerzenie dla pliku "{name}". Tylko następujące rozszerzenia plików "{extensions}", są obsługiwane.',
        msgUploadAborted: 'Plik przesyłanie zostało przerwane',
        msgValidationError: 'Błąd walidacji',
        msgLoading: 'Wczytywanie pliku {index} z {files} &hellip;',
        msgProgress: 'Wczytywanie pliku {index} z {files} - {name} - {percent}% zakończone.',
        msgSelected: '{n} {files} zaznaczonych',
        msgFoldersNotAllowed: 'Metodą przeciągnij i upuść, można przenosić tylko pliki. Pominięto {n} katalogów.',
        msgImageWidthSmall: 'Szerokość pliku obrazu "{name}" musi być co najmniej {size} px.',
        msgImageHeightSmall: 'Wysokość pliku obrazu "{name}" musi być co najmniej {size} px.',
        msgImageWidthLarge: 'Szerokość pliku obrazu "{name}" nie może przekraczać {size} px.',
        msgImageHeightLarge: 'Wysokość pliku obrazu "{name}" nie może przekraczać {size} px.',
        msgImageResizeError: 'Nie udało się uzyskać wymiary obrazu, aby zmienić rozmiar.',
        msgImageResizeException: 'Błąd podczas zmiany rozmiaru obrazu.<pre>{errors}</pre>',
        dropZoneTitle: 'Przeciągnij i upuść pliki tu &hellip;',
        fileActionSettings: {
            removeTitle: 'Usuń plik',
            uploadTitle: 'przesyłanie pliku',
            indicatorNewTitle: 'Jeszcze nie przesłanych',
            indicatorSuccessTitle: 'Dodane',
            indicatorErrorTitle: 'Prześlij błąd',
            indicatorLoadingTitle: 'Zamieszczanie ...'
        }
    };
})(window.jQuery);
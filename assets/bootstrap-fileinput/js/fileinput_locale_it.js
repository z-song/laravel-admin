/*!
 * FileInput Italian Translation
 * 
 * Author: Lorenzo Milesi <maxxer@yetopen.it>
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

    $.fn.fileinputLocales['it'] = {
        fileSingle: 'file',
        filePlural: 'file',
        browseLabel: 'Sfoglia&hellip;',
        removeLabel: 'Rimuovi',
        removeTitle: 'Rimuovi i file selezionati',
        cancelLabel: 'Annulla',
        cancelTitle: 'Annulla i caricamenti in corso',
        uploadLabel: 'Carica',
        uploadTitle: 'Carica i file selezionati',
        msgNo: 'No',
        msgCancelled: 'Annullato',
        msgZoomTitle: 'Guarda i dettagli',
        msgZoomModalHeading: 'Anteprima dettagliata',
        msgSizeTooLarge: 'Il file "{name}" (<b>{size} KB</b>) eccede la dimensione massima di caricamento di <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Devi selezionare almeno <b>{n}</b> {files} da caricare.',
        msgFilesTooMany: 'Il numero di file selezionati per il caricamento <b>({n})</b> eccede il numero massimo di file accettati <b>{m}</b>.',
        msgFileNotFound: 'File "{name}" non trovato!',
        msgFileSecured: 'Restrizioni di sicurezza impediscono la lettura del file "{name}".',
        msgFileNotReadable: 'Il file "{name}" non \xE8 leggibile.',
        msgFilePreviewAborted: 'Generazione anteprima per "{name}" annullata.',
        msgFilePreviewError: 'Errore durante la lettura del file "{name}".',
        msgInvalidFileType: 'Tipo non valido per il file "{name}". Sono ammessi solo file di tipo "{types}".',
        msgInvalidFileExtension: 'Estensione non valida per il file "{name}". Sono ammessi solo file con estensione "{extensions}".',
        msgUploadAborted: 'Il caricamento del file è stata interrotta',
        msgValidationError: 'Errore di convalida',
        msgLoading: 'Caricamento file {index} di {files}&hellip;',
        msgProgress: 'Caricamento file {index} di {files} - {name} - {percent}% completato.',
        msgSelected: '{n} {files} selezionati',
        msgFoldersNotAllowed: 'Trascina solo file! Ignorata/e {n} cartella/e.',
        msgImageWidthSmall: 'Larghezza di file immagine "{name}" deve essere di almeno {size} px.',
        msgImageHeightSmall: 'Altezza di file immagine "{name}" deve essere di almeno {size} px.',
        msgImageWidthLarge: 'Larghezza di file immagine "{name}" non può superare {size} px.',
        msgImageHeightLarge: 'Altezza di file immagine "{name}" non può superare {size} px.',
        msgImageResizeError: "Impossibile ottenere le dimensioni dell'immagine per ridimensionare.",
        msgImageResizeException: "Errore durante il ridimensionamento dell'immagine.<pre>{errors}</pre>",
        dropZoneTitle: 'Trascina i file qui&hellip;',
        fileActionSettings: {
            removeTitle: 'Rimuovere il file',
            uploadTitle: 'Caricare un file',
            indicatorNewTitle: 'Non ancora caricato',
            indicatorSuccessTitle: 'Caricati',
            indicatorErrorTitle: 'Carica Errore',
            indicatorLoadingTitle: 'Caricamento ...'
        }
    };
})(window.jQuery);
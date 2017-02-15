/*!
 * FileInput Greek Translations
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

    $.fn.fileinputLocales['el'] = {
        fileSingle: 'αρχείο',
        filePlural: 'αρχεία',
        browseLabel: 'Αναζήτηση &hellip;',
        removeLabel: 'Διαγραφή',
        removeTitle: 'Εκκαθάριση αρχείων',
        cancelLabel: 'Ακύρωση',
        cancelTitle: 'Ακύρωση μεταφόρτωσης',
        uploadLabel: 'Μεταφόρτωση',
        uploadTitle: 'Μεταφόρτωση επιλεγμένων αρχείων',
        msgNo: 'Όχι',
        msgNoFilesSelected: 'Δεν επιλέχθηκαν αρχεία',
        msgCancelled: 'Ακυρώθηκε',
        msgZoomModalHeading: 'Λεπτομερής Προεπισκόπηση',
        msgSizeTooSmall: 'Το "{name}" (<b>{size} KB</b>) είναι πολύ μικρό, πρέπει να είναι μεγαλύτερο από <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Το αρχείο "{name}" (<b>{size} KB</b>) υπερβαίνει το μέγιστο επιτρεπόμενο μέγεθος μεταφόρτωσης <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Πρέπει να επιλέξετε τουλάχιστον <b>{n}</b> {files} για να ξεκινήσει η μεταφόρτωση.',
        msgFilesTooMany: 'Ο αριθμός των αρχείων που έχουν επιλεγεί για μεταφόρτωση <b>({n})</b> υπερβαίνει το μέγιστο επιτρεπόμενο αριθμό <b>{m}</b>.',
        msgFileNotFound: 'Το αρχείο "{name}" δεν βρέθηκε!',
        msgFileSecured: 'Περιορισμοί ασφαλείας εμπόδισαν την ανάγνωση του αρχείου "{name}".',
        msgFileNotReadable: 'Το αρχείο "{name}" δεν είναι αναγνώσιμο.',
        msgFilePreviewAborted: 'Η προεπισκόπηση του αρχείου "{name}" ακυρώθηκε.',
        msgFilePreviewError: 'Παρουσιάστηκε σφάλμα κατά την ανάγνωση του αρχείου "{name}".',
        msgInvalidFileName: 'Μη έγκυροι χαρακτήρες στο όνομα του αρχείου "{name}".',
        msgInvalidFileType: 'Μη έγκυρος ο τύπος του αρχείου "{name}". Οι τύποι αρχείων που υποστηρίζονται είναι : "{types}".',
        msgInvalidFileExtension: 'Μη έγκυρη η επέκταση του αρχείου "{name}". Οι επεκτάσεις που υποστηρίζονται είναι : "{extensions}".',
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
        msgUploadAborted: 'Η μεταφόρτωση του αρχείου ματαιώθηκε',
        msgUploadThreshold: 'Μεταφόρτωση ...',
        msgValidationError: 'Σφάλμα Επικύρωσης',
        msgLoading: 'Φόρτωση αρχείου {index} από {files} &hellip;',
        msgProgress: 'Φόρτωση αρχείου {index} απο {files} - {name} - {percent}% ολοκληρώθηκε.',
        msgSelected: '{n} {files} επιλέχθηκαν',
        msgFoldersNotAllowed: 'Μπορείτε να σύρετε μόνο αρχεία! Παραβλέφθηκαν {n} φάκελος(οι).',
        msgImageWidthSmall: 'Το πλάτος του αρχείου εικόνας "{name}" πρέπει να είναι τουλάχιστον {size} px.',
        msgImageHeightSmall: 'Το ύψος του αρχείου εικόνας "{name}" πρέπει να είναι τουλάχιστον {size} px.',
        msgImageWidthLarge: 'Το πλάτος του αρχείου εικόνας "{name}" δεν μπορεί να υπερβαίνει το {size} px.',
        msgImageHeightLarge: 'Το ύψος του αρχείου εικόνας "{name}" δεν μπορεί να υπερβαίνει το {size} px.',
        msgImageResizeError: 'Δεν μπορούν να βρεθούν οι διαστάσεις της εικόνας για να αλλάγή μεγέθους.',
        msgImageResizeException: 'Σφάλμα κατά την αλλαγή μεγέθους της εικόνας. <pre>{errors}</pre>',
        msgAjaxError: 'Something went wrong with the {operation} operation. Please try again later!',
        msgAjaxProgressError: '{operation} failed',
        ajaxOperations: {
            deleteThumb: 'file delete',
            uploadThumb: 'single file upload',
            uploadBatch: 'batch file upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'Σύρετε τα αρχεία εδώ &hellip;',
        dropZoneClickTitle: '<br>(ή πατήστε για επιλογή {files})',
        fileActionSettings: {
            removeTitle: 'Αφαιρέστε το αρχείο',
            uploadTitle: 'Μεταφορτώστε το αρχείο',
            zoomTitle: 'Δείτε λεπτομέρειες',
            dragTitle: 'Μετακίνηση/Προσπαρμογή',
            indicatorNewTitle: 'Δεν μεταφορτώθηκε ακόμα',
            indicatorSuccessTitle: 'Μεταφορτώθηκε',
            indicatorErrorTitle: 'Σφάλμα Μεταφόρτωσης',
            indicatorLoadingTitle: 'Μεταφόρτωση ...'
        },
        previewZoomButtonTitles: {
            prev: 'Προηγούμενο αρχείο',
            next: 'Επόμενο αρχείο',
            toggleheader: 'Εμφάνιση/Απόκρυψη τίτλου',
            fullscreen: 'Εναλλαγή πλήρους οθόνης',
            borderless: 'Με ή χωρίς πλαίσιο',
            close: 'Κλείσιμο προβολής'
        }
    };
})(window.jQuery);

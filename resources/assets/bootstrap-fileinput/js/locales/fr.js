/*!
 * FileInput French Translations
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

    $.fn.fileinputLocales['fr'] = {
        fileSingle: 'fichier',
        filePlural: 'fichiers',
        browseLabel: 'Parcourir&hellip;',
        removeLabel: 'Retirer',
        removeTitle: 'Retirer les fichiers sélectionnés',
        cancelLabel: 'Annuler',
        cancelTitle: "Annuler l'envoi en cours",
        pauseLabel: 'Pause',
        pauseTitle: 'Pause ongoing upload',
        uploadLabel: 'Transférer',
        uploadTitle: 'Transférer les fichiers sélectionnés',
        msgNo: 'Non',
        msgNoFilesSelected: '',
        msgPaused: 'Paused',
        msgCancelled: 'Annulé',
        msgPlaceholder: 'Sélectionner le(s) {files}...',
        msgZoomModalHeading: 'Aperçu détaillé',
        msgFileRequired: 'Vous devez sélectionner un fichier à uploader.',
        msgSizeTooSmall: 'Le fichier "{name}" (<b>{size} KB</b>) est inférieur à la taille minimale de <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Le fichier "{name}" (<b>{size} Ko</b>) dépasse la taille maximale autorisée qui est de <b>{maxSize} Ko</b>.',
        msgFilesTooLess: 'Vous devez sélectionner au moins <b>{n}</b> {files} à transmettre.',
        msgFilesTooMany: 'Le nombre de fichier sélectionné <b>({n})</b> dépasse la quantité maximale autorisée qui est de <b>{m}</b>.',
        msgTotalFilesTooMany: 'You can upload a maximum of <b>{m}</b> files (<b>{n}</b> files detected).',
        msgFileNotFound: 'Le fichier "{name}" est introuvable !',
        msgFileSecured: "Des restrictions de sécurité vous empêchent d'accéder au fichier \"{name}\".",
        msgFileNotReadable: 'Le fichier "{name}" est illisible.',
        msgFilePreviewAborted: 'Prévisualisation du fichier "{name}" annulée.',
        msgFilePreviewError: 'Une erreur est survenue lors de la lecture du fichier "{name}".',
        msgInvalidFileName: 'Caractères invalides ou non supportés dans le nom de fichier "{name}".',
        msgInvalidFileType: 'Type de document invalide pour "{name}". Seulement les documents de type "{types}" sont autorisés.',
        msgInvalidFileExtension: 'Extension invalide pour le fichier "{name}". Seules les extensions "{extensions}" sont autorisées.',
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
        msgUploadAborted: 'Le transfert du fichier a été interrompu',
        msgUploadThreshold: 'En cours...',
        msgUploadBegin: 'Initialisation...',
        msgUploadEnd: 'Terminé',
        msgUploadResume: 'Resuming upload...',
        msgUploadEmpty: 'Aucune donnée valide disponible pour transmission.',
        msgUploadError: 'Upload Error',
        msgDeleteError: 'Delete Error',
        msgProgressError: 'Erreur',
        msgValidationError: 'Erreur de validation',
        msgLoading: 'Transmission du fichier {index} sur {files}&hellip;',
        msgProgress: 'Transmission du fichier {index} sur {files} - {name} - {percent}%.',
        msgSelected: '{n} {files} sélectionné(s)',
        msgFoldersNotAllowed: 'Glissez et déposez uniquement des fichiers ! {n} répertoire(s) exclu(s).',
        msgImageWidthSmall: 'La largeur de l\'image "{name}" doit être d\'au moins {size} px.',
        msgImageHeightSmall: 'La hauteur de l\'image "{name}" doit être d\'au moins {size} px.',
        msgImageWidthLarge: 'La largeur de l\'image "{name}" ne peut pas dépasser {size} px.',
        msgImageHeightLarge: 'La hauteur de l\'image "{name}" ne peut pas dépasser {size} px.',
        msgImageResizeError: "Impossible d'obtenir les dimensions de l'image à redimensionner.",
        msgImageResizeException: "Erreur lors du redimensionnement de l'image.<pre>{errors}</pre>",
        msgAjaxError: "Une erreur s'est produite pendant l'opération de {operation}. Veuillez réessayer plus tard.",
        msgAjaxProgressError: 'L\'opération "{operation}" a échoué',
        msgDuplicateFile: 'File "{name}" of same size "{size} KB" has already been selected earlier. Skipping duplicate selection.',
        msgResumableUploadRetriesExceeded:  'Upload aborted beyond <b>{max}</b> retries for file <b>{file}</b>! Error Details: <pre>{error}</pre>',
        msgPendingTime: '{time} remaining',
        msgCalculatingTime: 'calculating time remaining',
        ajaxOperations: {
            deleteThumb: 'suppression du fichier',
            uploadThumb: 'transfert du fichier',
            uploadBatch: 'transfert des fichiers',
            uploadExtra: 'soumission des données de formulaire'
        },
        dropZoneTitle: 'Glissez et déposez les fichiers ici&hellip;',
        dropZoneClickTitle: '<br>(ou cliquez pour sélectionner manuellement)',
        fileActionSettings: {
            removeTitle: 'Supprimer le fichier',
            uploadTitle: 'Transférer le fichier',
            uploadRetryTitle: 'Relancer le transfert',
            zoomTitle: 'Voir les détails',
            dragTitle: 'Déplacer / Réarranger',
            indicatorNewTitle: 'Pas encore transféré',
            indicatorSuccessTitle: 'Posté',
            indicatorErrorTitle: 'Ajouter erreur',
            indicatorPausedTitle: 'Upload Paused',
            indicatorLoadingTitle:  'En cours...'
        },
        previewZoomButtonTitles: {
            prev: 'Voir le fichier précédent',
            next: 'Voir le fichier suivant',
            toggleheader: 'Masquer le titre',
            fullscreen: 'Mode plein écran',
            borderless: 'Mode cinéma',
            close: "Fermer l'aperçu"
        }
    };
})(window.jQuery);

/*!
 * FileInput Portuguese Translations
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

    $.fn.fileinputLocales['pt'] = {
        fileSingle: 'ficheiro',
        filePlural: 'ficheiros',
        browseLabel: 'Procurar &hellip;',
        removeLabel: 'Remover',
        removeTitle: 'Remover ficheiros seleccionados',
        cancelLabel: 'Cancelar',
        cancelTitle: 'Abortar carregamento ',
        uploadLabel: 'Carregar',
        uploadTitle: 'Carregar ficheiros seleccionados',
        msgNo: 'Não',
        msgCancelled: 'Cancelado',
        msgZoomTitle: 'Ver detalhes',
        msgZoomModalHeading: 'Pré-visualização detalhada',
        msgSizeTooLarge: 'Ficheiro "{name}" (<b>{size} KB</b>) excede o tamanho máximo permido de <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Deve seleccionar pelo menos <b>{n}</b> {files} para fazer upload.',
        msgFilesTooMany: 'Número máximo de ficheiros seleccionados <b>({n})</b> excede o limite máximo de <b>{m}</b>.',
        msgFileNotFound: 'Ficheiro "{name}" não encontrado!',
        msgFileSecured: 'Restrições de segurança preventem a leitura do ficheiro "{name}".',
        msgFileNotReadable: 'Ficheiro "{name}" não pode ser lido.',
        msgFilePreviewAborted: 'Pré-visualização abortado para o ficheiro "{name}".',
        msgFilePreviewError: 'Ocorreu um erro ao ler o ficheiro "{name}".',
        msgInvalidFileType: 'Tipo inválido para o ficheiro "{name}". Apenas ficheiros "{types}" são suportados.',
        msgInvalidFileExtension: 'Extensão inválida para o ficheiro "{name}". Apenas ficheiros "{extensions}" são suportados.',
        msgUploadAborted: 'O upload do arquivo foi abortada',
        msgValidationError: 'Erro de validação',
        msgLoading: 'A carregar ficheiro {index} de {files} &hellip;',
        msgProgress: 'A carregar ficheiro {index} de {files} - {name} - {percent}% completo.',
        msgSelected: '{n} {files} seleccionados',
        msgFoldersNotAllowed: 'Arrastar e largar ficheiros apenas! {n} pasta(s) ignoradas.',
        msgImageWidthSmall: 'Largura do arquivo de imagem "{name}" deve ser pelo menos {size} px.',
        msgImageHeightSmall: 'Altura do arquivo de imagem "{name}" deve ser pelo menos {size} px.',
        msgImageWidthLarge: 'Largura do arquivo de imagem "{name}" não pode exceder {size} px.',
        msgImageHeightLarge: 'Altura do arquivo de imagem "{name}" não pode exceder {size} px.',
        msgImageResizeError: 'Could not get the image dimensions to resize.',
        msgImageResizeException: 'Erro ao redimensionar a imagem.<pre>{errors}</pre>',
        dropZoneTitle: 'Arrastar e largar ficheiros aqui &hellip;',
        fileActionSettings: {
            removeTitle: 'Remover arquivo',
            uploadTitle: 'Carregar arquivo',
            indicatorNewTitle: 'Ainda não carregou',
            indicatorSuccessTitle: 'Carregado',
            indicatorErrorTitle: 'Carregar Erro',
            indicatorLoadingTitle: 'A carregar ...'
        }
    };
})(window.jQuery);
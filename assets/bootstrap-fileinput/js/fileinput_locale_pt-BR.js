/*!
 * FileInput Brazillian Portuguese Translations
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

    $.fn.fileinputLocales['pt-BR'] = {
        fileSingle: 'arquivo',
        filePlural: 'arquivos',
        browseLabel: 'Procurar&hellip;',
        removeLabel: 'Remover',
        removeTitle: 'Remover arquivos selecionados',
        cancelLabel: 'Cancelar',
        cancelTitle: 'Interromper envio em andamento',
        uploadLabel: 'Enviar',
        uploadTitle: 'Enviar arquivos selecionados',
        msgNo: 'Não',
        msgCancelled: 'Cancelado',
        msgZoomTitle: 'Ver detalhes',
        msgZoomModalHeading: 'Pré-visualização detalhada',
        msgSizeTooLarge: 'O arquivo "{name}" (<b>{size} KB</b>) excede o tamanho máximo permitido de <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Você deve selecionar pelo menos <b>{n}</b> {files} para enviar.',
        msgFilesTooMany: 'O número de arquivos selecionados para o envio <b>({n})</b> excede o limite máximo permitido de <b>{m}</b>.',
        msgFileNotFound: 'O arquivo "{name}" não foi encontrado!',
        msgFileSecured: 'Restrições de segurança impedem a leitura do arquivo "{name}".',
        msgFileNotReadable: 'O arquivo "{name}" não pode ser lido.',
        msgFilePreviewAborted: 'A pré-visualização do arquivo "{name}" foi interrompida.',
        msgFilePreviewError: 'Ocorreu um erro ao ler o arquivo "{name}".',
        msgInvalidFileType: 'Tipo inválido para o arquivo "{name}". Apenas arquivos "{types}" são permitidos.',
        msgInvalidFileExtension: 'Extensão inválida para o arquivo "{name}". Apenas arquivos "{extensions}" são permitidos.',
        msgUploadAborted: 'O upload do arquivo foi abortada',
        msgValidationError: 'Erro de validação',
        msgLoading: 'Enviando arquivo {index} de {files}&hellip;',
        msgProgress: 'Enviando arquivo {index} de {files} - {name} - {percent}% completo.',
        msgSelected: '{n} {files} selecionado(s)',
        msgFoldersNotAllowed: 'Arraste e solte apenas arquivos! {n} soltar pasta(s) ignoradas.',
        msgImageWidthSmall: 'Largura do arquivo de imagem "{name}" deve ser pelo menos {size} px.',
        msgImageHeightSmall: 'Altura do arquivo de imagem "{name}" deve ser pelo menos {size} px.',
        msgImageWidthLarge: 'Largura do arquivo de imagem "{name}" não pode exceder {size} px.',
        msgImageHeightLarge: 'Altura do arquivo de imagem "{name}" não pode exceder {size} px.',
        msgImageResizeError: 'Could not get the image dimensions to resize.',
        msgImageResizeException: 'Erro ao redimensionar a imagem.<pre>{errors}</pre>',
        dropZoneTitle: 'Arraste e solte os arquivos aqui&hellip;',
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
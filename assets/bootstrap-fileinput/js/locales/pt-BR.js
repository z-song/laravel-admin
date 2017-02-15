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
        msgNoFilesSelected: '',
        msgCancelled: 'Cancelado',
        msgZoomModalHeading: 'Pré-visualização detalhada',
        msgSizeTooSmall: 'File "{name}" (<b>{size} KB</b>) is too small and must be larger than <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'O arquivo "{name}" (<b>{size} KB</b>) excede o tamanho máximo permitido de <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Você deve selecionar pelo menos <b>{n}</b> {files} para enviar.',
        msgFilesTooMany: 'O número de arquivos selecionados para o envio <b>({n})</b> excede o limite máximo permitido de <b>{m}</b>.',
        msgFileNotFound: 'O arquivo "{name}" não foi encontrado!',
        msgFileSecured: 'Restrições de segurança impedem a leitura do arquivo "{name}".',
        msgFileNotReadable: 'O arquivo "{name}" não pode ser lido.',
        msgFilePreviewAborted: 'A pré-visualização do arquivo "{name}" foi interrompida.',
        msgFilePreviewError: 'Ocorreu um erro ao ler o arquivo "{name}".',
        msgInvalidFileName: 'Invalid or unsupported characters in file name "{name}".',
        msgInvalidFileType: 'Tipo inválido para o arquivo "{name}". Apenas arquivos "{types}" são permitidos.',
        msgInvalidFileExtension: 'Extensão inválida para o arquivo "{name}". Apenas arquivos "{extensions}" são permitidos.',
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
        msgUploadAborted: 'O envio do arquivo foi abortado',
        msgUploadThreshold: 'Processando...',
        msgValidationError: 'Erro de validação',
        msgLoading: 'Enviando arquivo {index} de {files}&hellip;',
        msgProgress: 'Enviando arquivo {index} de {files} - {name} - {percent}% completo.',
        msgSelected: '{n} {files} selecionado(s)',
        msgFoldersNotAllowed: 'Arraste e solte apenas arquivos! {n} pasta(s) ignoradas.',
        msgImageWidthSmall: 'Largura do arquivo de imagem "{name}" deve ser pelo menos {size} px.',
        msgImageHeightSmall: 'Altura do arquivo de imagem "{name}" deve ser pelo menos {size} px.',
        msgImageWidthLarge: 'Largura do arquivo de imagem "{name}" não pode exceder {size} px.',
        msgImageHeightLarge: 'Altura do arquivo de imagem "{name}" não pode exceder {size} px.',
        msgImageResizeError: 'Não foi possível obter as dimensões da imagem para redimensionar.',
        msgImageResizeException: 'Erro ao redimensionar a imagem.<pre>{errors}</pre>',
        msgAjaxError: 'Something went wrong with the {operation} operation. Please try again later!',
        msgAjaxProgressError: '{operation} failed',
        ajaxOperations: {
            deleteThumb: 'file delete',
            uploadThumb: 'single file upload',
            uploadBatch: 'batch file upload',
            uploadExtra: 'form data upload'
        },
        dropZoneTitle: 'Arraste e solte os arquivos aqui&hellip;',
        dropZoneClickTitle: '<br>(ou clique para selecionar o(s) arquivo(s))',
        fileActionSettings: {
            removeTitle: 'Remover arquivo',
            uploadTitle: 'Enviar arquivo',
            zoomTitle: 'Ver detalhes',
            dragTitle: 'Mover / Reordenar',
            indicatorNewTitle: 'Ainda não enviado',
            indicatorSuccessTitle: 'Enviado',
            indicatorErrorTitle: 'Erro',
            indicatorLoadingTitle: 'A enviar ...'
        },
        previewZoomButtonTitles: {
            prev: 'Visualizar arquivo anterior',
            next: 'Visualizar próximo arquivo',
            toggleheader: 'Mostrar cabeçalho',
            fullscreen: 'Ativar tela cheia',
            borderless: 'Ativar modo sem borda',
            close: 'Fechar pré-visualização detalhada'
        }
    };
})(window.jQuery);
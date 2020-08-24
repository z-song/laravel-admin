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
        browseLabel: 'Procurar &hellip;',
        removeLabel: 'Remover',
        removeTitle: 'Remover arquivos selecionados',
        cancelLabel: 'Cancelar',
        cancelTitle: 'Interromper envio em andamento',
        pauseLabel: 'Pausa',
        pauseTitle: 'Pausa do envio',
        uploadLabel: 'Enviar',
        uploadTitle: 'Enviar arquivos selecionados',
        msgNo: 'Não',
        msgNoFilesSelected: 'Nenhum arquivo selecionado',
        msgPaused: 'Pausado',
        msgCancelled: 'Cancelado',
        msgPlaceholder: 'Selecionar {files} ...',
        msgZoomModalHeading: 'Pré-visualização detalhada',
        msgFileRequired: 'Você deve selecionar um arquivo para enviar.',
        msgSizeTooSmall: 'O arquivo "{name}" (<b>{size} KB</b>) é muito pequeno e deve ser maior que <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'O arquivo "{name}" (<b>{size} KB</b>) excede o tamanho máximo permitido de <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Você deve selecionar pelo menos <b>{n}</b> {files} para enviar.',
        msgFilesTooMany: 'O número de arquivos selecionados para o envio <b>({n})</b> excede o limite máximo permitido de <b>{m}</b>.',
        msgTotalFilesTooMany: 'Pode enviar no máximo <b>{m}</b> arquivos (<b>{n}</b> arquivos detetados).',
        msgFileNotFound: 'O arquivo "{name}" não foi encontrado!',
        msgFileSecured: 'Restrições de segurança impedem a leitura do arquivo "{name}".',
        msgFileNotReadable: 'O arquivo "{name}" não pode ser lido.',
        msgFilePreviewAborted: 'A pré-visualização do arquivo "{name}" foi interrompida.',
        msgFilePreviewError: 'Ocorreu um erro ao ler o arquivo "{name}".',
        msgInvalidFileName: 'Caracteres inválidos ou não suportados no arquivo "{name}".',
        msgInvalidFileType: 'Tipo inválido para o arquivo "{name}". Apenas arquivos "{types}" são permitidos.',
        msgInvalidFileExtension: 'Extensão inválida para o arquivo "{name}". Apenas arquivos "{extensions}" são permitidos.',
        msgFileTypes: {
            'image': 'imagem',
            'html': 'HTML',
            'text': 'texto',
            'video': 'vídeo',
            'audio': 'audio',
            'flash': 'flash',
            'pdf': 'PDF',
            'object': 'objeto'
        },
        msgUploadAborted: 'O envio do arquivo foi abortado',
        msgUploadThreshold: 'Processando &hellip;',
        msgUploadBegin: 'Inicializando &hellip;',
        msgUploadEnd: 'Concluído',
        msgUploadResume: 'Retomando envio &hellip;',
        msgUploadEmpty: 'Nenhuma informação válida para upload.',
        msgUploadError: 'Erro de Envio',
        msgDeleteError: 'Erro ao Deletar',
        msgProgressError: 'Erro de Envio',
        msgValidationError: 'Erro de validação',
        msgLoading: 'Enviando arquivo {index} de {files} &hellip;',
        msgProgress: 'Enviando arquivo {index} de {files} - {name} - {percent}% completo.',
        msgSelected: '{n} {files} selecionado(s)',
        msgFoldersNotAllowed: 'Arraste e solte apenas arquivos! {n} pasta(s) ignoradas.',
        msgImageWidthSmall: 'Largura do arquivo de imagem "{name}" deve ser pelo menos {size} px.',
        msgImageHeightSmall: 'Altura do arquivo de imagem "{name}" deve ser pelo menos {size} px.',
        msgImageWidthLarge: 'Largura do arquivo de imagem "{name}" não pode exceder {size} px.',
        msgImageHeightLarge: 'Altura do arquivo de imagem "{name}" não pode exceder {size} px.',
        msgImageResizeError: 'Não foi possível obter as dimensões da imagem para redimensionar.',
        msgImageResizeException: 'Erro ao redimensionar a imagem.<pre>{errors}</pre>',
        msgAjaxError: 'Algo deu errado com a operação {operation}. Por favor tente novamente mais tarde!',
        msgAjaxProgressError: '{operation} falhou',
        msgDuplicateFile: 'O arquivo "{name}" do mesmo tamanho "{size} KB" já foi selecionado. Ignorando a seleção duplicada.',
        msgResumableUploadRetriesExceeded:  'Envio abortado, excedido <b>{max}</b> tentativas para o arquivo <b>{file}</b>! Detalhes do erro: <pre>{error}</pre>',
        msgPendingTime: '{time} restante',
        msgCalculatingTime: 'calculando o tempo restante',
        ajaxOperations: {
            deleteThumb: 'Exclusão de arquivo',
            uploadThumb: 'Upload de arquivos',
            uploadBatch: 'Carregamento de arquivos em lote',
            uploadExtra: 'Carregamento de dados do formulário'
        },
        dropZoneTitle: 'Arraste e solte os arquivos aqui &hellip;',
        dropZoneClickTitle: '<br>(ou clique para selecionar o(s) arquivo(s))',
        fileActionSettings: {
            removeTitle: 'Remover arquivo',
            uploadTitle: 'Enviar arquivo',
            uploadRetryTitle: 'Repetir envio',
            downloadTitle: 'Baixar arquivo',
            zoomTitle: 'Ver detalhes',
            dragTitle: 'Mover / Reordenar',
            indicatorNewTitle: 'Ainda não enviado',
            indicatorSuccessTitle: 'Enviado',
            indicatorErrorTitle: 'Erro',
            indicatorPausedTitle: 'Envio pausado',
            indicatorLoadingTitle:  'Enviando &hellip;'
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

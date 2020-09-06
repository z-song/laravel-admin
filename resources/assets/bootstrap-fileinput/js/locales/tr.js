/*!
 * FileInput Turkish Translations
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

    $.fn.fileinputLocales['tr'] = {
        fileSingle: 'dosya',
        filePlural: 'dosyalar',
        browseLabel: 'Gözat &hellip;',
        removeLabel: 'Sil',
        removeTitle: 'Seçilen dosyaları sil',
        cancelLabel: 'İptal',
        cancelTitle: 'Devam eden yüklemeyi iptal et',
        pauseLabel: 'Pause',
        pauseTitle: 'Pause ongoing upload',
        uploadLabel: 'Yükle',
        uploadTitle: 'Seçilen dosyaları yükle',
        msgNo: 'Hayır',
        msgNoFilesSelected: '',
        msgPaused: 'Paused',
        msgCancelled: 'İptal edildi',
        msgPlaceholder: 'Seçilen {files} ...',
        msgZoomModalHeading: 'Detaylı Önizleme',
        msgFileRequired: 'Yüklemek için bir dosya seçmelisiniz.',
        msgSizeTooSmall: '"{name}"(<b>{size} KB</b>) dosyası çok küçük  ve <b>{minSize} KB</b> boyutundan büyük olmalıdır.',
        msgSizeTooLarge: '"{name}" dosyasının boyutu (<b>{size} KB</b>) izin verilen azami dosya boyutu olan <b>{maxSize} KB</b>\'tan büyük.',
        msgFilesTooLess: 'Yüklemek için en az <b>{n}</b> {files} dosya seçmelisiniz.',
        msgFilesTooMany: 'Yüklemek için seçtiğiniz dosya sayısı <b>({n})</b> azami limitin <b>({m})</b> altında olmalıdır.',
        msgTotalFilesTooMany: 'You can upload a maximum of <b>{m}</b> files (<b>{n}</b> files detected).',
        msgFileNotFound: '"{name}" dosyası bulunamadı!',
        msgFileSecured: 'Güvenlik kısıtlamaları "{name}" dosyasının okunmasını engelliyor.',
        msgFileNotReadable: '"{name}" dosyası okunabilir değil.',
        msgFilePreviewAborted: '"{name}" dosyası için önizleme iptal edildi.',
        msgFilePreviewError: '"{name}" dosyası okunurken bir hata oluştu.',
        msgInvalidFileName: '"{name}" dosya adında geçersiz veya desteklenmeyen karakterler var.',
        msgInvalidFileType: '"{name}" dosyasının türü geçerli değil. Yalnızca "{types}" türünde dosyalara izin veriliyor.',
        msgInvalidFileExtension: '"{name}" dosyasının uzantısı geçersiz. Yalnızca "{extensions}" uzantılı dosyalara izin veriliyor.',
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
        msgUploadAborted: 'Dosya yükleme iptal edildi',
        msgUploadThreshold: 'İşlem yapılıyor &hellip;',
        msgUploadBegin: 'Başlıyor &hellip;',
        msgUploadEnd: 'Başarılı',
        msgUploadResume: 'Resuming upload &hellip;',
        msgUploadEmpty: 'Yüklemek için geçerli veri mevcut değil.',
        msgUploadError: 'Upload Error',
        msgDeleteError: 'Delete Error',
        msgProgressError: 'Hata',
        msgValidationError: 'Doğrulama Hatası',
        msgLoading: 'Dosya yükleniyor {index} / {files} &hellip;',
        msgProgress: 'Dosya yükleniyor {index} / {files} - {name} - %{percent} tamamlandı.',
        msgSelected: '{n} {files} seçildi',
        msgFoldersNotAllowed: 'Yalnızca dosyaları sürükleyip bırakabilirsiniz! {n} dizin(ler) göz ardı edildi.',
        msgImageWidthSmall: '"{name}" adlı görüntü dosyasının genişliği en az {size} piksel olmalıdır.',
        msgImageHeightSmall: '"{name}" adlı görüntü dosyasının yüksekliği en az {size} piksel olmalıdır.',
        msgImageWidthLarge: '"{name}" adlı görüntü dosyasının genişliği {size} pikseli geçemez.',
        msgImageHeightLarge: '"{name}" adlı görüntü dosyasının yüksekliği {size} pikseli geçemez.',
        msgImageResizeError: 'Görüntü boyutlarını yeniden boyutlandıramadı.',
        msgImageResizeException: 'Görüntü boyutlandırma sırasında hata.<pre>{errors}</pre>',
        msgAjaxError: '{operation} işlemi ile ilgili bir şeyler ters gitti. Lütfen daha sonra tekrar deneyiniz!',
        msgAjaxProgressError: '{operation} işlemi başarısız oldu.',
        msgDuplicateFile: 'File "{name}" of same size "{size} KB" has already been selected earlier. Skipping duplicate selection.',
        msgResumableUploadRetriesExceeded:  'Upload aborted beyond <b>{max}</b> retries for file <b>{file}</b>! Error Details: <pre>{error}</pre>',
        msgPendingTime: '{time} remaining',
        msgCalculatingTime: 'calculating time remaining',
        ajaxOperations: {
            deleteThumb: 'dosya silme',
            uploadThumb: 'dosya yükleme',
            uploadBatch: 'toplu dosya yükleme',
            uploadExtra: 'form verisi yükleme'
        },
        dropZoneTitle: 'Dosyaları buraya sürükleyip bırakın',
        dropZoneClickTitle: '<br>(ya da {files} seçmek için tıklayınız)',
        fileActionSettings: {
            removeTitle: 'Dosyayı kaldır',
            uploadTitle: 'Dosyayı yükle',
            uploadRetryTitle: 'Retry upload',
            zoomTitle: 'Ayrıntıları görüntüle',
            dragTitle: 'Taşı / Yeniden düzenle',
            indicatorNewTitle: 'Henüz yüklenmedi',
            indicatorSuccessTitle: 'Yüklendi',
            indicatorErrorTitle: 'Yükleme Hatası',
            indicatorPausedTitle: 'Upload Paused',
            indicatorLoadingTitle:  'Yükleniyor &hellip;'
        },
        previewZoomButtonTitles: {
            prev: 'Önceki dosyayı göster',
            next: 'Sonraki dosyayı göster',
            toggleheader: 'Üst bilgi geçiş',
            fullscreen: 'Tam ekran geçiş',
            borderless: 'Çerçevesiz moda geçiş',
            close: 'Detaylı önizlemeyi kapat'
        }
    };
})(window.jQuery);

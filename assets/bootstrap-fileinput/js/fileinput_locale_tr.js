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
        uploadLabel: 'Yükle',
        uploadTitle: 'Seçilen dosyaları yükle',
        msgNo: 'Hayır',
        msgCancelled: 'Iptal edildi',
        msgZoomTitle: 'Ayrıntıları görüntüle',
        msgZoomModalHeading: 'Detaylı Önizleme',
        msgSizeTooLarge: '"{name}" dosyasının boyutu (<b>{size} KB</b>) izin verilen azami dosya boyutu olan <b>{maxSize} KB</b>\'tan büyük.',
        msgFilesTooLess: 'Yüklemek için en az <b>{n}</b> {files} dosya seçmelisiniz.',
        msgFilesTooMany: 'Yüklemek için seçtiğiniz dosya sayısı <b>({n})</b> azami limitin <b>({m})</b> altında olmalıdır.',
        msgFileNotFound: '"{name}" dosyası bulunamadı!',
        msgFileSecured: 'Güvenlik kısıtlamaları "{name}" dosyasının okunmasını engelliyor.',
        msgFileNotReadable: '"{name}" dosyası okunabilir değil.',
        msgFilePreviewAborted: '"{name}" dosyası için önizleme iptal edildi.',
        msgFilePreviewError: '"{name}" dosyası okunurken bir hata oluştu.',
        msgInvalidFileType: '"{name}" dosyasının türü geçerli değil. Yalnızca "{types}" türünde dosyalara izin veriliyor.',
        msgInvalidFileExtension: '"{name}" dosyasının uzantısı geçersiz. Yalnızca "{extensions}" uzantılı dosyalara izin veriliyor.',
        msgUploadAborted: 'Dosya yükleme iptal edildi',
        msgValidationError: 'Doğrulama Hatası',
        msgLoading: 'Dosya yükleniyor {index} / {files} &hellip;',
        msgProgress: 'Dosya yükleniyor {index} / {files} - {name} - %{percent} tamamlandı.',
        msgSelected: '{n} {files} seçildi',
        msgFoldersNotAllowed: 'Yalnızca dosyaları sürükleyip bırakabilirsiniz! {n} dizin(ler) göz ardı edildi.',
        msgImageWidthSmall: '"{name}" adlı görüntü dosyasının genişliği en az {size} piksel olmalıdır.',
        msgImageHeightSmall: '"{name}" adlı görüntü dosyasının yüksekliği en az {size} piksel olmalıdır.',
        msgImageWidthLarge: '"{name}" adlı görüntü dosyasının genişliği {size} pikseli geçemez.',
        msgImageHeightLarge: '"{name}" adlı görüntü dosyasının yüksekliği {size} pikseli geçemez.',
        msgImageResizeError: 'Görüntü boyutlarını yeniden boyutlandırmak için alınamadı.',
        msgImageResizeException: 'Görsel boyutlandırma sırasında hata.<pre>{errors}</pre>',
        dropZoneTitle: 'Dosyaları buraya sürükleyip bırakın &hellip;',
        fileActionSettings: {
            removeTitle: 'Dosyayı kaldır',
            uploadTitle: 'Dosyayı yükle',
            indicatorNewTitle: 'Henüz yüklenemedi',
            indicatorSuccessTitle: 'Yüklendi',
            indicatorErrorTitle: 'Yükleme Hatası',
            indicatorLoadingTitle: 'Yükleniyor ...'
        }
    };
})(window.jQuery);

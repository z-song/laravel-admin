/*!
 * FileInput Indonesian Translations
 *
 * This file must be loaded after 'fileinput.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-fileinput
 * @author Bambang Riswanto <bamz3r@gmail.com>
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
(function ($) {
    "use strict";

    $.fn.fileinputLocales['id'] = {
        fileSingle: 'berkas',
        filePlural: 'berkas',
        browseLabel: 'Pilih berkas &hellip;',
        removeLabel: 'Hapus',
        removeTitle: 'Hapus berkas terpilih',
        cancelLabel: 'Batal',
        cancelTitle: 'Batalkan proses pengunggahan',
        pauseLabel: 'Pause',
        pauseTitle: 'Pause ongoing upload',
        uploadLabel: 'Unggah',
        uploadTitle: 'Unggah berkas terpilih',
        msgNo: 'Tidak',
        msgNoFilesSelected: '',
        msgPaused: 'Paused',
        msgCancelled: 'Dibatalkan',
        msgPlaceholder: 'Pilih {files} ...',
        msgZoomModalHeading: 'Pratinjau terperinci',
        msgFileRequired: 'Anda harus memilih berkas untuk diunggah.',
        msgSizeTooSmall: 'Berkas "{name}" (<b>{size} KB</b>) terlalu kecil dan harus lebih besar dari <b>{minSize} KB</b>.',
        msgSizeTooLarge: 'Berkas "{name}" (<b>{size} KB</b>) melebihi ukuran unggah maksimal yaitu <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Anda harus memilih setidaknya <b>{n}</b> {files} untuk diunggah.',
        msgFilesTooMany: '<b>({n})</b> berkas yang dipilih untuk diunggah melebihi ukuran unggah maksimal yaitu <b>{m}</b>.',
        msgTotalFilesTooMany: 'You can upload a maximum of <b>{m}</b> files (<b>{n}</b> files detected).',
        msgFileNotFound: 'Berkas "{name}" tak ditemukan!',
        msgFileSecured: 'Sistem keamanan mencegah untuk membaca berkas "{name}".',
        msgFileNotReadable: 'Berkas "{name}" tak dapat dibaca.',
        msgFilePreviewAborted: 'Pratinjau untuk berkas "{name}" dibatalkan.',
        msgFilePreviewError: 'Kesalahan saat membaca berkas "{name}".',
        msgInvalidFileName: 'Karakter tidak dikenali atau tidak didukung untuk nama berkas "{name}".',
        msgInvalidFileType: 'Jenis berkas "{name}" tidak sah. Hanya berkas "{types}" yang didukung.',
        msgInvalidFileExtension: 'Ekstensi berkas "{name}" tidak sah. Hanya ekstensi "{extensions}" yang didukung.',
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
        msgUploadAborted: 'Proses Unggah berkas dibatalkan',
        msgUploadThreshold: 'Memproses &hellip;',
        msgUploadBegin: 'Menyiapkan &hellip;',
        msgUploadEnd: 'Selesai',
        msgUploadResume: 'Resuming upload &hellip;',
        msgUploadEmpty: 'Tidak ada data valid yang tersedia untuk diunggah.',
        msgUploadError: 'Upload Error',
        msgDeleteError: 'Delete Error',
        msgProgressError: 'Kesalahan',
        msgValidationError: 'Kesalahan saat memvalidasi',
        msgLoading: 'Memuat {index} dari {files} berkas &hellip;',
        msgProgress: 'Memuat {index} dari {files} berkas - {name} - {percent}% selesai.',
        msgSelected: '{n} {files} dipilih',
        msgFoldersNotAllowed: 'Hanya tahan dan lepas file saja! {n} folder diabaikan.',
        msgImageWidthSmall: 'Lebar dari gambar "{name}" harus sekurangnya {size} px.',
        msgImageHeightSmall: 'Tinggi dari gambar "{name}" harus sekurangnya {size} px.',
        msgImageWidthLarge: 'Lebar dari gambar "{name}" tak boleh melebihi {size} px.',
        msgImageHeightLarge: 'Tinggi dari gambar "{name}" tak boleh melebihi {size} px.',
        msgImageResizeError: 'Tidak dapat menentukan dimensi gambar untuk mengubah ukuran.',
        msgImageResizeException: 'Kesalahan saat mengubah ukuran gambar.<pre>{errors}</pre>',
        msgAjaxError: 'Terjadi kesalahan ketika melakukan operasi {operation}. Silahkan coba lagi nanti!',
        msgAjaxProgressError: '{operation} gagal',
        msgDuplicateFile: 'File "{name}" of same size "{size} KB" has already been selected earlier. Skipping duplicate selection.',
        msgResumableUploadRetriesExceeded:  'Upload aborted beyond <b>{max}</b> retries for file <b>{file}</b>! Error Details: <pre>{error}</pre>',
        msgPendingTime: '{time} remaining',
        msgCalculatingTime: 'calculating time remaining',
        ajaxOperations: {
            deleteThumb: 'Hapus berkas',
            uploadThumb: 'Unggah berkas',
            uploadBatch: 'Unggah banyak berkas',
            uploadExtra: 'Unggah form ekstra'
        },
        dropZoneTitle: 'Tarik dan lepaskan berkas disini &hellip;',
        dropZoneClickTitle: '<br>(atau klik untuk memilih {files})',
        fileActionSettings: {
            removeTitle: 'Hapus Berkas',
            uploadTitle: 'Unggah Berkas',
            uploadRetryTitle: 'Unggah Ulang',
            downloadTitle: 'Unduh Berkas',
            zoomTitle: 'Tampilkan Rincian',
            dragTitle: 'Pindah atau Atur Ulang',
            indicatorNewTitle: 'Belum diunggah',
            indicatorSuccessTitle: 'Sudah diunggah',
            indicatorErrorTitle: 'Kesalahan dalam mengungah',
            indicatorPausedTitle: 'Upload Paused',
            indicatorLoadingTitle:  'Mengunggah &hellip;'
        },
        previewZoomButtonTitles: {
            prev: 'Lihat berkas sebelumnya',
            next: 'Lihat berkas selanjutnya',
            toggleheader: 'Beralih ke tajuk',
            fullscreen: 'Beralih ke mode penuh',
            borderless: 'Beralih ke mode tanpa tepi',
            close: 'Tutup pratinjau terperinci'
        }
    };
})(window.jQuery);

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
        browseLabel: 'Pilih File &hellip;',
        removeLabel: 'Hapus',
        removeTitle: 'Hapus berkas terpilih',
        cancelLabel: 'Batal',
        cancelTitle: 'Batalkan proses pengunggahan',
        uploadLabel: 'Unggah',
        uploadTitle: 'Unggah berkas terpilih',
        msgNo: 'Tidak',
        msgCancelled: 'Dibatalkan',
        msgZoomTitle: 'Tampilkan Rincian',
        msgZoomModalHeading: 'Pratinjau terperinci',
        msgSizeTooLarge: 'Berkas "{name}" (<b>{size} KB</b>) melebihi ukuran upload maksimal yaitu <b>{maxSize} KB</b>.',
        msgFilesTooLess: 'Anda harus memilih setidaknya <b>{n}</b> {files} untuk diunggah.',
        msgFilesTooMany: '<b>({n})</b> berkas yang dipilih untuk diunggah melebihi ukuran upload maksimal yaitu <b>{m}</b>.',
        msgFileNotFound: 'Berkas "{name}" tak ditemukan!',
        msgFileSecured: 'Sistem keamanan mencegah untuk membaca berkas "{name}".',
        msgFileNotReadable: 'Berkas "{name}" tak dapat dibaca.',
        msgFilePreviewAborted: 'Pratinjau untuk berkas "{name}" dibatalkan.',
        msgFilePreviewError: 'Kesalahan saat membaca berkas "{name}".',
        msgInvalidFileType: 'Jenis berkas "{name}" tidak sah. Hanya berkas "{types}" yang didukung.',
        msgInvalidFileExtension: 'Ekstensi berkas "{name}" tidak sah. Hanya ekstensi "{extensions}" yang didukung.',
        msgUploadAborted: 'Pengunggahan berkas dibatalkan',
        msgValidationError: 'Kesalahan validasi',
        msgLoading: 'Memuat {index} dari {files} berkas &hellip;',
        msgProgress: 'Memuat {index} dari {files} berkas - {name} - {percent}% selesai.',
        msgSelected: '{n} {files} dipilih',
        msgFoldersNotAllowed: 'Hanya tahan dan lepas file saja! {n} folder diabaikan.',
        msgImageWidthSmall: 'Lebar dari gambar "{name}" harus sekurangnya {size} px.',
        msgImageHeightSmall: 'Tinggi dari gambar "{name}" harus sekurangnya {size} px.',
        msgImageWidthLarge: 'Lebar dari gambar "{name}" tak boleh melebihi {size} px.',
        msgImageHeightLarge: 'Tinggi dari gambar "{name}" tak boleh melebihi {size} px.',
        msgImageResizeError: 'Tak dapat menentukan dimensi gambar untuk mengubah ukuran.',
        msgImageResizeException: 'Kesalahan saat mengubah ukuran gambar.<pre>{errors}</pre>',
        dropZoneTitle: 'Tarik dan lepaskan berkas disini &hellip;',
        fileActionSettings: {
            removeTitle: 'Hapus berkas',
            uploadTitle: 'Unggah berkas',
            indicatorNewTitle: 'Belum diunggah',
            indicatorSuccessTitle: 'Sudah diunggah',
            indicatorErrorTitle: 'Kesalahan pengunggahan',
            indicatorLoadingTitle: 'Mengunggah ...'
        }
    };
})(window.jQuery);

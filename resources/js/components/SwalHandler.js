/**
 * SweetAlert2 için global handler.
 * Projede standart bir şekilde SweetAlert2 kullanımını sağlar.
 */
import Swal from 'sweetalert2';

const SwalHandler = {
    /**
     * Silme onayı için SweetAlert2 modali gösterir
     * 
     * @param {Function} callback - Onay verildiğinde çalışacak fonksiyon
     * @param {String} title - Modal başlığı (opsiyonel)
     * @param {String} text - Modal metni (opsiyonel)
     */
    confirmDelete: function(callback, title = null, text = null) {
        Swal.fire({
            title: title || window.trans.are_you_sure,
            text: text || window.trans.record_delete_confirm,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: window.trans.yes_delete,
            cancelButtonText: window.trans.cancel
        }).then((result) => {
            if (result.isConfirmed) {
                // Callback fonksiyonunu çalıştır (form submit işlemi vb.)
                if (typeof callback === 'function') {
                    callback();
                }
            }
        });
    },

    /**
     * Genel onay için SweetAlert2 modali gösterir
     * 
     * @param {Function} callback - Onay verildiğinde çalışacak fonksiyon
     * @param {Object} options - Özelleştirme seçenekleri
     */
    confirm: function(callback, options = {}) {
        const defaultOptions = {
            title: window.trans.are_you_sure,
            text: window.trans.confirm_action,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: window.trans.yes_confirm,
            cancelButtonText: window.trans.cancel
        };

        const swalOptions = { ...defaultOptions, ...options };

        Swal.fire(swalOptions).then((result) => {
            if (result.isConfirmed) {
                if (typeof callback === 'function') {
                    callback();
                }
            }
        });
    },

    /**
     * Başarı mesajı için SweetAlert2 modali gösterir
     * 
     * @param {String} title - Modal başlığı
     * @param {String} text - Modal metni (opsiyonel)
     */
    success: function(title, text = '') {
        Swal.fire({
            title: title,
            text: text,
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: window.trans.ok
        });
    },

    /**
     * Hata mesajı için SweetAlert2 modali gösterir
     * 
     * @param {String} title - Modal başlığı
     * @param {String} text - Modal metni (opsiyonel)
     */
    error: function(title, text = '') {
        Swal.fire({
            title: title,
            text: text,
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: window.trans.ok
        });
    },

    /**
     * Bilgi mesajı için SweetAlert2 modali gösterir
     * 
     * @param {String} title - Modal başlığı
     * @param {String} text - Modal metni (opsiyonel)
     */
    info: function(title, text = '') {
        Swal.fire({
            title: title,
            text: text,
            icon: 'info',
            confirmButtonColor: '#3085d6',
            confirmButtonText: window.trans.ok
        });
    },

    /**
     * Uyarı mesajı için SweetAlert2 modali gösterir
     * 
     * @param {String} title - Modal başlığı
     * @param {String} text - Modal metni (opsiyonel)
     */
    warning: function(title, text = '') {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: window.trans.ok
        });
    }
};

export default SwalHandler;
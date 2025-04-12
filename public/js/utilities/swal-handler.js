/**
 * SweetAlert2 Handler
 * Bu dosya SweetAlert2 bildirim işlemlerini tüm projede tutarlı kullanmamızı sağlar
 */
const SwalHandler = {
    // Varsayılan ayarlar
    defaultOptions: {
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        reverseButtons: true,
        focusConfirm: false,
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    },
    
    /**
     * Başarı mesajı gösterir
     * @param {string} message Mesaj
     * @param {string} title Başlık
     * @param {Object} options Opsiyonel parametreler
     * @returns {Promise} SweetAlert2 nesnesi
     */
    success: function(message, title = null, options = {}) {
        return Swal.fire({
            title: title || (window.trans ? window.trans.success : 'Başarılı'),
            html: message,
            icon: 'success',
            ...this.defaultOptions,
            ...options
        });
    },
    
    /**
     * Hata mesajı gösterir
     * @param {string} message Mesaj
     * @param {string} title Başlık
     * @param {Object} options Opsiyonel parametreler
     * @returns {Promise} SweetAlert2 nesnesi
     */
    error: function(message, title = null, options = {}) {
        return Swal.fire({
            title: title || (window.trans ? window.trans.error : 'Hata'),
            html: message,
            icon: 'error',
            ...this.defaultOptions,
            ...options
        });
    },
    
    /**
     * Uyarı mesajı gösterir
     * @param {string} message Mesaj
     * @param {string} title Başlık
     * @param {Object} options Opsiyonel parametreler
     * @returns {Promise} SweetAlert2 nesnesi
     */
    warning: function(message, title = null, options = {}) {
        return Swal.fire({
            title: title || (window.trans ? window.trans.warning : 'Uyarı'),
            html: message,
            icon: 'warning',
            ...this.defaultOptions,
            ...options
        });
    },
    
    /**
     * Bilgi mesajı gösterir
     * @param {string} message Mesaj
     * @param {string} title Başlık
     * @param {Object} options Opsiyonel parametreler
     * @returns {Promise} SweetAlert2 nesnesi
     */
    info: function(message, title = null, options = {}) {
        return Swal.fire({
            title: title || (window.trans ? window.trans.info : 'Bilgi'),
            html: message,
            icon: 'info',
            ...this.defaultOptions,
            ...options
        });
    },
    
    /**
     * Soru mesajı gösterir
     * @param {string} message Mesaj
     * @param {string} title Başlık
     * @param {Object} options Opsiyonel parametreler
     * @returns {Promise} SweetAlert2 nesnesi
     */
    question: function(message, title = null, options = {}) {
        return Swal.fire({
            title: title || (window.trans ? window.trans.question : 'Soru'),
            html: message,
            icon: 'question',
            ...this.defaultOptions,
            ...options
        });
    },
    
    /**
     * Onay mesajı gösterir
     * @param {string} message Mesaj
     * @param {string} title Başlık
     * @param {Object} options Opsiyonel parametreler
     * @returns {Promise} SweetAlert2 nesnesi
     */
    confirm: function(message, title = null, options = {}) {
        return Swal.fire({
            title: title || (window.trans ? window.trans.are_you_sure : 'Emin misiniz?'),
            html: message || (window.trans ? window.trans.confirm_action : 'Bu işlemi onaylıyor musunuz?'),
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: window.trans ? window.trans.yes_confirm : 'Evet, onaylıyorum',
            cancelButtonText: window.trans ? window.trans.cancel : 'İptal',
            ...this.defaultOptions,
            ...options
        });
    },
    
    /**
     * Silme onay mesajı gösterir
     * @param {string} message Mesaj
     * @param {string} title Başlık
     * @param {Object} options Opsiyonel parametreler
     * @returns {Promise} SweetAlert2 nesnesi
     */
    delete: function(message, title = null, options = {}) {
        return Swal.fire({
            title: title || (window.trans ? window.trans.are_you_sure : 'Emin misiniz?'),
            html: message || (window.trans ? window.trans.record_delete_confirm : 'Bu kayıt kalıcı olarak silinecektir.'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: window.trans ? window.trans.yes_delete : 'Evet, sil',
            cancelButtonText: window.trans ? window.trans.cancel : 'İptal',
            ...this.defaultOptions,
            ...options
        });
    },

    /**
     * Loading mesajı gösterir
     * @param {string} message Mesaj
     * @param {string} title Başlık
     * @param {Object} options Opsiyonel parametreler
     * @returns {Promise} SweetAlert2 nesnesi
     */
    loading: function(message, title = null, options = {}) {
        return Swal.fire({
            title: title || (window.trans ? window.trans.please_wait : 'Lütfen bekleyiniz...'),
            html: message || (window.trans ? window.trans.loading : 'Yükleniyor...'),
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            },
            ...options
        });
    },

    /**
     * Loading mesajını kapatır
     */
    closeLoading: function() {
        Swal.close();
    },
    
    /**
     * Form dialog mesajı gösterir
     * @param {string|HTMLElement} formContent Form içeriği (HTML/DOM)
     * @param {string} title Başlık
     * @param {Object} options Opsiyonel parametreler
     * @returns {Promise} SweetAlert2 nesnesi
     */
    form: function(formContent, title = null, options = {}) {
        return Swal.fire({
            title: title || 'Form',
            html: formContent,
            showCancelButton: true,
            confirmButtonText: window.trans ? window.trans.save : 'Kaydet',
            cancelButtonText: window.trans ? window.trans.cancel : 'İptal',
            ...this.defaultOptions,
            ...options
        });
    },
    
    /**
     * Toast bildirim gösterir (sağ üst köşede küçük bildirim)
     * @param {string} message Mesaj
     * @param {string} icon İkon (success, error, warning, info, question)
     * @param {Object} options Opsiyonel parametreler
     * @returns {Promise} SweetAlert2 nesnesi
     */
    toast: function(message, icon = 'success', options = {}) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        
        return Toast.fire({
            icon: icon,
            title: message,
            ...options
        });
    }
};

// Form işlemleri için yardımcı fonksiyonlar
document.addEventListener('DOMContentLoaded', function() {
    // Delete butonları için otomatik işlem
    document.body.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('[data-confirm-delete]');
        if (deleteBtn) {
            e.preventDefault();
            
            const message = deleteBtn.dataset.message || null;
            const title = deleteBtn.dataset.title || null;
            const form = deleteBtn.closest('form');
            
            // Eğer buton bir form içindeyse ve formu göndermesi gerekiyorsa
            if (form) {
                SwalHandler.delete(message, title).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
            // Eğer buton data-url attribute'u içeriyorsa, o URL'yi fetch ile çağır
            else if (deleteBtn.dataset.url) {
                SwalHandler.delete(message, title).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteBtn.dataset.url;
                    }
                });
            }
        }
    });
    
    // Confirm butonları için otomatik işlem
    document.body.addEventListener('click', function(e) {
        const confirmBtn = e.target.closest('[data-confirm]');
        if (confirmBtn) {
            e.preventDefault();
            
            const message = confirmBtn.dataset.message || null;
            const title = confirmBtn.dataset.title || null;
            const url = confirmBtn.dataset.url || confirmBtn.getAttribute('href') || null;
            
            SwalHandler.confirm(message, title).then((result) => {
                if (result.isConfirmed) {
                    if (url) {
                        window.location.href = url;
                    }
                }
            });
        }
    });
});
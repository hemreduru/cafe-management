/**
 * Toast Handler
 * Bu dosya Toastr bildirim işlemlerini tüm projede tutarlı kullanmamızı sağlar
 */
const ToastHandler = {
    // Varsayılan ayarlar
    defaultOptions: {
        closeButton: true,
        debug: false,
        newestOnTop: true,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: true,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "5000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    },

    /**
     * Toast ayarlarını yapılandır
     * @param {Object} options Ayarlar
     * @returns {void}
     */
    config: function(options = {}) {
        toastr.options = { ...this.defaultOptions, ...options };
    },

    /**
     * Başarı toastr mesajı gösterir
     * @param {string} message Mesaj
     * @param {string} title Başlık
     * @param {Object} options Opsiyonel parametreler
     * @returns {void}
     */
    success: function(message, title = null, options = {}) {
        this.config(options);
        toastr.success(message, title || (window.trans ? window.trans.success : 'Başarılı'));
    },

    /**
     * Hata toastr mesajı gösterir
     * @param {string} message Mesaj
     * @param {string} title Başlık
     * @param {Object} options Opsiyonel parametreler
     * @returns {void}
     */
    error: function(message, title = null, options = {}) {
        this.config(options);
        toastr.error(message, title || (window.trans ? window.trans.error : 'Hata'));
    },

    /**
     * Uyarı toastr mesajı gösterir
     * @param {string} message Mesaj
     * @param {string} title Başlık
     * @param {Object} options Opsiyonel parametreler
     * @returns {void}
     */
    warning: function(message, title = null, options = {}) {
        this.config(options);
        toastr.warning(message, title || (window.trans ? window.trans.warning : 'Uyarı'));
    },

    /**
     * Bilgi toastr mesajı gösterir
     * @param {string} message Mesaj
     * @param {string} title Başlık
     * @param {Object} options Opsiyonel parametreler
     * @returns {void}
     */
    info: function(message, title = null, options = {}) {
        this.config(options);
        toastr.info(message, title || (window.trans ? window.trans.info : 'Bilgi'));
    },

    /**
     * Flash mesajlarını göster (session üzerinden gelen)
     * @returns {void}
     */
    showFlash: function() {
        const metaSuccess = document.querySelector('meta[name="session-success"]');
        const metaError = document.querySelector('meta[name="session-error"]');
        const metaWarning = document.querySelector('meta[name="session-warning"]');
        const metaInfo = document.querySelector('meta[name="session-info"]');

        if (metaSuccess) {
            this.success(metaSuccess.getAttribute('content'));
        }

        if (metaError) {
            this.error(metaError.getAttribute('content'));
        }

        if (metaWarning) {
            this.warning(metaWarning.getAttribute('content'));
        }

        if (metaInfo) {
            this.info(metaInfo.getAttribute('content'));
        }

        // Global değişkenlerden gelen flash mesajları
        if (window.flashMessages) {
            if (window.flashMessages.success) {
                this.success(window.flashMessages.success);
            }
            if (window.flashMessages.error) {
                this.error(window.flashMessages.error);
            }
            if (window.flashMessages.warning) {
                this.warning(window.flashMessages.warning);
            }
            if (window.flashMessages.info) {
                this.info(window.flashMessages.info);
            }
        }
    }
};

// Sayfa yüklendiğinde flash mesajlarını göster
document.addEventListener('DOMContentLoaded', function() {
    // Toastr varsayılan ayarlarını yapılandır
    ToastHandler.config();
    
    // Flash mesajlarını göster
    ToastHandler.showFlash();
});
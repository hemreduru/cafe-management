/**
 * Toastr bildirim kütüphanesi için global handler.
 * Projede standart bir şekilde bildirim gösterimi sağlar.
 */
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

// Varsayılan Toastr ayarları
const defaultOptions = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    preventDuplicates: false,
    timeOut: 5000,
    extendedTimeOut: 1000,
    showEasing: 'swing',
    hideEasing: 'linear',
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut'
};

// Toastr ayarlarını uygula
toastr.options = defaultOptions;

const ToastHandler = {
    /**
     * Başarı bildirimi gösterir
     * 
     * @param {String} message - Bildirim mesajı
     * @param {String} title - Bildirim başlığı (opsiyonel)
     * @param {Object} options - Özelleştirme seçenekleri (opsiyonel)
     */
    success: function(message, title = null, options = {}) {
        toastr.success(message, title || window.trans.success, options);
    },

    /**
     * Hata bildirimi gösterir
     * 
     * @param {String} message - Bildirim mesajı
     * @param {String} title - Bildirim başlığı (opsiyonel)
     * @param {Object} options - Özelleştirme seçenekleri (opsiyonel)
     */
    error: function(message, title = null, options = {}) {
        toastr.error(message, title || window.trans.error, options);
    },

    /**
     * Bilgi bildirimi gösterir
     * 
     * @param {String} message - Bildirim mesajı
     * @param {String} title - Bildirim başlığı (opsiyonel)
     * @param {Object} options - Özelleştirme seçenekleri (opsiyonel)
     */
    info: function(message, title = null, options = {}) {
        toastr.info(message, title || window.trans.info, options);
    },

    /**
     * Uyarı bildirimi gösterir
     * 
     * @param {String} message - Bildirim mesajı
     * @param {String} title - Bildirim başlığı (opsiyonel)
     * @param {Object} options - Özelleştirme seçenekleri (opsiyonel)
     */
    warning: function(message, title = null, options = {}) {
        toastr.warning(message, title || window.trans.warning, options);
    },

    /**
     * Bildirimleri temizler
     */
    clear: function() {
        toastr.clear();
    },

    /**
     * Toastr ayarlarını günceller
     * 
     * @param {Object} options - Yeni ayarlar
     */
    setOptions: function(options) {
        toastr.options = { ...defaultOptions, ...options };
    },

    /**
     * Session flash mesajlarını toastr bildirimleri olarak gösterir
     * Laravel flash mesajları için kullanılır
     */
    showFlashMessages: function() {
        if (window.flashMessages) {
            for (const type in window.flashMessages) {
                if (window.flashMessages.hasOwnProperty(type)) {
                    const messages = window.flashMessages[type];
                    
                    if (Array.isArray(messages)) {
                        messages.forEach(message => {
                            if (this[type]) {
                                this[type](message);
                            } else {
                                this.info(message);
                            }
                        });
                    } else if (typeof messages === 'string') {
                        if (this[type]) {
                            this[type](messages);
                        } else {
                            this.info(messages);
                        }
                    }
                }
            }
        }
    }
};

export default ToastHandler;
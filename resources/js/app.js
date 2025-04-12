import './bootstrap';
import SwalHandler from './components/SwalHandler';
import ToastHandler from './components/ToastHandler';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
window.SwalHandler = SwalHandler;
window.ToastHandler = ToastHandler;

// Dil çevirilerini global olarak tanımla
window.trans = window.translations || {};

// Sayfa yüklendiğinde çalışacak işlemler
document.addEventListener('DOMContentLoaded', () => {
    // Flash mesajları varsa göster (Laravel session flash mesajları)
    ToastHandler.showFlashMessages();
    
    // Tüm delete form butonları için SweetAlert2 onayı
    setupDeleteForms();

    // Tüm data-confirm-form butonları için SweetAlert2 onayı
    setupConfirmForms();
});

Alpine.start();

/**
 * Delete formları için SweetAlert2 onay kutusu ayarla
 */
function setupDeleteForms() {
    const deleteForms = document.querySelectorAll('form[data-confirm="delete"]');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const message = form.dataset.message || null;
            const title = form.dataset.title || null;
            
            SwalHandler.confirmDelete(
                function() {
                    form.submit();
                }, 
                title, 
                message
            );
        });
    });
}

/**
 * Onay gerektiren formlar için SweetAlert2 onay kutusu ayarla
 */
function setupConfirmForms() {
    const confirmForms = document.querySelectorAll('form[data-confirm="yes"], button[data-confirm="yes"], a[data-confirm="yes"]');
    
    confirmForms.forEach(element => {
        element.addEventListener(element.tagName === 'FORM' ? 'submit' : 'click', function(e) {
            e.preventDefault();
            
            const elem = this;
            const title = elem.dataset.title || null;
            const message = elem.dataset.message || null;
            const icon = elem.dataset.icon || 'question';
            
            const options = {
                title: title,
                text: message,
                icon: icon
            };
            
            SwalHandler.confirm(function() {
                if (elem.tagName === 'FORM') {
                    elem.submit();
                } else if (elem.tagName === 'A') {
                    window.location.href = elem.href;
                } else if (elem.tagName === 'BUTTON' && elem.form) {
                    elem.form.submit();
                } else if (elem.dataset.action === 'ajax' && elem.dataset.url) {
                    // AJAX işlemi yapılabilir
                    console.log('AJAX call to', elem.dataset.url);
                }
            }, options);
        });
    });
}

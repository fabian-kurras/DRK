/**
 * JavaScript - Interaktive Funktionen
 */

// Dokumentation bereit
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    initializeModal();
});

/**
 * Event Listener initialisieren
 */
function initializeEventListeners() {
    // Mobile Menu Toggle (falls implementiert)
    const menuToggle = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Form Validierung
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });
    
    // Smooth Scroll für Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
}

/**
 * Formular validieren
 */
function validateForm(form) {
    let isValid = true;
    
    form.querySelectorAll('[required]').forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('border-red-500');
            isValid = false;
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    // Email validieren
    const emailFields = form.querySelectorAll('input[type="email"]');
    emailFields.forEach(field => {
        if (!isValidEmail(field.value)) {
            field.classList.add('border-red-500');
            isValid = false;
        }
    });
    
    return isValid;
}

/**
 * Email validieren
 */
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Modal/Dialog Funktionalität
 */
function initializeModal() {
    // Modal öffnen
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    };
    
    // Modal schließen
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
    };
    
    // Close-Buttons mit data-close-modal Attribut
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', function() {
            const modalId = this.getAttribute('data-close-modal');
            closeModal(modalId);
        });
    });
    
    // Außerhalb klicken um zu schließen
    document.querySelectorAll('[role="dialog"]').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    });
}

/**
 * Toast/Notification anzeigen
 */
function showToast(message, type = 'info', duration = 3000) {
    const toast = document.createElement('div');
    const bgClass = type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500';
    
    toast.className = `fixed top-4 right-4 ${bgClass} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, duration);
}

/**
 * API Call Helper
 */
async function apiCall(url, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
        },
    };
    
    const mergedOptions = { ...defaultOptions, ...options };
    
    try {
        const response = await fetch(url, mergedOptions);
        
        if (!response.ok) {
            throw new Error(`HTTP Error: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('API Call Error:', error);
        showToast('Ein Fehler ist aufgetreten', 'error');
        throw error;
    }
}

/**
 * Datum formatieren (Client-side)
 */
function formatDate(date, format = 'de-DE') {
    const options = format === 'de-DE' ? {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    } : {};
    
    return new Date(date).toLocaleDateString(format, options);
}

/**
 * Bestätigung vor Aktion
 */
function confirmAction(message = 'Sind Sie sicher?') {
    return confirm(message);
}

// Export für externe Nutzung
window.DRK = {
    openModal,
    closeModal,
    showToast,
    apiCall,
    formatDate,
    confirmAction,
    validateForm
};

/**
 * Toastr Compatibility Layer
 * Laravel-admin UI Modernization
 * 
 * Provides backward compatibility for Toastr notifications
 */

/**
 * Toastr Configuration
 */
const ToastrConfig = {
    closeButton: true,
    debug: false,
    newestOnTop: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    preventDuplicates: false,
    onclick: null,
    showDuration: '300',
    hideDuration: '1000',
    timeOut: '5000',
    extendedTimeOut: '1000',
    showEasing: 'swing',
    hideEasing: 'linear',
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut'
};

/**
 * Toastr Compatibility Manager
 */
class ToastrCompatibility {
    constructor() {
        this.config = { ...ToastrConfig };
        this.container = null;
        this.toastCounter = 0;
        
        this.init();
    }
    
    init() {
        this.createContainer();
        this.setupGlobalToastr();
        
        console.info('Toastr compatibility layer initialized');
    }
    
    /**
     * Create toast container
     */
    createContainer() {
        if (document.getElementById('toast-container')) {
            this.container = document.getElementById('toast-container');
            return;
        }
        
        this.container = document.createElement('div');
        this.container.id = 'toast-container';
        this.container.className = this.config.positionClass;
        this.container.style.cssText = `
            position: fixed;
            z-index: 999999;
            pointer-events: none;
            transition: all 0.3s ease;
        `;
        
        this.setContainerPosition();
        document.body.appendChild(this.container);
    }
    
    /**
     * Set container position based on positionClass
     */
    setContainerPosition() {
        const positions = {
            'toast-top-right': { top: '12px', right: '12px' },
            'toast-top-left': { top: '12px', left: '12px' },
            'toast-top-center': { top: '12px', left: '50%', transform: 'translateX(-50%)' },
            'toast-top-full-width': { top: '0', left: '0', right: '0' },
            'toast-bottom-right': { bottom: '12px', right: '12px' },
            'toast-bottom-left': { bottom: '12px', left: '12px' },
            'toast-bottom-center': { bottom: '12px', left: '50%', transform: 'translateX(-50%)' },
            'toast-bottom-full-width': { bottom: '0', left: '0', right: '0' }
        };
        
        const position = positions[this.config.positionClass] || positions['toast-top-right'];
        Object.assign(this.container.style, position);
    }
    
    /**
     * Setup global toastr object for backward compatibility
     */
    setupGlobalToastr() {
        window.toastr = {
            options: this.config,
            success: (message, title, options) => this.show(message, title, 'success', options),
            info: (message, title, options) => this.show(message, title, 'info', options),
            warning: (message, title, options) => this.show(message, title, 'warning', options),
            error: (message, title, options) => this.show(message, title, 'error', options),
            clear: (toast) => this.clear(toast),
            remove: (toast) => this.remove(toast),
            options: this.config
        };
    }
    
    /**
     * Show toast notification
     */
    show(message, title = '', type = 'info', options = {}) {
        const config = { ...this.config, ...options };
        const toast = this.createToast(message, title, type, config);
        
        this.container.appendChild(toast);
        
        // Animate in
        requestAnimationFrame(() => {
            toast.classList.add('toast-show');
        });
        
        // Auto-hide
        if (config.timeOut > 0) {
            setTimeout(() => {
                this.hide(toast);
            }, parseInt(config.timeOut));
        }
        
        return toast;
    }
    
    /**
     * Create toast element
     */
    createToast(message, title, type, config) {
        this.toastCounter++;
        
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.id = `toast-${this.toastCounter}`;
        toast.style.cssText = `
            position: relative;
            pointer-events: auto;
            overflow: hidden;
            margin: 0 0 6px;
            padding: 15px 15px 15px 50px;
            width: 300px;
            border-radius: 3px;
            background-position: 15px center;
            background-repeat: no-repeat;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
            color: white;
            opacity: 0;
            transform: translateX(${this.getSlideDirection()}300px);
            transition: all 0.3s ease;
            cursor: pointer;
        `;
        
        // Set background color and icon based on type
        this.setToastStyle(toast, type);
        
        // Create content
        const content = document.createElement('div');
        
        if (title) {
            const titleEl = document.createElement('div');
            titleEl.className = 'toast-title';
            titleEl.style.cssText = 'font-weight: bold; margin-bottom: 5px;';
            titleEl.textContent = title;
            content.appendChild(titleEl);
        }
        
        if (message) {
            const messageEl = document.createElement('div');
            messageEl.className = 'toast-message';
            messageEl.innerHTML = message;
            content.appendChild(messageEl);
        }
        
        toast.appendChild(content);
        
        // Add close button if enabled
        if (config.closeButton) {
            this.addCloseButton(toast);
        }
        
        // Add progress bar if enabled
        if (config.progressBar && config.timeOut > 0) {
            this.addProgressBar(toast, config.timeOut);
        }
        
        // Add click handler
        toast.addEventListener('click', () => {
            if (config.onclick && typeof config.onclick === 'function') {
                config.onclick();
            }
            this.hide(toast);
        });
        
        return toast;
    }
    
    /**
     * Set toast style based on type
     */
    setToastStyle(toast, type) {
        const styles = {
            success: {
                backgroundColor: '#51a351',
                backgroundImage: 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'white\'%3E%3Cpath d=\'M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z\'/%3E%3C/svg%3E")'
            },
            info: {
                backgroundColor: '#2f96b4',
                backgroundImage: 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'white\'%3E%3Cpath d=\'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z\'/%3E%3C/svg%3E")'
            },
            warning: {
                backgroundColor: '#f89406',
                backgroundImage: 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'white\'%3E%3Cpath d=\'M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z\'/%3E%3C/svg%3E")'
            },
            error: {
                backgroundColor: '#bd362f',
                backgroundImage: 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'white\'%3E%3Cpath d=\'M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z\'/%3E%3C/svg%3E")'
            }
        };
        
        const style = styles[type] || styles.info;
        Object.assign(toast.style, style);
    }
    
    /**
     * Add close button to toast
     */
    addCloseButton(toast) {
        const closeBtn = document.createElement('button');
        closeBtn.className = 'toast-close-button';
        closeBtn.innerHTML = '&times;';
        closeBtn.style.cssText = `
            position: absolute;
            right: -0.3em;
            top: -0.3em;
            z-index: 1;
            border: none;
            background: transparent;
            color: white;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            padding: 0;
            width: 1.2em;
            height: 1.2em;
            line-height: 1;
            opacity: 0.8;
        `;
        
        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.hide(toast);
        });
        
        closeBtn.addEventListener('mouseenter', () => {
            closeBtn.style.opacity = '1';
        });
        
        closeBtn.addEventListener('mouseleave', () => {
            closeBtn.style.opacity = '0.8';
        });
        
        toast.appendChild(closeBtn);
    }
    
    /**
     * Add progress bar to toast
     */
    addProgressBar(toast, timeOut) {
        const progressBar = document.createElement('div');
        progressBar.className = 'toast-progress';
        progressBar.style.cssText = `
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: rgba(0, 0, 0, 0.2);
            transform-origin: left center;
            transform: scaleX(1);
            transition: transform ${timeOut}ms linear;
        `;
        
        toast.appendChild(progressBar);
        
        // Animate progress bar
        requestAnimationFrame(() => {
            progressBar.style.transform = 'scaleX(0)';
        });
    }
    
    /**
     * Get slide direction based on position
     */
    getSlideDirection() {
        const rightPositions = ['toast-top-right', 'toast-bottom-right'];
        return rightPositions.includes(this.config.positionClass) ? '' : '-';
    }
    
    /**
     * Hide toast
     */
    hide(toast) {
        if (!toast || !toast.parentNode) return;
        
        toast.style.opacity = '0';
        toast.style.transform = `translateX(${this.getSlideDirection()}300px)`;
        
        setTimeout(() => {
            this.remove(toast);
        }, 300);
    }
    
    /**
     * Remove toast from DOM
     */
    remove(toast) {
        if (toast && toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }
    
    /**
     * Clear all toasts or specific toast
     */
    clear(toast) {
        if (toast) {
            this.hide(toast);
        } else {
            const toasts = this.container.querySelectorAll('.toast');
            toasts.forEach(t => this.hide(t));
        }
    }
    
    /**
     * Update configuration
     */
    configure(options) {
        Object.assign(this.config, options);
        
        if (options.positionClass) {
            this.container.className = options.positionClass;
            this.setContainerPosition();
        }
    }
}

// Initialize toastr compatibility
const toastrCompat = new ToastrCompatibility();

// Bootstrap Toast integration (optional)
if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
    // Enhance with Bootstrap Toast for better integration
    class BootstrapToastrBridge extends ToastrCompatibility {
        show(message, title = '', type = 'info', options = {}) {
            // Try to use Bootstrap Toast if available
            if (this.useBootstrapToast(message, title, type, options)) {
                return;
            }
            
            // Fallback to custom implementation
            return super.show(message, title, type, options);
        }
        
        useBootstrapToast(message, title, type, options) {
            // Create Bootstrap toast structure
            const toastElement = document.createElement('div');
            toastElement.className = `toast align-items-center text-bg-${this.mapTypeToBootstrap(type)} border-0`;
            toastElement.setAttribute('role', 'alert');
            toastElement.setAttribute('aria-live', 'assertive');
            toastElement.setAttribute('aria-atomic', 'true');
            
            toastElement.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${title ? `<strong>${title}</strong><br>` : ''}${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            // Add to container
            let container = document.querySelector('.toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'toast-container position-fixed top-0 end-0 p-3';
                document.body.appendChild(container);
            }
            
            container.appendChild(toastElement);
            
            // Initialize Bootstrap toast
            const bsToast = new bootstrap.Toast(toastElement, {
                autohide: options.timeOut !== 0,
                delay: parseInt(options.timeOut || this.config.timeOut)
            });
            
            bsToast.show();
            
            return true;
        }
        
        mapTypeToBootstrap(type) {
            const mapping = {
                success: 'success',
                info: 'info',
                warning: 'warning',
                error: 'danger'
            };
            return mapping[type] || 'info';
        }
    }
    
    // Replace with Bootstrap bridge if available
    const bootstrapToastr = new BootstrapToastrBridge();
    window.toastr = {
        ...window.toastr,
        success: (message, title, options) => bootstrapToastr.show(message, title, 'success', options),
        info: (message, title, options) => bootstrapToastr.show(message, title, 'info', options),
        warning: (message, title, options) => bootstrapToastr.show(message, title, 'warning', options),
        error: (message, title, options) => bootstrapToastr.show(message, title, 'error', options)
    };
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ToastrCompatibility;
}

// Global access
window.ToastrCompatibility = ToastrCompatibility;
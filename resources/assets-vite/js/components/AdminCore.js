/**
 * AdminCore - Core functionality for Laravel-admin
 * 
 * Provides essential admin interface functionality with modern JavaScript
 */
export class AdminCore {
    constructor(config = {}) {
        this.config = {
            csrfToken: config.token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            user: config.user || {},
            baseUrl: config.baseUrl || '/admin',
            ...config
        };
        
        this.modules = new Map();
        this.eventBus = new EventTarget();
    }

    /**
     * Initialize core functionality
     */
    async init() {
        this.setupCSRF();
        this.setupAjaxDefaults();
        this.setupGlobalErrorHandling();
        this.setupProgressIndicator();
        
        console.info('AdminCore initialized');
    }

    /**
     * Setup CSRF token handling
     */
    setupCSRF() {
        if (this.config.csrfToken) {
            // Add to all AJAX requests
            document.addEventListener('ajaxSend', (event) => {
                if (event.detail && event.detail.xhr) {
                    event.detail.xhr.setRequestHeader('X-CSRF-TOKEN', this.config.csrfToken);
                }
            });

            // Add to all forms
            const forms = document.querySelectorAll('form:not([data-csrf-added])');
            forms.forEach(form => {
                if (!form.querySelector('input[name="_token"]')) {
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = this.config.csrfToken;
                    form.appendChild(tokenInput);
                    form.setAttribute('data-csrf-added', 'true');
                }
            });
        }
    }

    /**
     * Setup default AJAX configuration
     */
    setupAjaxDefaults() {
        // Modern fetch API defaults
        const originalFetch = window.fetch;
        window.fetch = async (url, options = {}) => {
            const headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': this.config.csrfToken,
                ...options.headers
            };

            return originalFetch(url, { ...options, headers });
        };

        // jQuery AJAX setup for backward compatibility
        if (window.jQuery) {
            window.jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': this.config.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        }
    }

    /**
     * Setup global error handling
     */
    setupGlobalErrorHandling() {
        window.addEventListener('error', (event) => {
            this.handleError(event.error, 'JavaScript Error');
        });

        window.addEventListener('unhandledrejection', (event) => {
            this.handleError(event.reason, 'Unhandled Promise Rejection');
        });

        // AJAX error handling
        document.addEventListener('ajaxError', (event) => {
            this.handleAjaxError(event.detail);
        });
    }

    /**
     * Handle application errors
     */
    handleError(error, context = 'Error') {
        console.error(`${context}:`, error);
        
        // Show user-friendly error message
        this.showNotification('An error occurred. Please try again.', 'error');
        
        // Send to error reporting service if configured
        if (this.config.errorReporting && this.config.errorReporting.enabled) {
            this.reportError(error, context);
        }
    }

    /**
     * Handle AJAX errors
     */
    handleAjaxError(detail) {
        const { xhr, status, error } = detail;
        
        let message = 'An error occurred while processing your request.';
        
        if (xhr.status === 401) {
            message = 'Your session has expired. Please login again.';
            this.redirectToLogin();
        } else if (xhr.status === 403) {
            message = 'You do not have permission to perform this action.';
        } else if (xhr.status === 422) {
            // Validation errors
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.errors) {
                    this.showValidationErrors(response.errors);
                    return;
                }
            } catch (e) {
                // Fall through to generic message
            }
        } else if (xhr.status >= 500) {
            message = 'A server error occurred. Please try again later.';
        }

        this.showNotification(message, 'error');
    }

    /**
     * Setup progress indicator
     */
    setupProgressIndicator() {
        let activeRequests = 0;
        
        const showProgress = () => {
            if (window.NProgress) {
                window.NProgress.start();
            }
        };

        const hideProgress = () => {
            if (window.NProgress) {
                window.NProgress.done();
            }
        };

        // Monitor AJAX requests
        document.addEventListener('ajaxStart', () => {
            activeRequests++;
            if (activeRequests === 1) {
                showProgress();
            }
        });

        document.addEventListener('ajaxComplete', () => {
            activeRequests--;
            if (activeRequests === 0) {
                hideProgress();
            }
        });

        // Monitor fetch requests
        const originalFetch = window.fetch;
        window.fetch = async (...args) => {
            activeRequests++;
            if (activeRequests === 1) {
                showProgress();
            }

            try {
                const response = await originalFetch(...args);
                return response;
            } finally {
                activeRequests--;
                if (activeRequests === 0) {
                    hideProgress();
                }
            }
        };
    }

    /**
     * Show notification to user
     */
    showNotification(message, type = 'info', options = {}) {
        const defaultOptions = {
            timeout: 4000,
            position: 'top-right',
            closable: true,
            ...options
        };

        // Use toastr if available
        if (window.toastr && typeof window.toastr[type] === 'function') {
            window.toastr[type](message, '', defaultOptions);
            return;
        }

        // Fallback to modern notification
        this.showModernNotification(message, type, defaultOptions);
    }

    /**
     * Show modern notification
     */
    showModernNotification(message, type, options) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} notification-modern`;
        notification.innerHTML = `
            <span class="notification-message">${message}</span>
            ${options.closable ? '<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>' : ''}
        `;

        // Add to notification container
        let container = document.querySelector('.notification-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'notification-container';
            document.body.appendChild(container);
        }

        container.appendChild(notification);

        // Auto remove after timeout
        if (options.timeout > 0) {
            setTimeout(() => {
                notification.remove();
            }, options.timeout);
        }

        // Close button handler
        const closeButton = notification.querySelector('.close');
        if (closeButton) {
            closeButton.addEventListener('click', () => notification.remove());
        }
    }

    /**
     * Show validation errors
     */
    showValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            const messages = Array.isArray(errors[field]) ? errors[field] : [errors[field]];
            messages.forEach(message => {
                this.showNotification(message, 'error');
            });
        });
    }

    /**
     * Redirect to login page
     */
    redirectToLogin() {
        const loginUrl = this.config.loginUrl || '/admin/auth/login';
        setTimeout(() => {
            window.location.href = loginUrl;
        }, 2000);
    }

    /**
     * Report error to external service
     */
    reportError(error, context) {
        // Implementation for error reporting service
        // e.g., Sentry, Bugsnag, etc.
        console.log('Reporting error:', { error, context });
    }

    /**
     * Emit custom event
     */
    emit(eventName, detail = {}) {
        const event = new CustomEvent(eventName, { detail });
        this.eventBus.dispatchEvent(event);
        document.dispatchEvent(event);
    }

    /**
     * Listen to events
     */
    on(eventName, callback) {
        this.eventBus.addEventListener(eventName, callback);
    }

    /**
     * Remove event listener
     */
    off(eventName, callback) {
        this.eventBus.removeEventListener(eventName, callback);
    }

    /**
     * Register module
     */
    registerModule(name, module) {
        this.modules.set(name, module);
        if (typeof module.init === 'function') {
            module.init(this);
        }
    }

    /**
     * Get module
     */
    getModule(name) {
        return this.modules.get(name);
    }

    /**
     * Get configuration value
     */
    getConfig(key, defaultValue = null) {
        return key.split('.').reduce((obj, k) => obj?.[k], this.config) ?? defaultValue;
    }

    /**
     * Update configuration
     */
    setConfig(key, value) {
        const keys = key.split('.');
        const lastKey = keys.pop();
        const target = keys.reduce((obj, k) => obj[k] = obj[k] || {}, this.config);
        target[lastKey] = value;
    }
}
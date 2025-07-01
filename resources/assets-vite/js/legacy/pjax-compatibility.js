/**
 * PJAX Compatibility Layer
 * Laravel-admin UI Modernization
 * 
 * Provides backward compatibility for PJAX functionality
 */

/**
 * PJAX Configuration for Laravel-admin
 */
const PjaxConfig = {
    timeout: 6000,
    maxCacheLength: 20,
    scrollTo: 0,
    container: '#pjax-container',
    fragment: '#pjax-container',
    cache: true
};

/**
 * PJAX Compatibility Manager
 */
class PjaxCompatibility {
    constructor() {
        this.initialized = false;
        this.config = PjaxConfig;
        this.loadingBar = null;
        
        this.init();
    }
    
    init() {
        if (this.initialized) return;
        
        // Initialize PJAX if jQuery and PJAX are available
        if (window.jQuery && window.jQuery.pjax) {
            this.initializePjax();
        } else {
            this.setupFallback();
        }
        
        this.setupLoadingIndicator();
        this.setupEventHandlers();
        
        this.initialized = true;
    }
    
    /**
     * Initialize PJAX functionality
     */
    initializePjax() {
        const $ = window.jQuery;
        
        // Enable PJAX for navigation links
        $(document).pjax('a:not([target="_blank"]):not([no-pjax]):not([data-toggle]):not([data-bs-toggle])', this.config.container, {
            timeout: this.config.timeout,
            maxCacheLength: this.config.maxCacheLength,
            scrollTo: this.config.scrollTo,
            fragment: this.config.fragment,
            cache: this.config.cache
        });
        
        // Enable PJAX for forms
        $(document).on('submit', 'form[pjax]', (e) => {
            $.pjax.submit(e, this.config.container);
        });
        
        console.info('PJAX initialized for Laravel-admin');
    }
    
    /**
     * Setup fallback for when PJAX is not available
     */
    setupFallback() {
        // Create a basic SPA-like experience using History API
        window.addEventListener('popstate', (e) => {
            if (e.state && e.state.url) {
                this.loadPage(e.state.url);
            }
        });
        
        // Intercept navigation links
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (this.shouldInterceptLink(link)) {
                e.preventDefault();
                this.loadPage(link.href);
                history.pushState({ url: link.href }, '', link.href);
            }
        });
    }
    
    /**
     * Check if link should be intercepted for SPA navigation
     */
    shouldInterceptLink(link) {
        if (!link || !link.href) return false;
        if (link.target === '_blank') return false;
        if (link.hasAttribute('no-pjax')) return false;
        if (link.hasAttribute('data-toggle') || link.hasAttribute('data-bs-toggle')) return false;
        if (link.href.includes('#')) return false;
        if (link.href.startsWith('javascript:')) return false;
        if (link.hostname !== window.location.hostname) return false;
        
        return true;
    }
    
    /**
     * Load page content via AJAX
     */
    async loadPage(url) {
        try {
            this.showLoading();
            
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-PJAX': 'true',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const html = await response.text();
            this.updateContent(html);
            this.hideLoading();
            
            // Emit page loaded event
            this.emitEvent('pjax:success', { url, html });
            
        } catch (error) {
            console.error('Failed to load page via PJAX fallback:', error);
            this.hideLoading();
            
            // Fallback to regular navigation
            window.location.href = url;
        }
    }
    
    /**
     * Update page content
     */
    updateContent(html) {
        const container = document.querySelector(this.config.container);
        if (!container) {
            console.warn('PJAX container not found, falling back to full reload');
            return false;
        }
        
        // Extract content from response
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.querySelector(this.config.container);
        
        if (newContent) {
            container.innerHTML = newContent.innerHTML;
            
            // Re-initialize JavaScript components
            this.reinitializeComponents();
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Reinitialize JavaScript components after PJAX load
     */
    reinitializeComponents() {
        // Reinitialize admin components if available
        if (window.Admin && typeof window.Admin.initializeComponents === 'function') {
            window.Admin.initializeComponents();
        }
        
        // Reinitialize jQuery plugins
        if (window.jQuery) {
            const $ = window.jQuery;
            
            // Common Laravel-admin plugins
            if ($.fn.select2) {
                $('.select2').select2();
            }
            
            if ($.fn.iCheck) {
                $('input[type="checkbox"], input[type="radio"]').iCheck('destroy').iCheck({
                    checkboxClass: 'icheckbox_minimal-blue',
                    radioClass: 'iradio_minimal-blue'
                });
            }
            
            if ($.fn.datepicker) {
                $('.datepicker').datepicker();
            }
        }
        
        // Bootstrap components
        this.initializeBootstrapComponents();
        
        // Emit component reinitialized event
        this.emitEvent('pjax:components-reinitialized');
    }
    
    /**
     * Initialize Bootstrap components
     */
    initializeBootstrapComponents() {
        if (typeof bootstrap !== 'undefined') {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        }
    }
    
    /**
     * Setup loading indicator
     */
    setupLoadingIndicator() {
        // Create NProgress-style loading bar
        this.loadingBar = document.createElement('div');
        this.loadingBar.className = 'pjax-loading-bar';
        this.loadingBar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: #007bff;
            z-index: 9999;
            transition: width 0.3s ease;
            width: 0%;
            opacity: 0;
        `;
        document.body.appendChild(this.loadingBar);
    }
    
    /**
     * Show loading indicator
     */
    showLoading() {
        if (this.loadingBar) {
            this.loadingBar.style.opacity = '1';
            this.loadingBar.style.width = '30%';
            
            // Gradually increase width
            setTimeout(() => {
                this.loadingBar.style.width = '60%';
            }, 200);
            
            setTimeout(() => {
                this.loadingBar.style.width = '80%';
            }, 500);
        }
        
        // Add loading class to body
        document.body.classList.add('pjax-loading');
    }
    
    /**
     * Hide loading indicator
     */
    hideLoading() {
        if (this.loadingBar) {
            this.loadingBar.style.width = '100%';
            
            setTimeout(() => {
                this.loadingBar.style.opacity = '0';
                this.loadingBar.style.width = '0%';
            }, 200);
        }
        
        // Remove loading class from body
        document.body.classList.remove('pjax-loading');
    }
    
    /**
     * Setup event handlers
     */
    setupEventHandlers() {
        // PJAX events if jQuery PJAX is available
        if (window.jQuery && window.jQuery.pjax) {
            const $ = window.jQuery;
            
            $(document)
                .on('pjax:start', () => {
                    this.showLoading();
                    this.emitEvent('pjax:start');
                })
                .on('pjax:end', () => {
                    this.hideLoading();
                    this.emitEvent('pjax:end');
                })
                .on('pjax:success', (xhr, data, options) => {
                    this.reinitializeComponents();
                    this.emitEvent('pjax:success', { xhr, data, options });
                })
                .on('pjax:error', (xhr, textStatus, error, options) => {
                    this.hideLoading();
                    console.error('PJAX error:', textStatus, error);
                    this.emitEvent('pjax:error', { xhr, textStatus, error, options });
                })
                .on('pjax:timeout', (event, xhr, options) => {
                    // Prevent default timeout behavior
                    event.preventDefault();
                    this.hideLoading();
                    console.warn('PJAX request timed out');
                    this.emitEvent('pjax:timeout', { xhr, options });
                });
        }
    }
    
    /**
     * Get CSRF token
     */
    getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }
    
    /**
     * Emit custom event
     */
    emitEvent(eventName, detail = {}) {
        const event = new CustomEvent(eventName, { detail });
        document.dispatchEvent(event);
    }
    
    /**
     * Reload current page
     */
    reload() {
        if (window.jQuery && window.jQuery.pjax) {
            window.jQuery.pjax.reload(this.config.container);
        } else {
            window.location.reload();
        }
    }
    
    /**
     * Navigate to URL
     */
    navigate(url) {
        if (window.jQuery && window.jQuery.pjax) {
            window.jQuery.pjax({ url: url, container: this.config.container });
        } else {
            this.loadPage(url);
            history.pushState({ url: url }, '', url);
        }
    }
}

// Initialize PJAX compatibility
const pjaxCompat = new PjaxCompatibility();

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PjaxCompatibility;
}

// Global access
window.PjaxCompatibility = PjaxCompatibility;

// Backward compatibility
if (window.LA) {
    window.LA.pjax = {
        reload: () => pjaxCompat.reload(),
        navigate: (url) => pjaxCompat.navigate(url)
    };
}
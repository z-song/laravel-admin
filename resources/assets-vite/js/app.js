/**
 * Laravel-admin Vite JavaScript Entry Point
 * 
 * Modern JavaScript compilation for Laravel-admin with Laravel 11 Vite integration
 * This file serves as the main entry point for all admin JavaScript when Vite is enabled.
 */

// Import CSS for Vite processing
import '../css/app.css';

// Modern imports
import { AdminCore } from './components/AdminCore.js';
import { GridManager } from './components/GridManager.js';
import { FormManager } from './components/FormManager.js';
import { NavigationManager } from './components/NavigationManager.js';

// Legacy compatibility layer
import './legacy/jquery-compatibility.js';
import './legacy/pjax-compatibility.js';
import './legacy/toastr-compatibility.js';

/**
 * Laravel-admin Modern JavaScript Core
 * Provides backward compatibility while enabling modern development
 */
class LaravelAdmin {
    constructor() {
        this.version = '2.0.0-vite';
        this.config = window.LA || {};
        this.components = new Map();
        this.initialized = false;
        
        // Backward compatibility
        this.legacy = {
            token: this.config.token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            user: this.config.user || {}
        };
    }

    /**
     * Initialize the admin interface
     */
    async init() {
        if (this.initialized) {
            return;
        }

        try {
            // Initialize core components
            await this.initializeComponents();
            
            // Setup legacy compatibility
            this.setupLegacyCompatibility();
            
            // Initialize modern features
            this.initializeModernFeatures();
            
            this.initialized = true;
            
            // Emit ready event
            this.emit('admin:ready');
            
            console.info('Laravel-admin (Vite) initialized successfully');
        } catch (error) {
            console.error('Failed to initialize Laravel-admin:', error);
            // Fallback to legacy mode
            this.fallbackToLegacy();
        }
    }

    /**
     * Initialize core components
     */
    async initializeComponents() {
        // Core admin functionality
        this.components.set('core', new AdminCore(this.config));
        
        // Grid management
        if (document.querySelector('.grid-box')) {
            this.components.set('grid', new GridManager());
        }
        
        // Form management
        if (document.querySelector('.form-horizontal, .form-inline')) {
            this.components.set('form', new FormManager());
        }
        
        // Navigation management
        this.components.set('navigation', new NavigationManager());
        
        // Initialize all components
        for (const [name, component] of this.components) {
            if (typeof component.init === 'function') {
                await component.init();
            }
        }
    }

    /**
     * Setup legacy compatibility layer
     */
    setupLegacyCompatibility() {
        // Maintain global LA object for backward compatibility
        window.LA = window.LA || {};
        Object.assign(window.LA, {
            token: this.legacy.token,
            user: this.legacy.user,
            reload: () => this.reload(),
            toastr: window.toastr || {
                success: (msg) => this.showNotification(msg, 'success'),
                error: (msg) => this.showNotification(msg, 'error'),
                warning: (msg) => this.showNotification(msg, 'warning'),
                info: (msg) => this.showNotification(msg, 'info')
            }
        });

        // jQuery compatibility
        if (window.jQuery) {
            this.setupJQueryCompatibility();
        }
    }

    /**
     * Setup jQuery compatibility layer
     */
    setupJQueryCompatibility() {
        const $ = window.jQuery;
        
        // Maintain existing jQuery plugins and behaviors
        $.fn.admin = function(action, options) {
            return this.each(function() {
                const component = $(this).data('admin-component');
                if (component && typeof component[action] === 'function') {
                    component[action](options);
                }
            });
        };

        // CSRF token setup for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': this.legacy.token
            }
        });
    }

    /**
     * Initialize modern features
     */
    initializeModernFeatures() {
        // Modern event system
        this.setupEventSystem();
        
        // Performance monitoring
        this.setupPerformanceMonitoring();
        
        // Accessibility improvements
        this.setupAccessibility();
        
        // Dark mode support
        this.setupThemeManager();
    }

    /**
     * Setup modern event system
     */
    setupEventSystem() {
        this.eventBus = new EventTarget();
    }

    /**
     * Emit custom events
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
     * Setup performance monitoring
     */
    setupPerformanceMonitoring() {
        if (typeof performance !== 'undefined' && performance.mark) {
            performance.mark('admin:init:start');
            
            requestIdleCallback(() => {
                performance.mark('admin:init:end');
                performance.measure('admin:init', 'admin:init:start', 'admin:init:end');
            });
        }
    }

    /**
     * Setup accessibility features
     */
    setupAccessibility() {
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.altKey && e.key === 'm') {
                // Alt+M: Focus main navigation
                const nav = document.querySelector('.sidebar-menu');
                if (nav) nav.focus();
            }
        });

        // Skip links
        if (!document.querySelector('.skip-link')) {
            const skipLink = document.createElement('a');
            skipLink.href = '#main-content';
            skipLink.className = 'skip-link sr-only';
            skipLink.textContent = 'Skip to main content';
            document.body.insertBefore(skipLink, document.body.firstChild);
        }
    }

    /**
     * Setup theme manager
     */
    setupThemeManager() {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
        
        const applyTheme = (dark) => {
            document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
        };

        applyTheme(prefersDark.matches);
        prefersDark.addEventListener('change', (e) => applyTheme(e.matches));
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        // Modern notification system
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(`Admin: ${message}`, {
                icon: '/vendor/laravel-admin/AdminLTE/dist/img/AdminLTELogo.png'
            });
        }

        // Fallback to toastr if available
        if (window.toastr && typeof window.toastr[type] === 'function') {
            window.toastr[type](message);
        } else {
            console.log(`${type.toUpperCase()}: ${message}`);
        }
    }

    /**
     * Reload current page with PJAX if available
     */
    reload() {
        if (window.jQuery && window.jQuery.pjax) {
            window.jQuery.pjax.reload('#pjax-container');
        } else {
            window.location.reload();
        }
    }

    /**
     * Fallback to legacy mode
     */
    fallbackToLegacy() {
        console.warn('Falling back to legacy admin mode');
        
        // Load legacy scripts if needed
        if (typeof window.LA === 'undefined') {
            const script = document.createElement('script');
            script.src = '/vendor/laravel-admin/laravel-admin/laravel-admin.js';
            script.onload = () => {
                console.info('Legacy admin scripts loaded');
            };
            document.head.appendChild(script);
        }
    }

    /**
     * Get component instance
     */
    getComponent(name) {
        return this.components.get(name);
    }

    /**
     * Register new component
     */
    registerComponent(name, component) {
        this.components.set(name, component);
        if (this.initialized && typeof component.init === 'function') {
            component.init();
        }
    }
}

// Initialize admin when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeAdmin);
} else {
    initializeAdmin();
}

async function initializeAdmin() {
    // Create global admin instance
    window.Admin = new LaravelAdmin();
    
    // Initialize
    await window.Admin.init();
    
    // Maintain backward compatibility
    window.LA = window.LA || {};
    Object.assign(window.LA, {
        reload: () => window.Admin.reload(),
        // Add other legacy methods as needed
    });
}

// Export for ES6 modules
export { LaravelAdmin };
export default LaravelAdmin;
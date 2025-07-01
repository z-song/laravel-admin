/**
 * AdminLTE 4 + Bootstrap 5 JavaScript Integration
 * Laravel-admin UI Modernization
 * 
 * This file integrates AdminLTE 4 with Bootstrap 5 JavaScript functionality
 */

// Import Bootstrap 5 JavaScript
import 'bootstrap';

// Import AdminLTE 4 JavaScript (when available)
// Note: AdminLTE 4 is still in beta, so we'll create our own implementation

// Import existing modern components
import './components/AdminCore.js';
import './components/NavigationManager.js';

/**
 * AdminLTE 4 Layout Manager
 * Handles the main layout functionality for AdminLTE 4
 */
class AdminLTE4Layout {
    constructor() {
        this.sidebar = document.querySelector('.app-sidebar');
        this.main = document.querySelector('.app-main');
        this.footer = document.querySelector('.app-footer');
        this.toggleBtn = document.querySelector('.sidebar-toggle');
        this.overlay = null;
        
        this.init();
    }
    
    init() {
        this.setupSidebarToggle();
        this.setupResponsiveHandling();
        this.setupTreeviewMenu();
        this.setupDarkModeToggle();
        this.setupAccessibility();
    }
    
    /**
     * Setup sidebar toggle functionality
     */
    setupSidebarToggle() {
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', () => {
                this.toggleSidebar();
            });
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!this.sidebar?.contains(e.target) && !this.toggleBtn?.contains(e.target)) {
                    this.closeSidebar();
                }
            }
        });
    }
    
    /**
     * Toggle sidebar visibility
     */
    toggleSidebar() {
        if (window.innerWidth <= 768) {
            // Mobile behavior
            this.sidebar?.classList.toggle('show');
            this.toggleOverlay();
        } else {
            // Desktop behavior
            document.body.classList.toggle('sidebar-collapsed');
            this.sidebar?.classList.toggle('sidebar-mini');
        }
    }
    
    /**
     * Close sidebar (mobile)
     */
    closeSidebar() {
        this.sidebar?.classList.remove('show');
        this.removeOverlay();
    }
    
    /**
     * Toggle overlay for mobile sidebar
     */
    toggleOverlay() {
        if (this.sidebar?.classList.contains('show')) {
            this.createOverlay();
        } else {
            this.removeOverlay();
        }
    }
    
    /**
     * Create overlay element
     */
    createOverlay() {
        if (!this.overlay) {
            this.overlay = document.createElement('div');
            this.overlay.className = 'sidebar-overlay';
            this.overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1035;
                cursor: pointer;
            `;
            
            this.overlay.addEventListener('click', () => {
                this.closeSidebar();
            });
            
            document.body.appendChild(this.overlay);
        }
    }
    
    /**
     * Remove overlay element
     */
    removeOverlay() {
        if (this.overlay) {
            this.overlay.remove();
            this.overlay = null;
        }
    }
    
    /**
     * Setup responsive handling
     */
    setupResponsiveHandling() {
        const mediaQuery = window.matchMedia('(max-width: 768px)');
        
        const handleResize = (e) => {
            if (!e.matches) {
                // Desktop view
                this.closeSidebar();
                document.body.classList.remove('sidebar-collapsed');
            }
        };
        
        mediaQuery.addListener(handleResize);
        handleResize(mediaQuery);
    }
    
    /**
     * Setup treeview menu functionality
     */
    setupTreeviewMenu() {
        const treeviewItems = document.querySelectorAll('.nav-item.has-treeview');
        
        treeviewItems.forEach(item => {
            const link = item.querySelector('.nav-link');
            const treeview = item.querySelector('.nav-treeview');
            
            if (link && treeview) {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    // Close other open treeviews
                    treeviewItems.forEach(otherItem => {
                        if (otherItem !== item) {
                            otherItem.classList.remove('menu-open');
                            const otherTreeview = otherItem.querySelector('.nav-treeview');
                            if (otherTreeview) {
                                otherTreeview.style.display = 'none';
                            }
                        }
                    });
                    
                    // Toggle current treeview
                    item.classList.toggle('menu-open');
                    
                    if (item.classList.contains('menu-open')) {
                        treeview.style.display = 'block';
                        // Animate open
                        treeview.style.opacity = '0';
                        treeview.style.transform = 'translateY(-10px)';
                        
                        requestAnimationFrame(() => {
                            treeview.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            treeview.style.opacity = '1';
                            treeview.style.transform = 'translateY(0)';
                        });
                    } else {
                        // Animate close
                        treeview.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        treeview.style.opacity = '0';
                        treeview.style.transform = 'translateY(-10px)';
                        
                        setTimeout(() => {
                            treeview.style.display = 'none';
                        }, 300);
                    }
                });
            }
        });
    }
    
    /**
     * Setup dark mode toggle
     */
    setupDarkModeToggle() {
        const darkModeToggle = document.querySelector('.dark-mode-toggle');
        
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                this.toggleDarkMode();
            });
        }
        
        // Apply saved theme on load
        const savedTheme = localStorage.getItem('admin-theme') || 'light';
        this.setTheme(savedTheme);
    }
    
    /**
     * Toggle dark mode
     */
    toggleDarkMode() {
        const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        this.setTheme(newTheme);
    }
    
    /**
     * Set theme
     */
    setTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem('admin-theme', theme);
        
        // Update toggle button icon
        const toggleIcon = document.querySelector('.dark-mode-toggle i');
        if (toggleIcon) {
            toggleIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
    }
    
    /**
     * Setup accessibility features
     */
    setupAccessibility() {
        // Skip navigation link
        this.createSkipNav();
        
        // Keyboard navigation for sidebar
        this.setupKeyboardNavigation();
        
        // ARIA attributes
        this.setupAriaAttributes();
        
        // Focus management
        this.setupFocusManagement();
    }
    
    /**
     * Create skip navigation link
     */
    createSkipNav() {
        const skipNav = document.createElement('a');
        skipNav.href = '#main-content';
        skipNav.className = 'sr-only sr-only-focusable skip-nav';
        skipNav.textContent = 'Skip to main content';
        skipNav.style.cssText = `
            position: absolute;
            top: -40px;
            left: 6px;
            z-index: 1050;
            padding: 8px 16px;
            background: var(--bs-primary);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: top 0.3s;
        `;
        
        skipNav.addEventListener('focus', () => {
            skipNav.style.top = '6px';
        });
        
        skipNav.addEventListener('blur', () => {
            skipNav.style.top = '-40px';
        });
        
        document.body.insertBefore(skipNav, document.body.firstChild);
    }
    
    /**
     * Setup keyboard navigation
     */
    setupKeyboardNavigation() {
        // Escape key to close sidebar on mobile
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && window.innerWidth <= 768) {
                this.closeSidebar();
            }
        });
        
        // Arrow key navigation in sidebar
        const navLinks = document.querySelectorAll('.nav-sidebar .nav-link');
        navLinks.forEach((link, index) => {
            link.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowDown' && index < navLinks.length - 1) {
                    e.preventDefault();
                    navLinks[index + 1].focus();
                } else if (e.key === 'ArrowUp' && index > 0) {
                    e.preventDefault();
                    navLinks[index - 1].focus();
                }
            });
        });
    }
    
    /**
     * Setup ARIA attributes
     */
    setupAriaAttributes() {
        // Sidebar attributes
        if (this.sidebar) {
            this.sidebar.setAttribute('role', 'navigation');
            this.sidebar.setAttribute('aria-label', 'Main navigation');
        }
        
        // Toggle button attributes
        if (this.toggleBtn) {
            this.toggleBtn.setAttribute('aria-label', 'Toggle navigation');
            this.toggleBtn.setAttribute('aria-expanded', 'true');
        }
        
        // Main content attributes
        if (this.main) {
            this.main.setAttribute('id', 'main-content');
            this.main.setAttribute('role', 'main');
        }
    }
    
    /**
     * Setup focus management
     */
    setupFocusManagement() {
        // Focus trap for mobile sidebar
        const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab' && window.innerWidth <= 768 && this.sidebar?.classList.contains('show')) {
                const focusableContent = this.sidebar.querySelectorAll(focusableElements);
                const firstFocusable = focusableContent[0];
                const lastFocusable = focusableContent[focusableContent.length - 1];
                
                if (e.shiftKey) {
                    if (document.activeElement === firstFocusable) {
                        lastFocusable.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastFocusable) {
                        firstFocusable.focus();
                        e.preventDefault();
                    }
                }
            }
        });
    }
}

/**
 * Bootstrap 5 Integration Helper
 */
class Bootstrap5Helper {
    static initializeTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    static initializePopovers() {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }
    
    static initializeToasts() {
        const toastElList = [].slice.call(document.querySelectorAll('.toast'));
        toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl);
        });
    }
    
    static showAlert(message, type = 'info', duration = 5000) {
        const alertContainer = document.querySelector('.alert-container') || document.body;
        
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.setAttribute('role', 'alert');
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        alertContainer.appendChild(alert);
        
        // Auto-remove after duration
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, duration);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize AdminLTE 4 Layout
    new AdminLTE4Layout();
    
    // Initialize Bootstrap 5 components
    Bootstrap5Helper.initializeTooltips();
    Bootstrap5Helper.initializePopovers();
    Bootstrap5Helper.initializeToasts();
    
    // Add fade-in animation to main content
    const mainContent = document.querySelector('.app-main');
    if (mainContent) {
        mainContent.classList.add('fade-in');
    }
});

// Export for use in other modules
window.AdminLTE4Layout = AdminLTE4Layout;
window.Bootstrap5Helper = Bootstrap5Helper;
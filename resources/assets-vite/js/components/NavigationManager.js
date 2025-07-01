/**
 * NavigationManager - Navigation handling for Laravel-admin
 */
export class NavigationManager {
    constructor() {
        this.initialized = false;
        this.activeMenu = null;
    }

    async init() {
        this.setupSidebarToggle();
        this.setupMenuHandling();
        this.setupBreadcrumbs();
        this.initialized = true;
    }

    setupSidebarToggle() {
        const sidebarToggle = document.querySelector('[data-toggle="offcanvas"]');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                document.body.classList.toggle('sidebar-collapse');
            });
        }
    }

    setupMenuHandling() {
        const menuItems = document.querySelectorAll('.sidebar-menu li > a');
        menuItems.forEach(item => {
            item.addEventListener('click', (e) => this.handleMenuClick(e));
        });
    }

    setupBreadcrumbs() {
        // Breadcrumb functionality
    }

    handleMenuClick(event) {
        const link = event.target.closest('a');
        const menuItem = link.closest('li');
        
        // Handle submenu toggle
        if (menuItem.querySelector('.treeview-menu')) {
            event.preventDefault();
            this.toggleSubmenu(menuItem);
        }
    }

    toggleSubmenu(menuItem) {
        const isActive = menuItem.classList.contains('active');
        
        // Close other submenus
        const activeMenus = document.querySelectorAll('.sidebar-menu li.active');
        activeMenus.forEach(menu => {
            if (menu !== menuItem) {
                menu.classList.remove('active');
            }
        });
        
        // Toggle current submenu
        menuItem.classList.toggle('active', !isActive);
    }
}
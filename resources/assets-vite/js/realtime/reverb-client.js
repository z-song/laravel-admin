/**
 * Laravel Reverb Client for Admin Real-time Features
 * 
 * This module handles WebSocket connections using Laravel Reverb
 * for real-time admin operations, notifications, and presence tracking.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

export class ReverbClient {
    constructor(config = {}) {
        this.config = {
            broadcaster: 'reverb',
            key: config.key || window.REVERB_APP_KEY,
            wsHost: config.host || window.REVERB_HOST || '127.0.0.1',
            wsPort: config.port || window.REVERB_PORT || 8080,
            wssPort: config.port || window.REVERB_PORT || 8080,
            forceTLS: config.scheme === 'https',
            enabledTransports: ['ws', 'wss'],
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                },
            },
            ...config,
        };

        this.echo = null;
        this.channels = new Map();
        this.isConnected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        
        this.init();
    }

    /**
     * Initialize the Echo instance and set up connection event handlers
     */
    init() {
        try {
            // Make Pusher available globally for Laravel Echo
            window.Pusher = Pusher;

            this.echo = new Echo(this.config);

            // Set up connection event handlers
            this.setupConnectionHandlers();

            // Auto-connect if enabled
            if (this.isRealtimeEnabled()) {
                this.connect();
            }

        } catch (error) {
            console.error('Failed to initialize Reverb client:', error);
        }
    }

    /**
     * Set up WebSocket connection event handlers
     */
    setupConnectionHandlers() {
        if (!this.echo?.connector?.pusher) return;

        const pusher = this.echo.connector.pusher;

        pusher.connection.bind('connected', () => {
            this.isConnected = true;
            this.reconnectAttempts = 0;
            console.log('Reverb: Connected to WebSocket server');
            this.onConnected();
        });

        pusher.connection.bind('disconnected', () => {
            this.isConnected = false;
            console.log('Reverb: Disconnected from WebSocket server');
            this.onDisconnected();
        });

        pusher.connection.bind('error', (error) => {
            console.error('Reverb: Connection error:', error);
            this.onError(error);
        });

        pusher.connection.bind('unavailable', () => {
            console.warn('Reverb: Connection unavailable');
            this.attemptReconnect();
        });
    }

    /**
     * Connect to the WebSocket server
     */
    connect() {
        if (this.isConnected || !this.echo) return;

        try {
            // Subscribe to admin channels
            this.subscribeToAdminChannels();
        } catch (error) {
            console.error('Failed to connect to Reverb:', error);
        }
    }

    /**
     * Disconnect from the WebSocket server
     */
    disconnect() {
        if (!this.echo) return;

        // Unsubscribe from all channels
        this.channels.forEach((channel, channelName) => {
            this.echo.leave(channelName);
        });
        
        this.channels.clear();
        this.isConnected = false;

        console.log('Reverb: Disconnected');
    }

    /**
     * Subscribe to admin notification channels
     */
    subscribeToAdminChannels() {
        const userId = this.getCurrentUserId();
        
        if (!userId) {
            console.warn('Reverb: No user ID found, skipping channel subscriptions');
            return;
        }

        // Subscribe to general admin notifications
        if (this.isFeatureEnabled('notifications')) {
            this.subscribeToChannel('admin.notifications', 'private');
        }

        // Subscribe to user-specific operations
        if (this.isFeatureEnabled('operation_log')) {
            this.subscribeToChannel(`admin.operations.${userId}`, 'private');
        }

        // Subscribe to presence channel
        if (this.isFeatureEnabled('user_presence')) {
            this.subscribeToPresenceChannel();
        }
    }

    /**
     * Subscribe to a specific channel
     */
    subscribeToChannel(channelName, type = 'public') {
        if (!this.echo || this.channels.has(channelName)) return;

        try {
            let channel;

            switch (type) {
                case 'private':
                    channel = this.echo.private(channelName);
                    break;
                case 'presence':
                    channel = this.echo.join(channelName);
                    break;
                default:
                    channel = this.echo.channel(channelName);
            }

            // Listen for admin operation events
            channel.listen('.admin.operation', (event) => {
                this.handleOperationEvent(event);
            });

            this.channels.set(channelName, channel);
            console.log(`Reverb: Subscribed to ${type} channel: ${channelName}`);

        } catch (error) {
            console.error(`Failed to subscribe to channel ${channelName}:`, error);
        }
    }

    /**
     * Subscribe to presence channel for user tracking
     */
    subscribeToPresenceChannel() {
        const channelName = 'admin.presence';
        
        if (this.channels.has(channelName)) return;

        try {
            const channel = this.echo.join(channelName)
                .here((users) => {
                    this.handlePresenceHere(users);
                })
                .joining((user) => {
                    this.handlePresenceJoining(user);
                })
                .leaving((user) => {
                    this.handlePresenceLeaving(user);
                })
                .listen('.admin.presence', (event) => {
                    this.handlePresenceEvent(event);
                });

            this.channels.set(channelName, channel);
            console.log('Reverb: Subscribed to presence channel');

        } catch (error) {
            console.error('Failed to subscribe to presence channel:', error);
        }
    }

    /**
     * Subscribe to model-specific grid update channels
     */
    subscribeToGridUpdates(modelName) {
        if (!this.isFeatureEnabled('grid_updates')) return;

        const channelName = `admin.grid.${modelName.toLowerCase()}`;
        
        if (this.channels.has(channelName)) return;

        this.subscribeToChannel(channelName, 'private');
    }

    /**
     * Handle admin operation events
     */
    handleOperationEvent(event) {
        console.log('Reverb: Admin operation event received:', event);

        // Show notification
        this.showNotification(event.message, 'info');

        // Update UI if needed
        this.updateUI(event);

        // Trigger custom event for other components
        window.dispatchEvent(new CustomEvent('admin:operation', {
            detail: event
        }));
    }

    /**
     * Handle presence events
     */
    handlePresenceHere(users) {
        console.log('Reverb: Users currently online:', users);
        this.updateOnlineUsers(users);
    }

    handlePresenceJoining(user) {
        console.log('Reverb: User joined:', user);
        this.showNotification(`${user.name} joined`, 'success');
        this.addOnlineUser(user);
    }

    handlePresenceLeaving(user) {
        console.log('Reverb: User left:', user);
        this.showNotification(`${user.name} left`, 'warning');
        this.removeOnlineUser(user);
    }

    handlePresenceEvent(event) {
        console.log('Reverb: Presence event received:', event);
    }

    /**
     * Show notification to user
     */
    showNotification(message, type = 'info') {
        // Use existing admin notification system or create toast
        if (window.toastr) {
            window.toastr[type](message);
        } else if (window.Swal) {
            window.Swal.fire({
                text: message,
                icon: type,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
            });
        } else {
            console.log(`Notification (${type}): ${message}`);
        }
    }

    /**
     * Update UI based on operation events
     */
    updateUI(event) {
        // Refresh grid if current page matches the model
        if (event.model && this.isCurrentPageGrid(event.model)) {
            this.refreshCurrentGrid();
        }
    }

    /**
     * Update online users display
     */
    updateOnlineUsers(users) {
        const onlineUsersElement = document.getElementById('admin-online-users');
        if (!onlineUsersElement) return;

        onlineUsersElement.innerHTML = users.map(user => 
            `<div class="online-user" data-user-id="${user.id}">
                <img src="${user.avatar}" alt="${user.name}" class="user-avatar">
                <span class="user-name">${user.name}</span>
                <span class="online-indicator"></span>
            </div>`
        ).join('');
    }

    /**
     * Add user to online users display
     */
    addOnlineUser(user) {
        // Implementation for adding a single user to the online users list
    }

    /**
     * Remove user from online users display
     */
    removeOnlineUser(user) {
        const userElement = document.querySelector(`[data-user-id="${user.id}"]`);
        if (userElement) {
            userElement.remove();
        }
    }

    /**
     * Refresh current grid if applicable
     */
    refreshCurrentGrid() {
        // Check if we're on a grid page and refresh it
        if (window.LaravelAdmin && window.LaravelAdmin.grid) {
            window.LaravelAdmin.grid.refresh();
        }
    }

    /**
     * Check if current page is a grid for the specified model
     */
    isCurrentPageGrid(modelName) {
        const currentPath = window.location.pathname;
        const modelPath = modelName.toLowerCase().replace(/([A-Z])/g, '-$1').toLowerCase();
        return currentPath.includes(modelPath);
    }

    /**
     * Attempt to reconnect to WebSocket server
     */
    attemptReconnect() {
        if (this.reconnectAttempts >= this.maxReconnectAttempts) {
            console.error('Reverb: Max reconnection attempts reached');
            return;
        }

        this.reconnectAttempts++;
        const delay = Math.pow(2, this.reconnectAttempts) * 1000; // Exponential backoff

        console.log(`Reverb: Reconnecting in ${delay}ms (attempt ${this.reconnectAttempts})`);

        setTimeout(() => {
            this.connect();
        }, delay);
    }

    /**
     * Connection event handlers
     */
    onConnected() {
        // Update connection status in UI
        this.updateConnectionStatus(true);
    }

    onDisconnected() {
        // Update connection status in UI
        this.updateConnectionStatus(false);
    }

    onError(error) {
        // Handle connection errors
        console.error('Reverb connection error:', error);
    }

    /**
     * Update connection status indicator in UI
     */
    updateConnectionStatus(isConnected) {
        const statusElement = document.getElementById('reverb-status');
        if (!statusElement) return;

        statusElement.className = isConnected ? 'connected' : 'disconnected';
        statusElement.title = isConnected ? 'Real-time: Connected' : 'Real-time: Disconnected';
    }

    /**
     * Utility methods
     */
    isRealtimeEnabled() {
        return window.ADMIN_REVERB_ENABLED === true;
    }

    isFeatureEnabled(feature) {
        return window[`ADMIN_REALTIME_${feature.toUpperCase()}`] === true;
    }

    getCurrentUserId() {
        return window.Admin?.user?.id || null;
    }

    getAuthToken() {
        return localStorage.getItem('admin_token') || 
               document.querySelector('meta[name="api-token"]')?.content || '';
    }
}

// Auto-initialize if configuration is available
if (typeof window !== 'undefined' && window.ADMIN_REVERB_ENABLED) {
    window.AdminReverb = new ReverbClient();
}

export default ReverbClient;
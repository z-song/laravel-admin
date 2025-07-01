/**
 * jQuery Compatibility Layer
 * 
 * Maintains backward compatibility with existing jQuery-based code
 * while providing modern alternatives
 */

// Ensure jQuery is available
if (typeof window.jQuery === 'undefined') {
    console.warn('jQuery not found. Some legacy functionality may not work.');
} else {
    const $ = window.jQuery;
    
    // Extend jQuery with admin-specific methods
    $.extend({
        admin: {
            // Legacy admin utilities
            reload: function() {
                if ($.pjax) {
                    $.pjax.reload('#pjax-container');
                } else {
                    window.location.reload();
                }
            },
            
            // Toast notifications compatibility
            toastr: {
                success: function(message, title, options) {
                    if (window.Admin && window.Admin.showNotification) {
                        window.Admin.showNotification(message, 'success', options);
                    } else if (window.toastr) {
                        window.toastr.success(message, title, options);
                    }
                },
                error: function(message, title, options) {
                    if (window.Admin && window.Admin.showNotification) {
                        window.Admin.showNotification(message, 'error', options);
                    } else if (window.toastr) {
                        window.toastr.error(message, title, options);
                    }
                },
                warning: function(message, title, options) {
                    if (window.Admin && window.Admin.showNotification) {
                        window.Admin.showNotification(message, 'warning', options);
                    } else if (window.toastr) {
                        window.toastr.warning(message, title, options);
                    }
                },
                info: function(message, title, options) {
                    if (window.Admin && window.Admin.showNotification) {
                        window.Admin.showNotification(message, 'info', options);
                    } else if (window.toastr) {
                        window.toastr.info(message, title, options);
                    }
                }
            }
        }
    });

    // Legacy form handling
    $.fn.extend({
        adminForm: function(options) {
            return this.each(function() {
                const $form = $(this);
                
                // Setup form validation
                $form.on('submit', function(e) {
                    if (options && options.validate && typeof options.validate === 'function') {
                        if (!options.validate.call(this)) {
                            e.preventDefault();
                            return false;
                        }
                    }
                });

                // Setup AJAX form submission
                if (options && options.ajax) {
                    $form.on('submit', function(e) {
                        e.preventDefault();
                        
                        const formData = new FormData(this);
                        const url = $form.attr('action') || window.location.href;
                        const method = $form.attr('method') || 'POST';

                        fetch(url, {
                            method: method,
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (options.success && typeof options.success === 'function') {
                                options.success.call(this, data);
                            }
                        })
                        .catch(error => {
                            if (options.error && typeof options.error === 'function') {
                                options.error.call(this, error);
                            }
                        });
                    });
                }
            });
        },

        // Legacy grid handling
        adminGrid: function(options) {
            return this.each(function() {
                const $grid = $(this);
                
                // Setup grid interactions
                $grid.on('click', '[data-action]', function(e) {
                    const action = $(this).data('action');
                    const id = $(this).data('id');
                    
                    if (options && options.actions && options.actions[action]) {
                        options.actions[action].call(this, id, e);
                    }
                });
            });
        }
    });

    // AJAX setup for backward compatibility
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    // Document ready compatibility
    $(document).ready(function() {
        // Initialize legacy components that depend on jQuery
        
        // Setup legacy PJAX if available
        if ($.pjax) {
            $(document).pjax('a:not(a[target="_blank"])', {
                container: '#pjax-container',
                timeout: 5000
            });
        }

        // Setup legacy toastr options
        if (window.toastr) {
            window.toastr.options = {
                closeButton: true,
                progressBar: true,
                showMethod: 'slideDown',
                timeOut: 4000,
                positionClass: 'toast-top-right'
            };
        }

        // Setup legacy editable defaults
        if ($.fn.editable) {
            $.fn.editable.defaults.params = function(params) {
                params._token = $('meta[name="csrf-token"]').attr('content');
                params._editable = 1;
                params._method = 'PUT';
                return params;
            };

            $.fn.editable.defaults.error = function(data) {
                let msg = '';
                if (data.responseJSON && data.responseJSON.errors) {
                    $.each(data.responseJSON.errors, function(k, v) {
                        msg += v + "\n";
                    });
                }
                return msg;
            };
        }

        // Emit legacy ready event
        $(document).trigger('admin:legacy:ready');
    });

    // Expose jQuery globally for legacy code
    window.$ = window.jQuery = $;
}

// Polyfills for legacy browser support
if (!Element.prototype.matches) {
    Element.prototype.matches = Element.prototype.msMatchesSelector || 
                               Element.prototype.webkitMatchesSelector;
}

if (!Element.prototype.closest) {
    Element.prototype.closest = function(s) {
        var el = this;
        do {
            if (Element.prototype.matches.call(el, s)) return el;
            el = el.parentElement || el.parentNode;
        } while (el !== null && el.nodeType === 1);
        return null;
    };
}

// Custom event polyfill
if (typeof window.CustomEvent !== 'function') {
    function CustomEvent(event, params) {
        params = params || { bubbles: false, cancelable: false, detail: undefined };
        var evt = document.createEvent('CustomEvent');
        evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
        return evt;
    }
    CustomEvent.prototype = window.Event.prototype;
    window.CustomEvent = CustomEvent;
}
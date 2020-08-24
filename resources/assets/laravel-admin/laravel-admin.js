(function ($) {

    // NProgress init
    NProgress.configure({parent: '#pjax-container'});

    $('[data-toggle="tooltip"]').tooltip();

    // pjax init
    $.pjax.defaults.timeout = 5000;
    $.pjax.defaults.maxCacheLength = 0;
    $(document).pjax('a:not(a[target="_blank"])', {
        container: '#pjax-container'
    });

    $(document).on('submit', 'form[pjax-container]', function (event) {
        $.pjax.submit(event, '#pjax-container')
    });

    $(document).on('pjax:timeout', function (event) {
        event.preventDefault();
    });

    $(document).on("pjax:popstate", function () {
        $(document).one("pjax:end", function (event) {
            $(event.target).find("script[data-exec-on-popstate]").each(function () {
                $.globalEval(this.text || this.textContent || this.innerHTML || '');
            });
        });
    });

    $(document).on('pjax:send', function (xhr) {
        if (xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
            $submit_btn = $('form[pjax-container] :submit');
            if ($submit_btn) {
                $submit_btn.button('loading')
            }
        }
        NProgress.start();
    });

    $(document).on('pjax:complete', function (xhr) {
        if (xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
            $submit_btn = $('form[pjax-container] :submit');
            if ($submit_btn) {
                $submit_btn.button('reset')
            }
        }
        NProgress.done();
    });

    $(document).click(function () {
        $('.sidebar-form .dropdown-menu').hide();
    });

    (function () {
        var $active = $('.sidebar li.nav-item a[href$="' + (location.pathname + location.search + location.hash) + '"]');
        $active.addClass('active').parents('.has-treeview').addClass('menu-open').children('a').addClass('active');

        $('.sidebar li.nav-item:not(.has-treeview) a.nav-link').click(function () {
            $('.sidebar a.nav-link').removeClass('active');
            $(this).addClass('active').parents('.has-treeview').children('a').addClass('active');
        });

        $('[data-toggle="popover"]').popover();
    })();

    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        if (options.type.toLowerCase() === 'post' || originalOptions.type.toLowerCase() === 'post') {
            if (originalOptions.data instanceof FormData) {
                originalOptions.data.append('_token', $.admin.getToken());
                options.data = originalOptions.data;
            } else {
                options.data = $.param($.extend(originalOptions.data, { _token : $.admin.getToken()}));
            }
        }
    });

    $.ajaxSetup({
        statusCode: {
            500: function(xhr) {
                $.admin.toastr.error(xhr.responseJSON.message, {position:"bottom"});
            }
        }
    });

    $.delete = function (options) {
        options.type = 'POST';
        Object.assign(options.data, {_method: 'DELETE'});

        return $.ajax(options);
    };

    $.put = function (options) {
        options.type = 'POST';
        Object.assign(options.data, {_method: 'PUT'});

        return $.ajax(options);
    };

    function Table ($el) {
        this.$el = $el;
        this.selects = {};
        this.rows = $el.find('>tbody>tr');
    }

    Table.prototype.box = function () {
        return this.$el.closest('.card');
    };

    Table.prototype.select = function (id) {
        if (id in this.selects) {
            return;
        }
        this.selects[id] = id;
        this.findRow(id).addClass('selected');
    };

    Table.prototype.unselect = function (id) {
        if (!(id in this.selects)) {
            return;
        }
        delete this.selects[id];
        this.findRow(id).removeClass('selected');
    };

    Table.prototype.toggle = function (id) {
        if (id in this.selects) {
            this.unselect(id);
        } else {
            this.select(id);
        }
        this.toggleBatchAction();
    };

    Table.prototype.toggleAll = function (checked) {
        if (checked) {
            this.$el.find('input.table-row-checkbox').prop('checked', true).trigger('change');
        } else {
            this.$el.find('input.table-row-checkbox').prop('checked', false).trigger('change');
            this.selects = {};
        }
        this.toggleBatchAction();
    };

    Table.prototype.selected = function () {
        return Object.keys(this.selects);
    };

    Table.prototype.findRow  = function (id) {
        return this.$el.find('tr[data-key=' + id + ']');
    };

    Table.prototype.toggleBatchAction = function () {
        var selected = this.selected().length;
        var $btn = this.box().find('.table-select-all-btn');
        var $select = $btn.find('.selected');
        var $export = this.box().find('a.export-selected');

        if (selected > 0) {
            $btn.show();
            $export.removeClass('d-none');
            $select.html($select.data('tpl').replace('{n}', selected));
        } else {
            $btn.hide();
            $export.addClass('d-none');
        }
    };

    function Toast() {
        this.toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 400000,
            timerProgressBar: true,
            showClass: {
                popup: 'swal2-noanimation',
                backdrop: 'swal2-noanimation'
            },
            onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    }

    Toast.prototype.show = function (icon, title, options) {
        var settings = {
            icon: icon,
            title: title,
        };

        if (typeof options !== 'object') {
            Object.assign(settings, options);
        }

        this.toast.fire(settings);
    };

    ['success', 'error', 'info', 'warning', 'question'].forEach((icon) => {
        Toast.prototype[icon] = function (title, options) {
            this.show(icon, title, options);
        }
    });

    function Admin () {
        this.token = $('meta[name=csrf-token]').attr('content');
        this.user = __user;

        this.swal = swal;
        this.toastr = new Toast();

        this.totop = null;
        this.table = null;
        this.loadedScripts = [];

        this.enableTotop();
    }

    Admin.prototype.enableTotop = function () {
        var $totop = $('<button/>', {
            id: 'totop',
            title: 'Go to top',
            style: 'display: none;',
            html: '<i class="fa fa-chevron-up"></i>'
        }).on('click', function (e) {
            e.preventDefault();
            $('html,body').animate({scrollTop: 0}, 500);
        }).appendTo('body');

        $(window).scroll(function () {
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                $totop.fadeIn(500);
            } else {
                $totop.fadeOut(500);
            }
        });

        this.totop = $totop;
    };

    Admin.prototype.reload = function () {
        $.pjax.reload('#pjax-container');
    };

    Admin.prototype.redirect = function (url) {
        $.pjax({container:'#pjax-container', url: url });
    };

    Admin.prototype.getToken = function () {
        return $('meta[name="csrf-token"]').attr('content');
    };

    Admin.prototype.loadScripts = function(arr) {
        var loaded = this.loadedScripts;
        var _arr = $.map(arr, function(src) {
            if ($.inArray(src, loaded) >= 0) {
                return;
            }

            loaded.push(src);

            return $.getScript(src);
        });

        _arr.push($.Deferred(function(deferred){
            $(deferred.resolve);
        }));

        return $.when.apply($, _arr);
    };

    Admin.prototype.loadCss = function (css) {
        var existingCss = $('link[rel=stylesheet]');
        $.map(css, function (href) {
            var matchedCss = existingCss.filter(function () {
                return this.getAttribute("href") === href
            });

            if (matchedCss.length === 0) {
                $("<link/>", {rel: "stylesheet", type: "text/css", href: href,})
                    .appendTo("head");
            }
        });
    };

    Admin.prototype.loadAssets = function (dep, js, css, callback) {
        var admin = this;

        return admin.loadScripts(dep).then(function () {
            admin.loadScripts(js).then(function () {
                admin.loadCss(css);
            }).then(callback);
        });
    };

    Admin.prototype.action = {
        then: function (data) {
            var response = data[0];
            var $target = data[1];

            if (typeof response !== 'object') {
                return $.admin.swal.fire({type: 'error', title: 'Oops!'});
            }

            if (typeof response.html === 'string') {
                $target.html(response.html);
            }

            if (typeof response.swal === 'object') {
                $.admin.swal.fire(response.swal);
            }

            if (typeof response.toastr === 'object' && response.toastr.type) {
                $.admin.toastr[response.toastr.type](response.toastr.content, response.toastr.options);
            }

            var then = function (then) {
                if (then.action === 'refresh') {
                    $.admin.reload();
                } else if (then.action === 'download') {
                    window.open(then.value, '_blank');
                } else if (then.action === 'redirect') {
                    $.admin.redirect(then.value);
                } else if (then.action === 'location') {
                    window.location = then.value;
                } else if (then.action === 'open') {
                    window.open(this.value, '_blank');
                }
            };

            if (response.then) {
                then(response.then);
            }
        },
        catch: function (request) {
            if (request && typeof request.responseJSON === 'object') {
                $.admin.toastr.error(request.responseJSON.message, {
                    position:"bottom",
                    timer: 10000
                }).css("width","500px")
            }
        }
    };

    Admin.prototype.initTable = function ($table) {
        this.table = new Table($table);
    };

    $.fn.admin = $.admin = new Admin();

})(jQuery);

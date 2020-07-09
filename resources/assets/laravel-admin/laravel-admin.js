(function ($) {

    toastr.options = {
        closeButton: true,
        progressBar: true,
        showMethod: 'slideDown',
        timeOut: 4000
    };

    $.pjax.defaults.timeout = 5000;
    $.pjax.defaults.maxCacheLength = 0;
    $(document).pjax('a:not(a[target="_blank"])', {
        container: '#pjax-container'
    });

    NProgress.configure({parent: '#app'});

    $(document).on('pjax:timeout', function (event) {
        event.preventDefault();
    });

    $(document).on('submit', 'form[pjax-container]', function (event) {
        $.pjax.submit(event, '#pjax-container')
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
        $('.sidebar-menu li:not(.treeview) > a').on('click', function () {
            var $parent = $(this).parent().addClass('active');
            $parent.siblings('.treeview.active').find('> a').trigger('click');
            $parent.siblings().removeClass('active').find('li').removeClass('active');
        });
        var menu = $('.sidebar-menu li > a[href$="' + (location.pathname + location.search + location.hash) + '"]').parent().addClass('active');
        menu.parents('ul.treeview-menu').addClass('menu-open');
        menu.parents('li.treeview').addClass('active');

        $('[data-toggle="popover"]').popover();

        // Sidebar form autocomplete
        $('.sidebar-form .autocomplete').on('keyup focus', function () {
            var $menu = $('.sidebar-form .dropdown-menu');
            var text = $(this).val();

            if (text === '') {
                $menu.hide();
                return;
            }

            var regex = new RegExp(text, 'i');
            var matched = false;

            $menu.find('li').each(function () {
                if (!regex.test($(this).find('a').text())) {
                    $(this).hide();
                } else {
                    $(this).show();
                    matched = true;
                }
            });

            if (matched) {
                $menu.show();
            }
        }).click(function(event){
            event.stopPropagation();
        });

        $('.sidebar-form .dropdown-menu li a').click(function (){
            $('.sidebar-form .autocomplete').val($(this).text());
        });
    })();

    var $top = $('<button/>', {
        id: 'totop',
        title: 'Go to top',
        style: 'display: none;',
        html: '<i class="fa fa-chevron-up"></i>'
    }).on('click', function (e) {
        e.preventDefault();
        $('html,body').animate({scrollTop: 0}, 500);
    }).appendTo('body');

    $(window).scroll(function() {
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            $top.fadeIn(500);
        } else {
            $top.fadeOut(500);
        }
    });

    $.fn.admin = LA;
    $.admin = LA;
    $.admin.swal = swal;
    $.admin.toastr = toastr;

    $.admin.reload = function () {
        $.pjax.reload('#pjax-container');
    };

    $.admin.redirect = function (url) {
        $.pjax({container:'#pjax-container', url: url });
    };

    $.admin.getToken = function () {
        return $('meta[name="csrf-token"]').attr('content');
    };

    $.admin.loadedScripts = [];

    $.admin.loadScripts = function(arr) {
        var _arr = $.map(arr, function(src) {
            if ($.inArray(src, $.admin.loadedScripts) >= 0) {
                return;
            }

            $.admin.loadedScripts.push(src);

            return $.getScript(src);
        });

        _arr.push($.Deferred(function(deferred){
            $(deferred.resolve);
        }));

        return $.when.apply($, _arr);
    };

    $.admin.loadCss = function (css) {
        var existingCss = $('link[rel=stylesheet]');
        $.map(css, function (href) {
            var matchedCss = existingCss.filter(function () {
                return this.getAttribute("href") === href
            });

            if (matchedCss.length === 0) {
                $("<link/>", {
                    rel: "stylesheet",
                    type: "text/css",
                    href: href,
                }).appendTo("head");
            }
        });
    };

    $.admin.loadAssets = function (js, css) {
        var admin = this;
        return admin.loadScripts(js).then(function () {
            admin.loadCss(css);
        });
    };

    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
        if (originalOptions.type === 'POST' || options.type === 'POST') {
            if (originalOptions.data instanceof FormData) {
                originalOptions.data.append('_token', LA.token);
                options.data = originalOptions.data;
            } else {
                options.data = $.param($.extend(originalOptions.data, { _token : LA.token}));
            }
        }
    });

    $.admin.action = {
        then: function (data) {
            var response = data[0];
            var $target = data[1];

            if (typeof response !== 'object') {
                return $.admin.swal({type: 'error', title: 'Oops!'});
            }

            if (typeof response.html === 'string') {
                $target.html(response.html);
            }

            if (typeof response.swal === 'object') {
                $.admin.swal(response.swal);
            }

            if (typeof response.toastr === 'object' && response.toastr.type) {
                $.admin.toastr[response.toastr.type](response.toastr.content, '', response.toastr.options);
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
                } else if (then.action === 'oepn') {
                    window.open(this.value, '_blank');
                }
            };

            if (response.then) {
                then(response.then);
            }
        },
        catch: function (request) {
            if (request && typeof request.responseJSON === 'object') {
                $.admin.toastr.error(request.responseJSON.message, '', {
                    positionClass:"toast-bottom-center",
                    timeOut: 10000
                }).css("width","500px")
            }
        }
    };

    $.admin.initTable = function ($table) {
        Object.assign($table.init.prototype, {
            selects: {},
            select: function (id) {
                this.selects[id] = id;
            },
            unselect: function (id) {
                delete this.selects[id];
            },
            selected: function () {
                var rows = [];
                $.each(this.selects, function (key, val) {
                    rows.push(key);
                });
                return rows;
            },
            clearSelected: function () {
                this.selects = {};
            },
        });
    };

})(jQuery);

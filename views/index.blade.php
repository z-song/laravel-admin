<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ Admin::title() }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/bootstrap/css/bootstrap.min.css") }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset("/packages/admin/font-awesome/css/font-awesome.min.css") }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/dist/css/skins/" . config('admin.skin') .".min.css") }}">

    {!! Admin::css() !!}
    <link rel="stylesheet" href="{{ asset("/packages/admin/nestable/nestable.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/toastr/build/toastr.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/bootstrap3-editable/css/bootstrap-editable.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/google-fonts/fonts.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/AdminLTE/dist/css/AdminLTE.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/packages/admin/nprogress/nprogress.min.css") }}">

    <!-- REQUIRED JS SCRIPTS -->
    <script src="{{ asset ("/packages/admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
    <script src="{{ asset ("/packages/admin/AdminLTE/bootstrap/js/bootstrap.min.js") }}"></script>
    <script src="{{ asset ("/packages/admin/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js") }}"></script>
    <script src="{{ asset ("/packages/admin/AdminLTE/dist/js/app.min.js") }}"></script>
    <script src="{{ asset ("/packages/admin/jquery-pjax/jquery.pjax.js?v=6") }}"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .file-drag-handle{
            display:none;
        }
    </style>

</head>

<body class="hold-transition {{config('admin.skin')}} {{join(' ', config('admin.layout'))}}">
<div class="wrapper">

    @include('admin::partials.header')

    @include('admin::partials.sidebar')

    <div class="content-wrapper" id="pjax-container">
        @yield('content')
        {!! Admin::script() !!}
    </div>

    @include('admin::partials.footer')

</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<script src="{{ asset ("/packages/admin/AdminLTE/plugins/chartjs/Chart.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/nestable/jquery.nestable.js") }}"></script>
<script src="{{ asset ("/packages/admin/toastr/build/toastr.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/bootstrap3-editable/js/bootstrap-editable.min.js") }}"></script>
<script src="{{ asset ("/packages/admin/nprogress/nprogress.min.js") }}"></script>

{!! Admin::js() !!}

<script>

    $(function(){

        var LA = {

            token : "{{ csrf_token() }}",

            removeFormErrorLabel : function (form){
                $(form).find('.form-group.has-error').each(function(){
                    var $this = $(this);
                    $this.removeClass('has-error').find('.control-label.validation').remove();
                    $this.closest('.tab-pane').each(function(){
                        $('ul.nav a[href="#' + this.id +'"]').data('has-error', 0).find('i').remove();
                    })
                })
            },

            addFormErrorLabel : function (form, validation){
                var $form = $(form);
                $.each(validation, function(key,messages){
                    var target = $form.find('[name="'+LA.formatElementNameByErrorKey(key)+'"]').size() ? $form.find('[name="'+LA.formatElementNameByErrorKey(key)+'"]') : $form.find('[name="'+LA.formatElementNameByErrorKey(key)+'[]"]');
                    target.closest('.form-group').addClass('has-error');
                    $.each(messages, function(index, message){
                        target.closest('.form-group-fields').prepend('<label class="control-label validation" for="inputError"><i class="fa fa-times-circle-o"></i> '+ message +'</label>');
                    });
                });

                $('.has-error').closest('.tab-pane').each(function(){
                    var tabA = $('ul.nav a[href="#' + this.id +'"]');
                    if(!tabA.data('has-error')){
                        tabA.data('has-error', 1);
                        tabA.append('<i class="fa fa-exclamation-circle text-red"></i>');
                    }
                })

            },

            formatElementNameByErrorKey : function (key){
                var names = key.split('.');
                var name = names.shift();
                $.each(names, function(k, n){
                    name += '['+ n + ']';
                });
                return name;
            }

        }

        $.fn.editable.defaults.params = function (params) {
            params._token = '{{ csrf_token() }}';
            params._editable = 1;
            params._method = 'PUT';
            return params;
        };

        toastr.options = {
            "newestOnTop": true,
            "closeButton": true,
            "showMethod": 'slideDown',
            "preventDuplicates": true,
            "timeOut": 4000
        };

        $.pjax.defaults.timeout = 10000
        $.pjax.defaults.maxCacheLength = 0

        var $document = $(document);

        $document.pjax('a:not(a[target="_blank"])', {
            container: '#pjax-container'
        });

        $document.on('submit','form[pjax-container]', function(event) {
            $.pjax.submit(event, '#pjax-container')
        })

        $document.on({
            "pjax:start": function() {
                NProgress.start();
                LA.removeFormErrorLabel($('form[pjax-container]'))
            },

            "pjax:send": function(xhr) {
                if(xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
                    var $submitBtn = $('form[pjax-container] :submit');
                    if($submitBtn) {
                        $submitBtn.button('loading')
                    }
                }
            },
            "pjax:complete": function(xhr) {
                if(xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
                    var $submitBtn = $('form[pjax-container] :submit');
                    if($submitBtn) {
                        $submitBtn.button('reset')
                    }
                }
            },
            'pjax:success_object': function(event,data, status, xhr, options){
                var form = $('form[pjax-container]');
                if(typeof(data.status) === 'string'){
                    switch(data.status.toLowerCase())
                    {
                        case 'success':
                            form.closest('.box-form').find('.btn-success-redirect').trigger('click');
                            toastr['success'](data.message);
                            break;
                        case 'warning':
                            toastr['warning'](data.message);
                            break;
                        case 'error':
                            toastr['error'](data.message, null, {"positionClass": "toast-top-full-width", "timeOut": 0});
                            LA.addFormErrorLabel(form, data.extra);
                            break;
                        default:
                            toastr['info'](data.message);
                    }
                }
            },
            "pjax:end": function(event) {
                $(event.target).find("script[data-exec-on-popstate]").each(function() {
                    $.globalEval(this.text || this.textContent || this.innerHTML || '');
                });
                NProgress.done();
            },
            "ajaxStart": function() { NProgress.start();},
            "ajaxStop": function() { NProgress.done(); }
        });

        $(document).on('pjax:error', function(event, xhr) {
            var message = '';
            try{
                response = JSON.parse(xhr.responseText);
                message = response.message || 'error';
            }catch(e){
                if (xhr.status == 0) {
                    return;
                }
                toastr['warning'](xhr.statusText,'Warning!')
                return false;
            }
            if (message) {
                toastr['warning'](message,'Warning!')
            }
            return false;
        });

        function activeMenu( li) {
            li.addClass('active').siblings().removeClass('active').find('ul.treeview-menu').removeClass('menu-open');
            return li;
        }
        $('.sidebar-menu li:not(.treeview) > a').off('click').on('click', function(){
            var li = $(this).closest('li');
            while( activeMenu(li).size() && (! li.parent().hasClass('sidebar-menu'))){
                li = li.parent().closest('li');
            }
        });
    });

</script>

</body>
</html>

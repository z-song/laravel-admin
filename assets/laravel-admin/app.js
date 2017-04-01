/**
 * Created by Edwin Hui on 2017/4/1.
 */
$.fn.editable.defaults.params = function (params) {
    params._token = LA.token;
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

$.pjax.defaults.timeout = 5000
$.pjax.defaults.maxCacheLength = 0

LA.removeFormErrorLabel = function (form){
    $(form).find('.form-group.has-error').each(function(){
        var $this = $(this);
        $this.removeClass('has-error').find('.control-label.validation').remove();
        $this.closest('.tab-pane').each(function(){
            $('ul.nav a[href="#' + this.id +'"]').data('has-error', 0).find('i').remove();
        })
    })
}
LA.addFormErrorLabel = function (form, validation){
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
        if(!tabA.find('i').size()){
            tabA.append('<i class="fa fa-exclamation-circle text-red"></i>');
        }
    })

}
LA.formatElementNameByErrorKey = function (key){
    var names = key.split('.');
    var name = names.shift();
    $.each(names, function(k, n){
        name += '['+ n + ']';
    });
    return name;
}
LA.activeSidebar = function ( li) {
    li.addClass('active').siblings().removeClass('active').find('ul.treeview-menu').removeClass('menu-open');
    return li;
}

$(function(){

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
        'pjax:success_object': function(event, data, status, xhr, options){
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
                        if (xhr.readyState > 0 && options.push && !options.replace) {
                            window.history.replaceState(null, "", $.pjax.state.url)
                        }
                        break;
                    default:
                        toastr['info'](data.message);
                }
            }
        },
        'pjax:error':function(event, xhr) {
            var message = '';
            try{
                var response = JSON.parse(xhr.responseText);
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
        },
        "pjax:end": function(event) {
            $(event.target).find("script[data-exec-on-popstate]").each(function() {
                $.globalEval(this.text || this.textContent || this.innerHTML || '');
            });
            NProgress.done();
        }
    });

    $('.sidebar-menu li:not(.treeview) > a').off('click').on('click', function(){
        var li = $(this).closest('li');
        while( LA.activeSidebar(li).size() && (! li.parent().hasClass('sidebar-menu'))){
            li = li.parent().closest('li');
        }
    });
})
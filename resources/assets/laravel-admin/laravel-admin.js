$.fn.editable.defaults.params = function (params) {
    params._token = '{{ csrf_token() }}';
    params._editable = 1;
    params._method = 'PUT';
    return params;
};

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

$(document).on('submit', 'form[pjax-container]', function(event) {
    $.pjax.submit(event, '#pjax-container')
});

$(document).on("pjax:popstate", function() {

    $(document).one("pjax:end", function(event) {
        $(event.target).find("script[data-exec-on-popstate]").each(function() {
            $.globalEval(this.text || this.textContent || this.innerHTML || '');
        });
    });
});

$(document).on('pjax:send', function(xhr) {
    if(xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
        $submit_btn = $('form[pjax-container] :submit');
        if($submit_btn) {
            $submit_btn.button('loading')
        }
    }
})

$(document).on('pjax:complete', function(xhr) {
    if(xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
        $submit_btn = $('form[pjax-container] :submit');
        if($submit_btn) {
            $submit_btn.button('reset')
        }
    }
})

$(function(){
    $('.sidebar-menu li:not(.treeview) > a').on('click', function(){
        var $parent = $(this).parent().addClass('active');
        $parent.siblings('.treeview.active').find('> a').trigger('click');
        $parent.siblings().removeClass('active').find('li').removeClass('active');
    });
});
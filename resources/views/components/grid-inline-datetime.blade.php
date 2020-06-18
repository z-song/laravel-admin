<div>
    <a href="javascript:void(0);" class="grid-column-datetime text-muted" data-key="{{ $key }}">
        <i class="fa fa-clock-o"></i>
        <span id="dp-display-{{ $key }}">{{ $value }}</span>
    </a>&nbsp;
    <template >
        <div id="dp-{{ $key }}" style="height: 290px;width: 250px;position: relative;">
            <input id="dp-input-{{ $key }}" value="{{ $value }}" class="hide" />
        </div>
        <div>
            <button class="btn btn-primary pull-right dp-submit" style="margin: 10px 0px 10px 10px;" data-key="{{ $key }}">{{ __('admin.submit') }}</button>
            <button class="btn btn-default pull-right" style="margin: 10px 0px 10px 10px;">{{ __('admin.cancel') }}</button>
        </div>
    </template>
</div>

<script>
$('.grid-column-datetime').popover({
    html: true,
    container: 'body',
    trigger: 'focus',
    placement: function (context, source) {
        var position = $(source).position();

        if (position.left > 515) {
            return "left";
        }

        if (position.left < 515) {
            return "right";
        }

        if (position.top < 110){
            return "bottom";
        }

        return "top";
    },
    content: function () {
        if (this.cache) {
            return this.cache;
        }

        return this.cache = $(this).parent().find('template').html();
    }
}).on('shown.bs.popover', function () {
    var key = $(this).data('key');

    var $picker = $('#dp-'+key);
    var $input = $('#dp-input-'+key);
    var $display = $('#dp-display-'+key);

    $picker.datetimepicker({
        inline: true,
        format: '{{ $format }}',
        date: $input.val(),
        locale: '{{ $locale }}'
    }).on('dp.change', function(event) {
        var date = event.date.format('{{ $format }}');
        $input.val(date);
        $display.html(date);
    });
});

$(document).on('click', '.dp-submit', function () {
    var key = $(this).data('key');

    console.log(key);

    $.ajax({
        url: "{{ $resource }}/" + key,
        type: "POST",
        data: {
            {{ $name }}: $('#dp-input-'+key).val(),
            _token: LA.token,
            _method: 'PUT'
        },
        success: function (data) {
            toastr.success(data.message);
        }
    });
});
</script>

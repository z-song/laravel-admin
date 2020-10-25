<div {!! admin_attrs($group_attrs) !!}>

<label for="{{$id}}" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <input type="hidden" name="{{$name}}"/>

        <select class="form-control {{$class}}" style="width: 100%;" name="{{$name}}" {!! $attributes !!} >
            @if($groups)
                @foreach($groups as $group)
                    <optgroup label="{{ $group['label'] }}">
                        @foreach($group['options'] as $select => $option)
                            <option value="{{$select}}" {!! $optionDataAttributes ? $optionDataAttributes[$select] : '' !!} {{ $select == $value ?'selected':'' }}>{{$option}}</option>
                        @endforeach
                    </optgroup>
                @endforeach
             @else
                <option value=""></option>
                @foreach($options as $select => $option)
                    <option value="{{$select}}" {!! $optionDataAttributes ? $optionDataAttributes[$select] : '' !!} {{ $select == $value ?'selected':'' }}>{{$option}}</option>
                @endforeach
            @endif
        </select>
        @include('admin::form.error')
        @include('admin::form.help-block')

    </div>
</div>

<script require="select2" @script>

    $(this).select2(@json($configs));

    @isset($load)
    $(this).off('change').on('change', function () {
        var target = $(this).closest('.fields-group').find(".{{ $load['class'] }}");
        $.get("{{ $load['sourceUrl'] }}",{q : this.value}, function (data) {
            target.find("option").remove();
            $(target).select2({
                placeholder: {id:'', text: '{{ admin_trans('admin.choose') }}'},
                allowClear: {{ $strAllowClear }},
                data: $.map(data, function (d) {
                    d.id = d.{{$idField}};
                    d.text = d.{{$textField}};
                    return d;
                })
            });
            if (target.data('value')) {
                $(target).val(target.data('value'));
            }
            $(target).trigger('change');
    });
    @endisset

    @isset($loads)
    var fields = '{{$fieldsStr}}'.split('.');
    var urls = '{{$urlsStr}}'.split('^');

    var refreshOptions = function(url, target) {
        $.get(url).then(function(data) {
            target.find("option").remove();
            $(target).select2({
                placeholder: {id:'', text: '{{ admin_trans('admin.choose') }}'},
                allowClear: {{$strAllowClear}},
                data: $.map(data, function (d) {
                    d.id = d.{{$idField}};
                    d.text = d.{{$textField}};
                    return d;
                })
            }).trigger('change');
        });
    };

    $(this).off('change').on('change' , function () {
        var _this = this;
        var promises = [];

        fields.forEach(function(field, index){
            var target = $(_this).closest('.fields-group').find('.' + fields[index]);
            promises.push(refreshOptions(urls[index] + "?q="+ _this.value, target));
        });
    });
    @endisset

    @isset($remote)
    $.ajax(@json($options)).done(function(data) {
        $(this).select2({
            data: data,
            {{ $configs }}
        });
        var value = $(this).data('value') + '';
        if (value) {
            $(this).select2('val', value.split(','));
        }
    });
    @endisset

    @isset($ajax)
    $(this).select2({
        ajax: {
            url: "{{ $ajax['url'] }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: $.map(data.data, function (d) {
                        d.id = d.{{ $ajax['idField'] }};
                        d.text = d.{{ $ajax['textField'] }};
                        return d;
                    }),
                    pagination: {
                        more: data.next_page_url
                    }
                };
            },
            cache: true
        },
        {!! $ajax['configs'] !!},
        escapeMarkup: function (markup) {
            return markup;
        }
    });
    @endisset

</script>


@isset($readonly)
<script>
    //移除特定字段名称,增加MultipleSelect的修订
    //没有特定字段名可以使多个readonly的JS代码片段被Admin::script的array_unique精简代码
    $("form select").on("select2:opening", function (e) {
        if($(this).attr('readonly') || $(this).is(':hidden')){
            e.preventDefault();
        }
    });
    $('select').each(function(){
        if($(this).is('[readonly]')){
            var $formgroup = $(this).closest('.form-group');
            $formgroup.find('span.select2-selection__choice__remove').first().remove();
            $formgroup.find('li.select2-search').first().remove();
            $formgroup.find('span.select2-selection__clear').first().remove();
        }
    });
</script>
@endisset

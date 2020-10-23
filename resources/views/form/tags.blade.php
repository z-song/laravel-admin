<div {!! admin_attrs($group_attrs) !!}>

    <label for="{{$id}}" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <select class="form-control {{$class}}" style="width: 100%;" name="{{$name}}[]" multiple="multiple" data-placeholder="{{ $placeholder }}" {!! $attributes !!} >

            @foreach($options as $key => $option)
                <option value="{{ $keyAsValue ? $key : $option}}" {{ in_array($option, $value) ? 'selected' : '' }}>{{$option}}</option>
            @endforeach

        </select>
        <input type="hidden" name="{{$name}}[]" />

        @include('admin::form.error')
        @include('admin::form.help-block')

    </div>
</div>

<script require="select2" @script>
    $(this).select2({
        tags: true,
        tokenSeparators: @json($separators),
        createTag: function(params) {
            if (/[{{ $separatorsStr }}]/.test(params.term)) {
                var str = params.term.trim().replace(/[{{ $separatorsStr }}]*$/, '');
                return { id: str, text: str }
            } else {
                return null;
            }
        }
    });
</script>

<script>
    $(document).off('keyup', '.select2-selection--multiple .select2-search__field').on('keyup', '.select2-selection--multiple .select2-search__field', function (event) {
        try {
            if (event.keyCode == 13) {
                var $this = $(this), optionText = $this.val();
                if (optionText != "" && $this.find("option[value='" + optionText + "']").length === 0) {
                    var $select = $this.parents('.select2-container').prev("select");
                    var newOption = new Option(optionText, optionText, true, true);
                    $select.append(newOption).trigger('change');
                    $this.val('');
                    $select.select2('close');
                }
            }
        } catch (e) {
            console.error(e);
        }
    });
</script>

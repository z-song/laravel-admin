<div class="{{$viewClass['form-group']}}">
    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <div class="input-group" style="width: 300px;">
            <input {!! $attributes !!} />
             @if ($append)
                <span class="input-group-append">{!! $append !!}</span>
            @endif
        </div>
        @include('admin::form.help-block')
    </div>
</div>

<script require="bootstrap-input-spinner">
    $('{{ $selector}}:not(.initialized)')
        .addClass('initialized')
        .inputSpinner();
</script>

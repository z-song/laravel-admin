<div {!! admin_attrs($group_attrs) !!}>
    <label for="{{$id}}" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>
    <div class="{{$viewClass['field']}} row">
        <div class="col" data-field="{{$group_attrs['data-field']}}">
            <div class="input-group" style="width: 300px;">
                <input {!! $attributes !!} />
                 @if ($append)
                    <span class="input-group-append">{!! $append !!}</span>
                @endif
            </div>
            @include('admin::form.error')
            @include('admin::form.help-block')
        </div>

        @foreach($after ?? [] as $content)
            {!!  $content->renderAfter()  !!}
        @endforeach
    </div>
</div>

<script require="bootstrap-input-spinner" @script>
    $(this).inputSpinner();
</script>

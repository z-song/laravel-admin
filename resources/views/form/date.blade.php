<div {!! admin_attrs($group_attrs) !!}>
    <label for="{{$id}}" class="{{$viewClass['label']}} col-form-label">
        {{$label}}
    </label>
    <div class="{{$viewClass['field']}} mx-0 row">
        <div class="col" data-field="{{$group_attrs['data-field']}}">
            <div class="input-group"  style="width: 250px;">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="far {{ $icon }} fa-w"></i>
                    </span>
                </div>
                <input {!! $attributes !!} />
            </div>
            @include('admin::form.error')
            @include('admin::form.help-block')
        </div>

        @foreach($after ?? [] as $content)
            {!!  $content->renderAfter()  !!}
        @endforeach
    </div>
</div>

<script require="datetimepicker" @script>
    $(this).datetimepicker(@json($options));
</script>

<div {!! admin_attrs($group_attrs) !!}>
    <label for="{{$id}}" class="{{$viewClass['label']}} col-form-label">
        {{$label}}
    </label>
    <div class="{{$viewClass['field']}} mx-0 row">
        @foreach($fields as $key => $field)
            <div class="col" data-field="{!! $key !!}">
                {!! $field->renderInline() !!}
            </div>
        @endforeach
    </div>
</div>

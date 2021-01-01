<div class="row mt-3">
    @foreach($fields as $field)
    <div class="col-{{ $field['width'] }}">
        {!! $field['element']->render() !!}
    </div>
    @endforeach
</div>

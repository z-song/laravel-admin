<div class="row">
    <div class="col">
        {!! $panel !!}
    </div>
</div>
<div class="row">
    <div class="col">
        @foreach($relations as $relation)
            {!!  $relation->render() !!}
        @endforeach
    </div>
</div>

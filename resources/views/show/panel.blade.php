<div class="panel panel-{{ $style }}">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-right">{{ $title }}</h3>
        <div class="pull-left">
            {!! $tools !!}
        </div>
    </div>
    <ul class="list-group">
        @foreach ($fields as $field)
            {!! $field->render() !!}
        @endforeach
    </ul>
</div>

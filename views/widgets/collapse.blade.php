<div class="box-group" id="accordion" style="margin-bottom: 20px">
    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->

    @foreach($items as $key => $item)
    <div class="panel box box-primary" style="margin-bottom: 0px">
        <div class="box-header with-border">
            <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $key }}">
                    {{ $item['title'] }}
                </a>
            </h4>
        </div>
        <div id="collapse{{ $key }}" class="panel-collapse collapse {{ $key == 0 ? 'in' : '' }}">
            <div class="box-body">
                {!! $item['content'] !!}
            </div>
        </div>
    </div>
    @endforeach

</div>

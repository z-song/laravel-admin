<div class="form-group ">
    <label class="col-sm-{{$width['label']}} control-label">{{ $label }}</label>
    <div class="col-sm-{{$width['field']}}">
        @if($wrapped)
        <div class="card card-solid no-margin card-show">
            <!-- /.card-header -->
            <div class="card-body">
                @if($escape)
                    {{ $content }}&nbsp;
                @else
                    {!! $content !!}&nbsp;
                @endif
            </div><!-- /.card-body -->
        </div>
        @else
            @if($escape)
                {{ $content }}
            @else
                {!! $content !!}
            @endif
        @endif
    </div>
</div>

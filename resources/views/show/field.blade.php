<div class="form-group row mb-3">
    <label class="col-{{$width['label']}} col-form-label text-right">{{ $label }}</label>
    <div class="col-{{$width['field']}}">
        @if($wrapped)
        <div class="card card-solid m-0 card-show">
            <!-- /.card-header -->
            <div class="card-body py-2 px-3">
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



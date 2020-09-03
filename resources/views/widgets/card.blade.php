<div {!! $attributes !!}>
    @if($title || $tools)
        <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>
            <div class="card-tools float-right">
                @foreach($tools as $tool)
                    {!! $tool !!}
                @endforeach
            </div><!-- /.card-tools -->
        </div><!-- /.card-header -->
    @endif
    <div class="card-body" style="display: block;">
        {!! $content !!}
    </div><!-- /.card-body -->
    @if($footer)
        <div class="card-footer">
            {!! $footer !!}
        </div><!-- /.card-footer-->
    @endif
</div>
{{-- 由于widget box 有可能会用于expand，加载完页面后还没有对应的html，导致script失败，故只能和html写在一起。 --}}
<script>
    {!! $script !!}
</script>

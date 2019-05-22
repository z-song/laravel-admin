<div {!! $attributes !!}>
    <div class="box-header with-border">
        <h3 class="box-title">{{ $title }}</h3>
        <div class="box-tools pull-right">
            @foreach($tools as $tool)
                {!! $tool !!}
                @endforeach
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    <div class="box-body" style="display: block;">
        {!! $content !!}
    </div><!-- /.box-body -->
</div>
{{-- 由于widget box 有可能会用于expand，加载完页面后还没有对应的html，导致script失败，故只和html写在一起。 --}}
<script>
    {!! $script !!}
</script>
<div {!! $attributes !!}>
    <div class="inner">
        <h3>{{ $info }}</h3>
        <p>{{ $name }}</p>
    </div>
    <div class="icon">
        <i class="fa fa-{{ $icon }}"></i>
    </div>
    <a href="{{ $link }}" class="small-card-footer">
        {{ admin_trans('admin.more') }}&nbsp;
        <i class="fa fa-arrow-circle-right"></i>
    </a>
</div>

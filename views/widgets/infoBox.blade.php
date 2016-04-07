<div class="small-box bg-{{ $color }}">
    <div class="inner">
        <h3>{{ $info }}</h3>

        <p>{{ $name }}</p>
    </div>
    <div class="icon">
        <i class="fa fa-{{ $icon }}"></i>
    </div>
    <a href="{{ $link }}" class="small-box-footer">
        {{ \Illuminate\Support\Facades\Lang::get('admin::lang.more') }}&nbsp;
        <i class="fa fa-arrow-circle-right"></i>
    </a>
</div>
@foreach($boxes as $box)
<div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-{{ $box['color'] }}">
        <div class="inner">
            <h3>{{ $box['info'] }}</h3>

            <p>{{ $box['name'] }}</p>
        </div>
        <div class="icon">
            <i class="fa fa-{{ $box['icon'] }}"></i>
        </div>
        <a href="{{ $box['link'] }}" class="small-box-footer">{{ \Illuminate\Support\Facades\Lang::get('admin::lang.more') }}&nbsp;<i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
@endforeach
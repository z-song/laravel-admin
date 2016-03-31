<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $title }}</h3>
    </div><!-- /.box-header -->
    <div class="box-body">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">

                @foreach($items as $key => $item)
                <li data-target="#carousel-example-generic" data-slide-to="{{$key}}" class="{{ $key == 0 ? 'active' : '' }}"></li>
                @endforeach

            </ol>
            <div class="carousel-inner">

                @foreach($items as $key => $item)
                <div class="item {{ $key == 0 ? 'active' : '' }}">
                    <img src="{{ url($item['image']) }}" alt="{{$item['caption']}}">
                    <div class="carousel-caption">
                        {{$item['caption']}}
                    </div>
                </div>
                @endforeach

            </div>
            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                <span class="fa fa-angle-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                <span class="fa fa-angle-right"></span>
            </a>
        </div>
    </div><!-- /.box-body -->
</div>
<style>
    .ext-icon {
        color: rgba(0,0,0,0.5);
        margin-left: 10px;
    }
    .installed {
        color: #00a65a;
        margin-right: 10px;
    }
</style>
<div class="card card-@color card-outline">
    <div class="card-header">
        <h3 class="card-title">Available extensions</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <ul class="products-list product-list-in-card">

            @foreach($extensions as $extension)
            <li class="item">
                <div class="product-img">
                    <i class="{{$extension['icon']}} fa-2x ext-icon"></i>
                </div>
                <div class="product-info">
                    <a href="{{ $extension['link'] }}" target="_blank" class="product-title">
                        {{ $extension['name'] }}
                    </a>
                    @if($extension['installed'])
                        <span class="float-right installed"><i class="fa fa-check"></i></span>
                    @endif
                </div>
            </li>
            @endforeach

            <!-- /.item -->
        </ul>
    </div>
    <!-- /.card-body -->
    <div class="card-footer text-center">
        <a href="https://github.com/laravel-admin-extensions" target="_blank" class="uppercase">View All Extensions</a>
    </div>
    <!-- /.card-footer -->
</div>

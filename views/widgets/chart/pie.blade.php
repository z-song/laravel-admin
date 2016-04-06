<div class="row">
    <div class="col-md-9">
        <div class="chart-responsive">
            <canvas id="{{ $id }}" style="height: 100%; width: 100%;"></canvas>
        </div><!-- ./chart-responsive -->
    </div><!-- /.col -->
    <div class="col-md-3">
        <ul class="chart-legend clearfix">
            @foreach($data as $item)
            <li><i class="fa fa-circle-o" style="color: {{ $item['color'] }} !important;"></i> {{ $item['label'] }}</li>
            @endforeach
        </ul>
    </div>
</div>
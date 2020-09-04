<div class="box">
    <div class="box-header"></div>

    {!! $grid->renderFilter() !!}

    <div class="box-body table-responsive no-padding">
        <ul class="image clearfix">
            @foreach($grid->rows() as $row)
            <li>
                <label>
                    {!! $row->column($key) !!}
                    {!! $row->column('__modal_selector__') !!}
                </label>
            </li>
            @endforeach
        </ul>
    </div>

    <div class="box-footer clearfix">
        {!! $grid->paginator() !!}
    </div>
    <!-- /.box-body -->
</div>

<style>
ul.image {
    padding: 0px;
}

.image li {
    float: left;
    margin: 10px;
    list-style-type:none;
    position: relative;
}

.image label {
    cursor: pointer;
}

.image .img-thumbnail {
    padding-right: 15px;
}

.image .iradio_minimal-blue,.image .icheckbox_minimal-blue {
    position: absolute;
    bottom: 5px;
    right: 1px;
    background-color: #ffffff;
}
</style>

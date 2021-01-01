<div class="card">
    <div class="card-header"></div>

    {!! $table->renderFilter() !!}

    <div class="card-body table-responsive p-0">
        <ul class="image clearfix">
            @foreach($table->rows() as $row)
            <li class="d-inline-block">
                <label>
                    {!! $row->column($key) !!}
                    {!! $row->column('__modal_selector__') !!}
                </label>
            </li>
            @endforeach
        </ul>
    </div>

    <div class="card-footer clearfix">
        {!! $table->paginator() !!}
    </div>
    <!-- /.card-body -->
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

.image [class*=icheck-] {
    position: absolute;
    bottom: 5px;
    right: 1px;
    background-color: #ffffff;
}
</style>

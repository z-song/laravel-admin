<style>

    .table-selector .wrap {
        position: relative;
        line-height: 34px;
        border-bottom: 1px dashed #eee;
        padding: 0 30px;
        font-size: 13px;
        overflow:auto;
    }

    .table-selector .wrap:last-child {
        border-bottom: none;
    }

    .table-selector .select-label {
        float: left;
        width: 100px;
        padding-left: 10px;
        color: #999;
    }

    .table-selector .select-options {
        margin-left: 100px;
    }

    .table-selector ul {
        height: 25px;
        list-style: none;
    }

    .table-selector ul > li {
        margin-right: 30px;
        float: left;
    }

    .table-selector ul > li a {
        color: #666;
        text-decoration: none;
    }

    .table-selector .select-options a.active {
        color: #dd4b39;
        font-weight: 600;
    }

    .table-selector li .add {
        visibility: hidden;
    }

    .table-selector li:hover .add {
        visibility: visible;
    }

    .table-selector ul .clear {
        visibility: hidden;
    }

    .table-selector ul:hover .clear {
        color: #3c8dbc;
        visibility: visible;
    }
</style>

<div class="table-selector">
    @foreach($selectors as $column => $selector)
        <div class="wrap">
            <div class="select-label">{{ $selector['label'] }}</div>
            <div class="select-options">
                <ul>
                    @foreach($selector['options'] as $value => $option)
                        @php
                            $active = in_array($value, \Illuminate\Support\Arr::get($selected, $column, []));
                        @endphp
                        <li>
                            <a href="{{ \Encore\Admin\Table\Tools\Selector::url($column, $value, true) }}"
                               class="{{$active ? 'active' : ''}}">{{ $option }}</a>
                            @if(!$active && $selector['type'] == 'many')
                                &nbsp;
                                <a href="{{ \Encore\Admin\Table\Tools\Selector::url($column, $value) }}" class="add"><i
                                            class="fa fa-plus-square-o"></i></a>
                            @else
                                <a style="visibility: hidden;"><i class="fa fa-plus-square-o"></i></a>
                            @endif
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ \Encore\Admin\Table\Tools\Selector::url($column) }}" class="clear"><i
                                    class="fa fa-trash"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    @endforeach
</div>



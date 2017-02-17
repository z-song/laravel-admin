<div class="form-inline" style="padding-top: 10px;">
    <form action="{!! $action !!}" method="get" pjax-container>
        <fieldset>

            @foreach($filters as $filter)
                {!! $filter->render() !!}
            @endforeach

            <div class="input-group input-group-sm gird-filter-input-btn">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
                <a href="{!! $action !!}" class="btn btn-sm btn-warning" ><i class="fa fa-undo"></i></a>
            </div>

        </fieldset>
    </form>
</div>
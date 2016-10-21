<div class="form-inline pull-right">
    <form action="/{!! $grid->resource() !!}" method="get" pjax-container>
        <fieldset>

            @foreach($filters as $filter)
                {!! $filter->render() !!}
            @endforeach

            <div class="input-group input-group-sm">
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
            </div>

        </fieldset>
    </form>
</div>
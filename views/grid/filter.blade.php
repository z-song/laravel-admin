<div class="form-inline pull-right">
    <form action="{!! $action !!}" method="get" pjax-container>
        <fieldset>

            @foreach($filters as $filter)
                {!! $filter->render() !!}
            @endforeach

            <div class="input-group input-group-sm">
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
                <div class="input-group-btn">
                    <a href="{!! $action !!}" class="btn btn-warning" ><i class="fa fa-undo"></i></a>
                </div>
            </div>

        </fieldset>
    </form>
</div>
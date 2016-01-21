<div class="form-inline pull-right">
    <form action="" method="get">
        <fieldset>

            @foreach($fields as $field)
                {!! $field->render() !!}
            @endforeach

            <div class="input-group input-group-sm">
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
            </div>

        </fieldset>
    </form>
</div>
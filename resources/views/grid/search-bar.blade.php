<div class="input-group input-group-sm" style="display: inline-block;">
    <form action="{!! $action !!}" pjax-container method="get">
    <input type="text" name="{{ $key }}" class="form-control" style="width: 150px;" value="{{ $value }}">

    <div class="input-group-btn" style="display: inline-block;">
        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
    </div>
</form>
</div>
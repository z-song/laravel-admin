<form action="{!! $action !!}" pjax-container class="d-inline-block">
    <div class="input-group input-group-sm">
        <input type="text" name="{{ $key }}" class="form-control table-quick-search" style="width: 200px;" value="{{ $value }}" placeholder="{{ $placeholder }}">
        <div class="input-group-append" style="display: inline-block;">
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
        </div>
    </div>
</form>

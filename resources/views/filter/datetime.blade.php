<div class="input-group input-group-sm">
    <div class="input-group-addon">
        <i class="fa fa-calendar"></i>
    </div>
    <input class="form-control" id="{{$id}}" placeholder="{{$label}}" name="{{$name}}" value="{{ request($name, $value) }}">
</div>
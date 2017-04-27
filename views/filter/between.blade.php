<div class="input-group input-group-sm" style="width: 250px;">
    <span class="input-group-addon"><strong>{{$label}}</strong></span>
    <input type="text" class="form-control" placeholder="{{$label}}" name="{{$name['start']}}" value="{{ request($name['start'], array_get($value, 'start')) }}" style="width: 75px">
    <span class="input-group-addon" style="border-left: 0; border-right: 0;">-</span>
    <input type="text" class="form-control" placeholder="{{$label}}" name="{{$name['end']}}" value="{{ request($name['end'], array_get($value, 'end')) }}" style="width: 75px">
</div>
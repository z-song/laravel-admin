<div class="input-group input-group-sm">
    <span class="input-group-addon"><strong>{{$label}}</strong></span>
    <input type="text" class="form-control" id="{{$id['start']}}" placeholder="{{$label}}" name="{{$name['start']}}" value="{{ request($name['start'], array_get($value, 'start')) }}">
    <span class="input-group-addon" style="border-left: 0; border-right: 0;">-</span>
    <input type="text" class="form-control" id="{{$id['end']}}" placeholder="{{$label}}" name="{{$name['end']}}" value="{{ request($name['end'], array_get($value, 'end')) }}">
</div>
<div class="input-group input-group-sm">
    <span class="input-group-addon"><strong>{{$label}}</strong></span>
    <input type="text" class="form-control" placeholder="{{$label}}" name="{{$name['start']}}" value="{{ request($name['start'], $value['start']) }}" style="width: 75px">
    <span class="input-group-addon" style="border-left: 0; border-right: 0;">-</span>
    <input type="text" class="form-control" placeholder="{{$label}}" name="{{$name['end']}}" value="{{ request($name['end'], $value['end']) }}" style="width: 75px">
</div>
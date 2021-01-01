<div class="form-group row">
    <label class="col-2 col-form-label text-right">{{$label}}</label>
    <div class="col-8" style="width: 390px">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="{{$label}}" name="{{$name['start']}}" value="{{ request()->input("{$column}.start", \Illuminate\Support\Arr::get($value, 'start')) }}">
            <span class="input-group-addon" style="border-left: 0; border-right: 0;">-</span>
            <input type="text" class="form-control" placeholder="{{$label}}" name="{{$name['end']}}" value="{{ request()->input("{$column}.end", \Illuminate\Support\Arr::get($value, 'end')) }}">
        </div>
    </div>
</div>

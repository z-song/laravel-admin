<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

<label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')

        <select class="form-control " style="width: 100%;" id="{{$id}}" name="{{$name}}" {!! $attributes !!} >
            @foreach($options as $select => $option)
                <option value="{{$select}}" {{ $select == old($column, $value) ?'selected':'' }}>{{$option}}</option>
            @endforeach
        </select>

        @include('admin::form.help-block')

    </div>
</div>
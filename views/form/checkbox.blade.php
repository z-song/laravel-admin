<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6" id="{{$id}}">

        @include('admin::form.error')

        @foreach($values as $option => $label)
        <input type="checkbox" name="{{$name}}" value="{{$option}}" class="{{$id}}" {{ Input::old($column, $value)==$option?'checked':''; }}>{{$label}}
        @endforeach
    </div>
</div>
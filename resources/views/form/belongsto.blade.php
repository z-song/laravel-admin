<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

<label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <input type="hidden" name="{{$name}}"/>

        <select class="form-control {{$class}} hide" style="width: 100%;" name="{{$name}}" {!! $attributes !!} >
            <option value=""></option>
            @foreach($options as $select => $option)
                <option value="{{$select}}" {{ $select == old($column, $value) ?'selected':'' }}>{{$option}}</option>
            @endforeach
        </select>

        <div class="belongsto-{{ $class }}">
            {!! $grid->render() !!}
            <template class="empty">
                @include('admin::grid.empty-grid')
            </template>
        </div>

        @include('admin::form.help-block')

    </div>
</div>

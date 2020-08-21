<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        @foreach($options as $option => $label)

            {!! $inline ? '<span class="icheck-@theme">' : '<div class="radio icheck-@theme">'  !!}
                <input id="@id" type="radio" name="{{$name}}" value="{{$option}}" class="minimal {{$class}}" {{ ($option == old($column, $value)) || ($value === null && in_array($label, $checked)) ?'checked':'' }} {!! $attributes !!} />
                <label for="@id">&nbsp;{{$label}}&nbsp;&nbsp;
                </label>

            {!! $inline ? '</span>' :  '</div>' !!}

        @endforeach

        @include('admin::form.help-block')

    </div>
</div>

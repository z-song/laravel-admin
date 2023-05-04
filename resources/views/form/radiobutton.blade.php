<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div class="btn-group radio-group-toggle">
            @foreach($options as $option => $label)
                <label class="btn btn-default font-light {{ ($option == old($column, $value)) || ($value === null && in_array($label, $checked)) ?'active':'' }}">
                    @if(!empty($icons ?? null))
                    <i class="fa-regular {{ $icons[$option] }} mb-2.5 text-5xl"></i>
                    <br>
                    @endif
                    <input type="radio" name="{{$name}}" value="{{$option}}" class="hide minimal {{$class}}" {{ ($option == old($column, $value)) || ($value === null && in_array($label, $checked)) ?'checked':'' }} {!! $attributes !!} />&nbsp;{{$label}}&nbsp;&nbsp;
                </label>
            @endforeach
        </div>

        @include('admin::form.help-block')

    </div>
</div>

<div class="{{$viewClass['form-group']}} {!! !$errors->has($column) ?: 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}" id="{{$id}}">

        @include('admin::form.error')

        <div class="btn-group checkbox-group-toggle">
        @foreach($options as $option => $label)
            <label class="btn btn-default {{ false !== array_search($option, array_filter(old($column, $value ?? []))) || ($value === null && in_array($option, $checked)) ?'active':'' }}">
                <input type="checkbox" name="{{$name}}[]" value="{{$option}}" class="hide {{$class}}" {{ false !== array_search($option, array_filter(old($column, $value ?? []))) || ($value === null && in_array($option, $checked)) ?'checked':'' }} {!! $attributes !!} />&nbsp;{{$label}}&nbsp;&nbsp;
            </label>
        @endforeach
        </div>

        <input type="hidden" name="{{$name}}[]">

        @include('admin::form.help-block')

    </div>
</div>

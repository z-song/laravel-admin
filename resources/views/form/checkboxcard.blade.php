<div class="{{$viewClass['form-group']}} {!! !$errors->has($column) ?: 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}" id="{{$id}}">

        @include('admin::form.error')

        <div class="card-group checkbox-card-group">
        @foreach($options as $option => $label)
            <label class="card {{ false !== array_search($option, array_filter(old($column, $value ?? []))) || ($value === null && in_array($option, $checked)) ?admin_theme('bg-%s'):'' }}">
                <div class="card-body">
                <input type="checkbox" name="{{$name}}[]" value="{{$option}}" class="d-none {{$class}}" {{ false !== array_search($option, array_filter(old($column, $value ?? []))) || ($value === null && in_array($option, $checked)) ?'checked':'' }} {!! $attributes !!} />&nbsp;{{$label}}&nbsp;&nbsp;
                </div>
            </label>
        @endforeach
        </div>

        <input type="hidden" name="{{$name}}[]">

        @include('admin::form.help-block')

    </div>
</div>

<script>
    $('.checkbox-card-group label').on('click', function () {
        $(this).toggleClass('bg-@theme');
        return false;
    });
</script>

<style>
    .card-group label {
        cursor: pointer;
        margin-right: 8px;
        font-weight: 400;
    }

    .card-group .card {
        margin-bottom: 0px;
    }

    .card-group .card-body {
        padding: 10px 15px;
    }
</style>

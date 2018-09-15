<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')


        <div id="toolbar-container"></div>

        <div class="form-control {{$class}}" id="{{$id}}" name="{{$name}}">
            
          
            
        </div>

        @include('admin::form.help-block')

    </div>
</div>

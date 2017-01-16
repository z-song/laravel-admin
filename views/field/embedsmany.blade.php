<div class="form-group" style="margin:10px 0px 30px 0px">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6" id="{{$id}}">
        @foreach($form->fields() as $field)
            {!! $field->render() !!}
        @endforeach
    </div>
    <template id="embed">



    </template>
</div>
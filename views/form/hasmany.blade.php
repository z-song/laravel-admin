<hr>

<div class="form-has-many">

@foreach($forms as $form)

    @foreach($form->fields() as $field)
        {!! $field->render() !!}
    @endforeach

    <hr>

@endforeach

<div class="clone-wrapper">

    <div class="toclone">
        @foreach($template->fields() as $field)
            {!! $field->render() !!}
        @endforeach

            <div class="clone btn btn-success">+</div>
            <div class="delete btn btn-danger">-</div>
            <hr>
    </div>


</div>

</div>
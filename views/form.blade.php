{!! $form->open(['class' => "form-horizontal"]) !!}

<style>
    div.form-tools-box > .box.fixed{
        position:absolute;
        z-index:9999;
    }
</style>
<div class="form-tools-box" style="position: relative;">
    <div class="box">
        <div class="box-header">
            <div class="col-md-6">
                <a class="btn  btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;{{ trans('admin::lang.back') }}</a>
                <a href="{{ $resource }}" class="btn  btn-default"><i class="fa fa-list"></i>&nbsp;{{ trans('admin::lang.list') }}</a>
                <button type="reset" class="btn  btn-warning" >{{ trans('admin::lang.reset') }}</button>
            </div>
            <div class="col-md-6">
                {!! $form->submit() !!}

            </div>
        </div>
    </div>
</div>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $form->title() }}</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        @foreach($form->fields() as $field)
            @if($field instanceof \Encore\Admin\Field\DataField)
            {!! $field->render() !!}
            @endif
        @endforeach

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

    </div>
</div>
@foreach($form->fields() as $field)
    @if($field instanceof \Encore\Admin\Field\RelationField)
        {!! $field->render() !!}
    @endif
@endforeach
{!! $form->close() !!}

<script>
    var formToolsBox = $('div.form-tools-box > .box');
    var formToolsBoxTop = formToolsBox.offset().top;
    var formToolsBoxHeight = formToolsBox.outerHeight();
    $('div.form-tools-box').css('min-height', formToolsBoxHeight);
    $(document).on('scroll', function(){
        var scrollTop = $(document).scrollTop();
        if(scrollTop >= formToolsBoxTop){
            formToolsBox.addClass('fixed').css('top', scrollTop - formToolsBoxTop);
            return false;
        }
        formToolsBox.removeClass('fixed').css('top','');
    });
</script>
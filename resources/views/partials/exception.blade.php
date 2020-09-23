@if($errors->hasBag('exception') && config('app.debug') == true)
    <?php $error = $errors->getBag('exception');?>
    <div class="callout callout-danger">
        <h5 class="text-danger">
            <i class="icon fas fa-exclamation-triangle"></i>
            <i style="border-bottom: 1px dotted #fff;cursor: pointer;" title="{{ $error->first('type') }}" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{{ class_basename($error->first('type')) }}</i>
            In <i title="{{ $error->first('file') }} line {{ $error->first('line') }}" style="border-bottom: 1px dotted #fff;cursor: pointer;" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{{ basename($error->first('file')) }} line {{ $error->first('line') }}</i> :
        </h5>

        <p><a style="cursor: pointer;" onclick="$('#laravel-admin-exception-trace').toggleClass('d-none');$('i', this).toggleClass('fa-angle-double-down fa-angle-double-up');"><i class="fa fa-angle-double-down"></i>&nbsp;&nbsp;{!! $error->first('message') !!}</a></p>

        <p class="d-none" id="laravel-admin-exception-trace"><br>{!! nl2br($error->first('trace')) !!}</p>
    </div>
@endif

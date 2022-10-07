@if ($errors->hasBag('exception'))
    <?php $error = $errors->getBag('exception'); ?>
    @if ($error->first('type') === 'Encore\Admin\Exception\ProcessException')
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4>
                <i class="icon fa-solid fa-warning"></i>
                خطا!
            </h4>
            <p>{{ $error->first('message') }}</p>
        </div>
    @else
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4>
                <i class="icon fa-solid fa-warning"></i>
                خطا!
            </h4>
            <p>خطایی رخ داده است!</p>
        </div>
    @endif

    @if (config('app.debug') == true)
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>
                <i class="icon fa-solid fa-warning"></i>
                <i style="border-bottom: 1px dotted #fff;cursor: pointer;" title="{{ $error->first('type') }}"
                    ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{{ class_basename($error->first('type')) }}</i>
                In <i title="{{ $error->first('file') }} line {{ $error->first('line') }}"
                    style="border-bottom: 1px dotted #fff;cursor: pointer;"
                    ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{{ basename($error->first('file')) }}
                    line {{ $error->first('line') }}</i> :
            </h4>
            <p><a style="cursor: pointer;"
                    onclick="$('#laravel-admin-exception-trace').toggleClass('hidden');$('i', this).toggleClass('fa-angle-double-down fa-angle-double-up');"><i
                        class="fa-solid fa-angle-double-down"></i>&nbsp;&nbsp;{!! $error->first('message') !!}</a></p>

            <p class="hidden" id="laravel-admin-exception-trace"><br>{!! nl2br($error->first('trace')) !!}</p>
        </div>
    @endif
@endif

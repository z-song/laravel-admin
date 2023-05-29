@if($help)
<span class="help-block">
    <i class="fa-regular {{ \Illuminate\Support\Arr::get($help, 'icon') }}"></i>&nbsp;{!! \Illuminate\Support\Arr::get($help, 'text') !!}
</span>
@endif
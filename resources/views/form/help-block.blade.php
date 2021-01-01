@if($help)
<div class="mt-2 text-secondary">
    <i class="fa {{ \Illuminate\Support\Arr::get($help, 'icon') }}"></i>&nbsp;{!! \Illuminate\Support\Arr::get($help, 'text') !!}
</div>
@endif

@if($help)
<span class="help-block">
    <i class="fa {{ array_get($help, 'icon') }}"></i>&nbsp;{!! array_get($help, 'text') !!}
</span>
@endif
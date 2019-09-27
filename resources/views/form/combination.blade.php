<div class="form-group {{ isset($dataValue) ? '' : 'hide' }}">
    <label class="{{$viewClass['label']}} control-label"></label>
    <div class="{{$viewClass['field']}}">
        <div id="bootstrap-combination" data-attribute="{{$attribute_class}}"
             data-grid="{{$grid}}"
             data-value="{{ isset($dataValue) ? true : false }}">
            <input type="hidden" name="labels[]" class="labels" data-value="{{ isset($dataValue) ? $dataValue[0] : '' }}">
            <input type="hidden" name="combinations[]" class="combinations" data-value="{{ isset($dataValue) ? $dataValue[1] : '' }}">
        </div>
    </div>
</div>
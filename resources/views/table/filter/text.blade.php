<div class="input-group">
    @if($group)
    <div class="input-group-prepend show">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="min-width: 32px;">
            <span class="{{ $group_name }}-label">{{ $default['label'] }}</span>
        </button>
        <input type="hidden" name="{{ $id }}_group" class="{{ $group_name }}-operation" value="0"/>
        <div class="dropdown-menu {{ $group_name }}">
            @foreach($group as $index => $item)
                <a class="dropdown-item" href="#" data-index="{{ $index }}"> {{ $item['label'] }} </a>
            @endforeach
        </div>
    </div>
    @else
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="fa fa-{{ $icon }}"></i></span>
    </div>
    @endif

    <input type="{{ $type }}" class="form-control {{ $id }}" placeholder="{{$placeholder}}" name="{{$name}}" value="{{ request($name, $value) }}">
</div>

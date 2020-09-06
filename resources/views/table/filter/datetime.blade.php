<div class="input-group">
    @if($group)
    <div class="input-group-prepend">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="min-width: 32px;">
            <i class="fa fa-calendar"></i>
            <span class="{{ $group_name }}-label">
                &nbsp;{{ $default['label'] }}
            </span>
        </button>
        <input type="hidden" name="{{ $id }}_group" class="{{ $group_name }}-operation" value="0"/>
        <div class="dropdown-menu {{ $group_name }}">
            @foreach($group as $index => $item)
                <a href="#" data-index="{{ $index }}" class="dropdown-item"> {{ $item['label'] }} </a>
            @endforeach
        </div>
    </div>
    @else
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
    </div>
    @endif
    <input class="form-control" id="{{$id}}" placeholder="{{$label}}" name="{{$name}}" value="{{ request($name, $value) }}" autocomplete="off">
</div>

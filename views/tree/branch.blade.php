@if(!isset($branch['children']))
    <li class="dd-item" data-id="{{ $branch['id'] }}">
        <div class="dd-handle">
            {{ $branch['title'] }}
            <span class="pull-right action dd-nodrag" data-field-name="_edit">
                <a href="/{{ $path }}/{{ $branch['id'] }}/edit"><i class="fa fa-edit"></i></a>
                <a href="javascript:void(0);" data-id="{{ $branch['id'] }}" class="_delete"><i class="fa fa-trash"></i></a>
            </span>
        </div>
    </li>
@else
    <li class="dd-item" data-id="{{ $branch['id'] }}">
        <div class="dd-handle">
            {{ $branch['title'] }}
            <span class="pull-right action dd-nodrag" data-field-name="_edit">
                <a href="/{{ $path }}/{{ $branch['id'] }}/edit"><i class="fa fa-edit"></i></a>
                <a href="javascript:void(0);" data-id="{{ $branch['id'] }}" class="_delete"><i class="fa fa-trash"></i></a>
            </span>
        </div>
        <ol class="dd-list">
            @foreach($branch['children'] as $branch)
                @include('admin::tree.branch', $branch)
            @endforeach
        </ol>
    </li>
@endif
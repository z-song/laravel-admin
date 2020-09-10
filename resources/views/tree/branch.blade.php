
<li class="dd-item" data-id="{{ $branch[$keyName] }}">
    <div class="dd-handle">
        {!! $branchCallback($branch) !!}
        <span class="float-right dd-nodrag">
            <a href="{{ url("$path/$branch[$keyName]/edit") }}"><i class="fa fa-edit"></i></a>
            {!! (new \Encore\Admin\Tree\Delete($path, $branch[$keyName]))->setModel($model)->render()  !!}
        </span>
    </div>
    @if(isset($branch['children']))
    <ol class="dd-list">
        @foreach($branch['children'] as $branch)
            @include($branchView, $branch)
        @endforeach
    </ol>
    @endif
</li>

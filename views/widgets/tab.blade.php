<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">

        @foreach($tabs as $key => $tab)
        <li {{ $key == 0 ? 'class=active' : '' }}><a href="#tab_{{ $tab['id'] }}" data-toggle="tab">{{ $tab['title'] }}</a></li>
        @endforeach

        @if (!empty($dropDown))
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                Dropdown <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                @foreach($dropDown as $link)
                <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ $link['href'] }}">{{ $link['name'] }}</a></li>
                @endforeach
            </ul>
        </li>
        @endif
        <li class="pull-right header">{{ $title }}</li>
    </ul>
    <div class="tab-content"  id="tab_content_{{ $id }}">
        @foreach($tabs as $key => $tab)
        <div class="tab-pane {{ $key == 0 ? 'active' : '' }}" id="tab_{{ $tab['id'] }}">
            {!! $tab['content'] !!}
        </div>
        @endforeach

    </div>
</div>
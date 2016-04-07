<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">

        @foreach($tabs as $id => $tab)
        <li {{ $id == 0 ? 'class=active' : '' }}><a href="#tab_{{ $id }}" data-toggle="tab">{{ $tab['title'] }}</a></li>
        @endforeach
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
        <li class="pull-right header">{{ $title }}</li>
    </ul>
    <div class="tab-content">
        @foreach($tabs as $id => $tab)
        <div class="tab-pane {{ $id == 0 ? 'active' : '' }}" id="tab_{{ $id }}">
            {!! $tab['content'] !!}
        </div>
        @endforeach

    </div>
</div>
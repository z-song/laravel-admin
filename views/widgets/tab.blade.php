<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">

        @foreach($tabs as $id => $tab)
        <li {{ $tab['active'] ? 'class=active' : '' }}>
            @if(!empty($tab['externalLink']))
                <a href="{{ $tab['externalLink'] }}">
            @else
                <a href="#tab_{{ $id }}" data-toggle="tab">
            @endif
            {{ $tab['title'] }}</a>
        </li>
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
    <div class="tab-content">
        @foreach($tabs as $id => $tab)
        <div class="tab-pane {{ $tab['active'] ? 'active' : '' }}" id="tab_{{ $id }}">
            {!! $tab['content'] !!}
        </div>
        @endforeach

    </div>
</div>

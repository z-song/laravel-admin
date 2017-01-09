<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">

        @foreach($tabs as $id => $tab)
            <li {{ $id == 0 ? 'class=active' : '' }}><a href="#tab_{{ $tab['id'] }}" data-toggle="tab">{{ $tab['title'] }}</a></li>
        @endforeach

    </ul>
    <div class="tab-content">
        @foreach($tabs as $id => $tab)
            <div class="tab-pane {{ $id == 0 ? 'active' : '' }}" id="tab_{{ $tab['id'] }}">
                {!! $tab['form']->render() !!}
            </div>
        @endforeach

    </div>
</div>
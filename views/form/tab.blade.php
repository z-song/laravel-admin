{!! $form->open() !!}
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">

        @foreach($tabs as $tab)
            <li {{ $tab['active'] ? 'class=active' : '' }}><a href="#tab-{{ $tab['id'] }}" data-toggle="tab">{{ $tab['title'] }}</a></li>
        @endforeach

    </ul>
    <div class="tab-content">

        @foreach($tabs as $tab)
            <div class="tab-pane {{ $tab['active'] ? 'active' : '' }}" id="tab-{{ $tab['id'] }}">
                {!! $tab['form']->builder()->renderWithoutForm() !!}
            </div>
        @endforeach

    </div>
</div>
{!! $form->close() !!}
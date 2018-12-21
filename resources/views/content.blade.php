@extends('admin::index', ['header' => $header])

@section('content')
    <section class="content-header">
        <h1>
            {{ $header ?: trans('admin.title') }}
            <small>{{ $description ?: trans('admin.description') }}</small>
        </h1>

        <!-- breadcrumb start -->
        <ol class="breadcrumb" style="margin-right: 30px;">
            <li><a href="{{ admin_url('/') }}"><i class="fa fa-dashboard"></i>
                    {{ Lang::has($titleTranslation = 'admin.breadcrumb_titles.home') ? __($titleTranslation) : 'Home' }}
                </a></li>
            @if ($breadcrumb)
                @foreach($breadcrumb as $item)
                    @if($loop->last)
                        <li class="active">
                            @if (array_has($item, 'icon'))
                                <i class="fa fa-{{ $item['icon'] }}"></i>
                            @endif
                            {{ $item['text'] }}
                        </li>
                    @else
                    <li>
                        <a href="{{ admin_url(array_get($item, 'url')) }}">
                            @if (array_has($item, 'icon'))
                                <i class="fa fa-{{ $item['icon'] }}"></i>
                            @endif
                            {{ $item['text'] }}
                        </a>
                    </li>
                    @endif
                @endforeach
            @elseif(config('admin.enable_default_breadcrumb'))
                @for($i = 2; $i <= count(Request::segments()); $i++)
                    <li>
                        @if (Lang::has($titleTranslation = 'admin.breadcrumb_titles.' . trim(str_replace(' ', '_', strtolower(Request::segment($i))))))
                            <span>{{ __($titleTranslation) }}</span>
                        @else
                            <span>{{ Request::segment($i) }}</span>
                        @endif
                    </li>
                @endfor
             @endif
        </ol>
        <!-- breadcrumb end -->

    </section>

    <section class="content">

        @include('admin::partials.alerts')
        @include('admin::partials.exception')
        @include('admin::partials.toastr')

        {!! $content !!}

    </section>
@endsection
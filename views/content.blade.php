@extends('admin::index')

@section('content')
    <section class="content-header">
        <h1>
            {{ $header or trans('admin::lang.title') }}
            <small>{{ $description or trans('admin::lang.description') }}</small>
        </h1>

    </section>

    <section class="content">

        @include('admin::partials.error')

        {!! $content !!}

    </section>
@endsection
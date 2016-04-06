@extends('admin::index')

@section('content')
    <section class="content-header">
        <h1>
            {{ $header or Lang::get('admin::lang.title') }}
            <small>{{ $description or Lang::get('admin::lang.description') }}</small>
        </h1>

    </section>

    <section class="content">

        {!! $content !!}

    </section>
@endsection


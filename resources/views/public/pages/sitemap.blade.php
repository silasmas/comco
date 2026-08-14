@extends('layouts.public')

@php
  use App\Support\NavigationUrl;
@endphp

@section('page-header')
  <x-elixir.page-title title="Plan du site" />
@endsection

@section('content')
  <section class="bg-100 py-6">
    <div class="container">
      <p class="text-700 mb-5 col-lg-8 px-0">
        Retrouvez ici l'ensemble des rubriques du site institutionnel de la COMCO.
      </p>

      <div class="row g-4">
        @foreach ($mainMenu as $item)
          <div class="col-md-6 col-lg-4">
            <div class="h-100">
              <h5 class="mb-3">
                @if (isset($item['children']))
                  {{ $item['label'] }}
                @else
                  <a class="text-decoration-none" href="{{ NavigationUrl::resolve($item) }}">{{ $item['label'] }}</a>
                @endif
              </h5>

              @if (isset($item['children']))
                <ul class="list-unstyled mb-0">
                  @foreach ($item['children'] as $child)
                    <li class="mb-2">
                      <a class="text-decoration-none" href="{{ NavigationUrl::resolveChild($item, $child) }}">
                        {{ $child['label'] }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endsection

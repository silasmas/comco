@php
  use App\Support\NavigationUrl;
  $mainMenu = config('navigation.main');
@endphp

<header class="border-b border-slate-200 bg-white shadow-sm" x-data="{ mobileOpen: false, openMenu: null }">
  <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 lg:px-6">
    <a href="{{ route('home') }}" class="flex items-center gap-3">
      <span class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-700 text-sm font-bold text-white">C</span>
      <span class="leading-tight">
        <span class="block text-lg font-bold text-blue-800">{{ config('institution.shortName') }}</span>
        <span class="hidden text-xs text-slate-500 sm:block">{{ config('institution.fullName') }}</span>
      </span>
    </a>

    <nav class="hidden items-center gap-1 xl:flex" aria-label="Navigation principale">
      @foreach ($mainMenu as $index => $item)
        @if (isset($item['children']))
          <div
            class="relative"
            @mouseenter="openMenu = {{ $index }}"
            @mouseleave="openMenu = null"
          >
            <button
              type="button"
              class="flex items-center gap-1 px-3 py-2 text-sm font-semibold text-slate-800 hover:text-blue-700"
              :aria-expanded="openMenu === {{ $index }}"
            >
              {{ $item['label'] }}
              <span aria-hidden="true">▼</span>
            </button>
            <div
              x-show="openMenu === {{ $index }}"
              x-cloak
              class="absolute left-0 top-full z-50 min-w-[240px] border-t-2 border-blue-600 bg-white py-2 shadow-lg"
            >
              @foreach ($item['children'] as $child)
                <a
                  href="{{ NavigationUrl::resolveChild($item, $child) }}"
                  class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-700"
                >
                  {{ $child['label'] }}
                </a>
              @endforeach
            </div>
          </div>
        @else
          <a
            href="{{ NavigationUrl::resolve($item) }}"
            class="px-3 py-2 text-sm font-semibold text-slate-800 hover:text-blue-700 {{ request()->routeIs($item['route'] ?? '') ? 'text-blue-700' : '' }}"
          >
            {{ $item['label'] }}
          </a>
        @endif
      @endforeach
    </nav>

    <button
      type="button"
      class="rounded border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 lg:hidden"
      @click="mobileOpen = !mobileOpen"
      :aria-expanded="mobileOpen.toString()"
    >
      Menu
    </button>
  </div>

  <nav x-show="mobileOpen" x-cloak class="border-t border-slate-200 px-4 py-4 lg:hidden" aria-label="Navigation mobile">
    <ul class="space-y-2">
      @foreach ($mainMenu as $index => $item)
        <li>
          @if (isset($item['children']))
            <button
              type="button"
              class="flex w-full items-center justify-between py-2 text-sm font-semibold"
              @click="openMenu = openMenu === {{ $index }} ? null : {{ $index }}"
            >
              {{ $item['label'] }}
              <span aria-hidden="true">▼</span>
            </button>
            <ul x-show="openMenu === {{ $index }}" x-cloak class="ml-4 space-y-1 border-l border-slate-200 pl-3">
              @foreach ($item['children'] as $child)
                <li>
                  <a href="{{ NavigationUrl::resolveChild($item, $child) }}" class="block py-1.5 text-sm text-slate-600">
                    {{ $child['label'] }}
                  </a>
                </li>
              @endforeach
            </ul>
          @else
            <a href="{{ NavigationUrl::resolve($item) }}" class="block py-2 text-sm font-semibold">
              {{ $item['label'] }}
            </a>
          @endif
        </li>
      @endforeach
    </ul>
  </nav>
</header>

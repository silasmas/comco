@php
  use App\Support\NavigationUrl;
  $footerNav = config('navigation.footer.navigation');
  $footerEServices = config('navigation.footer.eServices');
@endphp

<footer class="bg-slate-900 text-white">
  <div class="mx-auto max-w-7xl px-4 py-12 lg:px-6">
    <div class="mb-10 flex flex-col gap-6 border-b border-white/10 pb-10 lg:flex-row lg:items-center lg:justify-between">
      <div class="flex items-center gap-4">
        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-700 text-sm font-bold">C</span>
        <p class="max-w-xl text-sm leading-relaxed text-slate-300">
          {{ config('institution.tagline') }}
        </p>
      </div>
    </div>

    <div class="grid gap-8 md:grid-cols-3">
      <div>
        <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-amber-400">Navigation</h3>
        <ul class="space-y-2 text-sm text-slate-300">
          @foreach ($footerNav as $item)
            <li>
              <a href="{{ NavigationUrl::resolve($item) }}" class="hover:text-white" @if(isset($item['url'])) target="_blank" rel="noopener noreferrer" @endif>
                {{ $item['label'] }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      <div>
        <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-amber-400">E-Services</h3>
        <ul class="space-y-2 text-sm text-slate-300">
          @foreach ($footerEServices as $item)
            <li>
              <a href="{{ NavigationUrl::resolve($item) }}" class="hover:text-white">{{ $item['label'] }}</a>
            </li>
          @endforeach
        </ul>
      </div>

      <div>
        <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-amber-400">Restez informé</h3>
        <p class="mb-4 text-sm text-slate-300">
          Recevez nos actualités, conseils et nouveautés directement par email.
        </p>
        <form action="#" method="post" class="flex gap-2">
          @csrf
          <input type="email" name="email" placeholder="Votre email" class="w-full rounded border-0 px-3 py-2 text-sm text-slate-900">
          <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-sm font-semibold whitespace-nowrap hover:bg-blue-700">
            S'abonner
          </button>
        </form>
      </div>
    </div>

    <div class="mt-10 border-t border-white/10 pt-6 text-sm text-slate-400">
      <ul class="mb-4 space-y-1">
        <li><a href="mailto:{{ config('institution.contact.email') }}" class="hover:text-white">{{ config('institution.contact.email') }}</a></li>
        <li>{{ config('institution.contact.phone') }}</li>
        <li>{{ config('institution.contact.address') }}</li>
      </ul>
      <p>© {{ date('Y') }} {{ config('institution.shortName') }} / RDC. Tous droits réservés.</p>
    </div>
  </div>
</footer>

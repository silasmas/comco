{{-- Panneau lien + QR code dans l'admin Filament. --}}
@php
  /** @var \App\Models\MobileApp|null $record */
  $record = $getRecord instanceof \Closure ? $getRecord() : ($record ?? null);
@endphp

@if ($record)
  <div class="space-y-4 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
    <div>
      <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Lien de partage</p>
      <div class="mt-2 flex flex-wrap items-center gap-2">
        <code class="rounded bg-gray-100 px-3 py-2 text-sm break-all dark:bg-gray-800">{{ $record->shareUrl() }}</code>
        <a
          href="{{ $record->shareUrl() }}"
          target="_blank"
          rel="noopener"
          class="text-sm font-semibold text-primary-600 hover:underline"
        >
          Ouvrir
        </a>
      </div>
      <p class="mt-1 text-xs text-gray-500">Ce lien ouvre la page de téléchargement (idéal pour WhatsApp, e-mail, etc.).</p>
    </div>

    <div>
      <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Téléchargement direct</p>
      <code class="mt-2 block rounded bg-gray-100 px-3 py-2 text-sm break-all dark:bg-gray-800">{{ $record->downloadUrl() }}</code>
    </div>

    <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center">
      <img
        src="{{ $record->qrCodeImageUrl(280) }}"
        alt="QR code de téléchargement"
        width="280"
        height="280"
        class="rounded-lg border border-gray-200 bg-white p-2 dark:border-gray-700"
      />
      <div class="text-sm text-gray-600 dark:text-gray-300">
        <p class="font-medium text-gray-800 dark:text-gray-100">QR code</p>
        <p class="mt-1">Scannez avec un téléphone : la page de téléchargement de l’APK s’ouvre.</p>
        <p class="mt-2">Téléchargements enregistrés : <strong>{{ number_format($record->download_count, 0, ',', ' ') }}</strong></p>
        <a
          href="{{ $record->qrCodeImageUrl(600) }}"
          target="_blank"
          rel="noopener"
          class="mt-3 inline-block font-semibold text-primary-600 hover:underline"
        >
          Télécharger le QR (PNG)
        </a>
      </div>
    </div>
  </div>
@else
  <p class="text-sm text-gray-500">Enregistrez l’application pour générer le lien et le QR code.</p>
@endif

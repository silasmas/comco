<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — {{ config('institution.shortName') }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/png" href="{{ asset('assets/ico.png') }}">
    <style>
      * { box-sizing: border-box; }
      body {
        margin: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(180deg, #eef2f7 0%, #ffffff 100%);
        color: #2a3855;
        padding: 24px;
      }
      .preview-banner {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 10;
        background: #8a6d00;
        color: #fff8dc;
        text-align: center;
        padding: 10px 16px;
        font-size: 0.9rem;
        font-weight: 600;
      }
      .card {
        width: 100%;
        max-width: 560px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 16px 48px rgba(0, 61, 165, 0.12);
        overflow: hidden;
        text-align: center;
        margin-top: {{ !empty($isPreview) ? '40px' : '0' }};
      }
      .header {
        background: #003DA5;
        padding: 28px 24px 20px;
      }
      .accent {
        height: 5px;
        background: #fdd428;
      }
      .header img {
        height: 56px;
        width: auto;
      }
      .body {
        padding: 32px 28px 36px;
      }
      h1 {
        margin: 0 0 12px;
        font-size: 1.5rem;
        color: #003DA5;
      }
      p {
        margin: 0;
        line-height: 1.7;
        color: #445066;
        white-space: pre-line;
      }
      .badge {
        display: inline-block;
        margin-top: 24px;
        padding: 10px 18px;
        border-radius: 999px;
        background: #fff8dc;
        color: #8a6d00;
        font-weight: 600;
        font-size: 0.95rem;
      }
    </style>
  </head>
  <body>
    @if (!empty($isPreview))
      <div class="preview-banner">Aperçu de la page de maintenance — non visible par les visiteurs</div>
    @endif
    <div class="card">
      <div class="header">
        <img src="{{ asset('assets/logo01.png') }}" alt="{{ config('institution.shortName') }}">
      </div>
      <div class="accent"></div>
      <div class="body">
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
        <span class="badge">Site en maintenance</span>
      </div>
    </div>
  </body>
</html>

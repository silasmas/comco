<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Installation production — {{ config('institution.shortName') }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/png" href="{{ asset('assets/ico.png') }}">
    <style>
      body {
        margin: 0;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: #eef2f7;
        color: #2a3855;
      }
      .topbar {
        background: #fff;
        border-bottom: 4px solid #fdd428;
        padding: 16px 24px;
        box-shadow: 0 2px 12px rgba(0, 61, 165, 0.08);
      }
      .topbar img { height: 48px; }
      .wrap {
        max-width: 920px;
        margin: 0 auto;
        padding: 32px 20px 48px;
      }
      h1 {
        margin: 0 0 8px;
        color: #003DA5;
        font-size: 1.75rem;
      }
      .lead {
        margin: 0 0 24px;
        line-height: 1.6;
        color: #445066;
      }
    </style>
  </head>
  <body>
    <div class="topbar">
      <img src="{{ asset('assets/logo01.png') }}" alt="{{ config('institution.shortName') }}">
    </div>

    <div class="wrap">
      <h1>Installation &amp; production</h1>
      <p class="lead">
        Première mise en production : exécutez les migrations, importez les articles, configurez l'environnement, créez le super administrateur, puis mettez le site en ligne.
      </p>

      @include('admin.partials.site-installation-body')
    </div>
  </body>
</html>

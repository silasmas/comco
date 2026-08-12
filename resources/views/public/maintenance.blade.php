<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $title }} — {{ config('institution.shortName') }}</title>
<meta name="robots" content="noindex,nofollow">
<link rel="icon" type="image/png" href="{{ asset('assets/ico.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
<style>
:root{--b:#003DA5;--d:#002b75;--g:#fdd428;--i:#2a3855;--m:#5b6b82}
*{box-sizing:border-box}
body{margin:0;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px;font-family:"Open Sans",sans-serif;color:var(--i);background:
radial-gradient(circle at 100% 0,rgba(253,212,40,.22),transparent 30%),
radial-gradient(circle at 0 100%,rgba(0,61,165,.18),transparent 34%),
linear-gradient(165deg,#f4f7fb,#e7eef8 42%,#fff)}
.preview-banner{position:fixed;top:0;left:0;right:0;background:#8a6d00;color:#fff8dc;text-align:center;padding:10px;font-weight:600;font-size:.9rem;z-index:10}
.shell{width:100%;max-width:640px;margin-top:{{ !empty($isPreview)?'40px':'0' }}}
.card{background:#fff;border-radius:22px;overflow:hidden;box-shadow:0 28px 64px rgba(0,61,165,.16);border:1px solid rgba(0,61,165,.08)}
.brand{padding:28px 28px 18px;text-align:center;background:#fff}
.brand img{height:72px;width:auto}
.accent{height:6px;background:linear-gradient(90deg,var(--b),var(--g),var(--b))}
.body{padding:34px 32px 36px;text-align:center}
.eyebrow{margin:0 0 10px;font:700 .75rem/1 Montserrat,sans-serif;letter-spacing:.14em;text-transform:uppercase;color:var(--b)}
h1{margin:0 0 14px;font:700 clamp(1.45rem,2.2vw,1.9rem)/1.25 Montserrat,sans-serif;color:var(--b)}
.lead{margin:0 auto;max-width:44ch;line-height:1.75;color:var(--m);white-space:pre-line}
.badge{display:inline-flex;align-items:center;gap:8px;margin-top:26px;padding:11px 16px;border-radius:999px;background:#fff8dc;color:#8a6d00;font:700 .9rem Montserrat,sans-serif}
.dot{width:8px;height:8px;border-radius:50%;background:var(--g);box-shadow:0 0 0 4px rgba(253,212,40,.28)}
.meta{margin-top:26px;padding-top:20px;border-top:1px solid #e6ebf3;color:var(--m);font-size:.95rem;line-height:1.6}
.meta a{color:var(--b);font-weight:600;text-decoration:none}
.note{margin-top:18px;text-align:center;color:#7a879c;font-size:.85rem}
</style>
</head>
<body>
@if(!empty($isPreview))
<div class="preview-banner">Aperçu de la page de maintenance — non visible par les visiteurs</div>
@endif
<div class="shell">
<div class="card">
<div class="brand">
<img src="{{ asset('assets/logo01.png') }}" alt="{{ config('institution.shortName') }}">
</div>
<div class="accent"></div>
<div class="body">
<p class="eyebrow">{{ config('institution.shortName') }}</p>
<h1>{{ $title }}</h1>
<p class="lead">{{ $message }}</p>
<span class="badge"><span class="dot" aria-hidden="true"></span>Site temporairement indisponible</span>
@php
  $email = $contactEmail ?? config('institution.contact.email');
@endphp
@if(filled($email))
<div class="meta">Pour toute urgence, contactez-nous à <a href="mailto:{{ $email }}">{{ $email }}</a></div>
@endif
</div>
</div>
<p class="note">Merci de votre compréhension. Le service sera rétabli très prochainement.</p>
</div>
</body>
</html>

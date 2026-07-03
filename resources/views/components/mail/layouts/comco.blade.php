@props([
  'heading' => null,
  'subjectLine' => null,
])

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subjectLine ?? $heading ?? config('institution.shortName') }}</title>
  </head>
  <body style="margin:0;padding:0;background-color:#eef2f7;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#2a3855;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#eef2f7;padding:24px 12px;">
      <tr>
        <td align="center">
          <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 8px 24px rgba(0,61,165,0.12);">
            <tr>
              <td align="center" style="background-color:#003DA5;padding:28px 24px;">
                <img src="{{ comcoMailLogoUrl() }}" alt="{{ config('institution.shortName') }}" height="56" style="display:block;height:56px;width:auto;">
              </td>
            </tr>
            <tr>
              <td style="height:5px;background-color:#fdd428;line-height:5px;font-size:0;">&nbsp;</td>
            </tr>
            <tr>
              <td style="padding:32px 36px 24px;">
                @if ($heading)
                  <h1 style="margin:0 0 16px;font-size:22px;line-height:1.3;color:#003DA5;">{{ $heading }}</h1>
                @endif
                {{ $slot }}
              </td>
            </tr>
            <tr>
              <td style="background-color:#2a3855;padding:24px 36px;text-align:center;">
                <p style="margin:0 0 8px;font-size:14px;font-weight:600;color:#ffffff;">
                  {{ config('institution.fullName') }} ({{ config('institution.shortName') }})
                </p>
                <p style="margin:0 0 4px;font-size:13px;color:#cdd8ea;">
                  {{ config('institution.contact.email') }} | {{ config('institution.contact.phone') }}
                </p>
                <p style="margin:0;font-size:13px;color:#cdd8ea;">
                  {{ config('institution.contact.address') }}
                </p>
              </td>
            </tr>
          </table>
          <p style="margin:16px 0 0;font-size:12px;color:#7f7f7f;text-align:center;">
            Message automatique — merci de ne pas répondre directement à cet email.
          </p>
        </td>
      </tr>
    </table>
  </body>
</html>

<x-mail.layouts.comco heading="Nouvelle soumission reçue">
  <p style="margin:0 0 24px;font-size:15px;line-height:1.7;color:#445066;">
    Une nouvelle soumission a été enregistrée sur le site public de la COMCO.
  </p>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e3e8f0;border-radius:8px;overflow:hidden;">
    @foreach ($lines as $label => $value)
      <tr>
        <td style="padding:12px 16px;width:38%;font-size:14px;font-weight:600;color:#2a3855;border-top:1px solid #e3e8f0;background-color:#fbfcfe;">
          {{ $label }}
        </td>
        <td style="padding:12px 16px;font-size:14px;line-height:1.5;color:#445066;border-top:1px solid #e3e8f0;">
          {{ is_bool($value) ? ($value ? 'Oui' : 'Non') : $value }}
        </td>
      </tr>
    @endforeach
  </table>
</x-mail.layouts.comco>

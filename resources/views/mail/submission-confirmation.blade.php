<x-mail.layouts.comco :heading="$subjectLine">
  <p style="margin:0 0 16px;font-size:16px;line-height:1.6;">
    Bonjour <strong>{{ $recipientName }}</strong>,
  </p>

  <p style="margin:0 0 24px;font-size:15px;line-height:1.7;color:#445066;">
    {{ $intro }}
  </p>

  @if (! empty($details))
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e3e8f0;border-radius:8px;overflow:hidden;">
      <tr>
        <td colspan="2" style="background-color:#f7f9fc;padding:12px 16px;font-size:13px;font-weight:700;color:#003DA5;text-transform:uppercase;letter-spacing:0.4px;">
          Récapitulatif
        </td>
      </tr>
      @foreach ($details as $label => $value)
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
  @endif
</x-mail.layouts.comco>

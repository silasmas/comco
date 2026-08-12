@props([
  'message' => null,
  'type' => 'success',
])

@if ($message)
  @php
    $icons = [
      'success' => 'check-circle',
      'danger' => 'exclamation-circle',
      'warning' => 'exclamation-triangle',
      'info' => 'info-circle',
    ];
    $icon = $icons[$type] ?? 'info-circle';
  @endphp
  <div class="alert alert-{{ $type }} comco-form-feedback d-flex align-items-start mb-3" role="alert">
    <span class="fas fa-{{ $icon }} me-2 mt-1"></span>
    <div>{{ $message }}</div>
  </div>
@endif

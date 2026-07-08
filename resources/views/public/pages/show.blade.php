@extends('layouts.public')

@php
  use App\Support\EServiceRegistry;

  $breadcrumb = [];
  if ($sectionLabel ?? false) {
    $breadcrumb[] = ['label' => $sectionLabel];
  }
  $breadcrumb[] = ['label' => $page->title];
  $isEService = ($page->section ?? null) === 'e-services';
  $hasEServiceForm = $isEService && EServiceRegistry::has($page->slug);
  $serviceConfig = $hasEServiceForm ? EServiceRegistry::get($page->slug) : [];
  $template = pageTemplate($page->section ?? '', $page->slug, $page->template);

  if ($isEService && $hasEServiceForm) {
    $template = 'e-service';
  } elseif ($isEService && $template === 'blank') {
    $template = 'service';
  }
@endphp

@section('page-header')
  <x-elixir.page-header :title="$page->title" :breadcrumb="$breadcrumb" />
@endsection

@section('content')
  @include('public.pages.templates.' . $template, [
    'page' => $page,
    'sectionLabel' => $sectionLabel ?? null,
    'isEService' => $isEService,
    'hasEServiceForm' => $hasEServiceForm,
    'serviceConfig' => $serviceConfig,
  ])
@endsection

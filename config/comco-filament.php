<?php

/**
 * Personnalisation Filament / visite guidée COMCO.
 */
return [
  'tour' => [
    'tooltip' => env('COMCO_TOUR_TOOLTIP', 'Visite guidée du panneau d\'administration'),
    'header_color' => env('COMCO_TOUR_HEADER_COLOR', '#003DA5'),
    'text_color' => env('COMCO_TOUR_TEXT_COLOR', '#2a3855'),
    'background_color' => env('COMCO_TOUR_BACKGROUND_COLOR', '#ffffff'),
    'content_background_color' => env('COMCO_TOUR_CONTENT_BACKGROUND_COLOR', '#ffffff'),
    'footer_background_color' => env('COMCO_TOUR_FOOTER_BACKGROUND_COLOR', '#f7f9fc'),
    'footer_border_color' => env('COMCO_TOUR_FOOTER_BORDER_COLOR', 'rgba(0, 61, 165, 0.12)'),
    'primary_button_color' => env('COMCO_TOUR_PRIMARY_BUTTON_COLOR', '#fdd428'),
    'primary_button_text_color' => env('COMCO_TOUR_PRIMARY_BUTTON_TEXT_COLOR', '#2a3855'),
    'primary_button_hover_color' => env('COMCO_TOUR_PRIMARY_BUTTON_HOVER_COLOR', '#e6c022'),
    'secondary_button_color' => env('COMCO_TOUR_SECONDARY_BUTTON_COLOR', '#2a3855'),
    'secondary_button_text_color' => env('COMCO_TOUR_SECONDARY_BUTTON_TEXT_COLOR', '#ffffff'),
    'secondary_button_hover_color' => env('COMCO_TOUR_SECONDARY_BUTTON_HOVER_COLOR', '#1f2a40'),
  ],
];

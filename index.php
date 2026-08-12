<?php

/**
 * Point d'entrée Hostinger : la racine du site est public_html, pas public/.
 * Délègue le bootstrapping Laravel au front controller officiel.
 */
require __DIR__ . '/public/index.php';

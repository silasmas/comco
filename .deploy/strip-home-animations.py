"""Retire les animations décoratives de la page d'accueil."""

from __future__ import annotations

import re
from pathlib import Path

path = Path(__file__).resolve().parents[1] / "resources" / "views" / "public" / "home" / "index.blade.php"
text = path.read_text(encoding="utf-8")

text = re.sub(r"""\s+data-zanim-[a-zA-Z0-9-]+=(?:'[^']*'|"[^"]*")""", "", text)
text = re.sub(r"""\s+data-inertia=(?:'[^']*'|"[^"]*")""", "", text)
text = re.sub(r"""\s+data-countup=(?:'[^']*'|"[^"]*")""", "", text)

text = text.replace(
    'class="swiper theme-slider min-vh-100" data-swiper=\'{"loop":true,"allowTouchMove":false,"autoplay":{"delay":5000},"effect":"fade","speed":800}\'',
    'class="swiper theme-slider comco-hero" data-swiper=\'{"loop":true,"allowTouchMove":true,"autoplay":false,"effect":"fade","speed":400}\'',
)
text = text.replace(
    'class="row min-vh-100 py-8 align-items-center"',
    'class="row comco-hero__inner py-6 align-items-center"',
)
text = text.replace('style="min-height:400px;"', 'style="min-height:240px;"')
text = text.replace(
    'class="swiper theme-slider" data-swiper=\'{"loop":true,"slidesPerView":1,"autoplay":{"delay":5000}}\'',
    'class="swiper theme-slider" data-swiper=\'{"loop":true,"slidesPerView":1,"autoplay":false}\'',
)
text = text.replace(
    "  <script src=\"{{ themeAsset('vendors/countup/countUp.umd.js') }}\"></script>\n",
    "",
)

path.write_text(text, encoding="utf-8")
print("updated", path)
print("data-zanim left:", "data-zanim" in text)
print("data-inertia left:", "data-inertia" in text)
print("min-vh-100 left:", "min-vh-100" in text)

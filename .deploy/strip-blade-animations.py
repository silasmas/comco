"""Retire data-zanim / data-inertia des gabarits Blade restants."""

from __future__ import annotations

import re
from pathlib import Path

root = Path(__file__).resolve().parents[1]
files = [
    root / "resources/views/public/pages/templates/about.blade.php",
    root / "resources/views/public/pages/templates/alumni.blade.php",
    root / "resources/views/livewire/public/latest-posts.blade.php",
    root / "resources/views/public/pages/templates/newsroom.blade.php",
    root / "resources/views/public/pages/templates/service.blade.php",
    root / "resources/views/public/posts/show.blade.php",
]

for path in files:
    text = path.read_text(encoding="utf-8")
    updated = re.sub(r"""\s+data-zanim-[a-zA-Z0-9-]+=(?:'[^']*'|"[^"]*")""", "", text)
    updated = re.sub(r"""\s+data-inertia=(?:'[^']*'|"[^"]*")""", "", updated)
    if updated != text:
        path.write_text(updated, encoding="utf-8")
        print("updated", path.relative_to(root))
    else:
        print("unchanged", path.relative_to(root))

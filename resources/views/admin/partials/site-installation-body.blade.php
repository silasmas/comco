<style>
  .comco-install * { box-sizing: border-box; }
  .comco-install .alert {
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-weight: 500;
  }
  .comco-install .alert-success { background: #e8f8ef; color: #1f6b43; border: 1px solid #b8e6cc; }
  .comco-install .alert-error { background: #fdecec; color: #8b2e2e; border: 1px solid #f0bcbc; }
  .comco-install .card {
    background: #fff;
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 8px 28px rgba(0, 61, 165, 0.08);
  }
  .comco-install .card h2 {
    margin: 0 0 8px;
    font-size: 1.15rem;
    color: #003DA5;
  }
  .comco-install .card p {
    margin: 0 0 18px;
    color: #5a6478;
    line-height: 1.55;
    font-size: 0.95rem;
  }
  .comco-install .actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }
  .comco-install .btn {
    display: inline-block;
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none !important;
    font-weight: 600;
    font-size: 0.92rem;
    border: none;
    cursor: pointer;
    line-height: 1.2;
  }
  .comco-install .btn-primary { background: #003DA5 !important; color: #fff !important; }
  .comco-install .btn-warning { background: #fdd428 !important; color: #2a3855 !important; }
  .comco-install .btn-success { background: #36b36a !important; color: #fff !important; }
  .comco-install .btn-secondary { background: #e8edf5 !important; color: #2a3855 !important; }
  .comco-install .btn-disabled {
    background: #d7dde8 !important;
    color: #7a8496 !important;
    cursor: not-allowed;
    pointer-events: none;
  }
  .comco-install .grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }
  @media (max-width: 720px) {
    .comco-install .grid { grid-template-columns: 1fr; }
  }
  .comco-install label {
    display: block;
    font-size: 0.88rem;
    font-weight: 600;
    margin-bottom: 6px;
  }
  .comco-install input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #cfd8e6;
    border-radius: 8px;
    font-size: 0.95rem;
  }
  .comco-install input:focus {
    outline: 2px solid rgba(0, 61, 165, 0.25);
    border-color: #003DA5;
  }
  .comco-install .field { margin-bottom: 14px; }
  .comco-install .header-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 24px;
  }
  .comco-install .status-list {
    list-style: none;
    margin: 0 0 18px;
    padding: 0;
    display: grid;
    gap: 10px;
  }
  .comco-install .status-item {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 10px;
    background: #f7f9fc;
    border: 1px solid #e2e8f0;
  }
  .comco-install .status-item.is-done {
    background: #edf9f1;
    border-color: #b8e6cc;
  }
  .comco-install .status-item.is-pending {
    background: #fff8e8;
    border-color: #f5dfa8;
  }
  .comco-install .status-item.is-blocked {
    background: #f3f4f6;
    border-color: #d7dde8;
    opacity: 0.85;
  }
  .comco-install .status-label {
    font-weight: 600;
    color: #2a3855;
  }
  .comco-install .status-detail {
    display: block;
    margin-top: 4px;
    font-size: 0.88rem;
    color: #5a6478;
    font-weight: 400;
  }
  .comco-install .status-badge {
    flex-shrink: 0;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 700;
    white-space: nowrap;
  }
  .comco-install .status-badge.done { background: #d7f2e2; color: #1f6b43; }
  .comco-install .status-badge.pending { background: #fff1c7; color: #8a6b00; }
  .comco-install .status-badge.blocked { background: #e8edf5; color: #6b7280; }
  .comco-install .seed-highlight {
    border: 2px solid #fdd428;
    box-shadow: 0 10px 30px rgba(253, 212, 40, 0.18);
  }
  .comco-install .hint {
    margin: 0 0 14px;
    padding: 10px 12px;
    border-radius: 8px;
    background: #eef4ff;
    color: #2a3855;
    font-size: 0.9rem;
  }
</style>

@php
  $status = $status ?? [];
  $counts = $status['counts'] ?? [];
  $databaseReady = (bool) ($status['databaseReady'] ?? false);
  $contentSeeded = (bool) ($status['contentSeeded'] ?? false);
  $postsSeeded = (bool) ($status['postsSeeded'] ?? false);
@endphp

<div class="comco-install">
  @if (is_array($notice))
    <div class="alert alert-{{ ($notice['type'] ?? '') === 'success' ? 'success' : 'error' }}">
      <strong>{{ $notice['title'] ?? 'Installation' }}</strong><br>
      {{ $notice['body'] ?? '' }}
    </div>
  @endif

  <div class="card">
    <h2>État de l'installation</h2>
    <p>Suivi des étapes essentielles. Les seeders ne peuvent s'exécuter qu'après les migrations.</p>
    <ul class="status-list">
      <li class="status-item {{ ($status['databaseReady'] ?? false) ? 'is-done' : 'is-pending' }}">
        <div>
          <span class="status-label">Base de données</span>
          <span class="status-detail">Tables migrées et prêtes pour les seeders.</span>
        </div>
        <span class="status-badge {{ ($status['databaseReady'] ?? false) ? 'done' : 'pending' }}">
          {{ ($status['databaseReady'] ?? false) ? 'OK' : 'En attente' }}
        </span>
      </li>
      <li class="status-item {{ $contentSeeded ? 'is-done' : ($databaseReady ? 'is-pending' : 'is-blocked') }}">
        <div>
          <span class="status-label">Contenus institutionnels</span>
          <span class="status-detail">
            @if ($contentSeeded)
              {{ $counts['pages'] ?? 0 }} pages, {{ $counts['homeBlocks'] ?? 0 }} blocs accueil, {{ $counts['navigationItems'] ?? 0 }} liens menu, {{ $counts['eServices'] ?? 0 }} e-services.
            @elseif ($databaseReady)
              Seeder non exécuté — le site utilise encore les valeurs de repli.
            @else
              Exécutez d'abord les migrations.
            @endif
          </span>
        </div>
        <span class="status-badge {{ $contentSeeded ? 'done' : ($databaseReady ? 'pending' : 'blocked') }}">
          {{ $contentSeeded ? 'Importé' : ($databaseReady ? 'À faire' : 'Bloqué') }}
        </span>
      </li>
      <li class="status-item {{ $postsSeeded ? 'is-done' : ($databaseReady ? 'is-pending' : 'is-blocked') }}">
        <div>
          <span class="status-label">Articles d'actualité</span>
          <span class="status-detail">
            @if ($postsSeeded)
              {{ $counts['posts'] ?? 0 }} article(s) de démonstration en base.
            @elseif ($databaseReady)
              Seeder articles non exécuté.
            @else
              Exécutez d'abord les migrations.
            @endif
          </span>
        </div>
        <span class="status-badge {{ $postsSeeded ? 'done' : ($databaseReady ? 'pending' : 'blocked') }}">
          {{ $postsSeeded ? 'Importé' : ($databaseReady ? 'À faire' : 'Bloqué') }}
        </span>
      </li>
      <li class="status-item {{ ($status['storageLinked'] ?? false) ? 'is-done' : 'is-pending' }}">
        <div>
          <span class="status-label">Lien storage</span>
          <span class="status-detail">Accès public aux fichiers téléversés.</span>
        </div>
        <span class="status-badge {{ ($status['storageLinked'] ?? false) ? 'done' : 'pending' }}">
          {{ ($status['storageLinked'] ?? false) ? 'OK' : 'À faire' }}
        </span>
      </li>
      <li class="status-item {{ ($status['superAdminExists'] ?? false) ? 'is-done' : 'is-pending' }}">
        <div>
          <span class="status-label">Super administrateur</span>
          <span class="status-detail">Compte principal du panneau Filament.</span>
        </div>
        <span class="status-badge {{ ($status['superAdminExists'] ?? false) ? 'done' : 'pending' }}">
          {{ ($status['superAdminExists'] ?? false) ? 'Créé' : 'À faire' }}
        </span>
      </li>
    </ul>
  </div>

  @if ($requiresInstallation)
    <div class="header-actions">
      <a class="btn btn-success" href="{{ $adminPrefix }}/install/launch" onclick="return confirm('Mettre le site en ligne ? Assurez-vous d\'avoir exécuté les migrations et créé le super administrateur.');">
        Mettre le site en ligne
      </a>
      <a class="btn btn-warning" href="{{ $adminPrefix }}/install/run-all" onclick="return confirm('Exécuter migrations, contenus, lien storage et optimisation ?');">
        Tout installer
      </a>
    </div>
  @endif

  <div class="card">
    <h2>Étapes système</h2>
    <p>Prépare le serveur : migrations (import auto des contenus si la base est vide), lien storage et cache Laravel.</p>
    <div class="actions">
      <a class="btn btn-primary" href="{{ $adminPrefix }}/install/migrate" onclick="return confirm('Exécuter les migrations ?');">Exécuter les migrations</a>
      <a class="btn btn-primary" href="{{ $adminPrefix }}/install/storage-link" onclick="return confirm('Créer le lien storage ?');">Créer le lien storage</a>
      <a class="btn btn-secondary" href="{{ $adminPrefix }}/install/optimize" onclick="return confirm('Optimiser pour la production ?');">Optimiser pour la production</a>
    </div>
  </div>

  <div class="card seed-highlight">
    <h2>Seeders — contenus dynamiques</h2>
    <p>Importe en base les pages, la navigation, l'accueil, le contact, les e-services et les articles. Indispensable pour que le site soit modifiable depuis Filament.</p>

    @if (! $databaseReady)
      <p class="hint">Les boutons seeders seront actifs après l'exécution des migrations.</p>
    @elseif ($contentSeeded && $postsSeeded)
      <p class="hint">Les seeders ont déjà été exécutés. Vous pouvez relancer un import pour mettre à jour les contenus sans créer de doublons.</p>
    @elseif ($contentSeeded)
      <p class="hint">Les contenus institutionnels sont importés. Il reste à importer les articles d'actualité si besoin.</p>
    @else
      <p class="hint">Aucun seeder détecté en base : cliquez sur un bouton ci-dessous pour rendre le site dynamique.</p>
    @endif

    <div class="actions">
      @if ($databaseReady)
        <a class="btn btn-warning" href="{{ $adminPrefix }}/install/seed-all" onclick="return confirm('Exécuter tous les seeders (contenus + articles) ?');">
          Exécuter tous les seeders
        </a>
        <a class="btn btn-primary" href="{{ $adminPrefix }}/install/seed-content" onclick="return confirm('Importer tous les contenus institutionnels ?');">
          {{ $contentSeeded ? 'Réimporter les contenus' : 'Importer les contenus du site' }}
        </a>
        <a class="btn btn-secondary" href="{{ $adminPrefix }}/install/seed-posts" onclick="return confirm('Importer les articles de démonstration ?');">
          {{ $postsSeeded ? 'Réimporter les articles' : 'Importer les articles d\'actualité' }}
        </a>
      @else
        <span class="btn btn-disabled">Exécuter tous les seeders</span>
        <span class="btn btn-disabled">Importer les contenus du site</span>
        <span class="btn btn-disabled">Importer les articles d'actualité</span>
      @endif
    </div>
  </div>

  <div class="card">
    <h2>Configuration de base</h2>
    <p>Met à jour les variables essentielles dans le fichier <code>.env</code>.</p>
    <form method="post" action="{{ $adminPrefix }}/install/save-configuration">
      @csrf
      <div class="grid">
        <div class="field">
          <label for="app_url">URL du site (APP_URL)</label>
          <input id="app_url" name="app_url" type="url" value="{{ $configValues['app_url'] }}" required>
        </div>
        <div class="field">
          <label for="app_env">Environnement (APP_ENV)</label>
          <input id="app_env" name="app_env" type="text" value="{{ $configValues['app_env'] }}" required>
        </div>
        <div class="field">
          <label for="app_debug">Mode debug (APP_DEBUG)</label>
          <input id="app_debug" name="app_debug" type="text" value="{{ $configValues['app_debug'] }}" required>
        </div>
        <div class="field">
          <label for="institution_email">Email institutionnel</label>
          <input id="institution_email" name="institution_email" type="email" value="{{ $configValues['institution_email'] }}" required>
        </div>
        <div class="field" style="grid-column: 1 / -1;">
          <label for="institution_full_name">Nom complet de l'institution</label>
          <input id="institution_full_name" name="institution_full_name" type="text" value="{{ $configValues['institution_full_name'] }}" required>
        </div>
        <div class="field">
          <label for="institution_name">Sigle (INSTITUTION_NAME)</label>
          <input id="institution_name" name="institution_name" type="text" value="{{ $configValues['institution_name'] }}" required>
        </div>
        <div class="field">
          <label for="mail_from_address">Email expéditeur</label>
          <input id="mail_from_address" name="mail_from_address" type="email" value="{{ $configValues['mail_from_address'] }}" required>
        </div>
        <div class="field" style="grid-column: 1 / -1;">
          <label for="mail_from_name">Nom expéditeur</label>
          <input id="mail_from_name" name="mail_from_name" type="text" value="{{ $configValues['mail_from_name'] }}" required>
        </div>
      </div>
      <button class="btn btn-primary" type="submit">Enregistrer la configuration</button>
    </form>
  </div>

  <div class="card">
    <h2>Super administrateur</h2>
    <p>Crée le compte principal d'accès au panneau d'administration.</p>
    <form method="post" action="{{ $adminPrefix }}/install/create-super-admin">
      @csrf
      <div class="grid">
        <div class="field">
          <label for="name">Nom complet</label>
          <input id="name" name="name" type="text" value="{{ $adminDefaults['name'] }}" required>
        </div>
        <div class="field">
          <label for="email">Email de connexion</label>
          <input id="email" name="email" type="email" value="{{ $adminDefaults['email'] }}" required>
        </div>
        <div class="field">
          <label for="password">Mot de passe</label>
          <input id="password" name="password" type="password" required>
        </div>
        <div class="field">
          <label for="password_confirmation">Confirmer le mot de passe</label>
          <input id="password_confirmation" name="password_confirmation" type="password" required>
        </div>
      </div>
      <button class="btn btn-primary" type="submit">Créer le super admin</button>
    </form>
  </div>
</div>

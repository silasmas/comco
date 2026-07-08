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
    text-decoration: none;
    font-weight: 600;
    font-size: 0.92rem;
    border: none;
    cursor: pointer;
  }
  .comco-install .btn-primary { background: #003DA5; color: #fff; }
  .comco-install .btn-warning { background: #fdd428; color: #2a3855; }
  .comco-install .btn-success { background: #36b36a; color: #fff; }
  .comco-install .btn-secondary { background: #e8edf5; color: #2a3855; }
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
</style>

<div class="comco-install">
  @if (is_array($notice))
    <div class="alert alert-{{ ($notice['type'] ?? '') === 'success' ? 'success' : 'error' }}">
      <strong>{{ $notice['title'] ?? 'Installation' }}</strong><br>
      {{ $notice['body'] ?? '' }}
    </div>
  @endif

  @if ($requiresInstallation)
    <div class="header-actions">
      <a class="btn btn-success" href="{{ $adminPrefix }}/install/launch" onclick="return confirm('Mettre le site en ligne ? Assurez-vous d\'avoir exécuté les migrations et créé le super administrateur.');">
        Mettre le site en ligne
      </a>
      <a class="btn btn-warning" href="{{ $adminPrefix }}/install/run-all" onclick="return confirm('Exécuter migrations, lien storage et optimisation ?');">
        Tout installer
      </a>
    </div>
  @endif

  <div class="card">
    <h2>Étapes système</h2>
    <p>Prépare le serveur : migrations, lien storage, cache Laravel et contenus de démonstration. Chaque action recharge cette page.</p>
    <div class="actions">
      <a class="btn btn-primary" href="{{ $adminPrefix }}/install/migrate" onclick="return confirm('Exécuter les migrations ?');">Exécuter les migrations</a>
      <a class="btn btn-primary" href="{{ $adminPrefix }}/install/storage-link" onclick="return confirm('Créer le lien storage ?');">Créer le lien storage</a>
      <a class="btn btn-secondary" href="{{ $adminPrefix }}/install/optimize" onclick="return confirm('Optimiser pour la production ?');">Optimiser pour la production</a>
    </div>
  </div>

  <div class="card">
    <h2>Contenus de démonstration</h2>
    <p>Importe les pages CMS, la navigation, l'accueil, le contact, les e-services et les médias attachés. Les contenus existants sont mis à jour sans doublon.</p>
    <div class="actions">
      <a class="btn btn-primary" href="{{ $adminPrefix }}/install/seed-content" onclick="return confirm('Importer tous les contenus institutionnels ?');">Importer les contenus du site</a>
      <a class="btn btn-secondary" href="{{ $adminPrefix }}/install/seed-posts" onclick="return confirm('Importer les articles de démonstration ?');">Importer les articles d'actualité</a>
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

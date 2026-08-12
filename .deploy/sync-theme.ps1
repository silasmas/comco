<#
.SYNOPSIS
  Déploie / synchronise le thème Elixir public/theme vers Hostinger.
.DESCRIPTION
  Le site Hostinger sert public_html avec rewrite vers public/.
  Sans public/theme, le CSS/JS manque et le site s'affiche sans style.
#>
param(
  [string]$SshHost = "comcordc.cd",
  [int]$SshPort = 65002,
  [string]$SshUser = "u911414181",
  [string]$RemotePublic = "/home/u911414181/domains/comcordc.cd/public_html/public",
  [string]$SshPassword
)

$ErrorActionPreference = "Stop"
$localTheme = Join-Path $PSScriptRoot ".." "public" "theme"
if (-not (Test-Path $localTheme)) {
  throw "Thème local introuvable: $localTheme"
}

Write-Host "Export SVN distant (recommandé si SSH indisponible)..."
Write-Host "Commande serveur:"
Write-Host "svn export --force https://github.com/silasmas/comco/trunk/public/theme $RemotePublic/theme"

if ($SshPassword) {
  Write-Host "Tentative SCP via plink/pscp si disponible..."
}

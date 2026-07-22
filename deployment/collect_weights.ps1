# Copies the trained models into deployment/model/weights/ so the model image
# can mount them.
#
# The models are TensorFlow SavedModel DIRECTORIES (not .h5 files) and live
# outside the repository, so they have to be staged before `docker compose up`.
#
#   powershell -ExecutionPolicy Bypass -File deployment\collect_weights.ps1
#
# Override the source root if your models live elsewhere:
#   ... -File deployment\collect_weights.ps1 -SourceRoot "D:\models"

param(
    [string]$SourceRoot = "$env:USERPROFILE\AnocondaProjects"
)

$ErrorActionPreference = 'Stop'
$dest = Join-Path $PSScriptRoot 'model\weights'

# name -> source path
$models = [ordered]@{
    'presys' = Join-Path $SourceRoot 'BrainT\Final training\models\presys'
    '1sys'   = Join-Path $SourceRoot 'BrainT\Final training\models\1sys'
    '102mod' = Join-Path $SourceRoot 'MRI_Image_Classificaion\102mod'
}

New-Item -ItemType Directory -Force -Path $dest | Out-Null

$missing = @()
foreach ($name in $models.Keys) {
    $src = $models[$name]
    if (-not (Test-Path $src)) { $missing += "$name  ->  $src"; continue }

    $target = Join-Path $dest $name
    if (Test-Path $target) { Remove-Item -Recurse -Force $target }
    Copy-Item -Recurse -Force $src $target

    $pb = Join-Path $target 'saved_model.pb'
    $ok = if (Test-Path $pb) { 'OK' } else { 'WARNING: no saved_model.pb' }
    Write-Host ("  {0,-8} {1}" -f $name, $ok)
}

if ($missing.Count -gt 0) {
    Write-Host ""
    Write-Warning "Could not find:"
    $missing | ForEach-Object { Write-Host "    $_" }
    Write-Host "Pass -SourceRoot <path> if your models are stored elsewhere."
    exit 1
}

Write-Host ""
Write-Host "Weights staged in $dest"
Write-Host "Next:  cd deployment ; docker compose up -d --build"

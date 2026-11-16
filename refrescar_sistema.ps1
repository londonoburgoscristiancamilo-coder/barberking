
# ==========================================
# BARBERKING - REFRESCAR SISTEMA
# ==========================================

$ErrorActionPreference = "Stop"

# Ubicarse en la raíz del proyecto
Set-Location $PSScriptRoot

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "       BARBERKING - REFRESCAR SISTEMA       " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan

# Verificar si Docker Compose está disponible
docker compose version

if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Docker Compose no está disponible." -ForegroundColor Red
    exit 1
}

# Comandos que se ejecutarán en orden
$comandos = @(
    @{
        Nombre = "Limpiando vistas Blade"
        Argumentos = @("compose", "exec", "-T", "app", "php", "artisan", "view:clear")
    },
    @{
        Nombre = "Compilando vistas Blade"
        Argumentos = @("compose", "exec", "-T", "app", "php", "artisan", "view:cache")
    },
    @{
        Nombre = "Limpiando vistas compiladas"
        Argumentos = @("compose", "exec", "-T", "app", "php", "artisan", "view:clear")
    },
    @{
        Nombre = "Compilando recursos con Vite"
        Argumentos = @("compose", "exec", "-T", "app", "npm", "run", "build")
    }
)

foreach ($comando in $comandos) {
    Write-Host ""
    Write-Host ">> $($comando.Nombre)..." -ForegroundColor Yellow

    & docker @($comando.Argumentos)

    if ($LASTEXITCODE -ne 0) {
        Write-Host ""
        Write-Host "ERROR: Falló el proceso: $($comando.Nombre)" -ForegroundColor Red
        exit $LASTEXITCODE
    }

    Write-Host "OK: $($comando.Nombre)" -ForegroundColor Green
}

Write-Host ""
Write-Host "==========================================" -ForegroundColor Green
Write-Host "     BARBERKING ACTUALIZADO CORRECTAMENTE  " -ForegroundColor Green
Write-Host "==========================================" -ForegroundColor Green
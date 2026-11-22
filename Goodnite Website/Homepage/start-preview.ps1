$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $MyInvocation.MyCommand.Path
$portablePhp = Join-Path $root '.runtime\php\php.exe'
$php = if (Test-Path -LiteralPath $portablePhp) {
    $portablePhp
} elseif (Test-Path 'C:\xampp\php\php.exe') {
    'C:\xampp\php\php.exe'
} else {
    (Get-Command php -ErrorAction SilentlyContinue).Source
}

if (-not $php) {
    Write-Host 'PHP is not installed or is not available on PATH.' -ForegroundColor Yellow
    Write-Host 'Install PHP 8.1 or newer, then run this file again.'
    Write-Host 'Preview URL after installation: http://127.0.0.1:8080'
    exit 1
}

Set-Location $root
Write-Host 'Goodnite preview: http://127.0.0.1:8080' -ForegroundColor Green
& $php -S 127.0.0.1:8080 server/router.php

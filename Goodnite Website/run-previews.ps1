<#
.SYNOPSIS
    Starts standalone preview servers for the Goodnite Website prototype templates.
#>
param(
    [ValidateSet('all', 'home', 'collection', 'product', 'contact', 'policy')]
    [string]$Page = 'home'
)

$php = if (Test-Path 'C:\xampp\php\php.exe') {
    'C:\xampp\php\php.exe'
} else {
    (Get-Command php -ErrorAction SilentlyContinue).Source
}

if (-not $php) {
    Write-Host "PHP executable not found on PATH or in C:\xampp\php\php.exe." -ForegroundColor Red
    exit 1
}

$baseDir = Split-Path -Parent $MyInvocation.MyCommand.Path

$servers = @(
    @{ Name = 'Homepage';       Path = "$baseDir\Homepage";             Port = 8080; Router = 'server/router.php' },
    @{ Name = 'Collection Page'; Path = "$baseDir\Collection Page";      Port = 8081; Router = '' },
    @{ Name = 'Product Page';    Path = "$baseDir\Product Page";         Port = 8082; Router = '' },
    @{ Name = 'Contact Us Page'; Path = "$baseDir\Contact Us Page";      Port = 8083; Router = '' },
    @{ Name = 'Policy Page';     Path = "$baseDir\Policy Page Template"; Port = 8084; Router = '' }
)

Write-Host "=============================================" -ForegroundColor Cyan
Write-Host " Goodnite Standalone Website Preview Runner" -ForegroundColor Cyan
Write-Host "=============================================" -ForegroundColor Cyan

if ($Page -eq 'all') {
    foreach ($srv in $servers) {
        Write-Host "Starting $($srv.Name) on http://127.0.0.1:$($srv.Port)..." -ForegroundColor Green
        $args = if ($srv.Router) { "-S 127.0.0.1:$($srv.Port) $($srv.Router)" } else { "-S 127.0.0.1:$($srv.Port)" }
        Start-Process -FilePath $php -ArgumentList $args -WorkingDirectory $srv.Path
    }
    Write-Host "`nAll prototype servers launched in background!" -ForegroundColor Yellow
    Write-Host "1. Homepage:        http://127.0.0.1:8080"
    Write-Host "2. Collection Page: http://127.0.0.1:8081"
    Write-Host "3. Product Page:    http://127.0.0.1:8082"
    Write-Host "4. Contact Us Page: http://127.0.0.1:8083"
    Write-Host "5. Policy Page:     http://127.0.0.1:8084"
} else {
    $target = $servers | Where-Object { $_.Name -like "*$Page*" } | Select-Object -First 1
    if (-not $target) { $target = $servers[0] }
    Write-Host "Starting $($target.Name) on http://127.0.0.1:$($target.Port)..." -ForegroundColor Green
    Write-Host "Press Ctrl+C to stop the preview server.`n" -ForegroundColor DarkGray
    Set-Location $target.Path
    if ($target.Router) {
        & $php -S 127.0.0.1:$($target.Port) $target.Router
    } else {
        & $php -S 127.0.0.1:$($target.Port)
    }
}

# Starts the Laravel dev server (if not already running) and opens Chrome.
$root = $PSScriptRoot
Set-Location $root

$listening = Get-NetTCPConnection -LocalPort 8000 -State Listen -ErrorAction SilentlyContinue
if (-not $listening) {
    Write-Host "Starting Laravel server..."
    Start-Process -FilePath 'C:\xampp\php\php.exe' `
        -ArgumentList 'artisan','serve','--host=127.0.0.1','--port=8000' `
        -WorkingDirectory $root -WindowStyle Hidden
    Start-Sleep -Seconds 4
} else {
    Write-Host "Laravel server already running."
}

$chrome = 'C:\Program Files\Google\Chrome\Application\chrome.exe'
if (-not (Test-Path $chrome)) {
    $chrome = 'chrome'
}

Write-Host "Opening Chrome at http://127.0.0.1:8000/login"
Start-Process -FilePath $chrome -ArgumentList 'http://127.0.0.1:8000/login'

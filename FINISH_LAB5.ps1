param(
    [Parameter(Mandatory=$true)][string]$RepoUrl,
    [Parameter(Mandatory=$true)][string]$LastName
)

$ErrorActionPreference = 'Stop'
$root = 'C:\xampp\htdocs\Lab5_2411600236'
$env:Path = 'C:\Program Files\Git\cmd;' + $env:Path
Set-Location $root

# 1. Push to GitHub
git remote remove origin 2>$null
git remote add origin $RepoUrl
git push -u origin main

# 2. Regenerate the lab report PDF with your real name
$answers = Get-Content -Raw "$root\LAB_REPORT_ANSWERS.md"
$answersHtml = [System.Net.WebUtility]::HtmlEncode($answers)
$shots = Get-ChildItem "$root\screenshots\*.png" | Sort-Object Name
$imgs = ''
foreach ($s in $shots) {
    $url = 'file:///'+($s.FullName -replace '\\','/')
    $imgs += "<figure><img src='$url' /><figcaption>$($s.Name)</figcaption></figure>`n"
}
$html = @"
<!doctype html><html><head><meta charset='utf-8'><title>Lab 5 Report - Lab5_2411600236</title>
<style>
body{font-family:Segoe UI,Arial,sans-serif;font-size:12pt;margin:0;padding:28px;color:#222}
h1,h2{color:#2e7d32} header{text-align:center;border-bottom:3px solid #f28c28;padding-bottom:14px;margin-bottom:22px}
.school{font-weight:bold;font-size:14pt}.meta{display:grid;grid-template-columns:1fr 1fr;gap:4px;margin:18px 0;text-align:left}
figure{margin:14px 0;break-inside:avoid}img{max-width:100%;border:1px solid #ccc;border-radius:6px}
figcaption{font-size:10pt;color:#666;margin-top:4px}pre{white-space:pre-wrap;font-family:Consolas,monospace;font-size:9.5pt;background:#f7f7f7;padding:12px;border:1px solid #ddd}
.page-break{page-break-before:always}
</style></head><body>
<header><div class='school'>RAMON MAGSAYSAY MEMORIAL COLLEGES</div><div>Information Technology Education Program</div><div>1st SEMESTER: AY 2026 - 2027</div><h1>LABORATORY EXERCISE 5</h1><h2>IMPLEMENTING MVC ARCHITECTURE WITH LARAVEL: INVENTORY MANAGEMENT SYSTEM</h2></header>
<div class='meta'><div><b>NAME:</b> $LastName</div><div><b>SCHEDULE:</b> ________________</div><div><b>SUBJECT:</b> Web Systems and Technologies</div><div><b>INSTRUCTOR:</b> ________________</div><div><b>DATE:</b> November 23, 2026</div><div><b>SCORE:</b> ________________</div></div>
<h2>Output / Results</h2>$imgs
<div class='page-break'></div><h2>Questions, Answers, and Conclusion</h2><pre>$answersHtml</pre>
</body></html>
"@
Set-Content -LiteralPath "$root\Lab5_2411600236_LabReport.html" -Value $html -Encoding UTF8
& 'C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe' --headless --disable-gpu --allow-file-access-from-files --virtual-time-budget=8000 --print-to-pdf="$root\Lab5_2411600236_$LastName.pdf" "file:///C:/xampp/htdocs/Lab5_2411600236/Lab5_2411600236_LabReport.html" 2>$null

git add Lab5_2411600236_LabReport.html
git commit -m "Final: lab report for $LastName" 2>$null
git push

Write-Host "DONE. Report: $root\Lab5_2411600236_$LastName.pdf"
Write-Host "GitHub: $RepoUrl"

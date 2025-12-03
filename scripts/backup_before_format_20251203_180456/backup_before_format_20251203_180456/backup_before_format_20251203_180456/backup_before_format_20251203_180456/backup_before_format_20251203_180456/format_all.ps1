# Backup current workspace
$workspace = Split-Path -Parent $MyInvocation.MyCommand.Definition
$backupDir = Join-Path $workspace "backup_before_format_$(Get-Date -Format yyyyMMdd_HHmmss)"
Write-Host "Creating backup at $backupDir"
New-Item -ItemType Directory -Force -Path $backupDir | Out-Null

# Copy all files to backup
Write-Host "Copying files to backup..."
Get-ChildItem -Path $workspace -Recurse -File | ForEach-Object {
    $dest = $_.FullName.Replace($workspace, $backupDir)
    $destDir = Split-Path -Parent $dest
    if (!(Test-Path $destDir)) { New-Item -ItemType Directory -Force -Path $destDir | Out-Null }
    Copy-Item -Path $_.FullName -Destination $dest -Force
}

# File extensions to format
$exts = @('*.php','*.html','*.htm','*.js','*.css','*.json','*.md','*.txt')

# Formatting rules:
# - Convert leading tabs to 4 spaces
# - Trim trailing whitespace on each line
# - Ensure file ends with a single newline
# - Replace mixed indentation sequences (tabs) in leading area

Write-Host "Formatting files..."
foreach ($ext in $exts) {
    Get-ChildItem -Path $workspace -Recurse -Include $ext -File | ForEach-Object {
        $path = $_.FullName
        try {
            $content = Get-Content -Raw -Encoding UTF8 -LiteralPath $path
        } catch {
            # If reading as UTF8 fails, skip
            Write-Host "Skipping $path (read error)"
            return
        }

        # Normalize line endings to CRLF
        $content = $content -replace "\r?\n", "`r`n"

        # Replace leading tabs at start of lines with four spaces per tab
        # Use regex to replace any leading tabs in each line
        $content = ($content -split "`r`n") | ForEach-Object {
            $line = $_
            # Replace leading tabs
            $line = $line -replace '^(\t+)', { param($m) (' ' * (4 * $m.Groups[1].Value.Length)) }
            # Trim trailing spaces
            $line = $line -replace '\s+$',''
            $line
        } -join "`r`n"

        # Ensure single newline at EOF
        if (-not $content.EndsWith("`r`n")) {
            $content += "`r`n"
        }

        # Write back if changed
        $orig = Get-Content -Raw -Encoding UTF8 -LiteralPath $path
        if ($orig -ne $content) {
            Set-Content -LiteralPath $path -Value $content -Encoding UTF8
            Write-Host "Formatted: $path"
        }
    }
}

Write-Host "Formatting complete. Backup saved to: $backupDir"

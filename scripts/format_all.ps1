# Backup current workspace
$workspace = Split-Path -Parent $MyInvocation.MyCommand.Definition
# Put backup outside the workspace folder to avoid recursive copying
$parent = Split-Path -Parent $workspace
$backupDir = Join-Path $parent "Eduqueue_backup_before_format_$(Get-Date -Format yyyyMMdd_HHmmss)"
Write-Host "Creating backup at $backupDir"
New-Item -ItemType Directory -Force -Path $backupDir | Out-Null

# Copy all files to backup (exclude the backup dir if accidentally inside workspace)
Write-Host "Copying files to backup..."
$excludeRegex = 'backup_before_format|Eduqueue_backup_before_format'
Write-Host "Removing old in-repo backup folders if present..."
Get-ChildItem -Path $workspace -Directory -Recurse -Force | Where-Object { $_.Name -match '^backup_before_format' -or $_.Name -match '^Eduqueue_backup_before_format' } | ForEach-Object {
    try {
        Remove-Item -LiteralPath $_.FullName -Recurse -Force -ErrorAction Stop
        Write-Host "Removed: $($_.FullName)"
    } catch {
        Write-Host "Warning: couldn't remove $($_.FullName): $($_.Exception.Message)"
    }
}

Get-ChildItem -Path $workspace -Recurse -File | Where-Object { $_.FullName -notlike "$backupDir*" -and ($_ -notmatch $excludeRegex) } | ForEach-Object {
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
    Get-ChildItem -Path $workspace -Recurse -Include $ext -File | Where-Object { $_.FullName -notlike "$backupDir*" -and ($_ -notmatch $excludeRegex) } | ForEach-Object {
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

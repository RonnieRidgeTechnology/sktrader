# auto_push.ps1

# Navigate to project directory
Set-Location "D:\Projects\sktrader"

# Check for changes
git status

# Stage all changes
git add -A

# Commit with current date and time
$commitMessage = "Auto-commit: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
git commit -m "$commitMessage"

# Push to the remote (default branch)
git push origin main

Write-Host "Changes committed and pushed successfully!"
# Git Sync Script
# This script syncs current branch with development, merges to master, and returns to development

param(
    [string]$CurrentBranch = (git branch --show-current)
)

Write-Host "Starting Git Sync Process..." -ForegroundColor Green
Write-Host "Current branch: $CurrentBranch" -ForegroundColor Yellow

# Step 1: Switch to development and pull latest changes
Write-Host "`nStep 1: Switching to development and pulling latest changes..." -ForegroundColor Cyan
git checkout development
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error: Failed to checkout development branch" -ForegroundColor Red
    exit 1
}

# Try to pull from origin/development, if it doesn't exist, just continue
git pull origin development 2>$null
if ($LASTEXITCODE -ne 0) {
    Write-Host "Warning: No remote development branch found, continuing with local development branch" -ForegroundColor Yellow
}

# Step 2: Switch back to original branch and merge development
Write-Host "`nStep 2: Switching back to $CurrentBranch and merging development..." -ForegroundColor Cyan
git checkout $CurrentBranch
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error: Failed to checkout $CurrentBranch branch" -ForegroundColor Red
    exit 1
}

git merge development
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error: Failed to merge development into $CurrentBranch" -ForegroundColor Red
    Write-Host "Please resolve conflicts manually and run the script again" -ForegroundColor Yellow
    exit 1
}

# Step 3: Switch to master and merge current branch
Write-Host "`nStep 3: Switching to master and merging $CurrentBranch..." -ForegroundColor Cyan
git checkout master
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error: Failed to checkout master branch" -ForegroundColor Red
    exit 1
}

git pull origin master
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error: Failed to pull latest changes from master" -ForegroundColor Red
    exit 1
}

git merge $CurrentBranch
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error: Failed to merge $CurrentBranch into master" -ForegroundColor Red
    Write-Host "Please resolve conflicts manually and run the script again" -ForegroundColor Yellow
    exit 1
}

# Step 4: Push master changes
Write-Host "`nStep 4: Pushing master changes..." -ForegroundColor Cyan
git push origin master
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error: Failed to push master changes" -ForegroundColor Red
    exit 1
}

# Step 5: Switch back to development and merge master
Write-Host "`nStep 5: Switching back to development and syncing with master..." -ForegroundColor Cyan
git checkout development
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error: Failed to checkout development branch" -ForegroundColor Red
    exit 1
}

git merge master
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error: Failed to merge master into development" -ForegroundColor Red
    Write-Host "Please resolve conflicts manually and run the script again" -ForegroundColor Yellow
    exit 1
}

# Try to push development to remote, if it fails, just continue
git push origin development 2>$null
if ($LASTEXITCODE -ne 0) {
    Write-Host "Warning: Could not push development branch to remote (may not exist on remote)" -ForegroundColor Yellow
}

# Step 6: Switch back to original branch
Write-Host "`nStep 6: Switching back to original branch $CurrentBranch..." -ForegroundColor Cyan
git checkout $CurrentBranch
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error: Failed to checkout $CurrentBranch branch" -ForegroundColor Red
    exit 1
}

Write-Host "`nGit Sync completed successfully!" -ForegroundColor Green
Write-Host "Summary:" -ForegroundColor Yellow
Write-Host "- Synced $CurrentBranch with development" -ForegroundColor White
Write-Host "- Merged $CurrentBranch to master" -ForegroundColor White
Write-Host "- Synced development with master" -ForegroundColor White
Write-Host "- Returned to $CurrentBranch" -ForegroundColor White

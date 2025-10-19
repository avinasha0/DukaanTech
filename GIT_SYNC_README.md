# Git Sync Command

This repository includes a custom `git sync` command that automates the process of syncing your current branch with development, merging to master, and syncing back to development.

## What it does

The `git sync` command performs the following steps:

1. **Switch to development branch** and pull latest changes (if remote exists)
2. **Switch back to your current branch** and merge development into it
3. **Switch to master branch** and merge your current branch into master
4. **Push master changes** to remote
5. **Switch back to development** and merge master into development
6. **Push development changes** to remote (if remote exists)
7. **Return to your original branch**

## How to use

### Option 1: Using the batch file (Recommended)
```bash
.\git-sync.bat
```

### Option 2: Using PowerShell directly
```powershell
.\git-sync.ps1
```

### Option 3: Using git sync command (if PowerShell profile is loaded)
```bash
git sync
```

## Files created

- `git-sync.ps1` - Main PowerShell script
- `git-sync.bat` - Batch file wrapper
- `git-sync.cmd` - Command file for PATH usage
- PowerShell profile updated to support `git sync` command

## Requirements

- PowerShell 5.0 or later
- Git installed and accessible via command line
- Repository with `development` and `master` branches

## Notes

- The script handles cases where the development branch doesn't exist on the remote
- All git operations include error handling and will stop on failures
- The script preserves your current working directory and uncommitted changes
- You can run this from any directory within your git repository

## Troubleshooting

If you get permission errors when running PowerShell scripts:
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

If the `git sync` command doesn't work, make sure your PowerShell profile is loaded:
```powershell
. $PROFILE
```

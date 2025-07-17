# Project Rules for Claude

## Environment Configuration

**IMPORTANT: This project runs in Windows environment, NOT WSL**

- All commands should be provided for Windows PowerShell or Command Prompt
- Do NOT install packages or run commands in WSL environment
- The project path is `C:\Users\antho\ustawi` (Windows path)
- Claude Code is being accessed through WSL, but the actual project and development environment is Windows
- When providing commands, always use Windows-compatible syntax

## Development Commands

When working on this Laravel project, use these Windows PowerShell commands:

```powershell
# Install dependencies
composer install
npm install

# Run development server
npm run dev

# Run build
npm run build
```
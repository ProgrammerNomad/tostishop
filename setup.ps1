# TostiShop Theme Setup Script (PowerShell)
# Run this script to set up the development environment on Windows

Write-Host "🚀 Setting up TostiShop WordPress Theme..." -ForegroundColor Green
Write-Host "📍 Logo location: assets/images/logo.png" -ForegroundColor Cyan

# Check if Node.js is installed
try {
    $nodeVersion = node --version
    Write-Host "✅ Node.js is installed: $nodeVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ Node.js is not installed. Please install Node.js first." -ForegroundColor Red
    Write-Host "Visit: https://nodejs.org/" -ForegroundColor Yellow
    exit 1
}

# Check if npm is installed
try {
    $npmVersion = npm --version
    Write-Host "✅ npm is installed: $npmVersion" -ForegroundColor Green
} catch {
    Write-Host "❌ npm is not installed. Please install npm first." -ForegroundColor Red
    exit 1
}

# Install dependencies
Write-Host "📦 Installing npm dependencies..." -ForegroundColor Blue
npm install

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Dependencies installed successfully" -ForegroundColor Green
} else {
    Write-Host "❌ Failed to install dependencies" -ForegroundColor Red
    exit 1
}

# Build CSS for the first time
Write-Host "🎨 Building Tailwind CSS..." -ForegroundColor Blue
npm run build

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ CSS built successfully" -ForegroundColor Green
} else {
    Write-Host "❌ Failed to build CSS" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "🎉 TostiShop theme setup completed!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Next steps:" -ForegroundColor Yellow
Write-Host "1. Activate the theme in WordPress admin"
Write-Host "2. Install and activate WooCommerce plugin"
Write-Host "3. Run 'npm run dev' to start development mode"
Write-Host "4. Customize the theme as needed"
Write-Host ""
Write-Host "📚 Useful commands:" -ForegroundColor Yellow
Write-Host "  npm run dev      - Watch for changes and rebuild CSS"
Write-Host "  npm run build    - Build CSS for production"
Write-Host "  npm run build-dev - Build CSS for development"
Write-Host ""
Write-Host "📖 Check COPILOT-INSTRUCTIONS.md for detailed development guide" -ForegroundColor Cyan

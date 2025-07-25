#!/bin/bash

# TostiShop Theme Setup Script
# Run this script to set up the development environment

echo "🚀 Setting up TostiShop WordPress Theme..."

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    echo "❌ Node.js is not installed. Please install Node.js first."
    echo "Visit: https://nodejs.org/"
    exit 1
fi

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    echo "❌ npm is not installed. Please install npm first."
    exit 1
fi

echo "✅ Node.js and npm are installed"

# Install dependencies
echo "📦 Installing npm dependencies..."
npm install

if [ $? -eq 0 ]; then
    echo "✅ Dependencies installed successfully"
else
    echo "❌ Failed to install dependencies"
    exit 1
fi

# Build CSS for the first time
echo "🎨 Building Tailwind CSS..."
npm run build

if [ $? -eq 0 ]; then
    echo "✅ CSS built successfully"
else
    echo "❌ Failed to build CSS"
    exit 1
fi

# Set up file permissions (if on Unix-like system)
if [[ "$OSTYPE" == "linux-gnu"* ]] || [[ "$OSTYPE" == "darwin"* ]]; then
    echo "🔐 Setting file permissions..."
    find . -type f -name "*.php" -exec chmod 644 {} \;
    find . -type f -name "*.css" -exec chmod 644 {} \;
    find . -type f -name "*.js" -exec chmod 644 {} \;
    find . -type d -exec chmod 755 {} \;
    echo "✅ File permissions set"
fi

echo ""
echo "🎉 TostiShop theme setup completed!"
echo ""
echo "📋 Next steps:"
echo "1. Activate the theme in WordPress admin"
echo "2. Install and activate WooCommerce plugin"
echo "3. Run 'npm run dev' to start development mode"
echo "4. Customize the theme as needed"
echo ""
echo "📚 Useful commands:"
echo "  npm run dev      - Watch for changes and rebuild CSS"
echo "  npm run build    - Build CSS for production"
echo "  npm run build-dev - Build CSS for development"
echo ""
echo "📖 Check COPILOT-INSTRUCTIONS.md for detailed development guide"

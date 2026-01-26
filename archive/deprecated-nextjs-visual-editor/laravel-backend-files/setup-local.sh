#!/bin/bash

# Setup Visual Editor Backend Locally (for testing)
# Usage: ./setup-local.sh

set -e  # Exit on error

echo "🚀 Setting up Visual Editor Backend Locally..."
echo ""

# Configuration
LARAVEL_PATH="$HOME/Desktop/Mein Business/Programmierprojekte/musikfuerfirmen-api"
LOCAL_FILES="$(dirname "$0")"

# Check if Laravel project exists
if [ -d "$LARAVEL_PATH" ]; then
    echo "⚠️  Laravel project already exists at:"
    echo "   $LARAVEL_PATH"
    echo ""
    read -p "Use existing project? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "❌ Setup cancelled"
        exit 1
    fi
else
    echo "📦 Creating new Laravel project..."
    echo "   This may take a few minutes..."
    composer create-project laravel/laravel "$LARAVEL_PATH"
fi

cd "$LARAVEL_PATH"

# Install dependencies
echo ""
echo "📦 Installing dependencies..."
composer require intervention/image

# Copy files
echo ""
echo "📂 Copying files..."

echo "   ✓ Models"
cp "$LOCAL_FILES/app/Models/Page.php" app/Models/

echo "   ✓ Controllers"
cp "$LOCAL_FILES/app/Http/Controllers/PageController.php" app/Http/Controllers/
cp "$LOCAL_FILES/app/Http/Controllers/MediaController.php" app/Http/Controllers/

echo "   ✓ Migration"
MIGRATION_FILE="database/migrations/$(date +%Y_%m_%d)_000000_create_pages_table.php"
cp "$LOCAL_FILES/database/migrations/2026_01_17_create_pages_table.php" "$MIGRATION_FILE"

echo "   ✓ Seeder"
cp "$LOCAL_FILES/database/seeders/ConvertHomepageSeeder.php" database/seeders/

echo "   ✓ CORS config"
cp "$LOCAL_FILES/config/cors.php" config/

echo "   ✓ API routes"
cp "$LOCAL_FILES/routes/api.php" routes/

# Configure .env
echo ""
echo "⚙️  Configuring environment..."

# Check if PostgreSQL is available
if command -v psql &> /dev/null; then
    echo "   ✓ PostgreSQL detected"
    USE_POSTGRES=true
else
    echo "   ⚠️  PostgreSQL not found, using SQLite instead"
    USE_POSTGRES=false
fi

if [ "$USE_POSTGRES" = true ]; then
    # PostgreSQL configuration
    cat >> .env <<EOL

# Visual Editor Configuration
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=musikfuerfirmen
DB_USERNAME=postgres
DB_PASSWORD=

# CORS
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:3001
EOL

    echo ""
    echo "📝 PostgreSQL database 'musikfuerfirmen' needs to be created"
    read -p "Create database now? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        createdb musikfuerfirmen || echo "   ⚠️  Database might already exist"
    fi
else
    # SQLite configuration (fallback)
    cat >> .env <<EOL

# Visual Editor Configuration (SQLite)
DB_CONNECTION=sqlite
DB_DATABASE=$LARAVEL_PATH/database/database.sqlite

# CORS
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:3001
EOL

    # Create SQLite database
    touch database/database.sqlite

    echo ""
    echo "⚠️  NOTE: Using SQLite instead of PostgreSQL"
    echo "   JSONB features will be limited. Consider installing PostgreSQL for production."
fi

# Run migrations
echo ""
echo "🗄️  Running migrations..."
php artisan migrate

# Seed database
echo ""
read -p "Seed database with example pages? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan db:seed --class=ConvertHomepageSeeder
fi

# Create storage link
echo ""
echo "📁 Setting up storage..."
php artisan storage:link
mkdir -p storage/app/public/editor-images
mkdir -p storage/app/temp
mkdir -p storage/app/public/uploads/{hero,services,team,general}
chmod -R 755 storage/app/public/editor-images
chmod -R 755 storage/app/temp
chmod -R 755 storage/app/public/uploads

# Clear caches
echo ""
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Test
echo ""
echo "✅ Setup Complete!"
echo ""
echo "📋 Next Steps:"
echo ""
echo "1. Start Laravel server:"
echo "   cd $LARAVEL_PATH"
echo "   php artisan serve --host=0.0.0.0 --port=8000"
echo ""
echo "2. Test API:"
echo "   curl http://localhost:8000/api/pages"
echo ""
echo "3. Update Next.js .env.local:"
echo "   NEXT_PUBLIC_API_URL=http://localhost:8000/api"
echo ""
echo "4. Start Next.js:"
echo "   cd musikfuerfirmen"
echo "   npm run dev"
echo ""
echo "5. Access editor:"
echo "   http://localhost:3000/admin/pages"
echo ""

# Offer to start server
read -p "Start Laravel server now? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo ""
    echo "🚀 Starting Laravel server..."
    echo "   Press Ctrl+C to stop"
    echo ""
    php artisan serve --host=0.0.0.0 --port=8000
fi

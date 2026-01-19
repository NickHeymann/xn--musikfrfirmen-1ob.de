#!/bin/bash

# Deploy Visual Editor Backend to Hetzner
# Usage: ./deploy-to-hetzner.sh

set -e  # Exit on error

echo "🚀 Deploying Visual Editor Backend to Hetzner..."

# Configuration
SERVER="hetzner"
LARAVEL_PATH="/opt/musikfuerfirmen-api"
LOCAL_FILES="$(dirname "$0")"

echo ""
echo "📋 Configuration:"
echo "   Server: $SERVER (46.224.6.69)"
echo "   Laravel Path: $LARAVEL_PATH"
echo "   Local Files: $LOCAL_FILES"
echo ""

# Step 1: Check if Laravel project exists on server
echo "1️⃣  Checking Laravel project on server..."
if ssh $SERVER "[ -d $LARAVEL_PATH ]"; then
    echo "   ✅ Laravel project exists at $LARAVEL_PATH"
    USE_EXISTING=true
else
    echo "   ⚠️  Laravel project not found at $LARAVEL_PATH"
    read -p "   Create new Laravel project? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo "   📦 Creating new Laravel project..."
        ssh $SERVER "cd /opt && composer create-project laravel/laravel musikfuerfirmen-api"
        USE_EXISTING=false
    else
        echo "   ❌ Deployment cancelled"
        exit 1
    fi
fi

# Step 2: Copy files to server
echo ""
echo "2️⃣  Copying files to server..."

echo "   📤 Uploading Models..."
scp "$LOCAL_FILES/app/Models/Page.php" "$SERVER:$LARAVEL_PATH/app/Models/"

echo "   📤 Uploading Controllers..."
scp "$LOCAL_FILES/app/Http/Controllers/PageController.php" "$SERVER:$LARAVEL_PATH/app/Http/Controllers/"
scp "$LOCAL_FILES/app/Http/Controllers/MediaController.php" "$SERVER:$LARAVEL_PATH/app/Http/Controllers/"

echo "   📤 Uploading Migration..."
MIGRATION_FILE="$LARAVEL_PATH/database/migrations/$(date +%Y_%m_%d)_000000_create_pages_table.php"
scp "$LOCAL_FILES/database/migrations/2026_01_17_create_pages_table.php" "$SERVER:$MIGRATION_FILE"

echo "   📤 Uploading Seeder..."
scp "$LOCAL_FILES/database/seeders/ConvertHomepageSeeder.php" "$SERVER:$LARAVEL_PATH/database/seeders/"

echo "   📤 Uploading CORS config..."
scp "$LOCAL_FILES/config/cors.php" "$SERVER:$LARAVEL_PATH/config/"

echo "   📤 Uploading API routes..."
echo "   ⚠️  WARNING: This will overwrite routes/api.php"
read -p "   Continue? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    scp "$LOCAL_FILES/routes/api.php" "$SERVER:$LARAVEL_PATH/routes/"
else
    echo "   ⏭️  Skipping API routes (you'll need to merge manually)"
fi

# Step 3: Install dependencies
echo ""
echo "3️⃣  Installing Laravel dependencies..."
ssh $SERVER "cd $LARAVEL_PATH && composer require intervention/image"

# Step 4: Configure environment
echo ""
echo "4️⃣  Configuring environment..."
echo "   📝 Please update .env file on server with:"
echo ""
echo "   DB_CONNECTION=pgsql"
echo "   DB_DATABASE=musikfuerfirmen"
echo "   DB_USERNAME=your_db_user"
echo "   DB_PASSWORD=your_db_password"
echo "   CORS_ALLOWED_ORIGINS=http://localhost:3000,https://musikfuerfirmen.de"
echo ""
read -p "   Press Enter when .env is configured..."

# Step 5: Run migrations
echo ""
echo "5️⃣  Running migrations..."
ssh $SERVER "cd $LARAVEL_PATH && php artisan migrate"

# Step 6: Seed database
echo ""
echo "6️⃣  Seeding database..."
read -p "   Seed database with example pages? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    ssh $SERVER "cd $LARAVEL_PATH && php artisan db:seed --class=ConvertHomepageSeeder"
else
    echo "   ⏭️  Skipping database seed"
fi

# Step 7: Create storage link
echo ""
echo "7️⃣  Setting up storage..."
ssh $SERVER "cd $LARAVEL_PATH && php artisan storage:link"
ssh $SERVER "cd $LARAVEL_PATH && mkdir -p storage/app/public/editor-images && chmod -R 755 storage/app/public/editor-images"

# Step 8: Clear caches
echo ""
echo "8️⃣  Clearing Laravel caches..."
ssh $SERVER "cd $LARAVEL_PATH && php artisan config:clear && php artisan route:clear && php artisan cache:clear"

# Step 9: Set permissions
echo ""
echo "9️⃣  Setting permissions..."
ssh $SERVER "cd $LARAVEL_PATH && chmod -R 755 storage bootstrap/cache"

# Step 10: Test API
echo ""
echo "🔟  Testing API..."
echo "   Waiting for Laravel to be ready..."
sleep 2

# Check if we can reach the API
if ssh $SERVER "cd $LARAVEL_PATH && php artisan route:list | grep 'api/pages'"; then
    echo "   ✅ API routes registered successfully"
else
    echo "   ⚠️  Could not verify API routes"
fi

# Final steps
echo ""
echo "✅ Deployment Complete!"
echo ""
echo "📋 Next Steps:"
echo ""
echo "1. Configure Nginx/Apache to serve Laravel (if not done)"
echo "2. Test API endpoint:"
echo "   curl http://your-server-ip:8000/api/pages"
echo ""
echo "3. Update Next.js .env.local:"
echo "   NEXT_PUBLIC_API_URL=http://your-server-ip:8000/api"
echo ""
echo "4. For production, configure HTTPS and update CORS"
echo ""
echo "📖 See DEPLOYMENT.md for detailed production setup"
echo ""

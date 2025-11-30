# Masuk ke folder project
cd C:\apps\myapp

# Install dependencies
composer install
npm install 

# Copy .env dan sesuaikan DB
cp .env.example .env
php artisan key:generate

# Migrasi database
php artisan migrate

# Jalankan server 
php artisan serve 
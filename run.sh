#!/bin/sh

echo "=> Running frontend"
npm run dev &
echo "=> Running backend"
php artisan serve &


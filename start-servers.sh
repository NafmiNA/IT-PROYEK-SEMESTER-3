#!/bin/bash

# Script untuk start Laravel + Vite dengan database MySQL
# Usage: ./start-servers.sh

cd "$(dirname "$0")"

echo "🧹 Cleaning caches..."
php artisan config:clear
php artisan cache:clear
rm -rf bootstrap/cache/*.php

echo ""
echo "🔧 Starting Vite development server..."
npm run dev > /tmp/vite-dev.log 2>&1 &
VITE_PID=$!
echo "   Vite PID: $VITE_PID"
sleep 3

echo ""
echo "🚀 Starting Laravel server with MySQL..."
export DB_CONNECTION=mysql
export DB_DATABASE=p3m_sistem
export DB_HOST=127.0.0.1
export DB_PORT=3306
export DB_USERNAME=root
export DB_PASSWORD=

php artisan serve > /tmp/laravel-serve.log 2>&1 &
LARAVEL_PID=$!
echo "   Laravel PID: $LARAVEL_PID"
sleep 3

echo ""
echo "✅ Servers started successfully!"
echo ""
echo "📊 Server Status:"
echo "   Vite:    http://localhost:5173 (PID: $VITE_PID)"
echo "   Laravel: http://127.0.0.1:8000 (PID: $LARAVEL_PID)"
echo ""
echo "📝 Logs:"
echo "   Vite:    tail -f /tmp/vite-dev.log"
echo "   Laravel: tail -f /tmp/laravel-serve.log"
echo ""
echo "🛑 To stop servers:"
echo "   kill $VITE_PID $LARAVEL_PID"
echo "   atau: pkill -f 'vite|php artisan serve'"
echo ""
echo "🌐 Login Info:"
echo "   URL: http://127.0.0.1:8000/login"
echo "   Email: andi@kampus.ac.id"
echo "   Password: password123"
echo ""

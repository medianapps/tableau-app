#!/bin/sh

echo "=> Running frontend"
npm run dev &
FRONTEND_PID=$!

echo "=> Running backend"
php artisan serve &
BACKEND_PID=$!

# Function to stop both processes
stop_processes() {
    echo "=> Stopping frontend (PID: $FRONTEND_PID)"
    kill $FRONTEND_PID

    echo "=> Stopping backend (PID: $BACKEND_PID)"
    kill $BACKEND_PID

    # Wait for both processes to exit
    wait $FRONTEND_PID
    wait $BACKEND_PID

    echo "=> Both processes stopped"
}

# Trap SIGINT and SIGTERM to stop the processes
trap stop_processes INT TERM

# Wait for both processes to finish
wait $FRONTEND_PID
wait $BACKEND_PID

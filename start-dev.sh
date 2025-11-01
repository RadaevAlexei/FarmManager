#!/bin/bash

echo "Starting FarmManager Development Server..."

# Start backend server
echo "Starting Backend server on http://localhost:8080"
cd backend/web
php -S localhost:8080 &
BACKEND_PID=$!

# Start frontend server
echo "Starting Frontend server on http://localhost:8081"
cd ../../frontend/web
php -S localhost:8081 &
FRONTEND_PID=$!

echo ""
echo "FarmManager is running!"
echo "Backend: http://localhost:8080"
echo "Frontend: http://localhost:8081"
echo ""
echo "Login credentials:"
echo "Username: admin"
echo "Password: password123"
echo ""
echo "Press Ctrl+C to stop servers"

# Wait for interrupt
trap "echo 'Stopping servers...'; kill $BACKEND_PID $FRONTEND_PID; exit" INT
wait 
#!/bin/sh
# docker-entrypoint.sh

# Run any initialization commands you need here
echo "Starting Laravel application..."

# Start PHP FPM service
exec "$@"

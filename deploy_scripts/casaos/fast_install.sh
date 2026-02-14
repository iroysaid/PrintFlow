#!/bin/bash
echo "=========================================="
echo "   PRINTFLOW POS - CASAOS INSTALLER"
echo "=========================================="
echo "Starting Docker Containers..."

# Navigate to script directory
cd "$(dirname "$0")"

# Run Docker Compose
docker-compose up -d --build

echo "=========================================="
echo "   INSTALLATION COMPLETE"
echo "=========================================="
echo "Access App at: http://<YOUR-CASAOS-IP>:8085"

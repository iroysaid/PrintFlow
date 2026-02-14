#!/bin/bash
# Get local IP
IP=$(ipconfig getifaddr en0)
if [ -z "$IP" ]; then
    IP=$(ipconfig getifaddr en1)
fi

echo "=========================================="
echo "   PRINTFLOW POS SERVER (MAC OS)   "
echo "=========================================="
echo "Local IP: $IP"
echo "Port: 4448"
echo "Access from other devices: http://$IP:4448"
echo "=========================================="
echo "Starting Server... (Do not close this window)"

# Navigate to project root (assuming script is in deploy_scripts/mac_os)
cd "$(dirname "$0")/../../"

# Open Firewall for port 4448 (Requires Password)
echo "Setting up Firewall rule..."
sudo /usr/libexec/ApplicationFirewall/socketfilterfw --add $(which php)
sudo /usr/libexec/ApplicationFirewall/socketfilterfw --unblockapp $(which php)

# Open Browser
open "http://localhost:4448/pos"

# Start Server
php spark serve --host 0.0.0.0 --port 4448

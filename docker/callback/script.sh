#!/bin/sh

echo "This script will call the callback periodically"

while true; do wget -qO- http://nginx/callback; echo ""; sleep 30; done

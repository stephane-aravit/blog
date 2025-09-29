#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x run-worker.sh`

# CECI EST UN FICHIER JUSTE POUR LE DEPLOIEMENT SUR RAILWAY

# This command runs the queue worker.
# An alternative is to use the php artisan queue:listen command
php artisan queue:work
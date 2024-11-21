#!/bin/bash

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m'

echo "Installing Website Creation Helper..."

# Check PHP version
PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
if (( $(echo "$PHP_VERSION < 8.0" | bc -l) )); then
    echo -e "${RED}Error: PHP 8.0 or higher is required${NC}"
    exit 1
fi

# Check Composer
if ! command -v composer &> /dev/null; then
    echo -e "${RED}Error: Composer is not installed${NC}"
    exit 1
fi

# Check Node.js
if ! command -v node &> /dev/null; then
    echo -e "${RED}Error: Node.js is not installed${NC}"
    exit 1
fi

# Install dependencies
composer install --no-dev --optimize-autoloader

# Make CLI executable
chmod +x bin/wch

echo -e "${GREEN}Installation completed successfully!${NC}"
echo "You can now use the 'wch' command to create new projects." 
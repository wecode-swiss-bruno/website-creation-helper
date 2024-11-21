#!/bin/bash

# Vérifier que Composer est installé
if ! command -v composer &> /dev/null; then
    echo "Composer n'est pas installé"
    exit 1
fi

# Installer les dépendances
composer install

# Vérifier que PHPUnit est installé
if ! [ -f "vendor/bin/phpunit" ]; then
    echo "PHPUnit n'est pas installé correctement"
    exit 1
fi

# Créer le dossier de couverture si nécessaire
mkdir -p coverage

echo "Installation terminée avec succès!" 
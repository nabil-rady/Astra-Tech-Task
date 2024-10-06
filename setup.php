<?php
if (!file_exists('.env')) {
    if (copy('.env.example', '.env')) {
        echo ".env file created successfully.\n";
    } else {
        echo "Failed to create .env file.\n";
    }
} else {
    echo ".env file already exists.\n";
}

if (!file_exists('database/database.sqlite')) {
    if (touch('database/database.sqlite')) {
        echo "Database file created successfully.\n";
    } else {
        echo "Failed to create database file.\n";
    }
} else {
    echo "Database file already exists.\n";
}

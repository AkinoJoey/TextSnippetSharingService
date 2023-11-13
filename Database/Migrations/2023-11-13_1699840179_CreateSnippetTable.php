<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateSnippetTable implements SchemaMigration
{
    public function up(): array
    {
        return [
            "CREATE TABLE IF NOT EXISTS snippets(
                id INT PRIMARY KEY AUTO_INCREMENT,
                snippet TEXT NOT NULL,
                url VARCHAR(255) NOT NULL UNIQUE,
                language VARCHAR(50) NOT NULL,
                expiration VARCHAR(50) NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP);"
        ];
    }

    public function down(): array
    {
        return [
            "DROP TABLE snippets"
        ];
    }
}
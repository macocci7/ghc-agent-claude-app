<?php

require_once __DIR__ . '/helpers.php';

function connectDatabase(): PDO 
{
    $config = config('database');
    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        $config['host'],
        $config['port'],
        $config['database']
    );
    
    return new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}

function runMigrations(): void
{
    try {
        $pdo = connectDatabase();
        
        // Create migrations table if not exists
        $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            filename VARCHAR(255) NOT NULL UNIQUE,
            executed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // Get executed migrations
        $stmt = $pdo->query("SELECT filename FROM migrations");
        $executed = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Get migration files
        $migrationFiles = glob(__DIR__ . '/database/migration/*.php');
        sort($migrationFiles);
        
        foreach ($migrationFiles as $file) {
            $filename = basename($file);
            
            if (in_array($filename, $executed)) {
                echo "Skipping: $filename (already executed)\n";
                continue;
            }
            
            echo "Executing: $filename\n";
            
            require_once $file;
            
            // Extract class name from filename  
            $className = str_replace('.php', '', preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $filename));
            $className = str_replace('_', '', ucwords($className, '_'));
            
            if (!class_exists($className)) {
                throw new Exception("Migration class $className not found in $filename");
            }
            
            $migration = new $className();
            $sql = $migration->up();
            
            $pdo->exec($sql);
            
            // Record migration
            $stmt = $pdo->prepare("INSERT INTO migrations (filename) VALUES (?)");
            $stmt->execute([$filename]);
            
            echo "Completed: $filename\n";
        }
        
        echo "All migrations completed successfully.\n";
        
    } catch (Exception $e) {
        echo "Migration failed: " . $e->getMessage() . "\n";
        exit(1);
    }
}

if (php_sapi_name() === 'cli') {
    runMigrations();
}

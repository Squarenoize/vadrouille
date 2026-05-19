<?php

/**
 * Singleton for database connection using PDO
 * This class ensures that only one PDO instance is created and shared across the application.
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * Returns the unique PDO instance
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                    DB_USER,
                    DB_PASS,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
                // Set MySQL timezone to match PHP timezone (Europe/Paris)
                // Calculate offset dynamically to handle DST (Daylight Saving Time)
                $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
                $offset = $now->format('P');
                self::$instance->exec("SET time_zone = '$offset'");
            } catch (PDOException $e) {
                error_log("Database connection failed: " . $e->getMessage());
                throw new RuntimeException("Connexion à la base de données impossible", 0, $e);
            }
        }
        return self::$instance;
    }
}

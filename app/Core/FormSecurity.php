<?php

/**
 * Class FormSecurity
 * Provides multiple layers of protection against bots for public forms
 */
class FormSecurity
{

    private const SESSION_CSRF_KEY = 'csrf_tokens';
    private const SESSION_RATE_LIMIT_KEY = 'form_submissions';
    private const SESSION_FORM_TIMESTAMP_KEY = 'form_timestamps';

    // Rate limiting: max submissions per IP
    private const MAX_SUBMISSIONS_PER_HOUR = 5;
    private const MAX_SUBMISSIONS_PER_DAY = 20;

    // Time-based protection: minimum time to fill form (in seconds)
    private const MIN_FORM_FILL_TIME = 3;

    /**
     * Generate a CSRF token for form protection
     * 
     * @param string $formName Name of the form (e.g., 'contact_form')
     * @return string The generated token
     */
    public static function generateCsrfToken(string $formName): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Generate a unique token
        $token = bin2hex(random_bytes(32));

        // Store in session with timestamp
        if (!isset($_SESSION[self::SESSION_CSRF_KEY])) {
            $_SESSION[self::SESSION_CSRF_KEY] = [];
        }

        $_SESSION[self::SESSION_CSRF_KEY][$formName] = [
            'token' => $token,
            'timestamp' => time()
        ];

        return $token;
    }

    /**
     * Verify CSRF token
     * 
     * @param string $formName Name of the form
     * @param string|null $submittedToken Token submitted with the form
     * @return bool True if valid, false otherwise
     */
    public static function verifyCsrfToken(string $formName, ?string $submittedToken): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if token exists in session
        if (!isset($_SESSION[self::SESSION_CSRF_KEY][$formName])) {
            return false;
        }

        $storedData = $_SESSION[self::SESSION_CSRF_KEY][$formName];

        // Check token expiration (1 hour)
        if (time() - $storedData['timestamp'] > 3600) {
            unset($_SESSION[self::SESSION_CSRF_KEY][$formName]);
            return false;
        }

        // Verify token match
        $isValid = hash_equals($storedData['token'], $submittedToken ?? '');

        // Remove token after use (one-time use)
        if ($isValid) {
            unset($_SESSION[self::SESSION_CSRF_KEY][$formName]);
        }

        return $isValid;
    }

    /**
     * Check honeypot field (should be empty)
     * 
     * @param string $honeypotValue Value of the honeypot field
     * @return bool True if valid (empty), false if filled (bot detected)
     */
    public static function checkHoneypot(?string $honeypotValue): bool
    {
        return empty($honeypotValue);
    }

    /**
     * Store form display timestamp to check fill time later
     * 
     * @param string $formName Name of the form
     */
    public static function storeFormTimestamp(string $formName): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION[self::SESSION_FORM_TIMESTAMP_KEY])) {
            $_SESSION[self::SESSION_FORM_TIMESTAMP_KEY] = [];
        }

        $_SESSION[self::SESSION_FORM_TIMESTAMP_KEY][$formName] = time();
    }

    /**
     * Check if form was filled too quickly (bot behavior)
     * 
     * @param string $formName Name of the form
     * @return bool True if valid, false if too fast
     */
    public static function checkFormFillTime(string $formName): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION[self::SESSION_FORM_TIMESTAMP_KEY][$formName])) {
            // No timestamp stored, suspicious
            return false;
        }

        $formLoadTime = $_SESSION[self::SESSION_FORM_TIMESTAMP_KEY][$formName];
        $timeTaken = time() - $formLoadTime;

        // Remove timestamp after check
        unset($_SESSION[self::SESSION_FORM_TIMESTAMP_KEY][$formName]);

        return $timeTaken >= self::MIN_FORM_FILL_TIME;
    }

    /**
     * Check rate limiting for IP address
     * 
     * @param string $formName Name of the form
     * @return array ['allowed' => bool, 'message' => string]
     */
    public static function checkRateLimit(string $formName): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $ip = self::getClientIp();
        $currentTime = time();

        // Initialize rate limit tracking
        if (!isset($_SESSION[self::SESSION_RATE_LIMIT_KEY])) {
            $_SESSION[self::SESSION_RATE_LIMIT_KEY] = [];
        }

        if (!isset($_SESSION[self::SESSION_RATE_LIMIT_KEY][$formName])) {
            $_SESSION[self::SESSION_RATE_LIMIT_KEY][$formName] = [];
        }

        // Clean old entries (older than 24 hours)
        $_SESSION[self::SESSION_RATE_LIMIT_KEY][$formName] = array_filter(
            $_SESSION[self::SESSION_RATE_LIMIT_KEY][$formName] ?? [],
            function ($entry) use ($currentTime) {
                return ($currentTime - $entry['timestamp']) < 86400; // 24 hours
            }
        );

        // Get submissions for this IP
        $ipSubmissions = array_filter(
            $_SESSION[self::SESSION_RATE_LIMIT_KEY][$formName],
            function ($entry) use ($ip) {
                return $entry['ip'] === $ip;
            }
        );

        // Check hourly limit
        $recentSubmissions = array_filter($ipSubmissions, function ($entry) use ($currentTime) {
            return ($currentTime - $entry['timestamp']) < 3600; // 1 hour
        });

        if (count($recentSubmissions) >= self::MAX_SUBMISSIONS_PER_HOUR) {
            return [
                'allowed' => false,
                'message' => 'Trop de demandes. Veuillez patienter avant de soumettre à nouveau.'
            ];
        }

        // Check daily limit
        if (count($ipSubmissions) >= self::MAX_SUBMISSIONS_PER_DAY) {
            return [
                'allowed' => false,
                'message' => 'Limite quotidienne atteinte. Veuillez réessayer demain.'
            ];
        }

        // Record this submission
        $_SESSION[self::SESSION_RATE_LIMIT_KEY][$formName][] = [
            'ip' => $ip,
            'timestamp' => $currentTime
        ];

        return ['allowed' => true, 'message' => ''];
    }

    /**
     * Get client IP address (handles proxies)
     * 
     * @return string Client IP address
     */
    private static function getClientIp(): string
    {
        $ip = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Can contain multiple IPs, take the first one
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ips[0]);
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }

        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
    }

    /**
     * Comprehensive validation for form submission
     * Returns array with 'valid' boolean and 'errors' array
     * 
     * @param string $formName Name of the form
     * @param array $postData POST data containing form fields
     * @return array ['valid' => bool, 'errors' => array]
     */
    public static function validateFormSubmission(string $formName, array $postData): array
    {
        $errors = [];

        // 1. Check CSRF token
        if (!self::verifyCsrfToken($formName, $postData['csrf_token'] ?? null)) {
            $errors[] = 'Token de sécurité invalide ou expiré. Veuillez recharger la page.';
        }

        // 2. Check honeypot
        if (!self::checkHoneypot($postData['website'] ?? null)) {
            $errors[] = 'Soumission invalide détectée.';
        }

        // 3. Check form fill time
        if (!self::checkFormFillTime($formName)) {
            $errors[] = 'Le formulaire a été soumis trop rapidement. Veuillez prendre le temps de le remplir.';
        }

        // 4. Check rate limiting
        $rateLimit = self::checkRateLimit($formName);
        if (!$rateLimit['allowed']) {
            $errors[] = $rateLimit['message'];
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Clean old session data to prevent memory bloat
     */
    public static function cleanOldSessionData(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $currentTime = time();

        // Clean old CSRF tokens (older than 1 hour)
        if (isset($_SESSION[self::SESSION_CSRF_KEY])) {
            $_SESSION[self::SESSION_CSRF_KEY] = array_filter(
                $_SESSION[self::SESSION_CSRF_KEY],
                function ($data) use ($currentTime) {
                    return ($currentTime - $data['timestamp']) < 3600;
                }
            );
        }

        // Clean old rate limit entries (older than 24 hours)
        if (isset($_SESSION[self::SESSION_RATE_LIMIT_KEY])) {
            foreach ($_SESSION[self::SESSION_RATE_LIMIT_KEY] as $formName => $entries) {
                $_SESSION[self::SESSION_RATE_LIMIT_KEY][$formName] = array_filter(
                    $entries,
                    function ($entry) use ($currentTime) {
                        return ($currentTime - $entry['timestamp']) < 86400;
                    }
                );
            }
        }
    }
}

<?php
/**
 * Validation class
 * Contains static methods to validate form data
 */
class DataVerification {
    
    /**
     * Checks if a string is a valid email
     */
    public static function isValidEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Checks if a string is a valid phone number
     */
    public static function isValidPhone(string $phone): bool {
        // Remove spaces, dashes, parentheses, etc.
        $cleaned = preg_replace('/[\s\-\(\)\+]/', '', $phone);
        // Accept 10 digits (FR) or international format
        return preg_match('/^[0-9]{10,15}$/', $cleaned);
    }
    
    /**
     * Checks if a string is not empty
     */
    public static function isNotEmpty(string $value): bool {
        return trim($value) !== '';
    }
    
    /**
     * Checks the length of a string
     */
    public static function hasValidLength(string $value, int $min, int $max = null): bool {
        $length = strlen(trim($value));
        
        if ($length < $min) {
            return false;
        }
        
        if ($max !== null && $length > $max) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Checks if a value is a positive number
     */
    public static function isPositiveNumber($value): bool {
        return is_numeric($value) && $value > 0;
    }
    
    /**
     * Checks if a date is valid and in the future
     */
    public static function isFutureDate(string $dateString): bool {
        $date = strtotime($dateString);
        
        if ($date === false) {
            return false; // Invalid date
        }
        
        return $date >= strtotime('today');
    }
    
    /**
     * Checks if a value is in an allowed list
     */
    public static function isInList($value, array $allowedValues): bool {
        return in_array($value, $allowedValues, true);
    }
    
    /**
     * Sanitizes a string (basic XSS protection)
     */
    public static function sanitize(string $value): string {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validates a budget (numeric format or text like "2000-3000")
     */
    public static function isValidBudget(string $budget): bool {
        // Accept numeric format or "min-max"
        if (is_numeric($budget) && $budget > 0) {
            return true;
        }
        
        // Format "1000-2000"
        if (preg_match('/^\d+\s*-\s*\d+$/', $budget)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Validates a travel duration (in days)
     */
    public static function isValidDuration($duration): bool {
        return is_numeric($duration) && $duration >= 1 && $duration <= 365;
    }
    
    /**
     * Validates the number of travelers
     */
    public static function isValidTravelerCount($count): bool {
        return is_numeric($count) && $count >= 0 && $count <= 20;
    }
}

<?php
/**
 * Helper class for trip-related operations
 */
class TripHelper {
    
    /**
     * Group trip items by day (based on start_datetime)
     * @param array $items Array of TripItem objects
     * @return array Associative array with date as key (Y-m-d) and array of items as value
     */
    public static function groupItemsByDay(array $items): array {
        $grouped = [];
        
        // Set French locale for date formatting
        $oldLocale = setlocale(LC_TIME, 0);
        setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'fra', 'french');
        
        foreach ($items as $item) {
            $day = $item->getStartDatetime()->format('Y-m-d');
            
            if (!isset($grouped[$day])) {
                // Format: "Lundi 12 mai 2026" using IntlDateFormatter for better French support
                if (class_exists('IntlDateFormatter')) {
                    $formatter = new IntlDateFormatter(
                        'fr_FR',
                        IntlDateFormatter::FULL,
                        IntlDateFormatter::NONE,
                        'Europe/Paris',
                        IntlDateFormatter::GREGORIAN,
                        'EEEE d MMMM yyyy'
                    );
                    $displayDate = $formatter->format($item->getStartDatetime());
                } else {
                    // Fallback to basic format if Intl extension not available
                    $displayDate = strftime('%A %e %B %Y', $item->getStartDatetime()->getTimestamp());
                }
                
                $grouped[$day] = [
                    'date' => $item->getStartDatetime()->format('Y-m-d'),
                    'displayDate' => ucfirst($displayDate),
                    'items' => []
                ];
            }
            
            $grouped[$day]['items'][] = $item;
        }
        
        // Restore original locale
        setlocale(LC_TIME, $oldLocale);
        
        return $grouped;
    }
}

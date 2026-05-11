<?php
/**
 * Controller for trips-related pages and actions
 */
class TripsController {
    
    /**
     * Display the trips page
     */
    public function index(): void {
        // Get SEO data
        $seo = SeoHelper::getPageSeo('trips');
        
        // Render the view
        $view = new View('public/trips', [
            // SEO Meta
            'pageTitle' => $seo['pageTitle'],
            'pageDescription' => $seo['pageDescription'],
            'pageFullUrl' => $seo['pageFullUrl'],
            'pageFullImage' => $seo['pageFullImage'],
            
            // Schemas
            'schemaOrganization' => SeoHelper::getOrganizationSchema(),
            'schemaPage' => SeoHelper::getTripsSchema(),
            'breadcrumbs' => SeoHelper::getBreadcrumbs('trips'),
            
            // Navigation
            'currentAction' => 'trips'
        ], 'public');
        
        $view->render();
    }
}

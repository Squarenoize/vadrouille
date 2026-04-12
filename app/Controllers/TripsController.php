<?php
class TripsController {
    
    /**
     * Afficher la page des voyages
     */
    public function index(): void {
        // Récupérer les données SEO
        $seo = SeoHelper::getPageSeo('trips');
        
        // Afficher la vue
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

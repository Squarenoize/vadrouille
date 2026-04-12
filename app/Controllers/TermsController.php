<?php
class TermsController {
    
    /**
     * Afficher la page mentions légales
     */
    public function index(): void {
        // Récupérer les données SEO
        $seo = SeoHelper::getPageSeo('terms');
        
        // Afficher la vue
        $view = new View('public/terms', [
            // SEO Meta
            'pageTitle' => $seo['pageTitle'],
            'pageDescription' => $seo['pageDescription'],
            'pageFullUrl' => $seo['pageFullUrl'],
            'pageFullImage' => $seo['pageFullImage'],
            
            // Schemas
            'schemaOrganization' => SeoHelper::getOrganizationSchema(),
            'breadcrumbs' => SeoHelper::getBreadcrumbs('terms'),
            
            // Navigation
            'currentAction' => 'terms'
        ], 'public');
        
        $view->render();
    }
}

<?php
class ContactController {
    
    /**
     * Afficher la page de contact
     */
    public function index(): void {
        // Récupérer les données SEO
        $seo = SeoHelper::getPageSeo('contact');
        
        // Afficher la vue
        $view = new View('public/contact', [
            // SEO Meta
            'pageTitle' => $seo['pageTitle'],
            'pageDescription' => $seo['pageDescription'],
            'pageFullUrl' => $seo['pageFullUrl'],
            'pageFullImage' => $seo['pageFullImage'],
            
            // Schemas
            'schemaOrganization' => SeoHelper::getOrganizationSchema(),
            'schemaPage' => SeoHelper::getContactSchema(),
            'breadcrumbs' => SeoHelper::getBreadcrumbs('contact'),
            
            // Navigation
            'currentAction' => 'contact'
        ], 'public');
        
        $view->render();
    }
}

<?php
class AboutController {
    
    /**
     * Afficher la page à propos
     */
    public function index(): void {
        // Récupérer les données SEO
        $seo = SeoHelper::getPageSeo('about');
        
        // Afficher la vue
        $view = new View('public/about', [
            // SEO Meta
            'pageTitle' => $seo['pageTitle'],
            'pageDescription' => $seo['pageDescription'],
            'pageFullUrl' => $seo['pageFullUrl'],
            'pageFullImage' => $seo['pageFullImage'],
            
            // Schemas
            'schemaOrganization' => SeoHelper::getOrganizationSchema(),
            'schemaPage' => SeoHelper::getAboutSchema(),
            'breadcrumbs' => SeoHelper::getBreadcrumbs('about'),
            
            // Navigation
            'currentAction' => 'about'
        ], 'public');
        
        $view->render();
    }
}

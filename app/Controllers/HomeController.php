<?php
class HomeController
{

    /**
     * Show the home page
     */
    public function index(): void
    {
        //Get SEO data
        $seo = SeoHelper::getPageSeo('home');

        // Display the view
        $view = new View('public/home', [
            // SEO Meta
            'pageTitle' => $seo['pageTitle'],
            'pageDescription' => $seo['pageDescription'],
            'pageFullUrl' => $seo['pageFullUrl'],
            'pageFullImage' => $seo['pageFullImage'],

            // Schemas
            'schemaOrganization' => SeoHelper::getOrganizationSchema(),
            'schemaPage' => SeoHelper::getHomeServiceSchema(),
            'schemaFAQ' => SeoHelper::getFaqSchema(),
            'breadcrumbs' => SeoHelper::getBreadcrumbs('home'),

            // Navigation
            'currentAction' => 'home'
        ], 'public');

        $view->render();
    }


    /**
     * Show about page
     */
    public function about(): void
    {
        // Get SEO data
        $seo = SeoHelper::getPageSeo('about');

        // Display the view
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

    /**
     * Show terms page
     */
    public function terms(): void
    {
        // Get SEO data
        $seo = SeoHelper::getPageSeo('terms');

        // Display the view
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

    /**
     * Show privacy policy page
     */
    public function privacy(): void
    {
        // Get SEO data
        $seo = SeoHelper::getPageSeo('privacy');

        // Display the view
        $view = new View('public/privacy', [
            // SEO Meta
            'pageTitle' => $seo['pageTitle'],
            'pageDescription' => $seo['pageDescription'],
            'pageFullUrl' => $seo['pageFullUrl'],
            'pageFullImage' => $seo['pageFullImage'],

            // Schemas
            'schemaOrganization' => SeoHelper::getOrganizationSchema(),
            'breadcrumbs' => SeoHelper::getBreadcrumbs('privacy'),

            // Navigation
            'currentAction' => 'privacy'
        ], 'public');

        $view->render();
    }
}

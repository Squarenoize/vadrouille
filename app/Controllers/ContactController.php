<?php
class ContactController {
    
    /**
     * Show the contact page
     */
    public function index(): void {
        // Get SEO data
        $seo = SeoHelper::getPageSeo('contact');
        
        // Display the view
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

    /**
     * Process the contact form (POST)
     * Traiter le formulaire de contact (POST)
     */
    public function send(): void {
        try {
            // 1. Create the entity from POST data
            $contactRequest = ContactRequest::fromArray($_POST);
            
            // 2. Validate the entity (all logic is in the entity!)
            $errors = $contactRequest->validate();
            
            // 3. If errors, re-display the form
            if (!empty($errors)) {
                $seo = SeoHelper::getPageSeo('contact');
                
                $view = new View('public/contact', [
                    'pageTitle' => $seo['pageTitle'],
                    'pageDescription' => $seo['pageDescription'],
                    'pageFullUrl' => $seo['pageFullUrl'],
                    'pageFullImage' => $seo['pageFullImage'],
                    'schemaOrganization' => SeoHelper::getOrganizationSchema(),
                    'schemaPage' => SeoHelper::getContactSchema(),
                    'breadcrumbs' => SeoHelper::getBreadcrumbs('contact'),
                    'currentAction' => 'contact',
                    'errors' => $errors,
                    'formData' => $_POST  // To pre-fill the form
                ], 'public');
                
                $view->render();
                return;
            }
            
            // 4. Validation OK : Save via the Model
            $contactModel = new ContactRequestModel();
            $contactModel->save($contactRequest);
            
            // 5. Redirect with success message
            $_SESSION['request_success'] = "Votre demande a bien été envoyée ! Nous vous recontacterons rapidement.";
            header('Location: ' . BASE_URL . '/contact');
            exit;
            
        } catch (Exception $e) {
            // In case of error, display a message
            http_response_code(500);
            echo '<h1>Erreur lors du traitement</h1>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '<p><a href="' . BASE_URL . '/contact">← Retour au formulaire</a></p>';
            if (isset($_GET['debug'])) {
                echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
            }
        }
    }
}

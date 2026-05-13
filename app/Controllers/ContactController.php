<?php
/**
 * Controller for the contact page and form processing
 */
class ContactController {
    
    /**
     * Show the contact page
     */
    public function index(): void {
        // Store form display timestamp for time-based validation
        FormSecurity::storeFormTimestamp('contact_form');
        
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
            'currentAction' => 'contact',
            
            // Security
            'csrfToken' => FormSecurity::generateCsrfToken('contact_form')
        ], 'public');
        
        $view->render();
    }

    /**
     * Process the contact form (POST)
     * Traiter le formulaire de contact (POST)
     */
    public function send(): void {
        try {
            // 1. Security validation (CSRF, honeypot, rate limiting, time-based)
            $securityCheck = FormSecurity::validateFormSubmission('contact_form', $_POST);
            
            if (!$securityCheck['valid']) {
                $_SESSION['form_errors'] = $securityCheck['errors'];
                header('Location: ' . BASE_URL . '/contact');
                exit;
            }
            
            // 2. Create the entity from POST data
            $contactRequest = ContactRequest::fromArray($_POST);
            
            // 3. Validate the entity (all logic is in the entity)
            $errors = $contactRequest->validate();
            
            // 4. If errors, re-display the form
            if (!empty($errors)) {
                // Store form timestamp for new token
                FormSecurity::storeFormTimestamp('contact_form');
                
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
                    'formData' => $_POST,  // To pre-fill the form
                    'csrfToken' => FormSecurity::generateCsrfToken('contact_form')
                ], 'public');
                
                $view->render();
                return;
            }
            
            // 5. Validation OK : Save via the Model
            $contactModel = new ContactRequestModel();
            $contactModel->save($contactRequest);
            
            // 6. Clean old session data
            FormSecurity::cleanOldSessionData();
            
            // 7. Redirect with success message
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

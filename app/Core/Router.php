<?php
class Router {
    private array $routes = [];
    
    /**
     * Ajouter une route GET
     */
    public function get(string $uri, string $controller, string $method): void {
        $this->addRoute('GET', $uri, $controller, $method);
    }
    
    /**
     * Ajouter une route POST
     */
    public function post(string $uri, string $controller, string $method): void {
        $this->addRoute('POST', $uri, $controller, $method);
    }
    
    /**
     * Enregistrer une route
     */
    private function addRoute(string $httpMethod, string $uri, string $controller, string $method): void {
        $this->routes[] = [
            'method' => $httpMethod,
            'uri' => $uri,
            'controller' => $controller,
            'action' => $method
        ];
    }
    
    /**
     * Dispatcher : analyse l'URI et appelle le bon contrôleur
     */
    public function dispatch(): void {
        // Récupérer l'URI demandée
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        
        // Enlever le BASE_PATH (ex: /Vadrouille/public)
        $basePath = $this->getBasePath();
        $uri = str_replace($basePath, '', $requestUri);
        $uri = '/' . trim($uri, '/'); // Normaliser : toujours commencer par /
        
        // Cas spécial : URI vide = racine
        if ($uri === '/') {
            // OK, c'est la racine
        }
        
        // Chercher la route correspondante
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $this->matchRoute($route['uri'], $uri)) {
                // Route trouvée !
                $controllerName = $route['controller'];
                $methodName = $route['action'];
                
                $controller = new $controllerName();
                $controller->$methodName();
                return;
            }
        }
        
        // Aucune route trouvée → 404
        $this->show404();
    }
    
    /**
     * Vérifier si l'URI correspond à la route
     */
    private function matchRoute(string $routeUri, string $requestUri): bool {
        // Pour l'instant, correspondance exacte
        // Plus tard, on pourra ajouter des paramètres dynamiques (/voyage/{id})
        return $routeUri === $requestUri;
    }
    
    /**
     * Obtenir le chemin de base du projet
     */
    private function getBasePath(): string {
        // En local : /Vadrouille/public
        // En prod : vide
        $isLocal = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || 
                    strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);
        
        return $isLocal ? '/Vadrouille/public' : '';
    }
    
    /**
     * Afficher la page 404
     */
    private function show404(): void {
        http_response_code(404);
        
        $view = new View('public/error404', [
            'pageTitle' => 'Page non trouvée - Vadrouille & Bourlingue',
            'pageDescription' => 'La page que vous recherchez n\'existe pas.',
            'schemaOrganization' => SeoHelper::getOrganizationSchema(),
            'breadcrumbs' => SeoHelper::getBreadcrumbs('home')
        ], 'public');
        
        $view->render();
    }
}
<?php

/**
 * Simple Router class to handle HTTP requests and dispatch to controllers
 * This class allows us to define routes in a clean way and centralizes the request handling logic.
 */
class Router
{
    private array $routes = [];

    /**
     * Add a GET route
     */
    public function get(string $uri, string $controller, string $method): void
    {
        $this->addRoute('GET', $uri, $controller, $method);
    }

    /**
     * Add a POST route
     */
    public function post(string $uri, string $controller, string $method): void
    {
        $this->addRoute('POST', $uri, $controller, $method);
    }

    /**
     * Register a route
     */
    private function addRoute(string $httpMethod, string $uri, string $controller, string $method): void
    {
        $this->routes[] = [
            'method' => $httpMethod,
            'uri' => $uri,
            'controller' => $controller,
            'action' => $method
        ];
    }

    /**
     * Dispatcher: analyze the URI and call the appropriate controller
     */
    public function dispatch(): void
    {
        // Get the requested URI
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Remove the BASE_PATH (e.g., /Vadrouille/public)
        $basePath = $this->getBasePath();
        $uri = str_replace($basePath, '', $requestUri);
        $uri = '/' . trim($uri, '/'); // Normalize: always start with /

        // Special case: empty URI or just "/" = root
        if ($uri === '/' || $uri === '') {
            $uri = '/';
        }

        // Search for the matching route
        foreach ($this->routes as $route) {
            $params = [];
            if ($route['method'] === $requestMethod && $this->matchRoute($route['uri'], $uri, $params)) {
                // Route found!
                $controllerName = $route['controller'];
                $methodName = $route['action'];

                // Check if the class exists
                if (!class_exists($controllerName)) {
                    throw new Exception("Controller '$controllerName' not found");
                }

                $controller = new $controllerName();

                // Check if the method exists
                if (!method_exists($controller, $methodName)) {
                    throw new Exception("Méthode '$methodName' introuvable dans $controllerName");
                }

                $controller->$methodName(...$params);
                return;
            }
        }

        // No route found → 404
        $this->show404();
    }

    /**
     * Check if the URI matches the route
     */
    private function matchRoute(string $routeUri, string $requestUri, &$params = []): bool
    {
        // DEBUG
        error_log("Comparing routeUri: $routeUri with requestUri: $requestUri");

        // If no regex in the route, match exact
        if (strpos($routeUri, '(') === false) {
            return $routeUri === $requestUri;
        }

        //Convert the route into a regex properly
        // Replace (\d+) with the number regex, and keep it as a capturing group
        $pattern = str_replace('/', '\/', $routeUri); // Escape the /
        $pattern = str_replace('(\d+)', '(\d+)', $pattern); // Keep (\d+) as is
        $pattern = '/^' . $pattern . '$/';

        error_log("Pattern created: $pattern");

        // Test the regex
        if (preg_match($pattern, $requestUri, $matches)) {
            error_log("MATCH! Params: " . print_r($matches, true));
            // Remove the full match, keep only the captured groups
            array_shift($matches);
            $params = $matches;
            return true;
        }

        error_log("No match");
        return false;
    }

    /**
     * Get the base path of the project
     */
    private function getBasePath(): string
    {
        // Local: /Vadrouille-Et-Bourlingue/public
        // Production: empty
        $isLocal = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
            strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);

        return $isLocal ? '/Vadrouille-Et-Bourlingue/public' : '';
    }

    /**
     * Display the 404 page
     */
    private function show404(): void
    {
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

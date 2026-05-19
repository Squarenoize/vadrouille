<?php

/**
 * View class to render templates with data and layouts
 * This class is responsible for loading the appropriate view file and wrapping it in the correct layout.
 */
class View
{
    private string $template;
    private array $data;
    private string $layout;

    /**
     * @param string $template Path to the template (e.g., 'public/home', 'admin/dashboard')
     * @param array $data Data to pass to the view
     * @param string $layout Layout to use: 'public', 'traveler', or 'admin'
     */
    public function __construct(string $template, array $data = [], string $layout = 'public')
    {
        $this->template = $template;
        $this->data = $data;
        $this->layout = $layout;
    }

    /**
     * Render the view with its layout
     */
    public function render(): void
    {
        // Extract data to make it available in the views
        extract($this->data);

        // Path to the page content
        $contentView = __DIR__ . '/../views/pages/' . $this->template . '.php';

        // Check if the content file exists
        if (!file_exists($contentView)) {
            throw new Exception("La vue '{$this->template}' est introuvable.");
        }

        // Load the matching layout
        $layoutFile = __DIR__ . '/../views/layouts/' . $this->layout . '.php';

        if (!file_exists($layoutFile)) {
            throw new Exception("Le layout '{$this->layout}' est introuvable.");
        }

        // Include the layout (which will include the content via $contentView)
        include $layoutFile;
    }
}

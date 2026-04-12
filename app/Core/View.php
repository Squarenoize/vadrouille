<?php
class View {
    private string $template;
    private array $data;
    private string $layout;

    /**
     * @param string $template Chemin du template (ex: 'public/home', 'admin/dashboard')
     * @param array $data Données à passer à la vue
     * @param string $layout Layout à utiliser : 'public', 'user', ou 'admin'
     */
    public function __construct(string $template, array $data = [], string $layout = 'public') {
        $this->template = $template;
        $this->data = $data;
        $this->layout = $layout;
    }
    
    /**
     * Rendre la vue avec son layout
     */
    public function render(): void {
        // Extraire les données pour les rendre disponibles dans les vues
        extract($this->data);
        
        // Chemin vers le contenu de la page
        $contentView = __DIR__ . '/../views/pages/' . $this->template . '.php';
        
        // Vérifier que le fichier de contenu existe
        if (!file_exists($contentView)) {
            throw new Exception("La vue '{$this->template}' est introuvable.");
        }
        
        // Charger le layout correspondant
        $layoutFile = __DIR__ . '/../views/layouts/' . $this->layout . '.php';
        
        if (!file_exists($layoutFile)) {
            throw new Exception("Le layout '{$this->layout}' est introuvable.");
        }
        
        // Inclure le layout (qui inclura le contenu via $contentView)
        include $layoutFile;
    }
}
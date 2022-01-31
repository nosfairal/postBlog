<?php
namespace Nosfair\Blogpost\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    private $loader;
    protected $twig;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(ROOT.'/blogpost/templates');
        $this->twig = new Environment($this->loader);
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->addGlobal("currentUrl", $_SERVER["REQUEST_URI"]);
    }
    public function render(string $fichier, array $data = [])
    {
        // Récupère les données et les extrait sous forme de variables
        extract($data);
    
        // Crée le chemin et inclut le fichier de vue
        include_once ROOT.'/blogpost/templates/'.$fichier.'.html.twig';
    }
}

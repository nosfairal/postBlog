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
        //$this->twig->addGlobal("currentPage", $_SERVER["REQUEST_URI"]);
    }
    public function render(string $fichier, array $data = [])
    {
        // Get gata and extract into variables
        extract($data);
    
        // Create the way and include the file's view
        include_once ROOT.'/blogpost/templates/'.$fichier.'.html.twig';
    }
}

<?php
namespace Nosfair\Blogpost\Service;
use Nosfair\Blogpost\Controller\MainController;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Main
{   
    public function __construct()
    {   
        $this->loader = new FilesystemLoader(ROOT.'/blogpost/templates');
        $this->twig = new Environment($this->loader);
    }
    public function start()
    {
        //Start the session
        session_start();
        // Get the address
        $uri = isset($_SERVER['REQUEST_URI']);

        // On vérifie si elle n'est pas vide et si elle se termine par un /
        if(!empty($uri) && $uri != '/' && $uri[-1] === '/') {
            // Dans ce cas on enlève le /
            $uri = substr($uri, 0, -1);

            // On envoie une redirection permanente
            http_response_code(301);

            // On redirige vers l'URL dans /
            header('Location: '.$uri);
            
        }
        // On sépare les paramètres et on les met dans le tableau $params
        $params=[];
        if(isset($_GET['p'])) {
            $params = explode('/', $_GET['p']);
        }

        // Si au moins 1 paramètre existe

        if(isset($params[0]) != "") {
            // On sauvegarde le 1er paramètre dans $controller en mettant sa 1ère lettre en majuscule, en ajoutant le namespace des controleurs et en ajoutant "Controller" à la fin
            $controller = '\\Nosfair\\Blogpost\\Controller\\'.ucfirst(array_shift($params)).'Controller';

            // On sauvegarde le 2ème paramètre dans $action si il existe, sinon index
            $action = isset($params[0]) ? array_shift($params) : 'index';

            // Instance of a controller
            $controller = new $controller();

            if(method_exists($controller, $action)) {
                // Si il reste des paramètres, on appelle la méthode en envoyant les paramètres sinon on l'appelle "à vide"
                (isset($params[0])) ? call_user_func_array([$controller,$action], $params) : $controller->$action();    
            }else{
                // Show the 404 page
        
                $this->twig->display('front/404.html.twig');
            }
        }else{
            // If no parameter
            // Instance of default Controller
            $controller = new MainController();

            // Call to the  method index
            $controller->index();
        }


    }
}

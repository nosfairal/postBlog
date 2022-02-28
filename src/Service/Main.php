<?php
namespace Nosfair\Blogpost\Service;
use Nosfair\Blogpost\Controller\MainController;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Nosfair\Blogpost\Service\GlobalConstant;

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
        //instance of GlobalConstant
        $global = new GlobalConstant;

        // Verify if not empty and finish by an  /
        if(!empty($uri) && $uri != '/' && $uri[-1] === '/') {
            // We substract the /
            $uri = substr($uri, 0, -1);

            // Send permanent redirection
            http_response_code(301);

            // redirect the URL in /
            header('Location: '.$uri);
            
        }
        // Explode parameters and put in the array $params.
        $params=[];
        if($global->issetGET('p')) {
            $params = explode('/', $_GET['p']);
        }

        // if at least 1 parameter exists

        if(isset($params[0]) != "") {
            if ($params[0] != "post" && $params[0] != "contact" && $params[0] != "admin" && $params[0] != "user" && $params[0] != "comment"){
                // Show the 404 page
        
                $this->twig->display('front/404.html.twig');
                return;
            }

            // Save the first parameter in $controller putting his first letter in capital, adding the namespace of the controller and "Controller" at the end.
            $controller = '\\Nosfair\\Blogpost\\Controller\\'.ucfirst(array_shift($params)).'Controller';

            // Save the second parameter in $action if exists, else index.
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

            // // Call to the  method index
            $controller->index();
            
        }


    }
}

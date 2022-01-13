<?php
namespace Nosfair\Blogpost\Controller;

class MainController extends Controller
{
    public function index()
    {
        $this->twig->display('front/index.html.twig');
    }
}

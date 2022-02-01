<?php
namespace Nosfair\Blogpost\Controller;
use Nosfair\Blogpost\Entity\Post;

class MainController extends Controller
{
    /***
     * @Route("/", name="home")
     */
    public function index()
    {   
        $post = new Post;
        $posts = $post->findAll();
        $currentPage = "home";
        $this->twig->display('front/index.html.twig', compact('posts','currentPage'));
    }
}

<?php
namespace Nosfair\Blogpost\Controller;
use Nosfair\Blogpost\Entity\Post;

class MainController extends Controller
{
    public function index()
    {   
        $post = new Post;
        $posts = $post->findAll();
        $this->twig->display('front/index.html.twig', compact('posts'));
    }
}

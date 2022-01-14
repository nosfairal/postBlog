<?php
namespace Nosfair\Blogpost\Controller;
use Nosfair\Blogpost\Controller\Controller;
use Nosfair\Blogpost\Entity\Post;


class PostController extends Controller
{
    /**
     * list of posts in the Db
     *
     * @return void
     */
    public function index()
    {
        $post = new Post;
        $posts = $post->findAll();
        var_dump($posts);
        $this->twig->display('back/PostIndex.html.twig', compact('posts'));
    }
    /**
     * Method to show a single post
     *
     * @param  int $id
     * @return void
     */
    public function show(int $id)
    {
        // On instancie le modèle
        $model = new Post;

        // On récupère les données
        $post = $model->findBy(['postId' =>$id]);
        var_dump($post);
        $this->render('back/post', compact('post'));
    }
    public function add()
    {
        $post = new Post;
        $this->twig->display('front/addPost.html.twig', compact('post'));
    }
}

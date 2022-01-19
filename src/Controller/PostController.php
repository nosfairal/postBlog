<?php
namespace Nosfair\Blogpost\Controller;
use Nosfair\Blogpost\Controller\Controller;
use Nosfair\Blogpost\Entity\Post;
use Nosfair\Blogpost\Service\Form;


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
        //Intance of Model
        $model = new Post;

        // On récupère les données
        $post = $model->findBy(['postId' =>$id]);
        var_dump($post);
        $this->render('back/post', compact('post'));
    }

    /**
     * Method to add an Post
     */
    public function add()
    {
        //Verify User connexion
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) true :
            $addPostForm = new Form;
            $addPostForm->startForm()
                ->addLabelFor('title', 'Titre du post :')
                ->addInput('text', 'title', ['class' => 'form-control'])
                ->addLabelFor('slug', 'Slug du post :')
                ->addInput('text', 'slug', ['class' => 'form-control'])
                ->addLabelFor('intro', 'Introduction du post :')
                ->addInput('text', 'intro', ['class' => 'form-control'])
                ->addLabelFor('content', 'Votre post')
                ->addTextarea('content', '', ['class' => 'form-control'])
                ->addButton('Valider', ['type' => 'submit', 'class' => 'btn btn-primary'])
                ->endForm()
                ;

        
        /*else{
            $_SESSION['erreur'] ="Vous devez être connecté pour accéder à cette page";
            header('Location: https://localhost/blogpost/index.php?p=user/login');
        }*/
            //$post = new Post;
        //$this->twig->display('back/newPost.html.twig', compact('post'));
        $this->twig->display('back/newPost.html.twig', ['addPostForm' => $addPostForm->create()]);
    }
}

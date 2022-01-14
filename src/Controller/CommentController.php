<?php
namespace Nosfair\Blogpost\Controller;
use Nosfair\Blogpost\Controller\Controller;
use Nosfair\Blogpost\Entity\Comment;


class CommentController extends Controller
{
    /**
     * list of comments in the Db
     *
     * @return void
     */
    public function index()
    {
        $comment = new Comment;
        $comments = $comment->findAll();
        var_dump($comments);
        $this->twig->display('back/commentIndex.html.twig', compact('comments'));
    }
    /**
     * Method to show a single comment
     *
     * @param  int $id
     * @return void
     */
    public function show(int $id)
    {
        // On instancie le modèle
        $model = new Comment;

        // On récupère les données
        $comment = $model->findBy(['CommentId' =>$id]);
        var_dump($comment);
        $this->render('back/comment', compact('comment'));
    }
    public function add()
    {
        $comment = new Comment;
        $this->twig->display('front/addcomment.html.twig', compact('comment'));
    }
}

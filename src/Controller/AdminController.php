<?php 
namespace Nosfair\Blogpost\Controller;

use Nosfair\Blogpost\Entity\Comment;
use Nosfair\Blogpost\Entity\User;
use Nosfair\Blogpost\Entity\Post;
use Nosfair\Blogpost\Repository\CommentRepository;

class AdminController extends Controller
{
    public function index()
    {
        \var_dump($_SESSION);
        //Verify Admin status
        if($this->isAdmin()){
            $this->twig->display('back/adminIndex.html.twig');
        }
    }

    /**
     * Method to manage users
     * @return void
     */
    public function users()
    {
        //Verify Admin status
        if($this->isAdmin()){
            $user = new User;
            $users = $user->findAll();
            $this->twig->display('back/adminUsers.html.twig', compact('users'));
        }
    }

    /**
     * Method to manage posts
     * @return void
     */
    public function posts()
    {
        //Verify Admin status
        if($this->isAdmin()){
            $post = new Post;
            $posts = $post->findAll();
            $this->twig->display('back/adminPosts.html.twig', compact('posts'));
        }
    }

    /**
     * Method to delete a post
     * @param int $id
     * return void
     */

     public function deletePost(int $id){
        //Verify Admin status
        if($this->isAdmin()){
            $post = new Post;
            $post->delete($id);
            header('Location: '.$_SERVER['HTTP_REFERER']);
        }
     }


    /**
     * Method to manage comments
     * @return void
     */
    public function comments()
    {
        //Verify Admin status
        if($this->isAdmin()){
            $comment= new Comment;
            $comments = $comment->findAll();
            $this->twig->display('back/adminComments.html.twig', compact('comments'));
        }
    }

    /**
     * Method to delete a comment
     * @param int $id
     * return void
     */

    public function deleteComment(int $id){
        //Verify Admin status
        if($this->isAdmin()){
            $comment= new Comment;
            $comment->delete($id);
            header('Location: '.$_SERVER['HTTP_REFERER']);
        }
     }

    /**
     * Method to approuve or reject a comment
     * @param int $id
     * @return void
     */

     public function approuveComment(int $id)
     {
         //Verify Admin status
         if($this->isAdmin()){
            $comment= new Comment;
            $commentRepository = new CommentRepository();
            $commentArray = $commentRepository->findby(['CommentId' => $id]);
                $commentApprouved =$comment->hydrate($commentArray);
                \var_dump($commentApprouved);
                $commentApprouved->setCommentStatus("approuved");
                $commentApprouved->update($id);
                \header('Location: https://localhost/blogpost/index.php?p=admin/comments/');
         }
     }
    
    /**
     * Method to verify Admin status
     */

     private function isAdmin()
     {
         if(isset($_SESSION['user']) && $_SESSION['user']['userRole'] == 'admin'){
            return true;
         }else{
             $_SESSION['erreur'] ="Vous n'avez pas les droits pour accéder à cette page";
             header('location: https://localhost/blogpost/index.php');
         }
     }
}
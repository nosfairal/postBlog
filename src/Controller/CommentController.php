<?php
namespace Nosfair\Blogpost\Controller;
use Nosfair\Blogpost\Controller\Controller;
use Nosfair\Blogpost\Entity\Comment;
use Nosfair\Blogpost\Service\Form;
use Nosfair\Blogpost\Service\Session;


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
        $this->twig->display('back/commentIndex.html.twig', compact('comments'));
    }
    /**
     * Method to show a single comment
     *
     * @param  int $commentId
     * @return void
     */
    public function show(int $commentId)
    {
        // On instancie le modèle
        $model = new Comment;

        // On récupère les données
        $comment = $model->findBy(['CommentId' =>$commentId]);
        if (!$comment) {
            http_response_code(404);
            Session::redirect("./index.php?p=comment/index");
        }
        $this->twig->display('back/comment.html.twig', compact('comment'));
    }
    public function add()
    {
        $comment = new Comment;
        $this->twig->display('front/addcomment.html.twig', compact('comment'));
    }

    /**
     * Method to update a comment
     * @param int $id
     * return void
     */
        
    public function update(int $commentId)
    {
       // Verify User's session
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['userId'])){
                
            // Instance of comment
            $comment= new Comment;

            // Search for the comment by id
            $comment = $comment->find($commentId);

            // If comment doesn't exist
            if (!$comment) {
                http_response_code(404);
                Session::redirect("./index.php?p=comment/index");
            }
            //Verify form compliance
            if(Form::validate($_POST, ['content'])){
                $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

                //Instance of comment
                $modifiedcomment = new Comment;

                //Set the data
                $modifiedcomment->setcommentId($comment->commentId)                    
                    ->setContent($content)                    
                ;
                //Record of the modified comment
                $modifiedcomment->update($comment->commentId);


            //Redirection + message
            Session::put("message", "Votre commentaire a été modifié avec succès");
            Session::redirect("./index.php?p=comment/index");
            }else{
                //form dosen't verify compliance
                $_SESSION['error'] = !empty($_POST) ? "le formulaire est incomplet" : '';
                $content = isset($_POST['content']) ? filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING) : '';
            }   
            //Display the form
            $updateCommentForm = new Form;
            $updateCommentForm->startForm()
                ->addLabelFor('content', 'Votre comment')
                ->addTextarea('content', $comment->content, [
                    'id' => 'content',
                    'class' => 'form-control'])
                ->addButton('Valider', ['type' => 'submit', 'class' => 'btn btn-primary'])
                ->endForm()
                ;
            $this->twig->display('back/updateComment.html.twig', ['updateCommentForm' => $updateCommentForm->create()]);
            return;
        }
            Session::put("erreur", "Vous devez vous connecter pour ajouter une annonce");
            Session::redirect("./index.php?p=user/login'");
    }
}

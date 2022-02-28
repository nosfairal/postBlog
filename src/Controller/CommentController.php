<?php
namespace Nosfair\Blogpost\Controller;
use Nosfair\Blogpost\Controller\Controller;
use Nosfair\Blogpost\Entity\Comment;
use Nosfair\Blogpost\Service\Form;
use Nosfair\Blogpost\Service\GlobalConstant;
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
        //Instance of Session
        $session = new Session;
        // instance of Model
        $model = new Comment;

        //Get the data
        $comment = $model->findBy(['CommentId' =>$commentId]);
        if (!$comment) {
            http_response_code(404);
            $session->redirect("./index.php?p=comment/index");
        }
        $this->twig->display('back/comment.html.twig', compact('comment'));
    }

    /*
    public function add()
    {
        $comment = new Comment;
        $this->twig->display('front/addcomment.html.twig', compact('comment'));
    }*/

    /**
     * Method to update a comment
     * @param int $id
     * return void
     */
        
    public function update(int $commentId)
    {
        //Instance of Session
        $session = new Session;
        //Instance of Form
        $form = new Form;
        //Instance of GlobalConstant
        $global = new GlobalConstant;
        $arrayPost = new GlobalConstant;
        // Verify User's session
        if($session->issetSession('user') && !empty($session->get("user")['userId'])){
                
            // Instance of comment
            $comment= new Comment;

            // Search for the comment by id
            $comment = $comment->find($commentId);

            // If comment doesn't exist
            if (!$comment) {
                http_response_code(404);
                $session->redirect("./index.php?p=comment/index");
            }
            //Verify form compliance
            if($form->validate($arrayPost->collectInput(), ['content'])){
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
            $session->put("message", "Votre commentaire a été modifié avec succès");
            $session->redirect("./index.php?p=comment/index");
            }else{
                //form dosen't verify compliance
                $_SESSION['error'] = $global->notEmptyPost() ? "le formulaire est incomplet" : '';
                $content = $global->issetPost('content') ? filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING) : '';
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
            $session->put("erreur", "Vous devez vous connecter pour ajouter un commentaire");
            $session->redirect("./index.php?p=user/login");
    }
}

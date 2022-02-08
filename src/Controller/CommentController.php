<?php
namespace Nosfair\Blogpost\Controller;
use Nosfair\Blogpost\Controller\Controller;
use Nosfair\Blogpost\Entity\Comment;
use Nosfair\Blogpost\Service\Form;


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
        
    public function update(int $id)
    {
       // Verify User's session
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['userId'])){
                
            // Instance of comment
            $comment= new Comment;

            // Search for the comment by id
            $comment = $comment->find($id);

            // If comment doesn't exist
            if (!$comment) {
                http_response_code(404);
                header('Location: /');
            }
            //Verify form compliance
            if(Form::validate($_POST, ['content'])){
                $content = strip_tags($_POST['content']);

                //Instance of comment
                $modifiedcomment = new Comment;

                //Set the data
                $modifiedcomment->setcommentId($comment->commentId)                    
                    ->setContent($content)                    
                ;
                //Record of the modified comment
                $modifiedcomment->update($comment->commentId);


            //Redirection + message
            $_SESSION['message'] = "Votre commentaire a été modifié avec succès";
            header('Location: https://localhost/blogpost/index.php?p=comment/index');
            }else{
                //form dosen't verify compliance
                $_SESSION['error'] = !empty($_POST) ? "le formulaire est incomplet" : '';
                $content = isset($_POST['content']) ?strip_tags($_POST['content']) : '';
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

        }else{
            $_SESSION['erreur'] = "Vous devez vous connecter pour ajouter une annonce";
            header('Location: https://localhost/blogpost/index.php?p=user/login');
        }

    }
}

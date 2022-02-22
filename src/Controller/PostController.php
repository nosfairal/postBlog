<?php
namespace Nosfair\Blogpost\Controller;

use DateTime;
use Nosfair\Blogpost\Controller\Controller;
use Nosfair\Blogpost\Entity\Post;
use Nosfair\Blogpost\Entity\User;
use Nosfair\Blogpost\Entity\Comment;
use Nosfair\Blogpost\Repository\CommentRepository;
use Nosfair\Blogpost\Service\Form;
use Nosfair\Blogpost\Repository\UserRepository;
use Nosfair\Blogpost\Service\Session;
use Nosfair\Blogpost\Service\Db;
use Nosfair\Blogpost\Service\GlobalConstant;

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
        $currentPage = "postIndex";
        $this->twig->display('front/postIndex.html.twig', compact('posts','currentPage'));
    }
    /**
     * Method to show a single post and his comment(s)
     *
     * @param  int $postId
     * @return void
     */
    public function show(int $postId)
    {   
        //Instance of Session
        $session = new Session;
        $sessionStopMessage = $session->forget('message');
        //Instance of Form
        $form = new Form;
        //Instance of GlobalConstant
        $global = new GlobalConstant;
        $arrayPost = new GlobalConstant;
        //Verify if User is connected
        if(!$session->issetSession('user')){
            $session->put("message", "Vous devez être inscrit et connecté pour pouvoir commenter");
            //header('Location: ./index.php?p=user/register');
        }
            //Instance of new Post
            $model = new Post;
            $post = $model->findBy(['postId' =>$postId]);
            if (!$post) {
                http_response_code(404);
                $session->redirect("./index.php?p=post/index");
            }
            //Instance of new User
            $user = new User;
            //Get the publicName of the author of the post
            $userPublicName = $user->getPostAuthorPublicName($postId);
            $userPublicName= $userPublicName->publicName;
            //var_dump($userPublicName);
            //Verify form compliance
            if($form->validate($arrayPost->collectInput(), ['content']) && $session->issetSession('user')){

                $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

                //Instance of Post
                $comment = new Comment;

                //Set the data
                $comment->setAuthor($session->get("user")['userId'])                    
                    ->setContent($content)
                    ->setPost($postId)                    
                ;
                //Record
                $comment->create();


                //Redirection + message
                $session->put("message", "Votre commentaire a été enregistré avec succès, sa validation sera traitée dans les plus brefs délais");
                $session->redirect("./index.php?p=post/index");
            }else{
                //form doesn't match
                $session->put("error", $global->notEmptyPost()) ? "le formulaire est incomplet" : '';
                $content = $global->issetPost('content') ? filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING) : '';
            }
        
    
        // Get datas
        $post = $model->findBy(['postId' =>$postId]);
        //var_dump($post);
        //Instance of Form
        $addCommentForm = new Form;
        //Construction of the form
        $addCommentForm->startForm()->addLabelFor('content', 'Partager un commentaire :')
            ->addTextarea('content', '', ['class' => 'form-control'])
            ->addButton('Valider', ['type' => 'submit', 'class' => 'btn btn-primary mb-2'])
            ->endForm()
            ;
        
        // Instance of Comment
        $commentRepository = new CommentRepository();
        $commentOfPost = new Comment;
        $commentOfPost = $commentRepository->findBy(['post' =>$postId]);
        //Get the publicName of the author of the post
        $commentator =new User;
        $commentatorPublicNameList = [];
        foreach ($commentOfPost as $com) {
            $commentId = $com->commentId;
            $commentatorPublicName = $commentator->getCommentAuthorPublicName($commentId);
            array_push($commentatorPublicNameList, $commentatorPublicName->{'publicName'});
        };

        //Get comments approuved
        $commentStatus = new Comment;
        
        $commentStatus= $commentRepository->findBy(['commentStatus' => 1]);   
        $this->twig->display('front/post.html.twig', ['sessionStopMessage' => $sessionStopMessage, 'commentators' => $commentatorPublicNameList,'author' => $userPublicName,'post' => $post,'commentOfPost' => $commentOfPost, 'commentStatus' => $commentStatus, 'addCommentForm' => $addCommentForm->create()]);
    }

    /**
     * Method to add a Post
     */
    public function add()
    {
        //instance of Session
        $session = new Session;
        //instance of Globalconstant
        $global = new GlobalConstant;
        //instance of Form
        $form = new Form;
        $arrayPost = new GlobalConstant;
        //Verify User connexion
        if($session->issetSession('user') && !empty($session->get("user")['userId'])){
            
            //Verify form compliance
            if($form->validate($arrayPost->collectInput(), ['title', 'slug', 'intro', 'content'])){
                $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
                $slug= filter_input(INPUT_POST, 'slug', FILTER_SANITIZE_STRING);
                $intro = filter_input(INPUT_POST, 'intro', FILTER_SANITIZE_STRING);
                $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

                //Instance of Post
                $post = new Post;

                //Set the data
                $post->setAuthor($session->get("user")['userId'])                    
                    ->setTitle($title)
                    ->setSlug($slug)
                    ->setIntro($intro)
                    ->setContent($content)                    
                ;
                //Record
                $post->create();


                //Redirection + message
                $session->put("message", "Votre post a été enregistré avec succès");
                $session->redirect("./index.php?p=post/index");
            }else{
                //form doesn't match
                $_SESSION['error'] = $global->notEmptyPost() ? "le formulaire est incomplet" : '';
                $title = $global->issetPost('title') ? filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING) : '';
                $slug= $global->issetPost('slug') ? filter_input(INPUT_POST, 'slug', FILTER_SANITIZE_STRING) : '';
                $intro = $global->issetPost('intro') ? filter_input(INPUT_POST, 'intro', FILTER_SANITIZE_STRING): '';
                $content = $global->issetPost('content') ? filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING) : '';
            }
            
            //instance of Form
            $addPostForm = new Form;
            //Construction of the form
            $addPostForm->startForm()
                ->addLabelFor('title', 'Titre du post :')
                ->addInput('text', 'title', [
                    'class' => 'form-control',
                    'value' => $title])
                ->addLabelFor('slug', 'Slug du post :')
                ->addInput('text', 'slug', [
                    'class' => 'form-control',
                    'value' => $slug])
                ->addLabelFor('intro', 'Introduction du post :')
                ->addInput('text', 'intro', [
                    'class' => 'form-control',
                    'value' => $intro])
                ->addLabelFor('content', 'Votre post')
                ->addTextarea('content', $content, ['class' => 'form-control',
                    'rows' => '10'])
                ->addButton('Valider', ['type' => 'submit', 'class' => 'btn btn-primary'])
                ->endForm()
                ;
                $this->twig->display('back/newPost.html.twig', ['addPostForm' => $addPostForm->create()]);
        
        }else{
            $session->put("erreur", "Vous devez être connecté pour accéder à cette page");
            $session->redirect("./index.php?p=user/login");
        }
        
        
    }
    /**
     * Method to update a Post
     * @param int $id
     * retrun void
     */
        
        public function update(int $postId)
        {   
            //instance of Session
            $session = new Session;
            //Instance of Form
            $form = new Form;
            //instance of Globalconstant
            $global = new GlobalConstant;
            $arrayPost = new GlobalConstant;
           // Verify User's session
            if($session->issetSession('user') && !empty($_SESSION['user']['userId'])){
                
                
                // Instance of Post
                $post= new Post;

                // Search for the post by id
                $post = $post->find($postId);

                // If Post doesn't exist
                if (!$post) {
                    http_response_code(404);
                    
                    $session->redirect("./index.php?p=post/index");
                }
                //Verify form compliance
                if($form->validate($arrayPost->collectInput(), ['title', 'slug', 'intro', 'content'])){
                    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
                    $slug= filter_input(INPUT_POST, 'slug', FILTER_SANITIZE_STRING);
                    $intro = filter_input(INPUT_POST, 'intro', FILTER_SANITIZE_STRING);
                    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

                    //Instance of Post
                    $modifiedPost = new Post;
                    //Instance of Datetime
                    $postDate = new DateTime('now');
                    $update = $postDate->format('Y-m-d H:i:s');

                    //Set the data
                    $modifiedPost->setPostId($post->postId)                    
                        ->setTitle($title)
                        ->setSlug($slug)
                        ->setIntro($intro)
                        ->setContent($content)
                        ->setLastUpdate($update)                    
                    ;
                    //Record
                    $modifiedPost->update($post->postId);


                //Redirection + message
                $session->put("message", "Votre post a été modifié avec succès");
                $session->redirect("./index.php?p=post/index");
                }else{
                    //form doesn't verify validation
                    $_SESSION['error'] = $global->notEmptyPost() ? "le formulaire est incomplet" : '';
                    $title = $global->issetPost('title') ? filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING) : '';
                    $slug= $global->issetPost('slug') ? filter_input(INPUT_POST, 'slug', FILTER_SANITIZE_STRING) : '';
                    $intro = $global->issetPost('intro') ? filter_input(INPUT_POST, 'intro', FILTER_SANITIZE_STRING): '';
                    $content = $global->issetPost('content') ? filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING) : '';
                }   
                //Display the form
                $updatePostForm = new Form;
                $updatePostForm->startForm()
                    ->addLabelFor('title', 'Titre du post :')
                    ->addInput('text', 'title', [
                        'id' => 'title',
                        'class' => 'form-control',
                        'value' => $post->title])
                    ->addLabelFor('slug', 'Slug du post :')
                    ->addInput('text', 'slug', [
                        'id' => 'slug',
                        'class' => 'form-control',
                        'value' => $post->slug])
                    ->addLabelFor('intro', 'Introduction du post :')
                    ->addInput('text', 'intro', [
                        'id' => 'intro',
                        'class' => 'form-control',
                        'value' => $post->intro])
                    ->addLabelFor('content', 'Votre post')
                    ->addTextarea('content', $post->content, [
                        'id' => 'content',
                        'class' => 'form-control',
                        'rows' => '10'])
                    ->addButton('Valider', ['type' => 'submit', 'class' => 'btn btn-primary'])
                    ->endForm()
                    ;
                    $this->twig->display('back/updatePost.html.twig', ['updatePostForm' => $updatePostForm->create()]);   

            }else{
                $session->put("erreur", "Vous devez vous connecter pour ajouter une annonce");
                $session->redirect("./index.php?p=user/login");
            }
 
        }
}

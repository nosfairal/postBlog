<?php
namespace Nosfair\Blogpost\Controller;
use Nosfair\Blogpost\Controller\Controller;
use Nosfair\Blogpost\Entity\Post;
use Nosfair\Blogpost\Entity\User;
use Nosfair\Blogpost\Entity\Comment;
use Nosfair\Blogpost\Repository\CommentRepository;
use Nosfair\Blogpost\Service\Form;
use Nosfair\Blogpost\Service\Session;
use Nosfair\Blogpost\Service\Db;


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
     * Method to show a single post and his comments(s)
     *
     * @param  int $postId
     * @return void
     */
    public function show(int $postId)
    {   
        
        //Verify if User is connected
        if(!isset($_SESSION['user'])){
            Session::put("message", "Vous devez être inscrit et connecté pour pouvoir poster");
            //header('Location: ./index.php?p=user/register');
        }
            //Instance of new Post
            $model = new Post;
            $post = $model->findBy(['postId' =>$postId]);
            //Instance of new User
            $user = new User;
            //Get the publicName of the author of the post
            $userPublicName = $user->getPostAuthorPublicName($postId);
            $userPublicName= $userPublicName->publicName;
            //var_dump($userPublicName);
            //Verify form compliance
            if(Form::validate($_POST, ['content'])){

                $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

                //Instance of Post
                $comment = new Comment;

                //Set the data
                $comment->setAuthor($_SESSION['user']['userId'])                    
                    ->setContent($content)
                    ->setPost($postId)                    
                ;
                //Record
                $comment->create();


                //Redirection + message
                Session::put("message", "Votre commentaire a été enregistré avec succès");
                header('Location: https://localhost/blogpost/index.php?p=post/index');
            }else{
                //form doesn't match
                Session::put("error", !empty($_POST)) ? "le formulaire est incomplet" : '';
                $content = isset($_POST['content']) ? filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING) : '';
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
        //\var_dump($commentOfPost);
        
        //var_dump($postActual,'******************', $commentOfPost);
        $commentStatus = new Comment;
        //Get comments approuved
        $commentStatus= $commentRepository->findBy(['commentStatus' => 'approuved']);   

        $this->twig->display('front/post.html.twig', ['author' => $userPublicName,'post' => $post,'commentOfPost' => $commentOfPost, 'commentStatus' => $commentStatus, 'addCommentForm' => $addCommentForm->create()]);
        //\var_dump($post);
        
       
    }

    /**
     * Method to add a Post
     */
    public function add()
    {
        //Verify User connexion
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['userId'])){
            //Verify form compliance
            if(Form::validate($_POST, ['title', 'slug', 'intro', 'content'])){
                $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
                $slug= filter_input(INPUT_POST, 'slug', FILTER_SANITIZE_STRING);
                $intro = filter_input(INPUT_POST, 'intro', FILTER_SANITIZE_STRING);
                $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

                //Instance of Post
                $post = new Post;

                //Set the data
                $post->setAuthor($_SESSION['user']['userId'])                    
                    ->setTitle($title)
                    ->setSlug($slug)
                    ->setIntro($intro)
                    ->setContent($content)                    
                ;
                var_dump($post);
                //Record
                $post->create();


                //Redirection + message
                Session::put("message", "Votre post a été enregistré avec succès");
                header('Location: https://localhost/blogpost/index.php?p=post/index');
            }else{
                //form doesn't match
                $_SESSION['error'] = !empty($_POST) ? "le formulaire est incomplet" : '';
                $title = isset($_POST['title']) ? filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING) : '';
                $slug= isset($_POST['slug']) ? filter_input(INPUT_POST, 'slug', FILTER_SANITIZE_STRING) : '';
                $intro = isset($_POST['intro']) ? filter_input(INPUT_POST, 'intro', FILTER_SANITIZE_STRING): '';
                $content = isset($_POST['content']) ? filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING) : '';
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
                ->addTextarea('content', $content, ['class' => 'form-control'])
                ->addButton('Valider', ['type' => 'submit', 'class' => 'btn btn-primary'])
                ->endForm()
                ;
                $this->twig->display('back/newPost.html.twig', ['addPostForm' => $addPostForm->create()]);
        
        }else{
            Session::put("erreur", "Vous devez être connecté pour accéder à cette page");
            header('Location: https://localhost/blogpost/index.php?p=user/login');
        }
        
        
    }
    /**
     * Method to update a Post
     * @param int $id
     * retrun void
     */
        
        public function update(int $postId)
        {
           // Verify User's session
            if(isset($_SESSION['user']) && !empty($_SESSION['user']['userId'])){
                    
                // Instance of Post
                $post= new Post;

                // Search for the post by id
                $post = $post->find($postId);

                // If Post doesn't exist
                if (!$post) {
                    http_response_code(404);
                    header('Location: /');
                }
                //Verify form compliance
                if(Form::validate($_POST, ['title', 'slug', 'intro', 'content'])){
                    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
                    $slug= filter_input(INPUT_POST, 'slug', FILTER_SANITIZE_STRING);
                    $intro = filter_input(INPUT_POST, 'intro', FILTER_SANITIZE_STRING);
                    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

                    //Instance of Post
                    $modifiedPost = new Post;

                    //Set the data
                    $modifiedPost->setPostId($post->postId)                    
                        ->setTitle($title)
                        ->setSlug($slug)
                        ->setIntro($intro)
                        ->setContent($content)                    
                    ;
                    //var_dump($modifiedPost);
                    //Record
                    $modifiedPost->update($post->postId);


                //Redirection + message
                Session::put("message", "Votre post a été modifié avec succès");
                header('Location: https://localhost/blogpost/index.php?p=post/index');
                }else{
                    //form dosen't verify validation
                    $_SESSION['error'] = !empty($_POST) ? "le formulaire est incomplet" : '';
                    $title = isset($_POST['title']) ? strip_tags($_POST['title']) : '';
                    $slug= isset($_POST['slug']) ? strip_tags($_POST['slug']) : '';
                    $intro = isset($_POST['intro']) ?strip_tags($_POST['intro']): '';
                    $content = isset($_POST['content']) ?strip_tags($_POST['content']) : '';
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
                        'class' => 'form-control'])
                    ->addButton('Valider', ['type' => 'submit', 'class' => 'btn btn-primary'])
                    ->endForm()
                    ;
                    $this->twig->display('back/updatePost.html.twig', ['updatePostForm' => $updatePostForm->create()]);   

            }else{
                Session::put("erreur", "Vous devez vous connecter pour ajouter une annonce");
                header('Location: https://localhost/blogpost/index.php?p=user/login');
            }
 
        }
}

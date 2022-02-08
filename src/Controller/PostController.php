<?php
namespace Nosfair\Blogpost\Controller;
use Nosfair\Blogpost\Controller\Controller;
use Nosfair\Blogpost\Entity\Post;
use Nosfair\Blogpost\Entity\User;
use Nosfair\Blogpost\Entity\Comment;
use Nosfair\Blogpost\Repository\CommentRepository;
use Nosfair\Blogpost\Service\Form;
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
     * @param  int $id
     * @return void
     */
    public function show(int $id)
    {   
        
        //Verify if User is connected
        if(!isset($_SESSION['user'])){
            $_SESSION['message'] = "Vous devez être inscrit et connecté pour pouvoir poster";
            //header('Location: ./index.php?p=user/register');
        }
            //Instance of new Post
            $model = new Post;
            $post = $model->findBy(['postId' =>$id]);
            //Instance of new User
            $user = new User;
            //Get the publicName of the author of the post
            $userPublicName = $user->getPostAuthorPublicName($id);
            $userPublicName= $userPublicName->publicName;
            //var_dump($userPublicName);
            //Verify form compliance
            if(Form::validate($_POST, ['content'])){

                $content = strip_tags($_POST['content']);

                //Instance of Post
                $comment = new Comment;

                //Set the data
                $comment->setAuthor($_SESSION['user']['userId'])                    
                    ->setContent($content)
                    ->setPost($id)                    
                ;
                //Record
                $comment->create();


                //Redirection + message
                $_SESSION['message'] = "Votre commentaire a été enregistré avec succès";
                header('Location: https://localhost/blogpost/index.php?p=post/index');
                exit;
            }else{
                //form doesn't match
                $_SESSION['error'] = !empty($_POST) ? "le formulaire est incomplet" : '';
                $content = isset($_POST['content']) ?strip_tags($_POST['content']) : '';
            }
        
    
        // Get datas
        $post = $model->findBy(['postId' =>$id]);
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
        $commentOfPost = $commentRepository->findBy(['post' =>$id]);
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
                $title = strip_tags($_POST['title']);
                $slug= strip_tags($_POST['slug']);
                $intro = strip_tags($_POST['intro']);
                $content = strip_tags($_POST['content']);

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
                $_SESSION['message'] = "Votre post a été enregistré avec succès";
                header('Location: https://localhost/blogpost/index.php?p=post/index');
                exit;
            }else{
                //form doesn't match
                $_SESSION['error'] = !empty($_POST) ? "le formulaire est incomplet" : '';
                $title = isset($_POST['title']) ? strip_tags($_POST['title']) : '';
                $slug= isset($_POST['slug']) ? strip_tags($_POST['slug']) : '';
                $intro = isset($_POST['intro']) ?strip_tags($_POST['intro']): '';
                $content = isset($_POST['content']) ?strip_tags($_POST['content']) : '';
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
            $_SESSION['erreur'] ="Vous devez être connecté pour accéder à cette page";
            header('Location: https://localhost/blogpost/index.php?p=user/login');
        }
        
        
    }
    /**
     * Method to update a Post
     * @param int $id
     * retrun void
     */
        
        public function update(int $id)
        {
           // Verify User's session
            if(isset($_SESSION['user']) && !empty($_SESSION['user']['userId'])){
                    
                // Instance of Post
                $post= new Post;

                // Search for the post by id
                $post = $post->find($id);

                // If Post doesn't exist
                if (!$post) {
                    http_response_code(404);
                    header('Location: /');
                    exit;
                }
                //Verify form compliance
                if(Form::validate($_POST, ['title', 'slug', 'intro', 'content'])){
                    $title = strip_tags($_POST['title']);
                    $slug= strip_tags($_POST['slug']);
                    $intro = strip_tags($_POST['intro']);
                    $content = strip_tags($_POST['content']);

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
                $_SESSION['message'] = "Votre post a été modifié avec succès";
                header('Location: https://localhost/blogpost/index.php?p=post/index');
                exit;
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
                $_SESSION['erreur'] = "Vous devez vous connecter pour ajouter une annonce";
                header('Location: https://localhost/blogpost/index.php?p=user/login');
                exit;
            }
 
        }
}

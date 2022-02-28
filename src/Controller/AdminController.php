<?php 
namespace Nosfair\Blogpost\Controller;

use Nosfair\Blogpost\Entity\Comment;
use Nosfair\Blogpost\Entity\User;
use Nosfair\Blogpost\Entity\Post;
use Nosfair\Blogpost\Service\Session;
use Nosfair\Blogpost\Repository\CommentRepository;
use Nosfair\Blogpost\Repository\UserRepository;
use Nosfair\Blogpost\Service\Form;
use Nosfair\Blogpost\Service\GlobalConstant;
use DateTime;

class AdminController extends Controller
{
    public function index()
    {
        $currentPage="admin";
        //Verify Admin status
        if($this->isAdmin()){
            $this->twig->display('back/adminIndex.html.twig', compact('currentPage'));
        }
    }

    /**
     * Method to manage users
     * @return void
     */
    public function users()
    {
        $session =new Session;
        //Verify Admin status
        if($this->isAdmin() && $session->get('user')['userId'] == 20){
            $currentPage="adminUsers";
            $user = new User;
            $users = $user->findAll();
            $this->twig->display('back/adminUsers.html.twig', compact('users','currentPage'));
            return;
        }
        $session->put("erreur","Vous n'avez pas les droits pour accéder à cette page");
        $this->twig->display('front/404.html.twig');
    }

    /**
     * Method to delete a user
     * @param int $userId
     * return void
     */

    public function deleteUser(int $userId){
        //Instance of Session
        $session = new Session;
        //Verify Admin status
        if($this->isAdmin()){
            $user = new User;
            $user->delete($userId);
            $session->redirect("./index.php?p=admin/users");
        }
     }
    
    /**
     * Method to approve a user
     * @param int $userId
     * @return void
     */

    public function approveUser(int $userId)
    {
        //Instance of Session
        $session = new Session;
        //Verify Admin status
        if($this->isAdmin()){
            $user = new User;
            $userRepository = new userRepository();
            $userArray = $userRepository->findBy(['userId' => $userId]);
            $userApproved = $user->hydrate($userArray);
    
            $userApproved->setUserStatus("approved")
                        ->setUserRole("member");

            $userApproved->update($userId);

            $session->redirect("./index.php?p=admin/users");
        }
    }

    /**
     * Method to upgrade a user
     * @param int $userId
     * @return void
     */

    public function upgradeUser(int $userId)
    {
        //Instance of Session
        $session = new Session;
        //Verify Admin status
        if($this->isAdmin()){
            $user = new User;
            $userRepository = new userRepository();
            $userArray = $userRepository->findBy(['userId' => $userId]);
            $userUpgraded = $user->hydrate($userArray);

    
            $userUpgraded->setUserRole("moderator");

            $userUpgraded->update($userId);

            $session->redirect("./index.php?p=admin/users");

        }
    }

    /**
     * Method to update a user
     * @param int $userId
     * return void
     */
    public function updateUser(int $userId)
    {
        //Instance of Session
        $session = new Session;
        //Instance of Form
        $form = new Form;
        //Instance of Form
        $global = new GlobalConstant;
        $arrayPost = new GlobalConstant;
        // Verify if User is admin
        if($this->isAdmin()){
                    
            // Instance of User
            $user= new User;

            // Search for the user by id
            $user = $user->find($userId);

            // If User doesn't exist
            if (!$user) {
                http_response_code(404);
                $session->redirect("./index.php?p=admin/users");
            }
            //Verify form compliance
            if($form->validate($arrayPost->collectInput(), ['lastName', 'firstName', 'publicName', 'email', 'password'])){
                // Verify informations and hash password
                $emailSafe = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $email = filter_var($emailSafe,FILTER_VALIDATE_EMAIL); 
                $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
                $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
                $passwordSafe = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
                $password = password_hash($passwordSafe, PASSWORD_ARGON2I);
                $publicName = filter_input(INPUT_POST, 'publicName', FILTER_SANITIZE_STRING);
                //Instance of a new User
                $modifiedUser = new User;
                
                //Set the data
                $modifiedUser->setUserId($userId)
                    ->setLastName($lastName)
                    ->setFirstName($firstName)
                    ->setPublicName($publicName)
                    ->setEmailAddress($email)
                    ->setPassword($password)              
                    ;
                    //var_dump($user);
                //Insert into BDD
                $modifiedUser->update($userId);                


            //Redirection + message
            $session->put("message","Votre profil a été modifié avec succès");
            $session->redirect("./index.php?p=admin/users");
            return;
            }
            //form dosen't verify validation
            $session->put("error", $global->notEmptyPost()) ? "le formulaire est incomplet" : '';
                 
            //Display the form
            $updateUserForm = new Form;

            $updateUserForm->startForm()
                ->addLabelFor('lastName', 'Votre nom :')
                ->addInput('lastName', 'lastName', ['id' => 'lastName', 'class' => 'form-control','value' => $user->lastName])
                ->addLabelFor('firstName', 'Votre prénom :')
                ->addInput('firstName', 'firstName', ['id' => 'firstName', 'class' => 'form-control','value' => $user->firstName])
                ->addLabelFor('publicName', 'Votre pseudonyme :')
                ->addInput('publicName', 'publicName', ['id' => 'publicName', 'class' => 'form-control','value' => $user->publicName])
                ->addButton('Modifier', ['type' => 'submit', 'class' => 'btn btn-primary'])
                ->endForm();
                //var_dump($updateUserForm);
                $this->twig->display('back/updateUser.html.twig', ['updateUserForm' => $updateUserForm->create()]);   

        }else{
            $session->put("erreur", "Vous devez vous connecter pour ajouter une annonce");
            $session->redirect("./index.php?p=user/login");
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
            $currentPage="adminPosts";
            $post = new Post;
            $posts = $post->findAll();
            $commentator = new User;
            $authorPublicNameList = [];
            foreach ($posts as $com) {
                $postId = $com->postId;
                $authorPublicName = $commentator->getPostAuthorPublicName($postId);
                array_push($authorPublicNameList, $authorPublicName->{'publicName'});
            };
            $this->twig->display('back/adminPosts.html.twig', compact('authorPublicNameList', 'posts', 'currentPage'));
        }
    }

    /**
     * Method to update a Post
     * @param int $id
     * retrun void
     */
        
    public function updatePost(int $postId)
    {   
        //instance of Session
        $session = new Session;
        $sessionStopMessage = $session->forget('message');
        //Instance of Form
        $form = new Form;
        //instance of Globalconstant
        $global = new GlobalConstant;
        $arrayPost = new GlobalConstant;
       // Verify User's session
        if($this->isAdmin()){
            
            
            // Instance of Post
            $post= new Post;

            // Search for the post by id
            $post = $post->find($postId);

            // If Post doesn't exist
            if (!$post) {
                http_response_code(404);
                
                $session->redirect("./index.php?p=admin/posts");
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
            $session->put("message", "Le post a été modifié avec succès");
            $session->redirect("./index.php?p=admin/posts");
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
                $this->twig->display('back/updatePost.html.twig', ['sessionStopMessage' => $sessionStopMessage, 'updatePostForm' => $updatePostForm->create()]);   

        }else{
            $session->put("erreur", "Vous devez vous connecter pour ajouter une annonce");
            $session->redirect("./index.php?p=user/login");
        }

    }

    /**
     * Method to delete a post
     * @param int $postId
     * return void
     */

    public function deletePost(int $postId)
    {
        //Instance of Session
        $session = new Session;
        //Verify Admin status
        if($this->isAdmin()){
            $post = new Post;
            $post->delete($postId);
            $session->redirect("./index.php?p=admin/posts");
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
            $currentPage="adminComments";
            $comment= new Comment;
            $comments = $comment->findAll();
            $commentator = new User;
            $commentatorPublicNameList = [];
            foreach ($comments as $com) {
                $commentId = $com->commentId;
                $commentatorPublicName = $commentator->getCommentAuthorPublicName($commentId);
                array_push($commentatorPublicNameList, $commentatorPublicName->{'publicName'});
            };            
            $this->twig->display('back/adminComments.html.twig', compact('commentatorPublicNameList', 'comments', 'currentPage'));
        }
    }

    /**
     * Method to update a comment
     * @param int $id
     * return void
     */
        
    public function updateComment(int $commentId)
    {
        //Instance of Session
        $session = new Session;
        $sessionStopMessage = $session->forget('message');
        //Instance of Form
        $form = new Form;
        //Instance of GlobalConstant
        $global = new GlobalConstant;
        $arrayPost = new GlobalConstant;
        // Verify User's session
        if($this->isAdmin()){
                
            // Instance of comment
            $comment= new Comment;

            // Search for the comment by id
            $comment = $comment->find($commentId);

            // If comment doesn't exist
            if (!$comment) {
                http_response_code(404);
                $session->redirect("./index.php?p=admin/comments");
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
            $session->put("message", "Le commentaire a été modifié avec succès");
            $session->redirect("./index.php?p=admin/comments");
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
            $this->twig->display('back/updateComment.html.twig', ['sessionStopMessage' => $sessionStopMessage, 'updateCommentForm' => $updateCommentForm->create()]);
            return;
        }
            $session->put("erreur", "Vous devez vous connecter pour modifier un commentaire");
            $session->redirect("./index.php?p=user/login");
    }

    /**
     * Method to delete a comment
     * @param int $commentId
     * return void
     */

    public function deleteComment(int $commentId)
    {
        //Instance of Session
        $session = new Session;
        //Verify Admin status
        if($this->isAdmin()){
            //Instance of Comment
            $comment= new Comment;
            $comment->delete($commentId);
            //redirection after deleting
            $session->redirect("./index.php?p=admin/comments");
        }
    }

    /**
     * Method to approuve a comment
     * @param int $commentId
     * @return void
     */

    public function approveComment(int $commentId)
    {
        //Instance of Session
        $session = new Session;
        //Verify Admin status
        if($this->isAdmin()){
            //Instance of Comment and CommentRepository
            $comment= new Comment;
            $commentRepository = new CommentRepository();
            //Search of Data
            $commentArray = $commentRepository->findBy(['commentId' => $commentId]);
            $commentApproved =$comment->hydrate($commentArray);

            if($this->commentStatus == 0){
                $commentApproved->setCommentStatus(1);
            }
            $commentApproved->update($commentId);
            $session->redirect("./index.php?p=admin/comments");
        }
    }
    
    /**
     * Method to verify Admin status
     */

    private function isAdmin()
    {
        //Instance of Session
        $session = new Session;
        if(!$session->get('user')){
            $session->put("erreur","Vous devez être connecté pour accéder à cette page");
            $session->redirect("./index.php?p=user/login");
        }
        if($session->issetSession('user') && $session->get("user")['userRole'] == 'admin' || $session->get("user")['userRole'] == 'moderator'){
        return true;
        }
        $session->put("erreur","Vous n'avez pas les droits pour accéder à cette page");
        $this->twig->display('front/404.html.twig');
        
    }
}

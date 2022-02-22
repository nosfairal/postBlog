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
        //Verify Admin status
        if($this->isAdmin()){
            $currentPage="adminUsers";
            $user = new User;
            $users = $user->findAll();
            $this->twig->display('back/adminUsers.html.twig', compact('users','currentPage'));
        }
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
     * Method to approuve a user
     * @param int $userId
     * @return void
     */

    public function approuveUser(int $userId)
    {
        //Instance of Session
        $session = new Session;
        //Verify Admin status
        if($this->isAdmin()){
            $user = new User;
            $userRepository = new userRepository();
            $userArray = $userRepository->findBy(['userId' => $userId]);
            $userApprouved = $user->hydrate($userArray);
    
            $userApprouved->setUserStatus("approuved")
                        ->setUserRole("member");

            $userApprouved->update($userId);

            $session->redirect("./index.php?p=admin/index");
        }
    }

    /**
     * Method to approuve a user
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
               //$userStatus = "approuved";
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
            if($form->validate($_POST, ['lastName', 'firstName', 'publicName', 'email', 'password'])){
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
            $comment= new Comment;
            $comment->delete($commentId);
            $session->redirect("./index.php?p=admin/comments");
        }
    }

    /**
     * Method to approuve a comment
     * @param int $commentId
     * @return void
     */

    public function approuveComment(int $commentId)
    {
        //Instance of Session
        $session = new Session;
        //Verify Admin status
        if($this->isAdmin()){
        $comment= new Comment;
        $commentRepository = new CommentRepository();
        $commentArray = $commentRepository->findBy(['commentId' => $commentId]);
        $commentApprouved =$comment->hydrate($commentArray);

            if($this->commentStatus == 0){
                $commentApprouved->setCommentStatus(1);
            }
            $commentApprouved->update($commentId);
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

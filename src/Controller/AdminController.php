<?php 
namespace Nosfair\Blogpost\Controller;

use Nosfair\Blogpost\Entity\Comment;
use Nosfair\Blogpost\Entity\User;
use Nosfair\Blogpost\Entity\Post;
use Nosfair\Blogpost\Repository\CommentRepository;
use Nosfair\Blogpost\Repository\UserRepository;
use Nosfair\Blogpost\Service\Form;

class AdminController extends Controller
{
    public function index()
    {

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
     * Method to delete a user
     * @param int $id
     * return void
     */

    public function deleteUser(int $id){
        //Verify Admin status
        if($this->isAdmin()){
            $user = new User;
            $user->delete($id);
            header('Location: '.$_SERVER['HTTP_REFERER']);
        }
     }
    
    /**
     * Method to approuve a user
     * @param int $id
     * @return void
     */

    public function approuveUser(int $id)
    {
        //Verify Admin status
        if($this->isAdmin()){
            $user = new User;
            $userRepository = new userRepository();
            $userArray = $userRepository->findBy(['userId' => $id]);
            \var_dump($user);
            $userApprouved = $user->hydrate($userArray);
\var_dump($userApprouved);
    
            $userApprouved->setUserStatus("approuved")
                        ->setUserRole("member");

            $userApprouved->update($id);

            header('Location: https://localhost/blogpost/index.php?p=admin/users/');

        }
    }

    /**
     * Method to approuve a user
     * @param int $id
     * @return void
     */

    public function upgradeUser(int $id)
    {
        //Verify Admin status
        if($this->isAdmin()){
            $user = new User;
            $userRepository = new userRepository();
            $userArray = $userRepository->findBy(['userId' => $id]);
            \var_dump($user);
            $userUpgraded = $user->hydrate($userArray);

    
            $userUpgraded->setUserRole("moderator");
               //$userStatus = "approuved";
            $userUpgraded->update($id);

            header('Location: https://localhost/blogpost/index.php?p=admin/users/');

        }
    }

    /**
     * Method to update a user
     * @param int $id
     * return void
     */
    public function updateUser(int $id)
    {
        // Verify if User is admin
        if($this->isAdmin()){
                    
            // Instance of User
            $user= new User;

            // Search for the user by id
            $user = $user->find($id);

            // If User doesn't exist
            if (!$user) {
                http_response_code(404);
                header('Location: /');
                exit;
            }
            //Verify form compliance
            if(Form::validate($_POST, ['lastName', 'firstName', 'publicName', 'email', 'password'])){
                // Verify informations and hash password
                $email = strip_tags($_POST['email']);
                $lastName = strip_tags($_POST['lastName']);
                $firstName = strip_tags($_POST['firstName']);
                $password = password_hash($_POST['password'], PASSWORD_ARGON2I);
                $publicName = strip_tags($_POST['lastName']);
                //Instance of a new User
                $modifiedUser = new User;
                
                //Set the data
                $modifiedUser->setUserId($id)
                    ->setLastName($lastName)
                    ->setFirstName($firstName)
                    ->setPublicName($publicName)
                    ->setEmailAddress($email)
                    ->setPassword($password)              
                    ;
                    //var_dump($user);
                //Insert into BDD
                $modifiedUser->update($id);

                


            //Redirection + message
            $_SESSION['message'] = "Votre profil a été modifié avec succès";
            header('Location: https://localhost/blogpost/index.php?p=admin/users');
            exit;
            }else{
                //form dosen't verify validation
                $_SESSION['error'] = !empty($_POST) ? "le formulaire est incomplet" : '';
                
            }   
            //Display the form
            $updateUserForm = new Form;

        $updateUserForm->startForm()
            ->addLabelFor('lastName', 'Votre nom :')
            ->addInput('lastName', 'lastName', ['id' => 'lastName', 'class' => 'form-control'])
            ->addLabelFor('firstName', 'Votre prénom :')
            ->addInput('firstName', 'firstName', ['id' => 'firstName', 'class' => 'form-control'])
            ->addLabelFor('publicName', 'Votre pseudonyme :')
            ->addInput('publicName', 'publicName', ['id' => 'publicName', 'class' => 'form-control'])
            ->addLabelFor('email', 'Votre e-mail :')
            ->addInput('email', 'email', ['id' => 'email', 'class' => 'form-control'])
            ->addLabelFor('password', 'Votre mot de passe :')
            ->addInput('password', 'password', ['id' => 'password', 'class' => 'form-control'])
            ->addButton('M\'inscrire', ['type' => 'submit', 'class' => 'btn btn-primary'])
            ->endForm();
            //var_dump($updateUserForm);
            $this->twig->display('back/updateUser.html.twig', ['updateUserForm' => $updateUserForm->create()]);   

        }else{
            $_SESSION['erreur'] = "Vous devez vous connecter pour ajouter une annonce";
            header('Location: https://localhost/blogpost/index.php?p=user/login');
            exit;
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
     * Method to approuve a comment
     * @param int $id
     * @return void
     */

     public function approuveComment(int $id)
     {
         //Verify Admin status
         if($this->isAdmin()){
            $comment= new Comment;
            $commentRepository = new CommentRepository();
            $commentArray = $commentRepository->findBy(['commentId' => $id]);
            $commentApprouved =$comment->hydrate($commentArray);

                if($this->commentStatus = "to validate"){
                    $commentApprouved->setCommentStatus("approuved");
                }
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
             header('location: '.$_SERVER['HTTP_REFERER']);
         }
     }
}
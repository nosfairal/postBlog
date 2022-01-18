<?php
namespace Nosfair\Blogpost\Controller;

use DateTime;
use DateTimeInterface;
use Nosfair\Blogpost\Controller\Controller;
use Nosfair\Blogpost\Entity\User;
use Nosfair\Blogpost\Repository\UserRepository;
use Nosfair\Blogpost\Service\Form;

class UserController extends Controller
{
    /**
     * list of users in the Db
     *
     * @return void
     */
    public function index()
    {
        $user = new User;
        $users = $user->findAll();
        var_dump($users);
        $this->twig->display('back/userIndex.html.twig', compact('users'));
    }

    public function login()
    {    
         //Verify the form's compliance
         if(Form::validate($_POST, ['emailAddress', 'password'])){
            // Search by email the User
            $user = new User;
            $userArray = $user->findOneByEmail(strip_tags($_POST['emailAddress']));
            var_dump($userArray);
            var_dump($_POST['emailAddress']);
            //if User doesn't exist
            /*if(!$userArray){
                $_SESSION['erreur'] = 'Votre mail est incorrect';
                
                header('Location: https://localhost/blogpost/index.php?p=user/login');
                
            }else{
            //User exists
            $user = $user->hydrate($userArray);
            $password= $user->getPassword();
            }
            //Verify the password
            if(password_verify($_POST['password'], $password)){
                $user->setSession();
                header('Location: https://localhost/blogpost/index.php');
                
            }else{
                //wrong password
                $_SESSION['erreur'] = 'Votre MDP est incorrect';
                header('Location: https://localhost/blogpost/index.php?p=user/login');
               
            }*/
            if(!$userArray){
                $_SESSION['erreur'] = 'Vos identifiants sont incorrect';                
                header('Location: https://localhost/blogpost/index.php?p=user/login');
            }
            $user = $user->hydrate($userArray);
            $password = $user->getPassword();
            
           
            if($userArray && password_verify($_POST['password'],$password))                      
                $user->setSession();
                header('Location: https://localhost/blogpost/index.php');
                var_dump($user);
            if(password_verify($_POST['password'],$password)) false;
                $_SESSION['erreur'] = 'Vos identifiants sont incorrect';
                header('Location: https://localhost/blogpost/index.php?p=user/login');

            
         }
         
            var_dump($_SESSION);
        $loginForm = new Form;

        $loginForm->startForm()
            ->addLabelFor('emailAddress', 'Votre e-mail :')
            ->addInput('emailAddress', 'emailAddress', ['id' => 'emailAddress', 'class' => 'form-control'])
            ->addLabelFor('password', 'Votre mot de passe :')
            ->addInput('password', 'password', ['id' => 'password', 'class' => 'form-control'])
            ->addButton('Me connecter', ['type' => 'submit', 'class' => 'btn btn-primary'])
            ->endForm();

        $this->twig->display('front/login.html.twig', ['loginForm' => $loginForm->create()]);
    }

    /**
     * Déconnexion de l'utilisateur
     * @return exit 
     */
    public function logout(){
        unset($_SESSION['user']);
        header('Location: '. $_SERVER['HTTP_REFERER']);
        exit;
    }

    /**
     * Method to show a single user
     *
     * @param  int $id
     * @return void
     */
    public function show(int $id)
    {
        // On instancie le modèle
        $model = new User;

        // On récupère les données
        $user = $model->findBy(['userId' =>$id]);
        var_dump($user);
        $this->twig->display('back/user.html.twig', compact('user'));
    }
    public function register()
    {
        //Verify the form's compliance
        if(Form::validate($_POST, ['lastName', 'firstName', 'publicName', 'email', 'password'])){
            // Verify informations and hash password
            $email = strip_tags($_POST['email']);
            $lastName = strip_tags($_POST['lastName']);
            $firstName = strip_tags($_POST['firstName']);
            $password = password_hash($_POST['password'], PASSWORD_ARGON2I);
            $publicName = strip_tags($_POST['lastName']);
            //Instance of a new User
            $user = new User;

            $user->setLastName($lastName)
                ->setFirstName($firstName)
                ->setPublicName($publicName)
                ->setEmailAddress($email)
                ->setPassword($password)              
                ;
                var_dump($user);
            //Insert into BDD
            $user->create();

        }
        $registerForm = new Form;

        $registerForm->startForm()
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
            var_dump($registerForm);
            $this->twig->display('front/register.html.twig', ['registerForm' => $registerForm->create()]);
        /*$user = new User;
        $this->twig->display('front/loginRegistration.html.twig', compact('user'));*/
    }

}

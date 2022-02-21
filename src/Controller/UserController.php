<?php
namespace Nosfair\Blogpost\Controller;

use DateTime;
use DateTimeInterface;
use Nosfair\Blogpost\Controller\Controller;
use Nosfair\Blogpost\Entity\User;
use Nosfair\Blogpost\Service\Session;
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
        ////var_dump($users);
        $this->twig->display('back/userIndex.html.twig', compact('users'));
    }

    /**
     * Connect a User
     */
    public function login()
    {   
        //Instance of Session
        $session = new Session;
        $sessionStop = $session->forget('error');
        //Verify the form's compliance
        if(Form::validate($_POST, ['emailAddress', 'password'])){
        // Search by email the User
        $user = new User;
        //$userRepository = new UserRepository();
        $userArray = $user->findOneByEmail(strip_tags($_POST['emailAddress']));
        //If email doesn't exist
        if(!$userArray){
            $session->put("erreur", 'Vos identifiants sont incorrects');                
            $session->redirect("./index.php?p=user/login");
        }
        //If email exist
        $user = $user->hydrate($userArray);            
        $password = $user->getPassword();
        //var_dump($password);
        //If password doesn't complain
        if(!password_verify(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING), $password)){                
                $session->put("erreur", 'Vos identifiants sont incorrects');
                $session->redirect("./index.php?p=user/login");                   
                return;
            }
            $user->setSession();
            $session->redirect("./index.php?");                
            
        }
         

            
        $loginForm = new Form;

        $loginForm->startForm()
            ->addLabelFor('emailAddress', 'Votre e-mail :')
            ->addInput('emailAddress', 'emailAddress', ['id' => 'emailAddress', 'class' => 'form-control'])
            ->addLabelFor('password', 'Votre mot de passe :')
            ->addInput('password', 'password', ['id' => 'password', 'class' => 'form-control'])
            ->addButton('Me connecter', ['type' => 'submit', 'class' => 'btn btn-primary'])
            ->endForm();

        $this->twig->display('front/login.html.twig', ['sessionStop' => $sessionStop, 'loginForm' => $loginForm->create()]);
    }

    /**
     * Disconnexion of User
     * @return exit 
     */
    public function logout(){
        //instance of Session
        $session = new Session;
        $session->forget("user");
        $session->put("message", "Merci et à bientôt");
        $session->redirect("./index.php?p=user/login");
    }

    /**
     * Method to show a single user
     *
     * @param  int $id
     * @return void
     */
    public function show(int $id)
    {
        // Instance of the model
        $model = new User;

        //Get the data
        $user = $model->findBy(['userId' =>$id]);
        $this->twig->display('back/user.html.twig', ['user' => $user]);
    }
    public function register()
    {   
        //Instance of Session
        $session = new Session;
        $sessionStopMessage = $session->forget('message');
        $currentPage = "register";
        //Verify the form's compliance
        if(Form::validate($_POST, ['lastName', 'firstName', 'publicName', 'email', 'password'])){
            // Verify informations and hash password
            $emailSafe = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $email = filter_var($emailSafe,FILTER_VALIDATE_EMAIL); 
            $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
            $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
            $passwordSafe = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
            $password = password_hash($passwordSafe, PASSWORD_ARGON2I);
            $publicName = filter_input(INPUT_POST, 'publicName', FILTER_SANITIZE_STRING);
            //Instance of a new User
            $user = new User;

            $user->setLastName($lastName)
                ->setFirstName($firstName)
                ->setPublicName($publicName)
                ->setEmailAddress($email)
                ->setPassword($password)              
                ;
            
            $session->put("message", "Nous avons bien enregistré votre demande d'inscription et l'examinerons dans les plus brefs délais");
            //Insert into BDD
            $user->create();
            

        }
        $registerForm = new Form;

        $registerForm->startForm()
            ->addLabelFor('lastName', 'Votre nom :',['class' => 'label-color'])
            ->addInput('lastName', 'lastName', ['id' => 'lastName', 'class' => 'form-control'])
            ->addLabelFor('firstName', 'Votre prénom :',['class' => 'label-color'])
            ->addInput('firstName', 'firstName', ['id' => 'firstName', 'class' => 'form-control'])
            ->addLabelFor('publicName', 'Votre pseudonyme :',['class' => 'label-color'])
            ->addInput('publicName', 'publicName', ['id' => 'publicName', 'class' => 'form-control'])
            ->addLabelFor('email', 'Votre e-mail :',['class' => 'label-color'])
            ->addInput('email', 'email', ['id' => 'email', 'class' => 'form-control'])
            ->addLabelFor('password', 'Votre mot de passe :',['class' => 'label-color'])
            ->addInput('password', 'password', ['id' => 'password', 'class' => 'form-control'])
            ->addButton('M\'inscrire', ['type' => 'submit', 'class' => 'btn btn-secondary label-color'])
            ->endForm();
            $this->twig->display('front/register.html.twig', ['sessionStopMessage' => $sessionStopMessage, 'currentPage' => $currentPage, 'registerForm' => $registerForm->create()]);
        
    }

}

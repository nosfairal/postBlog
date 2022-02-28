<?php
namespace Nosfair\Blogpost\Controller;

use DateTime;
use DateTimeInterface;
use Nosfair\Blogpost\Controller\Controller;
use Nosfair\Blogpost\Entity\User;
use Nosfair\Blogpost\Service\Session;
use Nosfair\Blogpost\Repository\UserRepository;
use Nosfair\Blogpost\Service\Form;
use Nosfair\Blogpost\Service\GlobalConstant;

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
        //Instance of GlobalConstant
        $global = new GlobalConstant;
        $arrayPost = new GlobalConstant;
        $sessionStop = $session->forget('error');
        $sessionStopMessage = $session->forget('message');
        //Instance of Form
        $form = new Form;
        //Verify the form's compliance
        if($form->validate($arrayPost->collectInput(), ['emailAddress', 'password'])){
        // Search by email the User
        $user = new User;
        //$userRepository = new UserRepository();
        $userArray = $user->findOneByEmail(strip_tags($global->Post('emailAddress')));
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

        //Instance of Form   
        $loginForm = new Form;
        //Construction of the loginForm
        $loginForm->startForm()
            ->addLabelFor('emailAddress', 'Votre e-mail :')
            ->addInput('emailAddress', 'emailAddress', ['id' => 'emailAddress', 'class' => 'form-control'])
            ->addLabelFor('password', 'Votre mot de passe :')
            ->addInput('password', 'password', ['id' => 'password', 'class' => 'form-control'])
            ->addButton('Me connecter', ['type' => 'submit', 'class' => 'btn btn-primary'])
            ->endForm();
        //Display on view
        $this->twig->display('front/login.html.twig', ['sessionStopMessage' => $sessionStopMessage, 'sessionStop' => $sessionStop, 'loginForm' => $loginForm->create()]);
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
    public function show(int $userId)
    {
        // Instance of the model
        $model = new User;

        //Get the data
        $user = $model->findBy(['userId' =>$userId]);
        $this->twig->display('back/user.html.twig', ['user' => $user]);
    }
    public function register()
    {   
        
        //Instance of Session
        $session = new Session;
        $sessionStopMessage = $session->forget('message');
        $sessionStop = $session->forget('error');
        //Instance of Form
        $form = new Form;
        //Instance of GlobalConstant
        $arrayPost = new GlobalConstant;
        $currentPage = "register";
        //Verify the form's compliance
        if($form->validate($arrayPost->collectInput(), ['lastName', 'firstName', 'publicName', 'email', 'password'])){
            // Verify informations and hash password
            $emailSafe = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $email = filter_var($emailSafe,FILTER_VALIDATE_EMAIL); 
            $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
            $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
            $passwordSafe = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
            //$form->validatePwd($passwordSafe) ? $session->put("message", "le mot de passe est valide") : $session->put("message", "le mot de passe n'est pas valide");
            $password = password_hash($passwordSafe, PASSWORD_ARGON2I);
            
            $publicName = filter_input(INPUT_POST, 'publicName', FILTER_SANITIZE_STRING);
            //Verify the password 's compliance
            if ($form->validatePwd($passwordSafe) == true){ 
                
                //Instance of a new User
                $user = new User;

                $user->setLastName($lastName)
                    ->setFirstName($firstName)
                    ->setPublicName($publicName)
                    ->setEmailAddress($email)
                    ->setPassword($password)              
                    ;
                $session->forget('error');    
                $session->put("message", "Nous avons bien enregistré votre demande d'inscription et l'examinerons dans les plus brefs délais");
                
                //Insert into BDD
                $user->create();
                $session->redirect("./index.php?p=user/register");
                return;
            }
            $session->put("alerte", "le mot de passe n'est pas valide");
        }else{

        $session->put("erreur", "Le formulaire d'inscription n'est pas complet");
        //$session->redirect("./index.php?p=user/register");
        }
        //Instance of Form
        $registerForm = new Form;
        //Construction of the registerForm
        $registerForm->startForm()
            ->addLabelFor('lastName', ' Votre nom *:',['class' => 'label-color p-1'])
            ->addInput('lastName', 'lastName', ['id' => 'lastName', 'class' => 'form-control'])
            ->addLabelFor('firstName', ' Votre prénom *:',['class' => 'label-color p-1'])
            ->addInput('firstName', 'firstName', ['id' => 'firstName', 'class' => 'form-control'])
            ->addLabelFor('publicName', ' Votre pseudonyme *:',['class' => 'label-color p-1'])
            ->addInput('publicName', 'publicName', ['id' => 'publicName', 'class' => 'form-control'])
            ->addLabelFor('email', ' Votre e-mail *:',['class' => 'label-color p-1'])
            ->addInput('email', 'email', ['id' => 'email', 'class' => 'form-control'])
            ->addLabelFor('password', ' Votre mot de passe *:',['class' => 'label-color p-1', 'data-toggle'=> "tooltip", 'title'=> 'Entre 8 et 15 caractères avec au moins une lettre majuscule et une miniscule, un chiffre et un caractère spécial parmi [-+!*$@%_]'])
            ->addInput('password', 'password', ['id' => 'password', 'class' => 'form-control'])
            ->addButton('M\'inscrire', ['type' => 'submit', 'class' => 'btn btn-outline-success btn-block label-color'])
            ->endForm();
        //Display on view
        $this->twig->display('front/register.html.twig', ['sessionStop' => $sessionStop, 'sessionStopMessage' => $sessionStopMessage, 'currentPage' => $currentPage, 'registerForm' => $registerForm->create()]);
        
    }

}

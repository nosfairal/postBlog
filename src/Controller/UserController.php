<?php
namespace Nosfair\Blogpost\Controller;
use Nosfair\Blogpost\Controller\Controller;
use Nosfair\Blogpost\Entity\User;
use Nosfair\Blogpost\Repository;
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
        $registerForm = new Form;

        $registerForm->startForm()
            ->addLabelFor('lastName', 'Votre nom :')
            ->addInput('lastName', 'lastName', ['id' => 'lastName'])
            ->addLabelFor('firstName', 'Votre prénom :')
            ->addInput('firstName', 'firstName', ['id' => 'firstName'])
            ->addLabelFor('email', 'Votre e-mail :')
            ->addInput('email', 'email', ['id' => 'email'])
            ->addLabelFor('pass', 'Votre mot de passe :')
            ->addInput('password', 'password', ['id' => 'pass'])
            ->addButton('M\'inscrire', ['class' => 'btn btn-primary'])
            ->endForm();
            var_dump($registerForm);
            $this->twig->display('front/loginRegistration.html.twig', ['registerForm' => $registerForm->create()]);
        /*$user = new User;
        $this->twig->display('front/loginRegistration.html.twig', compact('user'));*/
    }
}

<?php
namespace Nosfair\Blogpost\Controller;
use Nosfair\Blogpost\Controller\Controller;
use Nosfair\Blogpost\Entity\User;


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
     * Méthode permettant d'afficher un article à partir de son slug
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
        $this->render('back/user', compact('user'));
    }
}

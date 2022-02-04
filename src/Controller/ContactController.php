<?php 
namespace Nosfair\Blogpost\Controller;
use Nosfair\Blogpost\Service\Form;

class ContactController extends Controller
{
    public function index()
    {
        $currentPage = "contact";
        if(Form::validate($_POST, ['name', 'firstName', 'email', 'message'])){
            $name = strip_tags($_POST['name']);
            $firstName= strip_tags($_POST['firstName']);
            $email = strip_tags($_POST['email']);
            $message = strip_tags($_POST['message']);

        }else{
            //form doesn't match
            $_SESSION['error'] = !empty($_POST) ? "le formulaire est incomplet" : '';
            $name= isset($_POST['name']) ? strip_tags($_POST['name']) : '';
            $firstName= isset($_POST['firstName']) ? strip_tags($_POST['firstName']) : '';
            $email = isset($_POST['email']) ?strip_tags($_POST['email']): '';
            $message = isset($_POST['message']) ?strip_tags($_POST['message']) : '';
        }
        
        //instance of Form
        $addContactForm = new Form;
        //Construction of the form
        $addContactForm->startForm()
            ->addLabelFor('name', 'Nom :')
            ->addInput('text', 'name', [
                'class' => 'form-control',
                'value' => $name])
            ->addLabelFor('firstName', 'Prénom :')
            ->addInput('text', 'firstName', [
                'class' => 'form-control',
                'value' => $firstName])
            ->addLabelFor('email', 'Votre mail :')
            ->addInput('text', 'email', [
                'class' => 'form-control',
                'value' => $email])
            ->addLabelFor('message', 'Votre message :')
            ->addTextarea('message', $message, ['class' => 'form-control'])
            ->addButton('Valider', ['type' => 'submit', 'class' => 'btn btn-primary'])
            ->addInput('hidden', 'recaptcha-esponse', [
                'id' => 'recaptchaResponse'])
            ->endForm()
            ;
        $this->twig->display('front/contact.html.twig', ['currentPage' => $currentPage, 'addContactForm' => $addContactForm->create()]);
    }
}
<?php 
namespace Nosfair\Blogpost\Controller;

class AdminController extends Controller
{
    public function index()
    {
        \var_dump($_SESSION);
        //Verify Admin status
        if($this->isAdmin()){

        }
    }

    /**
     * Method to verify Admin status
     */

     private function isAdmin()
     {
         if(isset($_SESSION['user']) && $_SESSION['user']['userRole'] == 'admin'){
            echo "prout";
         }
     }
}
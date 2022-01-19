<?php
namespace Nosfair\Blogpost\Repository;
use Nosfair\Blogpost\Entity\Model;
use Nosfair\Blogpost\Service\Db;
use Nosfair\Blogpost\Entity\User;

class UserRepository extends Model
{
    /**
     * Get an user by e-mail
     * @param string $email 
     * @return mixed 
     */

    public function findOneByEmail(string $email) 
    {
        return $this->request("SELECT * FROM {$this->table} WHERE emailAddress = ?", [$email])->fetch();
    }

    public function setSession() 
    {
        $_SESSION['user'] = [
            'userId' => $this->userId,
            'emailAdrress' => $this->emailAddress
        ];
    }
}
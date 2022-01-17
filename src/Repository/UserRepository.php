<?php
namespace Nosfair\Blogpost\Repository;
use Nosfair\Blogpost\Entity\Model;
use Nosfair\Blogpost\Service\Db;

class UserRepository extends Model
{
    /**
     * Récupérer un user à partir de son e-mail
     * @param string $email 
     * @return mixed 
     */

    public function findOneByEmail(string $email)
    {
        return $this->request("SELECT * FROM {$this->table} WHERE emailAddress = ?", [$email])->fetch();
    }
}
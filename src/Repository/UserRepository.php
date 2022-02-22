<?php
namespace Nosfair\Blogpost\Repository;
use Nosfair\Blogpost\Repository\ModelRepository;
use Nosfair\Blogpost\Service\Db;
use Nosfair\Blogpost\Entity\User;

class UserRepository extends ModelRepository
{
    public function __construct()
    {

    }
    
    
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
            'emailAdrress' => $this->emailAddress,
            'userRole' => $this->userRole
        ];
    }
    public function findBy(array $properties)
    {
        $fields = [];
        $values = [];

 
        foreach($properties as $field => $value){

            $fields[] = "$field = ?";
            $values[] = $value;
        }

      
        $liste_fields = implode(' AND ', $fields);
        
  
        return $this->request('SELECT * FROM user WHERE '. $liste_fields, $values)->fetchAll();
    }
    public function update($modifiedId)
    {
        $fields = [];
        $values = [];



        foreach($this as $field => $value){
        
            if($value !== null && $field != 'db' && $field != 'table') {
                $fields[] = "$field = ?";
                $values[] = $value;
            }
        }
        $values[] = $modifiedId; //$this->postId;
       // \var_dump($values);
        
       

        //convert array into string
        $liste_fields = implode(', ', $fields);
//\var_dump($fields);

        //execute the request
        return $this->request('UPDATE user SET '. $liste_fields.' WHERE userId = ?', $values);
        //\var_dump($this);
        
    }

    public function find(int $userId)
    {
        return $this->request("SELECT * FROM user WHERE userId = $userId")->fetch();
    }


    public function hydrate($donnees)
    {
        foreach($donnees as $key => $value){

            $setter = 'set'.ucfirst($key);
            
  
            if(method_exists($this, $setter)) {
  
                $this->$setter($value);
            }
        }
        return $this;
    }

    /**
     * Get the public name of the author of a post
     */

    public function getPostAuthorPublicName($postId)
    {
        return $this->request("SELECT user.publicName FROM user JOIN post ON user.userId = post.author WHERE post.postId = $postId")->fetch();
    }

    /**
     * Get the public name of the author of a comment
     */

    public function getCommentAuthorPublicName($commentId)
    {
        return $this->request("SELECT user.publicName FROM user JOIN comment ON user.userId = comment.author WHERE comment.commentId = $commentId")->fetch();
    }

    public function request(string $sql, array $attributes = null)
    {
        // Get instanceof Db
        $this->db = Db::getInstance();

        // On vérifie si on a des attributs
        if($attributes !== null) {
            // Prepare request

            $query = $this->db->prepare($sql);
   
            $query->execute($attributes);
            return $query;
        }else{
            // Simple request
            return $this->db->query($sql);
        }
    }

}
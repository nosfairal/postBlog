<?php
namespace Nosfair\Blogpost\Repository;
use Nosfair\Blogpost\Entity\Model;
use Nosfair\Blogpost\Entity\Comment;
use Nosfair\Blogpost\Service\Db;

class CommentRepository extends Model
{   
    public function __construct()
    {

    }
    

    public function findAll()
    {
        $query = $this->request('SELECT * FROM comment');
        return $query->fetchAll();
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
    
    public function findBy(array $properties)
    {
        $fields = [];
        $values = [];

 
        foreach($properties as $field => $value){

            $fields[] = "$field = ?";
            $values[] = $value;
        }

      
        $liste_fields = implode(' AND ', $fields);
        
  
        return $this->request('SELECT * FROM comment WHERE '. $liste_fields, $values)->fetchAll();
    }
}
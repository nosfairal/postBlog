<?php
namespace Nosfair\Blogpost\Repository;

use Attribute;
use Nosfair\Blogpost\Service\Db;

class ModelRepository extends Db
{


    // Table of BDD
    protected $table;

    // Instance of Db
    protected $database;


    public function findAll()
    {
        $query = $this->request('SELECT * FROM '. $this->table);
        return $query->fetchAll();
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
        
  
        return $this->request('SELECT * FROM '.$this->table.' WHERE '. $liste_fields, $values)->fetchAll();
    }

    public function find(int $id)
    {
        return $this->request("SELECT * FROM {$this->table} WHERE {$this->table}Id = $id")->fetch();
    }

    /**
     * create an instance of an Entity
     */
    public function create()
    {
        $fields = [];
        $inter = [];
        $values = [];
        foreach($this as $field => $value){          

            if($value !== null && $field != 'db' && $field != 'table') {
                $fields[] = $field;
                $inter[] = "?";
                $values[] = $value;
            }

        }
        
        $liste_fields = implode(', ', $fields);
        $liste_inter = implode(', ', $inter);
        //\var_dump($values);

        return $this->request('INSERT INTO '.$this->table.' ('. $liste_fields.')VALUES('.$liste_inter.')', $values);
    }

    /**
     * update an instance of Entity
     */
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
        return $this->request('UPDATE '.$this->table.' SET '. $liste_fields.' WHERE '.$this->table.'Id = ?', $values);
        //\var_dump($this);
        
    }

    /**
     * Delete an instance of the Entity
     */
    public function delete(int $id)
    {
        return $this->request("DELETE FROM {$this->table} WHERE {$this->table}Id = ?", [$id]);
    }

    /**
     * 
     */
    public function request(string $sql, array $attributes = null)
    {
        // Get instanceof Db
        $this->database = Db::getInstance();

        // On v??rifie si on a des attributs
        if($attributes !== null) {
            // Prepare request

            $query = $this->database->prepare($sql);
   
            $query->execute($attributes);
            return $query;
        }else{
            // Simple request
            return $this->database->query($sql);
        }
    }

    /**
     * Find the publicName of the author of a post
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
}

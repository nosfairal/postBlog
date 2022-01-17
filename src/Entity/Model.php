<?php
namespace Nosfair\Blogpost\Entity;
use Nosfair\Blogpost\Service\Db;

class Model extends Db
{
    // Table of BDD
    protected $table;

    // Instance of Db
    private $db;


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
        return $this->request("SELECT * FROM {$this->table} WHERE id = $id")->fetch();
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
       
            if($value != null && $field != 'db' && $field != 'table') {
                $fields[] = $field;
                $inter[] = "?";
                $values[] = $value;
            }
        }

   
        $liste_fields = implode(', ', $fields);
        $liste_inter = implode(', ', $inter);


        return $this->request('INSERT INTO '.$this->table.' ('. $liste_fields.')VALUES('.$liste_inter.')', $values);
    }

    /**
     * update an instance of Entity
     */
    public function update()
    {
        $fields = [];
        $values = [];


        foreach($this as $field => $value){
        
            if($value !== null && $field != 'db' && $field != 'table') {
                $fields[] = "$field = ?";
                $values[] = $value;
            }
        }
        $values[] = $this->id;

        //convert array into string
        $liste_fields = implode(', ', $fields);

        //execute the request
        return $this->request('UPDATE '.$this->table.' SET '. $liste_fields.' WHERE id = ?', $values);
    }

    /**
     * Delete an instance of the Entity
     */
    public function delete(int $id)
    {
        return $this->request("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }

    /**
     * 
     */
    public function request(string $sql, array $attributes = null)
    {
        // On récupère l'instance de Db
        $this->db = Db::getInstance();

        // On vérifie si on a des attributs
        if($attributes !== null) {
            // Requête préparée
            $query = $this->db->prepare($sql);
            $query->execute($attributes);
            return $query;
        }else{
            // Requête simple
            return $this->db->query($sql);
        }
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

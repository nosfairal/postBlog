<?php
namespace Nosfair\Blogpost\Entity;

class Post
{
    private int  $postId;
    private string $author;
    private string $title;
    private string $slug;
    private string $intro;
    private string $content;
    private $publicationDate;
    private $creationDate;
    private $lastUpdate;

    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value)
        {
        $method = 'set'.ucfirst($key);
        
        if (method_exists($this, $method))
        {
            $this->$method($value);
        }
    }
  }

}


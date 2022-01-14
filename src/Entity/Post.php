<?php
namespace Nosfair\Blogpost\Entity;
use Nosfair\Blogpost\Entity\Model;
use DateTime;

class Post extends Model
{
    private int  $postId;
    private int $author;
    private string $title;
    private string $slug;
    private string $intro;
    private string $content;
    private DateTime $publicationDate;
    private Datetime $creationDate;
    private DateTime $lastUpdate;

    public function __construct()
    {
        $this->table = 'post';
    }

    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value)
        {
            $method = 'set'.ucfirst($key);
        
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }


    /**
     * Get the value of postId
     */ 
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * Set the value of postId
     *
     * @return self
     */ 
    public function setPostId($postId)
    {
        $this->postId = $postId;

        return $this;
    }

    /**
     * Get the value of author
     */ 
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @return self
     */ 
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of slug
     */ 
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @return self
     */ 
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the value of intro
     */ 
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * Set the value of intro
     *
     * @return self
     */ 
    public function setIntro($intro)
    {
        $this->intro = $intro;

        return $this;
    }

    /**
     * Get the value of content
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of publicationDate
     */ 
    public function getPublicationDate():DateTime
    {
        return $this->publicationDate;
    }

    /**
     * Set the value of publicationDate
     *
     * @return self
     */ 
    public function setPublicationDate(DateTime $publicationDate)
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * Get the value of creationDate
     */ 
    public function getCreationDate():DateTime
    {
        return $this->creationDate;
    }

    /**
     * Set the value of creationDate
     *
     * @return self
     */ 
    public function setCreationDate(DateTime $creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get the value of lastUpdate
     */ 
    public function getLastUpdate():DateTime
    {
        return $this->lastUpdate;
    }

    /**
     * Set the value of lastUpdate
     *
     * @return self
     */ 
    public function setLastUpdate(DateTime $lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }
}


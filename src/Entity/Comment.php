<?php
namespace Nosfair\Blogpost\Entity;
use Nosfair\Blogpost\Entity\Model;
use DateTime;

class Comment extends Model
{
    private int  $commentId;
    private int $post;
    private string  $commentStatus;
    private string $author;
    private string $content;
    private DateTime $publicationDate;
    private Datetime $creationDate;
    private DateTime $lastUpdate;

    public function __construct(array $donnees)
    {
        $this->table='comment';
        $this->hydrate($donnees);
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

    /**
     * Get the value of commentId
     */ 
    public function getCommentId()
    {
        return $this->commentId;
    }

    /**
     * Set the value of commentId
     *
     * @return self
     */ 
    public function setCommentId($commentId)
    {
        $this->commentId = $commentId;

        return $this;
    }

    /**
     * Get the value of commentStatus
     */ 
    public function getCommentStatus()
    {
        return $this->commentStatus;
    }

    /**
     * Set the value of commentStatus
     *
     * @return self
     */ 
    public function setCommentStatus($commentStatus)
    {
        $this->commentStatus = $commentStatus;

        return $this;
    }

    /**
     * Get the value of post
     */ 
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set the value of post
     *
     * @return self
     */ 
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }
}


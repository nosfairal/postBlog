<?php

namespace Nosfair\Blogpost\Entity;
use Nosfair\Blogpost\Entity\Model;
use DateTime;

class User extends Model
{
    private int  $userId;
    private string $lastName;
    private string $firstName;
    private string $publicName;
    private string $emailAddress;
    private string $password;
    private string $userStatus;
    private string $userRole;
    private DateTime $creationDate;
    

    public function __construct()
    {
        $this->table ='user';
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
     * Get the value of userId
     */ 
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     *
     * @return self
     */ 
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of lastName
     */ 
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @return self
     */ 
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }


    /**
     * Get the value of firstName
     */ 
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @return self
     */ 
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of publicName
     */ 
    public function getPublicName()
    {
        return $this->publicName;
    }

    /**
     * Set the value of publicName
     *
     * @return self
     */ 
    public function setPublicName($publicName)
    {
        $this->publicName = $publicName;

        return $this;
    }

    /**
     * Get the value of emailAddress
     */ 
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set the value of emailAddress
     *
     * @return self
     */ 
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of userStatus
     */ 
    public function getUserStatus()
    {
        return $this->userStatus;
    }

    /**
     * Set the value of userStatus
     *
     * @return self
     */ 
    public function setUserStatus($userStatus)
    {
        $this->userStatus = $userStatus;

        return $this;
    }

    /**
     * Get the value of userRole
     */ 
    public function getUserRole()
    {
        return $this->userRole;
    }

    /**
     * Set the value of userRole
     *
     * @return self
     */ 
    public function setUserRole($userRole)
    {
        $this->userRole = $userRole;

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
}

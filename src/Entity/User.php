<?php
namespace App\Entity;

use App\Service\User\Validator\EmailDomainBlacklistValidator;
use App\Service\User\Validator\NameBlacklistValidator;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * User
 *
 * @ORM\Table(name="users", uniqueConstraints={@ORM\UniqueConstraint(name="users_email_uindex", columns={"email"}), @ORM\UniqueConstraint(name="users_name_uindex", columns={"name"})}, options={"collate"="utf8mb4_unicode_ci", "charset"="utf8mb4"})
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, unique=true)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="email", type="string", length=256, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    private $deleted;

    /**
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param mixed $deleted
     * @return User
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param mixed $notes
     * @return User
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }


    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('name', new Assert\Length([
            'min' => 8,
            'max' => 64,
            'minMessage' => 'Your name must be at least {{ limit }} characters long',
            'maxMessage' => 'Your name cannot be longer than {{ limit }} characters',
        ]));
        $metadata->addPropertyConstraint('name', new Assert\Regex([
            'pattern' => '/^[a-zA-Z\d]+$/',
            'message' => 'Your name can contain only letters and numbers',
        ]));
        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'name',
            'message' => 'The name {{ value }} already in use'
        ]));
        $metadata->addConstraint(new Assert\Callback([
            NameBlacklistValidator::class,
            'validate',
        ]));


        $metadata->addPropertyConstraint('email', new Assert\NotBlank());
        $metadata->addPropertyConstraint('email', new Assert\Length([
            'max' => 256,
            'maxMessage' => 'Your email cannot be longer than {{ limit }} characters',
        ]));
        $metadata->addPropertyConstraint('email', new Assert\Email([
            'message' => 'The email {{ value }} is not a valid email.',
        ]));
        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'email',
            'message' => 'The email {{ value }} already in use'
        ]));
        $metadata->addConstraint(new Assert\Callback([
            EmailDomainBlacklistValidator::class,
            'validate',
        ]));

    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $dateTime = new \DateTime('now');

        if ($this->getCreated() === null) {
            $this->setCreated(
                $dateTime
            );
        }
    }
}

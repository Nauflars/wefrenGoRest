<?php

namespace App\Users\Domain\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class User
{
    const GENDER = ['male', 'female'];
    const STATUS = ['active', 'inactive'];
    private int $id;

    /**
     * @Assert\NotBlank(
     *     message = "name can't be blank"
     * )
     */
    private string $name;

    /**
     * @Assert\NotBlank(
     *    message = "email can't be blank"
     * )
     * @Assert\Email(
     *     message = "email is not a valid email."
     * )
     */
    private string $email;

    /**
     * @Assert\NotBlank(
     *     message = "gender can't be blank"
     * )
     * @Assert\Choice(choices=User::GENDER, message="Choose a valid gender (male or female).")
     */
    private string $gender;
    /**
     * @Assert\NotBlank(
     *     message = "status can't be blank"
     * )
     * @Assert\Choice(choices=User::STATUS, message="Choose a valid status ( active or inactive).")
     */
    private string $status;

    public function __construct()
    {
    }
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }   


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }   

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }   

      public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }   

}

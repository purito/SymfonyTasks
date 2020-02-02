<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="date")
     */
    private $deadline;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $priority;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="date")
     */
    private $added_on;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UsersTask", mappedBy="task")
     */
    private $usersTasks;

    public function __construct()
    {
        $this->usersTasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAddedOn(): ?\DateTimeInterface
    {
        return $this->added_on;
    }

    public function setAddedOn(\DateTimeInterface $added_on): self
    {
        $this->added_on = $added_on;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|UsersTask[]
     */
    public function getUsersTasks(): Collection
    {
        return $this->usersTasks;
    }

    public function addUsersTask(UsersTask $usersTask): self
    {
        if (!$this->usersTasks->contains($usersTask)) {
            $this->usersTasks[] = $usersTask;
            $usersTask->setTask($this);
        }

        return $this;
    }

    public function removeUsersTask(UsersTask $usersTask): self
    {
        if ($this->usersTasks->contains($usersTask)) {
            $this->usersTasks->removeElement($usersTask);
            // set the owning side to null (unless already changed)
            if ($usersTask->getTask() === $this) {
                $usersTask->setTask(null);
            }
        }

        return $this;
    }
}

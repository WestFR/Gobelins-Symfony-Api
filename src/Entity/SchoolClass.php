<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SchoolClassRepository")
 * @JMS\ExclusionPolicy("all")
 */
class SchoolClass
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     *
     * @JMS\Expose()
     * @JMS\Groups({"user_create", "level_item", "class_item", "classes_list"})
     *
     * @SWG\Property(description="Unique uuid of the class.")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", length=4)
     * @Assert\NotBlank()
     *
     * @JMS\Expose()
     * @JMS\Groups({"user_create", "level_item", "parent_list", "class_item", "classes_list"})
     *
     * @SWG\Property(description="Start year value.")
     */
    protected $yearStart;

    /**
     * @ORM\Column(type="integer", length=4)
     * @Assert\NotBlank()
     *
     * @JMS\Expose()
     * @JMS\Groups({"user_create", "level_item", "parent_list", "class_item", "classes_list"})
     *
     * @SWG\Property(description="End year value")
     */
    protected $yearEnd;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Children", mappedBy="schoolClass")
     *
     * @JMS\Expose()
     * @JMS\Groups({"class_item"})
     *
     * @SWG\Property(description="Childrens in the class.")
     */
    protected $childrens;

    /**
     * @ORM\ManyToOne(targetEntity="UserTeacher", inversedBy="schoolClasses")
     * @ORM\JoinColumn(nullable=false)
     *
     * @JMS\Expose()
     * @JMS\Groups({"parent_list", "children_item", "class_item"})
     *
     * @SWG\Property(description="Teacher of the class.")
     */
    protected $teacher;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SchoolLevel", inversedBy="schoolClasses")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="label")
     *
     * @JMS\Expose()
     * @JMS\Groups({"user_create", "children_item", "parent_list", "class_item", "classes_list"})
     *
     * @SWG\Property(description="School level of the class.")
     */
    protected $schoolLevel;

    public function __construct()
    {
        $this->childrens = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getYearStart(): ?int
    {
        return $this->yearStart;
    }

    public function setYearStart(int $yearStart): self
    {
        $this->yearStart = $yearStart;

        return $this;
    }

    public function getYearEnd(): ?int
    {
        return $this->yearEnd;
    }

    public function setYearEnd(int $yearEnd): self
    {
        $this->yearEnd = $yearEnd;

        return $this;
    }

    public function getSchoolLevel(): ?SchoolLevel
    {
        return $this->schoolLevel;
    }

    public function setSchoolLevel(?SchoolLevel $schoolLevel): self
    {
        $this->schoolLevel = $schoolLevel;

        return $this;
    }

    /**
     * @return Collection|Children[]
     */
    public function getChildrens(): Collection
    {
        return $this->childrens;
    }

    public function addChildren(Children $children): self
    {
        if (!$this->childrens->contains($children)) {
            $this->childrens[] = $children;
            $children->setSchoolClass($this);
        }

        return $this;
    }

    public function removeChildren(Children $children): self
    {
        if ($this->childrens->contains($children)) {
            $this->childrens->removeElement($children);
            // set the owning side to null (unless already changed)
            if ($children->getSchoolClass() === $this) {
                $children->setSchoolClass(null);
            }
        }

        return $this;
    }

    public function getTeacher(): ?UserTeacher
    {
        return $this->teacher;
    }

    public function setTeacher(?UserTeacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }
}

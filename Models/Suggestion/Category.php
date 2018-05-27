<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 17:37
 */

namespace ZONNY\Models\Suggestion;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 * @package ZONNY\Models\Suggestion
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Category
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    /**
     * @ORM\Column(type="datetimetz", name="creation_datetime")
     */
    private $creationDatetime;


    /**
     * Foreign keys
     */
    /**
     * @ORM\OneToMany(targetEntity=SuggestionCategory::class, cascade={"persist", "remove"}, mappedBy="suggestions")
     */
    private $suggestionCategories;

    public function __construct()
    {
        $this->suggestionCategories = new ArrayCollection();
    }

    /**
     * @return array|null
     */
    public function getSuggestionCategories(): ?array {
        return $this->suggestionCategories->toArray();
    }

    /**
     * @param SuggestionCategory $suggestionCategory
     * @return $this
     */
    public function addSuggestionCategory(SuggestionCategory $suggestionCategory){
        if(!$this->suggestionCategories->contains($suggestionCategory)){
            $this->suggestionCategories->add($suggestionCategory);
        }
        return $this;
    }

    /**
     * @param SuggestionCategory $suggestionCategory
     * @return $this
     */
    public function removeSuggestionCategory(SuggestionCategory $suggestionCategory){
        if($this->suggestionCategories->contains($suggestionCategory)){
            $this->suggestionCategories->removeElement($suggestionCategory);
        }
        return $this;
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 17:35
 */

namespace ZONNY\Models\Suggestion;
use Doctrine\ORM\Mapping as ORM;
use ZONNY\Models\Suggestion;

/**
 * Class SuggestionCategory
 * @package ZONNY\Models\Suggestion
 * @ORM\Entity
 * @ORM\Table(name="suggestion_categories")
 */
class SuggestionCategory
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var Suggestion $suggestion
     * @ORM\ManyToOne(targetEntity=Suggestion::class, inversedBy="suggestion_categories")
     */
    private $suggestion;
    /**
     * @var Category $category
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="suggestion_categories")
     */
    private $category;
    /**
     * @ORM\Column(type="datetimetz", name="creation_datetime")
     */
    private $creationDatetime;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return Suggestion
     */
    public function getSuggestion(): Suggestion
    {
        return $this->suggestion;
    }

    /**
     * @param Suggestion $suggestion
     * @return SuggestionCategory
     */
    public function setSuggestion(Suggestion $suggestion)
    {
        if($this->suggestion !== null){
            $this->suggestion->removeSuggestionCategory($this);
        }
        if($suggestion !== null){
            $suggestion->addSuggestionCategory($this);
        }

        $this->suggestion = $suggestion;
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return SuggestionCategory
     */
    public function setCategory(Category $category)
    {
        if($this->category !== null){
            $this->category->removeSuggestionCategory($this);
        }
        if($category !== null){
            $category->addSuggestionCategory($this);
        }

        $this->category = $category;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreationDatetime()
    {
        return $this->creationDatetime;
    }

    /**
     * @param mixed $creationDatetime
     */
    public function setCreationDatetime($creationDatetime): void
    {
        $this->creationDatetime = $creationDatetime;
    }




}
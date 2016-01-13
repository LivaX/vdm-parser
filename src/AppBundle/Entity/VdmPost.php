<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Post")
 */
class VdmPost {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $author;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $date;

    public function __construct(RowVdmPost $rowPost) {


        $this->id = $rowPost->_id;
        $this->content = $rowPost->_content;
        $this->author = $rowPost->_author;
        //ToDo: Valider que la date est bien une date
        $this->date = $rowPost->_date;
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return VdmPost
     */
    public function setContent($content)
    {
        $this->content = utf8_encode($content);

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return utf8_decode($this->content);
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return VdmPost
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set publishAt
     *
     * @param \string $date
     *
     * @return VdmPost
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \string
     */
    public function getDate()
    {
        return $this->date;
    }
}

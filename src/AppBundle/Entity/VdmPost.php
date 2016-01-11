<?php

namespace AppBundle\AppBundle\Entity;

class VdmPost {

    private $id;
    private $content;
    private $author;
    private $publishAt;

    public function __construct() {

        $this->id = null;
        $this->content = null;
        $this->author = null;
        $this->publishAt = null;
    }

}

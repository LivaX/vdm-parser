<?php

namespace AppBundle\AppBundle\Entity;

/**
 * Post "brut" tel que récupérer sur viedemerde.fr
 *
 */
class RowVdmPost {

    private $_id;
    private $_content;
    private $_author;
    private $_publishAt;

    public function __construct($rowValue) {
        $this->_id = trim($rowValue['id']);
        $this->_content = trim($rowValue['content']);
        $this->_author = trim($rowValue['author']);
        $this->_publishAt = trim($rowValue['pusblish_at']);
    }

    /**
     *
     *
     * @return VdmPostEntity
     */
    public function convertToModel() {
        
    }

}

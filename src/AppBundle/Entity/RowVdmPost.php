<?php

namespace AppBundle\Entity;

/**
 * Post "brut" tel que récupérer sur viedemerde.fr
 *
 */
class RowVdmPost {

    public $_id;
    public $_content;
    public $_author;
    public $_publishAt;

    public function __construct($rowValue) {
        
        $this->_id = $rowValue['id'];
        $this->_content = $rowValue['content'];
        $this->_author = $rowValue['author'];
        $this->_publishAt = $rowValue['publish_at'];
        

    }

    

}

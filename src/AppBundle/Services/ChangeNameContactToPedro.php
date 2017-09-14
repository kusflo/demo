<?php
/**
 * @author Marcos Redondo <kusflo@gmail.com>
 */

namespace AppBundle\Services;

use AppBundle\Entity\Contact;


class ChangeNameContactToPedro implements MyService
{

    public function doSomething($contact)
    {
        /**@var $contact Contact*/
        $contact->setName('Pedro');
    }
}
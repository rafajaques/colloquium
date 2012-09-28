<?php

namespace ZfcUser\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use ZfcUser\Options\AuthenticationOptionsInterface;
use ZfcUser\Module as ZfcUser;
/**
 * @author Colloquium - rafajaques
 */
class SetNewPassword extends ProvidesEventsForm
{
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->add(array(
            'name' => 'pass',
            'options' => array(
                'label' => 'New password (at least 6 characters)',
            ),
            'attributes' => array(
                'type' => 'text'
            ),
        ));

        $submitElement = new Element\Button('submit');
        $submitElement
            ->setLabel('Set new password')
            ->setAttributes(array(
                'type'  => 'submit',
            ));

        $this->add($submitElement, array(
            'priority' => -100,
        ));
    }
}

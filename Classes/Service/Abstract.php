<?php

abstract class Tx_HappyFeet_Service_Abstract
{
    /**
     * @var Tx_Extbase_Object_ObjectManager
     */
    protected $objectManager;

    /**
     * @return object|Tx_Extbase_Object_ObjectManager
     */
    protected function getObjectManager()
    {
        if (null === $this->objectManager) {
            $this->objectManager = t3lib_div::makeInstance( 'Tx_Extbase_Object_ObjectManager' );
        }
        return $this->objectManager;
    }
}
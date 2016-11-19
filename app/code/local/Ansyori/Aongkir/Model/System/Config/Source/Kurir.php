<?php
/**
 * Indonesia Shipping Carriers
 * @copyright   Copyright (c) 2015 Ansyori B.
 * @email		ansyori@gmail.com / ansyori@kemanaservices.com
 * @build_date  March 2015
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Ansyori_Aongkir_Model_System_Config_Source_Kurir
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(

            array('value' => 'JNE', 'label'=>Mage::helper('adminhtml')->__('JNE')),
            array('value' => 'POS', 'label'=>Mage::helper('adminhtml')->__('POS')),
            array('value' => 'TIKI', 'label'=>Mage::helper('adminhtml')->__('TIKI')),
            array('value' => 'JNT', 'label'=>Mage::helper('adminhtml')->__('J&T')),
        );
    }

}

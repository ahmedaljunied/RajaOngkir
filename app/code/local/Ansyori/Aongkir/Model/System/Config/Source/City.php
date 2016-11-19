<?php
/**
 * Indonesia Shipping Carriers
 * @copyright   Copyright (c) 2015 Ansyori B.
 * @email		ansyori@gmail.com / ansyori@kemanaservices.com
 * @build_date  March 2015   
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Ansyori_Aongkir_Model_System_Config_Source_City
{

    /**
     * Options getter
     *
     * @return array
     */
	
	public function helper()
	{
		return  Mage::helper('aongkir');
	}
	
	
	
	
    public function toOptionArray()
    {
		
		
		
		if($this->helper()->getApiKey()):
		
		//$this->helper()->saveAreaToDb();
		
			$city_array = array();
			$city_list = Mage::getModel('aongkir/area')->getCollection()->setOrder('province','ASC');
			
			$count = Mage::getModel('aongkir/area')->getCollection()->count();
			
			if(!$count)
			{
				$this->helper()->saveAreaToDb();
			}
			
			$city_array[] = array('value' => '', 'label'=> '--- Select Origin ('.$count.' Cities Registered) ---' );
			foreach($city_list->getData() as $data)
			{
				$city_id =  $data['city_id'];
				$city_name = "Provinsi ".$data["province"]." ".$data["type"]." ".$data["city_name"]." Kodepos ".$data["postal_code"];
				$city_array[] = array('value' => $city_id, 'label'=> $city_name );
				
			}
		 
		 	//asort($city_array,);
			//uasort($city_array, 'cmp');
			return $city_array;
		 
		
		else:
			return array(
			
				array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('API KEY INVALID')),
				
			);
		endif;
    }

}

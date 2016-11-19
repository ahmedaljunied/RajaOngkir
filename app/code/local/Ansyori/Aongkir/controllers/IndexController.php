<?php
/**
 * Indonesia Shipping Carriers
 * @copyright   Copyright (c) 2015 Ansyori B.
 * @email		ansyori@gmail.com / ansyori@kemanaservices.com
 * @build_date  March 2015   
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Ansyori_Aongkir_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
	  $this->loadLayout();   
      $this->renderLayout(); 
	  
    }
	
	public function refreshareaAction()
	{
		echo 'START Reinstall Province City'.'<br>';
		$this->helper()->saveAreaToDb();
		echo 'END Reinstall Province City'.'<br>';
		
	}
	
	public function cityAction()
	{
			$model = Mage::getModel('aongkir/area');
		    $prov_id = $this->getRequest()->getParam('prov_id');
			
			
			$region = Mage::getModel('directory/region')->load($prov_id);
			$state_code = $region->getCode(); //CA
			
			
			$city = $model->getCollection()
					->distinct(true)
					->addFieldToFilter('province_id',$state_code)
					->addFieldToSelect('city_id')
					->addFieldToSelect('city_name')
					->addFieldToSelect('type')
					
					
					->load()
					;
			foreach($city as $data_city)
			{
				$region_cities[] = array(
					'city_id' => $data_city->getCityId(),
					'display_name' => $data_city->getType() . ' ' .$data_city->getCityName(),
				);
				
			}
					
			
			
			echo json_encode($region_cities);
		return;
	}
	
	public function xcityAction()
	{
			$model = Mage::getModel('aongkir/area');
		    $prov_id = $_GET['query'];
			
			
			$region = Mage::getModel('directory/region')->load($prov_id);
			$state_code = $region->getCode(); //CA
			
			
			$city = $model->getCollection()
					->distinct(true)
					->addFieldToFilter('province_id',$state_code)
					->addFieldToSelect('city_id')
					->addFieldToSelect('city_name')
					->addFieldToSelect('type')
					
					
					->load()
					;
			foreach($city as $data_city)
			{
				$string .= "{ value: '".$data_city->getType().' '.$data_city->getCityName()."', data: '".$data_city->getCityId()."' },";
			}
			
			
			
			echo $string."{value: 'Kabupaten Badung', data: '17' },{ value: 'Kabupaten Bangli', data: '32' },{ value: 'Kabupaten Buleleng', data: '94' },{ value: 'Kota Denpasar', data: '114' },{ value: 'Kabupaten Gianyar', data: '128' },{ value: 'Kabupaten Jembrana', data: '161' },{ value: 'Kabupaten Karangasem', data: '170' },{ value: 'Kabupaten Klungkung', data: '197' },{ value: 'Kabupaten Tabanan', data: '447' },";
	}
	
	public function helper($type = 'aongkir')
	{
			return Mage::helper($type);	
	}
}
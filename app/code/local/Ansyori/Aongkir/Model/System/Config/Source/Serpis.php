<?php
/**
 * Indonesia Shipping Carriers
 * @copyright   Copyright (c) 2015 Ansyori B.
 * @email		ansyori@gmail.com / ansyori@kemanaservices.com
 * @build_date  March 2015
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Ansyori_Aongkir_Model_System_Config_Source_Serpis
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


	public function getSql()
	{
		$sql2 = "
		CREATE TABLE IF NOT EXISTS `aongkir_service_list` (
		  `idx` int(11) NOT NULL AUTO_INCREMENT,
		  `kurir` varchar(255) DEFAULT NULL,
		  `servis` varchar(255) DEFAULT NULL,
		  `service_text` varchar(255) DEFAULT NULL,
		  PRIMARY KEY (`idx`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;

		INSERT IGNORE INTO `aongkir_service_list` (`idx`, `kurir`, `servis`, `service_text`) VALUES
		(1,	'JNE',	'CTC',	'JNE (CTC) JNE City Courier'),
		(2,	'JNE',	'CTCOKE',	'JNE (CTCOKE) JNE City Courier'),
		(3,	'JNE',	'CTCYES',	'JNE (CTCYES) JNE City Courier'),
		(4,	'JNE',	'OKE',	'JNE (OKE) Ongkos Kirim Ekonomis'),
		(5,	'JNE',	'PELIK',	'JNE (PELIK) Amplop Pra Bayar PELIKAN'),
		(6,	'JNE',	'REG',	'JNE (REG) Layanan Reguler'),
		(7,	'JNE',	'SPS',	'JNE (SPS) Super Speed'),
		(8,	'JNE',	'YES',	'JNE (YES) Yakin Esok Sampai'),
    (9,	'JNE',	'JTR',	'JNE (JTR) JNE Trucking'),
    (10,	'JNE',	'JTR<150',	'JNE (JTR<150) JNE Trucking'),
    (11,	'JNE',	'JTR250',	'JNE (JTR250) JNE Trucking'),
    (12,	'JNE',	'JTR>250',	'JNE (JTR>250) JNE Trucking'),
		(13,	'POS',	'Express Next Day',	'POS (Express Next Day) Express Next Day'),
		(14,	'POS',	'Surat Kilat Khusus',	'POS (Surat Kilat Khusus) Surat Kilat Khusus'),
		(15,	'TIKI',	'ECO',	'TIKI (ECO) Economi Service'),
		(16,	'TIKI',	'HDS',	'TIKI (HDS) Holiday Delivery Service'),
		(17,	'TIKI',	'ONS',	'TIKI (ONS) Over Night Service'),
		(18,	'TIKI',	'REG',	'TIKI (REG) Regular Service'),
		(19,	'TIKI',	'SDS',	'TIKI (SDS) Same Day Service'),
		(20,	'TIKI',	'TDS',	'TIKI (TDS) Two Day Service');

		";

		try{
			$resource = Mage::getSingleton('core/resource');
			$writeConnection = $resource->getConnection('core_write');
		  	$writeConnection->query($sql2);

		}catch(Exception $xx)
		{
			$this->setLog('Erorr Sql : '.$xx->getMessage());
			return false;
		}
	}

	public function toOptionArray()
	{
		$this->getSql();
		$data = '';
		$arrs_serpis = array();
		$sql = 'select distinct * from `aongkir_service_list` ';
		$resource = Mage::getSingleton('core/resource');
		$writeConnection = $resource->getConnection('core_read');
		$data =   $writeConnection->fetchAll($sql);

		foreach($data as $serpis)
		{
			$arrs_serpis[] =  array(

					'value' => $serpis['kurir'].'|'.$serpis['servis'],
					'label'=> $serpis['service_text']
					);
		}

		return $arrs_serpis;

	}

    public function xtoOptionArray()
    {


		$this->getSql();

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

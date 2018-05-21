<?php
/**
 * Indonesia Shipping Carriers
 * @copyright   Copyright (c) 2015 Ansyori B.
 * @email		ansyori@gmail.com / ansyori@kemanaservices.com
 * @build_date  March 2015
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Ansyori_Aongkir_Model_Carrier_Ongkir
		extends Mage_Shipping_Model_Carrier_Abstract
		implements Mage_Shipping_Model_Carrier_Interface
	{
        protected $_code = 'ongkir';
        public function collectRates(Mage_Shipping_Model_Rate_Request $request){
		  	if (!$this->getConfigFlag('active')) {
            	return false;
        	}

			$list_euy = $this->getListRates();

            $result = Mage::getModel('shipping/rate_result');
			$count = 0;

			foreach($list_euy as $jangar_kana_hulu)
			{
				if ($jangar_kana_hulu['cost'] === 0) {
					$jangar_kana_hulu['cost'] = 9000;
				}

				if (strpos($jangar_kana_hulu['text'], 'J&T') !== false) {
    				$jangar_kana_hulu['cost'] = $jangar_kana_hulu['cost'] + 1000;
				}

				$count++;
				//getMethodRates($code,$title,$name,$rates)
				$method = $this->getMethodRates('_'.$count,'',$jangar_kana_hulu['text'],$jangar_kana_hulu['cost']);
				$result->append($method);
			}




            return $result;
        }

		public function helper($type = 'aongkir')
		{
			return Mage::helper($type);
		}

		public function getCityId()
		{
			$string_city = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getCity();
			if (!$string_city) {
				$string_city = Mage::getModel('adminhtml/sales_order_create')->getQuote()->getShippingAddress()->getCity();
			}

			//added a second condition (for customers who have address saved while using iruna api, they don't have city type prefix (kota/kabupaten))
			$sql = "select * from ".Mage::getConfig()->getTablePrefix()."daftar_alamat where concat(type,' ',city_name) = '$string_city' or city_name = '$string_city' limit 0,1 ";

			$res =  $this->helper()->fetchSql($sql);

			return $res[0]['city_id'];

		}

		public function getListRates()
		{
			$origin = $this->getOriginId();
			$dest = $this->getCityId();
			$weight = $this->getBeratTotal();

			$carriers = $this->getActiveCarriers();

			$rate_list = array();
			//getRates($origin,$dest,$weight,$kurir)
			foreach($carriers as $kurir)
			{
				if($weight > 29){
					$rates_by_kurir = $this->helper()->getSavedRates($origin,$dest,1,$kurir);
				}else{
					$rates_by_kurir = $this->helper()->getSavedRates($origin,$dest,$weight,$kurir);
				};
				foreach($rates_by_kurir as $final_list)
				{
					if($weight > 29):
						$ship_cost = $this->changePrice($final_list['cost']) * $weight;
					else:
						$ship_cost = $this->changePrice($final_list['cost']);
					endif;

					$rate_list[] = array(
						'text' => $final_list['text'] . "($weight Kg)",
						'cost' => $ship_cost,
						'etd'  => $final_list['etd']

					);
				}
			}
			$this->helper()->setLog('Final rate '.print_r($rate_list,true));
			return $rate_list;

		}

		public function getActiveCarriers()
		{
			return explode(',',strtolower($this->helper()->config('kurir')));
		}


		public function changePrice($price)
		{
			$set = $this->helper()->config('changeprice');

			if(!$set):
				return $price;

			else:
			/*if (strpos($a,'are') !== false) {
				echo 'true';
			}*/

			$found_persen = false;

			if (strpos($set,'%') !== false) {
				//echo 'true';

				$found_persen = true;

				$set = str_replace('%','',$set);
			};

			$found_minus = false;

			if (strpos($set,'-') !== false) {
				//echo 'true';

				$found_minus = true;
				$set = str_replace('-','',$set);

			};

			$found_plus = false;

			if (strpos($set,'+') !== false) {
				//echo 'true';

				$found_plus = true;
				$set = str_replace('+','',$set);

			};

			$final_set = $set ;
			$changed_price = 0;
			if($found_persen)
			{
				$changed_price = ($price * $set) / 100;
			}else
			{
				$changed_price = abs($set);
			};

			if($found_minus)
			{
				return $price - $changed_price;
			};

			if($found_plus)
			{
				return $price + $changed_price;
			};





			//$final_price



				return $price;
			endif;
		}

		public function getOriginId()
		{
			return $this->helper()->config('origin');
		}

		public function getDisabledServices()
		{
			return $this->helper()->config('disablesvr');
		}

		public function getBeratTotal()
		{
			$items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();

			if(!$items){
				$items = Mage::getSingleton('adminhtml/sales_order_create')->getQuote()->getAllItems();
			}
			$totalWeight = 0;
			foreach($items as $item) {
				$totalWeight += ($item->getWeight() * $item->getQty()) ;
			}

			if($totalWeight < 1)
			$totalWeight = 1;


			return $totalWeight;
		}


		public function getMethodRates($code,$title,$name,$rates)
		{
			$method = Mage::getModel('shipping/rate_result_method');
            $method->setCarrier($this->_code);
            $method->setCarrierTitle($title);
            $method->setMethod($this->_code.$code);
            $method->setMethodTitle($name);
		    $method->setPrice($rates);

			return $method;

		}


		/**
		 * Get allowed shipping methods
		 *
		 * @return array
		 */
		public function getAllowedMethods()
		{
			return array($this->_code=>$this->getConfigData('name'));
		}
		public function isTrackingAvailable()
		{
			return true;
		}
    }

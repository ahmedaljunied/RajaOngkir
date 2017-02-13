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
				$count++;
				$method = $this->getMethodRates('_'.$count,'',$jangar_kana_hulu['text'],$jangar_kana_hulu['cost']);
				$result->append($method);
			}

			// NYANG BAROE
			// foreach ($list_euy as $key => $_rates) {
			// 	foreach ($_rates as $_rate) {
			// 		$count++;
			// 		$_option = $this->getMethodRates('_'.$count,$key,$_rate['text'],$_rate['cost']);
			// 		$result->append($_option);
			// 	}
			// }
            return $result;
        }

		public function helper($type = 'aongkir')
		{
			return Mage::helper($type);
		}

		public function getCityId()
		{
			$string_city = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getCity();


			$sql = "select * from ".Mage::getConfig()->getTablePrefix()."daftar_alamat where concat(type,' ',city_name) = '$string_city' limit 0,1 ";

			$res =  $this->helper()->fetchSql($sql);

			return $res[0]['city_id'];

		}

		public function getOriginsId()
		{
			return $this->helper()->config('origin');
		}

		public function getListRates()
		{
			$marketplace_item = $this->getItems();
			$dest = $this->getCityId();
			// $weight = $this->getBeratTotal() + 0;
			$product_count = count($marketplace_item);

			$carriers = $this->getActiveCarriers();
			$rate_list = array();
			foreach ($carriers as $kurir) {
				foreach ($marketplace_item as $seller_item) {
					$origin = $seller_item['city'];
					// Mage::log('each origin: '.Zend_Debug::dump($origin, null, false), null, 'bayu.log');
					$weight = $seller_item['weight'] + 0;
					$rates_by_kurir = $this->helper()->getSavedRates($origin,$dest,$weight,$kurir);
					// Mage::log('each call: '.Zend_Debug::dump($rates_by_kurir, null, false), null, 'bayu.log');
					foreach ($rates_by_kurir as $final_list) {
						$rate_list_2[$final_list['text']][] = array(
							'cost' 	 => $final_list['cost'],
							'weight' => $weight,
						);
					}
				}
			}
			Mage::log('rates per-item: '.Zend_Debug::dump($rate_list_2, null, false), null, 'bayu.log');
			foreach ($rate_list_2 as $key => $rates) {
				$total_weight = 0;
				$total_cost = 0;
				foreach ($rates as $rate) {
					$total_weight += $rate['weight'];
					$total_cost += $rate['cost'];
					$ratex = array(
						'text' => $key . "(" . $total_weight . "Kg)",
						'cost' => $total_cost,
					);
				}
				if (count($rates) == $product_count)
					array_push($rate_list, $ratex);
			}
			Mage::log('total rates: '.Zend_Debug::dump($rate_list, null, false), null, 'bayu.log');
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

		public function getItems()
		{
			$products = array();
			$_items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
			foreach ($_items as $_item)
			{
				$product_seller	= Mage::getModel('marketplace/product')->getCollection()
					->addFieldToFilter('mageproductid',$_item->getProduct()->getId());
				if ($product_seller)
				{
					foreach ($product_seller as $product) {

						// Mage::log('iki kotane: '.Zend_Debug::dump($_item->getProduct()->getName(), null, false), null, 'bayu.log');
						$item_address = Mage::getModel('customer/address')->load($product[product_address_id]);
						$city_text = '';
						if ($item_address) $city_text = $item_address->getCity();

						// Mage::log('iki kotane: '.Zend_Debug::dump($city_text, null, false), null, 'bayu.log');

						$sql = "select * from ".Mage::getConfig()->getTablePrefix()."daftar_alamat where concat(type,' ',city_name) = '$city_text' limit 0,1 ";
						$res = $this->helper()->fetchSql($sql);
						// Mage::log('Ada gak kotanya: '.Zend_Debug::dump($res, null, false), null, 'bayu.log');
						$city_id = $res[0]['city_id'];

						$products[] = array(
							'weight'  => $_item->getProduct()->getWeight(),
							'city' => $city_id,
						);

					}
				}
			}
			// Mage::log($products);
			// Mage::log('products: '.Zend_Debug::dump($products, null, false), null, 'bayu.log');
			return $products;
		}

		public function getOriginId_perSeller()
		{
			$product_ids = array();
	    $seller_ids = array();
	    $seller_list = array();
			$ship_addresses = array();

			$_items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
			foreach ($_items as $_item) {
				$product_seller	= Mage::getModel('marketplace/product')->getCollection()
					->addFieldToFilter('mageproductid',$_item->getProduct()->getId());

				if ($product_seller) {
					foreach ($product_seller as $sellervalue) {
						$seller_id = $sellervalue->getUserid();
						$seller_id = (string) $seller_id;
						if($seller_id)
						{
							array_push($seller_ids, $seller_id);
							if (!isset($seller_list[$seller_id]['products'])) {
								$seller_list[$seller_id]['products'] = array();
							}
							array_push($seller_list[$seller_id]['products'], $_item->getProduct()->getWeight());
						}
					}
				}
			}

			foreach ($seller_ids as $seller_id) {
				$ship_address = Mage::getModel('customer/customer')->load($seller_id)
					->getPrimaryBillingAddress();
				if ($ship_address) {
					$city_text = $ship_address->getCity();
					$sql = "select * from ".Mage::getConfig()->getTablePrefix()."daftar_alamat where concat(type,' ',city_name) = '$city_text' limit 0,1 ";
					$res = $this->helper()->fetchSql($sql);
					$seller_list[$seller_id]['city'] = $res[0]['city_id'];
				}
			}
			// Mage::log($seller_list);
			return $seller_list;
		}

		public function getDisabledServices()
		{
			return $this->helper()->config('disablesvr');
		}

		public function getBeratTotal()
		{
			$items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
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

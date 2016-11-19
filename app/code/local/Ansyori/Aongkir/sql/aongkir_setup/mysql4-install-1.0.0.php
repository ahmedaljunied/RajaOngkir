<?php
/**
 * Indonesia Shipping Carriers
 * @copyright   Copyright (c) 2015 Ansyori B.
 * @email		ansyori@gmail.com / ansyori@kemanaservices.com
 * @build_date  March 2015   
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('daftar_alamat')};
CREATE TABLE {$this->getTable('daftar_alamat')} (
  idx int(10) unsigned auto_increment NOT NULL ,
  
  `city_id` int(10) NOT NULL default '0',
  `province_id` int(10) NOT NULL default '0',
  `city_name` varchar(255) NOT NULL default '',
  `province` varchar(255) NOT NULL default '',
  `type` varchar(255) NOT NULL default '',
  `postal_code` varchar(30) NOT NULL default '',

  `last_update` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,

  PRIMARY KEY(idx)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;





   ");
/*
idx int(10) unsigned NOT NULL auto_increment,
  website_id int(11) NOT NULL default '0',
  dest_country_id varchar(4) NOT NULL default '0',
  dest_region_id int(10) NOT NULL default '0',
  dest_city varchar(30) NOT NULL default '',
  dest_zip varchar(10) NOT NULL default '',
  dest_zip_to varchar(10) NOT NULL default '',
  condition_name varchar(20) NOT NULL default '',
  condition_from_value decimal(12,4) NOT NULL default '0.0000',
  condition_to_value decimal(12,4) NOT NULL default '0.0000',
  price decimal(12,4) NOT NULL default '0.0000',
  cost decimal(12,4) NOT NULL default '0.0000',
  delivery_type varchar(255) NOT NULL default '',
*/

$installer->endSetup();



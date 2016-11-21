<?php

$installer = $this;

$installer->startSetup();

$installer->run("

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
");

$installer->endSetup();

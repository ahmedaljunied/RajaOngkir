<?php

$installer = $this;

$installer->startSetup();

//truncate table aongkir_save_rates
$installer->run("truncate table aongkir_save_rates;");

$installer->run("

INSERT IGNORE INTO `aongkir_save_rates` (`dari`, `ke`, `harga`,`kurir`, `servis`,`text`) VALUES
(152, 151, 9000, 'JNE', 'REG', 'JNE (REG) Layanan Reguler'),
(152, 152, 9000, 'JNE', 'REG', 'JNE (REG) Layanan Reguler'),
(152, 153, 9000, 'JNE', 'REG', 'JNE (REG) Layanan Reguler'),
(152, 154, 9000, 'JNE', 'REG', 'JNE (REG) Layanan Reguler'),
(152, 155, 9000, 'JNE', 'REG', 'JNE (REG) Layanan Reguler')
");

$installer->endSetup();

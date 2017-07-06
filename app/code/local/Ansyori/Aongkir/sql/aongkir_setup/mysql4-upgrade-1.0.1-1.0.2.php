<?php

$installer = $this;

$installer->startSetup();

$installer->run("

INSERT IGNORE INTO `aongkir_service_list` (`kurir`, `servis`, `service_text`) VALUES
('TIKI', 'TRC',	'TIKI (TRC) TRUCKING SERVICE');

");

$installer->endSetup();

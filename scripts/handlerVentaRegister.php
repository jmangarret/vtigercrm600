<?php
require_once 'include/utils/utils.php';
require 'include/events/include.inc';
$em = new VTEventsManager($adb);
$em->registerHandler("vtiger.entity.aftersave", "modules/RegistroDeVentas/RegistroDeVentasHandler.php", "RegistroDeVentasHandler");
echo 'Custom Handler Registered !';
?>

<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2017-01-25 22:49:05 --> Severity: Compile Error --> Cannot redeclare Inventory_model::add_development_media_item() F:\xampp\htdocs\perfex_crm\crm\application\models\Inventory_model.php 412
ERROR - 2017-01-25 22:53:36 --> Severity: Notice --> Use of undefined constant id_unity - assumed 'id_unity' F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 534
ERROR - 2017-01-25 22:53:36 --> Query error: Not unique table/alias: 'tblunities' - Invalid query: SELECT `tblreservations`.`id`, `tblreservations`.`id_development`, `id_unity`, `id_lead`, `tblreservations`.`status`, `unidad`, `nombre`, `precio`, `firstname`, `tblunities`.`id`, `id_item`, `status`, `unidad`, `m2_habitables`, `balcon`, `terraza`, `roofgarden`, `m2_totales`, `recamaras`, `banios`, `estacionamientos`, `precio`, `enganche_total`, `reservacion`, `contrato`, `num_mensualidades`, `saldo_de_enganche`, `mensualidades`, `credito`
FROM (`tblreservations`, `tblunities`)
INNER JOIN `tbldevelopments` ON `tblreservations`.`id_development` = `tbldevelopments`.`id`
LEFT JOIN `tblunities` ON `tblreservations`.`id_unity` = `tblunities`.`id`
LEFT JOIN `tbldevelopmentassessors` ON `tblreservations`.`id_development` = `tbldevelopmentassessors`.`id_development`
LEFT JOIN `tblstaff` ON `tbldevelopmentassessors`.`id_staff` = `tblstaff`.`staffid`
INNER JOIN `tbldevelopments` ON `tblunities`.`id_item` = `tbldevelopments`.`id`
WHERE `tblreservations`.`id` = '1'
AND `id_item` = '1'
ERROR - 2017-01-25 22:55:47 --> Severity: Notice --> Use of undefined constant id_unity - assumed 'id_unity' F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 534
ERROR - 2017-01-25 22:55:47 --> Query error: Not unique table/alias: 'tblunities' - Invalid query: SELECT `tblreservations`.`id`, `tblreservations`.`id_development`, `id_unity`, `id_lead`, `tblreservations`.`status`, `unidad`, `nombre`, `precio`, `firstname`, `tblunities`.`id`, `id_item`, `status`, `unidad`, `m2_habitables`, `balcon`, `terraza`, `roofgarden`, `m2_totales`, `recamaras`, `banios`, `estacionamientos`, `precio`, `enganche_total`, `reservacion`, `contrato`, `num_mensualidades`, `saldo_de_enganche`, `mensualidades`, `credito`
FROM (`tblreservations`, `tblunities`)
INNER JOIN `tbldevelopments` ON `tblreservations`.`id_development` = `tbldevelopments`.`id`
LEFT JOIN `tblunities` ON `tblreservations`.`id_unity` = `tblunities`.`id`
LEFT JOIN `tbldevelopmentassessors` ON `tblreservations`.`id_development` = `tbldevelopmentassessors`.`id_development`
LEFT JOIN `tblstaff` ON `tbldevelopmentassessors`.`id_staff` = `tblstaff`.`staffid`
INNER JOIN `tbldevelopments` ON `tblunities`.`id_item` = `tbldevelopments`.`id`
WHERE `tblreservations`.`id` = '1'
AND `id_item` = '1'
ERROR - 2017-01-25 22:55:49 --> Severity: Notice --> Use of undefined constant id_unity - assumed 'id_unity' F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 534
ERROR - 2017-01-25 22:55:49 --> Query error: Not unique table/alias: 'tblunities' - Invalid query: SELECT `tblreservations`.`id`, `tblreservations`.`id_development`, `id_unity`, `id_lead`, `tblreservations`.`status`, `unidad`, `nombre`, `precio`, `firstname`, `tblunities`.`id`, `id_item`, `status`, `unidad`, `m2_habitables`, `balcon`, `terraza`, `roofgarden`, `m2_totales`, `recamaras`, `banios`, `estacionamientos`, `precio`, `enganche_total`, `reservacion`, `contrato`, `num_mensualidades`, `saldo_de_enganche`, `mensualidades`, `credito`
FROM (`tblreservations`, `tblunities`)
INNER JOIN `tbldevelopments` ON `tblreservations`.`id_development` = `tbldevelopments`.`id`
LEFT JOIN `tblunities` ON `tblreservations`.`id_unity` = `tblunities`.`id`
LEFT JOIN `tbldevelopmentassessors` ON `tblreservations`.`id_development` = `tbldevelopmentassessors`.`id_development`
LEFT JOIN `tblstaff` ON `tbldevelopmentassessors`.`id_staff` = `tblstaff`.`staffid`
INNER JOIN `tbldevelopments` ON `tblunities`.`id_item` = `tbldevelopments`.`id`
WHERE `tblreservations`.`id` = '1'
AND `id_item` = '1'
ERROR - 2017-01-25 22:55:52 --> Severity: Notice --> Use of undefined constant id_unity - assumed 'id_unity' F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 534
ERROR - 2017-01-25 22:55:52 --> Query error: Not unique table/alias: 'tblunities' - Invalid query: SELECT `tblreservations`.`id`, `tblreservations`.`id_development`, `id_unity`, `id_lead`, `tblreservations`.`status`, `unidad`, `nombre`, `precio`, `firstname`, `tblunities`.`id`, `id_item`, `status`, `unidad`, `m2_habitables`, `balcon`, `terraza`, `roofgarden`, `m2_totales`, `recamaras`, `banios`, `estacionamientos`, `precio`, `enganche_total`, `reservacion`, `contrato`, `num_mensualidades`, `saldo_de_enganche`, `mensualidades`, `credito`
FROM (`tblreservations`, `tblunities`)
INNER JOIN `tbldevelopments` ON `tblreservations`.`id_development` = `tbldevelopments`.`id`
LEFT JOIN `tblunities` ON `tblreservations`.`id_unity` = `tblunities`.`id`
LEFT JOIN `tbldevelopmentassessors` ON `tblreservations`.`id_development` = `tbldevelopmentassessors`.`id_development`
LEFT JOIN `tblstaff` ON `tbldevelopmentassessors`.`id_staff` = `tblstaff`.`staffid`
INNER JOIN `tbldevelopments` ON `tblunities`.`id_item` = `tbldevelopments`.`id`
WHERE `tblreservations`.`id` = '1'
AND `id_item` = '1'
ERROR - 2017-01-25 22:55:54 --> Severity: Notice --> Use of undefined constant id_unity - assumed 'id_unity' F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 534
ERROR - 2017-01-25 22:55:54 --> Query error: Not unique table/alias: 'tblunities' - Invalid query: SELECT `tblreservations`.`id`, `tblreservations`.`id_development`, `id_unity`, `id_lead`, `tblreservations`.`status`, `unidad`, `nombre`, `precio`, `firstname`, `tblunities`.`id`, `id_item`, `status`, `unidad`, `m2_habitables`, `balcon`, `terraza`, `roofgarden`, `m2_totales`, `recamaras`, `banios`, `estacionamientos`, `precio`, `enganche_total`, `reservacion`, `contrato`, `num_mensualidades`, `saldo_de_enganche`, `mensualidades`, `credito`
FROM (`tblreservations`, `tblunities`)
INNER JOIN `tbldevelopments` ON `tblreservations`.`id_development` = `tbldevelopments`.`id`
LEFT JOIN `tblunities` ON `tblreservations`.`id_unity` = `tblunities`.`id`
LEFT JOIN `tbldevelopmentassessors` ON `tblreservations`.`id_development` = `tbldevelopmentassessors`.`id_development`
LEFT JOIN `tblstaff` ON `tbldevelopmentassessors`.`id_staff` = `tblstaff`.`staffid`
INNER JOIN `tbldevelopments` ON `tblunities`.`id_item` = `tbldevelopments`.`id`
WHERE `tblreservations`.`id` = '1'
AND `id_item` = '1'
ERROR - 2017-01-25 22:55:58 --> Severity: Notice --> Use of undefined constant id_unity - assumed 'id_unity' F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 534
ERROR - 2017-01-25 22:55:58 --> Query error: Not unique table/alias: 'tblunities' - Invalid query: SELECT `tblreservations`.`id`, `tblreservations`.`id_development`, `id_unity`, `id_lead`, `tblreservations`.`status`, `unidad`, `nombre`, `precio`, `firstname`, `tblunities`.`id`, `id_item`, `status`, `unidad`, `m2_habitables`, `balcon`, `terraza`, `roofgarden`, `m2_totales`, `recamaras`, `banios`, `estacionamientos`, `precio`, `enganche_total`, `reservacion`, `contrato`, `num_mensualidades`, `saldo_de_enganche`, `mensualidades`, `credito`
FROM (`tblreservations`, `tblunities`)
INNER JOIN `tbldevelopments` ON `tblreservations`.`id_development` = `tbldevelopments`.`id`
LEFT JOIN `tblunities` ON `tblreservations`.`id_unity` = `tblunities`.`id`
LEFT JOIN `tbldevelopmentassessors` ON `tblreservations`.`id_development` = `tbldevelopmentassessors`.`id_development`
LEFT JOIN `tblstaff` ON `tbldevelopmentassessors`.`id_staff` = `tblstaff`.`staffid`
INNER JOIN `tbldevelopments` ON `tblunities`.`id_item` = `tbldevelopments`.`id`
WHERE `tblreservations`.`id` = '1'
AND `id_item` = '1'
ERROR - 2017-01-25 23:00:03 --> Severity: Notice --> Use of undefined constant id_unity - assumed 'id_unity' F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 534
ERROR - 2017-01-25 23:00:03 --> Query error: Not unique table/alias: 'tblunities' - Invalid query: SELECT `tblreservations`.`id`, `tblreservations`.`id_development`, `id_unity`, `id_lead`, `tblreservations`.`status`, `unidad`, `nombre`, `precio`, `firstname`, `tblunities`.`id`, `id_item`, `status`, `unidad`, `m2_habitables`, `balcon`, `terraza`, `roofgarden`, `m2_totales`, `recamaras`, `banios`, `estacionamientos`, `precio`, `enganche_total`, `reservacion`, `contrato`, `num_mensualidades`, `saldo_de_enganche`, `mensualidades`, `credito`
FROM (`tblreservations`, `tblunities`)
INNER JOIN `tbldevelopments` ON `tblreservations`.`id_development` = `tbldevelopments`.`id`
LEFT JOIN `tblunities` ON `tblreservations`.`id_unity` = `tblunities`.`id`
LEFT JOIN `tbldevelopmentassessors` ON `tblreservations`.`id_development` = `tbldevelopmentassessors`.`id_development`
LEFT JOIN `tblstaff` ON `tbldevelopmentassessors`.`id_staff` = `tblstaff`.`staffid`
INNER JOIN `tbldevelopments` ON `tblunities`.`id_item` = `tbldevelopments`.`id`
WHERE `tblreservations`.`id` = '1'
AND `id_item` = '1'
ERROR - 2017-01-25 23:00:47 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT `tblreservations`.`id`, `tblreservations`.`id_development`, `id_unity`, `id_lead`, `tblreservations`.`status`, `unidad`, `nombre`, `precio`, `firstname`, `id`, `url`, `name`, `id_type`
FROM (`tblreservations`, `tblmediaitems`)
INNER JOIN `tbldevelopments` ON `tblreservations`.`id_development` = `tbldevelopments`.`id`
LEFT JOIN `tblunities` ON `tblreservations`.`id_unity` = `tblunities`.`id`
LEFT JOIN `tbldevelopmentassessors` ON `tblreservations`.`id_development` = `tbldevelopmentassessors`.`id_development`
LEFT JOIN `tblstaff` ON `tbldevelopmentassessors`.`id_staff` = `tblstaff`.`staffid`
INNER JOIN `tblreservationsdocs` ON `tblmediaitems`.`id` = `tblreservationsdocs`.`id_media_item`
WHERE `tblreservations`.`id` = '1'
AND `id_reservation` = '1'
ERROR - 2017-01-25 23:05:22 --> Severity: Notice --> Use of undefined constant id_unity - assumed 'id_unity' F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 534
ERROR - 2017-01-25 23:05:22 --> Severity: Notice --> Array to string conversion F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 534
ERROR - 2017-01-25 23:05:41 --> Severity: Notice --> Use of undefined constant id_unity - assumed 'id_unity' F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 534
ERROR - 2017-01-25 23:05:41 --> Severity: Notice --> Array to string conversion F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 534
ERROR - 2017-01-25 23:09:41 --> Severity: Notice --> Trying to get property of non-object F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 535
ERROR - 2017-01-25 23:09:53 --> Severity: Notice --> Trying to get property of non-object F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 535
ERROR - 2017-01-25 23:10:16 --> Severity: Notice --> Trying to get property of non-object F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 535
ERROR - 2017-01-25 23:10:26 --> Severity: Notice --> Trying to get property of non-object F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 535
ERROR - 2017-01-25 23:11:08 --> Severity: Notice --> Trying to get property of non-object F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 535
ERROR - 2017-01-25 23:16:06 --> Severity: Notice --> Trying to get property of non-object F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 539
ERROR - 2017-01-25 23:17:16 --> Severity: Notice --> Trying to get property of non-object F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 539
ERROR - 2017-01-25 23:17:42 --> Severity: Notice --> Undefined index: id_development F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 539
ERROR - 2017-01-25 23:38:35 --> Severity: Warning --> move_uploaded_file(/home3/rafaq5/public_html/auri/crm/uploads/inventory/Departamento_301_ficha_pdf.pdf): failed to open stream: No such file or directory F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 235
ERROR - 2017-01-25 23:38:35 --> Severity: Warning --> move_uploaded_file(): Unable to move 'F:\xampp\tmp\phpCC12.tmp' to '/home3/rafaq5/public_html/auri/crm/uploads/inventory/Departamento_301_ficha_pdf.pdf' F:\xampp\htdocs\perfex_crm\crm\application\controllers\admin\Inventory.php 235

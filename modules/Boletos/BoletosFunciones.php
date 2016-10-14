<?php
//include_once('../../config.inc.php');   
include_once('config.inc.php');   
$con = mysql_connect($dbconfig['db_server'],$dbconfig['db_username'],$dbconfig['db_password']);
$db  = mysql_select_db($dbconfig['db_name']);
function esVentaSoto($ventaid){
	global $adb;
	$sqlSoto="SELECT COUNT(*) FROM vtiger_registrodeventas WHERE registrodeventasid=? AND registrodeventastype= ?";
	$result = $adb->pquery($sqlSoto, array($ventaid,"Boleto SOTO"));	
	$row = $adb->fetch_row($result);
	$esSoto=$row[0];
	return $esSoto;
}
function getVentaGds($ventaid){
	global $adb;
	$sqlSoto="SELECT gds FROM vtiger_localizadores WHERE registrodeventasid=?";
	$result = $adb->pquery($sqlSoto, array($ventaid));	
	$row = $adb->fetch_row($result);
	$gds=$row[0];
	return $gds;
}
function getCantPagos($ventaid){
	global $adb;
	$sqlSoto="SELECT COUNT(*) FROM vtiger_registrodepagos WHERE registrodeventasid=?";
	$result = $adb->pquery($sqlSoto, array($ventaid));	
	$row = $adb->fetch_row($result);
	$cant=$row[0];
	return $cant;
}
function getCantBoletos($locid){
	global $adb;
	$sqlSoto="SELECT COUNT(*) FROM vtiger_boletos WHERE localizadorid=?";
	$result = $adb->pquery($sqlSoto, array($locid));	
	$row = $adb->fetch_row($result);
	$cant=$row[0];
	return $cant;
}
function validarPasaportes($locid){
	global $adb;
	$cont=0;
	$sqlBol ="SELECT * FROM vtiger_boletos  ";
	$sqlBol.="INNER JOIN vtiger_crmentity ON vtiger_boletos.boletosid = vtiger_crmentity.crmid ";
	$sqlBol.=" WHERE vtiger_crmentity.deleted=0 AND vtiger_boletos.localizadorid = ?";
	$resBol = $adb->pquery($sqlSoto, array($locid));	
	while ($rowBol = $adb->fetch_array($resBol)){
		$boletosid=$rowBol["boletosid"];
		$sql = "SELECT attachmentsid FROM vtiger_attachments
					INNER JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
					INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_attachments.attachmentsid
					WHERE vtiger_crmentity.setype = 'Boletos Attachment' and vtiger_seattachmentsrel.crmid = ?
					ORDER BY vtiger_attachments.attachmentsid DESC";
		$result = $adb->pquery($sqlSoto, array($boletosid));	
		$numrows = $adb->num_rows($result);	
		//El query puede devolver muchos registros de todas las veces que se le haya adjuntado pasaporte	
		if ($numrows>0){
			$cont++;
		}
	}	
	return $cont;
}
function getFormaDePago($locid){
	global $adb;
	$sqlFp="SELECT paymentmethod FROM vtiger_localizadores WHERE localizadoresid=?";
	$result = $adb->pquery($sqlFp, array($locid));	
	$row = $adb->fetch_row($result);
	$fp=$row[0];
	return $fp;
}
function getLocGds($locid){
	global $adb;
	$sqlSoto="SELECT gds FROM vtiger_localizadores WHERE localizadoresid=$locid";
	$result=mysql_query($sqlSoto);
	$row=mysql_fetch_row($result);
	$gds=$row[0];
	return $gds;
}
function getLocId($idrel,$module){
	global $adb;
	if ($module=="RegistroDeVentas"){
		$sqlLoc="SELECT localizadoresid FROM vtiger_localizadores WHERE gds='Servi' AND registrodeventasid=$idrel";
		$result=mysql_query($sqlLoc);
		$row=mysql_fetch_row($result);
		$idloc=$row[0];	
	}
	if ($module=="Localizadores"){
		$sqlLoc="SELECT localizadoresid FROM vtiger_localizadores WHERE localizador='$idrel'";
		$result=mysql_query($sqlLoc);
		$row=mysql_fetch_row($result);
		$idloc=$row[0];	
	}		
	if ($module=="Boletos"){
		$sqlLoc="SELECT localizadorid FROM vtiger_boletos WHERE boletosid=$idrel";
		$result=mysql_query($sqlLoc);
		$row=mysql_fetch_row($result);
		$idloc=$row[0];	
	}	
	return $idloc;
}
function setStatusSoto($ventaid,$status){
	global $adb;
	$sqlVentaUpdate="UPDATE vtiger_registrodeventas SET statussoto=? WHERE registrodeventasid=?";
	$result = $adb->pquery($sqlVentaUpdate, array($status,$ventaid));	
	$update=$adb->getAffectedRowCount($result);
	if ($update)
		return true;
	else
		return false;
}
function getPagosVerificados($ventaid){
	global $adb;
	$cont=0;
	$sqlpag ="SELECT registrodepagosid, pagostatus FROM vtiger_registrodepagos as r ";
	$sqlpag.="INNER JOIN vtiger_crmentity as e ON r.registrodepagosid=e.crmid ";
	$sqlpag.="WHERE e.deleted=0 AND registrodeventasid=? ";
	$qrypag=$adb->pquery($sqlpag, array($ventaid));	
	while ($rowpag=$adb->fetch_row($qrypag)) {
		$pagoid=$rowpag[0];
		$status=$rowpag[1];
		if ($status=='Verificado'){
			$cont++;
		}else{
			$cont--;
		}							
	}	
	if ($cont>0)
		return true;
	else
		return false;
}

function getPlantillaEmitirSoto($ventaid, $label){
	$host= $_SERVER["HTTP_HOST"];
	$mensaje=" 
	<html>
	<head> 
	<title>Info - Tu Agencia 24</title> 
	</head> 
	<body> 
	<p>Hola, </p>
	<p>Se han verificado los pagos para proceder a la <b>Emisión de SOTO</b>:</p>
	<p><b>Registro de Venta: </b> <a href='http://".$host."/index.php?module=RegistroDeVentas&view=Detail&record=".$ventaid."'>".$label."</a></p>		
	<BR><BR>
	<i>
	Gracias,		
	<p>Equipo TuAgencia24.com</p>
	</i>
	</body> 
	</html> "; 
	return $mensaje;
}
function getPlantillaVerificarPagos($ventaid, $label){
	$host= $_SERVER["HTTP_HOST"];
	$mensaje=" 
	<html>
	<head> 
	<title>Info - Tu Agencia 24</title> 
	</head> 
	<body> 
	<p>Hola, </p>
	<p>Se han verificado los datos del pasajero para proceder a <b>Verificar los Pagos</b>:</p>
	<p><b>Registro de Venta: </b> <a href='http://".$host."/index.php?module=RegistroDeVentas&view=Detail&record=".$ventaid."'>".$label."</a></p>		
	<BR><BR>
	<i>
	Gracias,		
	<p>Equipo TuAgencia24.com</p>
	</i>
	</body> 
	</html> "; 
	return $mensaje;
}
function getPlantillaVerificarDatos($locid, $label){
	$host= $_SERVER["HTTP_HOST"];
	$mensaje = " 
	<html>
	<head> 
	<title>Info - Tu Agencia 24</title> 
	</head> 
	<body> 
	<p>Hola, </p>
	<p>Se ha registrado una Reserva de SOTO para la Verificacion de Datos:</p>					
	<p><b>Localizador: </b> <a href='http://".$host."/index.php?module=Localizadores&view=Detail&record=".$locid."'>".$label."</a></p>		
	<BR><BR><BR>
	<i>
	Gracias,		
	<p>Equipo TuAgencia24.com</p>
	</i>
	</body> 
	</html> "; 	
	return $mensaje;
}
function getPlantillaEmitido($ventaid, $label){
	$host= $_SERVER["HTTP_HOST"];
	$mensaje = " 
	<html>
	<head> 
	<title>Info - Tu Agencia 24</title> 
	</head> 
	<body> 
	<p>Hola, </p>
	<p>Se ha completado la emisión SOTO. Por favor complete los Numeros de Boletos de siguiente Registro de Venta:</p>					
	<p><b>Registro de Venta: </b> <a href='http://".$host."/index.php?module=RegistroDeVentas&view=Detail&record=".$ventaid."'>".$label."</a></p>		
	<BR><BR><BR>
	<i>
	Gracias,		
	<p>Equipo TuAgencia24.com</p>
	</i>
	</body> 
	</html> "; 	
	return $mensaje;
}

?>
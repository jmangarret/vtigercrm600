<?php
include_once("../../config.inc.php");
$user=$dbconfig['db_username'];
$pass=$dbconfig['db_password'];
$bd=$dbconfig['db_name'];
mysql_connect("localhost",$user,$pass);
mysql_select_db($bd);

$id=$_REQUEST["id"];
$output_dir=$_REQUEST["ruta"];
$user=$_REQUEST["user"];
$fecha=date("Y-m-d H:i:s");

if(isset($_FILES["myfile"]))
{
	$ret = array();
	
	$error =$_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
 	 	$fileName = $_FILES["myfile"]["name"];
 	 	$fileName=$id."_".$fileName;
 		copy($_FILES["myfile"]["tmp_name"],"../../".$output_dir.$fileName);
    	$ret[]= $fileName;
	}
	else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES["myfile"]["name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {
	  	$fileName = $_FILES["myfile"]["name"][$i];
		move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],"../../".$output_dir.$fileName);
	  	$ret[]= $fileName;
	  }
	
	}
	
	$filetype =$_FILES["myfile"]["type"];

	$sql="SELECT passenger FROM vtiger_boletos WHERE boletosid=".$id;
	$qry=mysql_query($sql);
	$pasajero=mysql_result($qry, 0);

	$IdCrm=mysql_query("CALL getCrmId();");
	$IdCrm=mysql_query("SELECT @idcrm;");
	$resultIdCrm=mysql_fetch_row($IdCrm);
	$crmId=$resultIdCrm[0];

	$sqlSetCrm="CALL setCrmEntity('Boletos Attachment','$pasajero','$fecha',$crmId,$user)";
	$setCrm=mysql_query($sqlSetCrm);

	$sql2 ="insert into vtiger_attachments(attachmentsid, name, description, type, path) ";
	$sql2.="values($crmId, '$fileName', NULL, '$filetype', '$output_dir')";
	mysql_query($sql2);

	//si existe
	$delquery = "delete from vtiger_seattachmentsrel where crmid = $id and attachmentsid = $crmId";
	mysql_query($delquery);
	
	$sql3 = "insert into vtiger_seattachmentsrel values($id, $crmId)";
	mysql_query($sql3);

	chmod($fileName, 755);
	
    echo json_encode($ret);
 }
 ?>
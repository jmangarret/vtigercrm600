<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reporte osTickets</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
include("librerias.php");
?>
</head>
<body>	
	<div id="container">
	<table border=1>
	<form>
		<tr><td><b>Fecha: </b><td><input id="date1" type="text" name="desde">
		
		<tr><th colspan="2">
			<input type="submit" Value="Consultar" id="submit"> 
			
 			<script type="text/javascript">
            $(document).ready(function () {                
                $('#date1').datepicker({
                    format: "dd/mm/yyyy"
                });  
            	
                $('#submit').click(function() {		    		
		        	$.ajax({
					method: "GET",
					url: "reportes/genTicketsPorStatus.php",
					type : 'GET',
					dataType:"html",
					data: {"desde":$('#date1').val()},
					success: function(response){     
					     $('#genreport').html(response);
					  	}
					});									        	      
	    		});    


            });
        	</script>
	</form>
	</table>	
	</div>
	<?php
		include("genTicketsPorStatus.php");
	?>
</body>
</html>

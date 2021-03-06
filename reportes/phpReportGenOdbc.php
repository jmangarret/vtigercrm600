<?

/*******************************************************************************************
*
* Class Name : phpReportGeneratorOdbc
* Version    : 1.0
* Written By : Hasin Hayder
* Start Date : 4th July, 2004
* Copyright  : Systech Digital. 
*
********************************************************************************************
*
* Script to generate report from a valid my sql connection.
* user have to supply which fields he want to display in table.
* All properties are changable.
*
*/

class phpReportGeneratorOdbc
{
	var $odbc_resource;
	var $header;
	var $foolter;
        var $fields = array();
	var $cellpad;
	var $cellspace;
	var $border;
	var $width;
	var $modified_width;
	var $header_color;
	var $header_textcolor;
	var $header_alignment;
	var $body_color;
	var $body_textcolor;
	var $body_alignment;
	var $surrounded;
	
	function generateReport()
	{
		$this->border = (empty($this->border))?"0":$this->border;
		$this->cellpad = (empty($this->cellpad))?"1":$this->cellpad;
		$this->cellspace = (empty($this->cellspace))?"0":$this->cellspace;
		$this->width = (empty($this->width))?"100%":$this->width;
		$this->header_color = (empty($this->header_color))?"#FFFFFF":$this->header_color;
		$this->header_textcolor = (empty($this->header_textcolor))?"#000000":$this->header_textcolor;		
		$this->header_alignment = (empty($this->header_alignment))?"left":$this->header_alignment;
		$this->body_color = (empty($this->body_color))?"#FFFFFF":$this->body_color;
		$this->body_textcolor = (empty($this->body_textcolor))?"#000000":$this->body_textcolor;
		$this->body_alignment = (empty($this->body_alignment))?"left":$this->body_alignment;
		$this->surrounded = (empty($this->surrounded))?false:true;
		$this->modified_width = ($this->surrounded==true)?"100%":$this->width;
		
		//echo "modified_width : ".$this->modified_width."<br>"; 
		
		if (!is_resource($this->odbc_resource))
			die ("User doesn't supply any valid odbc resource after executing query result");

		/*
		* Lets calculate how many fields are there in supplied resource
		* and store their name in $this->fields[] array
		*/
		
		$field_count = odbc_num_fields($this->odbc_resource);
		$i = 0;
		
		while ($i < $field_count)
		{
			$field = odbc_fetch_field($this->odbc_resource);
			$this->fields[$i] = $field->name;
			$this->fields[$i][0] = strtoupper($this->fields[$i][0]);
			$i++;
		}
		
		
		/*
		* Now start table generation
		* We must draw this table according to number of fields
		*/
		
		echo "<b><i>".$this->header."</i></b>";
		echo "<P></P>";
		
		//Check If our table has to be surrounded by an additional table
		//which increase style of this table
		if ($this->surrounded == true) 
			echo "<table width='$this->width'  border='1' cellspacing='0' cellpadding='0'><tr><td>";
			
		echo "<table width='$this->modified_width'  border='$this->border' cellspacing='$this->cellspace' cellpadding='$this->cellpad'>";
		echo "<tr bgcolor = '$this->header_color'>";
		
		//Header Draw
		for ($i = 0; $i< $field_count; $i++)
		{
			//Now Draw Headers
			echo "<th align = '$this->header_alignment'><font color = '$this->header_textcolor'>&nbsp;".$this->fields[$i]."</font></th>";
		}

		echo "</tr>";
		
		//Now fill the table with data
		while ($rows = odbc_fetch_row($this->odbc_resource))
		{
			echo "<tr align = '$this->body_alignment' bgcolor = '$this->body_color'>";
			for ($i = 0; $i < $field_count; $i++)
			{
				//Now Draw Data
				echo "<td><font color = '$this->body_textcolor'>&nbsp;".$rows[$i]."</font></td>";
			}
			echo "</tr>";
		}
		echo "</table>";
		
		if ($this->surrounded == true) 
			echo "</td></tr></table>";

		
		
	}
}

?>

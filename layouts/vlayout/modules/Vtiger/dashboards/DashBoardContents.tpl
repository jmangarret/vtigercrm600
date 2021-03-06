{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
{strip}
<script>
$(document).ready(function() {     
	
    $('#linkTicketSatelites').click(function() {
    		$('#report').html("<img src='themes/images/loading.gif'>");
        	$.ajax({
			method: "GET",
			url: "reportes/rptVentasSatelites.php",
			type : "GET",
			dataType:"html",
			data: {},
			success: function(response){     
			     $('#report').html(response);
			  	}
			});									        	      
    });    

    $('#linkOsTickets').click(function() {
    		$('#report').html("<img src='themes/images/loading.gif'>");
        	$.ajax({
			method: "GET",
			url: "reportes/rptTicketsPorStatus.php",
			type : 'GET',
			dataType:"html",
			data: { },
			success: function(response){     
			     $('#report').html(response);
			  	}
			});									        	      
    });    

    $('#linkProximosVuelos').click(function() {
    		$('#report').html("<img src='themes/images/loading.gif'>");
        	$.ajax({
			method: "GET",
			url: "reportes/rptProximosVuelos.php",
			type : 'GET',
			dataType:"html",
			data: { },
			success: function(response){     
			     $('#report').html(response);
			  	}
			});									        	      
    }); 

    $('#linkErroresSoto').click(function() {
    		$('#report').html("<img src='themes/images/loading.gif'>");
        	$.ajax({
			method: "GET",
			url: "reportes/rptDatosSoto.php",
			type : 'GET',
			dataType:"html",
			data: { },
			success: function(response){     
			     $('#report').html(response);
			  	}
			});									        	      
    });   


});
</script>

<div align="center">	
	<h3>
		<!--<a href="#" id="linkTicketSatelites">Tickets Satelites</a> |-->
		<a href="reportes/rptVentasSatelites.php">Tickets Satelites</a> |
		<a href="#" id="linkOsTickets">Reporte osTickets</a> |
		<a href="#" id="linkProximosVuelos">Próximos Vuelos</a> |
		<a href="#" id="linkErroresSoto">Errores SOTO</a> 
	</h3>
</div>
<div id="report" class="gridster span" style="width: 98%;" align="center">
	<ul>
	{assign var=COLUMNS value=2}
	{assign var=ROW value=1}
	{foreach from=$WIDGETS item=WIDGET name=count}
		{assign var=WIDGETDOMID value=$WIDGET->get('linkid')}
		{if $WIDGET->getName() eq 'MiniList'}
			{assign var=WIDGETDOMID value=$WIDGET->get('linkid')|cat:'-':$WIDGET->get('widgetid')}
		{elseif $WIDGET->getName() eq 'Notebook'}
			{assign var=WIDGETDOMID value=$WIDGET->get('linkid')|cat:'-':$WIDGET->get('widgetid')}
		{/if}
		<li id="{$WIDGETDOMID}" {if $smarty.foreach.count.index % $COLUMNS == 0 and $smarty.foreach.count.index != 0} {assign var=ROWCOUNT value=$ROW+1} data-row="{$WIDGET->getPositionRow($ROWCOUNT)}" {else} data-row="{$WIDGET->getPositionRow($ROW)}" {/if}
			{assign var=COLCOUNT value=($smarty.foreach.count.index % $COLUMNS)+1} data-col="{$WIDGET->getPositionCol($COLCOUNT)}" data-sizex="{$WIDGET->getWidth()}" data-sizey="{$WIDGET->getHeight()}"
			class="dashboardWidget dashboardWidget_{$smarty.foreach.count.index}" data-url="{$WIDGET->getUrl()}" data-mode="open" data-name="{$WIDGET->getName()}">
		</li>
	{/foreach}
	</ul>
	<input type="hidden" id=row value="{$ROWCOUNT}" />
	<input type="hidden" id=col value="{$COLCOUNT}" />
	<input type="hidden" id="userDateFormat" value="{$CURRENT_USER->get('date_format')}" />
</div>

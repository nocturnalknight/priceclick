<html>
	<head>
		<title>Markets</title>
	    <link class="include" rel="stylesheet" type="text/css" href="jquery.jqplot.min.css" />
	    <script class="include" type="text/javascript" src="jquery.min.js"></script>   
	</head>
	<body>
		<?php
		include('config.php');
		
		mysql_connect(DB_HOST, DB_USER, DB_PASS) or die (mysql_error ());
		mysql_select_db(ODDSCHECK_DB_NAME) or die(mysql_error());
		
		$fmfdid = $_REQUEST['fmfdid'];
		$spid = $_REQUEST['spid'];
		$condition = ''; 
		
		if($spid > 0){
			$condition .= " AND pcl.sportid = ".$spid;
			$query_get_sportsname = mysql_query("SELECT Sport_name FROM EO_OddsCheck.SportMaster WHERE Sport_id = ".$spid);
			$sportinfo = mysql_fetch_array($query_get_sportsname);
			$sportsname = " Sports : ".$sportinfo['Sport_name'];
		}
		if($fmfdid > 0){
			$condition .= " AND pcl.bookmaker = ".$fmfdid;
			$query_get_providername = mysql_query("SELECT feed_name FROM EO_OddsCheck.BPartners WHERE fm_fd_id = ".$fmfdid);
			$proinfo = mysql_fetch_array($query_get_providername);
			$providername = " & Provider : ".$proinfo['feed_name'];
		}
		
		if($fmfdid > 0 || $spid > 0){
			$provider_sport_name = " - (".$sportsname.$providername.")";
		}
		?>
		<h2 style="width:500px;text-align:center;margin:100px auto 0px auto;">By Markets <?php echo $provider_sport_name; ?><h2>
		<div id="chart2" class="example-chart" style="height:400px;width:600px;margin:50px auto;"></div>
		<?php		
		
		
		$query_get_availrasports = mysql_query("SELECT COUNT(id) as COUNT,pcl.marketid,md.market_name FROM easyodds_beta_new.EOB2B_price_click_log AS pcl
			JOIN Market_Data.Dt2_Md_Market AS md ON md.o_md_market_id = pcl.marketid
			WHERE 1=1".$condition." GROUP BY pcl.marketid");
	
		$result =  "[";
		
		if(mysql_num_rows($query_get_availrasports) > 0){
			while($datas = mysql_fetch_array($query_get_availrasports)){
				$resultarray[] = "['".$datas['market_name']."',".$datas['COUNT'].",".$datas['marketid']."]";
			}
		}
		
		$newresult = implode(",",$resultarray);
		$result .= $newresult."]";
		
		?>
	</body>

	<script class="include" type="text/javascript" src="jquery.jqplot.min.js"></script>
    <script language="javascript" type="text/javascript" src="plugins/jqplot.categoryAxisRenderer.min.js"></script>
    <script language="javascript" type="text/javascript" src="plugins/jqplot.barRenderer.min.js"></script>
	<script type="text/javascript" class="code">
	$(document).ready(function(){
	    var line1 = <?php echo $result; ?>;
	
	    $('#chart2').jqplot([line1], {
	        title:'Markets',
	        seriesDefaults:{
	            renderer:$.jqplot.BarRenderer,
	            rendererOptions: {
					
	                // Set the varyBarColor option to true to use different colors for each bar.
	                // The default series colors are used.
	                varyBarColor: true
	            }
	        },
	        axes:{
	            xaxis:{
	                renderer: $.jqplot.CategoryAxisRenderer
	            }
	        }
	    });
		
$("#chart2").bind('jqplotDataHighlight', function(ev, seriesIndex, pointIndex, line1) {
	var $this = $(this);
	$this.attr('title', line1[0] + ": " + line1[1]);                               
});
		
	});
	</script>
</html>

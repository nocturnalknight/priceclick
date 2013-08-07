<html>
	<head>
	    <title>Sports</title>
	    <link class="include" rel="stylesheet" type="text/css" href="jquery.jqplot.min.css" />
	    <script class="include" type="text/javascript" src="jquery.min.js"></script>   
	</head>
	<body>
		<?php
		
		include('config.php');
		
		mysql_connect(DB_HOST, DB_USER, DB_PASS) or die (mysql_error ());
		mysql_select_db(ODDSCHECK_DB_NAME) or die(mysql_error());
		
		$fmfdid = $_REQUEST['fmfdid'];
		$condition = '';
			
		if($fmfdid > 0){
			$condition .= " AND pcl.bookmaker = ".$fmfdid;
			$query_get_providername = mysql_query("SELECT feed_name FROM EO_OddsCheck.BPartners WHERE fm_fd_id = ".$fmfdid);
			$proinfo = mysql_fetch_array($query_get_providername);
			$providername = " -(Provider : ".$proinfo['feed_name'].")";
		}		
		
		?>
		<h2 style="width:500px;text-align:center;margin:100px auto 0px auto;">By Sports <?php echo $providername; ?></h2>
		<div id="chart1" style="height:400px; width:600px;margin:50px auto;"></div>
		<?php		 
		
		$query_get_availrasports = mysql_query("
			SELECT COUNT(pcl.id) AS COUNT,pcl.sportid,sp.Sport_name
			FROM easyodds_beta_new.EOB2B_price_click_log AS pcl
			JOIN EO_OddsCheck.SportMaster AS sp ON pcl.sportid = sp.Sport_id
			WHERE 1=1".$condition." GROUP BY pcl.sportid");
	
		$result =  "[";
		
		if(mysql_num_rows($query_get_availrasports) > 0){
			while($datas = mysql_fetch_array($query_get_availrasports)){
				$resultarray[] = "['".$datas['Sport_name']."',".$datas['COUNT'].",".$datas['sportid'].",".$fmfdid."]";
			}
		}
		
		$newresult = implode(",",$resultarray);
		$result .= $newresult."]";
		
		?>
	</body>
	
	<script class="include" type="text/javascript" src="jquery.jqplot.min.js"></script>
    <script class="include" language="javascript" type="text/javascript" src="plugins/jqplot.pieRenderer.min.js"></script>
	<script class="code" type="text/javascript">
		$(document).ready(function(){
		  /*var data = [
		    ['Heavy Industry', 12],['Retail', 9], ['Light Industry', 14], 
		    ['Out of home', 16],['Commuting', 7], ['Orientation', 9]
		  ];*/
		  var data = <?php echo $result; ?>;
		   
		  var plot1 = jQuery.jqplot ('chart1', [data], 
		    { 
		      seriesDefaults: {
		        // Make this a pie chart.
		        renderer: jQuery.jqplot.PieRenderer, 
		        rendererOptions: {
		          // Put data labels on the pie slices.
		          // By default, labels show the percentage of the slice.
		          showDataLabels: true
		        }
		      }, 
		      legend: { show:true, location: 'e' }
		    }
		  );
		});

		$('#chart1').bind('jqplotDataClick',
            function (ev, seriesIndex, pointIndex, data) {                			   
                var spid = data[2];
				var fmfdid = (data[3] > 0)?data[3]:0;
                window.location.href="market.php?spid="+spid+"&fmfdid="+fmfdid;
            }
        );
		
$("#chart1").bind('jqplotDataHighlight', function(ev, seriesIndex, pointIndex, data) {
	var $this = $(this);
	$this.attr('title', data[0] + ": " + data[1]);                               
}); 
		
	</script>
</html>

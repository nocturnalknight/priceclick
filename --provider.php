<html>
	<head>
	    <title>Providers</title>
	    <link class="include" rel="stylesheet" type="text/css" href="jquery.jqplot.min.css" />
	    <script class="include" type="text/javascript" src="jquery.min.js"></script>   
	</head>
	<body>
		<h2 style="width:500px;text-align:center;margin:100px auto 0px auto;">By Provider</h2>
		<div id="chart1" style="height:400px; width:600px; margin:50px auto;"></div>
		<?php
		include('config.php');
		
		mysql_connect(DB_HOST, DB_USER, DB_PASS) or die (mysql_error ());
		mysql_select_db(ODDSCHECK_DB_NAME) or die(mysql_error());
		
		$query_get_availproviders = mysql_query("SELECT COUNT(pcl.id) AS COUNT,pcl.bookmaker,bp.feed_name
		FROM easyodds_beta_new.EOB2B_price_click_log AS pcl
		JOIN EO_OddsCheck.BPartners AS bp ON bp.fm_fd_id = pcl.bookmaker GROUP BY pcl.bookmaker");
	
		$result =  "[";
		
		if(mysql_num_rows($query_get_availproviders) > 0){
			while($datas = mysql_fetch_array($query_get_availproviders)){
				$resultarray[] = "['".$datas['feed_name']."',".$datas['COUNT'].",".$datas['bookmaker']."]";
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
                var id = data[2];
                window.location.href="sport.php?fmfdid="+id;
				
            }
        );
		
		
 $("#chart1").bind('jqplotDataHighlight', function(ev, seriesIndex, pointIndex, data) {
            var $this = $(this);                

            $this.attr('title', data[0] + ": " + data[1]);                               
        }); 

 $("#chart1").bind('jqplotDataUnhighlight', function(ev, seriesIndex, pointIndex, data) {
            var $this = $(this);                

            $this.attr('title',""); 
 });
 
 /*$('#chart1').bind('jqplotDataHighlight', 
        function (ev, seriesIndex, pointIndex, data ) {
            var mouseX = ev.pageX; //these are going to be how jquery knows where to put the div that will be our tooltip
            var mouseY = ev.pageY;
            $('#chartpseudotooltip').html(ticks_array[pointIndex] + ', ' + data[1]);
            var cssObj = {
                  'position' : 'absolute',
                  'font-weight' : 'bold',
                  'left' : mouseX + 'px', //usually needs more offset here
                  'top' : mouseY + 'px'
                };
            $('#chartpseudotooltip').css(cssObj);
            }
    );    

    $('#chart1').bind('jqplotDataUnhighlight', 
        function (ev) {
            $('#chartpseudotooltip').html('');
        }
    );*/
		
	</script>
</html>
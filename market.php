<?php
/**
 * @author : Rekha R
 * @copyright : 11-8-2012
 */

ini_set('max_execution_time', 300);

session_start();


$userid=$_SESSION['userid'];
if (isSet($_SERVER["HTTP_X_FORWARDED_FOR"])) {
     $userip = $_SERVER["HTTP_X_FORWARDED_FOR"];            
} elseif (isSet($_SERVER["HTTP_CLIENT_IP"])) {
     $userip = $_SERVER["HTTP_CLIENT_IP"];
} else {
     $userip = $_SERVER["REMOTE_ADDR"];
}

if($userid!=''){

}else{
	if($process==""){
		    $msg = "Successfully logged out";
	        header("Location: /console_management/index.php?msg=".$msg);
	}else{
	echo "0";
	exit;
	}
	
}	

	 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Price Click By Market</title>
<link type="icon" href="http://77.246.47.33/console_management/console/images/favicon.ico" rel="shortcut icon">

<link rel="stylesheet" type="text/css" href="../../css/style.css" />
    <style type="text/css" title="currentStyle">
                @import "../../css/grid_sytles.css";
                @import "../../css/themes/smoothness/jquery-ui-1.8.4.custom.css";
    </style>
  <!-- jQuery libs -->
 		    <link class="include" rel="stylesheet" type="text/css" href="jquery.jqplot.min.css" />
	    <script class="include" type="text/javascript" src="jquery.min.js"></script>   
        <style type="text/css">
          .jqplot-event-canvas{
	      border: 1px solid #4BB2C5 !important;
	    }
	    

          </style>
   
</head>
<body>
<!----------------- container starts ------------->
<div id="container_wrapper">

<!----------- header starts ----------------------->
<div id="header">
<br>
<a href="../../../dashboard_priceclick.php"><img src="../../images/logo_0.png" alt="home" style="border:0"></a>
</div>  
<!------------ header ends ------------------------>

<!------------ Welcome starts ------------------------>
<div style="margin-top: -50px;float:right">
<div align="right" >
<h2 style="text-align:center;margin:0px;color:#ccc;font-size:12px;">Welcome <span style="color:#DEEEF8;font-family:italic;"><?php echo $_SESSION['user']; ?></span>,  <a style="text-align:center;margin:0px;color:#ccc;font-size:12px;text-decoration:none;" href="../../../logout.php">Logout</a><br><span style="color:#DEEEF8;font-family:italic;"><?php echo $_SESSION['admin_login_time'];?></span></h2>
</div>
</div>


<!-------------- container starts ----------------->
<div id="container12" style="border: 1px solid #E8F6FF;">


<!--------------- content starts ------------------>
<div id="content" style="height:670px;margin-left:0 !important;width:1000px !important;">

	<div id="msg" style="color:green;font-weight:bold;text-align:center;"></div>
	<div id="errormsg" style="color:red;font-weight:bold;text-align:center;"></div>
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
			<h2 style="width:500px;text-align:center;margin:50px auto 0px auto;color: #027AC6;">By Markets <?php echo $provider_sport_name; ?><h2>
			<div id="chart2" class="example-chart" style="height:500px;width:700px;margin:50px auto;"></div>
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
		</div>
<!------------------- content ends ----------------------->
		
</div>
<!------------------- container ends -------------------->
</div>
		
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
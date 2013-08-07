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
<title>Price Click By Sport</title>
<link class="include" rel="stylesheet" type="text/css" href="jquery.jqplot.min.css" />
<script class="include" type="text/javascript" src="jquery.min.js"></script>   
<link type="icon" href="http://77.246.47.33/console_management/console/images/favicon.ico" rel="shortcut icon">

<link rel="stylesheet" type="text/css" href="../../css/style.css" />
    <style type="text/css" title="currentStyle">
                @import "../../css/grid_sytles.css";
                @import "../../css/themes/smoothness/jquery-ui-1.8.4.custom.css";
    </style>
  <!-- jQuery libs -->
   <script class="include" type="text/javascript" src="jquery.jqplot.min.js"></script>
    <script class="include" language="javascript" type="text/javascript" src="plugins/jqplot.pieRenderer.min.js"></script>
	
    
    <style type="text/css">
   
	h1 {
    	color: #027AC6;
   		text-align: center;
   		margin-left:0px !important;
   		font-weight:bold !important;
    	font-size:18px;
	}
	
   </style>
</head>
<body>
<form action="" method="POST" name="myform" id="myform" >

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
<div id="content" style="height:760px;margin-left:0 !important;width:1000px !important;">

	<div id="msg" style="color:green;font-weight:bold;text-align:center;"></div>
	<div id="errormsg" style="color:red;font-weight:bold;text-align:center;"></div>
	
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
				$providername = " - (Provider : ".$proinfo['feed_name'].")";
			}		
			
			?>

			<h1>By Sports <?php echo $providername; ?></h1>
		
	<!--------------- Sport starts ------------------->
<div id="chart1" style="height:500px; width:700px;margin:50px auto;"></div>
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
 
 
</div>
<!------------------- content ends ----------------------->

</div>
<!------------------- container ends -------------------->
</div>
</form>

</body>
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
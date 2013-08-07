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
<title>Price Click By Provider</title>
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
    .left{
	float:right;
	width:120px;
	}
	#ajax{
		float:left;
		width:300px;
		padding-top:5px;
		font-weight:700;
	}
   .highlight {
        background-color: #82BFE3;
   }
   #event1{
	  width:747px !important;
   }
   #event2{
   	 margin-left: 140px;
   	 margin-top: 10px;
    }
     #event3{
   margin-left: 135px;
   	 margin-top: 20px;
    }
    #mor{
      margin-left: 130px;
      margin-top: -30px;
      cursor:pointer;
    }
    #test3{
     	height: 130px;
    	overflow-x: hidden;
    	overflow-y: auto;
    }
      #test2{
     	height: 130px;
   		overflow-x: hidden;
    	overflow-y: auto;
    }
     .black_overlay{
	  	display: none;
		position: absolute;
		top: 0%;
		left: 0%;
		width: 100%;
		height: 100%;
		z-index:1001;
		-moz-opacity: 0.1;
		opacity:.10;
		filter: alpha(opacity=80);
	}
	#loading12 {
   		 margin-left: 142px !important;
	}
	h1 {
    	color: #027AC6;
   		text-align: center;
   		margin-left:0px !important;
   		font-weight:bold !important;
    	font-size:18px;
	}
	.white_content {
		height: 10%;
   	    left: 25%;
  	    padding: 16px;
   		position: absolute;
  		top: 25%;
        width: 50%;
        z-index: 1002;
        display: none;
	}
	#column1 {
		float:left;
		width:27.5%;
		margin-right:20px;
	}
	#column2 {
		float:left;
		width:70%;
	}
  #column3 {
		float:left;
		width:57.5%;
		margin-left:50px;
	}
	#test{
		height:530px !important;
	}
	#input_selection {
		margin-left: -120px !important;
	}
	#button1 {
		margin-left:800px !important;
	}
	.ui-widget-content {
    	font-family: Arial !important;
    	font-size: 12px !important;
	}
    #event1{
	  float: left;
      margin-left: 145px;
      margin-top: 30px;
      border:none;
    }
	#insert {
      float: left;
      margin-left: 40px;
      cursor:pointer;
    }
        input:focus
	{
	background-color:yellow;
	} 
	#input_sport{
	 margin-left: -260px !important;
	 padding-left: 0px !important;
	}
	 .iphone_switch_container
	 {
	 padding-left: 10px !important;
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
	<h1>Price Click By Provider</h1>
	
	<!--------------- Sport starts ------------------->

<div id="chart1" style="height:500px; width:700px; margin:90px auto;"></div>
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
 
		
	</script>
</html>
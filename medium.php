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
	    include('config.php');
		mysql_connect(DB_HOST, DB_USER, DB_PASS) or die (mysql_error ());
		mysql_select_db(ODDSCHECK_DB_NAME) or die(mysql_error());
		
	
if($userid!=''){

	$process= $_GET["process"];
	
		$condition = ''; 
		$condition1 = ''; 
		$selectedmedium =  isset($_GET['selectedmedium']) ? $_GET['selectedmedium'] : $_POST['selectedmedium'];
  		$selectedprovider =  isset($_GET['selectedprovider']) ? $_GET['selectedprovider'] : $_POST['selectedprovider'];
  		$selectedsport =  isset($_GET['selectedsport']) ? $_GET['selectedsport'] : $_POST['selectedsport'];
  		$selectedmarket =  isset($_GET['selectedmarket']) ? $_GET['selectedmarket'] : $_POST['selectedmarket'];
  		
  		
  		if($selectedmedium!='' || $selectedprovider!=''  || $selectedsport!=''){
  			$condition .= "where id is not null";
		}else{
			$result = "'re'";
		}
		
  		if($selectedmedium!='' ){
  			$condition .= " AND $selectedmedium = 1";
		}
		if($selectedprovider!='' ){
  			$condition .= " AND pcl.bookmaker = ".$selectedprovider;
		}
		if($selectedsport!='' ){
  			$condition .= " AND pcl.sportid = ".$selectedsport;
		}
         if($selectedmarket!='' ){
  			$condition .= " AND pcl.marketid = ".$selectedmarket;
		}
		
if($selectedmarket==''){
	
		
	if($selectedmedium!='' && $selectedprovider=='' && $selectedsport==''){
		$pro="SELECT COUNT(pcl.id) AS COUNT,pcl.bookmaker,bp.feed_name
		FROM easyodds_beta_new.EOB2B_price_click_log AS pcl
		JOIN EO_OddsCheck.BPartners AS bp ON bp.fm_fd_id = pcl.bookmaker $condition GROUP BY pcl.bookmaker ORDER BY pcl.bookmaker";
			$result1 = mysql_query($pro);
	     	mysql_query("SET NAMES utf8");
  		    $count=mysql_num_rows($result1);
  		    $result =  "[";
		  	if($count>0){
				while($sport   = mysql_fetch_array($result1)){
					  $gametypeid = $sport['bookmaker'];
					  $categoryname   = $sport['feed_name'];
			 	  	  $selected = ($selectedregion == $gametypeid) ? "selected" : "";
					  
					$resultarray[] = "['".$sport['feed_name']."',".$sport['COUNT'].",".$sport['bookmaker']."]";
					
				}
						
		  	}
		  	    $newresult = implode(",",$resultarray);
		        $result .= $newresult."]";
		        
		        $test .= "test";  	
		        
	}
	
	
     if($selectedmedium!='' && $selectedprovider!='' && $selectedsport==''){
		 $pro="SELECT COUNT(pcl.id) AS COUNT,pcl.sportid,sp.Sport_name
			FROM easyodds_beta_new.EOB2B_price_click_log AS pcl
			JOIN EO_OddsCheck.SportMaster AS sp ON pcl.sportid = sp.Sport_id
			".$condition." GROUP BY pcl.sportid ORDER BY sp.Sport_name";
		  	$result1 = mysql_query($pro);
	     	mysql_query("SET NAMES utf8");
  		    $count=mysql_num_rows($result1);
  		    $result =  "[";
		  	if($count>0){
				while($sport   = mysql_fetch_array($result1)){
					  $gametypeid = $sport['sportid'];
					  $categoryname   = $sport['Sport_name'];
			 	  	  $selected = ($selectedregion == $gametypeid) ? "selected" : "";
					  
			          $resultarray[] = "['".$sport['Sport_name']."',".$sport['COUNT'].",".$sport['sportid'].",".$fmfdid."]";
			 	 }
						
		  	}
		  	    $newresult = implode(",",$resultarray);
		        $result .= $newresult."]"; 
		        $test .= "test";   	
		        
	}else if ($selectedmedium=='' && $selectedprovider!='' && $selectedsport==''){
		
			 $pro="SELECT COUNT(pcl.id) AS COUNT,pcl.sportid,sp.Sport_name
			FROM easyodds_beta_new.EOB2B_price_click_log AS pcl
			JOIN EO_OddsCheck.SportMaster AS sp ON pcl.sportid = sp.Sport_id
			".$condition." GROUP BY pcl.sportid ORDER BY sp.Sport_name";
		  	$result1 = mysql_query($pro);
	     	mysql_query("SET NAMES utf8");
  		    $count=mysql_num_rows($result1);
  		    $result =  "[";
		  	if($count>0){
				while($sport   = mysql_fetch_array($result1)){
					  $gametypeid = $sport['sportid'];
					  $categoryname   = $sport['Sport_name'];
			 	  	  $selected = ($selectedregion == $gametypeid) ? "selected" : "";
					  
			          $resultarray[] = "['".$sport['Sport_name']."',".$sport['COUNT'].",".$sport['sportid'].",".$fmfdid."]";
			 	 }
						
		  	}
		  	    $newresult = implode(",",$resultarray);
		        $result .= $newresult."]"; 
		        $test .= "test";  
		
	}	
	
   if($selectedprovider!='' && $selectedsport=='' && $selectedmarket!=''){
   		 $pro="SELECT COUNT(pcl.id) AS COUNT,pcl.sportid,sp.Sport_name
			FROM easyodds_beta_new.EOB2B_price_click_log AS pcl
			JOIN EO_OddsCheck.SportMaster AS sp ON pcl.sportid = sp.Sport_id
			".$condition." GROUP BY pcl.sportid ORDER BY sp.Sport_name";
		  	$result1 = mysql_query($pro);
	     	mysql_query("SET NAMES utf8");
  		    $count=mysql_num_rows($result1);
  		    $result =  "[";
		  	if($count>0){
				while($sport   = mysql_fetch_array($result1)){
					  $gametypeid = $sport['sportid'];
					  $categoryname   = $sport['Sport_name'];
			 	  	  $selected = ($selectedregion == $gametypeid) ? "selected" : "";
					  
			          $resultarray[] = "['".$sport['Sport_name']."',".$sport['COUNT'].",".$sport['sportid'].",".$fmfdid."]";
			 	 }
						
		  	}
		  	    $newresult = implode(",",$resultarray);
		        $result .= $newresult."]"; 
		        $test .= "test";  	 	
	}
	
	
	if($selectedsport!=''){
			$pro="SELECT COUNT(id) as COUNT,pcl.marketid,md.market_name FROM easyodds_beta_new.EOB2B_price_click_log AS pcl
			JOIN Market_Data.Dt2_Md_Market AS md ON md.o_md_market_id = pcl.marketid
			$condition GROUP BY pcl.marketid ORDER BY market_name";
			
		  	$result1 = mysql_query($pro);
	     	mysql_query("SET NAMES utf8");
  		    $count=mysql_num_rows($result1);
  		    $result =  "[";
		  	if($count>0){
				while($sport   = mysql_fetch_array($result1)){
					  $gametypeid = $sport['marketid'];
					  $categoryname   = $sport['market_name'];
			 	  	  $selected = ($selectedregion == $gametypeid) ? "selected" : "";
					  
			          $resultarray[] = "['".$sport['market_name']."',".$sport['COUNT'].",".$sport['marketid'].",".$fmfdid."]";
			 	 }
						
		  	}
		  	    $newresult = implode(",",$resultarray);
		        $result .= $newresult."]"; 
		        $test .= "market";  	 	
	}
	
}else{
	
 
		
		$pro="SELECT COUNT(pcl.id) AS COUNT,pcl.bookmaker,bp.feed_name
		FROM easyodds_beta_new.EOB2B_price_click_log AS pcl
		JOIN EO_OddsCheck.BPartners AS bp ON bp.fm_fd_id = pcl.bookmaker $condition GROUP BY pcl.bookmaker ORDER BY pcl.bookmaker";
			$result1 = mysql_query($pro);
	     	mysql_query("SET NAMES utf8");
  		    $count=mysql_num_rows($result1);
  		    $result =  "[";
		  	if($count>0){
				while($sport   = mysql_fetch_array($result1)){
					  $gametypeid = $sport['bookmaker'];
					  $categoryname   = $sport['feed_name'];
			 	  	  $selected = ($selectedregion == $gametypeid) ? "selected" : "";
					  
					$resultarray[] = "['".$sport['feed_name']."',".$sport['COUNT'].",".$sport['bookmaker']."]";
				}
						
		  	}
		  	    $newresult = implode(",",$resultarray);
		        $result .= $newresult."]";
		        $test .= "test";  	 	
 }
	

		

		
		
		
	 
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
<title>Price Click Analytics</title>
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
      <script language="javascript" type="text/javascript" src="plugins/jqplot.categoryAxisRenderer.min.js"></script>
    <script language="javascript" type="text/javascript" src="plugins/jqplot.barRenderer.min.js"></script>
    
	       <style type="text/css">
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
		h1 {
	    	color: #027AC6;
	   		text-align: center;
	   		margin-left:0px !important;
	   		font-weight:bold !important;
	    	font-size:18px;
		}   
		#input_provider_sport {
		    float: left;
		    margin-left: 0 !important;
		    margin-top:10px;
		 }
		#input_provider {
		    padding-top: 10px !important;
		    padding-left: 20px !important;
		}
		#input_sport {
		    padding-left: 10px !important;
		    padding-top: 10px !important;
		}
		#input_event {
		  padding-left: 10px !important;
  		  padding-top: 10px !important;
  		}
		#input_event12 {
		    margin-left: -45px !important;
	   		margin-top: 10px !important;
		    padding-left: 57px !important;
		    padding-top: 0px !important;
		}
		#selection_arrow {
		    margin-left: 40px !important;
		}
	    .jqplot-event-canvas{
	      border: 1px solid #4BB2C5 !important;
	    }
	     .jqplot-xaxis-tick{
	    	text-indent: 5px !important;
		    width: 120px !important;
		    word-wrap: break-word !important;
	     }
	     .jqplot-axis-tick, .jqplot-xaxis-tick, .jqplot-yaxis-tick, .jqplot-x2axis-tick, .jqplot-y2axis-tick, .jqplot-y3axis-tick, .jqplot-y4axis-tick, .jqplot-y5axis-tick, .jqplot-y6axis-tick, .jqplot-y7axis-tick, .jqplot-y8axis-tick, .jqplot-y9axis-tick, .jqplot-yMidAxis-tick {
	     
	     white-space:none !important;
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
<div id="content" style="height:760px;margin-left:0 !important;width:1000px !important;">

	<div id="msg" style="color:green;font-weight:bold;text-align:center;"></div>
	<div id="errormsg" style="color:red;font-weight:bold;text-align:center;"></div>
	
	<?php 
	   if($selectedmedium=='' && $selectedprovider=='' && $selectedsport==''  && $selectedmarket==''){
	  	    echo "<h1>Price Click Analytics</h1>";
	   }else if($selectedmarket!=''){
	   		$fmfdid = $_REQUEST['selectedprovider'];
			$spid = $_REQUEST['selectedsport'];
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
				$providername = " Provider : ".$proinfo['feed_name'];
			}
			
			if($fmfdid > 0 || $spid > 0){
				$provider_sport_name = " - (".$sportsname.$providername.")";
			}
			
	   		echo "<h1>By Market $provider_sport_name</h1>";
	   }else if($selectedprovider!='' && $selectedsport==''  && $selectedmarket==''){
	    	echo "<h1>By Provider</h1>";
		}else if($selectedprovider!='' && $selectedsport!=''){	
			$condition .= " AND pcl.bookmaker = ".$selectedprovider;
			$query_get_providername = mysql_query("SELECT feed_name FROM EO_OddsCheck.BPartners WHERE fm_fd_id = ".$selectedprovider);
			$proinfo = mysql_fetch_array($query_get_providername);
			$providername = "  - ( Provider : ".$proinfo['feed_name'].")";
			echo "<h1>By Sports  $providername</h1>";
		}else if($selectedmedium!=''){	
			echo "<h1>By Medium</h1>";
		}
		?>
		
		
	
<!--------------- Provider sport event starts ----->
<div id="input_provider_sport">

<!--------------- provider starts ----------------->
<div id="input_provider">
	<label>Medium</label>
		  <select  name="medium" id="medium" onchange="javascript:getProvider(this)" style="width:180px;border-radius:5px 5px 5px 5px;border:1px solid #8BD1FE;">
          <option value="Select">(Please select a Medium)</option>
		  <option value="in_matrix">Matrix</option>
		  <option value="in_kiosk">Kiosk</option>
		  <option value="in_content">Content</option>
		  <option value="in_twitter_facebook">Twitter_Facebook</option>
		  <option value="in_newsletter_email">Newsletter_Email</option>
		  <option value="in_api">Api</option>
          <option value="in_microsit">Microsite</option>
		  </select>

</div>

<!--------------- provider ends ------------------>

<!--------------- Sport starts ------------------->
<div id="input_sport">

  	<?php
  		$condition = ''; 
		$selectedmedium =  isset($_GET['selectedmedium']) ? $_GET['selectedmedium'] : $_POST['selectedmedium'];
  		$selectedprovider =  isset($_GET['selectedprovider']) ? $_GET['selectedprovider'] : $_POST['selectedprovider'];
  		$selectedsport =  isset($_GET['selectedsport']) ? $_GET['selectedsport'] : $_POST['selectedsport'];
  		$selectedmarket =  isset($_GET['selectedmarket']) ? $_GET['selectedmarket'] : $_POST['selectedmarket'];
  		
  		if($selectedmedium!='' || $selectedprovider!=''  || $selectedsport!='' || $selectedmarket!=''){
  			$condition .= "where  id is not null ";
  			
		}else{
				
		}
		
  		if($selectedmedium!='' ){
  			$condition .= " AND $selectedmedium = 1";
		}
		/*if($selectedprovider!='' ){
  			$condition .= " AND pcl.bookmaker = ".$selectedprovider;
		}*/
		if($selectedsport!='' ){
  			$condition .= " AND pcl.sportid = ".$selectedsport;
		}
		   if($selectedmarket!='' ){
  			$condition .= " AND pcl.marketid = ".$selectedmarket;
		}
	
 		echo "<label>Partner</label>
				  <select  name=\"provider\" id=\"provider\" onchange=\"javascript:getSport(this)\" style=\"width:180px;border-radius:5px 5px 5px 5px;border:1px solid #8BD1FE;\">
				  <option value=\"Select\">(Please select a Partner)</option>";
  		
	       $pro="SELECT COUNT(pcl.id) AS COUNT,pcl.bookmaker,bp.feed_name
			FROM easyodds_beta_new.EOB2B_price_click_log AS pcl
			JOIN EO_OddsCheck.BPartners AS bp ON bp.fm_fd_id = pcl.bookmaker 
			".$condition."  GROUP BY pcl.bookmaker ORDER BY pcl.bookmaker";
	  		$result1 = mysql_query($pro);
		     	mysql_query("SET NAMES utf8");
	  		    $count=mysql_num_rows($result1);
	  		   
			  	if($count>0){
					while($sport   = mysql_fetch_array($result1)){
						  $gametypeid = $sport['bookmaker'];
						  $categoryname   = $sport['feed_name'];
				 	  	  $selected = ($selectedprovider == $gametypeid) ? "selected" : "";
						 echo  "<option value=\"$gametypeid\" id=\"$gametypeid\" ".$selected.">".$categoryname."</option>\n";
						  
					}
			  	}
		  
		     echo "</select>";
	?>
	
	
</div>

<!----------------- Sport ends -------------->

<!--------------- Sport starts ------------------->
<div id="input_event">

<?php
	    $condition = ''; 
		$selectedmedium =  isset($_GET['selectedmedium']) ? $_GET['selectedmedium'] : $_POST['selectedmedium'];
  		$selectedprovider =  isset($_GET['selectedprovider']) ? $_GET['selectedprovider'] : $_POST['selectedprovider'];
  		$selectedsport =  isset($_GET['selectedsport']) ? $_GET['selectedsport'] : $_POST['selectedsport'];
  		$selectedmarket =  isset($_GET['selectedmarket']) ? $_GET['selectedmarket'] : $_POST['selectedmarket'];
  		
  	
		
  		if($selectedmedium!='' || $selectedprovider!=''  || $selectedsport!=''){
  			$condition1 .= "where  id is not null ";
  			
		}else{
				
		}
		
  		if($selectedmedium!='' ){
  			$condition1 .= " AND $selectedmedium = 1";
		}
		if($selectedprovider!='' ){
  			$condition1 .= " AND pcl.bookmaker = ".$selectedprovider;
		}
		/*if($selectedsport!='' ){
  			$condition1 .= " AND pcl.sportid = ".$selectedsport;
		}*/
		   if($selectedmarket!='' ){
  			$condition .= " AND pcl.marketid = ".$selectedmarket;
		}
  		
  		echo "<label>Sport</label>
				  <select  name=\"sport\" id=\"sport\" onchange=\"javascript:getMarket(this)\" style=\"width:180px;border-radius:5px 5px 5px 5px;border:1px solid #8BD1FE;\">
				  <option value=\"Select\">(Please select a Sport)</option>";
				
	    	 $spo="SELECT COUNT(pcl.id) AS COUNT,pcl.sportid,sp.Sport_name
			FROM easyodds_beta_new.EOB2B_price_click_log AS pcl
			JOIN EO_OddsCheck.SportMaster AS sp ON pcl.sportid = sp.Sport_id
			".$condition1." GROUP BY pcl.sportid ORDER BY sp.Sport_name";
		   $result1 = mysql_query($spo);
			mysql_query("SET NAMES utf8");
  		    $count=mysql_num_rows($result1);
		  	if($count>0){
				while($sport   = mysql_fetch_array($result1)){
					  $gametypeid = $sport['sportid'];
					  $categoryname   = $sport['Sport_name'];
			 	  	  $selected = ($selectedsport == $gametypeid) ? "selected" : "";
					  echo  "<option value=\"$gametypeid\" id=\"$gametypeid\" ".$selected.">".$categoryname."</option>\n";
					
		            
				}
					
		  	}
		  
		     echo "</select>";
	?>
	

</div>

<!----------------- Sport ends -------------->

<!--------------- Sport starts ------------------->
<div id="input_event12">
	<?php
  		 $condition = ''; 
		$selectedmedium =  isset($_GET['selectedmedium']) ? $_GET['selectedmedium'] : $_POST['selectedmedium'];
  		$selectedprovider =  isset($_GET['selectedprovider']) ? $_GET['selectedprovider'] : $_POST['selectedprovider'];
  		$selectedsport =  isset($_GET['selectedsport']) ? $_GET['selectedsport'] : $_POST['selectedsport'];
  		$selectedmarket =  isset($_GET['selectedmarket']) ? $_GET['selectedmarket'] : $_POST['selectedmarket'];
  		
  		
  		if($selectedmedium!='' || $selectedprovider!=''  || $selectedsport!='' || $selectedmarket!=''){
  			$condition .= "where  id is not null ";
  			
		}else{
				
		}
		
  		if($selectedmedium!='' ){
  			$condition .= " AND $selectedmedium = 1";
		}
		if($selectedprovider!='' ){
  			$condition .= " AND pcl.bookmaker = ".$selectedprovider;
		}
		if($selectedsport!='' ){
  			$condition .= " AND pcl.sportid = ".$selectedsport;
		}
		
	
 	 		
  		echo "<label>Market</label>
				  <select  name=\"market\" id=\"market\" onchange=\"javascript:getOutput(this)\" style=\"width:180px;border-radius:5px 5px 5px 5px;border:1px solid #8BD1FE;\">
				  <option value=\"Select\">(Please select a Market)</option>";
					
	    	 	$result1 = mysql_query("SELECT COUNT(id) as COUNT,pcl.marketid,md.market_name FROM easyodds_beta_new.EOB2B_price_click_log AS pcl
			JOIN Market_Data.Dt2_Md_Market AS md ON md.o_md_market_id = pcl.marketid
			$condition GROUP BY pcl.marketid ORDER BY market_name");
			
			mysql_query("SET NAMES utf8");
  		    $count=mysql_num_rows($result1); 
		  	if($count>0){
				while($sport   = mysql_fetch_array($result1)){
					  $gametypeid = $sport['marketid'];
					  $categoryname   = $sport['market_name'];
			 	  	  $selected = ($selectedmarket == $gametypeid) ? "selected" : "";
					  echo  "<option value=\"$gametypeid\" id=\"$gametypeid\" ".$selected.">".$categoryname."</option>\n";
					}
		  	}
		     echo "</select>";
		  	
	?>
</div>

<!----------------- Sport ends -------------->
	 
<div id="chart1" style="height:500px; width:700px; margin:90px auto;"></div>





</div>
		</div>
<!------------------- content ends ----------------------->
		
<div id="light" class="white_content"><img src="../images/loading.gif" style="margin-top:80px;margin-left:250px;"></div>
		<div id="fade" class="black_overlay"></div>
		
</div>
<!------------------- container ends -------------------->
</div>
		
	</body>


		<script type="text/javascript">

		var ajx_req1;
		function getProvider(sel){
		 document.getElementById('msg').innerHTML="";
		 document.getElementById('errormsg').innerHTML="";
	 	 
		  var value = sel.options[sel.selectedIndex].value;
		  
		  var medium=document.getElementById('medium').value;
		  var provider=document.getElementById('provider').value;
		  var sport=document.getElementById('sport').value;
		  var market=document.getElementById('market').value;
					

		  if(medium=="Select"){
				medium="";
			}
			if(provider=='Select'){
				provider="";
			}
			if(sport=='Select'){
				sport="";
			}

			if(market=='Select'){
				market="";
			}
			
			 window.location.href='kiosk.php?selectedmarket='+market+'&selectedmedium='+medium+'&selectedprovider='+provider+'&selectedsport='+sport;

	
		 } 

		function getSport(sel){
			 document.getElementById('msg').innerHTML="";
			 document.getElementById('errormsg').innerHTML="";
		 	 
			  var value = sel.options[sel.selectedIndex].value;

			 /* var medium=document.getElementById('medium').value;
			  var provider=document.getElementById('provider').value;
				

					if(medium!="Select" && provider!="Select"){
					     window.location.href='kiosk.php?selectedmedium='+medium+'&selectedprovider='+provider;
					}else{
						if(medium!="Select"){
						     window.location.href='kiosk.php?selectedmedium='+medium;
						}else if(provider!="Select"){
							 window.location.href='kiosk.php?selectedprovider='+provider;
						}
					}*/

					  var medium=document.getElementById('medium').value;
					  var provider=document.getElementById('provider').value;
					  var sport=document.getElementById('sport').value;
					  var market=document.getElementById('market').value;
									

					  if(medium=="Select"){
							medium="";
						}
						if(provider=='Select'){
							provider="";
						}
						if(sport=='Select'){
							sport="";
						}
						

						if(market=='Select'){
							market="";
						}
						
						 window.location.href='kiosk.php?selectedmarket='+market+'&selectedmedium='+medium+'&selectedprovider='+provider+'&selectedsport='+sport;
					
							
		 } 


		function getMarket(sel){
			 document.getElementById('msg').innerHTML="";
			 document.getElementById('errormsg').innerHTML="";
		 	 
			  var value = sel.options[sel.selectedIndex].value;


			  var medium=document.getElementById('medium').value;
			  var provider=document.getElementById('provider').value;
			  var sport=document.getElementById('sport').value;
			  var market=document.getElementById('market').value;
				

			  if(medium=="Select"){
					medium="";
				}
				if(provider=='Select'){
					provider="";
				}
				if(sport=='Select'){
					sport="";
				}
				

				if(market=='Select'){
					market="";
				}
				
				 window.location.href='kiosk.php?selectedmarket='+market+'&selectedmedium='+medium+'&selectedprovider='+provider+'&selectedsport='+sport;
				
			 } 


		function getOutput(sel){
			 document.getElementById('msg').innerHTML="";
			 document.getElementById('errormsg').innerHTML="";
		 	 
			  var value = sel.options[sel.selectedIndex].value;
			  var medium=document.getElementById('medium').value;
			  var provider=document.getElementById('provider').value;
			  var sport=document.getElementById('sport').value;
			  var market=document.getElementById('market').value;
				

			  if(medium=="Select"){
					medium="";
				}
				if(provider=='Select'){
					provider="";
				}
				if(sport=='Select'){
					sport="";
				}
				

				if(market=='Select'){
					market="";
				}
				
				 window.location.href='kiosk.php?selectedmarket='+market+'&selectedmedium='+medium+'&selectedprovider='+provider+'&selectedsport='+sport;
				

			 } 


		function load(){

			<?php $selectedmedium =  isset($_GET['selectedmedium']) ? $_GET['selectedmedium'] : $_POST['selectedmedium']; ?>
	          
	          var t='<?php echo $selectedmedium; ?>';
	           
	          document.getElementById('medium').value= t;
	          
	       
			
		}

		load();
		
		$(document).ready(function(){
            var data = <?php echo $result; ?>;
            var data1 = "<?php echo $test; ?>";
            

			  	if(data=='re'){
								  	 
			    }else{
			      if(data==''){
				      document.getElementById('errormsg').innerHTML="No Records Found";
			      }else{
			    	  if(data1=='market'){
			    		  var line1 = <?php echo $result; ?>;
			    			
			    		    $('#chart1').jqplot([line1], {
			    		        title:'Markets',
			    		        seriesDefaults:{
			    		            renderer:$.jqplot.BarRenderer,
							        dataRenderer: [data],
			    		            
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
			    	  }else{  
			    	  
					      var plot1 = jQuery.jqplot ('chart1',[data], 
							    { 
							      seriesDefaults: {
							        // Make this a pie chart.
							        renderer: jQuery.jqplot.PieRenderer, 
							        dataRenderer: [data],
							        rendererOptions: {
							          // Put data labels on the pie slices.
							          // By default, labels show the percentage of the slice.
							          showDataLabels: true
							        }
							      },
							       
							      legend: { show:true, location: 'e' }
							    }
							  );
			      }
			      }
			  }
				 
		});
			    		
	

		$('#chart1').bind('jqplotDataClick',
            function (ev, seriesIndex, pointIndex, data) {                			   
                var id = data[2];
                var data1 = "<?php echo $test; ?>";
            if(data1=='market'){

     			                            
            }else{	
                var provider=document.getElementById('provider').value;
                var sport=document.getElementById('sport').value;
                	
            	 if(sport=="Select" && provider!='Select'){ 

            		 var medium=document.getElementById('medium').value;
						var provider=document.getElementById('provider').value;
						var sport=document.getElementById('sport').value;
						var market=document.getElementById('market').value;
										
						if(medium=="Select"){
							medium="";
						}
						if(provider=='Select'){
							provider="";
						}
						if(sport=='Select'){
							sport="";
						}
						if(market=='Select'){
							market="";
						}
            		  var spid = data[2];
						var fmfdid = (data[3] > 0)?data[3]:0;
		             
		                
					window.location.href='kiosk.php?selectedprovider='+provider+'&selectedmarket='+market+'&selectedmedium='+medium+'&selectedsport='+spid;
		
            	 } else if(document.getElementById('sport').value!="Select"){ 
		                 var fmfdid = (data[3] > 0)?data[3]:0;

			            var medium=document.getElementById('medium').value;
						var provider=document.getElementById('provider').value;
						var sport=document.getElementById('sport').value;
						var market=document.getElementById('market').value;
										
						if(medium=="Select"){
							medium="";
						}
						if(provider=='Select'){
							provider="";
						}
						if(sport=='Select'){
							sport="";
						}
						if(market=='Select'){
							market="";
						}
						    var spid = data[2];
							var fmfdid = (data[3] > 0)?data[3]:0;
			              
			                
						window.location.href='kiosk.php?selectedmarket='+market+'&selectedmedium='+medium+'&selectedprovider='+fmfdid+'&selectedsport='+spid;
						

				}else{
					  var medium=document.getElementById('medium').value;
					  var provider=document.getElementById('provider').value;
					  var sport=document.getElementById('sport').value;
						var market=document.getElementById('market').value;
						
					if(medium=="Select"){
						medium="";
					}
					if(provider=='Select'){
						provider="";
					}
					if(sport=='Select'){
						sport="";
					}
					if(market=='Select'){
						market="";
					}
				     var id = data[2];
				                     
					window.location.href='kiosk.php?selectedmarket='+market+'&selectedsport='+sport+'&selectedmedium='+medium+'&selectedprovider='+id;
				}
           }
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
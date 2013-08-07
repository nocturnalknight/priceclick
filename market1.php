<html>
	<head>
	    <link class="include" rel="stylesheet" type="text/css" href="jquery.jqplot.min.css" />
	    <script class="include" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>   
	</head>
	<body>
		<div id="chart2" class="example-chart" style="height:300px;width:500px"></div>
	</body>

	<script class="include" type="text/javascript" src="jquery.jqplot.min.js"></script>
    <script language="javascript" type="text/javascript" src="plugins/jqplot.categoryAxisRenderer.min.js"></script>
    <script language="javascript" type="text/javascript" src="plugins/jqplot.barRenderer.js"></script>
	<script type="text/javascript" class="code">
	$(document).ready(function(){
	    var line1 = [['Nissan', 4],['Porche', 6]];
	
	    $('#chart2').jqplot([line1], {
	        title:'Bar Chart with Varying Colors',
	        seriesDefaults:{
	            renderer:$.jqplot.BarRenderer,
	            rendererOptions: {
	            	animation: {
                        speed: 3000
                    },
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
	});
	</script>
</html>

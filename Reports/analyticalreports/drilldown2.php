


<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Highcharts Example</title>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		
		<script type="text/javascript">
$(function () {

    Highcharts.data({
        csv: document.getElementById('tsv').innerHTML,
        itemDelimiter: '\t',
        parsed: function (columns) {

            var brands = {},
                brandsData = [],
                versions = {},
                drilldownSeries = [];

            // Parse percentage strings
            columns[1] = $.map(columns[1], function (value) {
                if (value.indexOf('%') === value.length - 1) {
                    value = parseFloat(value);
                }
                return value;
            });

            $.each(columns[0], function (i, name) {
                var brand,
                    version;

                if (i > 0) {

                    // Remove special edition notes
                    //name = name.split(' -')[0];

                    // Split into brand and version
                    version = name.match(/([A-Z]+[\.]*)/);
                    if (version) {
					//alert(version);
                        version = version[0];
                    }
                    brand = name.replace(version,'');

                    // Create the main data
                    if (!brands[brand]) {
                        brands[brand] = columns[1][i];
                    } else {
                        brands[brand] += columns[1][i];
                    }

                    // Create the version data
                    if (version !== null) {
                        if (!versions[brand]) {
                            versions[brand] = [];
                        }
                        versions[brand].push([version, columns[1][i]]);
                    }
                }

            });

            $.each(brands, function (name, y) {
                brandsData.push({
                    name: name,
                    y: y,
                    drilldown: versions[name] ? name : null
                });
            });
            $.each(versions, function (key, value) {
                drilldownSeries.push({
                    name: key,
                    id: key,
                    data: value
                });
            });

            // Create the chart
            $('#container').highcharts({
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Total SGBV cases. Feb, 2015.'
                },
                subtitle: {
                    text: 'Click the slices to view proportions of SGBV cases that were withdrawn,convicted or unresolved.'
                },
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}: {point.y:.1f}%'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
                },

                series: [{
                    name: 'Brands',
                    colorByPoint: true,
                    data: brandsData
                }],
                drilldown: {
                    series: drilldownSeries
                }
            });
        }
    });
});


		</script>
	</head>
	<body>
<script src="js/highcharts.js"></script>
<script src="js/modules/data.js"></script>
<script src="js/modules/drilldown.js"></script>

<div id="container" style="min-width: 310px; max-width: 600px; height: 400px; margin: 0 auto"></div>

<!-- Data from www.netmarketshare.com. Select Browsers => Desktop share by version. Download as tsv. -->

<pre id="tsv" style="display:none">Browser Version	Total Market Share
<?php 
include "classes/DB.class.php";
$db = new DB();
$db->connect();
$csv=$db->selectAll("piechart_drilldown"); 
echo  $csv;
?>
</pre>
 <center><div><section style="float:left; margin-left:25px;"><a href="../analytical_reports.php" align="left" style="font-family:Verdana, Geneva, sans-serif; font-size:15px; color:#81519C; text-decoration:none;padding-right:2px;">Back to Reports</a></section> <section style="float:right; margin-right:10px;"> <a href="" align="left" style="font-family:Verdana, Geneva, sans-serif; font-size:15px; color:#81519C; text-decoration:none;padding-right:2px;">Print</a></section></div> 
</body>
</html>

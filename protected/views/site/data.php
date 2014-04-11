<?php
/* @var $this SiteController */
/* @var $stations Stations[] */

// Set title of the page
$this->pageTitle=Yii::app()->name . ' - Situation actuelle du vent';

// Set the color scheme of the graphs depending on the theme
if ($this->style == 'clear') {
  $colorScheme = "var plainFrontColor = '#333333', lightFrontColor = '#333333', alternateFrontColor = 'rgba(120,120,120,0.5)';";
} else {
  $colorScheme = "var plainFrontColor = '#fff', lightFrontColor = 'rgba(255,255,255,0.3)', alternateFrontColor = 'rgba(120,120,120,0.3)';";
}

// Register client script
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/highcharts.src.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/highcharts-more.src.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/highcharts-wind.src.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('dataScript', "

var rootUrl = '" . Yii::app()->createAbsoluteUrl('site/getData') . "';
var currentStation = '" . $stations[0]->name . "';
var now = new Date();

" . $colorScheme . "

$( '.stations' ).change(function(e) {
	setCurrentStation(e.target.options[e.target.selectedIndex].text);
});

function drawWindRose(data)
{
	var d = [];
	
	// Re-format points
	data.forEach(function(entry) {
		d.push([entry[0], entry[1], Math.pow(2, (parseFloat(entry[2] & 0xFFFF)) / 200)]);
	});
	
	// Parse the data from an inline table using the Highcharts Data plugin
	$('#chartwindrose').highcharts({	    
		chart: {
			polar: true,
			type: 'bubble',
      backgroundColor: 'rgba(255,255,255,0)',
		},
		
		title: {
			text: 'Rose des vents',
      style: {
        color: plainFrontColor,
      },
		},
        
    tooltip: {
      formatter: function() {
        var date = new Date(this.point.x * 1000);
        return this.point.y +'km/h - ' + this.point.x + '°';
      },
      borderColor: plainFrontColor,
    },
		
		pane: {
			startAngle: 0,
			endAngle: 360,
		},
		
		xAxis: {
			min: 0,
			max: 360,
			tickInterval: 22.5,
      gridLineColor: plainFrontColor,
      lineColor: plainFrontColor,
			labels: {
        style: {
          color: plainFrontColor,
          fontWeight: 'bold',
        },
        enabled: true,
				formatter: function() {
					switch (this.value) {
						case 0:
							return 'N';
//						case 22.5:
//							return 'NNE';
//						case 45:
//							return 'NE';
//						case 67.5:
//							return 'ENE';
						case 90:
							return 'E';
//						case 112.5:
//							return 'ESE';
//						case 135:
//							return 'SE';
//						case 157.5:
//							return 'SSE';
						case 180:
							return 'S';
//						case 202.5:
//							return 'SSW';
//						case 225:
//							return 'SW';
//						case 247.5:
//							return 'WSW';
						case 270:
							return 'W';
//						case 292.5:
//							return 'WNW';
//						case 315:
//							return 'NW';
//						case 337.5:
//							return 'NNW';
						default:
							return '';
					}
				},
      },
		},
		
		yAxis: {
			min: 0,
			tickInterval: 5,
      gridLineColor: plainFrontColor,
      lineColor: plainFrontColor,
      alternateGridColor: alternateFrontColor,
			labels: {
        style: {
          color: plainFrontColor,
          fontWeight: 'bold',
        },
      },
		},
		
		series: [{
			name: 'Plus un point est gros plus il est récent',
			data: d,
			marker: {
				fillColor: lightFrontColor,
				lineWidth: 0,
				lineColor: null // inherit from series
			}
		}],
		
		legend: {
			enabled: true,
      borderColor: plainFrontColor,
      itemStyle: {
        color: plainFrontColor,
        fontWeight: 'bold',
      },
		},
		
		plotOptions: {
			bubble: {
				minSize: 0.1,
				maxSize: 15,
				color: plainFrontColor,
			},
		},
        
    credits: {
      position: {
        align: 'center',
        x: 10,
      },
      style: {
        color: plainFrontColor,
      },
    },
    
	});
}

function updateWindRose(data)
{
	// Re-format and add points
	data.forEach(function(entry) {
		var p = [entry[0], entry[1], Math.pow(2, (parseFloat(entry[2] & 0xFFFF)) / 200)];
		$('#chartwindrose').highcharts().series[0].addPoint(p, true, true);
	});
}

function drawClassic(data)
{
	var d = [];
	
	// Re-format points
	data.forEach(function(entry) {
		d.push([parseInt(entry[2] / 60) * 60, entry[1], entry[0]]);
	});
	
	// Parse the data from an inline table using the Highcharts Data plugin
	$('#chartclassic').highcharts({
		
		chart: {
			polar: false,
			type: 'wind',
      backgroundColor: 'rgba(255,255,255,0)',
		},
		
		title: {
			text: 'Le classique',
      style: {
        color: plainFrontColor,
      },
		},
    
    tooltip: {
      formatter: function() {
        var date = new Date(this.point.x * 1000);
        return (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) + ':' + (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes()) + ' - '+ this.point.y +'km/h - ' + this.point.z + '°';
      },
      borderColor: plainFrontColor,
    },
		
		xAxis: {
			tickInterval: 900,
      gridLineColor: plainFrontColor,
      lineColor: plainFrontColor,
			labels: {
        style: {
          color: plainFrontColor,
          fontWeight: 'bold',
        },
        enabled: true,
				formatter: function() {
					var date = new Date(this.value * 1000);
					return (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) + ':' + (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes());
				},
      },
      gridLineDashStyle: 'longdash',
			gridLineWidth: 1,
		},
		
		yAxis: {
			min: 0,
			tickInterval: 5,
      gridLineColor: plainFrontColor,
      lineColor: plainFrontColor,
			title: {
				text: 'km/h',
        style: {
          color: plainFrontColor,
        },
			},
			labels: {
        style: {
          color: plainFrontColor,
          fontWeight: 'bold',
        },
      },
		},
		
		series: [{
			name: 'data',
			data: d,
			marker: {
				symbol: 'flag',
				fillColor: plainFrontColor,
				lineWidth: 1,
				lineColor: plainFrontColor,
				radius: 4,
			}
		}],
		
		legend: {
			enabled: false,
		},
		
		plotOptions: {
			bubble: {
				minSize: 5,
				maxSize: 5,
				color: plainFrontColor,
			},
			line: {
				lineWidth: 0,
			},
		},
        
    credits: {
      position: {
        align: 'center',
        x: 10,
      },
      style: {
        color: plainFrontColor,
      },
    },
    
	});
}

function updateClassic(data)
{
	var serie = $('#chartclassic').highcharts().series[0];
	
	// Re-format and add points
	data.forEach(function(entry) {
		var p = [entry[2], entry[1], entry[0]];
		serie.addPoint(p, true, (serie.data.length >= 240));
	});
}

function updateSummary(latest)
{
	$('#summary').empty().append('<table class=\"table\"><thead><tr><th>R&eacute;capitulatif</th><th>Courant</th><th>Maximum du jour</th><th>Minimum du jour</th></tr></thead><tbody><tr><td>Temp&eacute;rature</td><td>'+latest.currentOutsideTemperature+' C</td><td>'+latest.maxOutsideTemperature+' C</td><td>'+latest.minOutsideTemperature+' C</td></tr><tr><td>Humidit&eacute;</td><td>'+latest.currentOutsideHumidity+'%</td><td>'+latest.maxOutsideHumidity+'%</td><td>'+latest.minOutsideHumidity+'%</td></tr><tr><td>Point de ros&eacute;e</td><td>'+latest.currentDewPoint+' C</td><td>'+latest.maxDewPoint+' C</td><td>'+latest.minDewPoint+' C</td></tr><tr><td>Pression atmosph&eacute;rique</td><td>'+latest.currentPressure+'mb</td><td>'+latest.maxPressure+'mb</td><td>'+latest.minPressure+'mb</td></tr><tr><td>Vent</td><td>'+latest.currentWindSpeed+' km/h - '+latest.currentWindDirection+'&deg;</td><td>'+latest.maxWindSpeed+' km/h</td><td>&nbsp;</td></tr></tbody><thead><tr style=\"height:72px;\"><th>Vent</th><th>Sur 2 minutes</th><th>Sur 10 minutes</th><th>&nbsp;</th></tr></thead><tbody><tr><td>Vitesse du vent en moyenne</td><td>'+latest.averageWindSpeed2Minutes+' km/h</td><td>'+latest.averageWindSpeed10Minutes+' km/h</td><td>&nbsp;</td></tr><td>Rafales de vent</td><td>'+latest.windGust+' km/h</td><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table>');
}

function loadData(success, stationName, deepnes)
{
	// Set deepnes to default value
	deepnes = typeof deepnes !== 'undefined' ? deepnes : 7200;
	
	// Compose url var
	var url = rootUrl + '&stationName=' + stationName + '&deepness=' + deepnes;
	
	// Send ajax request
	$.ajax({
		type: 'POST',
		url: url,
		dataType: 'json',
		success: success,
		error: function (data) {
		},
	});
}

function setCurrentStation(stationName)
{
	// Save current station's name
	currentStation = stationName;
	
	// Load data and display it
	loadData(function(data) {
		drawWindRose(data.data);
		drawClassic(data.data);
		updateSummary(data.latest);
	}, currentStation);
}

function captureNextGraphUpdate()
{
	now = new Date();
	now.setMinutes(now.getMinutes() + 1);
}

// Initial display
setCurrentStation(currentStation);

// Setup timer to trigger data update every minutes
captureNextGraphUpdate();
window.setInterval(function() {
	loadData(function(data) {
		captureNextGraphUpdate();
		updateWindRose(data.data);
		updateClassic(data.data);
		updateSummary(data.latest);
	}, currentStation, 60)
}, 60 * 1000);

window.setInterval(function () {
	var tmp = new Date(),
	secondsLeft = Math.round((now.getTime() - tmp.getTime()) / 1000);
	$('#counter').html('<h4 style=\"float: right;\">Prochaine mise &agrave; jour dans ' + secondsLeft + ' secondes</h4>');
}, 1 * 1000);

");
?>

<h1>Wind</h1>

<!--
<select class="stations form-control">
<?php
for ($i = 0; $i < count($stations); $i++) {
echo '<option>' . $stations[$i]->name . '</option>';
}
?>
</select>
-->

<h2>R&eacute;capitulatif</h2>

<div id="summary"></div>

<h2>Graphics</h2>

<div class="fluid-container">
  <div class="row">
    <div class="col-md-4"><div id="chartwindrose" style="height:400px;"></div></div>
    <div class="col-md-8"><div id="chartclassic" style="height:400px;"></div></div>
  </div>
<div class="row">
    <div class="col-md-12"><div id="counter"></div></div>
</div>
</div>

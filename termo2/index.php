<!DOCTYPE html>
<?PHP
$data = file_get_contents("http://localhost/data.php");
?>
<html>
	<head>
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>

		<script type="text/javascript" src="js/jquery.thermometer.js"></script>
		<script type="text/javascript">
			$(document).ready( function() {
				// http://stackoverflow.com/questions/5560248/programmatically-lighten-or-darken-a-hex-color-or-rgb-and-blend-colors
				function blendColors(c0, c1, p) {
					var f=parseInt(c0.slice(1),16),t=parseInt(c1.slice(1),16),R1=f>>16,G1=f>>8&0x00FF,B1=f&0x0000FF,R2=t>>16,G2=t>>8&0x00FF,B2=t&0x0000FF;
					return "#"+(0x1000000+(Math.round((R2-R1)*p)+R1)*0x10000+(Math.round((G2-G1)*p)+G1)*0x100+(Math.round((B2-B1)*p)+B1)).toString(16).slice(1);
				}

				$('#fixture').thermometer( {
					startValue:0,
					height: 700,
					width: "100%",
					bottomText: "5",
					topText: "30",
					animationSpeed: 100,
					maxValue: 30,
					minValue: 5,
					liquidColour: function( value ) {
						return blendColors("#00ff","#0000ff", (value / 10)+5); 
					},
					valueChanged: function(value) {
						$('#value').text(value.toFixed(2));
					}
				});

				window.setInterval( function() {
					//var m = Math.random() * 10;
					$.get('data.php',function(dato){
					m=dato;
					});
					$('#fixture').thermometer( 'setValue', m );
				}, 2000 );
			} );
		</script>

		<style type="text/css">
			#value { width: 160px; text-align: center; }
		</style>
	</head>

	<body>
		<h2 id="value"></h2>
		<div id="fixture"></div>
	<h2>----<?=$data;?>----</h2>
	</body>
</html>

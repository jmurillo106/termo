<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8" />
      <title>termometro</title>
      <link rel="stylesheet" href="style.css" />
      <link rel="stylesheet" href="jonstipe-meter-polyfill/meter-polyfill.css" />
   </head>
   <body>
      <!--
         This div contains the <meter> used to show the temperature.
         It also contains the thermometer labels
      -->
      <div id="thermometer-wrapper">
         <meter id="thermometer" min="-10" max="30" value="3"></meter>
         <div id="labels"></div>
      </div>
      <!--
         This div contains the text that shows the temperature
         of the chosen or retrieved location
      -->
      <div id="info">
         Temperatura<span id="city">-</span> 
         <output id="temperature" form="request">-</output>
         <p id="error"></p>
      </div>
      <!--
         This form is used by the user to write the location
         of which he or she wants to know the temperature
      -->
      <form id="request">
         <label for="location">freezer num:</label>
         <input type="text" id="location" name="location" value=1 size="10" placeholder="1" required />
         
         <br /><br />
         <input type="submit" value="Get temperature!" />
      </form>

      <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
      <script src="//maps.google.com/maps/api/js?sensor=true"></script>
      <script src="jonstipe-meter-polyfill/meter-polyfill.min.js"></script>
      <script src="thermometer.js"></script>
      <script>
         // Creates and initializes a Thermometer object
         var thermometer = new Thermometer({
            thermometerId: 'thermometer',
            labelsId: 'labels',
            errorId: 'error',
            cityId: 'city',
            temperatureId: 'temperature'
         });
         var $location = $('21394,mx');

         // When a new request is done, the previous data are
         // cleared and then the request to retrieve the WOEID code
         // (http://developer.yahoo.com/geo/geoplanet/guide/concepts.html)
         // is performed
         $('#request').submit(function(event) {
            event.preventDefault();

            thermometer
               .resetInfo()
               .requestWOEID($location.val());
         });

         // When the user requests to retrieve the current position, a call
         // to the geolocation API is performed. If the function succeeds,
         // a call to the Google Maps API is made to convert the latitude
         // and the longitude retrieved using geolocation API into an address
         $('#my-position').click(function() {
            thermometer.resetInfo();
            var $error = $('#error');

            if (!navigator.geolocation) {
               $error.text('Your browser does not support the geolocation API');
            } else {
               navigator.geolocation.getCurrentPosition(
                  function(position) {
                     // Performs a request to the Google maps API to retrieve
                     // an address using the user current location
                     new google.maps.Geocoder().geocode(
                        {
                           location: new google.maps.LatLng(position.coords.latitude, position.coords.longitude)
                        },
                        function(results, status) {
                           if (status == google.maps.GeocoderStatus.OK) {
                              $location.val(results[0].formatted_address);
                           } else {
                              $error.text('Unable to retrieve location');
                           }
                        }
                     );
                  },
                  function() {
                     $error.text('Unable to retrieve location');
                  },
                  {
                     timeout: 10 * 1000, // 10 seconds
                     maximinAge: 10 * 60 * 1000 // 10 minutes
                  }
               );
            }
         });
      </script>
   </body>
</html>

var lat = 0;
var lon = 0;
$.getJSON("http://ip-api.com/json/?callback=?", function(data) {
	lat = data.lat;
	lon = data.lon;
        console.log('lat-lon-2', lat, lon)
});
console.log('lat-lon', lat, lon);
function blurElement(selector, blur) {
    $(selector).css({
        '-webkit-filter'	: 'blur('+blur+'px)',
        '-moz-filter'		: 'blur('+blur+'px)',
        '-o-filter'			: 'blur('+blur+'px)',
        '-ms-filter'		: 'blur('+blur+'px)',
        'filter'   			: 'blur('+blur+'px)'
    });
}

function unblurElement(selector) {
    $(selector).css({
        '-webkit-filter'	: 'blur(0)',
        '-moz-filter'		: 'blur(0)',
        '-o-filter'			: 'blur(0)',
        '-ms-filter'		: 'blur(0)',
        'filter'   			: 'blur(0)'
    });
}

function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
    var R = 6371; // Radius of the earth in km
    var dLat = deg2rad(lat2-lat1);  // deg2rad below
    var dLon = deg2rad(lon2-lon1);
    var a =
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
        Math.sin(dLon/2) * Math.sin(dLon/2)
    ;
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var d = R * c; // Distance in km
    return d;
}

function deg2rad(deg) {
    return deg * (Math.PI/180)
}

function getLocation(cb, err, options)  {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(cb, err, options);
    } else {
        alert( 'La fonction de géolocalisation n\'est pas disponible sur votre navigateur' );
    }
}
/*
var cPos = [14,-17];

function cb(position) {
    cPos[0] = position.coords.latitude;
    cPos[1] = position.coords.longitude;
}
*/
function compare(a,b) {
    if (a.dist < b.dist)
        return -1;
    if (a.dist > b.dist)
        return 1;
    return 0;
}

function showLayer(){
    $('#layer').show();
    blurElement('#wrapper', 10);
}
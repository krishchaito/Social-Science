var googleMap;
var marker;

$(document).ready(function() {
    // Start nice scroll on HTML element
    $("html").niceScroll({cursorcolor:"#0070AF", autohidemode:false});

    // Adjust scroll when window resizes.
    $(window).resize(function() {
        $("html").getNiceScroll().resize();
    });

    // Show/Stop loader on/after ajax request
    $(document).on({
        ajaxStart: function() { $("#loading").show()},
        ajaxStop: function() { $("#loading").hide()}
    });
});

// Opens a new popup window to show print content
function printContent(contentId) {
    var content = $('#'+contentId);
    if(content.length) {
        var printWindow = window.open('','PrintWindow','width=700,height=700,top=250,left=345,toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no');

        if(printWindow) {
            printWindow.document.write(content.html());
            printWindow.document.close();
            printWindow.focus();

            setTimeout(function() {
                if(printWindow.print()) {
                    printWindow.close();
            }}, 3000);
        }
    }
}

// Takes UTC DateTime and returns localized DateTime
function getLocalizedTime(dateTimeStr) {
    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    var date = new Date(dateTimeStr + ' UTC');
    var localizedDateTimeStr = months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear() + ' ' + date.toLocaleTimeString();
    return localizedDateTimeStr;
}

function placeMarkerOnMap(latlong, message) {
    marker = new google.maps.Marker({
        position: latlong,
        map: googleMap,
        title: message
    });

    return marker;
}

// notifications
var notificationTimer;
function showNotification(params) {
    clearTimeout(notificationTimer);
    clearNotification();

    var options = {
        'duration': 2,
        'autoClose': true,
        'type': 'success',
        'message': ''
    };

    $.extend(true, options, params);
    $("#notification div:first").addClass('notification notification-'+options.type).html(options.message).show();

    if(options.autoClose) {
        notificationTimer = setTimeout(function() {
            clearTimeout(notificationTimer);
            clearNotification();
        }, options.duration * 1000);
    }
}

function clearNotification() {
    $("#notification div:first").removeClass().html('').hide();
}

$(function() {

    $("#lang-switch").niftyLanguage({
        onChange: function(e) {
            $.niftyNoty({
                type: "info",
                icon: "fa fa-info fa-lg",
                title: "Language changed",
                message: "The language apparently changed, the selected language is : <strong> " + e.id + " " + e.name + "</strong> "
                //timer:3000
            })
        }
    });

    $('.collapse').collapse()
    $('[data-toggle="tooltip"]').tooltip()


    function googleMap() {
        this.el = '';
        this.isRendered = false;
        this.center = null;
        this.instance = null;
        this.gMap = function() {
            var mapOps = {
                center: new google.maps.LatLng(25.069877,55.139386),
                zoom: 17,
                disableDefaultUI: !0
            };
            return new google.maps.Map(document.getElementById(this.el), mapOps);
        };
        this.gMarker = function() {
            this.instance = this.gMap();
            this.center = this.instance.getCenter();
            var markerOps = {
                position: new google.maps.LatLng(25.068956,55.138828),
                map: this.instance,
                title: 'Print Arabia'            
            };
            this.isRendered = true;
            return new google.maps.Marker(markerOps);
        };
        this.render = function() {
            if(this.el == '')
                throw 'Missing element id!';

            if(this.isRendered) {
                this.rerender();
            }
            else {
                this.gMarker();
            }
        };
        this.rerender = function() {
            google.maps.event.trigger(this.instance, 'resize');
            this.instance.setCenter(this.center);            
        };
    }

    gmap1 = new googleMap;
    gmap1.el = 'google-map1';
    $('#contact-google-map').on('mouseover', function() {
        gmap1.render();
    });

});
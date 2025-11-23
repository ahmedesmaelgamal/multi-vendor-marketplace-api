@extends('admin.layouts.master')
@section('title')
    {{ $setting->title ?? '' }} | {{ $user->name }}
@endsection
@section('page_name')
    بيانات العميل
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="wideget-user text-center">
                        <div class="wideget-user-desc">
                            <div class="wideget-user-img">
                                <img class="" src="{{ $user->image }}" alt="img">
                            </div>
                            <div class="user-wrap">
                                <h4 class="mb-1">{{ $user->name }}</h4>
                                <h6 class="text-muted mb-4">
                                    وقت الانضمام : {{ $user->created_at->diffForHumans() }}</h6>
                                @if ($average != 0)
                                    <div class="wideget-user-rating">
                                        <a href="#">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fa fa-star {{ round($average) >= $i ? 'text-warning' : 'text-default' }}"></i>
                                            @endfor
                                        </a>
                                        <span>{{ round($average, 1) }} ({{ $count }} تقييم)</span>
                                    </div>
                                @endif
                                <a href="tel:{{ $user->phone_code . $user->phone }}" class="btn btn-primary mt-2 mb-1"><i
                                        class="fa fa-phone"></i> اتصال </a>
                                <a href="mailto:{{ $user->email }}" class="btn btn-secondary mt-2 mb-1"><i
                                        class="fa fa-envelope"></i> تواصل </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="float-left">
                        <h3 class="card-title">بيانات شخصية</h3>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body wideget-user-contact">

                    <div class="media mb-5 mt-0">
                        <div class="d-flex ml-3"><span class="user-contact-icon bg-success"><i
                                    class="fa fa-envelope text-white"></i></span></div>
                        <div class="media-body"><a href="#" class="text-dark">الرقم التجاري</a>
                            <div class="text-muted fs-14">{{ $user->vat_number }}</div>
                        </div>
                    </div>

                    <div class="media mb-5 mt-0">
                        <div class="d-flex ml-3"><span class="user-contact-icon bg-primary"><i
                                    class="fa fa-envelope text-white"></i></span></div>
                        <div class="media-body"><a href="mailto:{{ $user->email }}" class="text-dark">البريد</a>
                            <div class="text-muted fs-14">{{ $user->email }}</div>
                        </div>
                    </div>
                    @foreach ($user->addresses as $address)
                        <div class="media mb-5 mt-0">
                            <div class="d-flex ml-3"><span class="user-contact-icon bg-secondary"><i
                                        class="fa fa-globe text-white"></i></span></div>
                            <div class="media-body"><a href="#" class="text-dark">عنوان {{ $loop->iteration }}</a>
                                <div class="text-muted fs-14">{{ $address->address }}</div>
                            </div>
                        </div>
                    @endforeach
                    <div class="media mb-0 mt-0">
                        <div class="d-flex ml-3"><span class="user-contact-icon bg-warning"><i
                                    class="fa fa-phone text-white"></i></span></div>
                        <div class="media-body"><a href="tel:{{ $user->phone_code . $user->phone }}"
                                class="text-dark">الهاتف</a>
                            <div class="text-muted fs-14">{{ $user->phone_code . $user->phone }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="wideget-user-tab">
                    <div class="tab-menu-heading">
                        <div class="tabs-menu1">
                            <ul class="nav">
                                <li class=""><a href="#tab-51" class="show active" data-toggle="tab">الطلبات <i
                                            class="fa fa-shopping-cart"></i> </a>
                                </li>


                                <li><a href="#tab-61" data-toggle="tab" class="">أحدث موقع <i
                                            class="fa fa-map-marked mr-1"></i> </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>




            <div class="tab-content">
                <div class="tab-pane show active" id="tab-51">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <a href="{{ route('showOrdersUser', $user->id) }}"
                                    class="btn btn-primary btn-sm float-left">
                                    طلبات المستخدم
                                </a>
                            </div>

                            <div class="example clearfix" style="border:none ">
                                <ul class="list-group">
                                    <li class="list-group-item justify-content-between">
                                        طلبات جديدة
                                        <span
                                            class="badgetext badge badge-primary badge-pill">{{ $new_orders ?? 0 }}</span>
                                    </li>
                                    <li class="list-group-item justify-content-between">
                                        طلبات معلقة
                                        <span
                                            class="badgetext badge badge-warning badge-pill">{{ $offered_orders ?? 0 }}</span>
                                    </li>
                                    <li class="list-group-item justify-content-between">
                                        طلبات جارية
                                        <span
                                            class="badgetext badge badge-success badge-pill">{{ $current_orders ?? 0 }}</span>
                                    </li>
                                    <li class="list-group-item justify-content-between">
                                        طلبات منتهية
                                        <span
                                            class="badgetext badge badge-danger badge-pill">{{ $ended_orders ?? 0 }}</span>
                                    </li>
                                    <li class="list-group-item justify-content-between">
                                        طلبات ملغية
                                        <span
                                            class="badgetext badge badge-info badge-pill">{{ $canceled_orders ?? 0 }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="tab-pane" id="tab-61">
                    <div class="card">
                        <div class="card-body">
                            <div id="map" style="width: 100%;height: 500px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>
@endsection
@section('js')
    <script>
        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: {{ $user->addresses->last()->latitude }},
                    lng: {{ $user->addresses->last()->latitude }}
                },
                zoom: 13,
                mapTypeId: 'roadmap'
            });
            // move pin and current location
            infoWindow = new google.maps.InfoWindow;
            geocoder = new google.maps.Geocoder();
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                        lat: {{ $user->addresses->last()->latitude }},
                        lng: {{ $user->addresses->last()->latitude }}
                    };
                    map.setCenter(pos);
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(pos),
                        map: map,
                        title: 'موقعك الحالي'
                    });
                    markers.push(marker);
                    marker.addListener('click', function() {
                        geocodeLatLng(geocoder, map, infoWindow, marker);
                    });
                    // to get current position address on load
                    google.maps.event.trigger(marker, 'click');
                }, function() {
                    handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
            var geocoder = new google.maps.Geocoder();

            function geocodeLatLng(geocoder, map, infowindow, markerCurrent) {
                var latlng = {
                    lat: markerCurrent.position.lat(),
                    lng: markerCurrent.position.lng()
                };

                geocoder.geocode({
                    'location': latlng
                }, function(results, status) {
                    if (status === 'OK') {
                        if (results[0]) {
                            map.setZoom(8);
                            var marker = new google.maps.Marker({
                                position: latlng,
                                map: map
                            });
                            markers.push(marker);
                            infowindow.setContent(results[0].formatted_address);
                            SelectedLocation = results[0].formatted_address;
                            $("#pac-input").val(results[0].formatted_address);
                            infowindow.open(map, marker);
                            $('#user_address').text(results[0].formatted_address);
                        } else {
                            window.alert('No results found');
                        }
                    } else {
                        window.alert('فشل من تحديد الموقع حاول في وقت لاحق' + status);
                    }
                });
                SelectedLatLng = (markerCurrent.position.lat(), markerCurrent.position.lng());
            }

            function addMarkerRunTime(location) {
                var marker = new google.maps.Marker({
                    position: location,
                    map: map
                });
                markers.push(marker);
            }

            function setMapOnAll(map) {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(map);
                }
            }

            function clearMarkers() {
                setMapOnAll(null);
            }

            function deleteMarkers() {
                clearMarkers();
                markers = [];
            }

            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            $("#pac-input").val("أبحث هنا ");
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function() {
                searchBox.setBounds(map.getBounds());
            });
            var markers = [];

            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();
                if (places.length == 0) {
                    return;
                }

                markers.forEach(function(marker) {
                    marker.setMap(null);
                });
                markers = [];

                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    if (!place.geometry) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var icon = {
                        url: place.icon,
                        size: new google.maps.Size(100, 100),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25)
                    };
                    // Create a marker for each place.
                    markers.push(new google.maps.Marker({
                        map: map,
                        icon: icon,
                        title: place.name,
                        position: place.geometry.location
                    }));
                    $('#latitude').val(place.geometry.location.lat());
                    $('#longitude').val(place.geometry.location.lng());
                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ?
                'Error: فشل في تحديد الموقع حاول في وقت لاحق' :
                'Error: المتصفح الخاص بك لا يدعم الخرائط');
            infoWindow.open(map);
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPraQRuDWeIUxsjaCt_Q-l-MFe2snY2ds&libraries=places&callback=initAutocomplete&language=ar&region=EG
                                                                                        async defer"></script>
@endsection

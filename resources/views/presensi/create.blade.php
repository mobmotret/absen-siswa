 @extends('layouts.presensi')
 @section('header')
     <!-- App Header -->
     <div class="appHeader bg-primary text-light">
         <div class="left">
             <a href="javascript:;" class="headerButton goBack">
                 <ion-icon name="chevron-back-outline"></ion-icon>
             </a>
         </div>
         <div class="pageTitle">Absen Siswa RPL</div>
         <div class="right"></div>
     </div>
     <!-- * App Header -->
     <style>
         .webcam-capture,
         .webcam-capture video {
             display: inline-block;
             width: 100% !important;
             margin: auto;
             height: auto !important;
             border-radius: 15px;
         }

         #map {
             height: 200px;
         }
     </style>

     <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
 @endsection
 @section('content')
     <div class="row" style="margin-top: 70px;">
         <div class="col">
             <input type="text" id="lokasi">
             <div class="webcam-capture"></div>
         </div>
     </div>
     <div class="row">
         <div class="col">
             <button id="takeabsen" class="btn btn-primary btn-block">
                 <ion-icon name="camera-outline"></ion-icon>
                 Absen Masuk</button>
         </div>
     </div>
     <div class="roww mt-2">
         <div class="col">
             <div id="map"></div>
         </div>
     </div>
 @endsection

 @push('myscript')
     <script>
         Webcam.set({
             height: 480,
             width: 640,
             image_format: 'jpeg',
             jpeg_quality: 80
         });

         Webcam.attach('.webcam-capture');

         var lokasi = document.getElementById('lokasi');
         if (navigator.geolocation) {
             navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
         }

         function successCallback(position) {
             lokasi.value = position.coords.latitude + "," + position.coords.longitude;
             var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 15);
             L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                 maxZoom: 19,
                 attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
             }).addTo(map);
             var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
             var circle = L.circle([position.coords.latitude, position.coords.longitude], {
                 color: 'red',
                 fillColor: '#f03',
                 fillOpacity: 0.5,
                 radius: 500
             }).addTo(map);

         }

         function errorCallback() {

         }

         $("#takeabsen").click(function(e) {
             console.log('tombol ditekan');
             Webcam.snap(function(uri) {
                 image = uri;
             });
             var lokasi = $("#lokasi").val();
             $.ajax({
                 type: "POST",
                 url: '/presensi/store',
                 data: {
                     _token: "{{ csrf_token() }}",
                     image: image,
                     lokasi: lokasi
                 },
                 cache: false,
                 success: function(respond) {
                     if (respond.status === true) {
                         alert(respond.message);
                     }
                 },
                 error: function(err) {
                     alert(err.responseJSON.message);
                 }
             });
         });
     </script>
 @endpush

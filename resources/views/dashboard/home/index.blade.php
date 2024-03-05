@extends('layouts.index')

@section('title', 'Home')

@section('style')
<style>
  .page-item.active .page-link {
    color: #FFF !important;
    background-color: ##1E9FF2 !important;
    border-color: ##1E9FF2 !important;
  }
  .pagination .page-lin {
    color: blue !important;
  }
  
  .modal-md-lg{
        max-width: 400px !important;
    }
    .map-on-modal{
        height: 345px !important;
    }
    
    .progress-circle {
       font-size: 20px;
       margin: 20px;
       position: relative; /* so that children can be absolutely positioned */
       padding: 0;
       width: 5em;
       height: 5em;
       background-color: #F2E9E1; 
       border-radius: 50%;
       line-height: 5em;
    }
    
    .progress-circle:after{
        border: none;
        position: absolute;
        top: 0.35em;
        left: 0.35em;
        text-align: center;
        display: block;
        border-radius: 50%;
        width: 4.3em;
        height: 4.3em;
        background-color: white;
        content: " ";
    }
    /* Text inside the control */
    .progress-circle span {
        position: absolute;
        line-height: 5em;
        width: 5em;
        text-align: center;
        display: block;
        color: #53777A;
        z-index: 2;
    }
    .left-half-clipper { 
       /* a round circle */
       border-radius: 50%;
       width: 5em;
       height: 5em;
       position: absolute; /* needed for clipping */
       clip: rect(0, 5em, 5em, 2.5em); /* clips the whole left half*/ 
    }
    /* when p>50, don't clip left half*/
    .progress-circle.over50 .left-half-clipper {
       clip: rect(auto,auto,auto,auto);
    }
    .value-bar {
       /*This is an overlayed square, that is made round with the border radius,
       then it is cut to display only the left half, then rotated clockwise
       to escape the outer clipping path.*/ 
       position: absolute; /*needed for clipping*/
       clip: rect(0, 2.5em, 5em, 0);
       width: 5em;
       height: 5em;
       border-radius: 50%;
       border: 0.45em solid #53777A; /*The border is 0.35 but making it larger removes visual artifacts */
       /*background-color: #4D642D;*/ /* for debug */
       box-sizing: border-box;
      
    }
    /* Progress bar filling the whole right half for values above 50% */
    .progress-circle.over50 .first50-bar {
       /*Progress bar for the first 50%, filling the whole right half*/
       position: absolute; /*needed for clipping*/
       clip: rect(0, 5em, 5em, 2.5em);
       background-color: #53777A;
       border-radius: 50%;
       width: 5em;
       height: 5em;
    }
    .progress-circle:not(.over50) .first50-bar{ display: none; }
    
    
    /* Progress bar rotation position */
    .progress-circle.p0 .value-bar { display: none; }
    .progress-circle.p1 .value-bar { transform: rotate(4deg); }
    .progress-circle.p2 .value-bar { transform: rotate(7deg); }
    .progress-circle.p3 .value-bar { transform: rotate(11deg); }
    .progress-circle.p4 .value-bar { transform: rotate(14deg); }
    .progress-circle.p5 .value-bar { transform: rotate(18deg); }
    .progress-circle.p6 .value-bar { transform: rotate(22deg); }
    .progress-circle.p7 .value-bar { transform: rotate(25deg); }
    .progress-circle.p8 .value-bar { transform: rotate(29deg); }
    .progress-circle.p9 .value-bar { transform: rotate(32deg); }
    .progress-circle.p10 .value-bar { transform: rotate(36deg); }
    .progress-circle.p11 .value-bar { transform: rotate(40deg); }
    .progress-circle.p12 .value-bar { transform: rotate(43deg); }
    .progress-circle.p13 .value-bar { transform: rotate(47deg); }
    .progress-circle.p14 .value-bar { transform: rotate(50deg); }
    .progress-circle.p15 .value-bar { transform: rotate(54deg); }
    .progress-circle.p16 .value-bar { transform: rotate(58deg); }
    .progress-circle.p17 .value-bar { transform: rotate(61deg); }
    .progress-circle.p18 .value-bar { transform: rotate(65deg); }
    .progress-circle.p19 .value-bar { transform: rotate(68deg); }
    .progress-circle.p20 .value-bar { transform: rotate(72deg); }
    .progress-circle.p21 .value-bar { transform: rotate(76deg); }
    .progress-circle.p22 .value-bar { transform: rotate(79deg); }
    .progress-circle.p23 .value-bar { transform: rotate(83deg); }
    .progress-circle.p24 .value-bar { transform: rotate(86deg); }
    .progress-circle.p25 .value-bar { transform: rotate(90deg); }
    .progress-circle.p26 .value-bar { transform: rotate(94deg); }
    .progress-circle.p27 .value-bar { transform: rotate(97deg); }
    .progress-circle.p28 .value-bar { transform: rotate(101deg); }
    .progress-circle.p29 .value-bar { transform: rotate(104deg); }
    .progress-circle.p30 .value-bar { transform: rotate(108deg); }
    .progress-circle.p31 .value-bar { transform: rotate(112deg); }
    .progress-circle.p32 .value-bar { transform: rotate(115deg); }
    .progress-circle.p33 .value-bar { transform: rotate(119deg); }
    .progress-circle.p34 .value-bar { transform: rotate(122deg); }
    .progress-circle.p35 .value-bar { transform: rotate(126deg); }
    .progress-circle.p36 .value-bar { transform: rotate(130deg); }
    .progress-circle.p37 .value-bar { transform: rotate(133deg); }
    .progress-circle.p38 .value-bar { transform: rotate(137deg); }
    .progress-circle.p39 .value-bar { transform: rotate(140deg); }
    .progress-circle.p40 .value-bar { transform: rotate(144deg); }
    .progress-circle.p41 .value-bar { transform: rotate(148deg); }
    .progress-circle.p42 .value-bar { transform: rotate(151deg); }
    .progress-circle.p43 .value-bar { transform: rotate(155deg); }
    .progress-circle.p44 .value-bar { transform: rotate(158deg); }
    .progress-circle.p45 .value-bar { transform: rotate(162deg); }
    .progress-circle.p46 .value-bar { transform: rotate(166deg); }
    .progress-circle.p47 .value-bar { transform: rotate(169deg); }
    .progress-circle.p48 .value-bar { transform: rotate(173deg); }
    .progress-circle.p49 .value-bar { transform: rotate(176deg); }
    .progress-circle.p50 .value-bar { transform: rotate(180deg); }
    .progress-circle.p51 .value-bar { transform: rotate(184deg); }
    .progress-circle.p52 .value-bar { transform: rotate(187deg); }
    .progress-circle.p53 .value-bar { transform: rotate(191deg); }
    .progress-circle.p54 .value-bar { transform: rotate(194deg); }
    .progress-circle.p55 .value-bar { transform: rotate(198deg); }
    .progress-circle.p56 .value-bar { transform: rotate(202deg); }
    .progress-circle.p57 .value-bar { transform: rotate(205deg); }
    .progress-circle.p58 .value-bar { transform: rotate(209deg); }
    .progress-circle.p59 .value-bar { transform: rotate(212deg); }
    .progress-circle.p60 .value-bar { transform: rotate(216deg); }
    .progress-circle.p61 .value-bar { transform: rotate(220deg); }
    .progress-circle.p62 .value-bar { transform: rotate(223deg); }
    .progress-circle.p63 .value-bar { transform: rotate(227deg); }
    .progress-circle.p64 .value-bar { transform: rotate(230deg); }
    .progress-circle.p65 .value-bar { transform: rotate(234deg); }
    .progress-circle.p66 .value-bar { transform: rotate(238deg); }
    .progress-circle.p67 .value-bar { transform: rotate(241deg); }
    .progress-circle.p68 .value-bar { transform: rotate(245deg); }
    .progress-circle.p69 .value-bar { transform: rotate(248deg); }
    .progress-circle.p70 .value-bar { transform: rotate(252deg); }
    .progress-circle.p71 .value-bar { transform: rotate(256deg); }
    .progress-circle.p72 .value-bar { transform: rotate(259deg); }
    .progress-circle.p73 .value-bar { transform: rotate(263deg); }
    .progress-circle.p74 .value-bar { transform: rotate(266deg); }
    .progress-circle.p75 .value-bar { transform: rotate(270deg); }
    .progress-circle.p76 .value-bar { transform: rotate(274deg); }
    .progress-circle.p77 .value-bar { transform: rotate(277deg); }
    .progress-circle.p78 .value-bar { transform: rotate(281deg); }
    .progress-circle.p79 .value-bar { transform: rotate(284deg); }
    .progress-circle.p80 .value-bar { transform: rotate(288deg); }
    .progress-circle.p81 .value-bar { transform: rotate(292deg); }
    .progress-circle.p82 .value-bar { transform: rotate(295deg); }
    .progress-circle.p83 .value-bar { transform: rotate(299deg); }
    .progress-circle.p84 .value-bar { transform: rotate(302deg); }
    .progress-circle.p85 .value-bar { transform: rotate(306deg); }
    .progress-circle.p86 .value-bar { transform: rotate(310deg); }
    .progress-circle.p87 .value-bar { transform: rotate(313deg); }
    .progress-circle.p88 .value-bar { transform: rotate(317deg); }
    .progress-circle.p89 .value-bar { transform: rotate(320deg); }
    .progress-circle.p90 .value-bar { transform: rotate(324deg); }
    .progress-circle.p91 .value-bar { transform: rotate(328deg); }
    .progress-circle.p92 .value-bar { transform: rotate(331deg); }
    .progress-circle.p93 .value-bar { transform: rotate(335deg); }
    .progress-circle.p94 .value-bar { transform: rotate(338deg); }
    .progress-circle.p95 .value-bar { transform: rotate(342deg); }
    .progress-circle.p96 .value-bar { transform: rotate(346deg); }
    .progress-circle.p97 .value-bar { transform: rotate(349deg); }
    .progress-circle.p98 .value-bar { transform: rotate(353deg); }
    .progress-circle.p99 .value-bar { transform: rotate(356deg); }
    .progress-circle.p100 .value-bar { transform: rotate(360deg); }
</style>
@endsection

@section('content-header')
    <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h4 class="content-header-title mb-0 d-inline-block">Home</h4>
        <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">Home</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-content collapse show">
                            <div class="card-body dashboard">
                                <div class="media d-flex">
                                    <div class="media-body">
                                        <h1 class="display-5">{{ $people }}</h1>
                                        <span class="text-muted">Jumlah Warga</span>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="la la-users font-large-2 blue-grey lighten-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="sp-line-total-cost"></div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-content collapse show">
                            <div class="card-body dashboard">
                                <div class="media d-flex">
                                    <div class="media-body">
                                        <h1 class="display-5">{{ $user }}</h1>
                                        <span class="text-muted">Jumlah User</span>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="la la-user font-large-2 blue-grey lighten-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="sp-line-total-cost"></div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-content collapse show">
                            <div class="card-body dashboard">
                                <div class="media d-flex">
                                    <div class="media-body">
                                        <h1 class="display-5">{{ $admin }}</h1>
                                        <span class="text-muted">Jumlah Admin</span>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="la la-user-lock font-large-2 blue-grey lighten-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="sp-line-total-cost"></div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-content collapse show">
                            <div class="card-body dashboard">
                                <div class="media d-flex">
                                    <div class="media-body">
                                        <h1 class="display-5">{{ $family_card }}</h1>
                                        <span class="text-muted">Jumlah KK</span>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="la la-address-card font-large-2 blue-grey lighten-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="sp-line-total-cost"></div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-content collapse show">
                            <div class="card-body dashboard">
                                <div class="media d-flex">
                                    <div class="media-body">
                                        <h1 class="display-5">{{ $house }}</h1>
                                        <span class="text-muted">Jumlah Rumah</span>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="la la-home font-large-2 blue-grey lighten-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="sp-line-total-cost"></div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-content collapse show">
                            <div class="card-body dashboard">
                                <div class="media d-flex">
                                    <div class="media-body">
                                        <h1 class="display-5">{{ $empty_house }}</h1>
                                        <span class="text-muted">Jumlah Rumah Kosong</span>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="la la-house-damage font-large-2 blue-grey lighten-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="sp-line-total-cost"></div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-content collapse show">
                            <div class="card-body dashboard">
                                <div class="media d-flex">
                                    <div class="media-body">
                                        <h1 class="display-5">{{ $man }}</h1>
                                        <span class="text-muted">Jumlah laki-laki</span>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="la la-male font-large-2 blue-grey lighten-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="sp-line-total-cost"></div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-content collapse show">
                            <div class="card-body dashboard">
                                <div class="media d-flex">
                                    <div class="media-body">
                                        <h1 class="display-5">{{ $woman }}</h1>
                                        <span class="text-muted">Jumlah perempuan</span>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="la la-female font-large-2 blue-grey lighten-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="sp-line-total-cost"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">List Aktifitas</h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        <li><a data-action="close"><i class="ft-x"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body card-dashboard">
                    <table class="table table-striped table-bordered zero-configuration datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Aktifitas</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($activity_history as $item)
                                <tr>
                                    <td class="text-capitalize">{{ $no++ }}</td>
                                    <td class="text-capitalize">
                                        {{ $item->user->family_member->family_member_name }}
                                    </td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfooter>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Aktifitas</th>   
                                <th>Waktu</th>
                            </tr>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <div class="modal fade" id="editStatusMarkerModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info white">
                    <h4 class="modal-title white">Ubah status</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="" id="editStatusMarkerForm" method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="">Status</label>
                            <select class="form-control" name="status" id="editStatus" required>
                                <option value="" hidden>Pilih</option>
                                <option value="diproses">Kasus diproses</option>
                                <option value="ditolak">Kasus ditolak</option>
                                <option value="selesai">Kasus selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-outline-info">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
    var greenIcon = new L.Icon({
      iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41]
    });
    
    var redIcon = new L.Icon({
      iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41]
    });
    
    var orangeIcon = new L.Icon({
      iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41]
    });
    
    var blackIcon = new L.Icon({
      iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-black.png',
      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41]
    });
    
    let showFormMap = L.map("showMap").setView([-1.2332402, 116.8700904], 13);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", 
    {
        attribution: "&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors"
    }).addTo(showFormMap);
    
    let showFormMarker = undefined;
    
    function marker() {
       $.ajax(
        {
            method: "GET",
            url: "/get-data-panic-button"
        }).done(function (response)
        {
            
            if (showFormMarker != undefined) {
                showFormMap.removeLayer(showFormMarker);
            };
            response.forEach(item => {
                if(item.status == 'menunggu') {
                    showFormMarker = L.marker([item.lat, item.lng],
                    {
                        id: item.id,
                        icon: redIcon,
                        draggable : false
                    }).addTo(showFormMap).bindPopup("<p class='text-capitalize'>Rumah: "+ item.house_number +"</p><p>Nama: "+ item.username +"</p><p>Telepon: "+ item.phone_number +"</p>").openPopup().on('click', onMapClick);
                }
                
                if(item.status == 'diproses') {
                    showFormMarker = L.marker([item.lat, item.lng],
                    {
                        id: item.id,
                        icon: orangeIcon,
                        draggable : false
                    }).addTo(showFormMap).bindPopup("<p class='text-capitalize'>Rumah: "+ item.house_number +"</p><p>Nama: "+ item.username +"</p><p>Telepon: "+ item.phone_number +"</p>").openPopup().on('click', onMapClick);
                }
                
                if(item.status == 'ditolak') {
                    showFormMarker = L.marker([item.lat, item.lng],
                    {
                        id: item.id,
                        icon: blackIcon,
                        draggable : false
                    }).addTo(showFormMap).bindPopup("<p class='text-capitalize'>Rumah: "+ item.house_number +"</p><p>Nama: "+ item.username +"</p><p>Telepon: "+ item.phone_number +"</p>");
                }
                
                if(item.status == 'selesai') {
                    showFormMarker = L.marker([item.lat, item.lng],
                    {
                        id: item.id,
                        icon: greenIcon,
                        draggable : false
                    }).addTo(showFormMap).bindPopup("<p class='text-capitalize'>Rumah: "+ item.house_number +"</p><p>Nama: "+ item.username +"</p><p>Telepon: "+ item.phone_number +"</p>")
                }
            });
        })
    }
    
    $(document).ready(function(){
        marker();
    })
    
    function onMapClick(e) {
        console.log(e.target.options.id);
        
        let id = e.target.options.id;
        $.ajax(
        {
            method: "GET",
            url: "/ubah-status-marker/" + id + "/edit"
        }).done(function (response)
        {
            
            $("#editStatus option[value=\"" + response.status + "\"]").attr("selected", true);
            $("#editStatusMarkerForm").attr("action", "/ubah-status-marker/" + id)
            $("#editStatusMarkerModal").modal();
        })
    }
    
    setInterval(marker, 30000);
    
</script>
@endsection

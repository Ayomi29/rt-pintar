@extends('layouts.index')

@section('title', 'Manajemen rumah')

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
        max-width: 660px !important;
    }
    .map-on-modal{
        height: 300px !important;
    }
</style>
@endsection

@section('content-header')
    <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">Manajemen rumah</h3>
        <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Rumah
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12">
        <div class="btn-group float-md-right">
            <button class="btn btn-info rounded-0 mb-1" id="createHouseButton" type="button">Tambah</button>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">List rumah</h4>
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
                                <th>Nomor rumah</th>
                                <th width="10%">Aksi</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($house as $item)
                                <tr>
                                    <td class="text-capitalize">{{ $no++ }}</td>
                                    <td class="text-capitalize">{{ $item->house_number }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">
                                                <i class="la la-cog"></i>
                                            </button>
                                            <div class="dropdown-menu" style="min-width: 9rem !important">         
                                                <button class="dropdown-item editHouseButton" value="{{ $item->id }}">
                                                    <i class="la la-edit"></i> Ubah
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfooter>
                            <tr>
                                <th>No</th>
                                <th>Nomor rumah</th>
                                <th width="10%">Aksi</th>                                                  
                            </tr>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">List Rumah Kosong</h4>
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
                                <th>Nomor rumah</th>
                                <th width="10%">Aksi</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($empty_house as $item)
                                <tr>
                                    <td class="text-capitalize">{{ $no++ }}</td>
                                    <td class="text-capitalize">{{ $item->house_number }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">
                                                <i class="la la-cog"></i>
                                            </button>
                                            <div class="dropdown-menu" style="min-width: 9rem !important">         
                                                <button class="dropdown-item editHouseButton" value="{{ $item->id }}">
                                                    <i class="la la-edit"></i> Ubah
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfooter>
                            <tr>
                                <th>No</th>
                                <th>Nomor rumah</th>
                                <th width="10%">Aksi</th>                                                  
                            </tr>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createHouseModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white">Tambah rumah</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="/houses" method="post">
                <div class="modal-body">
                    @csrf
                    
                    <div class="form-group">
                        <label for="">Nomor rumah</label>
                        <input type="text" name="house_number" placeholder="Masukkan nomor rumah" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Nomor Kartu Keluarga</label>
                        <input type="text" name="family_card_number" placeholder="Masukkan nomor kartu keluarga" class="form-control input-number">
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

<div class="modal fade" id="editHouseModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white">Ubah rumah</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" id="editHouseForm" method="post">
                <div class="modal-body">
                    @csrf
                    @method("PUT")
                    
                    <div class="form-group">
                        <label for="">Nomor rumah</label>
                        <input type="text" name="house_number" id="editHouseNumber" placeholder="Masukkan nomor rumah" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Longitude Rumah</label>
                        <input type="text" id="editLongitude" name="longitude" placeholder="Masukkan longitude rumah" class="form-control input-currency input-number">
                    </div>
                    <div class="form-group">
                        <label for="">Latitude Rumah</label>
                        <input type="text" id="editLatitude" name="latitude" placeholder="Masukkan latitude rumah" class="form-control input-currency input-number">
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
       
        
        $(document).on("click", "#createHouseButton", function ()
        {
            $("#createHouseModal").modal();
        });

        $(document).on("click", ".editHouseButton", function()
        {
            let id = $(this).val();
            $.ajax(
            {
                method: "GET",
                url: "{{ route('houses.index') }}/" + id + "/edit"
            }).done(function (response)
            {
                
                $("#editHouseNumber").val(response.house_number);
                $("#editLongitude").val(response.longitude);
                $("#editLatitude").val(response.latitude);
                $("#editHouseModal").modal();
                $("#editHouseForm").attr("action", "{{ route('houses.index') }}/" + id)
            })
        });

    </script>
@endsection

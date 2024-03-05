@extends('layouts.index')

@section('title', 'Manajemen Pengumuman')

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
        <h3 class="content-header-title mb-0 d-inline-block">Manajemen Data Pengumuman</h3>
        <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Pengumuman
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12">
        <div class="btn-group float-md-right">
            <button class="btn btn-info rounded-0 mb-1" id="createButton" type="button">Tambah</button>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">List Pengumuman</h4>
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
                                <th>Judul Pengumuman</th>
                                <th>Kontent Pengumuman</th>
                                <th>Status Pengumuman</th>
                                <th width="10%">Aksi</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($notices as $item)
                                <tr>
                                    <td class="text-capitalize">{{ $no++ }}</td>
                                    <td class="text-capitalize">{{ $item->title }}</td>
                                    <td class="text-capitalize">{{ $item->description }}</td>
                                    <td class="text-center">
                                        @if($item->status == 'aktif')
                                            <h4><span class="badge badge-success">Aktif</span></h4>
                                        @else
                                            <h4><span class="badge badge-danger">Tidak aktif</span></h4>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">
                                                <i class="la la-cog"></i>
                                            </button>
                                            <div class="dropdown-menu" style="min-width: 9rem !important">         
                                                <button class="dropdown-item editButton" value="{{ $item->id }}">
                                                    <i class="la la-edit"></i> Ubah
                                                </button>
                                                <button class="dropdown-item deleteButton" value="{{ $item->id }}">
                                                    <i class="la la-trash"></i> Hapus
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
                                <th>Judul Pengumuman</th>
                                <th>Kontent Pengumuman</th>
                                <th>Status Pengumuman</th>
                                <th width="10%">Aksi</th>                                                  
                            </tr>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white">Tambah Pengumuman</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="/notices" method="post">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="">Judul Pengumuman</label>
                        <input type="text" name="title" placeholder="Masukkan Judul Pengumuman" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Deskripsi</label>
                        <input type="text" name="description" placeholder="Masukkan Deskripsi" class="form-control" required>
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

<div class="modal fade" id="editModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white">Ubah Pengumuman</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" id="editForm" method="post">
                <div class="modal-body">
                    @csrf
                    @method("PUT")
                    
                    <div class="form-group">
                        <label for="">Judul Pengumuman</label>
                        <input type="text" name="title" id="editTitle" placeholder="Masukkan Judul Pengumuman" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Deskripsi</label>
                        <input type="text" name="description" id="editDesc" placeholder="Masukkan Deskripsi" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Status Pengumuman</label>
                        <select name="status" class="form-control" id="editStatus" required>
                            <option value="aktif">Aktif</option>
                            <option value="tidak aktif">Tidak aktif</option>
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

<div class="modal fade" id="deleteModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white">Apa anda yakin ingin menghapus data ini?</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" id="deleteForm" method="post">
                <div class="modal-footer">
                    @csrf
                    @method("DELETE")
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-outline-danger">Iya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script>
       
        
        $(document).on("click", "#createButton", function ()
        {
            $("#createModal").modal();
        });

        $(document).on("click", ".editButton", function()
        {
            let id = $(this).val();
            $.ajax(
            {
                method: "GET",
                url: "{{ route('notices.index') }}/" + id + "/edit"
            }).done(function (response)
            {
                $("#editTitle").val(response.title);
                $("#editDesc").val(response.description);
                $("#editStatus option[value=\"" + response.status + "\"]").attr("selected", true);
                $("#editModal").modal();
                $("#editForm").attr("action", "{{ route('notices.index') }}/" + id)
            })
        });
        $(document).on("click", ".deleteButton", function()
        {
            let id = $(this).val();

            $("#deleteForm").attr("action", "{{ route('notices.index') }}/" + id)
            $("#deleteModal").modal();
        });

    </script>
@endsection

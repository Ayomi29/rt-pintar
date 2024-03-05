@extends('layouts.index')

@section('title', 'Manajemen Aduan Warga')

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
</style>
@endsection

@section('content-header')
    <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">Manajemen Aduan Warga</h3>
        <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Aduan Warga
                    </li>
                </ol>
            </div>
        </div>
    </div>
    
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">List Aduan Warga</h4>
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
                    <table class="table table-striped table-bordered table-responsive zero-configuration datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Warga</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th width="10%">Aksi</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($complaints as $item)
                                <tr>
                                    <td class="text-capitalize">{{ $no++ }}</td>
                                    <td class="text-capitalize">{{ $item->created_at }}</td>
                                    <td class="text-capitalize">{{ $item->user->family_member->family_member_name }}</td>
                                    <td class="text-capitalize">{{ $item->title }}</td>
                                    <td class="text-center">
                                        @if($item->status == 'diposting')
                                            <h4><span class="badge badge-success">Posting</span></h4>
                                        @elseif ($item->status == 'diselidiki')
                                            <h4><span class="badge badge-warning">Selidiki</span></h4>                                        
                                        @elseif ($item->status == 'tolak')
                                            <h4><span class="badge badge-danger">Tolak</span></h4>
                                        @elseif ($item->status == 'selesai')
                                            <h4><span class="badge badge-success">Selesai</span></h4>
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
                                                @if ($item->complaint_document == true)
                                                <a href="/complaints/{{ $item->id }}" class="dropdown-item">
                                                    <i class="la la-image"></i> Dokumentasi
                                                </a>
                                                @endif
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
                                <th>Tanggal</th>
                                <th>Nama Warga</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th width="10%">Aksi</th>                                
                            </tr>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white">Ubah Aduan Warga</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" id="editForm" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="">Judul</label>
                        <input type="text" name="title" placeholder="Masukkan judul" class="form-control" id="editTitle" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Deskripsi</label>
                        <textarea name="description" placeholder="Masukkan deskripsi" class="form-control" id="editDescription" readonly></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Status</label>
                        <select class="form-control" name="status" id="editStatus">
                            <option value="" hidden>Pilih</option>
                            <option value="selesai">Selesai</option>
                            <option value="diselidiki">Selidiki</option>
                            <option value="diposting">Posting</option>
                            <option value="tolak">Tolak</option>
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

        $(document).on("click", ".editButton", function()
        {
            let id = $(this).val();
            $.ajax(
            {
                method: "GET",
                // url: "{{ route('complaints.index') }}/" + id + "/edit"
                url: "/complaints/" + id + "/edit"
            }).done(function (response)
            {
                $("#editTitle").val(response.title);
                $("#editDescription").val(response.description);
                $("#editStatus option[value=\"" + response.status + "\"]").attr("selected", true);
                // $("#editForm").attr("action", "{{ route('complaints.index') }}/" + id)
                $("#editForm").attr("action", "/complaints/" + id)
                $("#editModal").modal();
            })
        });
        
        
        $(document).on("click", ".deleteButton", function()
        {
            let id = $(this).val();

            $("#deleteForm").attr("action", "/complaints/" + id)
            $("#deleteModal").modal();
        });
    </script>
@endsection

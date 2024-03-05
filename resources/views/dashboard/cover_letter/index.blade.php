@extends('layouts.index')

@section('title', 'Manajemen Surat Pengantar')

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
    <h3 class="content-header-title mb-0 d-inline-block">Manajemen Surat Pengantar</h3>
    <div class="row breadcrumbs-top d-inline-block">
        <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a>
                </li>
                <li class="breadcrumb-item active">Surat Pengantar
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
                <h4 class="card-title">List Surat Pengantar</h4>
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
                    <table class="table table-striped table-bordered zero-configuration table-responsive datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama pengirim</th>
                                <th>Link berkas</th>
                                <th>Nomor surat</th>
                                <th>Tahun surat</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($cover_letters as $item)
                            <tr>
                                <td class="text-capitalize">{{ $no++ }}</td>
                                <td class="text-capitalize" style="white-space: nowrap;">{{
                                    $item->family_member->family_member_name }}</td>
                                <td style="white-space: nowrap;">
                                    <a class="text-info" href="/cv-download?id={{ $item->id }}">
                                        Unduh berkas
                                    </a>
                                </td>
                                <td class="text-capitalize" style="white-space: nowrap;">{{ $item->letter_number }}</td>
                                <td class="text-capitalize" style="white-space: nowrap;">{{ $item->year }}</td>
                                <td class="text-capitalize" style="white-space: nowrap;">{{ $item->title }}</td>
                                <td>
                                    <div style="overflow-y: scroll; height: 75px; width: 200px;">
                                        {{ $item->description }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <h4>
                                        @if($item->status == 'menunggu')
                                        <span class="badge badge-warning">Menunggu</span>
                                        @endif
                                        @if($item->status == 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                        @endif
                                        @if($item->status == 'diterima')
                                        <span class="badge badge-success">Diterima</span>
                                        @endif
                                    </h4>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-info dropdown-toggle" type="button"
                                            data-toggle="dropdown">
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
                                <th>Nama pengirim</th>
                                <th>Link berkas</th>
                                <th>Nomor surat</th>
                                <th>Tahun surat</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
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

<div class="modal fade" id="createModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white">Membuat Surat Warga</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="/cover-letters" method="post">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="">Nama Warga</label>
                        <select class="form-control" name="family_member_id" style="width: 100% !important;" required>
                            <option value="" hidden>Pilih</option>
                            @foreach($family_members as $item)
                            <option value="{{ $item->id }}">{{ $item->family_member_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Surat Pengantar</label>
                        <select class="form-control" name="title" style="width: 100% !important;" required>
                            <option value="" hidden>Pilih</option>
                            <option value="SURAT PENGANTAR">SURAT PENGANTAR</option>
                            <option value="SURAT PENGANTAR (Ahli Waris)">SURAT PENGANTAR (Ahli
                                Waris)</option>
                            <option value="SURAT PENGANTAR (Akta Kematian)">SURAT PENGANTAR (Akta
                                Kematian)</option>
                            <option value="SURAT PENGANTAR (Bertempat Tinggal Sementara)">SURAT
                                PENGANTAR (Bertempat Tinggal Sementara)</option>
                            <option value="SURAT PENGANTAR (Domisili Bertempat Tinggal)">SURAT
                                PENGANTAR (Domisili Bertempat Tinggal)</option>
                            <option value="SURAT PENGANTAR (Domisili Penduduk Untuk WNA)">SURAT
                                PENGANTAR (Domisili Penduduk Untuk WNA)</option>
                            <option value="SURAT PENGANTAR (Domisili Tempat Ibadah)">SURAT PENGANTAR
                                (Domisili Tempat Ibadah)</option>
                            <option value="SURAT PENGANTAR (Domisili Usaha)">SURAT PENGANTAR
                                (Domisili Usaha)</option>
                            <option value="SURAT PENGANTAR (Domisili Yayasan)">SURAT PENGANTAR
                                (Domisili Yayasan)</option>
                            <option value="SURAT PENGANTAR (Kuasa Ahli Waris)">SURAT PENGANTAR
                                (Kuasa Ahli Waris)</option>
                            <option value="SURAT PENGANTAR (Permohonan Kredit Bank)">SURAT PENGANTAR
                                (Permohonan Kredit Bank)</option>
                            <option value="SURAT PENGANTAR (Perubahan Data orang yang sama)">SURAT
                                PENGANTAR (Perubahan Data orang yang sama)</option>
                            <option value="SURAT PENGANTAR (Surat Izin Keramaian Pertunjukan)">SURAT
                                PENGANTAR (Surat Izin Keramaian Pertunjukan)</option>
                            <option value="SURAT PENGANTAR (Surat Keterangan)">SURAT PENGANTAR
                                (Surat Keterangan)</option>
                            <option value="SURAT PENGANTAR (Surat Ket. Menikah)">SURAT PENGANTAR
                                (Surat Ket. Menikah)</option>
                            <option value="SURAT PENGANTAR (Surat Keterangan Tidak Mampu)">SURAT
                                PENGANTAR (Surat Keterangan Tidak Mampu)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Deskripsi</label>
                        <input type="text" name="description" placeholder="Masukkan Deskripsi" class="form-control"
                            required>
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
                <h4 class="modal-title white">Ubah surat pengantar</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" id="editForm" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    @method("PUT")
                    <div class="form-group">
                        <label for="">Status</label>
                        <select class="form-control" name="status" required>
                            <option value="" hidden>Pilih</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="diterima">Diterima</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Nomor surat</label>
                        <input type="text" placeholder="Masukkan nomor surat" id="editLetterNumber" class="form-control"
                            readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Judul</label>
                        <input type="text" placeholder="Masukkan judul" id="editTitle" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Deskripsi</label>
                        <textarea name="description" placeholder="Masukkan deskripsi" id="editDescription"
                            class="form-control"></textarea>
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
                url: "/cover-letters/" + id + "/edit"
            }).done(function (response)
            {
                console.log(response);
                $("#editLetterNumber").val(response.letter_number);
                $("#editTitle").val(response.title);
                $("#editDescription").text(response.description);
                $("#editForm").attr("action", "/cover-letters/" + id)
                $("#editModal").modal();
            })
        });
        

        $(document).on("click", ".deleteButton", function()
        {
            let id = $(this).val();

            $("#deleteForm").attr("action", "/cover-letters/" + id)
            $("#deleteModal").modal();
        });
</script>
@endsection
@extends('layouts.index')

@section('title', 'Manajemen Polling')

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

    .modal-md-lg {
        max-width: 660px !important;
    }

    .map-on-modal {
        height: 300px !important;
    }
</style>
@endsection

@section('content-header')
<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
    <h3 class="content-header-title mb-0 d-inline-block">Manajemen Polling</h3>
    <div class="row breadcrumbs-top d-inline-block">
        <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a>
                </li>
                <li class="breadcrumb-item active">Polling
                </li>
            </ol>
        </div>
    </div>
</div>
<div class="content-header-right col-md-6 col-12">
    <div class="btn-group float-md-right">
        <button class="btn btn-info rounded-0 mb-1" id="createPollingButton" type="button">Tambah</button>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">List Polling</h4>
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
                                <th>Judul Polling</th>
                                <th>Deskripsi</th>
                                <th>Hasil Vote</th>
                                <th>Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($pollings as $item)
                            <tr>
                                <td class="text-capitalize">{{ $no++ }}</td>
                                <td class="text-capitalize">{{ $item->title }}</td>
                                <td class="text-capitalize">{{ $item->description }}</td>
                                <td class="text-capitalize">
                                    @foreach ($item->polling_option as $option)
                                    @if ($item->status == 'finish')
                                    <p style="white-space: nowrap">{{ $option->option_name }} : {{ $option->vote }}</p>
                                    @else
                                    <p style="white-space: nowrap">{{ $option->option_name }} : Menunggu hasil</p>
                                    @endif
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    @if($item->status == 'pending')
                                    <h4><span class="badge badge-danger">Belum Dimulai</span></h4>
                                    @elseif($item->status == 'start')
                                    <h4><span class="badge badge-warning">Dimulai</span></h4>
                                    @elseif($item->status == 'finish')
                                    <h4><span class="badge badge-success">Selesai</span></h4>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-info dropdown-toggle" type="button"
                                            data-toggle="dropdown">
                                            <i class="la la-cog"></i>
                                        </button>
                                        <div class="dropdown-menu" style="min-width: 9rem !important">
                                            @if($item->status == 'start')
                                            <button class="dropdown-item finishPollingButton" value="{{ $item->id }}">
                                                <i class="las la-play-circle"></i> Akhiri Polling
                                            </button>
                                            @endif
                                            @if($item->status == 'finish')
                                            <button class="dropdown-item deletePollingButton" value="{{ $item->id }}">
                                                <i class="la la-trash"></i> Hapus
                                            </button>
                                            @endif
                                            @if($item->status == 'pending')
                                            <button class="dropdown-item startPollingButton" value="{{ $item->id }}">
                                                <i class="las la-play-circle"></i> Mulai Polling
                                            </button>
                                            <button class="dropdown-item editPollingButton" value="{{ $item->id }}">
                                                <i class="la la-edit"></i> Ubah
                                            </button>
                                            <button class="dropdown-item deletePollingButton" value="{{ $item->id }}">
                                                <i class="la la-trash"></i> Hapus
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfooter>
                            <tr>
                                <th>No</th>
                                <th>Judul Polling</th>
                                <th>Deskripsi</th>
                                <th>Hasil Vote</th>
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
                <h4 class="modal-title white">Tambah Polling</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="/pollings" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Judul</label>
                        <input type="text" name="title" placeholder="Masukkan judul" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Deskripsi</label>
                        <textarea name="description" placeholder="Masukkan deskripsi" class="form-control"
                            required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Nama Pilihan</label>
                        <div id="create_dynamic_field">
                            <div class="row mb-1">
                                <div class="col-12 col-sm-9"><input type="text" name="option_name[]"
                                        placeholder="Masukkan nama pilihan" class="form-control option_name_list"
                                        required />
                                </div>
                                <div class="col-6 col-sm-2">
                                    <button type="button" name="add" id="create_add"
                                        class="btn btn-success">Tambah</button>
                                </div>
                            </div>
                        </div>
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
                <h4 class="modal-title white">Ubah polling</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" id="editForm" method="post">
                <div class="modal-body">
                    @csrf
                    @method("PUT")
                    <div class="form-group">
                        <label for="">Judul</label>
                        <input type="text" name="title" placeholder="Masukkan judul" class="form-control" id="editTitle"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="">Deskripsi</label>
                        <textarea name="description" placeholder="Masukkan deskripsi" class="form-control"
                            id="editDescription" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Nama Pilihan</label>
                        <div id="edit_dynamic_field"></div>
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

<div class="modal fade" id="startPollingModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white">Apa anda yakin ingin memulai polling ini?</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" id="startPollingForm" method="post">
                <div class="modal-footer">
                    @csrf
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-outline-success">Iya, Mulai</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="finishPollingModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white">Apa anda yakin ingin mengakhiri polling ini?</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" id="finishPollingForm" method="post">
                <div class="modal-footer">
                    @csrf
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-outline-success">Iya, Akhiri</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deletePollingModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white">Apa anda yakin ingin menghapus data ini?</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" id="deletePollingForm" method="post">
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
    var i=1;
    $('#create_add').click(function()
    {
        i++;
        $('#create_dynamic_field').append('<div class="row mb-1 additional-answer" id="row'+i+'"><div class="col-12 col-sm-9"><input type="text" name="option_name[]" placeholder="Masukkan nama pilihan" class="form-control option_name_list" required/></div><div class="col-6 col-sm-2"><button type="button" name="remove" id="'+i+'" class="btn btn-danger block btn_remove">X</button></div></div>');
    });
    
    $(document).on('click', '.btn_remove', function()
    {
        var button_id = $(this).attr("id"); 
        $('#row'+button_id+'').remove();
    });
    $(document).on("click", "#createPollingButton", function ()
        {
            $(".additional-answer").remove();
            $("#createModal").modal();
        });

        $(document).on("click", ".editPollingButton", function()
        {
            let id = $(this).val();
            $(".additional-answer").remove();
            $.ajax(
            {
                method: "GET",
                url: "{{ route('pollings.index') }}/" + id + "/edit"
            }).done(function (response)
            {
                let i = 0;
                response.polling_option.forEach(polling_option => 
                {
                    if(i == 0)
                    {
                        $('#edit_dynamic_field').append('<div class="row mb-1 additional-answer" id="row'+i+'"><div class="col-12 col-sm-9"><input type="text" name="option_name[]" value="'+polling_option.option_name+'" placeholder="Masukkan nama pilihan" class="form-control option_name_list" required/></div><div class="col-6 col-sm-2"><button type="button" name="add" id="edit_add" class="btn btn-success">Tambah</button></div></div>');
                    }else {
                        $('#edit_dynamic_field').append('<div class="row mb-1 additional-answer" id="row'+i+'"><div class="col-12 col-sm-9"><input type="text" name="option_name[]" value="'+polling_option.option_name+'" placeholder="Masukkan nama pilihan" class="form-control option_name_list" required/></div><div class="col-6 col-sm-2"><button type="button" name="remove" id="'+i+'" class="btn btn-danger block btn_remove">X</button></div></div>');
                    }
                    i++;
                });
                $('#edit_add').click(function()
                {
                    i++;
                    $('#edit_dynamic_field').append('<div class="row mb-1 additional-answer" id="row'+i+'"><div class="col-12 col-sm-9"><input type="text" name="option_name[]" placeholder="Masukkan nama pilihan" class="form-control option_name_list" required/></div><div class="col-6 col-sm-2"><button type="button" name="remove" id="'+i+'" class="btn btn-danger block btn_remove">X</button></div></div>');
                });
                $("#editTitle").val(response.title);
                $("#editDescription").val(response.description);
                $("#editStatus option[value=\"" + response.status + "\"]").attr("selected", true);
                $("#editForm").attr("action", "{{ route('pollings.index') }}/" + id)
                $("#editModal").modal();
                
            })
        });
        $(document).on("click", ".deletePollingButton", function()
        {
            let id = $(this).val();

            $("#deletePollingForm").attr("action", "pollings/" + id)
            $("#deletePollingModal").modal();
        });
        
        $(document).on("click", ".startPollingButton", function()
        {
            let id = $(this).val();

            $("#startPollingForm").attr("action", "/pollings/" + id + "/start")
            $("#startPollingModal").modal();
        });
        
        $(document).on("click", ".finishPollingButton", function()
        {
            let id = $(this).val();

            $("#finishPollingForm").attr("action", "/pollings/" + id + "/finish")
            $("#finishPollingModal").modal();
        });

</script>
@endsection
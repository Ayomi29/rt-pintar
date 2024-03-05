@extends('layouts.index')

@section('title', 'Manajemen Iuran')

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
    <h3 class="content-header-title mb-0 d-inline-block">Manajemen Iuran</h3>
    <div class="row breadcrumbs-top d-inline-block">
        <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a>
                </li>
                <li class="breadcrumb-item active">Iuran
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
                <h4 class="card-title">List Iuran</h4>
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
                                <th>Iuran</th>
                                <th>Deskripsi Iuran</th>
                                <th>Nominal Iuran</th>
                                <th>Foto Iuran</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($donations as $item)
                            <tr>
                                <td class="text-capitalize">{{ $no++ }}</td>
                                <td class="text-capitalize">{{ $item->title }}</td>
                                <td class="text-capitalize">{{ $item->description }}</td>
                                <td class="text-capitalize">{{ $item->nominal }}</td>
                                <td>
                                    <a target="_blank" class="text-info" href="{{ $item->image }}">
                                        <img src="{{ $item->image }}" width="75px" height="75px">
                                    </a>
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
                                <th>Iuran</th>
                                <th>Deskripsi Iuran</th>
                                <th>Nominal Iuran</th>
                                <th>Foto Iuran</th>
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
                <h4 class="modal-title white">Tambah Iuran</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="/iurans" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="">Nama Iuran</label>
                        <input type="text" name="title" placeholder="Masukkan nama iuran" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Deskripsi Iuran</label>
                        <input type="text" name="description" placeholder="Masukkan deskripsi iuran"
                            class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Nominal Iuran</label>
                        <input type="text" name="nominal" placeholder="Masukkan nominal iuran (Rp.)"
                            class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Foto Iuran</label>
                        <img class="img-preview text-center" alt="gambar"
                            style="display: block; width: 200px; height:auto;" />
                    </div>
                    <div class="form-group">
                        <input type="file" name="image" class="form-control-file" id="createImage"
                            data-show-upload="false" data-show-caption="true" data-show-preview="true"
                            onChange='previewImage()' accept="image/*">
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
                <h4 class="modal-title white">Ubah Iuran</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" id="editForm" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="">Nama Iuran</label>
                        <input type="text" name="title" placeholder="Masukkan nama iuran" class="form-control"
                            id='editTitle' required>
                    </div>
                    <div class="form-group">
                        <label for="">Deskripsi Iuran</label>
                        <input type="text" name="description" placeholder="Masukkan deskripsi iuran"
                            class="form-control" id='editDesc' required>
                    </div>
                    <div class="form-group">
                        <label for="">Nominal Iuran</label>
                        <input type="text" name="nominal" placeholder="Masukkan nominal iuran" class="form-control"
                            id='editNominal' required>
                    </div>

                    <div class="form-group">
                        <label>Foto Iuran</label>
                        <img id="editImage" class="text-center" alt="gambar"
                            style="display: block; width: 300px; height:auto;" />
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="oldImg" name="oldImage">
                        <input type="file" id="editImageChange" name="image" class="form-control-file"
                            data-show-upload="false" data-show-caption="true" data-show-preview="true" accept="image/*">
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
    function previewImage() {
            const image = document.querySelector('#createImage')
            const imagePreview = document.querySelector('.img-preview')
    
            imagePreview.style.display = 'block';
    
            const imgreader = new FileReader();
            imgreader.readAsDataURL(image.files[0]);
    
            imgreader.onload = function(imgEvent) {
                imagePreview.src = imgEvent.target.result;
            }
        }
        
        function editImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
    
                reader.onload = function (e) {
                    $('#editImage').attr('src', e.target.result);
                }
    
                reader.readAsDataURL(input.files[0]);
            }
        }
    
        $("#editImageChange").change(function () {
            editImage(this);
        });
        $(document).on("click", "#createButton", function ()
        {
            $("#createModal").modal();
        });
        
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
                // url: "{{ route('iurans.index') }}/" + id + "/edit"
                url: "/iurans/" + id + "/edit"
            }).done(function (response)
            {
                console.log(response);
                $("#editTitle").val(response.title);
                $("#editDesc").val(response.description);
                $("#editNominal").val(response.nominal);
                $("#oldImg").val(response.image);
                $("#editImage").attr("src", response.image);
                $("#editForm").attr("action", "/iurans/" + id)
                // $("#editForm").attr("action", "{{ route('iurans.index') }}/" + id)

                $("#editModal").modal();
            })
        });
        
        
        $(document).on("click", ".deleteButton", function()
        {
            let id = $(this).val();

            $("#deleteForm").attr("action", "{{ route('iurans.index') }}/" + id)
            $("#deleteModal").modal();
        });
</script>
@endsection
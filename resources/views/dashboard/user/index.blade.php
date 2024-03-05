@extends('layouts.index')

@section('title', 'Manajemen Data User')

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
        <h3 class="content-header-title mb-0 d-inline-block">Manajemen Data User</h3>
        <div class="row breadcrumbs-top d-inline-block">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">Home</a>
                    </li>
                    <li class="breadcrumb-item active">User Warga
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12">
        <div class="btn-group float-md-right">
            <button class="btn btn-info rounded-0 mb-1" id="createUserButton" type="button">Tambah</button>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">List user warga</h4>
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
                                <th>Nama</th>
                                <th>Nomor telepon</th>
                                <th>Verifikasi nomor</th>
                                <th>Status akun</th>
                                <th>Status pengurus</th>
                                <th width="10%">Aksi</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($users as $item)
                                <tr>
                                    <td class="text-capitalize">{{ $no++ }}</td>
                                    <td class="text-capitalize">{{ $item->family_member->family_member_name }}</td>
                                    <td>{{ $item->phone_number }}</td>
                                    <td class="text-center">
                                    @if($item->family_member->verified == 1)
                                        <h4><span class="badge badge-success">terverifikasi</span></h4>
                                    @else
                                        <h4><span class="badge badge-danger">belum verifikasi</span></h4>
                                    @endif
                                    </td>
                                    <td class="text-center">
                                    @if($item->family_member->status == 'aktif')
                                        <h4><span class="badge badge-success">Aktif</span></h4>
                                    @else
                                        <h4><span class="badge badge-danger">Tidak aktif</span></h4>
                                    @endif
                                    <td class="text-center">
                                    @if($item->roles == true)
                                        <h4><span class="badge badge-success">Aktif</span></h4>
                                    @else
                                        <h4><span class="badge badge-danger">Tidak aktif</span></h4>
                                    @endif
                                    </td>
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
                                                <button class="dropdown-item deleteUserButton" value="{{ $item->id }}">
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
                                <th>Nama</th>
                                <th>Nomor telepon</th>
                                <th>Verifikasi nomor</th>
                                <th>Status akun</th>
                                <th>Status pengurus</th>
                                <th width="10%">Aksi</th>                                
               
                            </tr>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createUserModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white">Tambah warga</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="/users" method="post">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="">Nik</label>
                        <select class="form-control select2" name="nik" style="width: 100% !important;" required>
                            <option value="" hidden>Pilih</option>
                            @foreach($family_members as $item)
                                <option value="{{ $item->nik }}">{{ $item->nik }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Email Address</label>
                        <input type="text" minlength="5" name="email" placeholder="Masukkan email address" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Nomor telepon</label>
                        <input type="text" minlength="10" maxlength="13" name="phone_number" placeholder="Masukkan nomor telepon" class="form-control" required>
                    </div>
                    <fieldset class="form-group floating-label-form-group mb-1 input-password">
                        <label for="user-password"><i class="fa laa-lock"></i> Password</label>
                        <div class="input-group">
                          <input type="password" class="form-control" name="password" id="password" maxlength="210" minlength="1" placeholder="Enter Your Password" required>
                          <div class="input-group-append">
                            <span class="input-group-text">
                              <i class="la la-eye-slash" id="togglePassword"></i>
                            </span>
                          </div>
                        </div>
                      </fieldset>
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
                <h4 class="modal-title white">Ubah data user</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" id="editUserForm" method="post">
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="">Nomor telepon</label>
                        <input type="number" minlength="10" maxlength="13" name="phone_number" placeholder="Masukkan nomor telepon" class="form-control" id="editPhoneNumber" required>
                    </div>
                    <fieldset class="form-group floating-label-form-group mb-1 input-password">
                        <label for="user-password"><i class="fa laa-lock"></i> Password</label>
                        <div class="input-group">
                          <input type="password" class="form-control" name="password" id="editPassword" maxlength="210" minlength="1" placeholder="Enter Your Password" required>
                          <div class="input-group-append">
                            <span class="input-group-text">
                              <i class="la la-eye-slash" id="togglePassword"></i>
                            </span>
                          </div>
                        </div>
                      </fieldset>
                    <div class="form-group">
                        <label for="">Status akun</label>
                        <select name="status" class="form-control" id="editStatusAkun" required>
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
<div class="modal fade" id="deleteUserModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white">Apa anda yakin ingin menghapus data ini?</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" id="deleteUserForm" method="post">
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
        
        $(document).on("click", "#createUserButton", function ()
        {
            $("#createUserModal").modal();
        });

        $(document).on("click", ".editButton", function()
        {
            let id = $(this).val();
            $.ajax(
            {
                method: "GET",
                url: "{{ route('users.index') }}/" + id + "/edit"
            }).done(function (response)
            {
                
                $("#editPhoneNumber").val(response.phone_number);
                $("#editPassword").val(response.password);
                $("#editStatusAkun option[value=\"" + response.status + "\"]").attr("selected", true);

                $("#editUserForm").attr("action", "{{ route('users.index') }}/" + id)
                $("#editModal").modal();
            })
        });
        
        
        $(document).on("click", ".deleteUserButton", function()
        {
            let id = $(this).val();

            $("#deleteUserForm").attr("action", "{{ route('users.index') }}/" + id)
            $("#deleteUserModal").modal();
        });
    </script>
@endsection

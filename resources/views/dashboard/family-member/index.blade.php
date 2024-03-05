@extends('layouts.index')

@section('title', 'Manajemen Anggota KK')

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
    <h3 class="content-header-title mb-0 d-inline-block">Manajemen Data Anggota KK</h3>
    <div class="row breadcrumbs-top d-inline-block">
        <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a>
                </li>
                <li class="breadcrumb-item active">Anggota KK
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
                <h4 class="card-title">List Anggota KK</h4>
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
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered zero-configuration datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Status</th>
                                    <th>Nama</th>
                                    <th>Nik</th>
                                    <th>Jenis kelamin</th>
                                    <th>Tempat, tanggal lahir</th>
                                    <th>Status perkawinan</th>
                                    <th>Agama</th>
                                    <th>Kewarganegaraan</th>
                                    <th>Pekerjaan</th>
                                    <th>Pendidikan</th>
                                    <th>Alamat domisili</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach($family_members as $item)
                                <tr>
                                    <td class="text-capitalize">{{ $no++ }}</td>
                                    <td class="text-capitalize" style="white-space: nowrap;">{{ $item->family_status }}
                                    </td>
                                    <td class="text-capitalize" style="white-space: nowrap;">{{
                                        $item->family_member_name }}</td>
                                    <td style="white-space: nowrap;">{{ $item->nik }}</td>
                                    <td class="text-capitalize" style="white-space: nowrap;">{{ $item->gender }}</td>
                                    <td class="text-capitalize" style="white-space: nowrap;">{{ $item->birth_place }},
                                        {{ $item->birth_date }}</td>
                                    <td class="text-capitalize" style="white-space: nowrap;">{{ $item->marital_status }}
                                    </td>
                                    <td class="text-capitalize" style="white-space: nowrap;">{{ $item->religious }}</td>
                                    <td class="text-uppercase" style="white-space: nowrap;">{{ $item->citizenship }}
                                    </td>
                                    <td class="text-capitalize" style="white-space: nowrap;">{{ $item->job }}</td>
                                    <td class="text-capitalize" style="white-space: nowrap;">{{ $item->education }}</td>
                                    <td class="text-capitalize" style="white-space: nowrap;">{{ $item->address }}</td>
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
                                    <th>Status</th>
                                    <th>Nama</th>
                                    <th>Nik</th>
                                    <th>Jenis kelamin</th>
                                    <th>Tempat, tanggal lahir</th>
                                    <th>Status perkawinan</th>
                                    <th>Agama</th>
                                    <th>Kewarganegaraan</th>
                                    <th>Pekerjaan</th>
                                    <th>Pendidikan</th>
                                    <th>Alamat domisili</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </tfooter>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white">Tambah Anggota KK</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="/family-members" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="">No. KK</label>
                        <select class="form-control select2" name="family_card_id" data-searchable="true"
                            style="width: 100% !important;" required>
                            <option value="" hidden>Pilih</option>
                            @foreach($family_cards as $item)
                            <option value="{{ $item->id }}">{{ $item->family_card_number }}
                            </option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="">Status</label>
                        <select name="family_status" data-searchable="true" class="form-control" required>
                            <option value="" hidden>Pilih</option>
                            <option value="kepala keluarga">Kepala keluarga</option>
                            <option value="istri">Istri</option>
                            <option value="anak">Anak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" name="family_member_name" placeholder="Masukkan nama" class="form-control"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="">NIK</label>
                        <input type="number" name="nik" maxlength="16" placeholder="Masukkan nik" class="form-control"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="">Jenis kelamin</label>
                        <select name="gender" class="form-control" required>
                            <option value="" hidden>Pilih</option>
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Tempat lahir</label>
                        <input type="text" name="birth_place" placeholder="Masukkan tampat lahir" class="form-control"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="">Tanggal lahir</label>
                        <input type="date" name="birth_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Status perkawinan</label>
                        <select name="marital_status" class="form-control" required>
                            <option value="" hidden>Pilih</option>
                            <option value="belum kawin">Belum kawin</option>
                            <option value="kawin">Kawin</option>
                            <option value="duda\janda">Duda\Janda</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Agama</label>
                        <select name="religious" class="form-control" required>
                            <option value="" hidden>Pilih</option>
                            <option value="islam">Islam</option>
                            <option value="kristen">Kristen</option>
                            <option value="katholik">Katholik</option>
                            <option value="hindu">Hindu</option>
                            <option value="budha">Budha</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Kewarganegaraan</label>
                        <select name="citizenship" class="form-control" required>
                            <option value="" hidden>Pilih</option>
                            <option value="wni">WNI</option>
                            <option value="wna">WNA</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Pekerjaan</label>
                        <input type="text" name="job" placeholder="Masukkan pekerjaan" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Pendidikan</label>
                        <input type="text" name="education" placeholder="Masukkan pendidikan" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Alamat domisili</label>
                        <textarea name="address" maxlength="120" placeholder="Masukkan alamat domisili"
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

<div class="modal fade" id="editModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info white">
                <h4 class="modal-title white">Ubah Anggota KK</h4>
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
                        <select name="status" id="editStatus" class="form-control" required>
                            <option hidden>Pilih</option>
                            <option value="aktif">Aktif</option>
                            <option value="tidak aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Status Keluarga</label>
                        <select name="family_status" id="editFamilyStatus" class="form-control" required>
                            <option hidden>Pilih</option>
                            <option value="kepala keluarga">Kepala keluarga</option>
                            <option value="istri">Istri</option>
                            <option value="anak">Anak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" id="editFamilyMemberName" name="family_member_name"
                            placeholder="Masukkan nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">NIK</label>
                        <input type="number" id="editFamilyMemberNik" name="nik" placeholder="Masukkan nik"
                            class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Jenis kelamin</label>
                        <select name="gender" id="editFamilyMemberGender" class="form-control" required>
                            <option value="" hidden>Pilih</option>
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Tempat lahir</label>
                        <input type="text" id="editFamilyMemberBirthPlace" name="birth_place"
                            placeholder="Masukkan tampat lahir" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Tanggal lahir</label>
                        <input type="date" id="editFamilyMemberBirthDate" name="birth_date" class="form-control"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="">Status perkawinan</label>
                        <select name="marital_status" id="editMaritalStatus" class="form-control" required>
                            <option value="" hidden>Pilih</option>
                            <option value="belum kawin">Belum kawin</option>
                            <option value="kawin">Kawin</option>
                            <option value="duda\janda">Duda\Janda</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Agama</label>
                        <select name="religious" id="editFamilyMemberReligious" class="form-control" required>
                            <option value="" hidden>Pilih</option>
                            <option value="islam">Islam</option>
                            <option value="kristen">Kristen</option>
                            <option value="katholik">Katholik</option>
                            <option value="hindu">Hindu</option>
                            <option value="budha">Budha</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Kewarganegaraan</label>
                        <select name="citizenship" id="editFamilyMemberCitizenship" class="form-control" required>
                            <option value="" hidden>Pilih</option>
                            <option value="wni">WNI</option>
                            <option value="wna">WNA</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Pekerjaan</label>
                        <input type="string" id="editFamilyMemberJob" name="job" placeholder="Masukkan pekerjaan"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Pendidikan</label>
                        <input type="text" name="education" id="editFamilyMemberEducation"
                            placeholder="Masukkan pendidikan" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Alamat domisili</label>
                        <textarea name="address" maxlength="120" id="editAddress" placeholder="Masukkan alamat domisili"
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
                url: "{{ route('family-members.index') }}/" + id + "/edit"
            }).done(function (response)
            {
                
                $("#editFamilyCardNumber").val(response.family_card.family_card_number);
                $("#editFamilyMemberName").val(response.family_member_name);
                $("#editFamilyMemberNik").val(response.nik);
                $("#editFamilyMemberGender option[value=\"" + response.gender + "\"]").attr("selected", true);
                
                $("#editFamilyMemberBirthPlace").val(response.birth_place);
                $("#editFamilyMemberBirthDate").val(response.birth_date);
                $("#editFamilyMemberReligious option[value=\"" + response.religious + "\"]").attr("selected", true);
                $("#editFamilyMemberCitizenship option[value=\"" + response.citizenship + "\"]").attr("selected", true);
                $("#editFamilyMemberEducation").val(response.education);
                $("#editFamilyMemberJob").val(response.job);
                $("#editMaritalStatus option[value=\"" + response.marital_status + "\"]").attr("selected", true);
                $("#editFamilyStatus option[value=\"" + response.family_status + "\"]").attr("selected", true);
                $("#editStatus option[value=\"" + response.status + "\"]").attr("selected", true);
                $("#editAddress").text(response.address);
                $("#editForm").attr("action", "{{ route('family-members.index') }}/" + id)
                $("#editModal").modal();
            })
        });
        $(document).on("click", ".deleteButton", function()
        {
            let id = $(this).val();

            $("#deleteForm").attr("action", "{{ route('family-members.index') }}/" + id)
            $("#deleteModal").modal();
        });

</script>
@endsection
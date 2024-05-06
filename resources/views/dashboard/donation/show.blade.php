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
                <li class="breadcrumb-item active"><a href="/iurans">Manajemen Iuran</a>
                </li>
                <li class="breadcrumb-item active">Rekapitulasi Iuran
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
                <h4 class="card-title">List Bukti Pembayaran Iuran</h4>
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
                                <th>Nama Warga</th>
                                <th>KK</th>
                                <th>Nominal Iuran</th>
                                <th>Bukti Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($donations as $item)
                            <tr>
                                <td class="text-capitalize">{{ $no++ }}</td>
                                <td class="text-capitalize">
                                    @if ($item->family_member_id)
                                    {{ $item->family_member->family_member_name }}
                                    @endif
                                </td>
                                <td class="text-capitalize">
                                    @if ($item->family_member_id)
                                    {{ $item->family_member->family_card->family_card_number }}
                                    @endif
                                </td>
                                <td class="text-capitalize">{{ $item->nominal }}</td>
                                <td>
                                    <a target="_blank" class="text-info" href="{{ $item->file }}">
                                        <img src="{{ $item->file }}" width="75px" height="75px">
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="text-capitalize">Total Dana</td>
                                <td class="text-capitalize">Rp. {{ $sum_donation }}</td>
                            </tr>
                        </tbody>
                        <tfooter>
                            <tr>
                                <th>No</th>
                                <th>Nama Warga</th>
                                <th>KK</th>
                                <th>Nominal Iuran</th>
                                <th>Bukti Pembayaran</th>
                            </tr>
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
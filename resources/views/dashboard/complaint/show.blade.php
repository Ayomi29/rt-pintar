@extends('layouts.index')

@section('title', 'Dokumen Aduan Warga')

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
    <h3 class="content-header-title mb-0 d-inline-block">Dokumen Aduan Warga</h3>
    <div class="row breadcrumbs-top d-inline-block">
        <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a>
                </li>
                <li class="breadcrumb-item"><a href="/complaints">Aduan Warga</a>
                </li>
                <li class="breadcrumb-item">Dokumen Aduan Warga
                </li>
            </ol>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-9">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">List dokumen aduan warga</h4>
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
                    @if ($complaint_image != null)
                    @foreach($complaint_image as $img)
                    <div class="form-group">
                        <a href="{{ $img->document }}" target="_blank">
                            <img src="{{ $img->document }}" class="img-fluid img-thumbnail rounded" width="250px">
                        </a>
                    </div>
                    @endforeach
                    @endif
                    @if ($complaint_video != null)
                    @foreach($complaint_video as $item)
                    <div class="form-group">
                        <video width="640" height="480" controls>
                            <source src="{{ $item->document }}" type="video/mp4">
                        </video>
                    </div>
                    @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection
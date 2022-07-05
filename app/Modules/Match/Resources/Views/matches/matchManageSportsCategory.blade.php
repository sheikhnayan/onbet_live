@extends('backend.backendMaster')
@section('title', 'Matches List')
@section('page_title', 'Matches List')
@section('page_content')
    <!-- Page -->
    <div class="page">

        <div class="page-header">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Home</a></li>
                <li class="breadcrumb-item active">@yield('page_title')</li>
            </ol>
        </div>

        <div class="page-content container-fluid">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <a href="{{ route("matches_manage_sports_category_id",["id"=>1]) }}" class="btn btn-success btn-block p-30 font-size-30">Cricket</a>
                </div>
                <div class="col-md-4 mb-4">
                    <a href="{{ route("matches_manage_sports_category_id",["id"=>2]) }}" class="btn btn-primary btn-block p-30 font-size-30">Football</a>
                </div>
                <div class="col-md-4 mb-4">
                    <a href="{{ route("matches_manage_sports_category_id",["id"=>3]) }}" class="btn btn-danger btn-block p-30 font-size-30">Basketball</a>
                </div>
                <div class="col-md-4 mb-2">
                    <a href="{{ route("matches_manage_sports_category_id",["id"=>4]) }}" class="btn btn-warning btn-block p-30 font-size-30">Volley</a>
                </div>
                <div class="col-md-4 mb-2">
                    <a href="{{ route("matches_manage_sports_category_id",["id"=>5]) }}" class="btn btn-dark btn-block p-30 font-size-30">Tennis</a>
                </div>
            </div>
        </div>

    </div>
    <!-- End Page -->

@endsection

@section('page_styles')
    @include('backend.partials._styles')
@endsection


@section('page_scripts')
    @include('backend.partials._scripts')
    @include('backend.partials._datatable_script')
    <script type="text/javascript">
        $('#matchManage').addClass('active open');
        $('#matchManageChildLi').addClass('active');
    </script>
@endsection

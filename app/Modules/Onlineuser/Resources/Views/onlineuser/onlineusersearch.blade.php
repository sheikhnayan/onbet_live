@extends('backend.backendMaster')
@section('title', 'Online User search')
@section('page_title', 'Online User search')
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
                <div class="col-md-12">
                    <div class="panel panel-bordered panel-info border border-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">@yield('page_title')</h3>
                        </div>
                        <div class="p-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="p-5">
                                    <form method="get" action="{{ route("search_online_indivisual_user") }}">
                                            @csrf
                                            <div class="row">

                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <div class="input-group input-group-icon">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    Username
                                                                    <span class="required text-danger"> *</span><!---->
                                                                </div>
                                                            </div>
                                                            <input autofocus class="form-control" type="text" name="searchuseranme" placeholder="Type perfect username" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xl-2">
                                                    <div class="form-group">
                                                        <div class="input-group input-group-icon">
                                                            <input class="form-control btn btn-block btn-success" type="submit" name="search" value="Search user"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    <script type="text/javascript">
        $('#OnlineUserManage').addClass('active open');
        $('#search').addClass('active');
    </script>
@endsection

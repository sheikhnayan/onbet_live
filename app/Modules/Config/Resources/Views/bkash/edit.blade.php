@extends('backend.backendMaster')
@section('title', 'Bkash Edit')
@section('page_title', 'Bkash Edit')
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
                                        <form action="{{ route('bkash_update',["id"=>$bkash->id]) }}" method="POST" id="exampleFullForm" autocomplete="off">
                                            @csrf

                                            <div class="row row-lg">
                                                <div class="col-xl-6 form-horizontal">
                                                    <div class="form-group">
                                                        <div class="input-group input-group-icon">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    Bkash Number
                                                                    <span class="required text-danger"> *</span>
                                                                </div>
                                                            </div>
                                                            <input type="text" name="bkashNumber" class="form-control" required placeholder="Bkash number" value="{{ $bkash->bkashNumber }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row row-lg">
                                                <div class="col-xl-6 form-horizontal">
                                                    <div class="form-group">
                                                        <div class="input-group input-group-icon">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    Payment Type
                                                                    <span class="required text-danger"> *</span>
                                                                </div>
                                                            </div>
                                                            <select required="" id="paymentMethodType" name="paymentMethodType" class="form-control">
                                                                <option value=""> Select method </option>
                                                                <option @if($bkash->paymentMethodType == 1) selected="selected" @endif value="1">Bkash</option>
                                                                <option @if($bkash->paymentMethodType == 2) selected="selected" @endif value="2">Nagad</option>
                                                                <option @if($bkash->paymentMethodType == 3) selected="selected" @endif value="3">Rocket</option>
                                                                <option @if($bkash->paymentMethodType == 4) selected="selected" @endif value="4">Paypal</option>
                                                                <option @if($bkash->paymentMethodType == 5) selected="selected" @endif value="5">Stripe</option>
                                                                <option @if($bkash->paymentMethodType == 6) selected="selected" @endif value="6">Neteller</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row row-lg">
                                                <div class="col-xl-6 form-horizontal">
                                                    <div class="form-group">
                                                        <div class="input-group input-group-icon">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    Status
                                                                    <span class="required text-danger"> *</span>
                                                                </div>
                                                            </div>
                                                            <select class="form-control" name="status" required>
                                                                <option value="">Select Status</option>
                                                                <option value="1" @if($bkash->status == 1) selected="selected" @endif>Published</option>
                                                                <option value="0" @if($bkash->status == 0) selected="selected" @endif>Unpublished</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row row-lg">
                                                <div class="form-group col-xl-2 padding-top-m">
                                                    <button type="submit" class="btn btn-info" id="validateButton1">Update Number</button>
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
    @include('backend.partials._formvalidation_style')
@endsection


@section('page_scripts')
    @include('backend.partials._scripts')
    @include('backend.partials._formvalidation_script')
    <script src="{{ asset('/validation/bkashValidation.js') }}"></script>
    <script type="text/javascript">
        $('#configManage').addClass('active open');
        $('#bkashManageChildLi').addClass('active');
    </script>
@endsection

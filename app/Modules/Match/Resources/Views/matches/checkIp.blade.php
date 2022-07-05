@extends('backend.backendMaster')
@section('title', 'Match IP check List')
@section('page_title', 'Match IP check List')
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
                    <div class="panel panel-bordered panel-success border border-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">IP Details</h3>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="p-5">
                                    {{ $userLocationInfo }}
                                </div>
                            </div>
                        </div>
                        
                        @if(Auth::guard("admin")->user()->type == 0)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="p-5">
                                        <table class="table table-hover table-striped w-full table-responsive-sm table-responsive-md table-responsive-lg">
                                            <thead>
                                                <tr>
                                                    <th>Action</th>
                                                    <th>Category</th>
                                                    <th>Title</th>
                                                    <th>Status</th>
                                                    <th>DateTime</th>
                                                    <th>Advance Count</th>
                                                    <th>Hide Count</th>
                                                    <th>First hide</th>
                                                    <th>Second hide</th>
                                                    <th>Third hide</th>
                                                    <th>Last hide</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                @foreach ($matchManageSportsByCategories as $match)
                                                <tr>
                                                    <td>
                                                        <a type="button" data-toggle="tooltip" data-placement="top"
                                                            data-trigger="hover" data-original-title="Match Details"
                                                            class="btn btn-xs btn-icon btn-primary btn-outline"
                                                            href="{{ route('matches_detail',['id'=>$match->id])}}">
                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                        </a>
                                                    </td>

                                                    <td>@if($match->sport_id){{ ucfirst($match->sportName) }}@endif</td>
                                                    <td>@if($match->matchTitle){{ ucfirst($match->matchTitle) }}@else Title not given @endif</td>

                                                    <td>
                                                        @if($match->status == 0)
                                                            <span class="badge badge-dark">Draft</span>
                                                        @elseif($match->status == 1)
                                                            <span class="badge badge-primary">OnBet</span>
                                                        @elseif($match->status == 2)
                                                            <span class="badge badge-success">Go Dashboard</span>
                                                        @elseif($match->status == 3)
                                                            <span class="badge badge-success">Live</span>
                                                        @elseif($match->status == 7)
                                                            <span class="badge badge-success">Hide form user</span>
                                                        @endif
                                                    </td>
                                                    
                                                    <td>
                                                        @if($match->matchDateTime)
                                                            {{ date("d M y h:i A",strtotime($match->matchDateTime)) }}
                                                        @endif
                                                    </td>
                                                    
                                                    <td> {{ $match->advanceCount }} </td>
                                                    <td> {{ $match->repeatAgainLive }} </td>
                                                    <td> {{ $match->repeatDateTimeOne }} </td>
                                                    <td> {{ $match->repeatDateTimeTwo }} </td>
                                                    <td> {{ $match->repeatDateTimeThree }} </td>
                                                    <td> {{ $match->repeatDateTimelast }} </td>                                            
                                                    
                                                    <td>
                                                        @if($match->created_at)
                                                        <?php
                                                            $db_time = DateTime::createFromFormat('Y-m-d H:i:s', $match->created_at, new DateTimeZone("UTC"));
                                                            echo $db_time->setTimeZone(new DateTimeZone("Asia/Dhaka"))->format('d M Y h:i:s A');
                                                        ?>
                                                        @endif
                                                    </td>

                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

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
    @include('backend.partials._datatable_script')
    <script type="text/javascript">
        $('#matchManage').addClass('active open');
        $('#matchIp').addClass('active');
    </script>
@endsection

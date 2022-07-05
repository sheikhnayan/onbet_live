@extends("frontend.frontendMaster")

@section("mainMenu")
    @include("frontend.partials.header")
@endsection

@section("content")
    <!-- breadcrumb Start -->
    <section class="breadcrumbs-custom-wrap">
        <h1 class="text-center">
            <ul class="breadcroumbLink">
                <li><a href="{{ url("/") }}">Home</a></li>
                <li><a>/ coin receive history</a></li>
            </ul>
        </h1>
    </section>
    <section class="login-section" style="padding:20px 0px">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="login-block text-center">
                        <div style="padding:0px 20px;">
                            <h3 class="title">Coin Receive History</h3>

                            <div class="customProfile">
                                <table id="example" class="table table-sm table-striped table-bordered table-responsive-lg table-responsive-md table-responsive-sm" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th >Receive User</th>
                                        <th >Transfer User</th>
                                        <th >Coin</th>
                                        <th >Date</th>
                                        <th >Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($coinReceiveHistories->count() > 0)
                                        @foreach($coinReceiveHistories as $coinReceiveHistory)
                                            <tr>
                                                <td>{{ $coinReceiveHistory->toUser->username }}</td>
                                                <td>{{ $coinReceiveHistory->fromUser->username }}</td>
                                                <td>{{ $coinReceiveHistory->transferAmount }}</td>
                                                <td>
                                                    @if($coinReceiveHistory->created_at)
                                                        <?php
                                                        $db_time = DateTime::createFromFormat('Y-m-d H:i:s', $coinReceiveHistory->created_at, new DateTimeZone("UTC"));
                                                        echo $db_time->setTimeZone(new DateTimeZone("Asia/Dhaka"))->format('d M Y h:i:s A');
                                                        ?>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($coinReceiveHistory->status == 1)
                                                        <span class="badge badge-success">Received</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5"><h3 class="text-warning">No coin receive yet</h3></td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            {!! $coinReceiveHistories->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section("main-footer")
    @include("frontend.partials.footer")
@endsection

@section("main-script")
    @include("frontend.partials.scriptFiles")
@endsection

@section("scriptExtra")

    <script type="text/javascript">
        $('#myWallet').addClass('active');
    </script>

@endsection

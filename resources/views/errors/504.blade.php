<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gateway Timeout</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .page_504 {
            padding: 40px 0;
            background: #fff;
        }

        .page_504 img {
            width: 100%;
        }

        /* .four_zero_four_bg {

            background-image: url('{{ asset('site/assets/img/error/funny-404-error-page-design.gif') }}');
            height: 400px;
            background-position: center;
        } */

        .four_zero_four_bg h1 {
            font-size: 80px;
        }

        .four_zero_four_bg h3 {
            font-size: 80px;
        }

        .link_504 {
            color: #fff !important;
            padding: 10px 20px;
            background: #65b530;
            margin: 20px 0;
            display: inline-block;
        }

        .contant_box_504 {
            margin-top: 50px;
        }
        .h1{
            margin-bottom: 0px !important;
        }
    </style>
</head>

<body>

<div>
    <section class="page_504">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 ">
                    <div class="col-sm-10 col-sm-offset-1  text-center">
                        <div class="four_zero_four_bg">
                            <h1 class="text-center" style="margin-bottom: 0px !important;">504</h1>
                            {{-- <img src="{{ asset('site/assets/images/504-gateway.png') }}" class="logo-light " alt="logo" style="width: 300px;"> --}}
                        </div>

                        <div class="contant_box_504 mt-3">
                            <h3 class="h2">
                                504 Gateway Timeout
                            </h3>
                            <p>The server took too long to respond. Please try again later!</p>

                            @if(AuthRole() == 'Admin')

                              <a href="{{ route('admin.dashboard.index') }}" class="link_504" >Go to Dashboard</a>

                            @elseif(AuthRole() =='Reseller')
                              <a href="{{ route('reseller.dashboard.index') }}" class="link_504" >Go to Dashboard</a>

                            @elseif(AuthRole() =='Client')
                              <a href="{{ route('client.dashboard.index') }}" class="link_504" >Go to Dashboard</a>

                            @elseif(AuthRole() =='Staff')
                              <a href="{{ route('staff.dashboard.index') }}" class="link_504" >Go to Dashboard</a>

                            @elseif(AuthRole() =='User')
                              <a href="{{ route('user.dashboard.index') }}" class="link_504" >Go to Dashboard</a>

                            @elseif(AuthRole() =='ResellerStaff')
                              <a href="{{ route('reseller-staff.dashboard.index') }}" class="link_504" >Go to Dashboard</a>

                            @elseif(AuthRole() =='ResellerAdmin')
                              <a href="{{ route('reseller-admin.dashboard.index') }}" class="link_504" >Go to Dashboard</a>

                            @elseif(AuthRole() =='Auditor')
                              <a href="{{ route('auditor.dashboard.index') }}" class="link_504" >Go to Dashboard</a>
                            @else
                                <a href="{{ url('/') }}" class="link_504">Go to Home</a>

                          @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

</body>

</html>


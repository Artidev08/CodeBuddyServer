
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Not Found</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .page_404 {
            padding: 40px 0;
            background: #fff;
        }

        .page_404 img {
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

        .link_404 {
            color: #fff !important;
            padding: 10px 20px;
            background: #65b530;
            margin: 20px 0;
            display: inline-block;
        }

        .contant_box_404 {
            margin-top: -50px;
        }
        .h1{
            margin-bottom: 0px !important;
        }
    </style>
</head>
<body>
<div>
    <section class="page_404">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 ">
                    <div class="col-sm-10 col-sm-offset-1  text-center">
                        <div class="four_zero_four_bg">
                            <img src="{{ asset('site/assets/images/403.png') }}" class="logo-light" alt="logo" style="width: 150px;">
                            <h1 class="text-center mb-0" style="margin-bottom: 40px !important;">403</h1>
                        </div>

                        <div class="contant_box_404 mt-1">
                            <h2 class="mt-2">
                                Look like you're lost
                            </h2>

                            <p>USER DOES NOT HAVE ANY OF THE NECESSARY ACCESS RIGHTS!</p>

                            @if(AuthRole() == 'Admin')
                              <a href="{{ route('panel.admin.dashboard.index') }}" class="link_404" >Go to Dashboard</a>

                            @elseif(AuthRole() =='Member')
                              <a href="{{ route('panel.member.dashboard.index') }}" class="link_404" >Go to Dashboard</a>

                           
                            @else
                                <a href="{{ url('/') }}" class="link_404">Go to Home</a>

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

@php
    $role = 'user';
    $root_directory = "panel/$role/";
    $root_directory_path = "panel.$role";
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    @yield('meta_data')
    @include($root_directory_path . '.include.head')
    @stack('style')
</head>

<body>
    <div>
        <!-- initiate header-->
        @include($root_directory_path . '.include.header')
        <div class="main-content pl-0 ">
            <section class="bg-white">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 mb-5 py-2">
                            <div class="row">
                                @include('panel.user.include.sidebar')
                                <div class="col-lg-10">
                                    <div class="">
                                        @yield('content')
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end container-->
            </section>
            <!--end section-->


            @include('panel.admin.users.includes.modal.delegate-access')
        </div>
        <!-- Back to top -->
        <a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top fs-5"><i data-feather="arrow-up"
                class="fea icon-sm icons align-middle"></i></a>

    </div>

    <!-- initiate script-->
    @include($root_directory_path . '.include.script')
    @stack('script')
</body>

</html>

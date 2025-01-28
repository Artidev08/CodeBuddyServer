@extends('layouts.main')
@section('title', 'Add '.$label)
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('admin/plugins/select2/dist/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">

    @endpush
    <div class="container-fluid head-margin">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card no-card">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                                <div class="card-body">
                                    <div class="customer_card card-personalization_info">
                                        <div class="pb-4">
                                            <h5>{{$label}}</h5>
                                            <div class="row mt-2 pt-20">
                                                    @foreach($themes as $theme)
                                                        <div class="col-md-4">
                                                            <div class="card mb-2 border p-2" style="background: transparent">
                                                                <a href="javascript:void(0)"class="apply-btn" data-theme="{{$theme['id']}}">
                                                                    <img style="object-fit: contain" src="{{ asset($theme['image']) }}" class="card-img-top" alt="{{ $theme['title'] }}">
                                                                </a>

                                                                <h6 class="text-center mt-2 mb-0">
                                                                    {{ $theme['title'] }}
                                                                    @if(isset(auth()->user()->preferences['theme_id']) && auth()->user()->preferences['theme_id'] == $theme['id'])
                                                                        <i class="fa fa-check"></i>
                                                                    @endif
                                                                </h6>
                                                                <div class="text-center">
                                                                    <i class="text-center text-muted">
                                                                      @lang('admin/ui.click_to_apply')
                                                                    </i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
    @push('script')
        <script>
              $('.addressType').on('click', function(){
              $('.addressType').removeClass('checked');
             $('.addressType').addClass('bg-white');
             $(this).addClass('checked');
             $(this).removeClass('bg-white');
            });



            $('.apply-btn').click(function(){
            let themeId = $(this).data('theme');

            let response = getData('GET', '{{ route("panel.admin.personalization.store") }}', "json",
            {theme_id: themeId}, callback = null, event = null, toast = 0);
            let redirectUrl = "{{url('admin/personalization/index')}}";
            setTimeout(() => {
                window.location.href = redirectUrl;
            }, 100);
        });

            $(document).ready(function(){
                $('#state, #country, #city').css('width','100%').select2();

                function getStates(countryId =  101) {
                    $.ajax({
                    url: '{{ route("world.get-states") }}',
                    method: 'GET',
                    data: {
                        country_id: countryId
                    },
                    success: function(res){
                        $('#state').html(res).css('width','100%').select2();
                    }
                    })
                }
                getStates(101);

                function getCities(stateId =  101) {
                    $.ajax({
                    url: '{{ route("world.get-cities") }}',
                    method: 'GET',
                    data: {
                        state_id: stateId
                    },
                    success: function(res){
                        $('#city').html(res).css('width','100%').select2();
                    }
                    })
                }
                $('#country').on('change', function(e){
                    getStates($(this).val());
                })

                $('#state').on('change', function(e){
                    getCities($(this).val());
                });

            });
        </script>
    @endpush

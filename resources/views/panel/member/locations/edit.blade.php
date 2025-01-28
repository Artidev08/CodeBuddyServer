@extends('layouts.main')
@section('title', $country->getPrefix().' Loacation Edit')
@section('content')
@php
    $breadcrumb_arr = [['name' => $label, 'url' => route('panel.admin.locations.country'), 'class' => ''], ['name' => $country->getPrefix(), 'url' => route('panel.admin.locations.country'), 'class' => ''], ['name' => 'Edit', 'url' => route('panel.admin.locations.country'), 'class' => 'active']];
@endphp


<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5> @lang('admin/ui.edit') {{ @$label ?? '--' }}</h5>
                        <span> @lang('admin/ui.update_a_record_for')  {{ @$label ?? '--' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
            </div>
        </div>
    </div>
    <div class="row">
    
        <div class="col-md-8 mx-auto">
            <div class="card ">
                <div class="card-header">
                    <h3> @lang('admin/ui.update')  {{ @$label ?? '--' }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('panel.admin.locations.country.update', $country->id) }}" method="post"
                        enctype="multipart/form-data" class="ajaxForm">
                        @csrf
                        
                        <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
                        regex="" validation="" value="country-update" />
                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group {{ @$errors->has('Name') ? 'has-error' : '' }}">
                                            <x-label name="country_name" validation="country_name" tooltip="edit_country_name" />
                                            <x-input name="name" placeholder="Enter Name" type="text"
                                                tooltip="edit_country_name" regex="name" validation="country_name"
                                                value="{{  @$country->name  }}"  />

                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group {{ @$errors->has('Name') ? 'has-error' : '' }}">
                                            <x-label name="capital" validation="country_name" tooltip="add_country_capital" />
                                            <x-input name="capital" placeholder="Enter Capital" type="text"
                                                tooltip="add_country_capital" regex="name" validation="country_name"
                                                value="{{ @$country->capital  }}"  />

                                        </div>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <div class="form-group {{ @$errors->has('Code') ? 'has-error' : '' }}">
                                            <x-label name="country_code" validation="common_code_2" tooltip="add_country_code" />
                                            <x-input name="iso3" placeholder="Enter Code" type="text"
                                                tooltip="add_country_code" regex="promo_code" validation="common_code_2"
                                                value="{{ @$country->iso3 }}"  />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group {{ @$errors->has('Currency') ? 'has-error' : '' }}">
                                            <x-label name="country_currency" validation="country_currency" tooltip="add_country_code" />
                                            <x-input name="currency" placeholder="Enter Currency" type="text"
                                                tooltip="add_country_code" regex="currency_name" validation="country_currency"
                                                value="{{ @$country->currency }}"  />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group {{ @$errors->has('region') ? 'has-error' : '' }}">
                                            <x-label name="region" validation="country_currency" tooltip="add_country_region" />
                                            <x-input name="region" placeholder="Enter Region" type="text"
                                                tooltip="add_country_region" regex="name" validation="country_currency"
                                                value="{{ @$country->region }}"  />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group {{ @$errors->has('Emoji') ? 'has-error' : '' }}">
                                            <x-label name="emoji_code" validation="common_code_2" tooltip="add_country_emoji" />
                                            <x-input name="emoji" placeholder="Enter Emoji Code" type="text"
                                                tooltip="add_country_emoji" regex="promo_code" validation="common_code_2"
                                                value="{{ @$country->emoji }}"  />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group {{ @$errors->has('phonecode') ? 'has-error' : '' }}">
                                            <x-label name="phone_code" validation="common_code_2" tooltip="add_country_phonecode" />
                                            <x-input name="phonecode" placeholder="Ente Phone Code" type="text"
                                                tooltip="add_country_phonecode" regex="promo_code" validation="common_code_2"
                                                value="{{  @$country->phonecode  }}"  />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary float-right ajax-btn">
                                        @lang('admin/ui.save_update') </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    {{-- START AJAX FORM INIT --}}
        <script>
            $('.ajaxForm').on('submit', function(e) {
                e.preventDefault();
                var route = $(this).attr('action');
                var method = $(this).attr('method');
                var data = new FormData(this);
                var redirectUrl = "{{ url('admin/locations/country') }}";
                var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
            })
        </script>
    {{-- END AJAX FORM INIT --}}
@endpush

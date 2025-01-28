@extends('layouts.app')

@section('meta_data')
    @php
        $meta_title = @$metas->title ?? 'Contact';
        $meta_description = @$metas->description ?? '';
        $meta_keywords = @$metas->keyword ?? '';
        $meta_motto = @$app_settings['site_motto'] ?? '';
        $meta_abstract = @$app_settings['site_motto'] ?? '';
        $meta_author_name = @$app_settings['app_name'] ?? 'Defenzelite';
        $meta_author_email = @$app_settings['frontend_footer_email'] ?? 'dev@defenzelite.com';
        $meta_reply_to = @$app_settings['frontend_footer_email'] ?? 'dev@defenzelite.com';
        $meta_img = ' ';
    @endphp
@endsection
<style>
  .iti--inline-dropdown .iti__dropdown-content {
                z-index: 9 !important;
            }
</style>
@section('content')
    <!-- Start Contact -->
    <section class="wrapper bg-light">
        <div class="container py-10">
            <div class="text-center mb-5 mt-5">
                <h1 class="display-4">Get in Touch</h1>
                <p class="lead">We'd love to hear from you! Please fill out the form below.</p>
            </div>

            <div class="row mb-5">
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3696.395349373495!2d79.54183787502795!3d22.110897149734495!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a2aaf5e5b95d1cf%3A0xbf018e215ed4607!2sDefenzelite%20Private%20Limited%3A%20Custom%20Software%2C%20Web%20%26%20App%20Development%2C%20Digital%20Marketing%2C%20Cybersecurity%20%26%20Sourcing!5e0!3m2!1sen!2sin!4v1721117503804!5m2!1sen!2sin"
                            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="p-4">
                        <h5>Contact Details</h5>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="uil uil-location-pin-alt text-primary"></i>
                                <span>{{ @$app_settings['app_address'] ?? '' }}</span>
                            </li>
                            <li class="mb-3">
                                <i class="uil uil-phone-volume text-primary"></i>
                                <span><a href="tel:{{ $app_settings['app_contact'] }}">{{ @$app_settings['app_contact'] ?? '' }}</a></span>
                            </li>
                            <li class="mb-3">
                                <i class="uil uil-envelope text-primary"></i>
                                <span><a href="mailto:{{ @$app_settings['app_email'] }}">{{ @$app_settings['app_email'] ?? '' }}</a></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <h2 class="display-5 text-center mb-4">Reach out by filling the form.</h2>
            <form method="post" action="{{ route('contact.store') }}" class="bg-white p-4 shadow-sm rounded">
                @csrf
                <input required type="hidden" value="email" name="type">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <input required name="name" value="{{ old('name') }}" id="name" type="text" class="form-control" placeholder="Name *" maxlength="30">
                    </div>
                    <div class="col-md-6 mb-3">
                        <input required type="email" class="form-control" name="value_type" value="{{ old('value_type') }}" placeholder="Email *">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Phone *" style="width: 525px !important">
                    </div>
                    <div class="col-md-6 mb-3">
                        <input required name="subject" value="{{ old('subject') }}" id="subject" class="form-control" placeholder="Subject *">
                    </div>
                </div>
                <div class="mb-4">
                    <textarea id="comments" name="description" class="form-control" placeholder="Your message *" style="height: 150px" required>{{ old('description') }}</textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill">Send Message</button>
                </div>
            </form>
        </div>
    </section>
    <!-- /section -->

    <section class="wrapper bg-light text-dark text-center py-5">
        <div class="container">
            <h3 class="display-4 mb-4">Embrace the Beauty of Words</h3>
            <p class="lead mb-5">Together, let's celebrate the power of expression and creativity!</p>
            <div class="row justify-content-center">
                <div class="col-md-4 mb-4">
                    <div class="rounded border shadow-sm p-4 bg-white">
                        <h3 class="counter text-success display-3">100+</h3>
                        <p class="h5">Verses Created</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="rounded border shadow-sm p-4 bg-white">
                        <h3 class="counter text-success display-3">70+</h3>
                        <p class="h5">Inspiring Readers</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="rounded border shadow-sm p-4 bg-white">
                        <h3 class="counter text-success display-3">30+</h3>
                        <p class="h5">Creative Contributors</p>
                    </div>
                </div>
            </div>
            <div class="my-4 mb-8">
                <a href="#" class="btn btn-primary btn-lg">Join Us Today</a>
            </div>
        </div>
    </section>
    
    
    <!-- End contact -->
@endsection


 {{-- COUNTRYCODE SELECTOR INIT --}}
 <script>
    document.addEventListener("DOMContentLoaded", function() {
       const input = document.querySelector("#phone");
       const countryCodeInput = document.querySelector("#countryCodeInput");

       const iti = window.intlTelInput(input, {
        initialCountry: "auto",
        separateDialCode: true,
        geoIpLookup: callback => {
            fetch("https://ipapi.co/json")
            .then(res => res.json())
            .then(data => callback(data.country_code))
            .catch(() => callback("us"));
        },
            utilsScript: "{{ asset('site/assets/js/country-code/utils.js') }}",
        });
       window.iti = iti;

       const updateCountryCode = () => {
           const selectedCountryData = iti.getSelectedCountryData();
           countryCodeInput.value = selectedCountryData.dialCode;
       };

       input.addEventListener("countrychange", updateCountryCode);
       input.addEventListener("keyup", updateCountryCode);
       input.addEventListener("change", updateCountryCode);

       setTimeout(() => {
           const event = new Event('countrychange');
           input.dispatchEvent(event);
       }, 300);
   });
</script>
{{--END COUNTRYCODE SELECTOR INIT --}}

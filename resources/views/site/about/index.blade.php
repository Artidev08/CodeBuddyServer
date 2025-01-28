@extends('layouts.app')

@section('meta_data')
    @php
        $meta_title = @$metas->title ?? 'About';
        $meta_description = @$metas->description ?? '';
        $meta_keywords = @$metas->keyword ?? '';
        $meta_motto = @$app_settings['site_motto'] ?? '';
        $meta_abstract = @$app_settings['site_motto'] ?? '';
        $meta_author_name = @$app_settings['app_name'] ?? 'Defenzelite';
        $meta_author_email = @$app_settings['frontend_footer_email'] ?? 'dev@defenzelite.com';
        $meta_reply_to = @$app_settings['frontend_footer_email'] ?? 'dev@defenzelite.com';
        $meta_img = ' ';

        $cta_visibility = false;
        $cta['title'] = "Discover more Products : zStarter";
        $cta['button_label'] = "Product Now";
        $cta['button_route'] = route('product');
    @endphp
@endsection
<style>
    .gradient {
        background: #f2efeb;
    }
</style>
@section('content')
    <section class="wrapper ">
        <div class="container pt-10 pt-md-14 text-center">
            <div class="row mt-5">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item active " aria-current="page">About Us</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-xl-6 mx-auto">
                    <h1 class="display-1 mb-4">Crafting Words That Touch the Heart</h1>
                    <p class="lead fs-lg mb-0">At Good Greets, we specialize in creating beautiful poetry and heartfelt messages for every occasion. Let our words elevate your celebrations!</p>
                </div>
            </div>
        </div>
    </section>

    <section class="wrapper bg-light angled upper-end lower-end ">
        <div class="container py-14 py-md-16">
            <div class="row gx-lg-8 gx-xl-12 gy-10 mb-14 mb-md-17 align-items-center">
                <div class="col-lg-6 position-relative order-lg-2">
                    <div class="overlap-grid overlap-grid-2">
                        <div class="item">
                            <figure class="rounded shadow"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRI2EUnA_uInSaobxOM05lQ5qXqj8-ivFnHrQ&s" alt="Poetry Celebration"></figure>
                        </div>
                        <div class="item">
                            <figure class="rounded shadow"><img src="https://static.vecteezy.com/system/resources/thumbnails/003/909/689/small/celebration-of-firework-background-free-vector.jpg" alt="Special Occasion"></figure>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('site/assets/img/icons/lineal/megaphone.svg') }}" class="svg-inject icon-svg icon-svg-md mb-4" alt="About Us" />
                    <h2 class="display-4 mb-3">Who We Are</h2>
                    <p class="lead fs-lg">Good Greets is passionate about expressing emotions through words. We provide heartfelt poetry and messages for every celebration, making your moments unforgettable.</p>
                    <p class="mb-6">From wedding vows to birthday wishes, our content is crafted with love and creativity.</p>
                    <div class="row gy-3 gx-xl-8">
                        <div class="col-xl-6">
                            <ul class="icon-list bullet-bg bullet-soft-primary mb-0">
                                <li><span><i class="uil uil-check"></i></span><span>Personalized Poetry</span></li>
                                <li class="mt-3"><span><i class="uil uil-check"></i></span><span>Meaningful Messages</span></li>
                            </ul>
                        </div>
                        <div class="col-xl-6">
                            <ul class="icon-list bullet-bg bullet-soft-primary mb-0">
                                <li><span><i class="uil uil-check"></i></span><span>Creative Writing Services</span></li>
                                <li class="mt-3"><span><i class="uil uil-check"></i></span><span>Custom Greetings</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-10 col-xl-8 col-xxl-7 mx-auto text-center">
                    <img src="{{ asset('site/assets/img/icons/lineal/list.svg') }}" class="svg-inject icon-svg icon-svg-md mb-4" alt="How We Work" />
                    <h2 class="display-4 mb-4 px-lg-14">How Good Greets Works</h2>
                </div>
            </div>
            <div class="row gx-lg-8 gx-xl-12 gy-10 align-items-center">
                <div class="col-lg-6 order-lg-2">
                    <div class="card me-lg-6">
                        <div class="card-body p-6">
                            <div class="d-flex flex-row">
                                <div>
                                    <span class="icon btn btn-circle btn-lg btn-soft-primary pe-none me-4"><span class="number">1</span></span>
                                </div>
                                <div>
                                    <h4 class="mb-1">Consultation</h4>
                                    <p class="mb-0">We discuss your needs and vision to create personalized content.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card ms-lg-13 mt-6">
                        <div class="card-body p-6">
                            <div class="d-flex flex-row">
                                <div>
                                    <span class="icon btn btn-circle btn-lg btn-soft-primary pe-none me-4"><span class="number">2</span></span>
                                </div>
                                <div>
                                    <h4 class="mb-1">Creation</h4>
                                    <p class="mb-0">Our talented writers craft beautiful poetry and messages tailored to your occasion.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mx-lg-6 mt-6">
                        <div class="card-body p-6">
                            <div class="d-flex flex-row">
                                <div>
                                    <span class="icon btn btn-circle btn-lg btn-soft-primary pe-none me-4"><span class="number">3</span></span>
                                </div>
                                <div>
                                    <h4 class="mb-1">Delivery</h4>
                                    <p class="mb-0">We deliver your personalized content, ready to be shared and cherished.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2 class="display-6 mb-3">Our Commitment to You</h2>
                    <p class="lead fs-lg pe-lg-5">At Good Greets, we strive to make your special moments even more beautiful with our words.</p>
                    <p>Let us help you express your feelings in a way that resonates and inspires.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="wrapper bg-light">
        <div class="container pb-14 pb-md-16">
            <div class="row gx-lg-8 gx-xl-12 gy-6 mb-10 align-items-center">
                <div class="col-lg-6 order-lg-2">
                    <ul class="progress-list">
                        <li class="mt-10">
                            <p>Poetry Crafting</p>
                            <div class="progressbar line blue" data-value="100"></div>
                        </li>
                        <li>
                            <p>Custom Messages</p>
                            <div class="progressbar line green" data-value="90"></div>
                        </li>
                        <li>
                            <p>Creative Writing</p>
                            <div class="progressbar line yellow" data-value="85"></div>
                        </li>
                        <li>
                            <p>Client Satisfaction</p>
                            <div class="progressbar line orange" data-value="95"></div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <h3 class="display-5 mb-5">Your Words, Our Passion</h3>
                    <p>We believe in the power of words to create beautiful memories. Let us help you celebrate life's moments with our heartfelt content.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="wrapper bg-gray">
        <div class="container py-14 py-md-16">
            <h3 class="display-5 mb-5">Join Us in Celebrating Life’s Moments</h3>
            <div class="row gx-lg-8 gx-xl-12 gy-10 mb-14 mb-md-16 align-items-center">
                <div class="col-lg-7">
                    <figure><img class="w-auto" src="https://img.freepik.com/premium-photo/people-sit-outside-watching-fireworks-celebration-style-iconic-american_921860-19202.jpg?w=740" alt="Celebration Illustration" style="height: 250px !important" /></figure>
                </div>
                <div class="col-lg-5">
                    <h2 class="fs-15 text-uppercase text-line text-primary mb-3">Your Celebration, Our Words</h2>
                    <h3 class="display-5 mb-7">Creating Moments That Matter</h3>
                    <p>Let Good Greets be your partner in expressing love and joy through words.</p>
                </div>
            </div>
        </div>
    </section>
@endsection




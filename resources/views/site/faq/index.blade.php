@extends('layouts.app')

@section('meta_data')
    @php
		$meta_title =  ($faqs->page_meta_title) ? $faqs->page_meta_title : getSetting('app_name');
		$meta_description = ($faqs->page_meta_description) ? $faqs->page_meta_description : '';
		$meta_keywords = ($faqs->page_keywords) ? $faqs->page_keywords : getSetting('app_name');
		$meta_motto = (false) ? $faqs->page_keywords : getSetting('app_name');
	@endphp
@endsection

@section('content')
    <section class="wrapper bg-soft-primary">
        <div class="container pt-10 pb-12 pt-md-14 pb-md-16 text-center">
            <div class="row mt-5">
                <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
                    aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active text-success" aria-current="page">FAQS</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-9 col-lg-7 col-xl-6 mx-auto">
                    <h1 class="display-1 mb-3">FAQ</h1>
                    <p class="lead px-xxl-10">Find answers to some frequently asked questions here.</p>
                </div>
                <!-- /column -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
    <!-- /section -->
    <div class="container">
        <div class="row">
            <aside class="col-xl-2 sidebar doc-sidebar mt-md-0 py-16 d-none d-xl-block">
                <div class="widget pb-3">
                    <h6 class="widget-title fs-17 mb-2">Usage</h6>
                    <nav id="collapse-usage">
                        <ul class="list-unstyled fs-sm lh-sm text-reset">
                            <li><a href="javascript:void();">Get Started</a></li>
                            <li><a href="javascript:void()">Forms</a></li>
                            <li><a href="javascript:void()" class="active">FAQ</a></li>
                            <li><a href="javascript:void()">Changelog</a></li>
                            <li><a href="javascript:void()">Credits</a></li>
                        </ul>
                    </nav>
                    <!-- /nav -->
                </div>
                <!-- /.widget -->
                <div class="widget pb-3">
                    <h6 class="widget-title fs-17 mb-2">Styleguide</h6>
                    <nav id="collapse-style">
                        <ul class="list-unstyled fs-sm lh-sm text-reset">
                            <li><a href="javascript:void()">Colors</a></li>
                            <li><a href="javascript:void()">Fonts</a></li>
                            <li><a href="javascript:void()">SVG Icons</a></li>
                            <li><a href="javascript:void()">Font Icons</a></li>
                            <li><a href="javascript:void()">Illustrations</a></li>
                            <li><a href="javascript:void()">Backgrounds</a></li>
                            <li><a href="javascript:void()">Misc</a></li>
                        </ul>
                    </nav>
                    <!-- /nav -->
                </div>
                <!-- /.widget -->
                <div class="widget pb-3">
                    <h6 class="widget-title fs-17 mb-2">Elements</h6>
                    <nav id="collapse-elements">
                        <ul class="list-unstyled fs-sm lh-sm text-reset">
                            <li><a href="javascript:void()">Accordion</a></li>
                            <li><a href="javascript:void()">Alerts</a></li>
                            <li><a href="javascript:void()">Animations</a></li>
                            <li><a href="javascript:void()">Avatars</a></li>
                            <li><a href="javascript:void()">Background</a></li>
                            <li><a href="javascript:void()">Badges</a></li>
                            <li><a href="javascript:void()">Buttons</a></li>
                            <li><a href="javascript:void()">Card</a></li>
                            <li><a href="javascript:void()">Carousel</a></li>
                            <li><a href="javascript:void()">Dividers</a></li>
                            <li><a href="javascript:void()">Form Elements</a></li>
                            <li><a href="javascript:void()">Image Hover</a></li>
                            <li><a href="javascript:void()">Image Mask</a></li>
                            <li><a href="javascript:void()">Lightbox</a></li>
                            <li><a href="javascript:void()">Media Player</a></li>
                            <li><a href="javascript:void()">Modal</a></li>
                            <li><a href="javascript:void()">Pagination</a></li>
                            <li><a href="javascript:void()">Progressbar</a></li>
                            <li><a href="javascript:void()">Shadows</a></li>
                            <li><a href="javascript:void()">Shapes</a></li>
                            <li><a href="javascript:void()">Tables</a></li>
                            <li><a href="javascript:void()">Tabs</a></li>
                            <li><a href="javascript:void()">Text Animations</a></li>
                            <li><a href="javascript:void()">Text Highlight</a></li>
                            <li><a href="javascript:void()">Tiles</a></li>
                            <li><a href="javascript:void()">Tooltips & Popovers</a></li>
                            <li><a href="javascript:void()">Typography</a></li>
                        </ul>
                    </nav>
                    <!-- /nav -->
                </div>
                <!-- /.widget -->
            </aside>
            <!-- /column -->
            <div class="col-xl-8 order-xl-2">
                <section id="snippet-1" class="wrapper py-16">
                    <h2 class="mb-3">Frequently Asked Questions</h2>
                    <p class="lead mb-5">If you don't see an answer to your question here, please feel free to contact us
                        with the links below:</p>
                    <a href="javascript:void();" class="btn btn-primary rounded-pill me-2" target="_blank">Contact Form</a>
                    <a href="javascript:void();" class="btn btn-soft-primary rounded-pill" target="_blank">Discussions
                        Page</a>
                    <div class="accordion accordion-wrapper mt-10" id="accordion">
                        <div class="card accordion-item">
                            <div class="card-header" id="faq-0">
                                <button class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-collapse-0"
                                    aria-expanded="true" aria-controls="faq-collapse-0"> How to reach v2.0.0 documentation?
                                </button>
                            </div>
                            <!--/.card-header -->
                            <div id="faq-collapse-0" class="accordion-collapse collapse" aria-labelledby="faq-0">
                                <div class="card-body">
                                    <p>You can reach the v2.0.0 documentation <a href="#"
                                            class="external bg-primary text-white" target="_blank">here</a></p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->
                        <div class="card accordion-item">
                            <div class="card-header" id="faq-1">
                                <button class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-collapse-1"
                                    aria-expanded="true" aria-controls="faq-collapse-1"> Can I use this template without
                                    using Gulp or SCSS? </button>
                            </div>
                            <!--/.card-header -->
                            <div id="faq-collapse-1" class="accordion-collapse collapse" aria-labelledby="faq-1">
                                <div class="card-body">
                                    <p>Yes. Gulp is optional. You can use plain HTML / CSS / JS to customize zStarter. Files
                                        you need are located in <code class="folder">dev</code> folder.</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->
                        <div class="card accordion-item">
                            <div class="card-header" id="faq-2">
                                <button class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-collapse-2"
                                    aria-expanded="true" aria-controls="faq-collapse-2"> How can I remove unwanted
                                    plugins?</button>
                            </div>
                            <!--/.card-header -->
                            <div id="faq-collapse-2" class="accordion-collapse collapse" aria-labelledby="faq-2">
                                <div class="card-body">
                                    <p>All third-party plugin JS files are located in <code
                                            class="folder">src/assets/js/vendor</code> and their CSS files are in <code
                                            class="folder">src/assets/css/vendor</code>. Just remove unwanted vendor JS /
                                        CSS files from vendor folders and then remove unwanted functions from <code
                                            class="file">src/assets/js/theme.js</code> and recompile.</p>
                                    <p>If you're <strong>not using</strong> Gulp, you can remove unwanted plugins manually
                                        from <code class="file">dev/assets/js/plugins.js</code>, <code
                                            class="file">dev/assets/js/theme.js</code> and their CSS from <code
                                            class="file">dev/assets/css/plugins.css</code>.</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->
                        <div class="card accordion-item">
                            <div class="card-header" id="faq-6">
                                <button class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-collapse-6"
                                    aria-expanded="true" aria-controls="faq-collapse-6">How can I remove unwanted
                                    CSS?</button>
                            </div>
                            <!--/.card-header -->
                            <div id="faq-collapse-6" class="accordion-collapse collapse" aria-labelledby="faq-6">
                                <div class="card-body">
                                    <p>Bootstrap SCSS imports are located in <code
                                            class="file">src/assets/scss/_bootstrap.scss</code> and theme SCSS imports
                                        are in <code class="file">src/assets/scss/theme/_theme.scss</code>. Remove or
                                        comment any unwanted import and recompile.</p>
                                    <p>If you're <strong>not using</strong> Gulp, you can remove unwanted CSS manually from
                                        <code class="file">dev/assets/css/style.css</code>
                                    </p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->
                        <div class="card accordion-item">
                            <div class="card-header" id="faq-3">
                                <button class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-collapse-3"
                                    aria-expanded="true" aria-controls="faq-collapse-3"> Does zStarter support RTL?
                                </button>
                            </div>
                            <!--/.card-header -->
                            <div id="faq-collapse-3" class="accordion-collapse collapse" aria-labelledby="faq-3">
                                <div class="card-body">
                                    <p>No, not currently. Although with the use of <a
                                            href="https://rtlcss.com/learn/usage-guide/install/" target="_blank"
                                            class="external">RTLCSS</a> project you can generate RTL version of <code
                                            class="file">style.css</code>, however some template specific styles won’t
                                        have support for RTL out of the box.</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->
                        <div class="card accordion-item">
                            <div class="card-header" id="faq-4">
                                <button class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-collapse-4"
                                    aria-expanded="true" aria-controls="faq-collapse-4"> Why SVG icons appear black?
                                </button>
                            </div>
                            <!--/.card-header -->
                            <div id="faq-collapse-4" class="accordion-collapse collapse" aria-labelledby="faq-4">
                                <div class="card-body">
                                    <p>Due to the <a href="https://en.wikipedia.org/wiki/Same-origin_policy"
                                            target="_blank" class="external">same-origin policy</a> SVGInject does not
                                        work when run from the local file system in many browsers (Chrome, Safari). Please
                                        test on a working web server.</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->
                        <div class="card accordion-item">
                            <div class="card-header" id="faq-5">
                                <button class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-collapse-5"
                                    aria-expanded="true" aria-controls="faq-collapse-5"> How to make the contact or
                                    newsletter forms work? </button>
                            </div>
                            <!--/.card-header -->
                            <div id="faq-collapse-5" class="accordion-collapse collapse" aria-labelledby="faq-5">
                                <div class="card-body">
                                    <p>Follow the instructions <a href="#" target="_blank"
                                            class="external">here</a> to reach the guide on configuring the contact or
                                        newsletter forms in zStarter. If the forms don't work or if you receive any errors
                                        please keep in mind that the contact forms won't work on local environment. Please
                                        test them on a working web server.</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->
                        <div class="card accordion-item">
                            <div class="card-header" id="faq-7">
                                <button class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-collapse-7"
                                    aria-expanded="true" aria-controls="faq-collapse-7"> Does zStarter require jQuery?
                                </button>
                            </div>
                            <!--/.card-header -->
                            <div id="faq-collapse-7" class="accordion-collapse collapse" aria-labelledby="faq-7">
                                <div class="card-body">
                                    <p>No, as of v3.0.0, zStarter no longer requires jQuery.</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->
                        <div class="card accordion-item">
                            <div class="card-header" id="faq-8">
                                <button class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-collapse-8"
                                    aria-expanded="true" aria-controls="faq-collapse-8"> Why am I getting an error while
                                    installing to Wordpress? </button>
                            </div>
                            <!--/.card-header -->
                            <div id="faq-collapse-8" class="accordion-collapse collapse" aria-labelledby="faq-8">
                                <div class="card-body">
                                    <p>zStarter is an HTML template, not a Wordpress theme. So it cannot be installed in
                                        Wordpress.</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->
                        <div class="card accordion-item">
                            <div class="card-header" id="faq-9">
                                <button class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-collapse-9"
                                    aria-expanded="true" aria-controls="faq-collapse-9"> Why the image mask doesn't work
                                    on my copy of the item? </button>
                            </div>
                            <!--/.card-header -->
                            <div id="faq-collapse-9" class="accordion-collapse collapse" aria-labelledby="faq-9">
                                <div class="card-body">
                                    <p>There is a known browsers-spesific issue regarding image-mask that prevents them from
                                        working on local environments. (Running from a filesystem is now restricted, so you
                                        can no longer reference one file from another.) If you upload the template on a
                                        working server image masks should work properly.</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->
                        <div class="card accordion-item">
                            <div class="card-header" id="faq-10">
                                <button class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-collapse-10"
                                    aria-expanded="true" aria-controls="faq-collapse-10"> How to disable sourcemaps?
                                </button>
                            </div>
                            <!--/.card-header -->
                            <div id="faq-collapse-10" class="accordion-collapse collapse" aria-labelledby="faq-10">
                                <div class="card-body">
                                    <p>You can disable sourcemaps in <code class="file">dist/asssets/css/style.css</code>
                                        by uncommenting lines <mark class="doc">148</mark> and <mark
                                            class="doc">158</mark> on <code class="file">gulpfile.js</code> and then
                                        running <kbd class="terminal bg-pale-primary">gulp serve</kbd> command.</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->
                        <div class="card accordion-item">
                            <div class="card-header" id="faq-11">
                                <button class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-collapse-11"
                                    aria-expanded="true" aria-controls="faq-collapse-11"> How to add a link to dropdown
                                    parent? </button>
                            </div>
                            <!--/.card-header -->
                            <div id="faq-collapse-11" class="accordion-collapse collapse" aria-labelledby="faq-11">
                                <div class="card-body">
                                    <p>Use the code below to add link to dropdown parent:</p>
                                </div>
                                <!--/.card-body -->
                                <div class="code-wrapper">
                                    <div class="code-wrapper-inner">
                                        <pre class="language-html bg-dark rounded-bottom"><code>&lt;li class=&quot;nav-item dropdown dropdown-mega parent-link&quot;&gt;
                                        &lt;a class=&quot;nav-link&quot; href=&quot;PARENT LINK&quot;&gt;Dropdown&lt;/a&gt;
                                        &lt;a class=&quot;nav-link dropdown-toggle&quot; href=&quot;#&quot; data-bs-toggle=&quot;dropdown&quot;&gt;&lt;span class=&quot;visually-hidden&quot;&gt;Toggle Dropdown&lt;/span&gt;&lt;/a&gt;
                                        &lt;ul class=&quot;dropdown-menu mega-menu&quot;&gt;
                                        ...
                                        &lt;/ul&gt;
                                        &lt;/li&gt;</code></pre>
                                    </div>
                                </div>
                            </div>
                            <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->
                        <div class="card accordion-item">
                            <div class="card-header" id="faq-12">
                                <button class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-collapse-12"
                                    aria-expanded="true" aria-controls="faq-collapse-12"> What font is used on the
                                    zStarter
                                    logo? </button>
                            </div>
                            <!--/.card-header -->
                            <div id="faq-collapse-12" class="accordion-collapse collapse" aria-labelledby="faq-12">
                                <div class="card-body">
                                    <p>The font used in the zStarter logo is <a class="external my-0"
                                            href="https://fonts.google.com/specimen/Manrope" target="_blank">Manrope</a>
                                    </p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->
                        <div class="card accordion-item">
                            <div class="card-header" id="faq-13">
                                <button class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-collapse-13"
                                    aria-expanded="true" aria-controls="faq-collapse-13"> How to enable STMP
                                    authentication on my contact form? </button>
                            </div>
                            <!--/.card-header -->
                            <div id="faq-collapse-13" class="accordion-collapse collapse" aria-labelledby="faq-13">
                                <div class="card-body">
                                    <p>You can enable STMP authentication by following the instructions shown in <a
                                            href="#" class="internal mt-n1">Forms documentation</a></p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->
                        <div class="card accordion-item">
                            <div class="card-header" id="faq-14">
                                <button class="collapsed" data-bs-toggle="collapse" data-bs-target="#faq-collapse-14"
                                    aria-expanded="true" aria-controls="faq-collapse-14"> How to add reCAPTCHA to my
                                    contact form? </button>
                            </div>
                            <!--/.card-header -->
                            <div id="faq-collapse-14" class="accordion-collapse collapse" aria-labelledby="faq-14">
                                <div class="card-body">
                                    <p>Please follow the instructions shown in <a href="#"
                                            class="internal mt-n1">Forms documentation</a> to add reCAPTCHA to your contact
                                        form.</p>
                                </div>
                                <!--/.card-body -->
                            </div>
                            <!--/.accordion-collapse -->
                        </div>
                        <!--/.accordion-item -->
                    </div>
                    <!--/.accordion -->
                </section>
            </div>
            <!-- /column -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
@endsection

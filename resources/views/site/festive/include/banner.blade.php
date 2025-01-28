<style>
    body {
        font-family: 'Source Sans Pro', sans-serif;
    }

    .image-banner {
        width: 100%;
        height: 17rem;
        background: url('{{ asset('site/assets/img/banner.jpg') }}') no-repeat center center;
        background-size: cover;
        position: relative;
        overflow: hidden;
    }

    .filters p {
        background: #abcdd7;
        padding: 5px 20px;
        border-radius: 1rem;
        font-weight: 600;
        cursor: pointer;
        margin: 0 5px;
    }

    .filter-options {
        margin: 4px;
        background: #ffffffd6;
        padding: 3px 15px;
        border-radius: 1rem;
        font-weight: 600;
        cursor: pointer;
        color: black;
        border: 1px solid transparent;
        transition: color 0.3s ease, border-color 0.3s ease;
        user-select: none;
    }

    .filter-options.active {
        color: white;
        background-color: #467383;
    }

    .banner-img {
        padding-top: 4rem;
    }
</style>
<section class="wrapper bg-cover">
    <div class="container banner-img py-1">
        <div class="banner">
            <a href="#">
                <img src="{{ asset('site/assets/img/Black_Orange_Pizza_Mobile_Banner_Ads.gif') }}" alt="Banner Image"
                    style="width: auto; height: 105px; text-align: center; margin: 0 auto;  display: block;">
            </a>
        </div>
    </div>
</section>

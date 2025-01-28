@extends('layouts.app')

@section('meta_data')
    @php
        $categoryTitle = @$category ? convertLowerToUpper(@$category) : '';
        $occasionTitle = @$occasion ? ' - ' . convertLowerToUpper(@$occasion) : '';
        $eventSingleTitle = @$event ? convertLowerToUpper(@$event->name) : '';
        $eventTitle = @$occasion && @$event ? ' - ' . convertLowerToUpper(@$event->name) : '';
        $categoryBreadcrumb = @$category ? ' / ' . convertLowerToUpper(@$category) : '';
        $occasionBreadcrumb = @$occasion ? ' / ' . convertLowerToUpper(@$occasion) : '';
        $eventBreadcrumb = @$event ? ' / ' . convertLowerToUpper(@$event->name) : '';
        $meta_title =
            $contents->count() .
            ' ' .
            @$event->name .
            ' ' .
            str_replace('/', '', @$categoryBreadcrumb) .
            ' | Celebrate Every Bond, Age & Emotion';
        // $meta_title = @$metas->title ?? $categoryTitle . $occasionTitle . (@$eventTitle ? @$eventTitle : @$eventSingleTitle);

        $eventsCounts = [
            'sentiment_id' => 0,
            'age_group_id' => 0,
            'language_id' => 0,
            'relation_id' => 0,
        ];
        foreach ($filterOptions as $mainKey => $filterOption) {
            if (in_array($mainKey, array_keys($eventsCounts)) && $filterOption->count() > 1) {
                $eventsCounts[$mainKey] = count($filterOption);
            }
        }

        $meta_description =
            'Browse ' .
            $contents->count() .
            ' ' .
            $event->name .
            ' wishes crafted for ' .
            @$eventsCounts['sentiment_id'] .
            ' sentiments, ' .
            @$eventsCounts['language_id'] .
            ' languages, ' .
            @$eventsCounts['relation_id'] .
            ' relations, and ' .
            @$eventsCounts['age_group_id'] .
            ' age groups. Find the perfect ' .
            str_replace('/', '', @$categoryBreadcrumb) .
            ' for every connection this festive season!';
        // $meta_description = @$meta_description ?? '';

        $meta_keywords = @$metas->keyword ?? '';
        $meta_motto = @$app_settings['site_motto'] ?? '';
        $meta_abstract = @$app_settings['site_motto'] ?? '';
        $meta_author_name = @$app_settings['app_name'] ?? 'Defenzelite';
        // $meta_author_email = @$app_settings['frontend_footer_email'] ?? 'dev@defenzelite.com';
        $meta_reply_to = @$app_settings['frontend_footer_email'] ?? 'dev@defenzelite.com';
        $meta_img = ' ';
        $cta_visibility = false;
        $cta['title'] = 'Discover more about the power of ultimate project starter: zStarter';
        $cta['button_label'] = 'Discover Now';
        $cta['button_route'] = route('about');
    @endphp
@endsection
{{-- @dd($occasion) --}}
<style>
    body {
        font-family: 'Source Sans Pro', sans-serif !important;
    }

    .filter-label {
        font-weight: 600;
        font-size: 13px;
    }

    .gradient {
        background: #f2efeb;
        height: 100%;
    }

    .tooltip-content p {
        color: white !important;
    }

    .filter-options:hover .tooltip-content {
        display: block;
    }

    .custom-select:focus {
        outline: none;
    }

    blockquote {
        cursor: pointer;
    }

    .copyCard {
        position: relative;
    }

    .copyMessage {
        display: none;
        position: absolute;
        bottom: 10px;
        left: 0;
        right: 0;
        background: #333;
    }

    .mobile-filter {
        display: none;
    }

    .container {
        padding-top: 4rem;
    }

    .banner-img {
        padding: 1rem 0 !important;
    }

    @media (max-width: 600px) {
        .container {
            padding-top: 2rem;
        }

        .mobile-filter {
            display: block;
        }
    }
</style>
@section('content')
    <section class="gradient">
        <div class="container">
            <div class="my-1"> <a href="{{ route('index') }} ">Home</a> {{ @$categoryBreadcrumb }}
                {{ @$occasionBreadcrumb }}
                {{ @$eventBreadcrumb }}</div>
            {{-- card --}}
            <div class="p-md-0 p-2">
                {{-- mobile responsive filter --}}
                <div class="mobile-filter">
                    @include('site.festive.mobileFilter.filter')
                </div>
                @if (@$event->name != null && @$event->name != '')
                    @include('site.festive.partials.event_card')
                @endif
            </div>
            <div class="row mt-1 px-md-0 px-3">
                <div class="col-md-12 col-lg-12 col-xs-12 p-0">
                    <div class="card-container mb-2">
                        @include('site.festive.include.content')
                    </div>
                </div>
            </div>
            @if (@$event->name != null && @$event->name != '' && @$event->description != null)
                @include('site.festive.partials.event-description')
            @endif
    </section>
    {{-- modal --}}
    @include('site.festive.modal.share')
    @push('script')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                function getFilterData(selectedOption) {
                    var currentUrl = window.location.href;
                    var url = new URL(currentUrl);
                    if (Array.isArray(selectedValues) && selectedValues.length > 0) {
                        selectedValues.forEach(item => {
                            const selectedKey = item.target; // Using the target as the key
                            const selectedValue = item.value; // Using the value

                            // Check if the key exists in the URL
                            if (url.searchParams.has(selectedKey)) {
                                // If the key exists, update the value
                                url.searchParams.set(selectedKey, selectedValue);
                            } else {
                                // If the key doesn't exist, append it with the value
                                url.searchParams.append(selectedKey, selectedValue);
                            }
                        });
                    } else {
                        if (selectedOption.key === 'sort') {
                            var selectedValue = selectedOption.value;
                            var selectedKey = selectedOption.key;
                        } else {
                            var selectedValue = selectedOption.val();
                            var selectedKey = selectedOption.data('key1');
                        }
                        // var currentUrl = window.location.href;
                        // var url = new URL(currentUrl);
                        // Check if the key exists in the URL
                        if (url.searchParams.has(selectedKey)) {
                            // If the key exists, update the value
                            url.searchParams.set(selectedKey, selectedValue);
                        } else {
                            // If the key doesn't exist, append it with the value
                            url.searchParams.append(selectedKey, selectedValue);
                        }
                    }
                    history.replaceState(null, '', url.toString());
                    $.ajax({
                        url: url.toString(),
                        method: 'GET',
                        data: {
                            key: selectedKey,
                            value: selectedValue
                        },
                        success: function(response) {
                            // console.log(response.contents);
                            $('.card-container').html(response.contents);
                            // Reinitialize lazy loading for new elements
                            initializeLazyLoading();
                        },
                        error: function(xhr, status, error) {
                            console.log("An error occurred: " + error);
                        }
                    });
                }
                $(document).on('change', '.filter-select', function() {
                    var selectedOption = $(this).find('option:selected');
                    getFilterData(selectedOption);
                });

                $(document).on('click', '.mobileFilterApply', function() {
                    document.getElementById('filterModal').style.display = 'none';
                    getFilterData(selectedValues);
                });
                $(document).on('change', 'input[name="sort"]', function() {
                    selectedValues.length = 0;
                    var selectedOption = {
                        key: $(this).attr('name'), // Get the 'name' attribute (key)
                        value: $(this).val() // Get the selected value
                    }; // Get the selected value
                    getFilterData(selectedOption);
                });


            });
        </script>

<script>
    function openShareModal(contentId, content) {
        // Set the content to the input field
        document.getElementById('linkInput').textContent = content;
        var baseUrl = window.location.origin;
        var url = baseUrl + '/update-share-count';
        // Send AJAX request to update share count
        $.ajax({
            url: url,
            method: 'GET',
            data: {
                content_id: contentId
            },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error('Error updating share count:', error);
            }
        });

        // Show the modal
        const copyMessage = document.getElementById('copyMessageNew');
        copyMessage.style.display = 'block';
        var myModal = new bootstrap.Modal(document.getElementById('ShareModel'));
        myModal.show();
        basic();
        setTimeout(() => {
            copyMessage.style.display = 'none';
        }, 1000);


    }
</script>

        <script>
            $(document).ready(function() {
                console.log('ok');
                document.getElementById('animated-placeholder-name').focus();
                $(document).on('click', '#search', function() {
                    event.preventDefault(); // Prevent default form submission
                    let query = $('#animated-placeholder-name').val();
                    var currentUrl = window.location.href;
                    if (query.length >= 0) {
                        $.ajax({
                            url: currentUrl,
                            method: 'GET',
                            data: {
                                query: query
                            },
                            success: function(response) {
                                $('.card-container').html(response.contents);
                                initializeLazyLoading();

                            },
                            error: function(xhr, status, error) {
                                console.error("AJAX Error: ", status, error);
                                alert('Error occurred while fetching results');
                            }
                        });
                    }
                    return false; // Stop further execution


                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $(document).on('click', '#reset', function() {
                    var currentUrl = window.location.href;
                    var url = new URL(currentUrl);

                    // Define the parameters you want to remove
                    var filterVariables = [
                        'language_id',
                        'sentiment_id',
                        'age_group_id',
                        'relation_id',
                        'gender_specificity_id',
                        'content_length_id',
                        'badge_id',
                        'media_type_id',
                        'sort',

                    ];

                    // Loop through each filter variable and remove it from the URL
                    filterVariables.forEach(function(param) {
                        url.searchParams.delete(param);
                    });

                    // Redirect to the new URL without the parameters
                    window.location.href = url.toString();
                });
            });
        </script>
        <script>
            function initializeLazyLoad() {
                const lazyLoadElements = document.querySelectorAll('.lazy-load');

                const options = {
                    root: null,
                    rootMargin: '0px',
                    threshold: 0.6
                };

                const loadContent = (entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const contentElement = entry.target;
                            const description = contentElement.getAttribute('data-src');
                            contentElement.querySelector('.card-content').textContent = description;
                            contentElement.style.opacity = 1;
                            observer.unobserve(contentElement);
                        }
                    });
                };

                const observer = new IntersectionObserver(loadContent, options);

                lazyLoadElements.forEach(element => {
                    observer.observe(element);
                });
            }

            // Initial lazy loading setup
            document.addEventListener("DOMContentLoaded", initializeLazyLoad);
        </script>
        <script>
            $(document).ready(function() {
                $('.cards1').removeClass('d-none');
                $('.shimmer-content').addClass('d-none');
                $('.content-found').removeClass('d-none');
            });
        </script>
        <script>
            $(document).on('ready ajaxComplete', function() {
                // Show shimmer and hide the content initially
                $('.cards1').addClass('d-none');
                $('.shimmer-content').removeClass('d-none');

                // Add a delay before showing the content and hiding the shimmer
                setTimeout(function() {
                    $('.cards1').removeClass('d-none'); // Show content
                    $('.shimmer-content').addClass('d-none');
                    $('.content-found').removeClass('d-none'); // Hide shimmer
                }, 500); // Delay in milliseconds (1000 ms = 1 second)
            });
        </script>
        <script>
            function initializeLazyLoading() {
                const contentItems = document.querySelectorAll('.content-item');
                const loadingIndicator = document.getElementById('loading-indicator');
                let currentItem = 0;
                const itemsToShow = 30; // Number of items to show on each scroll

                function loadMoreItems() {
                    loadingIndicator.style.display = 'block'; // Show loading indicator
                    setTimeout(() => { // Simulate loading time
                        for (let i = 0; i < itemsToShow; i++) {
                            if (currentItem < contentItems.length) {
                                contentItems[currentItem].style.display = 'block';
                                currentItem++;
                            }
                        }
                        loadingIndicator.style.display = 'none'; // Hide loading indicator
                    }, 500); // Adjust this time as needed
                }

                // Load initial items
                loadMoreItems();

                // Create an Intersection Observer
                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        loadMoreItems();

                        // Stop observing if all items are shown
                        if (currentItem >= contentItems.length) {
                            observer.disconnect();
                        }
                    }
                });

                // Start observing the load marker
                observer.observe(document.getElementById('load-marker'));
            }

            // Call the function when the DOM is fully loaded
            document.addEventListener('DOMContentLoaded', initializeLazyLoading);
        </script>
    @endpush
    {{-- modal call --}}
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    {{-- confetti  animation --}}
    <script>
        function basic() {
            var canvas = document.getElementById('confettiCanvas');

            var confettiInstance = confetti.create(canvas, {
                resize: true, // Resize to fit the canvas
                useWorker: true // Optimize performance using a worker
            });

            // Confetti effect inside the modal
            confettiInstance({
                particleCount: 500,
                spread: 70,
                origin: {
                    x: 0.5,
                    y: 0.9
                },
                colors: ['#bb0000', '#ffffff', '#000'],
                scalar: 0.7, // Control size of the confetti particles
                ticks: 200 // Duration of confetti
            });
        }
    </script>
   

    {{-- card share model --}}

    {{-- Start  --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-bs-toggle="modal"]').forEach(function(trigger) {
                trigger.addEventListener('click', function() {
                    var link = this.getAttribute('data-link');
                    var modal = new bootstrap.Modal(document.getElementById('ShareModel'), {});
                    document.getElementById('linkInput').value = link;
                    modal.show();
                });
            });
        });
    </script>

    {{-- End  --}}
    {{-- copy text --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const blockquotes = document.querySelectorAll('.copyCard');

            blockquotes.forEach(blockquote => {
                blockquote.addEventListener('click', function() {
                    const text = this.querySelector('p').innerText;
                    const message = this.querySelector('.copyMessage');  

                    const textarea = document.createElement('textarea');
                    textarea.value = text;
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);

                    message.style.display = 'block';

                    setTimeout(function() {
                        message.style.display = 'none';
                    }, 1000);
                });
            });
        });
    </script>
@endsection

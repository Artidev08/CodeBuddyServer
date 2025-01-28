<style>
    .responsive-filter {
        width: 100%;
        bottom: 0;
        position: fixed;
        z-index: 99;
        font-size: 20px;
        margin-left: -1.25rem;
    }

    .full-screen-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1000;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .full-screen-modal-content {
        position: relative;
        background: white;
        width: 100%;
        height: 100%;
        padding: 20px;
        box-sizing: border-box;
        top: 2rem;
    }

    .close-btn {
        position: absolute;
        right: 20px;
        cursor: pointer;
        font-size: 24px;
    }

    .sort-modal {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 30%;
        z-index: 1000;
        background-color: white;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
        transform: translateY(100%);
    }

    .sort-modal.open {
        display: block;
        transform: translateY(0);
    }

    .form-check {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-check-input {
        margin-left: auto;
    }
</style>

<!-- Responsive Filter -->
<div class="row bg-white text-center responsive-filter border">
    <div class="col-6 p-1" style="border-right: 1px solid #cacaca;">
        <div class="shot">
            <span><i class="uil uil-sort-amount-down"></i></span>
            <span>Sort</span>
        </div>
        <div class="sort-modal" id="sortModal">
            <div class="sort-modal-content">
                <div class="d-flex justify-content-between">
                    <h4 class="p-2 mb-0 fs-20">Sort By</h4>
                    <span id="closeSortModal" class="close-btn fs-38">&times;</span>
                </div>
                <hr class="m-0">
                <div class="p-2">
                    <form id="sortForm" action="" method="GET">
                        <div class="form-check p-0">
                            <label class="form-check-label fs-18" for="flexRadioDefault1">Popular</label>
                            <input class="form-check-input sort" type="radio" name="sort" id="flexRadioDefault1" value="popular" >
                        </div>
                        <div class="form-check p-0">
                            <label class="form-check-label fs-18" for="flexRadioDefault2">Latest</label>
                            <input class="form-check-input sort" type="radio" name="sort" id="flexRadioDefault2" value="latest" checked>
                        </div>
                        <div class="form-check p-0">
                            <label class="form-check-label fs-18" for="flexRadioDefault3">Oldest</label>
                            <input class="form-check-input sort" type="radio" name="sort" id="flexRadioDefault3" value="oldest">
                        </div>
                    </form>
                    
                    
                </div>
            </div>
        </div>
    </div>


    <div class="col-6 p-1 filter-btn">
        <span><i class="uil uil-filter"></i></span>
        <span>Filters</span>
    </div>
</div>

<!-- All Filters -->
<div class="full-screen-modal" id="filterModal">
    @include('site.festive.mobileFilter.allFilters')
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 {{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Set the action of the form to the current URL
    const currentUrl = window.location.href;
    document.getElementById('sortForm').action = currentUrl;
    });
</script> --}}
<script>
    document.querySelector('.filter-btn').addEventListener('click', function() {
        document.getElementById('filterModal').style.display = 'block';
    });

    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('filterModal').style.display = 'none';
    });

    document.addEventListener('DOMContentLoaded', function() {

        document.querySelector('.shot').addEventListener('click', function() {
            document.getElementById('sortModal').classList.add('open');
        });

        // Close modal when 'closeSortModal' is clicked
        document.getElementById('closeSortModal').addEventListener('click', function() {
            document.getElementById('sortModal').classList.remove('open');
        });

        window.addEventListener('click', function(event) {
            const modal = document.getElementById('sortModal');
            if (event.target === modal) {
                modal.classList.remove('open');
            }
        });

        // // Submit form on radio button change
        // const radios = document.querySelectorAll('input[name="sort"]');
        // radios.forEach(function(radio) {
        //     radio.addEventListener('change', function() {
        //         document.getElementById('sortForm').submit();
        //     });
        // });
    });

</script>

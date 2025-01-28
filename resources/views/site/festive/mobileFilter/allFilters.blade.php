<style>
    ul,
    li {
        list-style-type: none;
        padding: 0;
        margin: 0;
        font-size: 18px;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .hidden {
        display: none;
    }

    #content-area {
        margin-top: 20px;
        font-size: 18px;
    }

    .mobile-filter-btn {
        position: fixed;
        bottom: 0;
        width: 100%;
        margin-left: -1rem;
    }

    .form-check-input:checked {
        background-color: #323130;
        border-color: #323130;
        box-shadow: 0 0 5px #323130;
    }
</style>

<div class="full-screen-modal-content">
    <div class="d-flex justify-content-between">
        <h6 class="mt-1 fs-22">Filters</h6>
        <span class="close-btn fs-40" id="closeModal">&times;</span>
    </div>
    <div id="filterContent">
        <div class="row">
            <div class="col-5 vh-100" style="background-color: #f1f3f6;">
                <ul>
                    @foreach ($filterOptions as $mainKey => $filterOption)
                        <li data-target="{{ $mainKey }}" class="mt-2">{{ formatName($mainKey) }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="col-7">
                <div id="content-area">
                </div>
            </div>
        </div>
        {{-- btn --}}
        <div class="mobile-filter-btn bg-white d-flex justify-content-between p-1">
            <div class="fs-20 lh-xxs ms-1">
                <span class="fw-bold">32</span>
                <p class="mb-0">Content found</p>
            </div>
            <div class="me-1">
                <a href="#" class="btn btn-sm btn-primary rounded fs-22 py-0 px-7 mobileFilterApply">
                    Apply
                </a>
            </div>
        </div>
    </div>
</div>


<script>
    let selectedValues = [];
    const filterData = @json($filterOptions); // Pass the filter data to JavaScript
    const contentArea = document.getElementById('content-area');

    function displayFilter(target) {
        contentArea.innerHTML = ''; // Clear previous content

        if (filterData[target]) {
            let content = '<div>';
            // Loop through filter options for the selected target
           content += ` <div class="form-check mb-1 p-0"> <label class="form-check-label" >
                         All Select
                        </label><input class="form-check-input mobileFilter"
                            type="radio"
                            value=""
                            data-key1="${target}"
                            name="flexRadioDefault${target}"
                            id="${target}"
                            checked ></div>`;
            filterData[target].forEach((option, index) => {
                
                content += `
                    <div class="form-check mb-1 p-0">
                         <label class="form-check-label" for="${target}${index}">
                            ${option.emoji || ''} ${option.name}
                        </label>
                        <input class="form-check-input mobileFilter"
                            type="radio"
                            value="${option.id}"
                            data-key1="${target}"
                            name="flexRadioDefault${target}"
                            id="${target}${index}"
                            {{ request()->has('${target}') && request()->get('${target}') == ${option . id} ? 'checked' : '' }}
                            >
                    </div>
                `;
            });

            content += '</div>';
            contentArea.innerHTML = content; // Insert generated content
            const radioButtons = document.querySelectorAll(`input[name="flexRadioDefault${target}"]`);
            radioButtons.forEach(radio => {
                radio.addEventListener('change', (event) => {
                    const selectedValue = {
                        target: target,
                        value: event.target.value
                    };
                    // Check if the target is already in the array
                    const existingIndex = selectedValues.findIndex(item => item.target === target);
                    if (existingIndex !== -1) {
                        // Update the existing value
                        selectedValues[existingIndex] = selectedValue;
                    } else {
                        // Add new target-value pair
                        selectedValues.push(selectedValue);
                    }
                    console.log(selectedValues); // Log the selected values for debugging
                });
            });
        } else {
            contentArea.innerHTML = '<p>Select a filter to view options.</p>';
        }
    }

    // Add event listeners to all li elements
    document.querySelectorAll('li').forEach(item => {
        item.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            displayFilter(target);
        });
    });

    // Automatically click the first filter when the modal is opened
    document.addEventListener('DOMContentLoaded', function() {
        const firstFilter = document.querySelector('li[data-target]');
        if (firstFilter) {
            const target = firstFilter.getAttribute('data-target');
            displayFilter(target);
        }
    });
</script>

{{-- 
        <div class="footer-py-20 footer_bar" style="position: fixed; bottom: 0px; width: 100%;">
            <div class="container-fulid bg-soft-primary px-5 ">
                <div class="row">
                    <div class="col-sm-12 py-2 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-sm-start d-flex justify-content-between align-items-center">
                                
                            </div>
                        </div>
                        <div>
                            <div class="text-sm-start">
                                <a
                                href="https://www.defenzelite.com/" target="_blank"
                                class="text-reset">  <p class="m-0 text-center bg-color footer-para-left">{{ getSetting('frontend_copyright_text') ?? '' }}. Design with <i class="admin/setting text-danger"></i> by Defenzelite</a></p>
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->
        </div> --}}
        <div class="progress-wrap">
            <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
                <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
            </svg>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#newsletter-form').submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    var form = $(this);
                    var formData = form.serialize(); // Serialize form data

                    $.ajax({
                        url: form.attr('action'), // Use the form's action attribute
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            $('#response-message').removeClass('alert-danger').addClass(
                                    'alert-success')
                                .text(response.message).show();
                            form[0].reset(); // Optionally reset the form
                        },
                        error: function(xhr) {
                            var errorMessage = xhr.responseJSON.message ||
                                'An error occurred. Please try again.';
                            $('#response-message').removeClass('alert-success').addClass(
                                    'alert-danger')
                                .text(errorMessage).show();
                        }
                    });
                });
            });
        </script>

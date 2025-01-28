<style>
    .social-icons {
        padding: 2px 13px;
        cursor: pointer;
    }

    .whatsapp {
        border: 1px solid #50cb5e;
        color: #50cb5e;
    }

    .facebook {
        border: 1px solid #4267b2;
        color: #4267b2;
    }

    .instagram {
        border: 1px solid #af3687;
        color: #af3687;
    }

    .twitter {
        background-color: #1da1f2;
        color: #fff;
    }

    .telegram {
        border: 1px solid #28a8e9;
        color: #28a8e9;
    }

    #copyIcon {
        cursor: pointer;
    }
    #copyInput:focus {
        outline: none;
    }
</style>

<div class="modal" id="CardShareModel" tabindex="-1" role="dialog" aria-labelledby="contactModalCenterLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="d-flex justify-content-between">
                <h5 class="p-2 mb-0 fs-22 ">Share</h5>
                <div>
                    <a type="button" class="close fs-30 mx-4" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
            </div>
            <hr class="m-0 p-0">
            <div class="modal-body">
                <h6>Share this link via</h6>
                <div class="d-flex justify-content-evenly">
                    <div class="social-icons whatsapp rounded-circle">
                        <p class="mb-0 text-center fs-30"><i class="uil uil-whatsapp"></i></p>
                    </div>
                    <div class="social-icons facebook rounded-circle">
                        <p class="mb-0 text-center fs-30"><i class="uil uil-facebook-f"></i></p>
                    </div>
                    <div class="social-icons instagram rounded-circle">
                        <p class="mb-0 text-center fs-30"><i class="uil uil-instagram"></i></p>
                    </div>
                    <div class="social-icons twitter rounded-circle">
                        <p class="mb-0 text-center fs-30"><i class="uil uil-twitter"></i></p>
                    </div>
                    <div class="social-icons telegram rounded-circle">
                        <p class="mb-0 text-center fs-30"><i class="uil uil-telegram-alt"></i></p>
                    </div>
                </div>
                {{--  --}}
                <div class="mt-5 mb-4">
                    <h6 class="">Or Copy link</h6>
                    <div class="d-flex gap-2">
                        <input type="text" id="copyInput" value="https://goodgreets.dze-labs.in/" class="border p-1 w-100 px-2 rounded">
                        <span class="fs-30" id="copyIcon" title="Copy Text">
                            <i class="uil uil-copy"></i>
                        </span>
                    </div>
                    <span id="copyMessage" class="text-dark " style="display: none;">Text copied to clipboard!</span>

                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('copyIcon').addEventListener('click', function() {
        const input = document.getElementById('copyInput');
        const message = document.getElementById('copyMessage');

        input.select();
        input.setSelectionRange(0, 99999);

        document.execCommand('copy');

        message.style.display = 'block';
        message.textContent = 'Text copied to clipboard!';

        setTimeout(() => {
            message.style.display = 'none';
        }, 2000);
    });
    </script>

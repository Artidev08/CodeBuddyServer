<style>
    .social-media-icon p {
        background-color: #e6eff9;
        padding: 0px 14px;
        border-radius: 5px;
        cursor: pointer;
    }

    .copy-link-wrapper {
        margin-top: 15px;
    }

    .copy-link-wrapper input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px 0 0 5px;
    }

    .copy-link-wrapper button {
        padding: 8px 12px;
        background-color: #323130;
        color: white;
        border: none;
        border-radius: 5px 5px 5px 5px;
        cursor: pointer;
    }

    .copy-link-wrapper button:hover {
        background-color: #1a1919;
    }

    .copy-message {
        margin-top: 10px;
        display: none;
        font-size: 14px;
    }

    .modal-title {
        margin-top: 1.5rem;
        margin-left: 4rem;
        color: black;
        font-size: 900;
    }

    .side-line {
        height: 13rem;
    }

    .modal-content {
        background-color: #f2efeb;
    }

    #confettiCanvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }
</style>

<div class="modal" id="ShareModel" tabindex="-1" role="dialog" aria-labelledby="contactModalCenterLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="d-flex justify-content-between">
                <h6 class="mb-0 mx-3 mt-2">
                    Nice Pick!
                </h6>
                <div>
                    <a type="button" class="close fs-30 mx-4" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
            </div>
            <div class="modal-body">
                <!-- social media links -->
                <div class="copy-link-wrapper">
                    <blockquote class="w-100 side-line" type="text" value="">
                        <h2 id="linkInput" class="fs-25 text-dark" style="font-weight: 600;"></h2>
                    </blockquote>

                    <br>    

                    <div class="d-flex justify-content-between mt-2">
                        <button id="copyButton" class="">Copy Content</button>
                        <div class="button-container">
                            <div id="copyMessageNew" class="copy-message pink-color m-1">Content copied!</div>
                        </div>
                    </div>
                </div>

                <!-- Create a canvas for confetti inside the modal -->
                <canvas id="confettiCanvas"></canvas>

            </div>
        </div>
    </div>
</div>

<script>
    function shareOnWhatsApp() {
        // Get the value from the input field
        const message = document.getElementById('linkInput').innerText;

        // Encode the message to ensure it can be used in a URL
        const encodedMessage = encodeURIComponent(message);

        // Create the WhatsApp sharing link
        const whatsappLink = `https://api.whatsapp.com/send?text=${encodedMessage}`;

        // Open the WhatsApp link in a new tab
        window.open(whatsappLink, '_blank');
    }
</script>
<script>
    document.getElementById("copyButton").addEventListener("click", function() {
        var linkInput = document.getElementById("linkInput").innerText;
        navigator.clipboard.writeText(linkInput).then(function() {
            var copyMessage = document.getElementById("copyMessageNew");
            copyMessage.style.display = "block";
            setTimeout(function() {
                copyMessage.style.display = "none";
            }, 1000);
        }).catch(function(error) {
            console.error("Copy failed!", error);
        });
    });
</script>

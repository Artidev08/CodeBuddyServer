<!-- Modal -->
<div class="modal fade p-2" id="AddSpeechText" tabindex="" role="dialog" aria-labelledby="addSummary" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Speak Now</strong></h5>
               
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center mb-2">
                        <button id="speech" class="custom-btn mb-2 speech-pulse">
                            <div class="pulse-ring"></div>
                            <i class="fa fa-microphone text-center mb-1" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="radio d-none"><input type="radio" name="lang" value="en-US"
                                    checked="checked"> US English
                            </div>

                            <p class="output speech-pulse">You said: <strong class="output_result"></strong></p>
                            <textarea name="" class="form-control" id="said" cols="30" rows="10"></textarea>
                            <span class="my-3 output_log float-right"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group text-right">
                    <button class="btn btn-danger " id="restart-activateMic" type="button"> <i
                            class="fa fa-microphone"></i> Listen me</button>
                    <button class="btn btn-outline-primary ml-1" id="addText-VoiceCommand" type="button">Use Text &
                        Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

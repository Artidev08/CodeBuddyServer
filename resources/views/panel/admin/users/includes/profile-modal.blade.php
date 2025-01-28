{{-- Modal --}}
<div class="modal fade" id="updateProfileImageModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="updateProfileImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered center">
        <form action="{{ route('panel.admin.profile.update.profile-img', $user->id) }}" method="POST"
            enctype="multipart/form-data" onsubmit="return checkCoords();">
            @csrf

            <input type="hidden" name="request_with" value="profile_img">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateProfileImageModalLabel">Update Avatar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">


                    <div class="form-group mt-5">
                        <input class="text-color-white" type="file" name="avatar" id="avatar" accept="image/jpg,image/png,image/jpeg">
                    </div>
                    <small class="text-danger text-left">Allowed file: .jpg, .jpeg, .png</small>
                    <img id="imagePreview" class="d-none" src="#" alt="your image" />
                    <div class="demo"></div>
                    <input type="hidden" id="croppedImageData" name="croppedImageData">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

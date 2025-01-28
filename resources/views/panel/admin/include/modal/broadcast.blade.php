@php
    $roles = App\Models\Role::get();
@endphp
<div class="modal fade" id="addBrodcast" tabindex="-1" role="dialog" aria-labelledby="addBrodcast" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBrodcast"><i class="ik ik ik-radio" title="Broadcast"></i> Broadcast</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('panel.admin.broadcast.index') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <div class="alert alert-primary" role="alert">
                            <strong>Attention!</strong>
                            This broadcast cannot be rolled backed. Ensure content integrity before broadcasting
                            anything.
                        </div>
                    </div>
                    <div class="help-block with-errors"></div>
                    <div class="form-group">
                        <div class="form-radio">
                            @foreach ($roles as $role)
                                <div class="radio radio-inline">
                                    <label class="fw-700">
                                        <input required type="radio" class="role_name" name="role_name"
                                            value="{{ $role->display_name }}" required>
                                        <i class="helper"></i>Send to All {{ $role->display_name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="broadcast_section" style="display: none">
                            <div class="form-group" data-select2-id="23">
                                <label for="">Select Specific Users (Leave Blank For All)</label>
                                <select class="form-control select2" id="allUsers" name="admin_selected[]" multiple>
                                </select>
                            </div>
                        </div>
                        <div class="form-group broadcast_section" style="display: none">
                            <label for="brodcast"> @lang('admin/tooltip.Your Broadcast') </label>
                            <textarea required minlength="10" id="brodcast" type="text-area" class="form-control" name="brodcast"
                                placeholder="Enter Your Message Here..."></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer m-0">
                        <button type="submit" class="btn btn-primary btn-lg">Broadcast Now</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="card-body">
    <form class="row" action="{{ route('panel.admin.profile.update.password', $user->id) }}" method="POST">
        @csrf
        <input type="hidden" name="request_with" value="password">
        <div class="col-12">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="password">@lang('admin/ui.new_password') <span class="text-danger">*</span></label>
                    <input required type="password" class="form-control custom-placeholder" name="password" placeholder="@lang('admin/ui.new_password')"
                        id="password">
                </div>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="confirm-password">@lang('admin/ui.confirm_password') <span class="text-danger">*</span></label>
            <input required type="password" class="form-control custom-placeholder" name="confirm_password" placeholder="@lang('admin/ui.confirm_password')"
                id="confirm-password">
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary" type="submit">@lang('admin/ui.change')  {{ Str::limit($user->full_name, 20) }}
                @lang('admin/ui.password') </button>
        </div>
    </form>
</div>

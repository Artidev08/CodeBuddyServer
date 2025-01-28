<div class="form-group row">
    @if (env('IS_DEV') == 1)
        <div class="col-md-12">
            <div class="card troubleshoot bg-light">
                <div class="row">
                    <div class="col-6">
                        <h5 class="ml-4 mt-2"> @lang('admin/ui.storage_link') </h5>
                        <p class="ml-4"> @lang('admin/ui.storage_subheading')
                        </p>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('panel.admin.general.storage-link') }}"
                            class="btn btn-outline-dark mt-4"> @lang('admin/ui.storage_link')

                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="col-md-12">
        <div class="card troubleshoot bg-light">
            <div class="row">
                <div class="col-6">
                    <h5 class="ml-4 mt-2"> @lang('admin/ui.optimize_clear') </h5>
                    <p class="ml-4"> @lang('admin/ui.optimize_subheading')
                    </p>
                </div>
                <div class="col-6">
                    <a href="{{ route('panel.admin.general.optimize-clear') }}"
                        class="btn btn-outline-dark mt-4"> @lang('admin/ui.optimize_clear')

                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card troubleshoot bg-light">
            <div class="row">
                <div class="col-6">
                    <h5 class="ml-4 mt-2"> @lang('admin/ui.session_clear') </h5>
                    <p class="ml-4"> @lang('admin/ui.header_custom')
                    </p>
                </div>
                <div class="col-6">
                    <a href="{{ route('panel.admin.general.session-clear') }}"
                        class="btn btn-outline-dark mt-4"> @lang('admin/ui.session_clear') 
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

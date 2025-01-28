<div class="modal fade" id="BulkStoreAgentModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="updateProfileImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-center">
        <div class="modal-content" style="width: 170%;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Create/Update {{ @$label }}</h5>
                <form action="{{ route('panel.admin.sliders.export', [request()->get('sliderTypeId')]) }}"
                    method="POST" enctype="multipart/form-data" onsubmit="return checkCoords();">
                    @csrf
                    <div class="d-flex justify-content-between mt-0">
                        <div class="text-right">
                            <button type="submit"
                                href="{{ route('panel.admin.users.export', [request()->get('sliderTypeId')]) }}"
                                class="btn btn-link">Download
                                Prefill Excel
                            </button>
                        </div>
                    </div>
                </form>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="previous-month" role="tabpanel"
                    aria-labelledby="pills-setting-tab">
                    <div class="modal-body">

                        <form action="{{ route('panel.admin.bulk.sliders') }}" method="POST"
                            enctype="multipart/form-data" onsubmit="return checkCoords();">
                            @csrf
                            <div>
                                <div class="alert alert-warning" style="padding: 0.75rem 1rem;">
                                    <p class="mb-0">For updating an existing {{ @$label }}, please leave the
                                        'ID'
                                        column blank.</p>
                                </div>
                                <div class="alert alert-info" style="padding: 0.75rem 1rem;">
                                    <p class="mb-0">Title field is required.</p>
                                    <p class="mb-0">Type field is required.</p>
                                    <p class="mb-0">Content field is required.</p>
                                    <p class="mb-0">Image field is required.</p>
                                </div>
                            </div>

                            <div class="form-group mt-5">
                                <label for="agents_bulk_update" class="form-label">Update Records</label> <br>
                                <input type="file" name="file" class="form-control" id="agents_bulk_update"
                                accept=".xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                            </div>
                            <small class="text-danger text-left">Allowed file: .xlsx</small>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

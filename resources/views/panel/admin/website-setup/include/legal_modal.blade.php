<!-- Modal -->
@php
    $websitePages = App\Models\WebsitePage::where('status',1)
    ->select('id','title','status')
    ->get();
@endphp
<div class="modal fade" id="legalModal" tabindex="-1" role="dialog" aria-labelledby="legalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('admin/ui.generator') </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="POST"
                enctype="multipart/form-data" class="documentGenerateForm">
                @csrf
                <div class="modal-body">
                    <label class="control-label" for="legal">@lang('admin/ui.choose_document') </label>
                    <div class="row">
                        <div class="col-12">
                            <select name="legal" id="legalOnChange" class="form-control">
                                <option value="" readonly>Select Document</option>
                                @foreach ($websitePages as $websitePage)
                                    <option value="{{$websitePage->id}}">{{$websitePage->title}}</option>
                                @endforeach
                            </select>
                        </div>
                      <div class="col-lg-6 mt-2">
                            <label class="control-label"
                                for="name_of_company">@lang('admin/ui.name_of_company') </label>
                            <input type="text" pattern="[a-zA-Z]+.*"
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                id="name_of_company" name="name_of_company" placeholder="Name of Company"
                                class="form-control" value="" required>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label class="control-label" for="website_url">@lang('admin/ui.website_url') </label>
                            <input type="text"
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                id="website_url" name="website_url" placeholder="Website URL" class="form-control"
                                value="" pattern="^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$" required>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label class="control-label"
                                for="website_name">@lang('admin/ui.website_name') </label>
                            <input type="text"
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                id="website_name" name="website_name" placeholder="Website name"
                                class="form-control" value="" required>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label class="control-label"
                                for="entity_type">@lang('admin/ui.entity_type') </label>
                            <input type="text"
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                id="entity_type" name="entity_type" placeholder="Entity type" class="form-control"
                                value="" required>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label class="control-label" for="address">@lang('admin/ui.address') </label>
                            <input type="text"
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                id="address" name="address" placeholder="Address" class="form-control"
                                value="" required>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label class="control-label" for="phone">@lang('admin/ui.phone') </label>
                            <input type="tel" pattern="[0-9]+.*" title="Please enter number is required."
                                title="Please enter number is required." id="phone" name="phone"
                                placeholder="Phone" class="form-control" value="" required>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label class="control-label" for="email">@lang('admin/ui.email') </label>
                            <input type="email" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                id="email" name="email" placeholder="Email" class="form-control"
                                value="" required>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label class="control-label" for="country">@lang('admin/ui.country') </label>
                            <input type="text"
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                id="country" name="country" placeholder="Country" class="form-control"
                                value="" required>
                        </div>
                        <div class="col-lg-6 mt-2">
                            <label class="control-label" for="page_keywords">@lang('admin/ui.state') </label>
                            <input type="text"
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                id="state" name="state" placeholder="State" class="form-control"
                                value="" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-block btn-primary">@lang('admin/ui.generate') </button>
                </div>
            </form>
        </div>
    </div>
</div>

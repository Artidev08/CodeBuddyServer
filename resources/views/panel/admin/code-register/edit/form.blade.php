<form class="row ajaxForm" action="{{ route('panel.admin.code-register.update', $item->id) }}" method="post"
    enctype="multipart/form-data" id="ItemForm">
    @csrf
    <div class="col-md-12">
        <!-- start message area-->
        @include('panel.admin.include.message')
        <!-- end message area-->
    </div>
    <div class="col-md-7 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3>{{ $title }} Details</h3>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label for="title" class="control-label ">Title<span
                                    class="text-danger">*</span></label>
                            <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_scenario_title')"><i
                                    class="ik ik-help-circle text-muted ml-1"></i></a>
                            <input type="text" required class="form-control" name="title" id="title"
                                value="{{ $item->title }}" placeholder="Enter Title">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('base_path') ? 'has-error' : '' }}">
                            <label for="base_path" class="control-label ">Base Path<span
                                    class="text-danger">*</span></label>
                            <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_scenario_title')"><i
                                    class="ik ik-help-circle text-muted ml-1"></i></a>
                            <input type="text" required class="form-control" name="base_path" id="base_path"
                                value="{{ $item->base_path }}" placeholder="Enter Base Path">
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="agent_id">Agent <span class="text-danger">*</span></label>
                            <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_item_department')"><i
                                    class="ik ik-help-circle text-muted ml-1"></i></a>
                            <select required name="agent_id" id="agent_id" data-flag="0"
                                class="form-control select2 agent_id">
                                @foreach (App\Models\Agent::get() as $agent)
                                <option value="{{ $agent->id }}"
                                    @if($agent->id == $item->agent_id) selected @endif>{{ $agent->name .' - '.$agent->bio.' | '.$agent->getPrefix() }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_item_department')"><i
                                    class="ik ik-help-circle text-muted ml-1"></i></a>
                            <select required name="status" id="status" data-flag="0"
                                class="form-control select2 status">
                                @foreach (App\Models\CodeRegister::STATUS as $key => $status)
                                <option value="{{ $key }}"
                                    {{ $key == $item->status ? 'Selected' : '' }}>{{ $status['label'] ?? '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="workflow">Workflow <span class="text-danger">*</span></label>
                            <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_item_department')"><i
                                    class="ik ik-help-circle text-muted ml-1"></i></a>
                            <select required name="workflow" id="workflow" data-flag="0"
                                class="form-control select2 workflow">
                                @php
                                $workflows = ['SPLCMT','TRAVERS'];
                                @endphp
                                @foreach ($workflows as $workflow)
                                <option value="{{ $workflow }}"
                                    {{ $workflow == $item->workflow ? 'Selected' : '' }}>{{ $workflow }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary floating-btn ajax-btn">Save & Update</button>
</form>

<!-- push external js -->
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>
    <script src="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>

    {{-- START AJAX FORM INIT --}}

    <script>
        // STORE DATA USING AJAX
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var redirectUrl = "{{ url('admin/code-register/') }}";
            var response = postData(method, route, 'json', data, null, null, toast = 1, async = true, redirectUrl);
        })
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
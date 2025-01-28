<form class="row" action="{{ route('panel.admin.code-register.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="request_with" value="create">
    <div class="col-md-12">
        <!-- start message area-->
        @include('panel.admin.include.message')
        <!-- end message area-->
    </div>
    <div class="col-md-7  mx-auto">
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
                                col="10" rows="10" placeholder="Enter Title">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('base_path') ? 'has-error' : '' }}">
                            <label for="base_path" class="control-label ">Base Path<span
                                    class="text-danger">*</span></label>
                            <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_scenario_title')"><i
                                    class="ik ik-help-circle text-muted ml-1"></i></a>
                            <input type="text" required class="form-control" name="base_path" id="base_path"
                                col="10" rows="10" placeholder="Enter Base Path">
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
                                <option value="{{ $agent->id }}">{{ $agent->getPrefix().' - '.$agent->name .' - '.$agent->bio }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 d-none">
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_item_department')"><i
                                    class="ik ik-help-circle text-muted ml-1"></i></a>
                            <select required name="status" id="status" data-flag="0"
                                class="form-control select2 status">
                                @foreach (App\Models\CodeRegister::STATUS as $key => $status)
                                <option value="{{ $key }}" {{ $key == '0' ? 'Selected' : '' }}>
                                    {{ $status['label'] ?? '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="project_id">Project <span class="text-danger">*</span></label>
                            <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_item_department')"><i
                                    class="ik ik-help-circle text-muted ml-1"></i></a>
                            <select required name="project_id" id="project_id" data-flag="0"
                                class="form-control select2 project_id">
                                @foreach ($projects as $project)
                                    @php
                                        $handleTypeLabel = App\Models\Project::HANDLE_TYPES[$project->handle_type]['label'] ?? '-';
                                        $discoveryTypeLabel = App\Models\Project::DISCOVERY_TYPES[$project->discovery_type]['label'] ?? '-';
                                        $prefix = $project->getPrefix() ?? '-';
                                        $name = $project->name ?? '-';
                                    @endphp
                                    <option value="{{ $project->id }}">
                                        {{ $prefix . ' | ' . $name . ' | ' . $handleTypeLabel . ' | ' . $discoveryTypeLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button id="submit" type="submit" class="btn btn-primary floating-btn ajax-btn">Create</button>
</form>

<!-- push external js -->
@push('script')
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
    });
</script>
{{-- END AJAX FORM INIT --}}
@endpush
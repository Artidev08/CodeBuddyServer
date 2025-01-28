<div class="modal fade" id="exampleModalCenter" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterLabel"> @lang('admin/ui.add_note') </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('panel.admin.user-notes.store') }}" method="post">
                    @csrf

                    <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex=""
                        validation="" value="create" />
                    <x-input name="type" placeholder="" type="hidden" tooltip="" regex="" validation=""
                        value="User" />
                    <x-input name="type_id" placeholder="" type="hidden" tooltip="" regex="" validation=""
                        value="{{ @$user->id }}" />

                    <div class="row">
                        <div class="col-md-12 mx-auto">
                            <div class="form-group {{ @$errors->has('title') ? 'has-error' : '--' }}">
                                {{-- <label for="title" class="control-label"> @lang('admin/ui.title') @if (@validation('note_title')['pattern']['mandatory'])
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <input class="form-control" name="title" type="text"
                                    pattern="{{ regex('name')['pattern'] }}" title="{{ regex('name')['message'] }}"
                                    minlength="{{ @validation('common_name')['pattern']['minlength'] }}"
                                    maxlength="{{ @validation('common_name')['pattern']['maxlength'] }}"
                                    title="{{ @validation('common_name')['message'] }}" id="title"
                                    placeholder="{{ __('admin/ui.enter_title') }}"
                                    value="{{ isset($note->title) ? @$note->title : '' }}"
                                    {{ @validation('note_title')['pattern']['mandatory'] }}> --}}
                                <x-label name="title" validation="note_title" tooltip="" />
                                <x-input name="title" id="title"
                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.title') }}" type="text"
                                    tooltip="" regex="title" validation="note_title"
                                    value="{{ isset($note->title) ? @$note->title : '' }}" />
                                <x-message name="title" :message="@$message" />
                            </div>
                            <div class="form-group {{ @$errors->has('category_id') ? 'has-error' : '--' }}">
                                {{-- <label for="category_id" class="control-label"> @lang('admin/ui.category') </label>
                                <select class="form-control select2" tabindex="-1" name="category_id">
                                    <option value="" readonly>@lang('admin/ui.select_category') </option>
                                    @foreach (@$categories as $category)
                                        <option value="{{ @$category->id }}">{{ @$category->name ?? '' }}</option>
                                    @endforeach
                                </select> --}}
                                {{-- @dd($categories); --}}

                                <x-label name="category" validation="category_select" tooltip="" />
                                <x-select name="category_id" value="" label="Category" optionName="name"
                                    class="select2" :arr="@$categories" validation="category_select" id="category_id"
                                    valueName="id" />
                            </div>
                            <div class="form-group {{ @$errors->has('description') ? 'has-error' : '--' }}">
                                {{-- <label for="description" class="control-label"> @lang('admin/ui.description') @if (@validation('note_description')['pattern']['mandatory'])
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>

                                <textarea {{ @validation('note_description')['pattern']['mandatory'] }} class="form-control"
                                    pattern="{{ regex('short_description')['pattern'] }}" title="{{ regex('short_description')['message'] }}"
                                    minlength="{{ @validation('common_short_description')['pattern']['minlength'] }}"
                                    maxlength="{{ @validation('common_short_description')['pattern']['maxlength'] }}"
                                    title="{{ @validation('common_short_description')['message'] }}" rows="5" name="description" type="textarea"
                                    id="description" placeholder="{{ __('admin/ui.enter_description') }}">{{ isset($note->description) ? @$note->description : '' }}</textarea> --}}
                                <x-label name="description" validation="note_description" tooltip="" />
                                <x-textarea regex="short_description" validation="common_short_description"
                                    value="{{ old('short_description') }}" name="description" id="description"
                                    placeholder="{{ __('admin/ui.enter') . __('admin/ui.description') }}"
                                    rows="5" />
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary"> @lang('admin/ui.create')</button>

                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModalCenter" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterLabel"> @lang('admin/ui.editNote') </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="editNoteForm" method="post">
                    @csrf

                    <x-input name="type" placeholder="" type="hidden" tooltip="" regex="" validation=""
                        value="{{ App\Models\User::class }}" />
                    <x-input name="type_id" id="note-type_id" placeholder="" type="hidden" tooltip=""
                        regex="" validation="" value="" />

                    <div class="row">
                        <div class="col-md-12 mx-auto">
                            <div class="form-group {{ @$errors->has('title') ? 'has-error' : '' }}">
                               

                                <x-label name="title" validation="common_name" tooltip="" />
                                <x-input name="title" id="note-title"
                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.title') }}" type="text"
                                    tooltip="" regex="name" validation="common_name"
                                    value="{{ isset($note->title) ? @$note->title : '' }}" />
                                <x-message name="title" :message="@$message" />
                            </div>
                            <div class="form-group {{ @$errors->has('category_id') ? 'has-error' : '' }}">
                                
                                <x-label name="category" validation="category_select" tooltip="" />
                                <x-select name="category_id" value="" label="Category" optionName="name"
                                    class="" :arr="@$categories" validation="category_select" id="category_id_edit"
                                    valueName="id" />
                            </div>
                            <div class="form-group {{ @$errors->has('description') ? 'has-error' : '' }}">
                                <x-label name="description" validation="note_description" tooltip="" />
                                <x-textarea regex="short_description" validation="common_short_description"
                                    value="" name="description" id="note-description"
                                    placeholder="{{ __('admin/ui.enter') . __('admin/ui.description') }}"
                                    rows="5" />
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary"> @lang('admin/ui.update') </button>
                                
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

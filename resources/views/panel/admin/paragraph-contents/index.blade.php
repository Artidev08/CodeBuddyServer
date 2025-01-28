@extends('layouts.main')
@section('title', @$label)
@section('content')
    @php
        $breadcrumb_arr = [['name' => $label, 'url' => 'javascript:void(0);', 'class' => 'active']];
    @endphp
    @push('head')
 {{-- INITIALIZE SHIMMER & INIT LOAD --}}
 <script>
    window.onload = function() {
        $('#ajax-container').show();
        document.getElementById('reset').click();
    };
</script>
{{-- END INITIALIZE SHIMMER & INIT LOAD --}}
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ @$label }}</h5>
                            <span> @lang('admin/ui.list_of') {{ @$label ?? '--' }} </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>{{ $label }}</h3>
                        <div class="d-flex">
                            @if ($permissions->contains('add_paragraph_content'))
                                <a href="{{ route('panel.admin.paragraph-contents.create') }}"
                                    class="btn btn-sm btn-outline-primary mr-2" title="Add New Site Content Management"><i
                                        class="fa fa-plus" aria-hidden="true"></i> @lang('admin/ui.add') </a>
                            @endif
                            @if (getSetting('paragraph_content_bulk_delete', @$setting) || getSetting('paragraph_content_bulk_upload', @$setting))
                                <form action="{{ route('panel.admin.paragraph-contents.bulk-delete') }}" method="POST"
                                    id="bulkAction">
                                    @csrf
                                    <input type="hidden" name="ids" id="bulk_ids">
                                    <button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light" type="button"
                                        id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false"><i class="ik ik-more-vertical pl-1"></i></button>
                                    <ul class="dropdown-menu multi-level support-dropdown"
                                        style="margin-top: 10px; margin-left: 31px;" role="menu"
                                        aria-labelledby="dropdownMenu">
                                        @if (getSetting('paragraph_content_bulk_upload', @$setting))
                                            <a href="javascript:void(0);" class="dropdown-item text-primary fw-700"
                                                data-toggle="modal" data-target="#BulkStoreAgentModal"><i
                                                    class="ik ik-upload"></i>
                                                Bulk Upload</a>
                                        @endif
                                        @if (getSetting('paragraph_content_bulk_delete', @$setting))
                                            <hr class="m-1">
                                            <button type="submit" class="dropdown-item bulk-action text-danger fw-700"
                                                data-value="" data-message="You want to delete these Paragraph Content?"
                                                data-action="delete" data-callback="bulkDeleteCallback"><i
                                                    class="ik ik-trash"> </i> Bulk
                                                Delete
                                            </button>
                                        @endif
                                    </ul>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div id="ajax-container" style="display: none;">
                        @include('panel.admin.paragraph-contents.load')
                    </div>
                </div>
            </div>
            @include('panel.admin.modal.sitemodal', [
                'title' => 'How to use',
                'content' =>
                    'You need to create a unique code and call the unique code with paragraph content helper.',
            ])
        </div>
    </div>
    @if (getSetting('paragraph_content_table_filter', @$setting))
        @include('panel.admin.paragraph-contents.include.filter')
    @endif
    @if (getSetting('paragraph_content_bulk_upload', @$setting))
        @include('panel.admin.paragraph-contents.include.bulk-upload')
    @endif
@endsection

@push('script')
    @include('panel.admin.include.bulk-script')

    {{-- START HTML TO EXCEL INIT --}}
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script src="{{ asset('panel/admin/plugins/ckeditor5/ckeditor.js') }}"></script>
    <script>
        function tableHeadIconFixer(clonedTable) {
            clonedTable.find('i.icon-head').each(function() {
                var dataTitle = $(this).data('title');
                $(this).replaceWith(dataTitle);
            });
            return clonedTable;
        }

        function html_table_to_excel(type) {
            var table_core = $("#siteTable").clone();
            var clonedTable = $("#siteTable").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            clonedTable = tableHeadIconFixer(clonedTable);
            $("#siteTable").html(clonedTable.html());
            var data = document.getElementById('siteTable');

            var file = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(file, 'leadFile.' + type);
            $("#siteTable").html(table_core.html());
        }

        $(document).on('click', '#export_button', function() {
            html_table_to_excel('xlsx');
        });

        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.paragraph-contents.index') }}");
            window.history.pushState("", "", "{{ route('panel.admin.paragraph-contents.index') }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
        });
    </script>
    {{-- END HTML TO EXCEL INIT --}}

    {{-- START CKEDITOR INIT --}}
    <script>
        var editor;
        $(ajaxContainer).on('click', '.edit-content', function(e) {
            let rec = $(this).data('rec');
            $('#Id').val(rec.id);
            $('.content').html('');
            if (rec.type == 1) {
                $('.content').html(
                    ` <textarea name="value" id="" class="form-control" cols="30" rows="10">${rec.value}</textarea>`
                );
            } else if (rec.type == 2) {
                $('.content').html(`  <div id="content-holder">
                                        <div id="toolbar-container"></div>
                                        <div id="txt_area">
                                            ${rec.value}
                                        </div>
                                    </div>`);
                $(document).find('#txt_area').addClass('ck-editor');
                DecoupledEditor
                    .create(document.querySelector('.ck-editor'), {
                        ckfinder: {
                            uploadUrl: "{{ route('panel.admin.media.ckeditor.upload') . '?_token=' . csrf_token() }}",
                        }
                    })
                    .then(newEditor => {
                        editor = newEditor;
                        const toolbarContainer = document.querySelector('#toolbar-container');

                        toolbarContainer.appendChild(editor.ui.view.toolbar.element);
                    })
                    .catch(error => {
                        console.error(error);
                    });
            } else {
                $('.content').html(
                    `<input type="text"  pattern="[a-zA-Z]+.*" title="Please enter first letter alphabet and at least one alphabet character is required." name="value" class="form-control" value="${rec.value}" id="">`
                );
            }
            $('#updateParagraphModal').modal('show');
        });
        $('#update-ajax-form').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            if (data.get('value') == undefined || data.get('value') == '') {
                const value = editor.getData();
                data.append('value', value);
            }
            postData(method, route, 'json', data, null, null);
            $('#updateParagraphModal').modal('hide');
            fetchData("{{ route('panel.admin.paragraph-contents.index') }}");
        });
    </script>
    {{-- END CKEDITOR INIT --}}
@endpush

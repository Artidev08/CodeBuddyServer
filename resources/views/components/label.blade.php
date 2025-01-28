<label class="{{ $class }}" for="{{ $name }}">@lang('admin/ui.' . $name) @if (isset($validation['pattern']['mandatory']) && $validation['pattern']['mandatory'] == 'required')<span class="text-danger">*</span> @endif @if (@isset($tooltip) && $tooltip != null && $tooltip != '')<span data-toggle="tooltip" title=" @lang('admin/tooltip.' . $tooltip) "><i class="ik ik-help-circle text-muted ml-1"></i></span>
@endif
</label>

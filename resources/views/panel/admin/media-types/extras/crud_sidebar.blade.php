@if($master_permissions->contains('view_media_types'))
    <div class="nav-item {{ ($segment2 == 'media-types') ? 'active' : '' }}">
        <a href="{{ route('admin.media-types.index') }}" class="a-item">
            <i class="ik ik-grid"></i><span>Media Type</span>
        </a>
    </div>
@endif

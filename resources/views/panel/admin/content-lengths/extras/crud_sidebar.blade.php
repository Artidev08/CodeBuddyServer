@if($master_permissions->contains('view_content_lengths'))
    <div class="nav-item {{ ($segment2 == 'content-lengths') ? 'active' : '' }}">
        <a href="{{ route('admin.content-lengths.index') }}" class="a-item">
            <i class="ik ik-grid"></i><span>Content Length</span>
        </a>
    </div>
@endif
@if($master_permissions->contains('view_content_lengths'))
    <div class="nav-item {{ ($segment2 == 'content-lengths') ? 'active' : '' }}">
        <a href="{{ route('admin.content-lengths.index') }}" class="a-item">
            <i class="ik ik-grid"></i><span>Content Length</span>
        </a>
    </div>
@endif

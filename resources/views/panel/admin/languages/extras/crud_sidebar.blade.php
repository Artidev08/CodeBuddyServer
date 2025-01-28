@if($master_permissions->contains('view_languages'))
    <div class="nav-item {{ ($segment2 == 'languages') ? 'active' : '' }}">
        <a href="{{ route('admin.languages.index') }}" class="a-item">
            <i class="ik ik-grid"></i><span>Language</span>
        </a>
    </div>
@endif

@if($master_permissions->contains('view_occasions'))
    <div class="nav-item {{ ($segment2 == 'occasions') ? 'active' : '' }}">
        <a href="{{ route('admin.occasions.index') }}" class="a-item">
            <i class="ik ik-grid"></i><span>occasion</span>
        </a>
    </div>
@endif

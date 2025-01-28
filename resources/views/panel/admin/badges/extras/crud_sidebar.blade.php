@if($master_permissions->contains('view_badges'))
    <div class="nav-item {{ ($segment2 == 'badges') ? 'active' : '' }}">
        <a href="{{ route('admin.badges.index') }}" class="a-item">
            <i class="ik ik-grid"></i><span>Badge</span>
        </a>
    </div>
@endif

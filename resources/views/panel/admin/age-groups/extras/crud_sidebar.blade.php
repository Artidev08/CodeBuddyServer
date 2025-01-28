@if($master_permissions->contains('view_age_groups'))
    <div class="nav-item {{ ($segment2 == 'age-groups') ? 'active' : '' }}">
        <a href="{{ route('admin.age-groups.index') }}" class="a-item">
            <i class="ik ik-grid"></i><span>Age Group</span>
        </a>
    </div>
@endif

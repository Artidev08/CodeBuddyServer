@if($master_permissions->contains('view_gender_specificities'))
    <div class="nav-item {{ ($segment2 == 'gender-specificities') ? 'active' : '' }}">
        <a href="{{ route('admin.gender-specificities.index') }}" class="a-item">
            <i class="ik ik-grid"></i><span>Gender Specificity</span>
        </a>
    </div>
@endif

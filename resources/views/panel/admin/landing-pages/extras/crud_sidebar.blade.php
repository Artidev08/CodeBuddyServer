@if($master_permissions->contains('view_landing_pages'))
    <div class="nav-item {{ ($segment2 == 'landing-pages') ? 'active' : '' }}">
        <a href="{{ route('admin.landing-pages.index') }}" class="a-item">
            <i class="ik ik-grid"></i><span>Landing Page</span>
        </a>
    </div>
@endif

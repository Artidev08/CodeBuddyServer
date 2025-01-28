@if($master_permissions->contains('view_events'))
    <div class="nav-item {{ ($segment2 == 'events') ? 'active' : '' }}">
        <a href="{{ route('admin.events.index') }}" class="a-item">
            <i class="ik ik-grid"></i><span>Event</span>
        </a>
    </div>
@endif

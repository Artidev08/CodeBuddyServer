@if($master_permissions->contains('view_sentiments'))
    <div class="nav-item {{ ($segment2 == 'sentiments') ? 'active' : '' }}">
        <a href="{{ route('admin.sentiments.index') }}" class="a-item">
            <i class="ik ik-grid"></i><span>Sentiment</span>
        </a>
    </div>
@endif

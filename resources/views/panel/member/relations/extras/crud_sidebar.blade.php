@if($master_permissions->contains('view_relations'))
    <div class="nav-item {{ ($segment2 == 'relations') ? 'active' : '' }}">
        <a href="{{ route('admin.relations.index') }}" class="a-item">
            <i class="ik ik-grid"></i><span>Relation</span>
        </a>
    </div>
@endif

@php
    $roles = App\Models\Role::pluck('display_name');
    $segment1 = request()->segment(1);
    $segment2 = request()->segment(2);
    $segment3 = request()->segment(3);
    $segment4 = request()->segment(4);
    $segment5 = request()->segment(5);
    $segment6 = request()->segment(6);
    $authUser = auth()->user();
@endphp
<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{ route('panel.member.dashboard.index') }}">
            <div class="logo-img">
                <img height="35px" src="{{ getBackendLogo(getSetting('app_logo', @$setting)) }}" class="header-brand-img"
                    title="App Logo">
            </div>
        </a>
        <div class="sidebar-action"><i class="ik ik-chevron-left"></i></div>
        <button id="sidebarClose" class="nav-close"></button>
    </div>
    <div class="sidebar-content">
        <div class="nav-container">
            <div class="px-20px mt-3 mb-3">
                <input class="form-control bg-soft-secondary border-0 form-control-sm"
                    style="background-color: #131923;border-color: #131923; color:white" type="text" name=""
                    placeholder=" @lang('admin/ui.left_sidebar_search_in_menu') " id="menu-search" oninput="menuSearch()">
            </div>
            <nav id="search-menu-navigation" class="navigation-main">
            </nav>
            <nav id="main-menu-navigation" class="navigation-main">
                <div class="nav-item {{ $segment2 == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('panel.member.dashboard.index') }}" class="a-item"><i
                            class="ik ik-bar-chart-2"></i><span> @lang('admin/ui.left_sidebar_dashboard') </span></a>
                </div>
                {{-- <div class="nav-item {{ activeClassIfRoutes(['panel.member.users.index', 'panel.member.users.create', 'panel.member.users.edit'], 'active open') }}">
                    <a href="{{ route('panel.member.users.index') }}?role=Member" class="a-item"><i class="ik ik-package"></i><span>{{ __('Agents') }}</span></a>
                </div> --}}
                @if (
                    !is_null($authUser->permissions) &&

                        isset($authUser->permissions['permissions']) &&
                        isUserHasPermission($authUser->permissions['permissions'], 'view_registers'))
                    <div
                        class="nav-item {{ activeClassIfRoutes(['panel.member.agent-content-registers.index', 'panel.member.agent-content-registers.create', 'panel.member.agent-content-registers.edit'], 'active open') }}">
                        <a href="{{ route('panel.member.agent-content-registers.index', ['agent_id' => secureToken(auth()->id()),'role'=>'Member']) }}"
                            class="a-item"><i class="ik ik-package"></i><span>{{ __('Registers') }}</span></a>
                    </div>
                @endif

                @if ($permissions->contains('access_by_admin'))
                    @if (getSetting('order_activation', @$setting) ||
                            getSetting('subscribers_activation', @$setting) ||
                            getSetting('payout_activation', @$setting))
                        <div
                            class="nav-item {{ activeClassIfRoutes(['panel.member.orders.index', 'panel.member.orders.show', 'panel.member.orders.invoice', 'panel.member.user-subscriptions.index', 'panel.member.user-subscriptions.create', 'panel.member.user-subscriptions.edit', 'panel.member.payouts.index', 'panel.member.payouts.show', 'panel.member.orders.invoice', 'panel.member.orders.create'], 'active open') }} has-sub">
                            @if ($permissions->contains('view_orders') || $permissions->contains('view_payouts'))
                                <a href="#"><i class="ik ik-shopping-bag"></i><span> @lang('admin/ui.left_sidebar_sales_payment')
                                    </span></a>
                                <div class="submenu-content">
                                    @if (getSetting('order_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_orders'))
                                            <a href="{{ route('panel.member.orders.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.member.orders.index', 'panel.member.payouts.edit'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_manage_orders') </a>
                                        @endif
                                    @endif

                                    @if (getSetting('subscribers_activation', @$setting) == 1)
                                        <a href="{{ route('panel.member.user-subscriptions.index') }}"
                                            class="menu-item a-item {{ activeClassIfRoutes(['panel.member.user-subscriptions.index', 'panel.member.user-subscriptions.edit'], 'active') }}">@lang('admin/ui.left_sidebar_subscribers')
                                        </a>
                                    @endif
                                    @if (getSetting('payout_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_payouts'))
                                            <a href="{{ route('panel.member.payouts.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.member.payouts.index', 'panel.member.payouts.edit'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_control_payouts') </a>
                                        @endif
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                    @if ($permissions->contains('view_items'))
                        @if (getSetting('item_activation', @$setting) || getSetting('subscription_plans_activation', @$setting))

                            <div
                                class="nav-item {{ activeClassIfRoutes(['panel.member.items.index', 'panel.member.items.create', 'panel.member.items.edit', 'panel.member.subscriptions.index', 'panel.member.subscriptions.create', 'panel.member.subscriptions.edit'], 'active open') }} has-sub">
                                <a href="#"><i class="ik ik-package"></i><span> @lang('admin/ui.left_sidebar_control_products') </span></a>
                                <div class="submenu-content">
                                    @if (getSetting('item_activation', @$setting) == 1)
                                        <a href="{{ route('panel.member.items.index') }}"
                                            class="menu-item a-item {{ activeClassIfRoutes(['panel.member.items.index', 'panel.member.items.edit'], 'active') }}">
                                            @lang('admin/ui.left_sidebar_manage_items') </a>
                                    @endif
                                    @if (getSetting('subscription_plans_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_subscription_plans'))
                                            <a href="{{ route('panel.member.subscriptions.index') }}"
                                                class="menu-item {{ activeClassIfRoutes(['panel.member.subscriptions.index', 'panel.member.subscriptions.create', 'panel.member.subscriptions.edit'], 'active') }}">@lang('admin/ui.subscriptions')</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                    {{-- Reports module --}}
                    @if ($permissions->contains('view_reports'))
                        @if (getSetting('purchase_activation', @$setting) || getSetting('registration_activation', @$setting))
                            <div
                                class="nav-item {{ activeClassIfRoutes(['panel.member.reports.purchase', 'panel.member.reports.registration'], 'active open') }} has-sub">
                                <a href="#"><i class="ik ik-pie-chart"></i><span> @lang('admin/ui.left_sidebar_reports') </span></a>
                                <div class="submenu-content">
                                    @if (getSetting('purchase_activation', @$setting) == 1)
                                        <a href="{{ route('panel.member.reports.purchase') }}"
                                            class="menu-item a-item {{ activeClassIfRoutes(['panel.member.reports.purchase'], 'active') }}">
                                            @lang('admin/ui.left_sidebar_purchase_flow') </a>
                                    @endif
                                    @if (getSetting('registration_activation', @$setting) == 1)
                                        <a href="{{ route('panel.member.reports.registration') }}"
                                            class="menu-item a-item {{ activeClassIfRoutes(['panel.member.reports.registration'], 'active') }}">
                                            @lang('admin/ui.left_sidebar_registration_flow') </a>
                                    @endif

                                </div>
                            </div>
                        @endif
                    @endif

                    @if ($permissions->contains('manage_administrator'))
                        @if (getSetting('user_management_activation', @$setting) || getSetting('roles_and_permission_activation', @$setting))
                            <div
                                class="nav-item {{ activeClassIfRoutes(['panel.member.users.index', 'panel.member.users.show', 'panel.member.users.create', 'panel.member.user_log.index', 'panel.member.roles.index', 'panel.member.permissions.index', 'panel.member.roles.edit', 'panel.member.users.edit'], 'active open') }} has-sub">
                                <a href="#"><i class="ik ik-users"></i><span> @lang('admin/ui.left_sidebar_administrator') </span></a>
                                <div class="submenu-content">
                                    @foreach ($roles as $role)
                                        @if (getSetting('user_management_activation', @$setting) == 1)
                                            <a href="{{ route('panel.member.users.index') }}?role={{ $role }}"
                                                class="menu-item a-item @if (request()->has('role') && request()->get('role') == $role) active @endif">{{ $role }}
                                                @lang('admin/ui.left_sidebar_management')</a>
                                        @endif
                                    @endforeach
                                    @if (getSetting('roles_and_permission_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_roles'))
                                            <a href="{{ route('panel.member.roles.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoute('panel.member.roles.index', 'active') }}">
                                                @lang('admin/ui.left_sidebar_roles') </a>
                                        @endif
                                        @if ($permissions->contains('view_permissions'))
                                            <a href="{{ route('panel.member.permissions.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoute('panel.member.permissions.index', 'active') }}">
                                                @lang('admin/ui.left_sidebar_permissions') </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                    @if ($permissions->contains('manage_resources'))
                        @if (getSetting('website_enquiry_activation', @$setting) ||
                                getSetting('ticket_activation', @$setting) ||
                                getSetting('newsletter_activation', @$setting) ||
                                getSetting('lead_activation', @$setting))
                            <div
                                class="nav-item {{ activeClassIfRoutes(['panel.member.leads.index', 'panel.member.leads.create', 'panel.member.leads.edit', 'panel.member.leads.show', 'panel.member.website-enquiries.index', 'panel.member.website-enquiries.create', 'panel.member.website-enquiries.edit', 'panel.member.support-tickets.index', 'panel.member.support-tickets.show', 'panel.member.support-tickets.show', 'panel.member.news-letters.index', 'panel.member.news-letters.create', 'panel.member.news-letters.edit'], 'active open') }} has-sub">
                                <a href="#"><i class="ik ik-mail"></i><span> @lang('admin/ui.left_sidebar_contacts_enquiry') </span></a>
                                <div class="submenu-content">
                                    @if (getSetting('website_enquiry_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_enquiries'))
                                            <a href="{{ route('panel.member.website-enquiries.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.member.website-enquiries.index', 'panel.member.website-enquiries.create', 'panel.member.website-enquiries.edit'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_website_enquiry') </a>
                                        @endif
                                    @endif
                                    @if (getSetting('ticket_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_tickets'))
                                            <a href="{{ route('panel.member.support-tickets.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.member.support-tickets.index', 'panel.member.support-tickets.show'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_support_tickets') </a>
                                        @endif
                                    @endif
                                    @if (getSetting('newsletter_activation', @$setting) == 1)
                                        {{-- @if ($permissions->contains('view_newletters')) --}}
                                        <a href="{{ route('panel.member.news-letters.index') }}"
                                            class="menu-item a-item {{ activeClassIfRoutes(['panel.member.news-letters.index', 'panel.member.news-letters.create', 'panel.member.news-letters.edit'], 'active') }}">
                                            @lang('admin/ui.left_sidebar_news_letter') </a>
                                        {{-- @endif --}}
                                    @endif
                                    @if (getSetting('lead_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_leads'))
                                            <a href="{{ route('panel.member.leads.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.member.leads.index', 'panel.member.leads.create', 'panel.member.leads.edit', 'panel.member.leads.show'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_leads') </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif

                    

                    @if ($permissions->contains('manage_constant_management') && !is_null($authUser->permissions) && isset($authUser->permissions['permissions'])&& (
                        isUserHasPermission($authUser->permissions['permissions'] , 'view_occasions') ||
                        isUserHasPermission($authUser->permissions['permissions'] , 'view_blogs') ||
                        isUserHasPermission($authUser->permissions['permissions'] , 'view_content_categories') ||
                        isUserHasPermission($authUser->permissions['permissions'] , 'view_age_groups') ||
                        isUserHasPermission($authUser->permissions['permissions'] , 'view_badges') ||
                        isUserHasPermission($authUser->permissions['permissions'] , 'view_relations') ||
                        isUserHasPermission($authUser->permissions['permissions'] , 'view_gender_specificities') ||
                        isUserHasPermission($authUser->permissions['permissions'] , 'view_languages') ||
                        isUserHasPermission($authUser->permissions['permissions'] , 'view_media_types') ||
                        isUserHasPermission($authUser->permissions['permissions'] , 'view_sentiments')
                    ))
                    
                        @if (getSetting('article_activation', @$setting) ||
                                getSetting('mail_sms_activation', @$setting) ||
                                getSetting('category_management_activation', @$setting) ||
                                getSetting('slider_activation', @$setting) ||
                                getSetting('paragraph_content_activation', @$setting) ||
                                getSetting('faq_activation', @$setting) ||
                                getSetting('location_activation', @$setting) ||
                                getSetting('seo_tags_activation', @$setting) ||
                                getSetting('resources_activation', @$setting) ||
                                getSetting('pages_activation', @$setting))
                            <div
                                class="nav-item {{ activeClassIfRoutes(
                                    [
                                        'panel.member.resources.documentation',
                                        'panel.member.templates.index',
                                        'panel.member.website-pages.index',
                                        'panel.member.faqs.index',
                                        'panel.member.faqs.create',
                                        'panel.member.faqs.edit',
                                        'panel.member.events.index',
                                        'panel.member.templates.create',
                                        'panel.member.templates.edit',
                                        'panel.member.templates.show',
                                        'panel.member.category-types.index',
                                        'panel.member.category-types.create',
                                        'panel.member.category-types.edit',
                                        'panel.member.categories.index',
                                        'panel.member.categories.create',
                                        'panel.member.categories.edit',
                                        'panel.member.paragraph-contents.index',
                                        'panel.member.paragraph-contents.create',
                                        'panel.member.paragraph-contents.edit',
                                        'panel.member.slider-types.index',
                                        'panel.member.slider-types.create',
                                        'panel.member.slider-types.edit',
                                        'panel.member.blogs.index',
                                        'panel.member.blogs.create',
                                        'panel.member.blogs.edit',
                                        'panel.member.blogs.show',
                                        'panel.member.sliders.edit',
                                        'panel.member.sliders.index',
                                        'panel.member.sliders.create',
                                        'panel.member.locations.country',
                                        'panel.member.locations.country.create',
                                        'panel.member.locations.state',
                                        'panel.member.locations.state.create',
                                        'panel.member.locations.city',
                                        'panel.member.locations.city.create',
                                        'panel.member.seo-tags.index',
                                        'panel.member.website-pages.appearance',
                                        'panel.member.website-pages.create',
                                        'admin.promo-codes.index',
                                        'panel.member.content-categories.index',
                                        'panel.member.content-categories.create',
                                        'panel.member.content-categories.edit',
                                        'panel.member.content-categories.show',
                                        'panel.member.content-lengths.index',
                                        'panel.member.content-lengths.create',
                                        'panel.member.content-lengths.edit',
                                        'panel.member.content-lengths.show',
                                        'panel.member.age-groups.index',
                                        'panel.member.age-groups.create',
                                        'panel.member.age-groups.edit',
                                        'panel.member.age-groups.show',
                                        'panel.member.badges.index',
                                        'panel.member.badges.create',
                                        'panel.member.badges.edit',
                                        'panel.member.badges.show',
                                        'panel.member.gender-specificities.index',
                                        'panel.member.gender-specificities.edit',
                                        'panel.member.gender-specificities.create',
                                        'panel.member.gender-specificities.show',
                                        'panel.member.landing-pages.index',
                                        'panel.member.languages.edit',
                                        'panel.member.languages.show',
                                        'panel.member.media-types.index',
                                        'panel.member.media-types.create',
                                        'panel.member.media-types.edit',
                                        'panel.member.media-types.show',
                                        'panel.member.occasions.create',
                                        'panel.member.occasions.index',
                                        'panel.member.occasions.edit',
                                        'panel.member.occasions.show',
                                        'panel.member.relations.index',
                                        'panel.member.relations.create',
                                        'panel.member.relations.edit',
                                        'panel.member.relations.show',
                                        'panel.member.sentiments.index',
                                        'panel.member.sentiments.create',
                                        'panel.member.sentiments.edit',
                                        'panel.member.sentiments.show',
                                        'panel.member.languages.index',
                                    ],
                                    'active open',
                                ) }} has-sub">
                                <a href="#"><i class="ik ik-hard-drive"></i><span> @lang('admin/ui.left_sidebar_content_management')
                                    </span></a>
                                <div class="submenu-content">
                                    @if (getSetting('article_activation', @$setting) == 1)
                                        
                                            <a href="{{ route('panel.member.blogs.index') }}"
                                                class="menu-item {{ activeClassIfRoutes(['panel.member.blogs.index', 'panel.member.blogs.create', 'panel.member.blogs.edit', 'panel.member.blogs.show'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_blogs') </a>
                                        
                                    @endif
                                    
                                    @if (
                                        !is_null($authUser->permissions) &&

                                            isset($authUser->permissions['permissions']) &&
                                            isUserHasPermission($authUser->permissions['permissions'], 'view_content_categories'))
                                        @if (getSetting('category_management_activation', @$setting) == 1)
                                            
                                                <a href="{{ route('panel.member.content-categories.index') }}"
                                                    class="menu-item a-item {{ activeClassIfRoutes(['panel.member.content-categories.index', 'panel.member.content-categories.create', 'panel.member.content-categories.edit', 'panel.member.content-categories.show'], 'active') }}">
                                                    @lang('admin/ui.left_sidebar_content_category') </a>
                                            
                                        @endif
                                    @endif

                                    @if (
                                        !is_null($authUser->permissions) &&

                                            isset($authUser->permissions['permissions']) &&
                                            isUserHasPermission($authUser->permissions['permissions'], 'view_occasions'))
                                        @if (getSetting('pages_activation', @$setting) == 1)
                                            <a href="{{ route('panel.member.occasions.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.member.occasions.index', 'panel.member.occasions.create', 'panel.member.occasions.edit', 'panel.member.occasions.show'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_occasion')</a>
                                        @endif
                                    @endif
                                    @if (
                                        !is_null($authUser->permissions) &&

                                            isset($authUser->permissions['permissions']) &&
                                            isUserHasPermission($authUser->permissions['permissions'], 'view_events'))
                                        @if (getSetting('faq_activation', @$setting) == 1)
                                        
                                                <a href="{{ route('panel.member.events.index') }}"
                                                    class="menu-item {{ activeClassIfRoutes(['panel.member.events.index', 'panel.member.events.create', 'panel.member.events.edit', 'panel.member.events.show'], 'active') }}">
                                                    @lang('admin/ui.left_sidebar_event') </a>
                                            
                                        @endif
                                    @endif
                                    @if (
                                        !is_null($authUser->permissions) &&
                                            isset($authUser->permissions['permissions']) &&
                                            isUserHasPermission($authUser->permissions['permissions'], 'view_age_groups'))
                                        
                                            <a href="{{ route('panel.member.age-groups.index') }}"
                                                class="menu-item {{ activeClassIfRoutes(['panel.member.age-groups.index', 'panel.member.age-groups.create', 'panel.member.age-groups.edit', 'panel.member.age-groups.show'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_age-group') </a>
                                        
                                    @endif
                                    @if (
                                        !is_null($authUser->permissions) &&

                                            isset($authUser->permissions['permissions']) &&
                                            isUserHasPermission($authUser->permissions['permissions'], 'view_badges'))

                                        
                                            <a href="{{ route('panel.member.badges.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.member.badges.index', 'panel.member.badges.create', 'panel.member.badges.edit', 'panel.member.badges.show'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_badge') </a>
                                        
                                    @endif

                                    @if (
                                        !is_null($authUser->permissions) &&

                                            isset($authUser->permissions['permissions']) &&
                                            isUserHasPermission($authUser->permissions['permissions'], 'view_relations'))
                                       
                                            <a href="{{ route('panel.member.relations.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.member.relations.index', 'panel.member.relations.create', 'panel.member.relations.edit', 'panel.member.relations.show'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_relation') </a>
                                        
                                    @endif

                                    @if (
                                        !is_null($authUser->permissions) &&

                                            isset($authUser->permissions['permissions']) &&
                                            isUserHasPermission($authUser->permissions['permissions'], 'view_gender_specificities'))
                                        
                                        <a href="{{ route('panel.member.gender-specificities.index') }}"
                                            class="menu-item {{ activeClassIfRoutes(['panel.member.gender-specificities.index', 'panel.member.gender-specificities.create', 'panel.member.gender-specificities.edit', 'panel.member.gender-specificities.show'], 'active') }}">
                                            @lang('admin/ui.left_sidebar_gender-specificity') </a>
                                       
                                    @endif

                                    @if (
                                        !is_null($authUser->permissions) &&

                                            isset($authUser->permissions['permissions']) &&
                                            isUserHasPermission($authUser->permissions['permissions'], 'view_languages'))
                                        @if (getSetting('pages_activation', @$setting) == 1)
                                            
                                                <a href="{{ route('panel.member.languages.index') }}"
                                                    class="menu-item a-item {{ activeClassIfRoutes(['panel.member.languages.index', 'panel.member.languages.create', 'panel.member.languages.edit', 'panel.member.languages.show'], 'active') }}">
                                                    @lang('admin/ui.left_sidebar_language')</a>
                                            
                                        @endif
                                    @endif

                                    @if (
                                        !is_null($authUser->permissions) &&

                                            isset($authUser->permissions['permissions']) &&
                                            isUserHasPermission($authUser->permissions['permissions'], 'view_media_types'))
                                        @if (getSetting('pages_activation', @$setting) == 1)
                                            @if ($permissions->contains('view_media_types'))
                                                <a href="{{ route('panel.member.media-types.index') }}"
                                                    class="menu-item a-item {{ activeClassIfRoutes(['panel.member.media-types.index', 'panel.member.media-types.create', 'panel.member.media-types.edit', 'panel.member.media-types.show'], 'active') }}">
                                                    @lang('admin/ui.left_sidebar_media_type')</a>
                                            @endif
                                        @endif
                                    @endif

                                    @if (
                                        !is_null($authUser->permissions) &&

                                            isset($authUser->permissions['permissions']) &&
                                            isUserHasPermission($authUser->permissions['permissions'], 'view_sentiments'))
                                        @if (getSetting('pages_activation', @$setting) == 1)
                                            @if ($permissions->contains('view_sentiments'))
                                                <a href="{{ route('panel.member.sentiments.index') }}"
                                                    class="menu-item a-item {{ activeClassIfRoutes(['panel.member.sentiments.index', 'panel.member.sentiments.create', 'panel.member.sentiments.edit', 'panel.member.sentiments.show'], 'active') }}">
                                                    @lang('admin/ui.left_sidebar_sentiment')</a>
                                            @endif
                                        @endif
                                    @endif

                                    @if (
                                        !is_null($authUser->permissions) &&

                                            isset($authUser->permissions['permissions']) &&
                                            isUserHasPermission($authUser->permissions['permissions'], 'view_content_lengths'))
                                        <a href="{{ route('panel.member.content-lengths.index') }}"
                                            class="menu-item a-item {{ activeClassIfRoutes(['panel.member.content-lengths.index', 'panel.member.content-lengths.create', 'panel.member.content-lengths.edit', 'panel.member.content-lengths.show'], 'active') }}">@lang('admin/ui.left_sidebar_content_length')</a>
                                    @endif

                                </div>
                            </div>
                        @endif
                    @endif
                        

                    @if(!is_null($authUser->permissions) && isset($authUser->permissions['permissions']) && isUserHasPermission($authUser->permissions['permissions'], 'view_contents'))
                        <div class="nav-item {{ $segment5 == 'contents' ? 'active' : '' }}">
                            @if (getSetting('paragraph_content_activation', @$setting) == 1)
                                    <a href="{{ route('panel.member.contents.index') }}" class="a-item">
                                        <i class="ik ik-monitor"></i><span> @lang('admin/ui.left_sidebar_content') </a></span>
                            @endif
                        </div>
                    @endif

                    @if(!is_null($authUser->permissions) && isset($authUser->permissions['permissions']) && isUserHasPermission($authUser->permissions['permissions'], 'view_landing_pages'))
                        <div class="nav-item {{ $segment6 == 'landing' ? 'active' : '' }}">
                            @if (getSetting('paragraph_content_activation', @$setting) == 1)
                                    <a href="{{ route('panel.member.landing-pages.index') }}" class="a-item">
                                        <i class="ik ik-hard-drive"></i><span> @lang('admin/ui.left_sidebar_landing_page')</span> </a>
                            @endif
                        </div>
                    @endif

                    {{-- @if ($permissions->contains('manage_setup_configuration'))
                        @if (getSetting('basic_details_activation', @$setting) || getSetting('manage_general_configuration_activation', @$setting) || getSetting('mail_sms_configuration_activation', @$setting))
                            <div
                                class="nav-item {{ activeClassIfRoutes(['panel.member.setting.index', 'panel.member.social-login', 'panel.member.website-pages.social-login', 'panel.member.general.index', 'panel.member.setting.payment', 'panel.member.mail-sms-configuration.index', 'panel.member.setting.payment', 'panel.member.setting.features-activation', 'panel.member.personalization.index'], 'active open') }} has-sub">
                                <a href="#"><i class="ik ik-settings"></i><span> @lang('admin/ui.left_sidebar_setup_configurations') </span></a>
                                <div class="submenu-content">

                                    @if (getSetting('basic_details_activation', @$setting) == 1 && $permissions->contains('control_basic_details'))
                                        <a href="{{ route('panel.member.setting.index') }}"
                                            class="menu-item a-item {{ activeClassIfRoute('panel.member.setting.index', 'active') }}">
                                            @lang('admin/ui.left_sidebar_basic_details') </a>
                                    @endif

                                    @if (getSetting('manage_general_configuration_activation', @$setting) == 1)
                                        @if ($permissions->contains('access_general_setting') || $permissions->contains('access_currency_setting') || $permissions->contains('access_date_time_setting') || $permissions->contains('access_notification_setting') || $permissions->contains('access_troubleshoot_setting'))
                                            <a href="{{ route('panel.member.general.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoute('panel.member.general.index', 'active') }}">
                                                @lang('admin/ui.left_sidebar_general_configuration') </a>
                                        @endif
                                    @endif
                                    @if (getSetting('mail_sms_configuration_activation', @$setting) == 1)
                                        @if ($permissions->contains('access_email_setting') || $permissions->contains('access_sms_setting') || $permissions->contains('access_fcm_setting'))
                                            <a href="{{ route('panel.member.mail-sms-configuration.index', ['name' => 'mail_config']) }}"
                                                class="menu-item a-item {{ activeClassIfRoute('panel.member.mail-sms-configuration.index', 'active') }}">
                                                @lang('admin/ui.left_sidebar_mail_sms_configuration') </a>
                                        @endif
                                    @endif

                                    @if ($permissions->contains('features_activation') && env('DEV_MODE') == 1)
                                        <a href="{{ route('panel.member.setting.features-activation') }}"
                                            class="menu-item a-item {{ activeClassIfRoute('panel.member.setting.features-activation', 'active') }}">
                                            @lang('admin/ui.left_sidebar_features_activation') </a>
                                    @endif
                                    @if (getSetting('theme_activation', @$setting) == 1)
                                        @if ($permissions->contains('theme_activation') && env('DEV_MODE') == 1)
                                        <a href="{{ route('panel.member.personalization.index') }}"
                                            class="menu-item a-item {{ activeClassIfRoute('panel.member.personalization.index', 'active') }}">
                                            @lang('admin/ui.theme') </a>
                                    @endif


                                </div>
                            </div>
                        @endif
                    @endif --}}
                @endif
            </nav>
        </div>
    </div>
</div>

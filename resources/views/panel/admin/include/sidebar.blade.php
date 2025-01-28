@php
    $roles = App\Models\Role::pluck('display_name');
    $segment1 = request()->segment(1);
    $segment2 = request()->segment(2);
    $segment3 = request()->segment(3);
    $segment4 = request()->segment(4);
    $segment5 = request()->segment(5);
    $segment6 =  request()->segment(6);
@endphp
<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{ route('panel.admin.dashboard.index') }}">
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
                    <a href="{{ route('panel.admin.dashboard.index') }}" class="a-item"><i
                            class="ik ik-bar-chart-2"></i><span> @lang('admin/ui.left_sidebar_dashboard') </span></a>
                </div>
                <div class="nav-item {{ activeClassIfRoutes(['panel.admin.users.index', 'panel.admin.users.create', 'panel.admin.users.edit'], 'active open') }}">
                    <a href="{{ route('panel.admin.users.index') }}?role=Member" class="a-item"><i class="ik ik-package"></i><span>{{ __('Agents') }}</span></a>
                </div>

                @if ($permissions->contains('access_by_admin'))
                    @if (getSetting('order_activation', @$setting) ||
                            getSetting('subscribers_activation', @$setting) ||
                            getSetting('payout_activation', @$setting))
                        <div
                            class="nav-item {{ activeClassIfRoutes(['panel.admin.orders.index', 'panel.admin.orders.show', 'panel.admin.orders.invoice', 'panel.admin.user-subscriptions.index', 'panel.admin.user-subscriptions.create', 'panel.admin.user-subscriptions.edit', 'panel.admin.payouts.index', 'panel.admin.payouts.show', 'panel.admin.orders.invoice', 'panel.admin.orders.create'], 'active open') }} has-sub">
                            @if ($permissions->contains('view_orders') || $permissions->contains('view_payouts'))
                                <a href="#"><i class="ik ik-shopping-bag"></i><span> @lang('admin/ui.left_sidebar_sales_payment')
                                    </span></a>
                                <div class="submenu-content">
                                    @if (getSetting('order_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_orders'))
                                            <a href="{{ route('panel.admin.orders.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.orders.index', 'panel.admin.payouts.edit'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_manage_orders') </a>
                                        @endif
                                    @endif

                                    @if (getSetting('subscribers_activation', @$setting) == 1)
                                        <a href="{{ route('panel.admin.user-subscriptions.index') }}"
                                            class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.user-subscriptions.index', 'panel.admin.user-subscriptions.edit'], 'active') }}">@lang('admin/ui.left_sidebar_subscribers')
                                        </a>
                                    @endif
                                    @if (getSetting('payout_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_payouts'))
                                            <a href="{{ route('panel.admin.payouts.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.payouts.index', 'panel.admin.payouts.edit'], 'active') }}">
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
                                class="nav-item {{ activeClassIfRoutes(['panel.admin.items.index', 'panel.admin.items.create', 'panel.admin.items.edit', 'panel.admin.subscriptions.index', 'panel.admin.subscriptions.create', 'panel.admin.subscriptions.edit'], 'active open') }} has-sub">
                                <a href="#"><i class="ik ik-package"></i><span> @lang('admin/ui.left_sidebar_control_products') </span></a>
                                <div class="submenu-content">
                                    @if (getSetting('item_activation', @$setting) == 1)
                                        <a href="{{ route('panel.admin.items.index') }}"
                                            class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.items.index', 'panel.admin.items.edit'], 'active') }}">
                                            @lang('admin/ui.left_sidebar_manage_items') </a>
                                    @endif
                                    @if (getSetting('subscription_plans_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_subscription_plans'))
                                            <a href="{{ route('panel.admin.subscriptions.index') }}"
                                                class="menu-item {{ activeClassIfRoutes(['panel.admin.subscriptions.index', 'panel.admin.subscriptions.create', 'panel.admin.subscriptions.edit'], 'active') }}">@lang('admin/ui.subscriptions')</a>
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
                                class="nav-item {{ activeClassIfRoutes(['panel.admin.reports.purchase', 'panel.admin.reports.registration'], 'active open') }} has-sub">
                                <a href="#"><i class="ik ik-pie-chart"></i><span> @lang('admin/ui.left_sidebar_reports') </span></a>
                                <div class="submenu-content">
                                    @if (getSetting('purchase_activation', @$setting) == 1)
                                        <a href="{{ route('panel.admin.reports.purchase') }}"
                                            class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.reports.purchase'], 'active') }}">
                                            @lang('admin/ui.left_sidebar_purchase_flow') </a>
                                    @endif
                                    @if (getSetting('registration_activation', @$setting) == 1)
                                        <a href="{{ route('panel.admin.reports.registration') }}"
                                            class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.reports.registration'], 'active') }}">
                                            @lang('admin/ui.left_sidebar_registration_flow') </a>
                                    @endif

                                </div>
                            </div>
                        @endif
                    @endif

                    @if ($permissions->contains('manage_administrator'))
                        @if (getSetting('user_management_activation', @$setting) || getSetting('roles_and_permission_activation', @$setting))
                            <div
                                class="nav-item {{ activeClassIfRoutes(['panel.admin.users.index', 'panel.admin.users.show', 'panel.admin.users.create', 'panel.admin.user_log.index', 'panel.admin.roles.index', 'panel.admin.permissions.index', 'panel.admin.roles.edit', 'panel.admin.users.edit'], 'active open') }} has-sub">
                                <a href="#"><i class="ik ik-users"></i><span> @lang('admin/ui.left_sidebar_administrator') </span></a>
                                <div class="submenu-content">
                                    @foreach ($roles as $role)
                                        @if (getSetting('user_management_activation', @$setting) == 1)
                                            <a href="{{ route('panel.admin.users.index') }}?role={{ $role }}"
                                                class="menu-item a-item @if (request()->has('role') && request()->get('role') == $role) active @endif">{{ $role }}
                                                @lang('admin/ui.left_sidebar_management')</a>
                                        @endif
                                    @endforeach
                                    @if (getSetting('roles_and_permission_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_roles'))
                                            <a href="{{ route('panel.admin.roles.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoute('panel.admin.roles.index', 'active') }}">
                                                @lang('admin/ui.left_sidebar_roles') </a>
                                        @endif
                                        @if ($permissions->contains('view_permissions'))
                                            <a href="{{ route('panel.admin.permissions.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoute('panel.admin.permissions.index', 'active') }}">
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
                                class="nav-item {{ activeClassIfRoutes(['panel.admin.leads.index', 'panel.admin.leads.create', 'panel.admin.leads.edit', 'panel.admin.leads.show', 'panel.admin.website-enquiries.index', 'panel.admin.website-enquiries.create', 'panel.admin.website-enquiries.edit', 'panel.admin.support-tickets.index', 'panel.admin.support-tickets.show', 'panel.admin.support-tickets.show', 'panel.admin.news-letters.index', 'panel.admin.news-letters.create', 'panel.admin.news-letters.edit'], 'active open') }} has-sub">
                                <a href="#"><i class="ik ik-mail"></i><span> @lang('admin/ui.left_sidebar_contacts_enquiry') </span></a>
                                <div class="submenu-content">
                                    @if (getSetting('website_enquiry_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_enquiries'))
                                            <a href="{{ route('panel.admin.website-enquiries.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.website-enquiries.index', 'panel.admin.website-enquiries.create', 'panel.admin.website-enquiries.edit'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_website_enquiry') </a>
                                        @endif
                                    @endif
                                    @if (getSetting('ticket_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_tickets'))
                                            <a href="{{ route('panel.admin.support-tickets.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.support-tickets.index', 'panel.admin.support-tickets.show'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_support_tickets') </a>
                                        @endif
                                    @endif
                                    @if (getSetting('newsletter_activation', @$setting) == 1)
                                        {{-- @if ($permissions->contains('view_newletters')) --}}
                                        <a href="{{ route('panel.admin.news-letters.index') }}"
                                            class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.news-letters.index', 'panel.admin.news-letters.create', 'panel.admin.news-letters.edit'], 'active') }}">
                                            @lang('admin/ui.left_sidebar_news_letter') </a>
                                        {{-- @endif --}}
                                    @endif
                                    @if (getSetting('lead_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_leads'))
                                            <a href="{{ route('panel.admin.leads.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.leads.index', 'panel.admin.leads.create', 'panel.admin.leads.edit', 'panel.admin.leads.show'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_leads') </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif

                    @if ($permissions->contains('manage_constant_management'))
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
                                        'panel.admin.resources.documentation',
                                        'panel.admin.templates.index',
                                        'panel.admin.website-pages.index',
                                        'panel.admin.faqs.index',
                                        'panel.admin.faqs.create',
                                        'panel.admin.faqs.edit',
                                        'panel.admin.templates.create',
                                        'panel.admin.templates.edit',
                                        'panel.admin.templates.show',
                                        'panel.admin.category-types.index',
                                        'panel.admin.category-types.create',
                                        'panel.admin.category-types.edit',
                                        'panel.admin.categories.index',
                                        'panel.admin.categories.create',
                                        'panel.admin.categories.edit',
                                        'panel.admin.paragraph-contents.index',
                                        'panel.admin.paragraph-contents.create',
                                        'panel.admin.paragraph-contents.edit',
                                        'panel.admin.slider-types.index',
                                        'panel.admin.slider-types.create',
                                        'panel.admin.slider-types.edit',
                                        'panel.admin.blogs.index',
                                        'panel.admin.blogs.create',
                                        'panel.admin.blogs.edit',
                                        'panel.admin.blogs.show',
                                        'panel.admin.sliders.edit',
                                        'panel.admin.sliders.index',
                                        'panel.admin.sliders.create',
                                        'panel.admin.locations.country',
                                        'panel.admin.locations.country.create',
                                        'panel.admin.locations.state',
                                        'panel.admin.locations.state.create',
                                        'panel.admin.locations.city',
                                        'panel.admin.locations.city.create',
                                        'panel.admin.seo-tags.index',
                                        'panel.admin.website-pages.appearance',
                                        'panel.admin.website-pages.create',
                                        'admin.promo-codes.index',
                                        'panel.admin.content-categories.index',
                                        'panel.admin.content-categories.create',
                                        'panel.admin.content-categories.edit',
                                        'panel.admin.content-categories.show',
                                        'panel.admin.content-lengths.index',
                                        'panel.admin.content-lengths.create',
                                        'panel.admin.content-lengths.edit',
                                        'panel.admin.content-lengths.show',
                                         'panel.admin.age-groups.index',
                                'panel.admin.age-groups.create',
                                'panel.admin.age-groups.edit',
                                'panel.admin.age-groups.show',
                                'panel.admin.badges.index',
                                'panel.admin.badges.create',
                                'panel.admin.badges.edit',
                                'panel.admin.badges.show',
                                'panel.admin.gender-specificities.index',
                                'panel.admin.gender-specificities.edit',
                                'panel.admin.gender-specificities.create',
                                'panel.admin.gender-specificities.show',
                                'panel.admin.landing-pages.index',
                                'panel.admin.languages.edit',
                                'panel.admin.languages.show',
                                'panel.admin.media-types.index',
                                'panel.admin.media-types.create',
                                'panel.admin.media-types.edit',
                                'panel.admin.media-types.show',
                                'panel.admin.occasions.create',
                                'panel.admin.occasions.index',
                                'panel.admin.occasions.edit',
                                'panel.admin.occasions.show',
                                'panel.admin.relations.index',
                                'panel.admin.relations.create',
                                'panel.admin.relations.edit',
                                'panel.admin.relations.show',
                                'panel.admin.sentiments.index',
                                'panel.admin.sentiments.create',
                                'panel.admin.sentiments.edit',
                                'panel.admin.sentiments.show',
                                    ],
                                    'active open',
                                ) }} has-sub">
                                <a href="#"><i class="ik ik-hard-drive"></i><span> @lang('admin/ui.left_sidebar_content_management')
                                    </span></a>
                                <div class="submenu-content">
                                    @if (getSetting('article_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_blogs'))
                                            <a href="{{ route('panel.admin.blogs.index') }}"
                                                class="menu-item {{ activeClassIfRoutes(['panel.admin.blogs.index', 'panel.admin.blogs.create', 'panel.admin.blogs.edit', 'panel.admin.blogs.show'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_blogs') </a>
                                        @endif
                                    @endif
                                    @if (getSetting('mail_sms_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_mail_templates'))
                                            <a href="{{ route('panel.admin.templates.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.templates.index', 'panel.admin.templates.create', 'panel.admin.templates.edit', 'panel.admin.templates.show'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_mail_sms_templates') </a>
                                        @endif
                                    @endif
                                    @if (getSetting('category_management_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_categories'))
                                            <a href="{{ route('panel.admin.category-types.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.category-types.index', 'panel.admin.category-types.create', 'panel.admin.category-types.edit', 'panel.admin.category.index', 'panel.admin.category.create', 'panel.admin.category.edit'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_category_group') </a>
                                        @endif
                                    @endif
                                    @if (getSetting('slider_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_sliders'))
                                            <a href="{{ route('panel.admin.slider-types.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.slider-types.index', 'panel.admin.slider-types.create', 'panel.admin.slider-types.edit'], 'active') }}">@lang('admin/ui.left_sidebar_slider_group')</a>
                                        @endif
                                    @endif
                                    @if (getSetting('paragraph_content_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_paragraph_contents'))
                                            <a href="{{ route('panel.admin.paragraph-contents.index') }}"
                                                class="menu-item {{ activeClassIfRoutes(['panel.admin.paragraph-contents.index', 'panel.admin.paragraph-contents.create', 'panel.admin.paragraph-contents.edit'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_paragraph_content') </a>
                                        @endif
                                    @endif
                                    {{-- @if (getSetting('faq_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_faqs'))
                                            <a href="{{ route('panel.admin.faqs.index') }}"
                                                class="menu-item {{ activeClassIfRoutes(['panel.admin.faqs.index', 'panel.admin.faqs.create', 'panel.admin.faqs.edit'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_manage_faqs') </a>
                                        @endif
                                    @endif --}}
                                    @if (getSetting('location_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_locations'))
                                            <a href="{{ route('panel.admin.locations.country') }}"
                                                class="menu-item {{ activeClassIfRoutes(['panel.admin.locations.country', 'panel.admin.locations.country.create', 'panel.admin.locations.state.create', 'panel.admin.locations.city.create', 'panel.admin.locations.city', 'panel.admin.locations.state'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_locations') </a>
                                        @endif
                                    @endif
                                    @if (getSetting('seo_tags_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_seo_tags'))
                                            <a href="{{ route('panel.admin.seo-tags.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoute('panel.admin.seo-tags.index', 'active') }}">
                                                @lang('admin/ui.left_sidebar_control_seo') </a>
                                        @endif
                                    @endif
                                    @if (getSetting('pages_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_pages'))
                                            <a href="{{ route('panel.admin.website-pages.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.website-pages.index', 'panel.admin.website-pages.create', 'panel.admin.website-pages.edit'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_pages')</a>
                                        @endif
                                    @endif


                                    @if (getSetting('resources_activation', @$setting) == 1)
                                        <a href="{{ route('panel.admin.resources.documentation') }}"
                                            class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.resources.documentation'], 'active') }}">
                                            @lang('admin/ui.left_sidebar_documentation') </a>
                                    @endif

                                    @if (getSetting('category_management_activation', @$setting) == 1)
                                        @if ($permissions->contains('view_content_categories'))
                                            <a href="{{ route('panel.admin.content-categories.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.content-categories.index', 'panel.admin.content-categories.create', 'panel.admin.content-categories.edit', 'panel.admin.content-categories.show'], 'active') }}">
                                                @lang('admin/ui.left_sidebar_content_category') </a>
                                        @endif
                                    @endif
                                   

                                     {{-- @if (getSetting('pages_activation', @$setting) == 1) --}}
                            {{-- @if ($permissions->contains('view_occasions')) --}}
                            <a href="{{ route('panel.admin.occasions.index') }}"
                            class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.occasions.index', 'panel.admin.occasions.create', 'panel.admin.occasions.edit', 'panel.admin.occasions.show'], 'active') }}">
                            @lang('admin/ui.left_sidebar_occasion')</a>
                        {{-- @endif --}}
                        {{-- @endif --}}
                        @if (getSetting('faq_activation', @$setting) == 1)
                            @if ($permissions->contains('view_events'))
                                <a href="{{ route('panel.admin.events.index') }}"
                                    class="menu-item {{ activeClassIfRoutes(['panel.admin.events.index', 'panel.admin.events.create', 'panel.admin.events.edit', 'panel.admin.events.show'], 'active') }}">
                                    @lang('admin/ui.left_sidebar_event') </a>
                            @endif
                        @endif
                        @if ($permissions->contains('view_age_groups'))
                            <a href="{{ route('panel.admin.age-groups.index') }}"
                                class="menu-item {{ activeClassIfRoutes(['panel.admin.age-groups.index', 'panel.admin.age-groups.create', 'panel.admin.age-groups.edit', 'panel.admin.age-groups.show'], 'active') }}">
                                @lang('admin/ui.left_sidebar_age-group') </a>
                        @endif

                        @if ($permissions->contains('view_badges'))
                            <a href="{{ route('panel.admin.badges.index') }}"
                                class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.badges.index', 'panel.admin.badges.create', 'panel.admin.badges.edit', 'panel.admin.badges.show'], 'active') }}">
                                @lang('admin/ui.left_sidebar_badge') </a>
                        @endif
                        @if ($permissions->contains('view_relations'))
                            <a href="{{ route('panel.admin.relations.index') }}"
                                class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.relations.index', 'panel.admin.relations.create', 'panel.admin.relations.edit', 'panel.admin.relations.show'], 'active') }}">
                                @lang('admin/ui.left_sidebar_relation') </a>
                        @endif

                        {{-- @if (getSetting('location_activation', @$setting) == 1) --}}
                        {{-- @if ($permissions->contains('view_gender_specificities')) --}}
                        <a href="{{ route('panel.admin.gender-specificities.index') }}"
                            class="menu-item {{ activeClassIfRoutes(['panel.admin.gender-specificities.index', 'panel.admin.gender-specificities.create', 'panel.admin.gender-specificities.edit', 'panel.admin.gender-specificities.show'], 'active') }}">
                            @lang('admin/ui.left_sidebar_gender-specificity') </a>
                        {{-- @endif --}}
                        {{-- @endif --}}
                        @if (getSetting('pages_activation', @$setting) == 1)
                            @if ($permissions->contains('view_languages'))
                                <a href="{{ route('panel.admin.languages.index') }}"
                                    class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.languages.index', 'panel.admin.languages.create', 'panel.admin.languages.edit', 'panel.admin.languages.show'], 'active') }}">
                                    @lang('admin/ui.left_sidebar_language')</a>
                            @endif
                        @endif
                        @if (getSetting('pages_activation', @$setting) == 1)
                            @if ($permissions->contains('view_media_types'))
                                <a href="{{ route('panel.admin.media-types.index') }}"
                                    class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.media-types.index', 'panel.admin.media-types.create', 'panel.admin.media-types.edit', 'panel.admin.media-types.show'], 'active') }}">
                                    @lang('admin/ui.left_sidebar_media_type')</a>
                            @endif
                        @endif

                        @if (getSetting('pages_activation', @$setting) == 1)
                            @if ($permissions->contains('view_sentiments'))
                                <a href="{{ route('panel.admin.sentiments.index') }}"
                                    class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.sentiments.index', 'panel.admin.sentiments.create', 'panel.admin.sentiments.edit', 'panel.admin.sentiments.show'], 'active') }}">
                                    @lang('admin/ui.left_sidebar_sentiment')</a>
                            @endif
                        @endif
                                <a href="{{ route('panel.admin.content-lengths.index') }}"
                                    class="menu-item a-item {{ activeClassIfRoutes(['panel.admin.content-lengths.index', 'panel.admin.content-lengths.create', 'panel.admin.content-lengths.edit', 'panel.admin.content-lengths.show'], 'active') }}">@lang('admin/ui.left_sidebar_content_length')</a>

                                </div>
                            </div>
                        @endif
                    @endif
                    <div class="nav-item {{ $segment5 == 'contents' ? 'active' : '' }}">
                        @if (getSetting('paragraph_content_activation', @$setting) == 1)
                            @if ($permissions->contains('view_contents'))
                                <a href="{{ route('panel.admin.contents.index') }}" class="a-item">
                                    <i class="ik ik-monitor"></i><span> @lang('admin/ui.left_sidebar_content') </a></span>
                            @endif
                        @endif
                    </div>
                    <div class="nav-item {{ $segment6 == 'landing' ? 'active' : '' }}">
                        @if (getSetting('paragraph_content_activation', @$setting) == 1)
                            @if ($permissions->contains('view_landing_pages'))
                            <a href="{{ route('panel.admin.landing-pages.index') }}"
                            class="a-item">
                               <i class="ik ik-hard-drive"></i><span> @lang('admin/ui.left_sidebar_landing_page')</span> </a>
                        @endif
                        @endif
                    </div>
                    {{-- @endif --}}

                    @if ($permissions->contains('manage_setup_configuration'))
                        @if (getSetting('basic_details_activation', @$setting) ||
                                getSetting('manage_general_configuration_activation', @$setting) ||
                                getSetting('mail_sms_configuration_activation', @$setting))
                            <div
                                class="nav-item {{ activeClassIfRoutes(['panel.admin.setting.index', 'panel.admin.social-login', 'panel.admin.website-pages.social-login', 'panel.admin.general.index', 'panel.admin.setting.payment', 'panel.admin.mail-sms-configuration.index', 'panel.admin.setting.payment', 'panel.admin.setting.features-activation', 'panel.admin.personalization.index'], 'active open') }} has-sub">
                                <a href="#"><i class="ik ik-settings"></i><span> @lang('admin/ui.left_sidebar_setup_configurations') </span></a>
                                <div class="submenu-content">

                                    @if (getSetting('basic_details_activation', @$setting) == 1 && $permissions->contains('control_basic_details'))
                                        <a href="{{ route('panel.admin.setting.index') }}"
                                            class="menu-item a-item {{ activeClassIfRoute('panel.admin.setting.index', 'active') }}">
                                            @lang('admin/ui.left_sidebar_basic_details') </a>
                                    @endif

                                    @if (getSetting('manage_general_configuration_activation', @$setting) == 1)
                                        @if (
                                            $permissions->contains('access_general_setting') ||
                                                $permissions->contains('access_currency_setting') ||
                                                $permissions->contains('access_date_time_setting') ||
                                                $permissions->contains('access_notification_setting') ||
                                                $permissions->contains('access_troubleshoot_setting'))
                                            <a href="{{ route('panel.admin.general.index') }}"
                                                class="menu-item a-item {{ activeClassIfRoute('panel.admin.general.index', 'active') }}">
                                                @lang('admin/ui.left_sidebar_general_configuration') </a>
                                        @endif
                                    @endif
                                    @if (getSetting('mail_sms_configuration_activation', @$setting) == 1)
                                        @if (
                                            $permissions->contains('access_email_setting') ||
                                                $permissions->contains('access_sms_setting') ||
                                                $permissions->contains('access_fcm_setting'))
                                            <a href="{{ route('panel.admin.mail-sms-configuration.index', ['name' => 'mail_config']) }}"
                                                class="menu-item a-item {{ activeClassIfRoute('panel.admin.mail-sms-configuration.index', 'active') }}">
                                                @lang('admin/ui.left_sidebar_mail_sms_configuration') </a>
                                        @endif
                                    @endif

                                    @if ($permissions->contains('features_activation') && env('DEV_MODE') == 1)
                                        <a href="{{ route('panel.admin.setting.features-activation') }}"
                                            class="menu-item a-item {{ activeClassIfRoute('panel.admin.setting.features-activation', 'active') }}">
                                            @lang('admin/ui.left_sidebar_features_activation') </a>
                                    @endif
                                    @if (getSetting('theme_activation', @$setting) == 1)
                                        {{-- @if ($permissions->contains('theme_activation') && env('DEV_MODE') == 1) --}}
                                        <a href="{{ route('panel.admin.personalization.index') }}"
                                            class="menu-item a-item {{ activeClassIfRoute('panel.admin.personalization.index', 'active') }}">
                                            @lang('admin/ui.theme') </a>
                                    @endif


                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </nav>
        </div>
    </div>
</div>

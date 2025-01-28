<div class="col-lg-2 border-end d-lg-block d-md-none d-none">
    <div class="user-sidebar">
        <div class="user-sidebar-list">
            <h6 class="mb-0">@lang('user/ui.menu')</h6>
            <ul>
                <li class="mb-0"><a href="{{ route('panel.user.dashboard.index') }}"
                        class="{{ activeClassIfRoutes(['panel.user.dashboard.index'], 'side-heading') }}">@lang('user/ui.overview')</a>
                </li>
                <li><a href="{{ route('panel.user.order.index') }}"
                        class="{{ activeClassIfRoutes(['panel.user.order.index'], 'side-heading') }}"
                        class="{{ activeClassIfRoutes(['panel.user.order.index'], 'side-heading') }}">@lang('user/ui.orders_returns')</a>
                </li>
                <li><a href="{{ route('panel.user.payout.index') }}"
                        class="{{ activeClassIfRoutes(['panel.user.payout.index'], 'side-heading') }}">@lang('user/ui.payout')</a></li>
                <li><a href="{{ route('panel.user.wallet.index') }}"
                        class="{{ activeClassIfRoutes(['panel.user.wallet.index'], 'side-heading') }}">@lang('user/ui.wallet_statement')</a></li>
                <li><a href="{{ route('panel.user.support-ticket.index') }}"
                        class="{{ activeClassIfRoutes(['panel.user.support-ticket.index'], 'side-heading') }}">@lang('user/ui.tickets')</a>
                </li>
            </ul>
        </div>

        <div class="user-sidebar-list">
            <h6 class="mb-0">@lang('user/ui.account')</h6>
            <ul>
                <li><a href="{{ route('panel.user.profile.index',['active' => 'security']) }}"
                        class="{{ activeClassIfRoutes(['panel.user.profile.index'], 'side-heading') }}">@lang('user/ui.profile')</a>
                </li>
                <li><a href="{{ route('panel.user.address.index') }}"
                        class="{{ activeClassIfRoutes(['panel.user.address.index'], 'side-heading') }}">@lang('user/ui.addresses')</a>
                </li>
                <li><a href="{{ route('panel.user.saved-account.index') }}"
                        class="{{ activeClassIfRoutes(['panel.user.saved-account.index'], 'side-heading') }}">@lang('user/ui.saved_banks')</a></li>
                <li><a href="{{ route('panel.user.verify.index') }}"
                        class="{{ activeClassIfRoutes(['panel.user.verify.index'], 'side-heading') }}">@lang('user/ui.verifications')</a>
                </li>
                <li><a href="{{ route('panel.user.security.index') }}"
                        class="{{ activeClassIfRoutes(['panel.user.security.index'], 'side-heading') }}">@lang('user/ui.security')</a>
                </li>
                <li><a href="{{ route('panel.user.my-activity.index') }}"
                        class="{{ activeClassIfRoutes(['panel.user.my-activity.index'], 'side-heading') }}">@lang('user/ui.my_activity')</a>
                </li>
                <li><a href="{{ route('panel.user.delegate-access.index') }}"
                        class="{{ activeClassIfRoutes(['panel.user.delegate-access.index'], 'side-heading') }}">@lang('user/ui.delegate_access')</a>
                </li>
            </ul>
        </div>

        <div class="user-sidebar-list">
            <h6 class="mb-0">@lang('user/ui.legal')</h6>
            <ul>
                @php
                    $usefull_links = App\Models\WebsitePage::select('title', 'slug')->whereStatus(1)->latest()->limit(10)->get();
                @endphp
                @foreach ($usefull_links as $item)
                    <li>
                        <a href="{{ route('page.slug', $item->slug) }}" target="_blank">
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="">
        <p class="mt-4 fs-12 text-muted">©
            <script>
                document.write(new Date().getFullYear())
            </script> {{ getSetting('app_name') }}.
        </p>
    </div>
</div>

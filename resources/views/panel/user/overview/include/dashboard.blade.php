@php
    $dashboards = [
        ['icon' => 'order-icon-01.png', 'sidebarname' => __('user/ui.order'), 'shorttitle' => __('user/ui.check_order_status'), 'route' => route('panel.user.order.index')],
        ['icon' => 'payout-icon-images-01.png', 'sidebarname' =>  __('user/ui.payout'), 'shorttitle' => __('user/ui.see_payout_details'), 'route' => route('panel.user.payout.index')],
        ['icon' => 'wallet-icons-01.png', 'sidebarname' => __('user/ui.wallet'), 'shorttitle' => __('user/ui.inside_wallet'), 'route' => route('panel.user.wallet.index')],
        ['icon' => 'support-icon-01.png', 'sidebarname' => __('user/ui.tickets'), 'shorttitle' => __('user/ui.need_ticket_help'), 'route' => route('panel.user.support-ticket.index')],
        ['icon' => 'profile-icon-01.png', 'sidebarname' => __('user/ui.profile'), 'shorttitle' => __('user/ui.change_profile'), 'route' => route('panel.user.profile.index',['active'=>"security"])],
        ['icon' => 'adresses-icon-01.png', 'sidebarname' => __('user/ui.addresses'), 'shorttitle' => __('user/ui.check_address_status'), 'route' => route('panel.user.address.index')],
        ['icon' => 'saved-bank-icon-01.png', 'sidebarname' =>  __('user/ui.saved_banks'), 'shorttitle' => __('user/ui.manage_bank_details'), 'route' => route('panel.user.saved-account.index')],
        ['icon' => 'varification-icon-01.png', 'sidebarname' => __('user/ui.verifications'), 'shorttitle' => __('user/ui.check_verification_status'), 'route' => route('panel.user.verify.index')],
        ['icon' => 'security-icon-01.png', 'sidebarname' => __('user/ui.security'), 'shorttitle' => __('user/ui.change_password'), 'route' => route('panel.user.security.index')],
    ];
@endphp
@foreach ($dashboards as $dashboard)
    <div class="col-lg-4 col-md-6 col-sm-12 mb-lg-3 mb-4">
        <div class="card">
            <a class="link-card" href="{{ $dashboard['route'] }}">
                <div class="card-body">
                    <div class="link-content">
                        <img class="link-icon" src="{{ asset('panel/user/assets/img/dashboard/' . $dashboard['icon']) }}"
                            alt="{{ $dashboard['sidebarname'] }}">
                        <div class="link-labels text-center">
                            <div class="link-label text-dark">{{ $dashboard['sidebarname'] }}</div>
                            <div class="link-subLabel">{{ $dashboard['shorttitle'] }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endforeach

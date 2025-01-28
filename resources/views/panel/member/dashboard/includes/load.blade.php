<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            @if (getSetting('order_activation', @$setting) == 1)
                <div class="col-md-12">
                    <div class="statistic-header">
                        <h5>@lang('admin/ui.order_management')</h5>
                    </div>
                </div>
        </div>
        <div class="statistics-grid">
            @foreach (\App\Models\Order::STATUSES as $key => $order)
                <a class="" href="{{ route('panel.member.orders.index', ['status' => $key]) }}">
                    <div class="card m-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="state">
                                    <h3 class="text-secondary">{{ getOrderStatusCount($key) }}</h3>
                                    <h6 class="card-subtitle text-dark fw-700 mb-0">{{ @$order['label'] }}</h6>
                                </div>
                                <div class="col-auto icon-size">
                                    <i
                                        class="{{ $order['icon'] }} text-muted f-12 btn btn-light btn-icon p-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
            @endif
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('admin/ui.subscription_management')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid row">
            <!-- User Subscriptions -->
            <a class="c" href="{{ route('panel.member.user-subscriptions.index') }}">
                <div class="card m-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="state">
                                <h3 class="text-secondary">{{ $stats['userSubscriptionCount'] }}</h3>
                                <h6 class="card-subtitle text-dark fw-700 mb-0">User Subscription</h6>
                            </div>
                            <div class="col-auto icon-size">
                                <i class="fas fa-users text-muted f-12 btn btn-light btn-icon p-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <!-- Subscriptions -->
            <a class="  " href="{{ route('panel.member.subscriptions.index') }}">
                <div class="card m-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="state">
                                <h3 class="text-secondary">{{ $stats['subscriptionCount'] }}</h3>
                                <h6 class="card-subtitle text-dark fw-700 mb-0">Subscription</h6>
                            </div>
                            <div class="col-auto icon-size">
                                <i class="fas fa-file-alt text-muted f-12 btn btn-light btn-icon p-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('admin/ui.payouts_management')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid">
            @foreach (\App\Models\Payout::STATUSES as $key => $payout)
                <a class="" href="{{ route('panel.member.payouts.index', ['status' => $key]) }}">
                    <div class="card m-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="state">
                                    <h3 class="text-secondary">{{ getPayoutStatusCount($key) }}</h3>
                                    <h6 class="card-subtitle text-dark fw-700 mb-0 blink-light-effect">
                                        {{ @$payout['label'] }} <div class="">
                                            @if ($key == 0)
                                                <div class="blinking-light"></div>
                                            @endif
                                        </div>
                                    </h6>

                                </div>
                                <div class="col-auto icon-size">
                                    <i
                                        class="{{ $payout['icon'] }} text-muted f-12 btn btn-light btn-icon p-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('admin/ui.item_management')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid row">
            <a class="" href="{{ route('panel.member.items.index') }}">
                <div class="card m-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="state">
                                <h3 class="text-secondary">{{ $stats['itemCount'] }}</h3>
                                <h6 class="card-subtitle text-dark fw-700 mb-0">Item</h6>
                            </div>
                            <div class="col-auto icon-size">
                                <i class="fas fa-users text-muted f-12 btn btn-light btn-icon p-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('admin/ui.administrator_management')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid">
            @foreach ($roles as $key => $role)
                <a class=""
                    href="{{ route('panel.member.users.index', ['role' => $role->display_name]) }}">
                    <div class="card m-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="state">
                                    <h3 class="text-secondary">{{ getUserCountByRole($role->display_name) }}
                                    </h3>
                                    <h6 class="card-subtitle text-dark fw-700 mb-0">{{ $role->display_name }}
                                    </h6>
                                </div>
                                <div class="col-auto icon-size">
                                    <i
                                        class="{{ $order['icon'] }} text-muted f-12 btn btn-light btn-icon p-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('admin/ui.website_enquiry_management')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid">
            @foreach (\App\Models\WebsiteEnquiry::STATUSES as $key => $enquiry)
                <a class=""
                    href="{{ route('panel.member.website-enquiries.index', ['status' => $key]) }}">
                    <div class="card m-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="state">
                                    <h3 class="text-secondary">{{ getEnquiryStatusCount($key) }}</h3>
                                    <h6 class="card-subtitle text-dark fw-700 mb-0">{{ @$enquiry['label'] }}
                                    </h6>
                                </div>
                                <div class="col-auto icon-size">
                                    <i
                                        class="{{ $enquiry['icon'] }} text-muted f-12 btn btn-light btn-icon p-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('admin/ui.support_tickets_management')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid">
            @foreach (\App\Models\SupportTicket::STATUSES as $key => $ticket)
                <a class=""
                    href="{{ route('panel.member.support-tickets.index', ['status' => $key]) }}">
                    <div class="card m-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="state">
                                    <h3 class="text-secondary">{{ getSupportTicketStatusCount($key) }}</h3>
                                    <h6 class="card-subtitle text-dark fw-700 mb-0 d-flex">
                                        {{ @$ticket['label'] }}
                                        <div class="ml-2">
                                            @if ($key == 0)
                                                <div class="blinking-light"></div>
                                            @endif
                                        </div>
                                    </h6>
                                </div>
                                <div class="col-auto icon-size">
                                    <i
                                        class="{{ $ticket['icon'] }} text-muted f-12 btn btn-light btn-icon p-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('admin/ui.lead_management')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid">
            @foreach (\App\Models\Lead::STATUSES as $key => $lead)
                <a class="" href="{{ route('panel.member.leads.index', ['status' => $key]) }}">
                    <div class="card m-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="state">
                                    <h3 class="text-secondary">{{ getLeadStatusCount($key) }}</h3>
                                    <h6 class="card-subtitle text-dark fw-700 mb-0 d-flex">
                                        {{ @$lead['label'] }}
                                        <div class="ml-2">
                                            @if ($key == 1)
                                                <div class="blinking-light"></div>
                                            @endif
                                        </div>
                                    </h6>
                                </div>
                                <div class="col-auto icon-size">
                                    <i
                                        class="{{ $lead['icon'] }} text-muted f-12 btn btn-light btn-icon p-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="statistic-header">
                    <h5>@lang('admin/ui.blog_management')</h5>
                </div>
            </div>
        </div>
        <div class="statistics-grid row">
            <!-- User Subscriptions -->
            <a class="" href="{{ route('panel.member.blogs.index') }}">
                <div class="card m-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="state">
                                <h3 class="text-secondary">{{ $stats['blogCount'] }}</h3>
                                <h6 class="card-subtitle text-dark fw-700 mb-0">Blogs</h6>
                            </div>
                            <div class="col-auto icon-size">
                                <i class="fas fa-blog text-muted f-12 btn btn-light btn-icon p-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>

</div>

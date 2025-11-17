<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-center">
                <div class="logo">
                    <a href="{{ url('home') }}">
                        <img src="{{ url('assets/images/logo/' . (system_setting('company_logo') ?? null)) }}" alt="Logo" srcset="">
                    </a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">

                {{-- Dashboard --}}
                @if (has_permissions('read', 'dashboard'))
                    <li class="sidebar-item">
                        <a href="{{ url('home') }}" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span class="menu-item">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                @endif

                {{-- Core --}}
                {{-- Core: Property --}}
                @if (has_permissions('read', 'property'))
                    <li class="sidebar-item">
                        <a href="{{ url('property') }}" class='sidebar-link'>
                            <i class="bi bi-building"></i>
                            <span class="menu-item">{{ __('Property') }}</span>
                        </a>
                    </li>
                @endif

                {{-- Core: Project --}}
                @if (has_permissions('read', 'project'))
                    <li class="sidebar-item">
                        <a href="{{ url('project') }}" class='sidebar-link'>
                            <i class="bi bi-house"></i>
                            <span class="menu-item">{{ __('Project') }}</span>
                        </a>
                    </li>
                @endif

                {{-- Taxonomy/Attributes --}}
                {{-- Taxonomy: Categories --}}
                @if (has_permissions('read', 'categories'))
                    <li class="sidebar-item">
                        <a href="{{ url('categories') }}" class='sidebar-link'>
                            <i class="fas fa-align-justify"></i>
                            <span class="menu-item">{{ __('Categories') }}</span>
                        </a>
                    </li>
                @endif

                {{-- Attributes: Facilities --}}
                @if (has_permissions('read', 'facility'))
                    <li class="sidebar-item">
                        <a href="{{ url('parameters') }}" class='sidebar-link'>
                            <i class="bi bi-x-diamond"></i>
                            <span class="menu-item">{{ __('Facilities') }}</span>
                        </a>
                    </li>
                @endif

                {{-- Attributes: Near by places --}}
                @if (has_permissions('read', 'near_by_places'))
                    <li class="sidebar-item">
                        <a href="{{ url('outdoor_facilities') }}" class='sidebar-link'>
                            <i class="bi bi-geo-alt"></i>
                            <span class="menu-item">{{ __('Near By Places') }}</span>
                        </a>
                    </li>
                @endif

                {{-- Media: City Images--}}
                @if (has_permissions('read', 'city_images'))
                    <li class="sidebar-item">
                        <a href="{{ route('city-images.index') }}" class='sidebar-link'>
                            <i class="bi bi-image-alt"></i>
                            <span class="menu-item">{{ __('City Images') }}</span>
                        </a>
                    </li>
                @endif

                {{-- People --}}
                {{-- People: Customer --}}
                @if (has_permissions('read', 'customer'))
                    <li class="sidebar-item">
                        <a href="{{ url('customer') }}" class='sidebar-link'>
                            <i class="bi bi-person-circle"></i>
                            <span class="menu-item">{{ __('Customer') }}</span>
                        </a>
                    </li>
                @endif

                {{-- People: Verify Agent --}}
                @if (has_permissions('read', 'verify_customer_form') || has_permissions('read', 'approve_agent_verification'))
                    <li class="sidebar-item has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-person-check"></i>
                            <span class="menu-item">{{ __('Verify Agent') }}</span>
                        </a>
                        <ul class="submenu" style="padding-left: 0rem">

                            {{-- Custom Form --}}
                            @if (has_permissions('read', 'verify_customer_form'))
                                <li class="submenu-item">
                                    <a href="{{ route('verify-customer.form') }}">{{ __('Custom Form') }}</a>
                                </li>
                            @endif

                            {{-- Custom Form --}}
                            @if (has_permissions('read', 'approve_agent_verification'))
                                <li class="submenu-item">
                                    <a href="{{ route('agent-verification.index') }}">{{ __('Agent Verification List') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{-- Communication --}}
                {{-- Communication: Chat --}}
                @if (has_permissions('read', 'chat'))
                    
                @endif

                {{-- Communication: User Inquiries --}}
                @if (has_permissions('read', 'users_inquiries'))
                    <li class="sidebar-item">
                        <a href="{{ url('users_inquiries') }}" class='sidebar-link'>
                            <i class="fas fa-question-circle"></i>
                            <span class="menu-item">{{ __('Users Inquiries') }}</span>
                        </a>
                    </li>
                @endif

                {{-- Moderation --}}
                {{-- Moderation: User Reports --}}
                @if (has_permissions('read', 'user_reports'))
                    <li class="sidebar-item">
                        <a href="{{ url('users_reports') }}" class='sidebar-link'>
                            <i class="bi bi-exclamation-octagon-fill"></i>
                            <span class="menu-item">{{ __('Users Reports') }}</span>
                        </a>
                    </li>
                @endif

                {{-- Moderation: Report Reasons --}}
                @if (has_permissions('read', 'report_reason'))
                    <li class="sidebar-item">
                        <a href="{{ url('report-reasons') }}" class='sidebar-link'>
                            <i class="bi bi-list-task"></i>
                            <span class="menu-item">{{ __('Report Reasons') }}</span>
                        </a>
                    </li>
                @endif

                {{-- Monetization --}}
                {{-- Monetization: Feature Packages --}}
                @if (has_permissions('read', 'package'))
                    <li class="sidebar-item has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-credit-card-2-back"></i>
                            <span class="menu-item">{{ __('Feature Packages') }}</span>
                        </a>
                        <ul class="submenu" style="padding-left: 0rem">
                            {{-- List Features --}}
                            @if (has_permissions('read', 'package-feature') || has_permissions('create', 'package-feature'))
                                <li class="submenu-item">
                                    <a href="{{ route('package-features.index') }}">{{ __('Features') }}</a>
                                </li>
                            @endif

                            {{-- List Packages --}}
                            @if (has_permissions('read', 'package') || has_permissions('create', 'package'))
                                <li class="submenu-item">
                                    <a href="{{ route('package.index') }}">{{ __('Packages') }}</a>
                                </li>
                            @endif

                            {{-- User Packages --}}
                            @if (has_permissions('read', 'user_package'))
                                <li class="submenu-item">
                                    <a href="{{ route('user-packages.index') }}">{{ __('Users Packages') }}</a>
                                </li>
                            @endif

                            {{-- Payment --}}
                            @if (has_permissions('read', 'payment'))
                                <li class="submenu-item">
                                    <a href="{{ route('payment.index') }}">{{ __('Payment') }}</a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif

                {{-- System --}}
                {{-- System: FAQs --}}
                @if (has_permissions('read', 'faqs'))
                    <li class="sidebar-item">
                        <a href="{{ route('faqs.index') }}" class='sidebar-link'>
                            <i class="bi bi-question-circle"></i>
                            <span class="menu-item">{{ __('FAQ') }}</span>
                        </a>
                    </li>
                @endif

            </ul>
        </div>
        </div>
    </div>

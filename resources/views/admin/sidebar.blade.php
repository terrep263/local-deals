 <!-- Sidebar -->
            <nav id="sidebar">
                <!-- Sidebar Scroll Container -->
                <div id="sidebar-scroll">
                    <!-- Sidebar Content -->
                    <!-- Adding .sidebar-mini-hide to an element will hide it when the sidebar is in mini mode -->
                    <div class="sidebar-content">
                        <!-- Side Header -->
                        <div class="side-header side-content bg-white-op">
                            <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                            <button class="btn btn-link text-gray pull-right hidden-md hidden-lg" style="padding: 5px;" type="button" data-toggle="layout" data-action="sidebar_close">
                                <i class="fa fa-times"></i>
                            </button>
                            
                            <a class="h5 text-white" href="{{ URL::to('admin/dashboard') }}">
                                <span class="h4 font-w600 sidebar-mini-hide">{{getcong('site_name')}}</span>
                            </a>
                        </div>
                        <!-- END Side Header -->

                        <!-- Side Content -->
                        <div class="side-content">
                            <ul class="nav-main">
                                <li><a class="{{classActivePath('dashboard')}}" href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i><span class="sidebar-mini-hide">{{trans('words.dashboard')}}</span></a></li>
                                
                                <!-- Module 6: Deal Management -->
                                <li><a class="{{classActivePath('deals')}}" href="{{ route('admin.deals.index') }}"><i class="fa fa-tags"></i><span class="sidebar-mini-hide">Deals</span></a></li>
                                
                                <!-- Module 6: Vendor Management -->
                                <li><a class="{{classActivePath('vendors')}}" href="{{ route('admin.vendors.index') }}"><i class="fa fa-store"></i><span class="sidebar-mini-hide">Manage Vendors</span></a></li>
                                
                                <!-- Module 1: Subscriptions -->
                                <li><a class="{{classActivePath('subscriptions')}}" href="{{ route('admin.subscriptions.index') }}"><i class="fa fa-credit-card"></i><span class="sidebar-mini-hide">Subscriptions</span></a></li>
                                
                                <!-- Module 6: Support Tickets -->
                                <li><a class="{{classActivePath('support')}}" href="{{ route('admin.support.index') }}"><i class="fa fa-ticket"></i><span class="sidebar-mini-hide">Support Tickets</span></a></li>
                                
                                <!-- Module 6: Reports & Analytics -->
                                <li><a class="{{classActivePath('reports')}}" href="{{ route('admin.reports.index') }}"><i class="fa fa-bar-chart"></i><span class="sidebar-mini-hide">Reports</span></a></li>
                                <li><a class="{{classActivePath('analytics')}}" href="{{ route('admin.analytics.index') }}"><i class="fa fa-line-chart"></i><span class="sidebar-mini-hide">Analytics</span></a></li>
                                
                                <!-- Module 6: Activity Log -->
                                <li><a class="{{classActivePath('activity-log')}}" href="{{ route('admin.activity-log.index') }}"><i class="fa fa-history"></i><span class="sidebar-mini-hide">Activity Log</span></a></li>
                                
                                <!-- Categories (still needed for deal categorization) -->
                                <li><a class="{{classActivePath('categories')}}" href="{{ URL::to('admin/categories') }}"><i class="fa fa-bars"></i><span class="sidebar-mini-hide">{{trans('words.categories')}}</span></a></li>
                                <!-- Cities / Locations -->
                                <li><a class="{{ request()->routeIs('admin.cities.*') ? 'active' : '' }}" href="{{ route('admin.cities.index') }}"><i class="fa fa-map-marker"></i><span class="sidebar-mini-hide">Cities</span></a></li>
                                <!-- Locations -->
                                <li><a class="{{classActivePath('locations')}}" href="{{ URL::to('admin/locations') }}"><i class="fa fa-map-marker"></i><span class="sidebar-mini-hide">{{trans('words.locations')}}</span></a></li>
                                
                                <!-- Users -->
                                <li><a class="{{classActivePath('users')}}" href="{{ URL::to('admin/users') }}"><i class="fa fa-users"></i><span class="sidebar-mini-hide">{{trans('words.users')}}</span></a></li>
                                
                                <!-- Module 6: Settings -->
                                <li><a class="{{classActivePath('platform-settings')}}" href="{{ route('admin.platform-settings.index') }}"><i class="fa fa-sliders"></i><span class="sidebar-mini-hide">Platform Settings</span></a></li>
                                <li><a class="{{classActivePath('email-templates')}}" href="{{ route('admin.email-templates.index') }}"><i class="fa fa-envelope"></i><span class="sidebar-mini-hide">Email Templates</span></a></li>
                                <li><a class="{{classActivePath('settings')}}" href="{{ URL::to('admin/settings') }}"><i class="fa fa-cog"></i><span class="sidebar-mini-hide">{{trans('words.settings')}}</span></a></li> 
                            </ul>
                        </div>
                        <!-- END Side Content -->
                    </div>
                    <!-- Sidebar Content -->
                </div>
                <!-- END Sidebar Scroll Container -->
            </nav>
            <!-- END Sidebar -->
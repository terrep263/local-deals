 <!-- Header -->
            <header id="header-navbar" class="content-mini content-mini-full">
                <!-- Header Navigation Right -->
                <ul class="nav-header pull-right">
                    <!-- View Switching Controls -->
                    <li class="hidden-xs">
                        <div class="btn-group" style="margin-right: 10px;">
                            <!-- View As Vendor Dropdown -->
                            <div class="dropdown" style="display: inline-block;">
                                <button class="btn btn-sm btn-alt-secondary dropdown-toggle" 
                                        type="button" 
                                        id="viewAsDropdown" 
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                    <i class="fa fa-eye"></i> <span class="hidden-sm">View As Vendor</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" style="min-width: 280px; max-height: 400px; overflow-y: auto;">
                                    <li class="dropdown-header">Select Vendor to View As</li>
                                    <li style="padding: 8px 15px;">
                                        <input type="text" 
                                               class="form-control input-sm" 
                                               id="vendorSearch" 
                                               placeholder="Search vendors..."
                                               style="margin-bottom: 5px;">
                                    </li>
                                    <li class="divider"></li>
                                    <li id="vendorListContainer">
                                        <a href="#" class="text-center" style="padding: 10px;">
                                            <i class="fa fa-spin fa-spinner"></i> Loading vendors...
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                            <!-- View Public Site Button -->
                            <a href="{{ url('/') }}" 
                               target="_blank" 
                               class="btn btn-sm btn-alt-primary"
                               title="View public site">
                                <i class="fa fa-external-link"></i> <span class="hidden-sm">View Public Site</span>
                            </a>
                        </div>
                    </li>
                    
                    <!-- Admin Profile Dropdown -->
                    <li>
                        <div class="btn-group">
                            <button class="btn btn-default btn-image dropdown-toggle" data-toggle="dropdown" type="button">
                                 
                            @if(Auth::user()->image_icon)
                                 
								<img src="{{URL::to('upload/members/'.Auth::user()->image_icon)}}\" width="40" alt="Avatar">
							
							@else
								
							<img src="{{ URL::asset('admin_assets/img/avatars/avatar10.jpg') }}" alt="Avatar"  width="40"/>
							
							@endif
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="dropdown-header">{{trans('words.profile')}}</li>                                
                                <li>
                                    <a tabindex="-1" href="{{ URL::to('admin/profile') }}">
                                        <i class="si si-user pull-right"></i>
                                        {{trans('words.profile')}}
                                    </a>
                                </li>
                                <li>
                                    <a tabindex="-1" href="{{ URL::to('admin/settings') }}">
                                        <i class="si si-settings pull-right"></i>{{trans('words.settings')}}
                                    </a>
                                </li>
                                 
                                <li>
                                    <a tabindex="-1" href="{{ URL::to('admin/logout') }}">
                                        <i class="si si-logout pull-right"></i>{{trans('words.logout')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                </ul>
                <!-- END Header Navigation Right -->

                <!-- Header Navigation Left -->
                <ul class="nav-header pull-left">
                    <li class="hidden-md hidden-lg">
                        <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                        <button class="btn btn-default" data-toggle="layout" data-action="sidebar_toggle" type="button">
                            <i class="fa fa-navicon"></i>
                        </button>
                    </li>
                    <li class="hidden-xs hidden-sm">
                        <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                        <button class="btn btn-default" data-toggle="layout" data-action="sidebar_mini_toggle" type="button">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                    </li>
                    <li>
                        <!-- Opens the Apps modal found at the bottom of the page, before including JS code -->
                        <button class="btn btn-default pull-right" data-toggle="modal" data-target="#apps-modal" type="button">
                            <i class="si si-grid"></i>
                        </button>
                    </li>
                     
                     
                </ul>
                <!-- END Header Navigation Left -->
            </header>
            <!-- END Header -->

            <!-- Vendor Search & Impersonation JavaScript -->
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Load vendors when dropdown is opened
                var vendorDropdown = document.getElementById('viewAsDropdown');
                var vendorListLoaded = false;
                
                if (vendorDropdown) {
                    vendorDropdown.addEventListener('click', function() {
                        if (!vendorListLoaded) {
                            loadVendors();
                            vendorListLoaded = true;
                        }
                    });
                }
                
                // Vendor search functionality
                var vendorSearch = document.getElementById('vendorSearch');
                if (vendorSearch) {
                    vendorSearch.addEventListener('input', function(e) {
                        var searchTerm = e.target.value.toLowerCase();
                        var vendorItems = document.querySelectorAll('.vendor-item');
                        
                        vendorItems.forEach(function(item) {
                            var vendorName = item.getAttribute('data-vendor-name').toLowerCase();
                            if (vendorName.includes(searchTerm)) {
                                item.style.display = 'block';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    });
                }
                
                function loadVendors() {
                    var container = document.getElementById('vendorListContainer');
                    
                    fetch('{{ route("admin.impersonate.vendors", [], false) ?? url("/admin/impersonate/vendors") }}', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.vendors && data.vendors.length > 0) {
                            var html = '';
                            data.vendors.forEach(function(vendor) {
                                html += '<li class="vendor-item" data-vendor-name="' + vendor.business_name + '">';
                                html += '<a href="#" onclick="impersonateVendor(' + vendor.id + '); return false;" style="padding: 8px 15px;">';
                                html += '<i class="fa fa-store"></i> <strong>' + vendor.business_name + '</strong>';
                                html += '<br><small class="text-muted" style="padding-left: 18px;">' + vendor.subscription_tier + '</small>';
                                html += '</a>';
                                html += '</li>';
                            });
                            container.innerHTML = html;
                        } else {
                            container.innerHTML = '<li><a href="#" class="text-muted" style="padding: 10px;">No active vendors found</a></li>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading vendors:', error);
                        container.innerHTML = '<li><a href="#" class="text-danger" style="padding: 10px;"><i class="fa fa-exclamation-triangle"></i> Error loading vendors</a></li>';
                    });
                }
            });
            
            function impersonateVendor(vendorId) {
                if (confirm('View dashboard as this vendor? You will be able to see everything they see.')) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("admin.impersonate.start", ":id", false) ?? url("/admin/impersonate/:id/start") }}'.replace(':id', vendorId);
                    
                    var csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            }
            </script>

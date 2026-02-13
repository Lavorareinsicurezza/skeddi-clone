<!-- Mobile sidebar button -->
{{-- <button data-drawer-target="cta-button-sidebar" data-drawer-toggle="cta-button-sidebar"
    aria-controls="cta-button-sidebar" type="button"
    class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
    <span class="sr-only">{{ __('lang.open_sidebar') }}</span>
    <i class="fas fa-bars w-6 h-6"></i>
</button> --}}

<!-- Sidebar -->
<aside id="cta-button-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full lg:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 py-8 overflow-y-auto bg-white border-r border-gray-200">
        <!-- Logo Text -->
        <div class="mb-8">
            <h1 class="text-lg font-bold text-[#0C3183]">{{ __('lang.management_software') }}</h1>
        </div>

        <!-- Company Management Section (only shown when company is selected) -->
        @if(session('selectedCompanyId'))
        <div class="mb-6">
            <h2 class="px-2 mb-3 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('lang.company_management') }}</h2>
            <ul class="space-y-2 font-medium">
                @can('view selected-company')
                <li>
                    <a href="{{ route('admin.selected-company.detail') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.selected-company.detail') ? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-briefcase w-5 h-5 {{ request()->routeIs('admin.selected-company.detail') ? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">{{ __('lang.company_details') }}</span>
                    </a>
                </li>
                @endcan
                @can('view deadlines')
                <li>
                    <a href="{{ route('admin.deadlines') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.deadlines') ? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-clock w-5 h-5 {{ request()->routeIs('admin.deadlines') ? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">{{ __('lang.deadlines') }}</span>
                    </a>
                </li>
                @endcan
                @can('view chart')
                <li>
                    <a href="{{ route('admin.chart.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.chart.*') ? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-sitemap w-5 h-5 {{ request()->routeIs('admin.chart.*') ? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">{{ __('lang.organizational_chart') }}</span>
                    </a>
                </li>
                @endcan
                @can('view company-workers')
                <li>
                    <a href="{{ route('admin.company-workers.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.company-workers.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-users w-5 h-5 {{ request()->routeIs('admin.company-workers.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">{{ __('lang.workers') }}</span>
                    </a>
                </li>
                @endcan
                @can('view operating-locations')
                <li>
                    <a href="{{ route('admin.operating-locations.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.operating-locations.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-map-marker-alt w-5 h-5 {{ request()->routeIs('admin.operating-locations.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">{{ __('lang.operating_locations') }}</span>
                    </a>
                </li>
                @endcan
                @can('view company-documents')
                <li>
                    <a href="{{ route('admin.company-documents.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.company-documents.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-file-alt w-5 h-5 {{ request()->routeIs('admin.company-documents.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">{{ __('lang.documents') }}</span>
                    </a>
                </li>
                @endcan
                @can('view company-course-types')
                <li>
                    <a href="{{ route('admin.company-course-types.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.company-course-types.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-graduation-cap w-5 h-5 {{ request()->routeIs('admin.company-course-types.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">{{ __('lang.course_types') }}</span>
                    </a>
                </li>
                @endcan
                @can('view training-plan')
                <li>
                    <a href="{{ route('admin.training-plan.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.training-plan.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-clipboard-list w-5 h-5 {{ request()->routeIs('admin.training-plan.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">{{ __('lang.training_plan') }}</span>
                    </a>
                </li>
                @endcan
                @can('view company-visit-types')
                <li>
                    <a href="{{ route('admin.company-visit-types.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.company-visit-types.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-map-marker-alt w-5 h-5 {{ request()->routeIs('admin.company-visit-types.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">{{ __('lang.types_of_visit') }}</span>
                    </a>
                </li>
                @endcan
                {{-- @can('view company-renewals')
                <li>
                    <a href="{{ route('admin.company-renewals.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.company-renewals.index')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-sync-alt w-5 h-5 {{ request()->routeIs('admin.company-renewals.index')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">{{ __('lang.renewals') }}</span>
                    </a>
                </li>
                @endcan --}}
            </ul>
        </div>
        @endif

        <!-- Content Management Section -->
        <div class="mb-6">
            <h2 class="px-2 mb-3 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('lang.content_management') }}</h2>
            <ul class="space-y-2 font-medium">
            @can('view dashboard')
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.dashboard')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-tachometer-alt w-5 h-5 {{ request()->routeIs('admin.dashboard')? 'text-[#0C3183]': 'text-gray-500'}} group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">{{ __('lang.dashboard') }}</span>
                </a>
            </li>
            @endcan
            @can('view companies')
            <li>
                <a href="{{ route('admin.companies.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.companies.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-building w-5 h-5 {{ request()->routeIs('admin.companies.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">{{ __('lang.companies') }}</span>
                </a>
            </li>
            @endcan
            @can('view users')
            <li>
                <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.users.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-users w-5 h-5 {{ request()->routeIs('admin.users.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">{{ __('lang.users') }}</span>
                </a>
            </li>
            @endcan
            @can('view course-types')
            <li>
                <a href="{{ route('admin.course-types.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.course-types.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-book w-5 h-5 {{ request()->routeIs('admin.course-types.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">{{ __('lang.courses') }}</span>
                </a>
            </li>
            @endcan
            @can('view document-types')
            <li>
                <a href="{{ route('admin.document-types.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.document-types.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-file-alt w-5 h-5 {{ request()->routeIs('admin.document-types.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">{{ __('lang.document_types') }}</span>
                </a>
            </li>
            @endcan
            @can('view visit-types')
            <li>
                <a href="{{ route('admin.visit-types.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.visit-types.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-map-marker-alt w-5 h-5 {{ request()->routeIs('admin.visit-types.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">{{ __('lang.types_of_visit') }}</span>
                </a>
            </li>
            @endcan
            @can('view settings')
            <li>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.settings.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-cog w-5 h-5 {{ request()->routeIs('admin.settings.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">{{ __('lang.settings') }}</span>
                </a>
            </li>
            @endcan
            @can('view smtp-profiles')
            <li>
                <a href="{{ route('admin.smtp-profiles.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.smtp-profiles.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-envelope w-5 h-5 {{ request()->routeIs('admin.smtp-profiles.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">{{ __('lang.smtp_profiles') }}</span>
                </a>
            </li>
            @endcan
            @can( 'view roles')
            <li>
                <a href="{{ route('admin.roles.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.roles.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-user-tag w-5 h-5 {{ request()->routeIs('admin.roles.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">{{ __('lang.roles') }}</span>
                </a>
            </li>
            @endcan

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-2 text-gray-500 rounded-[20px] hover:text-red-600 hover:bg-red-50 group">
                        <i class="fas fa-sign-out-alt w-5 h-5 text-gray-500 transition duration-75 group-hover:text-red-600"></i>
                        <span class="ms-3">{{ __('lang.logout') }}</span>
                    </button>
                </form>
            </li>
            </ul>
        </div>
    </div>
</aside>

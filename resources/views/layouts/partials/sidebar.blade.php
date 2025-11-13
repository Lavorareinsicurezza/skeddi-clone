<!-- Mobile sidebar button -->
<button data-drawer-target="cta-button-sidebar" data-drawer-toggle="cta-button-sidebar"
    aria-controls="cta-button-sidebar" type="button"
    class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
    <span class="sr-only">Open sidebar</span>
    <i class="fas fa-bars w-6 h-6"></i>
</button>

<!-- Sidebar -->
<aside id="cta-button-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 py-8 overflow-y-auto bg-white border-r border-gray-200">
        <!-- Logo Text -->
        <div class="mb-8">
            <h1 class="text-lg font-bold text-[#0C3183]">Management Software</h1>
        </div>

        <!-- Company Management Section (only shown when company is selected) -->
        @if(session('selectedCompanyId'))
        <div class="mb-6">
            <h2 class="px-2 mb-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Company Management</h2>
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('admin.selected-company.detail') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.selected-company.detail') ? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-briefcase w-5 h-5 {{ request()->routeIs('admin.selected-company.detail') ? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">Company Details</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 rounded-[20px] text-gray-500 hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-clock w-5 h-5 text-gray-500 transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">Deadlines</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 rounded-[20px] text-gray-500 hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-sitemap w-5 h-5 text-gray-500 transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">Organizational Chart</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 rounded-[20px] text-gray-500 hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-users w-5 h-5 text-gray-500 transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">Workers</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 rounded-[20px] text-gray-500 hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-file-alt w-5 h-5 text-gray-500 transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">Documents</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 rounded-[20px] text-gray-500 hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-graduation-cap w-5 h-5 text-gray-500 transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">Course Types</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 rounded-[20px] text-gray-500 hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-clipboard-list w-5 h-5 text-gray-500 transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">Training plan</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 rounded-[20px] text-gray-500 hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-map-marker-alt w-5 h-5 text-gray-500 transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">Types of Visit</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 rounded-[20px] text-gray-500 hover:text-[#0C3183] hover:bg-blue-50 group">
                        <i class="fas fa-sync-alt w-5 h-5 text-gray-500 transition duration-75 group-hover:text-[#0C3183]"></i>
                        <span class="ms-3">Renewals</span>
                    </a>
                </li>
            </ul>
        </div>
        @endif

        <!-- Content Management Section -->
        <div class="mb-6">
            <h2 class="px-2 mb-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Content Management</h2>
            <ul class="space-y-2 font-medium">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.dashboard')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-tachometer-alt w-5 h-5 {{ request()->routeIs('admin.dashboard')? 'text-[#0C3183]': 'text-gray-500'}} group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.companies.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.companies.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-building w-5 h-5 {{ request()->routeIs('admin.companies.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">Companies</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.users.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-users w-5 h-5 {{ request()->routeIs('admin.users.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">Users</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.course-types.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.course-types.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-book w-5 h-5 {{ request()->routeIs('admin.course-types.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">Courses</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.document-types.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.document-types.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-file-alt w-5 h-5 {{ request()->routeIs('admin.document-types.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">Document Types</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.visit-types.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.visit-types.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-map-marker-alt w-5 h-5 {{ request()->routeIs('admin.visit-types.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">Types of visit</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center p-2 rounded-[20px] {{ request()->routeIs('admin.settings.*')? 'text-[#0C3183] bg-blue-50': 'text-gray-500' }} hover:text-[#0C3183] hover:bg-blue-50 group">
                    <i class="fas fa-cog w-5 h-5 {{ request()->routeIs('admin.settings.*')? 'text-[#0C3183]': 'text-gray-500'}} transition duration-75 group-hover:text-[#0C3183]"></i>
                    <span class="ms-3">Settings</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-2 text-gray-500 rounded-[20px] hover:text-red-600 hover:bg-red-50 group">
                        <i class="fas fa-sign-out-alt w-5 h-5 text-gray-500 transition duration-75 group-hover:text-red-600"></i>
                        <span class="ms-3">Logout</span>
                    </button>
                </form>
            </li>
            </ul>
        </div>
    </div>
</aside>

<!-- Header -->
<nav class="bg-white border-b border-gray-200 px-4 py-4 fixed top-0 left-0 right-0 z-30 sm:pl-64">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4 px-3">
            <button data-drawer-target="cta-button-sidebar" data-drawer-toggle="cta-button-sidebar"
                aria-controls="cta-button-sidebar" type="button"
                class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
                <span class="sr-only">Open sidebar</span>
                <i class="fas fa-bars w-6 h-6 mt-2"></i>
            </button>
            <div class="relative">
                <button id="openCompanyModal" type="button"
                    class="px-3 py-2 text-1sm text-gray-900 bg-white border border-gray-200 font-medium rounded-lg focus:ring-blue-500 focus:border-blue-500 appearance-none w-[100px] sm:w-[200px] hover:bg-blue-50 hover:text-[#0C3183] truncate">
                    @if(session('selectedCompanyName'))
                        {{ session('selectedCompanyName') }}
                    @else
                        Select Company
                    @endif
                </button>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <button class="relative p-2 text-gray-500 hover:text-gray-600">
                <i class="fas fa-bell w-5 h-5"></i>
                <div class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></div>
            </button>
            <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg"
                alt="User avatar">
        </div>
    </div>
</nav>

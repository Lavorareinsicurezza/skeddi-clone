@extends('layouts.app')

@section('content')

    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flash-message-box" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flash-message-box" role="alert">
            <span class="block sm:inline">{!! session('error') !!}</span>
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.users') }}</h1>

        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            @can('view users')
            <a href="{{ route('admin.users.export') }}"
                class=" px-5 py-3 font-bold text-gray-900 hover:text-blue-700 hover:bg-blue-50 text-sm border-r border-gray-200 flex" title="Export Data">
                <i class="text-gray-900 fa fa-download"></i>
            </a>
            @endcan
            @can('create users')
            <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                class=" px-5 py-3 font-bold text-gray-900 hover:text-blue-700 hover:bg-blue-50 text-sm border-r border-gray-200 flex" title="Import Data">
                <i class="text-gray-900 fa fa-upload"></i>
            </button>
            @endcan
            @can('create users')
            <a href="{{ route('admin.users.create') }}"
            class=" px-5 py-3 font-bold text-gray-900 hover:text-blue-700 hover:bg-blue-50 text-sm flex" title="Create User">
            <i class="text-gray-900 fa fa-plus"></i>
        </a>
        @endcan
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">{{ __('lang.import_users') }}</h3>
                <button onclick="document.getElementById('importModal').classList.add('hidden')"
                    class="text-gray-900 hover:text-red-600">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('lang.select_excel_file') }}</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <a href="{{ route('admin.users.template') }}" class="text-sm text-blue-600 hover:underline">
                        <i class="fa fa-download mr-1"></i>{{ __('lang.download_import_template') }}
                    </a>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 text-gray-900 rounded-md hover:bg-gray-400">
                        {{ __('lang.cancel') }}
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        {{ __('lang.import') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-900 bg-white">
            <thead class="text-xs text-white uppercase bg-blue-600 border-b">
                <tr>
                    <th scope="col" class="px-6 py-3 font-bold">
                        {{ __('lang.email_address') }}
                    </th>
                    <th scope="col" class="px-6 py-3 font-bold">
                        {{ __('lang.role_list') }}
                    </th>
                    <th scope="col" class="px-6 py-3 font-bold">
                        {{ __('lang.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($users->count() > 0)
                    @foreach ($users as $user)
                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap">
                                {{ $user->email }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $user->role }}
                            </td>
                            <td class="px-6 py-4">
                                @can('view users')
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                    class="font-bold text-gray-900 p-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="View">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                @endcan
                                @can('edit users')
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="font-bold text-gray-900 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endcan
                                @can('edit users')
                                <button onclick="openResetModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')"
                                    class="font-bold text-gray-900 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="Reset Password">
                                    <i class="fa fa-key"></i>
                                </button>
                                @endcan
                                {{-- <a href="#"
                                    class="font-bold text-red-500 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]">
                                    <i class="fa fa-trash"></i>
                                </a> --}}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="bg-white border-b border-gray-200">
                        <td colspan="5" class="px-6 py-4 text-center text-gray-900">
                            {{ __('lang.no_data_available') }}
                        </td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        {{ $users->onEachSide(1)->links('pagination::tailwind') }}
    </div>

    <!-- Reset Password Modal -->
    <div id="resetPasswordModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Reset Password</h3>
                <button onclick="closeResetModal()" class="text-gray-900 hover:text-red-600">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-900">User: <span id="resetUserName" class="font-bold"></span></p>
                <p class="text-sm text-gray-900">Email: <span id="resetUserEmail" class="font-bold"></span></p>
            </div>

            <div id="otpSection">
                <button type="button" onclick="sendOtp()" id="sendOtpBtn"
                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 mb-4">
                    Send OTP
                </button>
                <div id="otpMessage" class="hidden mb-4 text-sm"></div>
            </div>

            <form id="resetPasswordForm" onsubmit="submitResetPassword(event)">
                <input type="hidden" id="resetUserId" name="user_id">

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-900 mb-2">OTP Code</label>
                    <input type="text" id="otpInput" name="otp" required maxlength="6"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter 6-digit OTP">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-900 mb-2">New Password</label>
                    <input type="password" id="newPassword" name="password" required minlength="8"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    {{-- <p class="text-xs text-gray-900 mt-1">Min 8 chars, letters, numbers & symbols</p> --}}
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="password_confirmation" required minlength="8"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeResetModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-900 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" id="submitResetBtn"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentUserId = null;

        function openResetModal(id, name, email) {
            currentUserId = id;
            document.getElementById('resetUserId').value = id;
            document.getElementById('resetUserName').textContent = name;
            document.getElementById('resetUserEmail').textContent = email;
            document.getElementById('resetPasswordModal').classList.remove('hidden');
            document.getElementById('otpMessage').classList.add('hidden');
            document.getElementById('resetPasswordForm').reset();
        }

        function closeResetModal() {
            document.getElementById('resetPasswordModal').classList.add('hidden');
        }

        async function sendOtp() {
            const btn = document.getElementById('sendOtpBtn');
            const msg = document.getElementById('otpMessage');

            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';
            msg.classList.add('hidden');

            try {
                const response = await fetch("{{ route('admin.users.send-otp') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ user_id: currentUserId })
                });

                const data = await response.json();

                msg.textContent = data.message;
                msg.className = data.success ? 'mb-4 text-sm text-green-600' : 'mb-4 text-sm text-red-600';
                msg.classList.remove('hidden');

            } catch (error) {
                msg.textContent = 'Network error occurred.';
                msg.className = 'mb-4 text-sm text-red-600';
                msg.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Send OTP';
            }
        }

        async function submitResetPassword(e) {
            e.preventDefault();
            const btn = document.getElementById('submitResetBtn');
            const originalText = btn.textContent;

            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';

            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch("{{ route('admin.users.reset-password') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                // Check if response is ok
                if (!response.ok) {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                         const result = await response.json();
                         let errorMsg = result.message || 'Failed to reset password.';
                         if (result.errors) {
                             errorMsg += '\n' + Object.values(result.errors).flat().join('\n');
                         }
                         alert(errorMsg);
                         return;
                    }
                    throw new Error(response.statusText || 'Server Error');
                }

                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    closeResetModal();
                } else {
                    let errorMsg = result.message || 'Failed to reset password.';
                    if (result.errors) {
                         errorMsg += '\n' + Object.values(result.errors).flat().join('\n');
                    }
                    alert(errorMsg);
                }
            } catch (error) {
                console.error(error);
                alert('An error occurred: ' + (error.message || 'Please try again.'));
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        }
    </script>

@endsection

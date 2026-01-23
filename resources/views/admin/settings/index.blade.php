@extends('layouts.app')

@section('content')

    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flash-message-box"
            role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flash-message-box"
            role="alert">
            <span class="block sm:inline">{!! session('error') !!}</span>
        </div>
    @endif

    <!-- Test Message Container (hidden by default) -->
    <div id="testMessageContainer" class="mb-4 hidden">
        <div class="bg-white border border-gray-300 rounded-lg p-4 shadow-sm">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-green-700 font-semibold text-lg">Test</span>
            </div>
        </div>
    </div>

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.system_settings') }}</h1>
        <button type="button" id="updateHistoryBtn"
            class="px-5 py-3 bg-[#0C9488] hover:bg-[#0a7d73] text-white font-semibold rounded-lg shadow-sm">
            {{ __('lang.update_history') }}
        </button>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-lg shadow-sm">
        @csrf
        @method('PUT')

        <!-- UI Version -->
        <div class="px-6 py-3">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">{{ __('lang.ui_version') }}</h2>
            <input type="text" value="{{ $uiVersion }}" readonly
                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 text-sm">
        </div>

        <!-- Alert Notifications Settings -->
        <div class="px-6 py-3" x-data="{
            periods: @json($setting->notification_periods ?? [90, 30]),
            addPeriod() {
                this.periods.push(0);
            },
            removePeriod(index) {
                this.periods.splice(index, 1);
            }
        }">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">{{ __('lang.alert_notifications_settings') }}</h2>
            <div class="mb-4">
                <label class="block text-xs text-gray-500 mb-2">Notification Periods (Days before expiry)</label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 space-y-3">
                    <template x-for="(period, index) in periods" :key="index">
                        <div class="flex items-center gap-3 p-2 rounded-lg bg-blue-100">
                            <input type="number" name="notification_periods[]" x-model="periods[index]" min="1"
                                class=" w-[100%] px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter days (e.g., 90)">

                            <button type="button" @click="removePeriod(index)" class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
                <button type="button" @click="addPeriod()"
                    class="mt-3 flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 text-sm font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    Add Period
                </button>
            </div>
        </div>

        <!-- SMTP Configuration -->
        <div class="px-6 py-3">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">{{ __('lang.smtp_configuration') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-2">{{ __('lang.address_notification_sent') }}</label>
                    <input type="text" name="smtp_address" value="{{ old('smtp_address', $setting->smtp_address) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-2">{{ __('lang.alias_notification_sent') }}</label>
                    <input type="text" name="smtp_alias" value="{{ old('smtp_alias', $setting->smtp_alias) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-2">{{ __('lang.replyto_address') }}</label>
                    <input type="text" name="smtp_reply_to" value="{{ old('smtp_reply_to', $setting->smtp_reply_to) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-2">{{ __('lang.smtp_host') }}</label>
                    <input type="text" name="smtp_host" value="{{ old('smtp_host', $setting->smtp_host) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-2">{{ __('lang.smtp_port') }}</label>
                    <input type="text" name="smtp_port" value="{{ old('smtp_port', $setting->smtp_port) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-2">{{ __('lang.smtp_username') }}</label>
                    <input type="text" name="smtp_username" value="{{ old('smtp_username', $setting->smtp_username) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-2">{{ __('lang.smtp_password') }}</label>
                    <input type="password" name="smtp_password" value="{{ old('smtp_password', $setting->smtp_password) }}"
                        placeholder="**********"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <button type="button" id="testSmtpBtn"
                class="px-6 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium text-sm">
                {{ __('lang.test_smtp') }}
            </button>
            <div id="smtp-test-message-box" class="mt-4 w-[50%]"></div>
        </div>

        <!-- Notification Settings -->
        <div class="px-6 py-3">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Notification Settings</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center justify-between p-4 border border-gray-300 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Email automatically generated</p>
                        <p class="text-xs text-gray-500">by the system</p>
                        <p class="text-xs text-gray-500">Do not reply to this email</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="email_auto_generated" value="1" {{ old('email_auto_generated', $setting->email_auto_generated) ? 'checked' : '' }} class="sr-only peer">
                        <div
                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0C9488]">
                        </div>
                    </label>
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-300 rounded-lg">
                    <p class="text-sm font-medium text-gray-900">WhatsApp Notification</p>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="whatsapp_notification" value="1" {{ old('whatsapp_notification', $setting->whatsapp_notification) ? 'checked' : '' }} class="sr-only peer">
                        <div
                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0C9488]">
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- WhatsApp SMTP Configuration -->
        <div class="px-6 py-3">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">{{ __('lang.whatsapp_settings') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-2">{{ __('lang.address_notification_sent') }}</label>
                    <input type="text" name="whatsapp_smtp_address"
                        value="{{ old('whatsapp_smtp_address', $setting->whatsapp_smtp_address) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-2">{{ __('lang.alias_notification_sent') }}</label>
                    <input type="text" name="whatsapp_smtp_alias"
                        value="{{ old('whatsapp_smtp_alias', $setting->whatsapp_smtp_alias) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-2">{{ __('lang.replyto_address') }}</label>
                    <input type="text" name="whatsapp_smtp_reply_to"
                        value="{{ old('whatsapp_smtp_reply_to', $setting->whatsapp_smtp_reply_to) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <button type="button" id="testWhatsAppSmtpBtn"
                class="px-6 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium text-sm">
                {{ __('lang.test_smtp') }}
            </button>
        </div>

        <!-- Notification Settings (Read-only sections) -->
        <div class="px-6">
            <h1 class="text-sm font-semibold text-gray-700 mb-4">Notification Settings</h1>
        </div>
        <div class="px-6">
            <label class="block text-xs text-gray-500 mb-2">Subject of the notification</label>
            <input type="text" name="notification_subject"
                value="{{ old('notification_subject', $setting->notification_subject) }}"
                placeholder="Enter notification subject"
                class="p-4 border border-gray-300 rounded-lg bg-white w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="pt-6 px-6">
            <label class="block text-xs text-gray-500 mb-2">Notification body</label>

            <div class="w-full border border-gray-300 rounded-lg bg-white">
                <!-- Toolbar -->
                <div class="px-3 py-2 border-b bg-gray-50">
                    <!-- First Row -->
                    <div class="flex flex-wrap items-center gap-1 mb-2 pb-2 border-b border-gray-200">
                        <button type="button" id="boldBtn" title="Bold"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M6 4v12h4.5a4.5 4.5 0 003.256-7.606A4 4 0 0011 4H6zm2 2h3a2 2 0 110 4H8V6zm0 6h4a2.5 2.5 0 110 5H8v-5z" />
                            </svg>
                        </button>
                        <button type="button" id="italicBtn" title="Italic"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 4v3h2.21l-3.42 8H6v3h8v-3h-2.21l3.42-8H18V4z" />
                            </svg>
                        </button>
                        <button type="button" id="underlineBtn" title="Underline"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 17a5 5 0 01-5-5V4h2v8a3 3 0 006 0V4h2v8a5 5 0 01-5 5zm-8 1h16v2H2z" />
                            </svg>
                        </button>
                        <button type="button" id="strikeBtn" title="Strikethrough"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 4a6 6 0 00-6 6h2a4 4 0 018 0h2a6 6 0 00-6-6zM4 11h12v2H4z" />
                            </svg>
                        </button>
                        <div class="w-px h-6 bg-gray-300 mx-1"></div>
                        <button type="button" id="textColorBtn" title="Text Color"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                        </button>
                        <button type="button" id="codeBtn" title="Code"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" />
                            </svg>
                        </button>
                        <button type="button" id="linkBtn" title="Insert Link"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" />
                            </svg>
                        </button>
                        <button type="button" id="unlinkBtn" title="Remove Link"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" />
                                <path
                                    d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                            </svg>
                        </button>
                        <div class="w-px h-6 bg-gray-300 mx-1"></div>
                        <select id="fontSizeSelect" title="Font Size"
                            class="text-xs border border-gray-300 rounded px-2 py-1 text-gray-600 hover:bg-gray-200">
                            <option value="1">Small</option>
                            <option value="3" selected>Normal</option>
                            <option value="5">Large</option>
                            <option value="7">Huge</option>
                        </select>
                        <button type="button" id="highlightBtn" title="Highlight"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" />
                            </svg>
                        </button>
                        <div class="w-px h-6 bg-gray-300 mx-1"></div>
                        <button type="button" id="alignLeftBtn" title="Align Left"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h8a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h8a1 1 0 110 2H4a1 1 0 01-1-1z" />
                            </svg>
                        </button>
                        <button type="button" id="alignCenterBtn" title="Align Center"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3 4a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm-3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3 4a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1z" />
                            </svg>
                        </button>
                        <button type="button" id="alignRightBtn" title="Align Right"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm4 4a1 1 0 011-1h8a1 1 0 110 2H8a1 1 0 01-1-1zm-4 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm4 4a1 1 0 011-1h8a1 1 0 110 2H8a1 1 0 01-1-1z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Second Row -->
                    <div class="flex flex-wrap items-center gap-1">
                        <select id="formatSelect" title="Format"
                            class="text-sm border border-gray-300 rounded px-3 py-1 text-gray-600 hover:bg-gray-200">
                            <option value="">Format</option>
                            <option value="h1">Heading 1</option>
                            <option value="h2">Heading 2</option>
                            <option value="h3">Heading 3</option>
                            <option value="h4">Heading 4</option>
                            <option value="p">Paragraph</option>
                        </select>

                        <!-- Image Upload Button -->
                        <button type="button" id="imageBtn" title="Insert Image"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />
                            </svg>
                        </button>
                        <input type="file" id="imageInput" accept="image/*" class="hidden">

                        <!-- Video Upload Button -->
                        <button type="button" id="videoBtn" title="Insert Video"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                            </svg>
                        </button>
                        <input type="file" id="videoInput" accept="video/*" class="hidden">

                        <div class="w-px h-6 bg-gray-300 mx-1"></div>
                        <button type="button" id="ulBtn" title="Bullet List"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M6 4h12v2H6V4zm0 5h12v2H6V9zm0 5h12v2H6v-2zM2 4h2v2H2V4zm0 5h2v2H2V9zm0 5h2v2H2v-2z" />
                            </svg>
                        </button>
                        <button type="button" id="olBtn" title="Numbered List"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 4h1v1H3V4zm0 3h1v1H3V7zm0 3h1v1H3v-1zM6 4h12v2H6V4zm0 5h12v2H6V9zm0 5h12v2H6v-2zM3 13.5h1v1H3v-1z" />
                            </svg>
                        </button>
                        <button type="button" id="quoteBtn" title="Quote"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" />
                            </svg>
                        </button>
                        <button type="button" id="hrBtn" title="Horizontal Rule"
                            class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Editor Area -->
                <div class="px-4 py-3 bg-white rounded-b-lg">
                    <div id="editor" contenteditable="true"
                        class="block w-full px-0 text-sm text-gray-800 bg-white border-0 focus:ring-0 focus:outline-none min-h-[200px]"
                        style="min-height: 200px;">{!! old('notification_body', $setting->notification_body ?? '') !!}</div>
                    <textarea id="notification_body" name="notification_body"
                        class="hidden">{{ old('notification_body', $setting->notification_body ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="mb-6 px-6">
            <ul class="mt-4 text-sm text-gray-700 list-disc list-inside">
                <li>{company_name} - Company Name</li>
                <li>{days_left} - Days remaining until expiry</li>
                <li>{course_name} - Name of the course/document/item</li>
                <li>{expiry_date} - Expiration Date (e.g., 22 February 2026)</li>
                <li>{worker_first_name} - Worker's First Name</li>
                <li>{worker_last_name} - Worker's Last Name</li>
            </ul>
        </div>

        <!-- Save Button -->
        <div class="p-6 bg-gray-50 rounded-b-lg">
            <button type="submit"
                class="px-6 py-3 bg-[#0C9488] hover:bg-[#0a7d73] text-white font-semibold rounded-lg shadow-sm">
                {{ __('lang.save_settings') }}
            </button>
        </div>
    </form>

    <script>
        // Rich Text Editor Initialization
        document.addEventListener('DOMContentLoaded', function () {
            const editor = document.getElementById('editor');
            const textarea = document.getElementById('notification_body');

            // Helper function to execute commands
            function execCmd(command, value = null) {
                document.execCommand(command, false, value);
                editor.focus();
            }

            // Basic formatting buttons
            document.getElementById('boldBtn').addEventListener('click', (e) => { e.preventDefault(); execCmd('bold'); });
            document.getElementById('italicBtn').addEventListener('click', (e) => { e.preventDefault(); execCmd('italic'); });
            document.getElementById('underlineBtn').addEventListener('click', (e) => { e.preventDefault(); execCmd('underline'); });
            document.getElementById('strikeBtn').addEventListener('click', (e) => { e.preventDefault(); execCmd('strikeThrough'); });

            // Text color
            document.getElementById('textColorBtn').addEventListener('click', function (e) {
                e.preventDefault();
                const color = prompt('Enter color (e.g., red, #ff0000):');
                if (color) execCmd('foreColor', color);
            });

            // Code
            document.getElementById('codeBtn').addEventListener('click', function (e) {
                e.preventDefault();
                execCmd('formatBlock', '<pre>');
            });

            // Link
            document.getElementById('linkBtn').addEventListener('click', function (e) {
                e.preventDefault();
                const url = prompt('Enter URL:');
                if (url) execCmd('createLink', url);
            });

            document.getElementById('unlinkBtn').addEventListener('click', (e) => { e.preventDefault(); execCmd('unlink'); });

            // Font size
            document.getElementById('fontSizeSelect').addEventListener('change', function () {
                execCmd('fontSize', this.value);
            });

            // Highlight
            document.getElementById('highlightBtn').addEventListener('click', function (e) {
                e.preventDefault();
                execCmd('hiliteColor', 'yellow');
            });

            // Alignment
            document.getElementById('alignLeftBtn').addEventListener('click', (e) => { e.preventDefault(); execCmd('justifyLeft'); });
            document.getElementById('alignCenterBtn').addEventListener('click', (e) => { e.preventDefault(); execCmd('justifyCenter'); });
            document.getElementById('alignRightBtn').addEventListener('click', (e) => { e.preventDefault(); execCmd('justifyRight'); });

            // Format dropdown
            document.getElementById('formatSelect').addEventListener('change', function () {
                if (this.value) {
                    execCmd('formatBlock', this.value);
                    this.value = '';
                }
            });

            // Image - Open file manager
            document.getElementById('imageBtn').addEventListener('click', function (e) {
                e.preventDefault();
                document.getElementById('imageInput').click();
            });

            // Handle image file selection
            document.getElementById('imageInput').addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        const img = `<img src="${event.target.result}" alt="Uploaded image" style="max-width: 100%; height: auto;">`;
                        execCmd('insertHTML', img);
                    };
                    reader.readAsDataURL(file);
                }
                // Reset input
                e.target.value = '';
            });

            // Video - Open file manager
            document.getElementById('videoBtn').addEventListener('click', function (e) {
                e.preventDefault();
                document.getElementById('videoInput').click();
            });

            // Handle video file selection
            document.getElementById('videoInput').addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('video/')) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        const video = `<video controls style="max-width: 100%; height: auto;"><source src="${event.target.result}" type="${file.type}">Your browser does not support the video tag.</video>`;
                        execCmd('insertHTML', video);
                    };
                    reader.readAsDataURL(file);
                }
                // Reset input
                e.target.value = '';
            });

            // Lists
            document.getElementById('ulBtn').addEventListener('click', (e) => { e.preventDefault(); execCmd('insertUnorderedList'); });
            document.getElementById('olBtn').addEventListener('click', (e) => { e.preventDefault(); execCmd('insertOrderedList'); });

            // Quote
            document.getElementById('quoteBtn').addEventListener('click', function (e) {
                e.preventDefault();
                execCmd('formatBlock', '<blockquote>');
            });

            // Horizontal rule
            document.getElementById('hrBtn').addEventListener('click', (e) => { e.preventDefault(); execCmd('insertHorizontalRule'); });

            // Sync editor content with hidden textarea on input
            editor.addEventListener('input', function () {
                textarea.value = editor.innerHTML;
            });

            // Sync on form submit
            document.querySelector('form').addEventListener('submit', function () {
                textarea.value = editor.innerHTML;
            });
        });

        // Test SMTP Button Handler
        document.getElementById('testSmtpBtn').addEventListener('click', function () {
            const btn = this;
            const originalText = btn.innerHTML;

            // Disable button and show loading
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-5 w-5 text-[#0C9488] inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Testing...';

            fetch('{{ route('admin.settings.test') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const container = document.getElementById('smtp-test-message-box');
                        container.innerHTML = `
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-semibold">${data.message}</span>
                                    </div>
                                </div>
                            `;
                        container.classList.remove('hidden');

                        setTimeout(() => {
                            container.classList.add('hidden');
                        }, 5000);
                    } else {
                        // Show error message
                        const container = document.getElementById('smtp-test-message-box');
                        container.innerHTML = `
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-semibold">${data.message}</span>
                                    </div>
                                </div>
                            `;
                        container.classList.remove('hidden');

                        setTimeout(() => {
                            container.classList.add('hidden');
                        }, 5000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const container = document.getElementById('smtp-test-message-box');
                    container.innerHTML = `
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="font-semibold">Network error. Please try again.</span>
                                </div>
                            </div>
                        `;
                    container.classList.remove('hidden');

                    setTimeout(() => {
                        container.classList.add('hidden');
                    }, 5000);
                })
                .finally(() => {
                    // Re-enable button
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        });

        // Test WhatsApp SMTP Button Handler
        document.getElementById('testWhatsAppSmtpBtn').addEventListener('click', function () {
            const btn = this;
            const originalText = btn.innerHTML;

            // Disable button and show loading
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-5 w-5 text-gray-700 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Testing...';

            fetch('{{ route('admin.settings.test') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const container = document.getElementById('testMessageContainer');
                        container.innerHTML = `
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-semibold">${data.message}</span>
                                    </div>
                                </div>
                            `;
                        container.classList.remove('hidden');

                        setTimeout(() => {
                            container.classList.add('hidden');
                        }, 5000);
                    } else {
                        // Show error message
                        const container = document.getElementById('testMessageContainer');
                        container.innerHTML = `
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-semibold">${data.message}</span>
                                    </div>
                                </div>
                            `;
                        container.classList.remove('hidden');

                        setTimeout(() => {
                            container.classList.add('hidden');
                        }, 5000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const container = document.getElementById('testMessageContainer');
                    container.innerHTML = `
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="font-semibold">Network error. Please try again.</span>
                                </div>
                            </div>
                        `;
                    container.classList.remove('hidden');

                    setTimeout(() => {
                        container.classList.add('hidden');
                    }, 5000);
                })
                .finally(() => {
                    // Re-enable button
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        });

        // Update History Button Handler (placeholder)
        document.getElementById('updateHistoryBtn').addEventListener('click', function () {
            alert('Update History functionality to be implemented');
        });
    </script>

@endsection

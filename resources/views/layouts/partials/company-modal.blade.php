<!-- Company Selection Modal -->
<div id="companyModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black opacity-50" onclick="closeCompanyModal()"></div>

        <!-- Modal -->
        <div class="relative bg-white w-full max-w-3xl shadow-lg">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-5">
                <h3 class="text-2xl font-semibold pt-[50px] text-gray-900 w-full text-center">
                    {{ __('lang.workplace_safety_medium_risk') }}
                </h3>
                <button type="button" onclick="closeCompanyModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 inline-flex justify-center items-center">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-12 pb-6">
                <!-- Search Input -->
                <div class="border-b border-gray-200">
                    <input type="text" id="companySearch" placeholder="{{ __('lang.search') }} {{ __('lang.companies') }}..."
                        class="w-full px-4 py-3 text-gray-600 focus:outline-none"
                        onkeyup="filterCompanies()">
                </div>

                <!-- Companies List -->
                <ul id="companiesList" class="max-h-96 overflow-y-auto">
                    <!-- Loading state -->
                    <li class="text-center text-gray-500 py-8">
                        <i class="fas fa-spinner fa-spin text-2xl"></i>
                        <p class="mt-2">Loading companies...</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function openCompanyModal() {
    document.getElementById('companyModal').classList.remove('hidden');
    loadCompanies();
}

function closeCompanyModal() {
    document.getElementById('companyModal').classList.add('hidden');
}

function loadCompanies() {
    fetch('/api/companies')
        .then(response => response.json())
        .then(data => {
            const companiesList = document.getElementById('companiesList');

            if (data.companies && data.companies.length > 0) {
                companiesList.innerHTML = data.companies.map(company => `
                    <li class="border-b border-gray-200" data-name="${company.name.toLowerCase()}" data-display-name="${company.name}" data-phone="${company.phone}">
                        <input type="radio" id="company-${company.id}" name="company" value="${company.id}" class="hidden peer" />
                        <label for="company-${company.id}" class="flex items-center justify-between w-full p-4 text-gray-900 bg-white cursor-pointer hover:bg-gray-50 peer-checked:bg-blue-50 peer-checked:text-blue-600">
                            <div class="flex sm:gap-8 gap-2 w-full">
                                <div class="text-lg text-gray-500 font-semibold peer-checked:text-blue-600">${company.name}</div>
                                <div class="text-sm text-gray-500 mt-1">${company.phone}</div>
                            </div>
                            <svg class="w-5 h-5 ms-3 text-gray-400 peer-checked:text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                            </svg>
                        </label>
                    </li>
                `).join('');
            } else {
                companiesList.innerHTML = '<li class="text-center text-gray-500 py-8 border-b border-gray-200">No companies available</li>';
            }
        })
        .catch(error => {
            console.error('Error loading companies:', error);
            document.getElementById('companiesList').innerHTML = '<li class="text-center text-red-500 py-8">Error loading companies</li>';
        });
}

function filterCompanies() {
    const searchValue = document.getElementById('companySearch').value.toLowerCase();
    const companies = document.querySelectorAll('#companiesList li[data-name]');

    companies.forEach(company => {
        const name = company.getAttribute('data-name');
        const phone = company.getAttribute('data-phone');

        if (name.includes(searchValue) || phone.includes(searchValue)) {
            company.style.display = '';
        } else {
            company.style.display = 'none';
        }
    });
}

function selectCompany(companyId, companyName) {
    // Store selected company in localStorage
    localStorage.setItem('selectedCompanyId', companyId);
    localStorage.setItem('selectedCompanyName', companyName);

    // Also store in session via AJAX
    fetch('/api/select-company', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify({ company_id: companyId, company_name: companyName })
    })
    .then(response => response.json())
    .then(data => {
        // Close modal
        closeCompanyModal();

        // Reload page to show updated sidebar
        window.location.reload();
    })
    .catch(error => {
        console.error('Error selecting company:', error);
    });
}

// Attach event listener when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const openButton = document.getElementById('openCompanyModal');
    if (openButton) {
        openButton.addEventListener('click', openCompanyModal);
    }

    // Add event delegation for company selection
    document.getElementById('companiesList').addEventListener('change', function(e) {
        if (e.target.name === 'company') {
            const selectedLi = e.target.closest('li');
            const companyName = selectedLi.getAttribute('data-display-name');
            selectCompany(e.target.value, companyName);
        }
    });
});
</script>

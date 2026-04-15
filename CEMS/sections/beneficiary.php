<?php include_once __DIR__ . '/../db.php'; ?>

<section id="beneficiary-section" class="space-y-6">

    <!-- Premium Header with 3D Effect -->
    <div class="rounded-3xl p-6 relative overflow-hidden group"
         style="background: linear-gradient(135deg, #212529 0%, #343A40 50%, #495057 100%);">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-full blur-3xl -mr-20 -mt-20 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-gradient-to-tr from-emerald-500/20 to-teal-500/20 rounded-full blur-2xl -ml-10 -mb-10 animate-pulse delay-1000"></div>
        </div>
        <div class="absolute top-10 right-20 w-16 h-16 border border-white/10 rounded-2xl rotate-45 animate-float-slow"></div>
        <div class="absolute bottom-10 left-20 w-12 h-12 border border-white/10 rounded-full animate-float"></div>
        <div class="absolute top-20 left-40 w-8 h-8 bg-white/5 rounded-lg rotate-12 animate-float-delayed"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-xs text-white/40 uppercase tracking-[0.2em] font-light">COMMUNITY MANAGEMENT</span>
                <span class="w-1 h-1 rounded-full bg-blue-400 animate-pulse"></span>
                <span class="text-xs bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-white/80 border border-white/20 flex items-center gap-1" id="headerCount">
                    <i class="ri-group-line text-blue-400"></i>0 Total
                </span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="relative">
                    <h1 class="text-4xl md:text-5xl font-bold mb-2">
                        <span class="text-white">Beneficiary</span>
                        <span class="text-white/40 mx-2">/</span>
                        <span class="text-white/60 text-2xl">Management</span>
                    </h1>
                    <p class="text-white/30 text-sm flex items-center gap-2">
                        <i class="ri-sparkling-line text-blue-400 animate-pulse"></i>
                        Manage and track all community beneficiaries
                    </p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportBeneficiaries()"
                            class="px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all group relative overflow-hidden">
                        <span class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        <i class="ri-download-line text-white/70 group-hover:text-white relative z-10"></i>
                        <span class="hidden md:inline relative z-10">Export CSV</span>
                    </button>
                    <button id="addBtn"
                            class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all shadow-lg shadow-blue-500/20 relative overflow-hidden group">
                        <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                        <i class="ri-add-line relative z-10"></i>
                        <span class="hidden md:inline relative z-10">Add Beneficiary</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="rounded-2xl p-5 relative overflow-hidden"
         style="background: #2A2F37; border: 1px solid rgba(255,255,255,0.05);">
        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-500/5 to-purple-500/5 rounded-bl-3xl"></div>
        <div class="flex flex-col md:flex-row gap-4 relative z-10">
            <div class="flex-1 relative">
                <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" placeholder="Search by name, occupation..."
                       class="w-full pl-12 pr-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:bg-black/30">
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <select id="genderFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none cursor-pointer min-w-[140px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Genders</option>
                        <option value="Male" class="bg-gray-800">Male</option>
                        <option value="Female" class="bg-gray-800">Female</option>
                        <option value="Other" class="bg-gray-800">Other</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <button id="clearFilters"
                        class="px-4 py-3 bg-black/20 hover:bg-black/30 border border-white/10 rounded-xl text-gray-400 hover:text-white transition-all">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="rounded-2xl overflow-hidden relative"
         style="background: #2A2F37; border: 1px solid rgba(255,255,255,0.05);">

        <!-- Loading State -->
        <div id="loadingState" class="text-center py-16">
            <div class="relative inline-block">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full blur-xl opacity-50 animate-pulse"></div>
                <div class="relative w-20 h-20 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center mb-4 mx-auto">
                    <i class="ri-loader-4-line animate-spin text-3xl text-white"></i>
                </div>
            </div>
            <p class="text-gray-400 font-medium mt-4">Loading beneficiaries...</p>
            <div class="flex justify-center gap-1 mt-2">
                <span class="w-1 h-1 bg-blue-400 rounded-full animate-bounce" style="animation-delay:0s"></span>
                <span class="w-1 h-1 bg-blue-400 rounded-full animate-bounce" style="animation-delay:0.1s"></span>
                <span class="w-1 h-1 bg-blue-400 rounded-full animate-bounce" style="animation-delay:0.2s"></span>
            </div>
        </div>

        <!-- Table -->
        <div id="tableWrapper" class="hidden overflow-x-auto">
            <table class="w-full">
                <thead style="background:#1E2228; border-bottom:1px solid rgba(255,255,255,0.05);">
                    <tr>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Name</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Age</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Gender</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Occupation</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Added</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="beneficiaryTable" style="background:#2A2F37;" class="divide-y divide-white/5"></tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-16 relative">
            <div class="absolute inset-0 overflow-hidden">
                <i class="ri-user-line absolute text-white/5 text-7xl top-10 left-10 rotate-12 animate-float-slow"></i>
                <i class="ri-user-search-line absolute text-white/5 text-7xl bottom-10 right-10 -rotate-12 animate-float-delayed"></i>
            </div>
            <div class="relative z-10">
                <div class="relative inline-block mb-4">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full blur-xl animate-pulse"></div>
                    <div class="relative w-24 h-24 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center mx-auto">
                        <i class="ri-user-search-line text-4xl text-white"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold animate-bounce">
                        <i class="ri-add-line"></i>
                    </div>
                </div>
                <h3 class="text-white text-xl font-semibold mb-2">No beneficiaries found</h3>
                <p class="text-gray-400 text-sm mb-6 max-w-sm mx-auto">Get started by adding your first beneficiary to the community</p>
                <button id="addFirstBtn"
                        class="px-8 py-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-xl font-medium transition-all shadow-lg inline-flex items-center gap-2">
                    <i class="ri-add-line"></i> Add Beneficiary
                </button>
            </div>
        </div>

        <!-- Table Footer -->
        <div id="tableFooter" class="hidden">
            <div class="h-1 bg-white/5 w-full">
                <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500" id="progressBar" style="width:0%"></div>
            </div>
            <div class="flex items-center justify-between px-6 py-4" style="background:#1E2228;">
                <div class="flex items-center gap-6 text-sm">
                    <span class="flex items-center gap-2 text-gray-400">
                        <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span>
                        Total: <b id="totalCount" class="text-white">0</b>
                    </span>
                    <span class="flex items-center gap-2 text-gray-400">
                        <span class="w-2.5 h-2.5 bg-blue-500 rounded-full"></span>
                        Male: <b id="maleCount" class="text-white">0</b>
                    </span>
                    <span class="flex items-center gap-2 text-gray-400">
                        <span class="w-2.5 h-2.5 bg-pink-500 rounded-full"></span>
                        Female: <b id="femaleCount" class="text-white">0</b>
                    </span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-gray-400">Rows:</span>
                    <select id="rowsPerPage" class="bg-black/20 border border-white/10 rounded-lg text-white px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <option value="5" class="bg-gray-800">5</option>
                        <option value="10" selected class="bg-gray-800">10</option>
                        <option value="25" class="bg-gray-800">25</option>
                        <option value="50" class="bg-gray-800">50</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-2">
        <div class="text-gray-400 text-sm flex items-center gap-2">
            <i class="ri-file-list-line"></i>
            Showing <span id="showingStart" class="font-medium text-white">0</span>
            to <span id="showingEnd" class="font-medium text-white">0</span>
            of <span id="totalRecords" class="font-medium text-white">0</span>
        </div>
        <div class="flex gap-2" id="pagination"></div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" id="modalOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md px-4">
            <div class="rounded-2xl shadow-2xl p-6 relative animate-slideIn overflow-hidden"
                 style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="absolute top-0 right-0 w-48 h-48 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-40 h-40 bg-gradient-to-tr from-emerald-500/10 to-teal-500/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl blur-md animate-pulse"></div>
                            <div class="relative w-12 h-12 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <i class="ri-user-add-line text-2xl text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent" id="modalTitle">Add Beneficiary</h2>
                            <p class="text-gray-400 text-xs">Fill in the beneficiary details</p>
                        </div>
                        <button id="closeModalBtn" class="ml-auto w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center border border-white/10 group transition-all">
                            <i class="ri-close-line text-gray-400 group-hover:text-white"></i>
                        </button>
                    </div>
                    <form id="beneficiaryForm" class="space-y-4">
                        <input type="hidden" id="beneficiaryId">
                        <div class="group/field">
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                                <i class="ri-user-line text-blue-400"></i> Full Name *
                            </label>
                            <div class="relative">
                                <i class="ri-user-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                                <input type="text" id="bName" placeholder="e.g., Juan Dela Cruz"
                                       class="w-full pl-10 pr-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:bg-black/30">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                                    <i class="ri-cake-line text-blue-400"></i> Age
                                </label>
                                <div class="relative">
                                    <i class="ri-cake-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                                    <input type="number" id="bAge" placeholder="Age" min="1" max="120"
                                           class="w-full pl-10 pr-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:bg-black/30">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                                    <i class="ri-user-settings-line text-blue-400"></i> Gender
                                </label>
                                <div class="relative">
                                    <i class="ri-user-settings-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                                    <select id="bGender" class="w-full pl-10 pr-8 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none hover:bg-black/30">
                                        <option value="" class="bg-gray-800">Select</option>
                                        <option value="Male" class="bg-gray-800">Male</option>
                                        <option value="Female" class="bg-gray-800">Female</option>
                                        <option value="Other" class="bg-gray-800">Other</option>
                                    </select>
                                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                                <i class="ri-briefcase-line text-blue-400"></i> Occupation
                            </label>
                            <div class="relative">
                                <i class="ri-briefcase-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                                <input type="text" id="bOccupation" placeholder="e.g., Farmer, Teacher"
                                       class="w-full pl-10 pr-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:bg-black/30">
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                            <button type="button" id="cancelBtn"
                                    class="px-6 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 rounded-xl text-white font-medium transition-all shadow-lg flex items-center gap-2 relative overflow-hidden group">
                                <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                                <i class="ri-save-line relative z-10"></i>
                                <span class="relative z-10">Save Beneficiary</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" id="deleteOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm px-4">
            <div class="rounded-2xl shadow-2xl p-6 text-center animate-slideIn relative overflow-hidden"
                 style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-500/10 to-pink-500/10 rounded-full blur-2xl"></div>
                <div class="relative mb-4">
                    <div class="absolute inset-0 bg-gradient-to-r from-red-500 to-pink-500 rounded-full blur-xl animate-pulse"></div>
                    <div class="relative w-20 h-20 mx-auto rounded-full bg-gradient-to-r from-red-500 to-pink-600 flex items-center justify-center">
                        <i class="ri-delete-bin-line text-4xl text-white"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center text-white text-xs font-bold animate-bounce">!</div>
                </div>
                <h3 class="text-white text-xl font-semibold mb-2">Delete Beneficiary?</h3>
                <p class="text-gray-400 text-sm mb-6">This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button id="cancelDeleteBtn" class="flex-1 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                    <button id="confirmDeleteBtn" class="flex-1 py-2.5 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 rounded-xl text-white font-medium transition-all shadow-lg relative overflow-hidden group">
                        <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                        <span class="relative z-10">Delete</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes float { 0%,100%{transform:translateY(0) rotate(0deg)} 50%{transform:translateY(-10px) rotate(2deg)} }
@keyframes float-slow { 0%,100%{transform:translateY(0) rotate(0deg)} 50%{transform:translateY(-15px) rotate(-2deg)} }
@keyframes float-delayed { 0%,100%{transform:translateY(0) scale(1)} 50%{transform:translateY(-8px) scale(1.05)} }
@keyframes slideIn { from{opacity:0;transform:translateY(-30px) scale(0.95)} to{opacity:1;transform:translateY(0) scale(1)} }
.animate-float { animation: float 4s ease-in-out infinite; }
.animate-float-slow { animation: float-slow 6s ease-in-out infinite; }
.animate-float-delayed { animation: float-delayed 5s ease-in-out infinite; }
.animate-slideIn { animation: slideIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
#beneficiaryTable tr { transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
#beneficiaryTable tr:hover { transform: translateY(-2px); background: rgba(255,255,255,0.05); box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
::-webkit-scrollbar { width:8px; height:8px; }
::-webkit-scrollbar-track { background:rgba(255,255,255,0.05); border-radius:10px; }
::-webkit-scrollbar-thumb { background: linear-gradient(135deg,#3B82F6,#8B5CF6); border-radius:10px; }
</style>

<script>
let beneficiaries = [];
let deleteId      = null;
let currentPage   = 1;
let rowsPerPage   = 10;

const API = 'api/beneficiary_api.php';

// ---- Load from DB ----
function loadBeneficiaries() {
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('tableWrapper').classList.add('hidden');
    document.getElementById('emptyState').classList.add('hidden');
    document.getElementById('tableFooter').classList.add('hidden');

    fetch(`${API}?action=get`)
        .then(r => r.json())
        .then(data => {
            beneficiaries = data;
            document.getElementById('loadingState').classList.add('hidden');
            renderTable();
        })
        .catch(() => {
            document.getElementById('loadingState').innerHTML =
                '<p class="text-red-400 py-8 text-center">Failed to load. Check API connection.</p>';
        });
}

// ---- Render ----
function renderTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const gender = document.getElementById('genderFilter').value;

    let filtered = beneficiaries.filter(b =>
        (b.name.toLowerCase().includes(search) || (b.occupation||'').toLowerCase().includes(search)) &&
        (!gender || b.gender === gender)
    );

    const total = filtered.length;
    document.getElementById('totalCount').textContent  = total;
    document.getElementById('maleCount').textContent   = filtered.filter(b => b.gender==='Male').length;
    document.getElementById('femaleCount').textContent = filtered.filter(b => b.gender==='Female').length;
    document.getElementById('headerCount').innerHTML   = `<i class="ri-group-line text-blue-400"></i> ${total} Total`;

    // Progress bar (male % of total)
    const malePct = total ? Math.round((filtered.filter(b=>b.gender==='Male').length/total)*100) : 0;
    const pb = document.getElementById('progressBar');
    if (pb) pb.style.width = malePct + '%';

    const totalPages = Math.max(1, Math.ceil(total / rowsPerPage));
    if (currentPage > totalPages) currentPage = totalPages;
    const start  = (currentPage - 1) * rowsPerPage;
    const paged  = filtered.slice(start, start + rowsPerPage);

    document.getElementById('showingStart').textContent  = total ? start + 1 : 0;
    document.getElementById('showingEnd').textContent    = Math.min(start + rowsPerPage, total);
    document.getElementById('totalRecords').textContent  = total;

    if (total === 0) {
        document.getElementById('tableWrapper').classList.add('hidden');
        document.getElementById('emptyState').classList.remove('hidden');
        document.getElementById('tableFooter').classList.add('hidden');
        document.getElementById('pagination').innerHTML = '';
        return;
    }

    document.getElementById('emptyState').classList.add('hidden');
    document.getElementById('tableWrapper').classList.remove('hidden');
    document.getElementById('tableFooter').classList.remove('hidden');

    document.getElementById('beneficiaryTable').innerHTML = paged.map(b => {
        const gClass = b.gender==='Male'
            ? 'bg-blue-500/20 text-blue-400'
            : b.gender==='Female'
                ? 'bg-pink-500/20 text-pink-400'
                : 'bg-purple-500/20 text-purple-400';
        const added = b.created_at
            ? new Date(b.created_at).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})
            : '—';
        return `
        <tr class="hover:bg-white/5 transition-all cursor-default">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500/20 to-purple-500/20 border border-white/10 flex items-center justify-center font-bold text-blue-400 text-sm flex-shrink-0">
                        ${b.name.charAt(0).toUpperCase()}
                    </div>
                    <span class="text-white font-medium">${b.name}</span>
                </div>
            </td>
            <td class="px-6 py-4 text-gray-400">${b.age||'—'}</td>
            <td class="px-6 py-4">
                <span class="px-2.5 py-1 rounded-full text-xs font-medium ${gClass}">${b.gender||'—'}</span>
            </td>
            <td class="px-6 py-4 text-gray-400">${b.occupation||'—'}</td>
            <td class="px-6 py-4 text-gray-500 text-sm">${added}</td>
            <td class="px-6 py-4">
                <div class="flex gap-2">
                    <button onclick="editBeneficiary(${b.id})"
                            class="w-8 h-8 rounded-lg bg-white/5 hover:bg-blue-500/20 border border-white/10 flex items-center justify-center transition-all group">
                        <i class="ri-edit-line text-gray-400 group-hover:text-blue-400"></i>
                    </button>
                    <button onclick="openDeleteModal(${b.id})"
                            class="w-8 h-8 rounded-lg bg-white/5 hover:bg-red-500/20 border border-white/10 flex items-center justify-center transition-all group">
                        <i class="ri-delete-bin-line text-gray-400 group-hover:text-red-400"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');

    // Pagination
    document.getElementById('pagination').innerHTML = `
        <button onclick="changePage(${currentPage-1})" ${currentPage===1?'disabled':''}
                class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 text-gray-400 hover:bg-white/10 hover:text-white disabled:opacity-30 disabled:cursor-not-allowed transition-all">
            <i class="ri-arrow-left-s-line"></i>
        </button>
        <span class="text-gray-400 text-sm px-3 self-center">Page ${currentPage} / ${totalPages}</span>
        <button onclick="changePage(${currentPage+1})" ${currentPage===totalPages?'disabled':''}
                class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 text-gray-400 hover:bg-white/10 hover:text-white disabled:opacity-30 disabled:cursor-not-allowed transition-all">
            <i class="ri-arrow-right-s-line"></i>
        </button>`;
}

function changePage(p) { currentPage = p; renderTable(); }

// ---- Modal helpers ----
function openModal(edit=false) {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = edit ? 'Edit Beneficiary' : 'Add Beneficiary';
}
function closeModal() {
    document.getElementById('modal').classList.add('hidden');
    document.getElementById('beneficiaryForm').reset();
    document.getElementById('beneficiaryId').value = '';
}
function openDeleteModal(id) { deleteId = id; document.getElementById('deleteModal').classList.remove('hidden'); }
function closeDeleteModal() { document.getElementById('deleteModal').classList.add('hidden'); deleteId = null; }

// ---- Edit ----
function editBeneficiary(id) {
    const b = beneficiaries.find(x => x.id == id);
    if (!b) return;
    document.getElementById('beneficiaryId').value = b.id;
    document.getElementById('bName').value        = b.name;
    document.getElementById('bAge').value         = b.age  || '';
    document.getElementById('bGender').value      = b.gender || '';
    document.getElementById('bOccupation').value  = b.occupation || '';
    openModal(true);
}

// ---- Form Submit ----
document.getElementById('beneficiaryForm').addEventListener('submit', e => {
    e.preventDefault();
    const id   = document.getElementById('beneficiaryId').value;
    const name = document.getElementById('bName').value.trim();
    if (!name) { alert('Name is required.'); return; }

    const fd = new FormData();
    fd.append('action',     id ? 'update' : 'save');
    if (id) fd.append('id', id);
    fd.append('name',       name);
    fd.append('age',        document.getElementById('bAge').value);
    fd.append('gender',     document.getElementById('bGender').value);
    fd.append('occupation', document.getElementById('bOccupation').value.trim());

    fetch(API, { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closeModal(); loadBeneficiaries(); }
            else alert('Error: ' + (data.message||'Unknown error'));
        })
        .catch(() => alert('Network error. Try again.'));
});

// ---- Delete ----
document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
    if (!deleteId) return;
    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('id', deleteId);
    fetch(API, { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closeDeleteModal(); loadBeneficiaries(); }
            else alert('Error: ' + (data.message||'Unknown error'));
        });
});

// ---- Export CSV ----
function exportBeneficiaries() {
    if (!beneficiaries.length) { alert('No data to export.'); return; }
    const rows = [['Name','Age','Gender','Occupation','Added']];
    beneficiaries.forEach(b => rows.push([b.name, b.age||'', b.gender||'', b.occupation||'', b.created_at||'']));
    const csv = rows.map(r => r.map(c=>`"${c}"`).join(',')).join('\n');
    Object.assign(document.createElement('a'),{href:'data:text/csv,'+encodeURIComponent(csv),download:'beneficiaries.csv'}).click();
}

// ---- Event Listeners ----
document.getElementById('addBtn').addEventListener('click', () => openModal());
document.getElementById('addFirstBtn')?.addEventListener('click', () => openModal());
document.getElementById('cancelBtn').addEventListener('click', closeModal);
document.getElementById('closeModalBtn').addEventListener('click', closeModal);
document.getElementById('cancelDeleteBtn').addEventListener('click', closeDeleteModal);
document.getElementById('modalOverlay').addEventListener('click', closeModal);
document.getElementById('deleteOverlay').addEventListener('click', closeDeleteModal);
document.getElementById('searchInput').addEventListener('input', () => { currentPage=1; renderTable(); });
document.getElementById('genderFilter').addEventListener('change', () => { currentPage=1; renderTable(); });
document.getElementById('clearFilters').addEventListener('click', () => {
    document.getElementById('searchInput').value = '';
    document.getElementById('genderFilter').value = '';
    currentPage=1; renderTable();
});
document.getElementById('rowsPerPage').addEventListener('change', e => {
    rowsPerPage = parseInt(e.target.value); currentPage=1; renderTable();
});
document.addEventListener('keydown', e => {
    if (e.key==='Escape') { closeModal(); closeDeleteModal(); }
});

loadBeneficiaries();
</script>
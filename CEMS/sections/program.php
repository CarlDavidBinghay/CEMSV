<?php include_once __DIR__ . '/../db.php'; ?>

<section id="program-section" class="space-y-6">

    <!-- Premium Header -->
    <div class="rounded-3xl p-6 relative overflow-hidden group"
         style="background: linear-gradient(135deg, #212529 0%, #343A40 50%, #495057 100%);">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-yellow-500/20 to-orange-500/20 rounded-full blur-3xl -mr-20 -mt-20 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-gradient-to-tr from-amber-500/20 to-yellow-500/20 rounded-full blur-2xl -ml-10 -mb-10 animate-pulse delay-1000"></div>
        </div>
        <div class="absolute top-10 right-20 w-16 h-16 border border-yellow-500/20 rounded-2xl rotate-45 animate-float-slow"></div>
        <div class="absolute bottom-10 left-20 w-12 h-12 border border-yellow-500/20 rounded-full animate-float"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.5) 1px, transparent 0); background-size: 40px 40px;"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-xs text-white/40 uppercase tracking-[0.2em] font-light">PROGRAM MANAGEMENT</span>
                <span class="w-1 h-1 rounded-full bg-yellow-400 animate-pulse"></span>
                <span class="text-xs bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-white/80 border border-white/20 flex items-center gap-1" id="programHeaderCount">
                    <i class="ri-archive-line text-yellow-400"></i> 0 Total
                </span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2 text-white">
                        Programs
                        <span class="text-white/40 mx-2">/</span>
                        <span class="text-white/60 text-2xl">Management</span>
                    </h1>
                    <p class="text-white/30 text-sm flex items-center gap-2">
                        <i class="ri-sparkling-line text-yellow-400 animate-pulse"></i>
                        Plan, track, and manage all community programs
                    </p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportPrograms()"
                            class="px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all">
                        <i class="ri-download-line text-yellow-400"></i>
                        <span class="hidden md:inline">Export CSV</span>
                    </button>
                    <button id="addProgramBtn"
                            class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all shadow-lg shadow-yellow-500/20 relative overflow-hidden group">
                        <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                        <i class="ri-add-line relative z-10"></i>
                        <span class="hidden md:inline relative z-10">Add Program</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-yellow-500/10 to-orange-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Programs</p>
                    <p class="text-3xl font-bold text-white mt-1" id="statTotalPrograms">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-500 to-orange-500 flex items-center justify-center shadow-lg">
                    <i class="ri-archive-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs">All registered programs</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-green-500/10 to-emerald-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Active Programs</p>
                    <p class="text-3xl font-bold text-white mt-1" id="statActivePrograms">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg">
                    <i class="ri-play-circle-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-green-400 text-xs">● Currently running</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Budget</p>
                    <p class="text-3xl font-bold text-white mt-1" id="statTotalBudget">₱0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg">
                    <i class="ri-coins-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-blue-400 text-xs">Combined budget</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Completed</p>
                    <p class="text-3xl font-bold text-white mt-1" id="statCompletedPrograms">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
                    <i class="ri-checkbox-circle-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-purple-400 text-xs">Finished programs</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="rounded-2xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="programSearch" placeholder="Search programs by name, goals..."
                       class="w-full pl-12 pr-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all hover:bg-black/30">
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <select id="programStatusFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 appearance-none cursor-pointer min-w-[140px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Status</option>
                        <option value="Active" class="bg-gray-800">Active</option>
                        <option value="Planning" class="bg-gray-800">Planning</option>
                        <option value="Completed" class="bg-gray-800">Completed</option>
                        <option value="On Hold" class="bg-gray-800">On Hold</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <button id="clearProgramFilters"
                        class="px-4 py-3 bg-black/20 hover:bg-black/30 border border-white/10 rounded-xl text-gray-400 hover:text-white transition-all">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="flex items-center justify-between px-2">
        <div class="flex items-center gap-2">
            <span class="text-white/60 text-sm">View:</span>
            <button id="tableViewBtn" class="px-3 py-1.5 bg-yellow-500/20 text-yellow-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all">
                <i class="ri-table-line"></i> Table
            </button>
            <button id="cardViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all">
                <i class="ri-layout-grid-line"></i> Cards
            </button>
        </div>
        <div class="text-white/40 text-sm"><span id="showingProgramsCount">0</span> programs displayed</div>
    </div>

    <!-- Table View -->
    <div id="tableView" class="rounded-2xl overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <!-- Loading -->
        <div id="programLoadingState" class="text-center py-12">
            <div class="relative inline-block">
                <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full blur-xl opacity-50 animate-pulse"></div>
                <div class="relative w-16 h-16 rounded-full bg-gradient-to-r from-yellow-500 to-orange-500 flex items-center justify-center mb-4 mx-auto">
                    <i class="ri-loader-4-line animate-spin text-2xl text-white"></i>
                </div>
            </div>
            <p class="text-gray-400 mt-4">Loading programs...</p>
        </div>

        <!-- Table -->
        <div id="programTableWrapper" class="hidden overflow-x-auto">
            <table class="w-full">
                <thead style="background:#1E2228; border-bottom:1px solid rgba(255,255,255,0.05);">
                    <tr>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Program</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Goals</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Duration</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Budget</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Beneficiaries</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="programTable" style="background:#2A2F37;" class="divide-y divide-white/5"></tbody>
            </table>
        </div>

        <!-- Empty -->
        <div id="programEmptyState" class="hidden text-center py-12">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-yellow-500/20 to-orange-500/20 flex items-center justify-center">
                <i class="ri-archive-drawer-line text-4xl text-yellow-400/50"></i>
            </div>
            <h3 class="text-white text-lg font-semibold mb-2">No programs found</h3>
            <p class="text-gray-400 text-sm mb-4">Get started by creating your first program</p>
            <button id="addFirstProgramBtn"
                    class="px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-600 text-white rounded-xl font-medium transition-all inline-flex items-center gap-2">
                <i class="ri-add-line"></i> Create Program
            </button>
        </div>

        <!-- Footer -->
        <div id="programTableFooter" class="hidden flex items-center justify-between px-6 py-4" style="background:#1E2228; border-top:1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center gap-4 text-sm">
                <span class="text-gray-400">Total: <b id="programTotalCount" class="text-white">0</b></span>
                <span class="text-gray-400">Active: <b id="programActiveCount" class="text-green-400">0</b></span>
                <span class="text-gray-400">Budget: <b id="programBudgetTotal" class="text-yellow-400">₱0</b></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-400 text-sm">Rows:</span>
                <select id="programRowsPerPage" class="bg-black/20 border border-white/10 rounded-lg text-white px-3 py-1.5 text-sm focus:outline-none">
                    <option value="5" class="bg-gray-800">5</option>
                    <option value="10" selected class="bg-gray-800">10</option>
                    <option value="25" class="bg-gray-800">25</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Card View -->
    <div id="cardView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-2">
        <div class="text-gray-400 text-sm">
            Showing <span id="programShowingStart">0</span> to <span id="programShowingEnd">0</span> of <span id="programTotalRecords">0</span>
        </div>
        <div class="flex gap-2" id="programPagination"></div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="programModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" id="programModalOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl px-4 max-h-screen overflow-y-auto py-6">
            <div class="rounded-2xl shadow-2xl p-6 relative animate-slideIn overflow-hidden"
                 style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-yellow-500/10 to-orange-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-amber-500/10 to-yellow-500/10 rounded-full blur-2xl"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl blur-md animate-pulse"></div>
                            <div class="relative w-14 h-14 rounded-xl bg-gradient-to-r from-yellow-500 to-orange-600 flex items-center justify-center">
                                <i class="ri-archive-line text-2xl text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent" id="programModalTitle">Add New Program</h2>
                            <p class="text-gray-400 text-sm">Fill in the program details below</p>
                        </div>
                        <button id="closeProgramModalBtn"
                                class="ml-auto w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center border border-white/10 transition-all">
                            <i class="ri-close-line text-gray-400"></i>
                        </button>
                    </div>

                    <form id="programForm" class="space-y-4">
                        <input type="hidden" id="programId">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                    <i class="ri-archive-line text-yellow-400 mr-1"></i> Program Name *
                                </label>
                                <input type="text" id="programName" placeholder="e.g., Community Health Initiative"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                    <i class="ri-price-tag-line text-yellow-400 mr-1"></i> Status
                                </label>
                                <select id="programStatus" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                    <option value="Planning" class="bg-gray-800">Planning</option>
                                    <option value="Active" class="bg-gray-800">Active</option>
                                    <option value="On Hold" class="bg-gray-800">On Hold</option>
                                    <option value="Completed" class="bg-gray-800">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                <i class="ri-file-text-line text-yellow-400 mr-1"></i> Description
                            </label>
                            <textarea id="programDesc" rows="3" placeholder="Describe the program objectives and scope..."
                                      class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 resize-none"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                    <i class="ri-flag-line text-yellow-400 mr-1"></i> Goals & Objectives
                                </label>
                                <input type="text" id="programGoals" placeholder="e.g., Reduce malnutrition by 50%"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                    <i class="ri-group-line text-yellow-400 mr-1"></i> Target Beneficiaries
                                </label>
                                <input type="text" id="programBeneficiaries" placeholder="e.g., Children, Elderly"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                    <i class="ri-calendar-line text-yellow-400 mr-1"></i> Start Date
                                </label>
                                <input type="date" id="programStartDate"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                    <i class="ri-calendar-check-line text-yellow-400 mr-1"></i> End Date
                                </label>
                                <input type="date" id="programEndDate"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                    <i class="ri-coins-line text-yellow-400 mr-1"></i> Budget (₱)
                                </label>
                                <input type="number" id="programBudget" placeholder="e.g., 100000"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                    <i class="ri-user-star-line text-yellow-400 mr-1"></i> Program Manager
                                </label>
                                <input type="text" id="programManager" placeholder="e.g., Dr. Maria Santos"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-all">
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                            <button type="button" id="cancelProgramBtn"
                                    class="px-6 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 rounded-xl text-white font-medium transition-all shadow-lg flex items-center gap-2 relative overflow-hidden group">
                                <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                                <i class="ri-save-line relative z-10"></i>
                                <span class="relative z-10">Save Program</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="programDeleteModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" id="programDeleteOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm px-4">
            <div class="rounded-2xl shadow-2xl p-6 text-center animate-slideIn"
                 style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-500/10 flex items-center justify-center">
                    <i class="ri-delete-bin-line text-3xl text-red-400"></i>
                </div>
                <h3 class="text-white text-lg font-semibold mb-2">Delete Program?</h3>
                <p class="text-gray-400 text-sm mb-6">This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button id="cancelProgramDeleteBtn" class="flex-1 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                    <button id="confirmProgramDeleteBtn" class="flex-1 py-2.5 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl text-white font-medium transition-all">Delete</button>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes float { 0%,100%{transform:translateY(0) rotate(0deg)} 50%{transform:translateY(-10px) rotate(2deg)} }
@keyframes float-slow { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-15px)} }
@keyframes float-delayed { 0%,100%{transform:translateY(0) scale(1)} 50%{transform:translateY(-8px) scale(1.05)} }
@keyframes slideIn { from{opacity:0;transform:translateY(-30px) scale(0.95)} to{opacity:1;transform:translateY(0) scale(1)} }
.animate-float { animation: float 4s ease-in-out infinite; }
.animate-float-slow { animation: float-slow 6s ease-in-out infinite; }
.animate-float-delayed { animation: float-delayed 5s ease-in-out infinite; }
.animate-slideIn { animation: slideIn 0.4s cubic-bezier(0.175,0.885,0.32,1.275) forwards; }
#programTable tr { transition: all 0.3s ease; }
#programTable tr:hover { background: rgba(255,255,255,0.05); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
</style>

<script>
let programs     = [];
let deleteProgId = null;
let currentPage  = 1;
let rowsPerPage  = 10;
let currentView  = 'table';

const API = 'api/program_api.php';

const statusColors = {
    'Active':    'bg-green-500/20 text-green-400 border border-green-500/30',
    'Planning':  'bg-blue-500/20 text-blue-400 border border-blue-500/30',
    'On Hold':   'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
    'Completed': 'bg-gray-500/20 text-gray-400 border border-gray-500/30'
};

// ---- Load from DB ----
function loadPrograms() {
    document.getElementById('programLoadingState').classList.remove('hidden');
    document.getElementById('programTableWrapper').classList.add('hidden');
    document.getElementById('programEmptyState').classList.add('hidden');
    document.getElementById('programTableFooter')?.classList.add('hidden');

    fetch(`${API}?action=get`)
        .then(r => r.json())
        .then(data => {
            programs = data;
            document.getElementById('programLoadingState').classList.add('hidden');
            updateStats();
            renderPrograms();
        })
        .catch(() => {
            document.getElementById('programLoadingState').innerHTML =
                '<p class="text-red-400 py-8 text-center">Failed to load. Check API connection.</p>';
        });
}

// ---- Stats ----
function updateStats() {
    const total     = programs.length;
    const active    = programs.filter(p => p.status === 'Active').length;
    const completed = programs.filter(p => p.status === 'Completed').length;
    const budget    = programs.reduce((s, p) => s + (parseFloat(p.budget) || 0), 0);

    document.getElementById('statTotalPrograms').textContent    = total;
    document.getElementById('statActivePrograms').textContent   = active;
    document.getElementById('statCompletedPrograms').textContent= completed;
    document.getElementById('statTotalBudget').textContent      = '₱' + budget.toLocaleString();
    document.getElementById('programHeaderCount').innerHTML     = `<i class="ri-archive-line text-yellow-400"></i> ${total} Total`;
}

// ---- Render ----
function renderPrograms() {
    const search = document.getElementById('programSearch').value.toLowerCase();
    const status = document.getElementById('programStatusFilter').value;

    let filtered = programs.filter(p =>
        (p.name.toLowerCase().includes(search) ||
         (p.goals||'').toLowerCase().includes(search) ||
         (p.target_beneficiaries||'').toLowerCase().includes(search)) &&
        (!status || p.status === status)
    );

    const total = filtered.length;
    document.getElementById('programTotalCount').textContent  = total;
    document.getElementById('programActiveCount').textContent = filtered.filter(p=>p.status==='Active').length;
    document.getElementById('programBudgetTotal').textContent = '₱' + filtered.reduce((s,p)=>s+(parseFloat(p.budget)||0),0).toLocaleString();
    document.getElementById('showingProgramsCount').textContent = total;

    const totalPages = Math.max(1, Math.ceil(total / rowsPerPage));
    if (currentPage > totalPages) currentPage = totalPages;
    const start = (currentPage-1) * rowsPerPage;
    const paged = filtered.slice(start, start+rowsPerPage);

    document.getElementById('programShowingStart').textContent = total ? start+1 : 0;
    document.getElementById('programShowingEnd').textContent   = Math.min(start+rowsPerPage, total);
    document.getElementById('programTotalRecords').textContent = total;

    if (total === 0) {
        document.getElementById('programTableWrapper').classList.add('hidden');
        document.getElementById('programEmptyState').classList.remove('hidden');
        document.getElementById('programTableFooter')?.classList.add('hidden');
        document.getElementById('cardView').innerHTML = '';
        document.getElementById('programPagination').innerHTML = '';
        return;
    }

    document.getElementById('programEmptyState').classList.add('hidden');
    document.getElementById('programTableFooter')?.classList.remove('hidden');

    if (currentView === 'table') {
        document.getElementById('programTableWrapper').classList.remove('hidden');
        renderTableView(paged);
    } else {
        document.getElementById('programTableWrapper').classList.add('hidden');
        renderCardView(paged);
    }

    renderPagination(total, totalPages);
}

function renderTableView(data) {
    document.getElementById('programTable').innerHTML = data.map(p => {
        const sc = statusColors[p.status] || 'bg-gray-500/20 text-gray-400';
        const duration = (p.start_date && p.end_date)
            ? `${p.start_date} → ${p.end_date}`
            : (p.duration || '—');
        return `
        <tr class="hover:bg-white/5 transition-all">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-yellow-500 to-orange-500 flex items-center justify-center text-white text-sm flex-shrink-0">
                        <i class="ri-archive-line"></i>
                    </div>
                    <div>
                        <span class="text-white font-medium">${p.name}</span>
                        <p class="text-gray-500 text-xs">${p.manager||'No manager'}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 text-gray-400 text-sm max-w-[200px] truncate">${p.goals||'—'}</td>
            <td class="px-6 py-4 text-gray-400 text-sm">${duration}</td>
            <td class="px-6 py-4"><span class="text-yellow-400 font-medium">₱${parseFloat(p.budget||0).toLocaleString()}</span></td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium ${sc}">${p.status||'—'}</span></td>
            <td class="px-6 py-4 text-gray-400 text-sm">${p.target_beneficiaries||'—'}</td>
            <td class="px-6 py-4">
                <div class="flex gap-2">
                    <button onclick="editProgram(${p.id})" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-yellow-500/20 border border-white/10 flex items-center justify-center transition-all group">
                        <i class="ri-edit-line text-gray-400 group-hover:text-yellow-400"></i>
                    </button>
                    <button onclick="openDeleteModal(${p.id})" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-red-500/20 border border-white/10 flex items-center justify-center transition-all group">
                        <i class="ri-delete-bin-line text-gray-400 group-hover:text-red-400"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderCardView(data) {
    document.getElementById('cardView').innerHTML = data.map(p => {
        const sc = statusColors[p.status] || 'bg-gray-500/20 text-gray-400';
        return `
        <div class="rounded-xl p-5 relative overflow-hidden hover:scale-105 transition-all duration-300"
             style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-500/10 to-orange-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-yellow-500 to-orange-500 flex items-center justify-center">
                    <i class="ri-archive-line text-white text-xl"></i>
                </div>
                <span class="px-2 py-1 rounded-full text-xs font-medium ${sc}">${p.status||'—'}</span>
            </div>
            <h3 class="text-white font-semibold text-lg mb-1">${p.name}</h3>
            <p class="text-gray-400 text-sm mb-3">${(p.goals||'').substring(0,60)}${(p.goals||'').length>60?'...':''}</p>
            <div class="space-y-1.5 mb-4">
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-group-line text-yellow-400"></i>${p.target_beneficiaries||'—'}</div>
                <div class="flex items-center gap-2 text-xs text-yellow-400 font-medium"><i class="ri-coins-line"></i>₱${parseFloat(p.budget||0).toLocaleString()}</div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-user-star-line text-yellow-400"></i>${p.manager||'No manager'}</div>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-white/10">
                <button onclick="editProgram(${p.id})" class="px-3 py-1.5 bg-white/5 hover:bg-yellow-500/20 rounded-lg text-gray-400 hover:text-yellow-400 text-sm transition-all">
                    <i class="ri-edit-line"></i> Edit
                </button>
                <button onclick="openDeleteModal(${p.id})" class="px-3 py-1.5 bg-white/5 hover:bg-red-500/20 rounded-lg text-gray-400 hover:text-red-400 text-sm transition-all">
                    <i class="ri-delete-bin-line"></i> Delete
                </button>
            </div>
        </div>`;
    }).join('');
}

function renderPagination(total, totalPages) {
    document.getElementById('programPagination').innerHTML = `
        <button onclick="changePage(${currentPage-1})" ${currentPage===1?'disabled':''}
                class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 text-gray-400 hover:bg-white/10 hover:text-white disabled:opacity-30 disabled:cursor-not-allowed transition-all">
            <i class="ri-arrow-left-s-line"></i>
        </button>
        <span class="text-gray-400 text-sm px-3 self-center">${currentPage} / ${totalPages}</span>
        <button onclick="changePage(${currentPage+1})" ${currentPage===totalPages?'disabled':''}
                class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 text-gray-400 hover:bg-white/10 hover:text-white disabled:opacity-30 disabled:cursor-not-allowed transition-all">
            <i class="ri-arrow-right-s-line"></i>
        </button>`;
}

function changePage(p) { currentPage = p; renderPrograms(); }

// ---- Modals ----
function openProgramModal(edit=false) {
    document.getElementById('programModal').classList.remove('hidden');
    document.getElementById('programModalTitle').textContent = edit ? 'Edit Program' : 'Add New Program';
}
function closeProgramModal() {
    document.getElementById('programModal').classList.add('hidden');
    document.getElementById('programForm').reset();
    document.getElementById('programId').value = '';
}
function openDeleteModal(id) { deleteProgId = id; document.getElementById('programDeleteModal').classList.remove('hidden'); }
function closeDeleteModal() { document.getElementById('programDeleteModal').classList.add('hidden'); deleteProgId = null; }

// ---- Edit ----
function editProgram(id) {
    const p = programs.find(x => x.id == id);
    if (!p) return;
    document.getElementById('programId').value           = p.id;
    document.getElementById('programName').value         = p.name;
    document.getElementById('programDesc').value         = p.description || '';
    document.getElementById('programGoals').value        = p.goals || '';
    document.getElementById('programBeneficiaries').value= p.target_beneficiaries || '';
    document.getElementById('programStartDate').value    = p.start_date || '';
    document.getElementById('programEndDate').value      = p.end_date || '';
    document.getElementById('programBudget').value       = p.budget || '';
    document.getElementById('programManager').value      = p.manager || '';
    document.getElementById('programStatus').value       = p.status || 'Planning';
    openProgramModal(true);
}

// ---- Form Submit ----
document.getElementById('programForm').addEventListener('submit', e => {
    e.preventDefault();
    const id   = document.getElementById('programId').value;
    const name = document.getElementById('programName').value.trim();
    if (!name) { alert('Program name is required.'); return; }

    const start = document.getElementById('programStartDate').value;
    const end   = document.getElementById('programEndDate').value;

    const fd = new FormData();
    fd.append('action',        id ? 'update' : 'save');
    if (id) fd.append('id', id);
    fd.append('name',          name);
    fd.append('description',   document.getElementById('programDesc').value.trim());
    fd.append('goals',         document.getElementById('programGoals').value.trim());
    fd.append('beneficiaries', document.getElementById('programBeneficiaries').value.trim());
    fd.append('duration',      start && end ? `${start} to ${end}` : '');
    fd.append('start_date',    start);
    fd.append('end_date',      end);
    fd.append('budget',        document.getElementById('programBudget').value || 0);
    fd.append('manager',       document.getElementById('programManager').value.trim());
    fd.append('status',        document.getElementById('programStatus').value);

    fetch(API, { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closeProgramModal(); loadPrograms(); }
            else alert('Error: ' + (data.message||'Unknown error'));
        })
        .catch(() => alert('Network error. Try again.'));
});

// ---- Delete ----
document.getElementById('confirmProgramDeleteBtn').addEventListener('click', () => {
    if (!deleteProgId) return;
    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('id', deleteProgId);
    fetch(API, { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closeDeleteModal(); loadPrograms(); }
            else alert('Error: ' + (data.message||'Unknown error'));
        });
});

// ---- Export ----
function exportPrograms() {
    if (!programs.length) { alert('No programs to export.'); return; }
    const rows = [['Name','Description','Goals','Beneficiaries','Duration','Budget','Manager','Status']];
    programs.forEach(p => rows.push([p.name, p.description||'', p.goals||'', p.target_beneficiaries||'',
        p.duration||'', p.budget||0, p.manager||'', p.status||'']));
    const csv = rows.map(r => r.map(c=>`"${c}"`).join(',')).join('\n');
    Object.assign(document.createElement('a'),{href:'data:text/csv,'+encodeURIComponent(csv),download:'programs.csv'}).click();
}

// ---- Event Listeners ----
document.getElementById('addProgramBtn').addEventListener('click', () => openProgramModal());
document.getElementById('addFirstProgramBtn')?.addEventListener('click', () => openProgramModal());
document.getElementById('cancelProgramBtn').addEventListener('click', closeProgramModal);
document.getElementById('closeProgramModalBtn').addEventListener('click', closeProgramModal);
document.getElementById('programModalOverlay').addEventListener('click', closeProgramModal);
document.getElementById('cancelProgramDeleteBtn').addEventListener('click', closeDeleteModal);
document.getElementById('programDeleteOverlay').addEventListener('click', closeDeleteModal);
document.getElementById('programSearch').addEventListener('input', () => { currentPage=1; renderPrograms(); });
document.getElementById('programStatusFilter').addEventListener('change', () => { currentPage=1; renderPrograms(); });
document.getElementById('clearProgramFilters').addEventListener('click', () => {
    document.getElementById('programSearch').value = '';
    document.getElementById('programStatusFilter').value = '';
    currentPage=1; renderPrograms();
});
document.getElementById('programRowsPerPage').addEventListener('change', e => {
    rowsPerPage = parseInt(e.target.value); currentPage=1; renderPrograms();
});

// View toggle
document.getElementById('tableViewBtn').addEventListener('click', () => {
    currentView = 'table';
    document.getElementById('tableView').classList.remove('hidden');
    document.getElementById('cardView').classList.add('hidden');
    document.getElementById('tableViewBtn').className = 'px-3 py-1.5 bg-yellow-500/20 text-yellow-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    document.getElementById('cardViewBtn').className  = 'px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    renderPrograms();
});
document.getElementById('cardViewBtn').addEventListener('click', () => {
    currentView = 'cards';
    document.getElementById('tableView').classList.add('hidden');
    document.getElementById('cardView').classList.remove('hidden');
    document.getElementById('cardViewBtn').className  = 'px-3 py-1.5 bg-yellow-500/20 text-yellow-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    document.getElementById('tableViewBtn').className = 'px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    renderPrograms();
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeProgramModal(); closeDeleteModal(); }
});

loadPrograms();
</script>
<?php include_once __DIR__ . '/../db.php'; ?>

<section id="staff-section" class="space-y-6">

    <!-- Premium Header -->
    <div class="rounded-3xl p-6 relative overflow-hidden group"
         style="background: linear-gradient(135deg, #212529 0%, #343A40 50%, #495057 100%);">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-cyan-500/20 to-blue-500/20 rounded-full blur-3xl -mr-20 -mt-20 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-gradient-to-tr from-sky-500/20 to-cyan-500/20 rounded-full blur-2xl -ml-10 -mb-10 animate-pulse delay-1000"></div>
        </div>
        <div class="absolute top-10 right-20 w-16 h-16 border border-cyan-500/20 rounded-2xl rotate-45 animate-float-slow"></div>
        <div class="absolute bottom-10 left-20 w-12 h-12 border border-cyan-500/20 rounded-full animate-float"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.5) 1px, transparent 0); background-size: 40px 40px;"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-xs text-white/40 uppercase tracking-[0.2em] font-light">TEAM MANAGEMENT</span>
                <span class="w-1 h-1 rounded-full bg-cyan-400 animate-pulse"></span>
                <span class="text-xs bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-white/80 border border-white/20 flex items-center gap-1" id="staffHeaderCount">
                    <i class="ri-team-line text-cyan-400"></i> 0 Total
                </span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2 text-white">
                        Staff & Volunteers
                        <span class="text-white/40 mx-2">/</span>
                        <span class="text-white/60 text-2xl">Management</span>
                    </h1>
                    <p class="text-white/30 text-sm flex items-center gap-2">
                        <i class="ri-sparkling-line text-cyan-400 animate-pulse"></i>
                        Manage coordinators, staff, and volunteers
                    </p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportStaff()"
                            class="px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all">
                        <i class="ri-download-line text-cyan-400"></i>
                        <span class="hidden md:inline">Export CSV</span>
                    </button>
                    <button id="addStaffBtn"
                            class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all shadow-lg shadow-cyan-500/20 relative overflow-hidden group">
                        <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                        <i class="ri-add-line relative z-10"></i>
                        <span class="hidden md:inline relative z-10">Add Team Member</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-cyan-500/10 to-blue-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Team</p>
                <p class="text-3xl font-bold text-white mt-1" id="statTotalTeam">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-lg">
                    <i class="ri-team-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs">All registered members</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-red-500/10 to-pink-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Admins</p>
                <p class="text-3xl font-bold text-white mt-1" id="statAdmins">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-red-500 to-pink-500 flex items-center justify-center shadow-lg">
                    <i class="ri-shield-user-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-red-400 text-xs">Developer & Director</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-purple-500/10 to-indigo-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Coordinators</p>
                <p class="text-3xl font-bold text-white mt-1" id="statCoordinators">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center shadow-lg">
                    <i class="ri-user-star-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-purple-400 text-xs">Dept. Coordinators</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-green-500/10 to-emerald-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Staff/Volunteers</p>
                <p class="text-3xl font-bold text-white mt-1" id="statStaffVolunteers">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg">
                    <i class="ri-user-3-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-green-400 text-xs">Staff, Students & Others</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="rounded-2xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="staffSearch" placeholder="Search by name, role, assignments..."
                       class="w-full pl-12 pr-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all hover:bg-black/30">
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <select id="roleFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500 appearance-none cursor-pointer min-w-[150px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Roles</option>
                        <option value="Developer" class="bg-gray-800">Developer</option>
                        <option value="Director" class="bg-gray-800">Director</option>
                        <option value="Dept. Coordinator" class="bg-gray-800">Dept. Coordinator</option>
                        <option value="Teaching Staff" class="bg-gray-800">Teaching Staff</option>
                        <option value="Non-Teaching Staff" class="bg-gray-800">Non-Teaching Staff</option>
                        <option value="Student" class="bg-gray-800">Student</option>
                        <option value="Donor/Partner" class="bg-gray-800">Donor/Partner</option>
                        <option value="Beneficiary" class="bg-gray-800">Beneficiary</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <div class="relative">
                    <select id="performanceFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500 appearance-none cursor-pointer min-w-[160px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Performance</option>
                        <option value="High" class="bg-gray-800">High</option>
                        <option value="Medium" class="bg-gray-800">Medium</option>
                        <option value="Low" class="bg-gray-800">Low</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <button id="clearStaffFilters"
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
            <button id="staffTableViewBtn" class="px-3 py-1.5 bg-cyan-500/20 text-cyan-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-table-line"></i> Table</button>
            <button id="staffCardViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-layout-grid-line"></i> Cards</button>
            <button id="staffKanbanViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-kanban-view"></i> Kanban</button>
        </div>
        <div class="text-white/40 text-sm"><span id="showingStaffCount">0</span> members</div>
    </div>

    <!-- Table View -->
    <div id="staffTableView" class="rounded-2xl overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div id="staffLoadingState" class="text-center py-12">
            <div class="relative inline-block">
                <div class="absolute inset-0 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full blur-xl opacity-50 animate-pulse"></div>
                <div class="relative w-16 h-16 rounded-full bg-gradient-to-r from-cyan-500 to-blue-500 flex items-center justify-center mb-4 mx-auto">
                    <i class="ri-loader-4-line animate-spin text-2xl text-white"></i>
                </div>
            </div>
            <p class="text-gray-400 mt-4">Loading team members...</p>
        </div>
        <div id="staffTableWrapper" class="hidden overflow-x-auto">
            <table class="w-full">
                <thead style="background:#1E2228; border-bottom:1px solid rgba(255,255,255,0.05);">
                    <tr>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Member</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Role</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Assignments</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Performance</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Training</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="staffTable" style="background:#2A2F37;" class="divide-y divide-white/5"></tbody>
            </table>
        </div>
        <div id="staffEmptyState" class="hidden text-center py-12">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-cyan-500/20 to-blue-500/20 flex items-center justify-center">
                <i class="ri-team-line text-4xl text-cyan-400/50"></i>
            </div>
            <h3 class="text-white text-lg font-semibold mb-2">No team members found</h3>
            <p class="text-gray-400 text-sm mb-4">Get started by adding your first team member</p>
            <button id="addFirstStaffBtn" class="px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-xl font-medium transition-all inline-flex items-center gap-2">
                <i class="ri-add-line"></i> Add Team Member
            </button>
        </div>
        <div id="staffTableFooter" class="hidden flex items-center justify-between px-6 py-4" style="background:#1E2228; border-top:1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center gap-4 text-sm">
                <span class="text-gray-400">Total: <b id="staffTotalCount" class="text-white">0</b></span>
                <span class="text-gray-400">Active: <b id="staffActiveCount" class="text-green-400">0</b></span>
                <span class="text-gray-400">On Leave: <b id="staffOnLeaveCount" class="text-yellow-400">0</b></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-400 text-sm">Rows:</span>
                <select id="staffRowsPerPage" class="bg-black/20 border border-white/10 rounded-lg text-white px-3 py-1.5 text-sm focus:outline-none">
                    <option value="5" class="bg-gray-800">5</option>
                    <option value="10" selected class="bg-gray-800">10</option>
                    <option value="25" class="bg-gray-800">25</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Card View -->
    <div id="staffCardView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>

    <!-- Kanban View -->
    <div id="staffKanbanView" class="hidden grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-xl p-4" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-white font-semibold flex items-center gap-2"><span class="w-2 h-2 bg-red-400 rounded-full"></span>Developer & Director</h3>
                <span class="text-xs bg-white/5 text-gray-400 px-2 py-1 rounded-full" id="kanbanAdminsCount">0</span>
            </div>
            <div id="kanbanAdmins" class="space-y-3 min-h-[200px]"></div>
        </div>
        <div class="rounded-xl p-4" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-white font-semibold flex items-center gap-2"><span class="w-2 h-2 bg-purple-400 rounded-full"></span>Dept. Coordinator</h3>
                <span class="text-xs bg-white/5 text-gray-400 px-2 py-1 rounded-full" id="kanbanCoordinatorsCount">0</span>
            </div>
            <div id="kanbanCoordinators" class="space-y-3 min-h-[200px]"></div>
        </div>
        <div class="rounded-xl p-4" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-white font-semibold flex items-center gap-2"><span class="w-2 h-2 bg-green-400 rounded-full"></span>Staff, Students & Others</h3>
                <span class="text-xs bg-white/5 text-gray-400 px-2 py-1 rounded-full" id="kanbanStaffCount">0</span>
            </div>
            <div id="kanbanStaff" class="space-y-3 min-h-[200px]"></div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-2">
        <div class="text-gray-400 text-sm">
            Showing <span id="staffShowingStart">0</span> to <span id="staffShowingEnd">0</span> of <span id="staffTotalRecords">0</span>
        </div>
        <div class="flex gap-2" id="staffPagination"></div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="staffModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" id="staffModalOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl px-4 max-h-screen overflow-y-auto py-6">
            <div class="rounded-2xl shadow-2xl p-6 relative animate-slideIn overflow-hidden"
                 style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-cyan-500/10 to-blue-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-sky-500/10 to-cyan-500/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl blur-md animate-pulse"></div>
                            <div class="relative w-14 h-14 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center">
                                <i class="ri-user-add-line text-2xl text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent" id="staffModalTitle">Add Team Member</h2>
                            <p class="text-gray-400 text-sm">Enter team member information below</p>
                        </div>
                        <button id="closeStaffModalBtn" class="ml-auto w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center border border-white/10 transition-all">
                            <i class="ri-close-line text-gray-400"></i>
                        </button>
                    </div>
                    <form id="staffForm" class="space-y-4">
                        <input type="hidden" id="staffId">

                        <!-- Basic Info -->
                        <h3 class="text-white text-sm font-semibold flex items-center gap-2"><i class="ri-information-line text-cyan-400"></i> Basic Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-user-line text-cyan-400 mr-1"></i> Full Name *</label>
                                <input type="text" id="staffName" placeholder="e.g., Juan Dela Cruz"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all">
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-mail-line text-cyan-400 mr-1"></i> Email Address</label>
                                <input type="email" id="staffEmail" placeholder="juan@example.com"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-phone-line text-cyan-400 mr-1"></i> Phone Number</label>
                                <input type="text" id="staffPhone" placeholder="+63 912 345 6789"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-calendar-line text-cyan-400 mr-1"></i> Join Date</label>
                                <input type="date" id="staffJoinDate"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-price-tag-3-line text-cyan-400 mr-1"></i> Role * <span class="text-cyan-400 text-xs">(editable)</span></label>
                                <select id="staffRole" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
                                    <option value="" class="bg-gray-800">Select Role</option>
                                    <optgroup label="Admin Access" style="color:#6b7280;">
                                        <option value="Developer" class="bg-gray-800">Developer (Admin Access)</option>
                                        <option value="Director" class="bg-gray-800">Director (Admin Access)</option>
                                    </optgroup>
                                    <optgroup label="Staff" style="color:#6b7280;">
                                        <option value="Dept. Coordinator" class="bg-gray-800">Dept. Coordinator</option>
                                        <option value="Teaching Staff" class="bg-gray-800">Teaching Staff</option>
                                        <option value="Non-Teaching Staff" class="bg-gray-800">Non-Teaching Staff</option>
                                    </optgroup>
                                    <optgroup label="Others" style="color:#6b7280;">
                                        <option value="Student" class="bg-gray-800">Student</option>
                                        <option value="Donor/Partner" class="bg-gray-800">Donor/Partner</option>
                                        <option value="Beneficiary" class="bg-gray-800">Beneficiary</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-flag-line text-cyan-400 mr-1"></i> Status</label>
                                <select id="staffStatus" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
                                    <option value="Active" class="bg-gray-800">Active</option>
                                    <option value="On Leave" class="bg-gray-800">On Leave</option>
                                    <option value="Inactive" class="bg-gray-800">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Work Info -->
                        <div class="pt-4 border-t border-white/10">
                            <h3 class="text-white text-sm font-semibold flex items-center gap-2 mb-4"><i class="ri-briefcase-line text-cyan-400"></i> Work Information</h3>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-folder-2-line text-cyan-400 mr-1"></i> Assigned Programs/Projects</label>
                                <input type="text" id="staffAssignments" placeholder="e.g., Health Program, Education Project"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all">
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-bar-chart-2-line text-cyan-400 mr-1"></i> Performance Metrics</label>
                                    <input type="text" id="staffMetrics" placeholder="e.g., 95% completion rate"
                                           class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-award-line text-cyan-400 mr-1"></i> Performance Rating</label>
                                    <select id="staffPerformance" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
                                        <option value="High" class="bg-gray-800">High</option>
                                        <option value="Medium" class="bg-gray-800">Medium</option>
                                        <option value="Low" class="bg-gray-800">Low</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-medal-line text-cyan-400 mr-1"></i> Training / Certifications</label>
                                <input type="text" id="staffTraining" placeholder="e.g., CPR, Leadership Training, First Aid"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all">
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="pt-4 border-t border-white/10">
                            <h3 class="text-white text-sm font-semibold flex items-center gap-2 mb-4"><i class="ri-shield-user-line text-cyan-400"></i> Emergency Contact</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Contact Person</label>
                                    <input type="text" id="staffEmergencyContact" placeholder="e.g., Maria Dela Cruz"
                                           class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Emergency Phone</label>
                                    <input type="text" id="staffEmergencyPhone" placeholder="+63 912 345 6789"
                                           class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                            <button type="button" id="cancelStaffBtn" class="px-6 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 rounded-xl text-white font-medium transition-all shadow-lg flex items-center gap-2 relative overflow-hidden group">
                                <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                                <i class="ri-save-line relative z-10"></i>
                                <span class="relative z-10">Save Team Member</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="staffDeleteModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" id="staffDeleteOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm px-4">
            <div class="rounded-2xl shadow-2xl p-6 text-center animate-slideIn" style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-500/10 flex items-center justify-center">
                    <i class="ri-delete-bin-line text-3xl text-red-400"></i>
                </div>
                <h3 class="text-white text-lg font-semibold mb-2">Delete Team Member?</h3>
                <p class="text-gray-400 text-sm mb-6">This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button id="cancelStaffDeleteBtn" class="flex-1 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                    <button id="confirmStaffDeleteBtn" class="flex-1 py-2.5 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl text-white font-medium transition-all">Delete</button>
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
#staffTable tr { transition: all 0.3s ease; }
#staffTable tr:hover { background:rgba(255,255,255,0.05); transform:translateY(-2px); box-shadow:0 5px 15px rgba(0,0,0,0.3); }
.kanban-card { background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.05); border-radius:12px; padding:1rem; transition:all 0.3s ease; cursor:pointer; }
.kanban-card:hover { transform:translateY(-2px); background:rgba(255,255,255,0.05); border-color:rgba(6,182,212,0.3); }
</style>

<script>
let staffList   = [];
let deleteId    = null;
let currentPage = 1;
let rowsPerPage = 10;
let currentView = 'table';

const API = 'api/staff.api.php';

const roleBadge = {
    'Developer':         'bg-red-500/20 text-red-400 border border-red-500/30',
    'Director':          'bg-orange-500/20 text-orange-400 border border-orange-500/30',
    'Dept. Coordinator': 'bg-purple-500/20 text-purple-400 border border-purple-500/30',
    'Teaching Staff':    'bg-blue-500/20 text-blue-400 border border-blue-500/30',
    'Non-Teaching Staff':'bg-indigo-500/20 text-indigo-400 border border-indigo-500/30',
    'Student':           'bg-cyan-500/20 text-cyan-400 border border-cyan-500/30',
    'Donor/Partner':     'bg-teal-500/20 text-teal-400 border border-teal-500/30',
    'Beneficiary':       'bg-green-500/20 text-green-400 border border-green-500/30'
};
const perfBadge = {
    'High':   'bg-green-500/20 text-green-400',
    'Medium': 'bg-yellow-500/20 text-yellow-400',
    'Low':    'bg-red-500/20 text-red-400'
};
const statusBadge = {
    'Active':   'bg-green-500/20 text-green-400',
    'On Leave': 'bg-yellow-500/20 text-yellow-400',
    'Inactive': 'bg-gray-500/20 text-gray-400'
};

// ---- Load ----
function loadStaff() {
    document.getElementById('staffLoadingState').classList.remove('hidden');
    document.getElementById('staffTableWrapper').classList.add('hidden');
    document.getElementById('staffEmptyState').classList.add('hidden');
    document.getElementById('staffTableFooter')?.classList.add('hidden');

    fetch(`${API}?action=get_staff`)
        .then(r => r.json())
        .then(data => {
            staffList = data.map(s => ({
                id:               s.id,
                name:             s.fullname,
                email:            s.email || '',
                role:             s.role,
                phone:            s.phone || '',
                joinDate:         s.join_date || '',
                status:           s.status || 'Active',
                assignments:      s.assignments || '',
                metrics:          s.metrics || '',
                performance:      s.performance || 'Medium',
                training:         s.training || '',
                emergencyContact: s.emergency_contact || '',
                emergencyPhone:   s.emergency_phone || ''
            }));
            document.getElementById('staffLoadingState').classList.add('hidden');
            updateStats();
            renderStaff();
        })
        .catch(() => {
            document.getElementById('staffLoadingState').innerHTML =
                '<p class="text-red-400 py-8 text-center">Failed to load. Check API connection.</p>';
        });
}

const ADMIN_ROLES = ['developer','director'];
const COORD_ROLES = ['dept. coordinator'];
const FIELD_ROLES = ['teaching staff','non-teaching staff','student','donor/partner','beneficiary'];

function updateStats() {
    const total = staffList.length;
    document.getElementById('statTotalTeam').textContent       = total;
    document.getElementById('statAdmins').textContent          = staffList.filter(s=>ADMIN_ROLES.includes(s.role.toLowerCase())).length;
    document.getElementById('statCoordinators').textContent    = staffList.filter(s=>COORD_ROLES.includes(s.role.toLowerCase())).length;
    document.getElementById('statStaffVolunteers').textContent = staffList.filter(s=>FIELD_ROLES.includes(s.role.toLowerCase())).length;
    document.getElementById('staffHeaderCount').innerHTML      = `<i class="ri-team-line text-cyan-400"></i> ${total} Total`;
}

function renderStaff() {
    const search  = document.getElementById('staffSearch').value.toLowerCase();
    const role    = document.getElementById('roleFilter').value;
    const perf    = document.getElementById('performanceFilter').value;

    let filtered = staffList.filter(s =>
        (s.name.toLowerCase().includes(search) ||
         s.role.toLowerCase().includes(search) ||
         s.assignments.toLowerCase().includes(search)) &&
        (!role || s.role.toLowerCase() === role.toLowerCase()) &&
        (!perf || s.performance === perf)
    );

    const total = filtered.length;
    document.getElementById('staffTotalCount').textContent    = total;
    document.getElementById('staffActiveCount').textContent   = filtered.filter(s=>s.status==='Active').length;
    document.getElementById('staffOnLeaveCount').textContent  = filtered.filter(s=>s.status==='On Leave').length;
    document.getElementById('showingStaffCount').textContent  = total;

    const totalPages = Math.max(1, Math.ceil(total/rowsPerPage));
    if (currentPage > totalPages) currentPage = totalPages;
    const start = (currentPage-1)*rowsPerPage;
    const paged = filtered.slice(start, start+rowsPerPage);

    document.getElementById('staffShowingStart').textContent = total ? start+1 : 0;
    document.getElementById('staffShowingEnd').textContent   = Math.min(start+rowsPerPage, total);
    document.getElementById('staffTotalRecords').textContent = total;

    if (total === 0) {
        document.getElementById('staffTableWrapper').classList.add('hidden');
        document.getElementById('staffEmptyState').classList.remove('hidden');
        document.getElementById('staffTableFooter')?.classList.add('hidden');
        document.getElementById('staffCardView').innerHTML = '';
        document.getElementById('staffPagination').innerHTML = '';
        return;
    }

    document.getElementById('staffEmptyState').classList.add('hidden');
    document.getElementById('staffTableFooter')?.classList.remove('hidden');

    if (currentView === 'table') {
        document.getElementById('staffTableWrapper').classList.remove('hidden');
        renderTableView(paged);
    } else if (currentView === 'cards') {
        document.getElementById('staffTableWrapper').classList.add('hidden');
        renderCardView(paged);
    } else {
        document.getElementById('staffTableWrapper').classList.add('hidden');
        renderKanbanView(filtered);
    }

    renderPagination(total, totalPages);
}

function getRoleBadge(role) {
    const r = role || '';
    const key = Object.keys(roleBadge).find(k => k.toLowerCase() === r.toLowerCase());
    return roleBadge[key] || 'bg-gray-500/20 text-gray-400';
}

function renderTableView(data) {
    document.getElementById('staffTable').innerHTML = data.map(s => {
        const rb = getRoleBadge(s.role);
        const pb = perfBadge[s.performance]  || 'bg-gray-500/20 text-gray-400';
        const sb = statusBadge[s.status]     || 'bg-gray-500/20 text-gray-400';
        return `
        <tr class="hover:bg-white/5 transition-all">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        ${s.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <span class="text-white font-medium">${s.name}</span>
                        <p class="text-gray-500 text-xs">${s.email||'No email'}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium ${rb}">${s.role||'—'}</span></td>
            <td class="px-6 py-4 text-gray-400 text-sm max-w-[180px] truncate">${s.assignments||'—'}</td>
            <td class="px-6 py-4">
                <div class="space-y-1">
                    <p class="text-gray-400 text-xs">${s.metrics||'—'}</p>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium ${pb}">${s.performance||'—'}</span>
                </div>
            </td>
            <td class="px-6 py-4 text-gray-400 text-sm max-w-[150px] truncate">${s.training||'—'}</td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium ${sb}">${s.status||'—'}</span></td>
            <td class="px-6 py-4">
                <div class="flex gap-2">
                    <button onclick="editStaff(${s.id})" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-cyan-500/20 border border-white/10 flex items-center justify-center transition-all group">
                        <i class="ri-edit-line text-gray-400 group-hover:text-cyan-400"></i>
                    </button>
                    <button onclick="openDeleteModal(${s.id})" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-red-500/20 border border-white/10 flex items-center justify-center transition-all group">
                        <i class="ri-delete-bin-line text-gray-400 group-hover:text-red-400"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderCardView(data) {
    document.getElementById('staffCardView').innerHTML = data.map(s => {
        const rb = getRoleBadge(s.role);
        const pb = perfBadge[s.performance] || 'bg-gray-500/20 text-gray-400';
        const sb = statusBadge[s.status]    || 'bg-gray-500/20 text-gray-400';
        return `
        <div class="rounded-xl p-5 relative overflow-hidden hover:scale-105 transition-all duration-300"
             style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-cyan-500/10 to-blue-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center text-white font-bold text-lg">
                        ${s.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <h3 class="text-white font-semibold">${s.name}</h3>
                        <p class="text-gray-400 text-xs">${s.email||'No email'}</p>
                    </div>
                </div>
                <span class="px-2 py-1 rounded-full text-xs font-medium ${sb}">${s.status||'—'}</span>
            </div>
            <div class="space-y-1.5 mb-4">
                <div class="flex items-center gap-2 text-xs"><i class="ri-price-tag-3-line text-cyan-400"></i><span class="px-2 py-0.5 rounded-full text-xs font-medium ${rb}">${s.role||'—'}</span></div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-phone-line text-cyan-400"></i>${s.phone||'No phone'}</div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-folder-2-line text-cyan-400"></i>${s.assignments||'No assignments'}</div>
                <div class="flex items-center gap-2 text-xs"><i class="ri-bar-chart-2-line text-cyan-400"></i><span class="px-2 py-0.5 rounded-full text-xs font-medium ${pb}">${s.performance||'—'}</span></div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-medal-line text-cyan-400"></i>${s.training||'No certs'}</div>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-white/10">
                <button onclick="editStaff(${s.id})" class="px-3 py-1.5 bg-white/5 hover:bg-cyan-500/20 rounded-lg text-gray-400 hover:text-cyan-400 text-sm transition-all"><i class="ri-edit-line"></i> Edit</button>
                <button onclick="openDeleteModal(${s.id})" class="px-3 py-1.5 bg-white/5 hover:bg-red-500/20 rounded-lg text-gray-400 hover:text-red-400 text-sm transition-all"><i class="ri-delete-bin-line"></i> Delete</button>
            </div>
        </div>`;
    }).join('');
}

function renderKanbanView(data) {
    const admins  = data.filter(s => ADMIN_ROLES.includes(s.role.toLowerCase()));
    const coords  = data.filter(s => COORD_ROLES.includes(s.role.toLowerCase()));
    const others  = data.filter(s => FIELD_ROLES.includes(s.role.toLowerCase()));
    document.getElementById('kanbanAdminsCount').textContent      = admins.length;
    document.getElementById('kanbanCoordinatorsCount').textContent= coords.length;
    document.getElementById('kanbanStaffCount').textContent       = others.length;

    const card = arr => arr.map(s => `
        <div class="kanban-card" onclick="editStaff(${s.id})">
            <div class="flex items-start gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    ${s.name.charAt(0).toUpperCase()}
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-white font-medium text-sm truncate">${s.name}</h4>
                    <p class="text-gray-400 text-xs">${s.role}</p>
                </div>
                <span class="text-xs ${s.status==='Active'?'text-green-400':'text-yellow-400'}">${s.status==='Active'?'●':'○'}</span>
            </div>
            <div class="space-y-1 text-xs text-gray-500">
                <p><i class="ri-folder-2-line mr-1"></i>${s.assignments||'No assignments'}</p>
                <p><i class="ri-medal-line mr-1"></i>${s.training?(s.training.substring(0,35)+'...'):'No certs'}</p>
            </div>
            <div class="mt-2 flex justify-between items-center">
                <span class="px-1.5 py-0.5 rounded text-xs ${perfBadge[s.performance]||'bg-gray-500/20 text-gray-400'}">${s.performance||'—'}</span>
                <span class="text-gray-500 text-xs">${s.joinDate||''}</span>
            </div>
        </div>`).join('');

    document.getElementById('kanbanAdmins').innerHTML      = card(admins);
    document.getElementById('kanbanCoordinators').innerHTML= card(coords);
    document.getElementById('kanbanStaff').innerHTML       = card(others);
}

function renderPagination(total, totalPages) {
    document.getElementById('staffPagination').innerHTML = `
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

function changePage(p) { currentPage = p; renderStaff(); }

// ---- Modal ----
function openStaffModal(edit=false) {
    document.getElementById('staffModal').classList.remove('hidden');
    document.getElementById('staffModalTitle').textContent = edit ? 'Edit Team Member' : 'Add New Team Member';
}
function closeStaffModal() {
    document.getElementById('staffModal').classList.add('hidden');
    document.getElementById('staffForm').reset();
    document.getElementById('staffId').value = '';
}
function openDeleteModal(id) { deleteId = id; document.getElementById('staffDeleteModal').classList.remove('hidden'); }
function closeDeleteModal() { document.getElementById('staffDeleteModal').classList.add('hidden'); deleteId = null; }

// ---- Edit ----
function editStaff(id) {
    const s = staffList.find(x => x.id == id);
    if (!s) return;
    document.getElementById('staffId').value              = s.id;
    document.getElementById('staffName').value           = s.name;
    document.getElementById('staffEmail').value          = s.email || '';
    document.getElementById('staffPhone').value          = s.phone || '';
    document.getElementById('staffJoinDate').value       = s.joinDate || '';
    document.getElementById('staffRole').value           = s.role || '';
    document.getElementById('staffStatus').value         = s.status || 'Active';
    document.getElementById('staffAssignments').value    = s.assignments || '';
    document.getElementById('staffMetrics').value        = s.metrics || '';
    document.getElementById('staffPerformance').value    = s.performance || 'Medium';
    document.getElementById('staffTraining').value       = s.training || '';
    document.getElementById('staffEmergencyContact').value = s.emergencyContact || '';
    document.getElementById('staffEmergencyPhone').value   = s.emergencyPhone || '';
    openStaffModal(true);
}

// ---- Form Submit ----
document.getElementById('staffForm').addEventListener('submit', e => {
    e.preventDefault();
    const id   = document.getElementById('staffId').value;
    const name = document.getElementById('staffName').value.trim();
    const role = document.getElementById('staffRole').value;
    if (!name || !role) { alert('Name and Role are required.'); return; }

    const fd = new FormData();
    fd.append('action',            id ? 'update_staff' : 'save_staff');
    if (id) fd.append('id', id);
    fd.append('name',              name);
    fd.append('email',             document.getElementById('staffEmail').value.trim());
    fd.append('role',              role);
    fd.append('phone',             document.getElementById('staffPhone').value.trim());
    fd.append('join_date',         document.getElementById('staffJoinDate').value);
    fd.append('status',            document.getElementById('staffStatus').value);
    fd.append('assignments',       document.getElementById('staffAssignments').value.trim());
    fd.append('metrics',           document.getElementById('staffMetrics').value.trim());
    fd.append('performance',       document.getElementById('staffPerformance').value);
    fd.append('training',          document.getElementById('staffTraining').value.trim());
    fd.append('emergency_contact', document.getElementById('staffEmergencyContact').value.trim());
    fd.append('emergency_phone',   document.getElementById('staffEmergencyPhone').value.trim());

    fetch('api/staff.api.php', { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closeStaffModal(); loadStaff(); }
            else alert('Error: ' + (data.message||'Unknown'));
        })
        .catch(() => alert('Network error. Try again.'));
});

// ---- Delete ----
document.getElementById('confirmStaffDeleteBtn').addEventListener('click', () => {
    if (!deleteId) return;
    const fd = new FormData();
    fd.append('action', 'delete_staff');
    fd.append('id', deleteId);
    fetch('api/staff.api.php', { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closeDeleteModal(); loadStaff(); }
            else alert('Error: ' + (data.message||'Unknown'));
        });
});

// ---- Export ----
function exportStaff() {
    if (!staffList.length) { alert('No data to export.'); return; }
    const rows = [['Name','Email','Phone','Role','Status','Assignments','Performance','Training']];
    staffList.forEach(s => rows.push([s.name,s.email||'',s.phone||'',s.role||'',s.status||'',
        s.assignments||'',s.performance||'',s.training||'']));
    const csv = rows.map(r => r.map(c=>`"${c}"`).join(',')).join('\n');
    Object.assign(document.createElement('a'),{href:'data:text/csv,'+encodeURIComponent(csv),download:'staff.csv'}).click();
}

// ---- View Toggle ----
function setView(view) {
    currentView = view;
    document.getElementById('staffTableView').classList.toggle('hidden', view!=='table');
    document.getElementById('staffCardView').classList.toggle('hidden', view!=='cards');
    document.getElementById('staffKanbanView').classList.toggle('hidden', view!=='kanban');
    ['staffTableViewBtn','staffCardViewBtn','staffKanbanViewBtn'].forEach(id => {
        document.getElementById(id).className = 'px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    });
    const map = { table:'staffTableViewBtn', cards:'staffCardViewBtn', kanban:'staffKanbanViewBtn' };
    document.getElementById(map[view]).className = 'px-3 py-1.5 bg-cyan-500/20 text-cyan-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    renderStaff();
}

// ---- Events ----
document.getElementById('addStaffBtn').addEventListener('click', () => openStaffModal());
document.getElementById('addFirstStaffBtn')?.addEventListener('click', () => openStaffModal());
document.getElementById('cancelStaffBtn').addEventListener('click', closeStaffModal);
document.getElementById('closeStaffModalBtn').addEventListener('click', closeStaffModal);
document.getElementById('staffModalOverlay').addEventListener('click', closeStaffModal);
document.getElementById('cancelStaffDeleteBtn').addEventListener('click', closeDeleteModal);
document.getElementById('staffDeleteOverlay').addEventListener('click', closeDeleteModal);
document.getElementById('staffSearch').addEventListener('input', () => { currentPage=1; renderStaff(); });
document.getElementById('roleFilter').addEventListener('change', () => { currentPage=1; renderStaff(); });
document.getElementById('performanceFilter').addEventListener('change', () => { currentPage=1; renderStaff(); });
document.getElementById('clearStaffFilters').addEventListener('click', () => {
    ['staffSearch','roleFilter','performanceFilter'].forEach(id => { const el=document.getElementById(id); if(el) el.value=''; });
    currentPage=1; renderStaff();
});
document.getElementById('staffRowsPerPage').addEventListener('change', e => {
    rowsPerPage=parseInt(e.target.value); currentPage=1; renderStaff();
});
document.getElementById('staffTableViewBtn').addEventListener('click', () => setView('table'));
document.getElementById('staffCardViewBtn').addEventListener('click', () => setView('cards'));
document.getElementById('staffKanbanViewBtn').addEventListener('click', () => setView('kanban'));
document.addEventListener('keydown', e => { if(e.key==='Escape'){closeStaffModal();closeDeleteModal();} });

loadStaff();
</script>
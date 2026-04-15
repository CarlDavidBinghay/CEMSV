<?php include_once __DIR__ . '/../db.php'; ?>

<section id="activity-section" class="space-y-6">

    <!-- Premium Header -->
    <div class="rounded-3xl p-6 relative overflow-hidden group"
         style="background: linear-gradient(135deg, #212529 0%, #343A40 50%, #495057 100%);">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-pink-500/20 to-rose-500/20 rounded-full blur-3xl -mr-20 -mt-20 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-gradient-to-tr from-fuchsia-500/20 to-pink-500/20 rounded-full blur-2xl -ml-10 -mb-10 animate-pulse delay-1000"></div>
        </div>
        <div class="absolute top-10 right-20 w-16 h-16 border border-pink-500/20 rounded-2xl rotate-45 animate-float-slow"></div>
        <div class="absolute bottom-10 left-20 w-12 h-12 border border-pink-500/20 rounded-full animate-float"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.5) 1px, transparent 0); background-size: 40px 40px;"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-xs text-white/40 uppercase tracking-[0.2em] font-light">ACTIVITY MANAGEMENT</span>
                <span class="w-1 h-1 rounded-full bg-pink-400 animate-pulse"></span>
                <span class="text-xs bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-white/80 border border-white/20 flex items-center gap-1" id="activityHeaderCount">
                    <i class="ri-calendar-line text-pink-400"></i> 0 Total
                </span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2 text-white">
                        Activities
                        <span class="text-white/40 mx-2">/</span>
                        <span class="text-white/60 text-2xl">Management</span>
                    </h1>
                    <p class="text-white/30 text-sm flex items-center gap-2">
                        <i class="ri-sparkling-line text-pink-400 animate-pulse"></i>
                        Plan, schedule, and track all community activities
                    </p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportActivities()"
                            class="px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all">
                        <i class="ri-download-line text-pink-400"></i>
                        <span class="hidden md:inline">Export CSV</span>
                    </button>
                    <button id="addActivityBtn"
                            class="px-4 py-2 bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all shadow-lg shadow-pink-500/20 relative overflow-hidden group">
                        <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                        <i class="ri-add-line relative z-10"></i>
                        <span class="hidden md:inline relative z-10">Add Activity</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-pink-500/10 to-rose-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Activities</p>
                <p class="text-3xl font-bold text-white mt-1" id="statTotalActivities">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center shadow-lg">
                    <i class="ri-calendar-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs">All scheduled activities</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Upcoming</p>
                <p class="text-3xl font-bold text-white mt-1" id="statUpcomingActivities">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg">
                    <i class="ri-calendar-event-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-blue-400 text-xs">⏰ Scheduled ahead</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-green-500/10 to-emerald-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Completed</p>
                <p class="text-3xl font-bold text-white mt-1" id="statCompletedActivities">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg">
                    <i class="ri-checkbox-circle-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-green-400 text-xs">✓ Done</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Budget Used</p>
                <p class="text-3xl font-bold text-white mt-1" id="statTotalBudget">₱0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
                    <i class="ri-coins-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-purple-400 text-xs">Combined spending</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="rounded-2xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="activitySearch" placeholder="Search activities by name, location..."
                       class="w-full pl-12 pr-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all hover:bg-black/30">
            </div>
            <div class="flex gap-2 flex-wrap">
                <div class="relative">
                    <select id="activityProjectFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-pink-500 appearance-none cursor-pointer min-w-[150px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Projects</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <div class="relative">
                    <select id="activityStatusFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-pink-500 appearance-none cursor-pointer min-w-[130px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Status</option>
                        <option value="Upcoming" class="bg-gray-800">Upcoming</option>
                        <option value="Ongoing" class="bg-gray-800">Ongoing</option>
                        <option value="Completed" class="bg-gray-800">Completed</option>
                        <option value="Cancelled" class="bg-gray-800">Cancelled</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <div class="relative">
                    <select id="activityMonthFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-pink-500 appearance-none cursor-pointer min-w-[130px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Months</option>
                        <option value="01" class="bg-gray-800">January</option>
                        <option value="02" class="bg-gray-800">February</option>
                        <option value="03" class="bg-gray-800">March</option>
                        <option value="04" class="bg-gray-800">April</option>
                        <option value="05" class="bg-gray-800">May</option>
                        <option value="06" class="bg-gray-800">June</option>
                        <option value="07" class="bg-gray-800">July</option>
                        <option value="08" class="bg-gray-800">August</option>
                        <option value="09" class="bg-gray-800">September</option>
                        <option value="10" class="bg-gray-800">October</option>
                        <option value="11" class="bg-gray-800">November</option>
                        <option value="12" class="bg-gray-800">December</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <button id="clearActivityFilters"
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
            <button id="activityTableViewBtn" class="px-3 py-1.5 bg-pink-500/20 text-pink-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-table-line"></i> Table</button>
            <button id="activityCardViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-layout-grid-line"></i> Cards</button>
            <button id="activityCalendarViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-calendar-line"></i> Calendar</button>
        </div>
        <div class="text-white/40 text-sm"><span id="showingActivitiesCount">0</span> activities</div>
    </div>

    <!-- Table View -->
    <div id="activityTableView" class="rounded-2xl overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div id="activityLoadingState" class="text-center py-12">
            <div class="relative inline-block">
                <div class="absolute inset-0 bg-gradient-to-r from-pink-500 to-rose-500 rounded-full blur-xl opacity-50 animate-pulse"></div>
                <div class="relative w-16 h-16 rounded-full bg-gradient-to-r from-pink-500 to-rose-500 flex items-center justify-center mb-4 mx-auto">
                    <i class="ri-loader-4-line animate-spin text-2xl text-white"></i>
                </div>
            </div>
            <p class="text-gray-400 mt-4">Loading activities...</p>
        </div>
        <div id="activityTableWrapper" class="hidden overflow-x-auto">
            <table class="w-full">
                <thead style="background:#1E2228; border-bottom:1px solid rgba(255,255,255,0.05);">
                    <tr>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Activity</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Project</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Date & Time</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Location</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Attendance</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="activityTable" style="background:#2A2F37;" class="divide-y divide-white/5"></tbody>
            </table>
        </div>
        <div id="activityEmptyState" class="hidden text-center py-12">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-pink-500/20 to-rose-500/20 flex items-center justify-center">
                <i class="ri-calendar-todo-line text-4xl text-pink-400/50"></i>
            </div>
            <h3 class="text-white text-lg font-semibold mb-2">No activities found</h3>
            <p class="text-gray-400 text-sm mb-4">Get started by creating your first activity</p>
            <button id="addFirstActivityBtn" class="px-6 py-2.5 bg-gradient-to-r from-pink-500 to-rose-600 text-white rounded-xl font-medium transition-all inline-flex items-center gap-2">
                <i class="ri-add-line"></i> Create Activity
            </button>
        </div>
        <div id="activityTableFooter" class="hidden flex items-center justify-between px-6 py-4" style="background:#1E2228; border-top:1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center gap-4 text-sm">
                <span class="text-gray-400">Total: <b id="activityTotalCount" class="text-white">0</b></span>
                <span class="text-gray-400">Upcoming: <b id="activityUpcomingCount" class="text-blue-400">0</b></span>
                <span class="text-gray-400">Completed: <b id="activityCompletedCount" class="text-green-400">0</b></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-400 text-sm">Rows:</span>
                <select id="activityRowsPerPage" class="bg-black/20 border border-white/10 rounded-lg text-white px-3 py-1.5 text-sm focus:outline-none">
                    <option value="5" class="bg-gray-800">5</option>
                    <option value="10" selected class="bg-gray-800">10</option>
                    <option value="25" class="bg-gray-800">25</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Card View -->
    <div id="activityCardView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>

    <!-- Calendar View -->
    <div id="activityCalendarView" class="hidden rounded-2xl p-6" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <button id="prevMonthBtn" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center transition-all">
                    <i class="ri-arrow-left-s-line text-white"></i>
                </button>
                <h3 class="text-white font-semibold" id="calendarMonthYear"></h3>
                <button id="nextMonthBtn" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center transition-all">
                    <i class="ri-arrow-right-s-line text-white"></i>
                </button>
            </div>
            <div class="flex gap-3 text-xs text-gray-400">
                <span class="flex items-center gap-1"><span class="w-2 h-2 bg-pink-500 rounded-full"></span>Today</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 bg-blue-500 rounded-full"></span>Upcoming</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 bg-green-500 rounded-full"></span>Completed</span>
            </div>
        </div>
        <div class="grid grid-cols-7 gap-1 mb-2">
            <div class="text-center text-gray-400 text-xs py-2">Sun</div>
            <div class="text-center text-gray-400 text-xs py-2">Mon</div>
            <div class="text-center text-gray-400 text-xs py-2">Tue</div>
            <div class="text-center text-gray-400 text-xs py-2">Wed</div>
            <div class="text-center text-gray-400 text-xs py-2">Thu</div>
            <div class="text-center text-gray-400 text-xs py-2">Fri</div>
            <div class="text-center text-gray-400 text-xs py-2">Sat</div>
        </div>
        <div id="calendarDays" class="grid grid-cols-7 gap-1"></div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-2">
        <div class="text-gray-400 text-sm">
            Showing <span id="activityShowingStart">0</span> to <span id="activityShowingEnd">0</span> of <span id="activityTotalRecords">0</span>
        </div>
        <div class="flex gap-2" id="activityPagination"></div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="activityModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" id="activityModalOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl px-4 max-h-screen overflow-y-auto py-6">
            <div class="rounded-2xl shadow-2xl p-6 relative animate-slideIn overflow-hidden"
                 style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-pink-500/10 to-rose-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-fuchsia-500/10 to-pink-500/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-pink-500 to-rose-500 rounded-xl blur-md animate-pulse"></div>
                            <div class="relative w-14 h-14 rounded-xl bg-gradient-to-r from-pink-500 to-rose-600 flex items-center justify-center">
                                <i class="ri-calendar-check-line text-2xl text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-pink-400 to-rose-400 bg-clip-text text-transparent" id="activityModalTitle">Add New Activity</h2>
                            <p class="text-gray-400 text-sm">Schedule and manage activity details</p>
                        </div>
                        <button id="closeActivityModalBtn" class="ml-auto w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center border border-white/10 transition-all">
                            <i class="ri-close-line text-gray-400"></i>
                        </button>
                    </div>
                    <form id="activityForm" class="space-y-4">
                        <input type="hidden" id="activityId">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-calendar-line text-pink-400 mr-1"></i> Activity Name *</label>
                                <input type="text" id="activityName" placeholder="e.g., Medical Mission Day 1"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all">
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-folder-2-line text-pink-400 mr-1"></i> Project</label>
                                <select id="activityProject" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-pink-500">
                                    <option value="" class="bg-gray-800">Select Project</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-calendar-line text-pink-400 mr-1"></i> Date & Time</label>
                                <input type="datetime-local" id="activityDateTime"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-pink-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-flag-line text-pink-400 mr-1"></i> Status</label>
                                <select id="activityStatus" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-pink-500">
                                    <option value="Upcoming" class="bg-gray-800">Upcoming</option>
                                    <option value="Ongoing" class="bg-gray-800">Ongoing</option>
                                    <option value="Completed" class="bg-gray-800">Completed</option>
                                    <option value="Cancelled" class="bg-gray-800">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-map-pin-line text-pink-400 mr-1"></i> Location</label>
                                <input type="text" id="activityLocation" placeholder="e.g., Barangay Hall"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-group-line text-pink-400 mr-1"></i> Attendance</label>
                                <input type="text" id="activityAttendance" placeholder="e.g., 50 participants"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-tools-line text-pink-400 mr-1"></i> Resources Needed</label>
                            <input type="text" id="activityResources" placeholder="e.g., Medical supplies, 5 volunteers"
                                   class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-file-text-line text-pink-400 mr-1"></i> Expected Output / Accomplishments</label>
                            <textarea id="activityOutput" rows="3" placeholder="Describe the expected outcomes or actual accomplishments..."
                                      class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-500 resize-none"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-user-star-line text-pink-400 mr-1"></i> Facilitators</label>
                                <input type="text" id="activityFacilitators" placeholder="e.g., Dr. Santos, Nurse Ana"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-coins-line text-pink-400 mr-1"></i> Budget Used (₱)</label>
                                <input type="number" id="activityBudget" placeholder="e.g., 5000"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all">
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                            <button type="button" id="cancelActivityBtn" class="px-6 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 rounded-xl text-white font-medium transition-all shadow-lg flex items-center gap-2 relative overflow-hidden group">
                                <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                                <i class="ri-save-line relative z-10"></i>
                                <span class="relative z-10">Save Activity</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="activityDeleteModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" id="activityDeleteOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm px-4">
            <div class="rounded-2xl shadow-2xl p-6 text-center animate-slideIn" style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-500/10 flex items-center justify-center">
                    <i class="ri-delete-bin-line text-3xl text-red-400"></i>
                </div>
                <h3 class="text-white text-lg font-semibold mb-2">Delete Activity?</h3>
                <p class="text-gray-400 text-sm mb-6">This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button id="cancelActivityDeleteBtn" class="flex-1 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                    <button id="confirmActivityDeleteBtn" class="flex-1 py-2.5 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl text-white font-medium transition-all">Delete</button>
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
#activityTable tr { transition: all 0.3s ease; }
#activityTable tr:hover { background: rgba(255,255,255,0.05); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
.calendar-day { aspect-ratio:1; padding:0.5rem; background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:8px; transition:all 0.2s ease; cursor:pointer; position:relative; }
.calendar-day:hover { background:rgba(255,255,255,0.05); border-color:rgba(236,72,153,0.3); }
.calendar-day.today { border:2px solid #EC4899; background:rgba(236,72,153,0.1); }
.calendar-day.has-activity::after { content:''; position:absolute; bottom:4px; left:50%; transform:translateX(-50%); width:4px; height:4px; border-radius:50%; background:#EC4899; }
</style>

<script>
let activities   = [];
let projectsList = [];
let deleteActId  = null;
let currentPage  = 1;
let rowsPerPage  = 10;
let currentView  = 'table';
let calendarDate = new Date();

const API = 'api/activity_api.php';

const statusColors = {
    'Upcoming':  'bg-blue-500/20 text-blue-400 border border-blue-500/30',
    'Ongoing':   'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
    'Completed': 'bg-green-500/20 text-green-400 border border-green-500/30',
    'Cancelled': 'bg-red-500/20 text-red-400 border border-red-500/30'
};

// ---- Load projects ----
function loadProjects() {
    fetch(`${API}?action=get_projects`)
        .then(r => r.json())
        .then(data => {
            projectsList = data;
            populateProjectDropdowns();
        }).catch(() => {});
}

function populateProjectDropdowns() {
    ['activityProjectFilter','activityProject'].forEach(id => {
        const sel = document.getElementById(id);
        if (!sel) return;
        const first = id === 'activityProjectFilter'
            ? '<option value="" class="bg-gray-800">All Projects</option>'
            : '<option value="" class="bg-gray-800">Select Project</option>';
        sel.innerHTML = first + projectsList.map(p =>
            `<option value="${p.id}" class="bg-gray-800">${p.name}</option>`
        ).join('');
    });
}

// ---- Load activities ----
function loadActivities() {
    document.getElementById('activityLoadingState').classList.remove('hidden');
    document.getElementById('activityTableWrapper').classList.add('hidden');
    document.getElementById('activityEmptyState').classList.add('hidden');
    document.getElementById('activityTableFooter')?.classList.add('hidden');

    fetch(`${API}?action=get`)
        .then(r => r.json())
        .then(data => {
            activities = data;
            document.getElementById('activityLoadingState').classList.add('hidden');
            updateStats();
            renderActivities();
            renderCalendar();
        })
        .catch(() => {
            document.getElementById('activityLoadingState').innerHTML =
                '<p class="text-red-400 py-8 text-center">Failed to load. Check API connection.</p>';
        });
}

// ---- Stats ----
function updateStats() {
    const total     = activities.length;
    const upcoming  = activities.filter(a => a.status === 'Upcoming').length;
    const completed = activities.filter(a => a.status === 'Completed').length;
    const budget    = activities.reduce((s,a) => s + (parseFloat(a.budget)||0), 0);

    document.getElementById('statTotalActivities').textContent    = total;
    document.getElementById('statUpcomingActivities').textContent = upcoming;
    document.getElementById('statCompletedActivities').textContent= completed;
    document.getElementById('statTotalBudget').textContent        = '₱' + budget.toLocaleString();
    document.getElementById('activityHeaderCount').innerHTML      = `<i class="ri-calendar-line text-pink-400"></i> ${total} Total`;
}

function getProjectName(id) {
    const p = projectsList.find(x => String(x.id) === String(id));
    return p ? p.name : (id || '—');
}

// ---- Render ----
function renderActivities() {
    const search  = document.getElementById('activitySearch').value.toLowerCase();
    const project = document.getElementById('activityProjectFilter').value;
    const status  = document.getElementById('activityStatusFilter').value;
    const month   = document.getElementById('activityMonthFilter').value;

    let filtered = activities.filter(a =>
        (a.name.toLowerCase().includes(search) || (a.location||'').toLowerCase().includes(search)) &&
        (!project || String(a.project_id) === String(project)) &&
        (!status  || a.status === status) &&
        (!month   || (a.date_time && a.date_time.substring(5,7) === month))
    );

    const total = filtered.length;
    document.getElementById('activityTotalCount').textContent    = total;
    document.getElementById('activityUpcomingCount').textContent = filtered.filter(a=>a.status==='Upcoming').length;
    document.getElementById('activityCompletedCount').textContent= filtered.filter(a=>a.status==='Completed').length;
    document.getElementById('showingActivitiesCount').textContent= total;

    const totalPages = Math.max(1, Math.ceil(total / rowsPerPage));
    if (currentPage > totalPages) currentPage = totalPages;
    const start = (currentPage-1)*rowsPerPage;
    const paged = filtered.slice(start, start+rowsPerPage);

    document.getElementById('activityShowingStart').textContent = total ? start+1 : 0;
    document.getElementById('activityShowingEnd').textContent   = Math.min(start+rowsPerPage, total);
    document.getElementById('activityTotalRecords').textContent = total;

    if (total === 0) {
        document.getElementById('activityTableWrapper').classList.add('hidden');
        document.getElementById('activityEmptyState').classList.remove('hidden');
        document.getElementById('activityTableFooter')?.classList.add('hidden');
        document.getElementById('activityCardView').innerHTML = '';
        document.getElementById('activityPagination').innerHTML = '';
        return;
    }

    document.getElementById('activityEmptyState').classList.add('hidden');
    document.getElementById('activityTableFooter')?.classList.remove('hidden');

    if (currentView === 'table') {
        document.getElementById('activityTableWrapper').classList.remove('hidden');
        renderTableView(paged);
    } else if (currentView === 'cards') {
        document.getElementById('activityTableWrapper').classList.add('hidden');
        renderCardView(paged);
    }

    renderPagination(total, totalPages);
}

function fmtDate(dt) {
    if (!dt) return '—';
    try {
        return new Date(dt).toLocaleString('en-US',{month:'short',day:'numeric',year:'numeric',hour:'2-digit',minute:'2-digit'});
    } catch { return dt; }
}

function renderTableView(data) {
    document.getElementById('activityTable').innerHTML = data.map(a => {
        const sc = statusColors[a.status] || 'bg-gray-500/20 text-gray-400';
        return `
        <tr class="hover:bg-white/5 transition-all">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center text-white flex-shrink-0">
                        <i class="ri-calendar-line"></i>
                    </div>
                    <span class="text-white font-medium">${a.name}</span>
                </div>
            </td>
            <td class="px-6 py-4 text-pink-400 text-sm">${getProjectName(a.project_id)}</td>
            <td class="px-6 py-4 text-gray-400 text-sm">${fmtDate(a.date_time)}</td>
            <td class="px-6 py-4 text-gray-400 text-sm">${a.location||'—'}</td>
            <td class="px-6 py-4 text-gray-400 text-sm">${a.attendance||'—'}</td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium ${sc}">${a.status||'—'}</span></td>
            <td class="px-6 py-4">
                <div class="flex gap-2">
                    <button onclick="editActivity(${a.id})" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-pink-500/20 border border-white/10 flex items-center justify-center transition-all group">
                        <i class="ri-edit-line text-gray-400 group-hover:text-pink-400"></i>
                    </button>
                    <button onclick="openDeleteModal(${a.id})" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-red-500/20 border border-white/10 flex items-center justify-center transition-all group">
                        <i class="ri-delete-bin-line text-gray-400 group-hover:text-red-400"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderCardView(data) {
    document.getElementById('activityCardView').innerHTML = data.map(a => {
        const sc = statusColors[a.status] || 'bg-gray-500/20 text-gray-400';
        return `
        <div class="rounded-xl p-5 relative overflow-hidden hover:scale-105 transition-all duration-300"
             style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-pink-500/10 to-rose-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center">
                    <i class="ri-calendar-line text-white text-xl"></i>
                </div>
                <span class="px-2 py-1 rounded-full text-xs font-medium ${sc}">${a.status||'—'}</span>
            </div>
            <h3 class="text-white font-semibold text-lg mb-1">${a.name}</h3>
            <p class="text-pink-400 text-sm mb-3">${getProjectName(a.project_id)}</p>
            <div class="space-y-1.5 mb-4">
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-calendar-line text-pink-400"></i>${fmtDate(a.date_time)}</div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-map-pin-line text-pink-400"></i>${a.location||'—'}</div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-group-line text-pink-400"></i>${a.attendance||'No attendance'}</div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-user-star-line text-pink-400"></i>${a.facilitators||'No facilitator'}</div>
                ${a.budget ? `<div class="flex items-center gap-2 text-xs text-pink-400 font-medium"><i class="ri-coins-line"></i>₱${parseFloat(a.budget).toLocaleString()}</div>` : ''}
            </div>
            ${a.expected_output ? `<p class="text-gray-500 text-xs mb-3 border-t border-white/10 pt-3">${a.expected_output.substring(0,80)}...</p>` : ''}
            <div class="flex justify-end gap-2">
                <button onclick="editActivity(${a.id})" class="px-3 py-1.5 bg-white/5 hover:bg-pink-500/20 rounded-lg text-gray-400 hover:text-pink-400 text-sm transition-all"><i class="ri-edit-line"></i> Edit</button>
                <button onclick="openDeleteModal(${a.id})" class="px-3 py-1.5 bg-white/5 hover:bg-red-500/20 rounded-lg text-gray-400 hover:text-red-400 text-sm transition-all"><i class="ri-delete-bin-line"></i> Delete</button>
            </div>
        </div>`;
    }).join('');
}

function renderPagination(total, totalPages) {
    document.getElementById('activityPagination').innerHTML = `
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

function changePage(p) { currentPage = p; renderActivities(); }

// ---- Calendar ----
function renderCalendar() {
    const year  = calendarDate.getFullYear();
    const month = calendarDate.getMonth();
    const names = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    document.getElementById('calendarMonthYear').textContent = `${names[month]} ${year}`;

    const firstDay    = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month+1, 0).getDate();
    const today       = new Date();

    let html = '';
    for (let i = 0; i < firstDay; i++) html += '<div class="calendar-day opacity-20"></div>';

    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
        const has     = activities.some(a => a.date_time && a.date_time.startsWith(dateStr));
        const isToday = today.getFullYear()===year && today.getMonth()===month && today.getDate()===day;
        html += `<div class="calendar-day ${isToday?'today':''} ${has?'has-activity':''}" onclick="showDateActivities('${dateStr}')">
            <span class="text-white text-sm">${day}</span>
        </div>`;
    }
    document.getElementById('calendarDays').innerHTML = html;
}

function showDateActivities(date) {
    const found = activities.filter(a => a.date_time && a.date_time.startsWith(date));
    if (!found.length) return;
    alert(`Activities on ${date}:\n\n` + found.map(a => `• ${a.name} (${a.status})`).join('\n'));
}

// ---- Modal ----
function openActivityModal(edit=false) {
    populateProjectDropdowns();
    document.getElementById('activityModal').classList.remove('hidden');
    document.getElementById('activityModalTitle').textContent = edit ? 'Edit Activity' : 'Add New Activity';
}
function closeActivityModal() {
    document.getElementById('activityModal').classList.add('hidden');
    document.getElementById('activityForm').reset();
    document.getElementById('activityId').value = '';
}
function openDeleteModal(id) { deleteActId = id; document.getElementById('activityDeleteModal').classList.remove('hidden'); }
function closeDeleteModal() { document.getElementById('activityDeleteModal').classList.add('hidden'); deleteActId = null; }

// ---- Edit ----
function editActivity(id) {
    const a = activities.find(x => x.id == id);
    if (!a) return;
    document.getElementById('activityId').value         = a.id;
    document.getElementById('activityName').value       = a.name;
    document.getElementById('activityLocation').value   = a.location || '';
    document.getElementById('activityResources').value  = a.resources || '';
    document.getElementById('activityOutput').value     = a.expected_output || '';
    document.getElementById('activityAttendance').value = a.attendance || '';
    document.getElementById('activityStatus').value     = a.status || 'Upcoming';
    document.getElementById('activityFacilitators').value = a.facilitators || '';
    document.getElementById('activityBudget').value     = a.budget || '';
    // format datetime for input
    if (a.date_time) {
        const dt = new Date(a.date_time);
        const local = new Date(dt.getTime() - dt.getTimezoneOffset()*60000).toISOString().slice(0,16);
        document.getElementById('activityDateTime').value = local;
    }
    setTimeout(() => { document.getElementById('activityProject').value = a.project_id || ''; }, 100);
    openActivityModal(true);
}

// ---- Form Submit ----
document.getElementById('activityForm').addEventListener('submit', e => {
    e.preventDefault();
    const id   = document.getElementById('activityId').value;
    const name = document.getElementById('activityName').value.trim();
    if (!name) { alert('Activity name is required.'); return; }

    const fd = new FormData();
    fd.append('action',       id ? 'update' : 'save');
    if (id) fd.append('id', id);
    fd.append('name',         name);
    fd.append('project_id',   document.getElementById('activityProject').value || 0);
    fd.append('date_time',    document.getElementById('activityDateTime').value);
    fd.append('location',     document.getElementById('activityLocation').value.trim());
    fd.append('resources',    document.getElementById('activityResources').value.trim());
    fd.append('output',       document.getElementById('activityOutput').value.trim());
    fd.append('attendance',   document.getElementById('activityAttendance').value.trim());
    fd.append('status',       document.getElementById('activityStatus').value);
    fd.append('facilitators', document.getElementById('activityFacilitators').value.trim());
    fd.append('budget',       document.getElementById('activityBudget').value || 0);

    fetch(API, { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closeActivityModal(); loadActivities(); }
            else alert('Error: ' + (data.message||'Unknown'));
        })
        .catch(() => alert('Network error. Try again.'));
});

// ---- Delete ----
document.getElementById('confirmActivityDeleteBtn').addEventListener('click', () => {
    if (!deleteActId) return;
    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('id', deleteActId);
    fetch(API, { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closeDeleteModal(); loadActivities(); }
            else alert('Error: ' + (data.message||'Unknown'));
        });
});

// ---- Export ----
function exportActivities() {
    if (!activities.length) { alert('No activities to export.'); return; }
    const rows = [['Name','Project','Date & Time','Location','Attendance','Resources','Output','Status','Facilitators','Budget']];
    activities.forEach(a => rows.push([a.name, getProjectName(a.project_id), a.date_time||'',
        a.location||'', a.attendance||'', a.resources||'', a.expected_output||'',
        a.status||'', a.facilitators||'', a.budget||0]));
    const csv = rows.map(r => r.map(c=>`"${c}"`).join(',')).join('\n');
    Object.assign(document.createElement('a'),{href:'data:text/csv,'+encodeURIComponent(csv),download:'activities.csv'}).click();
}

// ---- View Toggle ----
function setView(view) {
    currentView = view;
    document.getElementById('activityTableView').classList.toggle('hidden', view!=='table');
    document.getElementById('activityCardView').classList.toggle('hidden', view!=='cards');
    document.getElementById('activityCalendarView').classList.toggle('hidden', view!=='calendar');
    ['activityTableViewBtn','activityCardViewBtn','activityCalendarViewBtn'].forEach(id => {
        document.getElementById(id).className = 'px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    });
    const map = { table:'activityTableViewBtn', cards:'activityCardViewBtn', calendar:'activityCalendarViewBtn' };
    document.getElementById(map[view]).className = 'px-3 py-1.5 bg-pink-500/20 text-pink-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    if (view === 'calendar') renderCalendar();
    else renderActivities();
}

// ---- Event Listeners ----
document.getElementById('addActivityBtn').addEventListener('click', () => openActivityModal());
document.getElementById('addFirstActivityBtn')?.addEventListener('click', () => openActivityModal());
document.getElementById('cancelActivityBtn').addEventListener('click', closeActivityModal);
document.getElementById('closeActivityModalBtn').addEventListener('click', closeActivityModal);
document.getElementById('activityModalOverlay').addEventListener('click', closeActivityModal);
document.getElementById('cancelActivityDeleteBtn').addEventListener('click', closeDeleteModal);
document.getElementById('activityDeleteOverlay').addEventListener('click', closeDeleteModal);
document.getElementById('activitySearch').addEventListener('input', () => { currentPage=1; renderActivities(); });
document.getElementById('activityProjectFilter').addEventListener('change', () => { currentPage=1; renderActivities(); });
document.getElementById('activityStatusFilter').addEventListener('change', () => { currentPage=1; renderActivities(); });
document.getElementById('activityMonthFilter').addEventListener('change', () => { currentPage=1; renderActivities(); });
document.getElementById('clearActivityFilters').addEventListener('click', () => {
    ['activitySearch','activityProjectFilter','activityStatusFilter','activityMonthFilter'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    currentPage=1; renderActivities();
});
document.getElementById('activityRowsPerPage').addEventListener('change', e => {
    rowsPerPage = parseInt(e.target.value); currentPage=1; renderActivities();
});
document.getElementById('activityTableViewBtn').addEventListener('click', () => setView('table'));
document.getElementById('activityCardViewBtn').addEventListener('click', () => setView('cards'));
document.getElementById('activityCalendarViewBtn').addEventListener('click', () => setView('calendar'));
document.getElementById('prevMonthBtn').addEventListener('click', () => {
    calendarDate.setMonth(calendarDate.getMonth()-1); renderCalendar();
});
document.getElementById('nextMonthBtn').addEventListener('click', () => {
    calendarDate.setMonth(calendarDate.getMonth()+1); renderCalendar();
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeActivityModal(); closeDeleteModal(); }
});

loadProjects();
loadActivities();
</script>
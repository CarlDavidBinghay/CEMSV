<?php include_once __DIR__ . '/../db.php'; ?>

<section id="project-section" class="space-y-6">

    <!-- Premium Header -->
    <div class="rounded-3xl p-6 relative overflow-hidden group"
         style="background: linear-gradient(135deg, #212529 0%, #343A40 50%, #495057 100%);">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-full blur-3xl -mr-20 -mt-20 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-gradient-to-tr from-indigo-500/20 to-purple-500/20 rounded-full blur-2xl -ml-10 -mb-10 animate-pulse delay-1000"></div>
        </div>
        <div class="absolute top-10 right-20 w-16 h-16 border border-purple-500/20 rounded-2xl rotate-45 animate-float-slow"></div>
        <div class="absolute bottom-10 left-20 w-12 h-12 border border-purple-500/20 rounded-full animate-float"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.5) 1px, transparent 0); background-size: 40px 40px;"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-xs text-white/40 uppercase tracking-[0.2em] font-light">PROJECT MANAGEMENT</span>
                <span class="w-1 h-1 rounded-full bg-purple-400 animate-pulse"></span>
                <span class="text-xs bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-white/80 border border-white/20 flex items-center gap-1" id="projectHeaderCount">
                    <i class="ri-folder-2-line text-purple-400"></i> 0 Total
                </span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2 text-white">
                        Projects
                        <span class="text-white/40 mx-2">/</span>
                        <span class="text-white/60 text-2xl">Management</span>
                    </h1>
                    <p class="text-white/30 text-sm flex items-center gap-2">
                        <i class="ri-sparkling-line text-purple-400 animate-pulse"></i>
                        Plan, track, and manage all community projects
                    </p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportProjects()"
                            class="px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all">
                        <i class="ri-download-line text-purple-400"></i>
                        <span class="hidden md:inline">Export CSV</span>
                    </button>
                    <button id="addProjectBtn"
                            class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all shadow-lg shadow-purple-500/20 relative overflow-hidden group">
                        <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                        <i class="ri-add-line relative z-10"></i>
                        <span class="hidden md:inline relative z-10">Add Project</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Projects</p>
                <p class="text-3xl font-bold text-white mt-1" id="statTotalProjects">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
                    <i class="ri-folder-2-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs">All registered projects</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-green-500/10 to-emerald-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Ongoing</p>
                <p class="text-3xl font-bold text-white mt-1" id="statOngoingProjects">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg">
                    <i class="ri-play-circle-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-green-400 text-xs">● In progress</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Completed</p>
                <p class="text-3xl font-bold text-white mt-1" id="statCompletedProjects">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg">
                    <i class="ri-checkbox-circle-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-blue-400 text-xs">✓ Delivered</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-amber-500/10 to-orange-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Budget</p>
                <p class="text-3xl font-bold text-white mt-1" id="statTotalBudget">₱0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg">
                    <i class="ri-coins-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-amber-400 text-xs">Combined budget</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="rounded-2xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="projectSearch" placeholder="Search projects by name, program, coordinator..."
                       class="w-full pl-12 pr-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all hover:bg-black/30">
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <select id="projectProgramFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-purple-500 appearance-none cursor-pointer min-w-[160px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Programs</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <div class="relative">
                    <select id="projectStatusFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-purple-500 appearance-none cursor-pointer min-w-[140px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Status</option>
                        <option value="Planned" class="bg-gray-800">Planned</option>
                        <option value="Ongoing" class="bg-gray-800">Ongoing</option>
                        <option value="Completed" class="bg-gray-800">Completed</option>
                        <option value="On Hold" class="bg-gray-800">On Hold</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <button id="clearProjectFilters"
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
            <button id="projectTableViewBtn" class="px-3 py-1.5 bg-purple-500/20 text-purple-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-table-line"></i> Table</button>
            <button id="projectCardViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-layout-grid-line"></i> Cards</button>
            <button id="projectKanbanViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-kanban-view"></i> Kanban</button>
        </div>
        <div class="text-white/40 text-sm"><span id="showingProjectsCount">0</span> projects</div>
    </div>

    <!-- Table View -->
    <div id="projectTableView" class="rounded-2xl overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div id="projectLoadingState" class="text-center py-12">
            <div class="relative inline-block">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full blur-xl opacity-50 animate-pulse"></div>
                <div class="relative w-16 h-16 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center mb-4 mx-auto">
                    <i class="ri-loader-4-line animate-spin text-2xl text-white"></i>
                </div>
            </div>
            <p class="text-gray-400 mt-4">Loading projects...</p>
        </div>
        <div id="projectTableWrapper" class="hidden overflow-x-auto">
            <table class="w-full">
                <thead style="background:#1E2228; border-bottom:1px solid rgba(255,255,255,0.05);">
                    <tr>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Project</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Program</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Objectives</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Timeline</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Budget</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Progress</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="projectTable" style="background:#2A2F37;" class="divide-y divide-white/5"></tbody>
            </table>
        </div>
        <div id="projectEmptyState" class="hidden text-center py-12">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center">
                <i class="ri-folder-4-line text-4xl text-purple-400/50"></i>
            </div>
            <h3 class="text-white text-lg font-semibold mb-2">No projects found</h3>
            <p class="text-gray-400 text-sm mb-4">Get started by creating your first project</p>
            <button id="addFirstProjectBtn" class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl font-medium transition-all inline-flex items-center gap-2">
                <i class="ri-add-line"></i> Create Project
            </button>
        </div>
        <div id="projectTableFooter" class="hidden flex items-center justify-between px-6 py-4" style="background:#1E2228; border-top:1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center gap-4 text-sm">
                <span class="text-gray-400">Total: <b id="projectTotalCount" class="text-white">0</b></span>
                <span class="text-gray-400">Ongoing: <b id="projectOngoingCount" class="text-green-400">0</b></span>
                <span class="text-gray-400">Completed: <b id="projectCompletedCount" class="text-blue-400">0</b></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-400 text-sm">Rows:</span>
                <select id="projectRowsPerPage" class="bg-black/20 border border-white/10 rounded-lg text-white px-3 py-1.5 text-sm focus:outline-none">
                    <option value="5" class="bg-gray-800">5</option>
                    <option value="10" selected class="bg-gray-800">10</option>
                    <option value="25" class="bg-gray-800">25</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Card View -->
    <div id="projectCardView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>

    <!-- Kanban View -->
    <div id="projectKanbanView" class="hidden grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-xl p-4" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-white font-semibold flex items-center gap-2"><span class="w-2 h-2 bg-yellow-400 rounded-full"></span>Planned</h3>
                <span class="text-xs bg-white/5 text-gray-400 px-2 py-1 rounded-full" id="plannedCount">0</span>
            </div>
            <div id="plannedColumn" class="space-y-3 min-h-[200px]"></div>
        </div>
        <div class="rounded-xl p-4" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-white font-semibold flex items-center gap-2"><span class="w-2 h-2 bg-green-400 rounded-full"></span>Ongoing</h3>
                <span class="text-xs bg-white/5 text-gray-400 px-2 py-1 rounded-full" id="ongoingCount">0</span>
            </div>
            <div id="ongoingColumn" class="space-y-3 min-h-[200px]"></div>
        </div>
        <div class="rounded-xl p-4" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-white font-semibold flex items-center gap-2"><span class="w-2 h-2 bg-blue-400 rounded-full"></span>Completed</h3>
                <span class="text-xs bg-white/5 text-gray-400 px-2 py-1 rounded-full" id="completedCount">0</span>
            </div>
            <div id="completedColumn" class="space-y-3 min-h-[200px]"></div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-2">
        <div class="text-gray-400 text-sm">
            Showing <span id="projectShowingStart">0</span> to <span id="projectShowingEnd">0</span> of <span id="projectTotalRecords">0</span>
        </div>
        <div class="flex gap-2" id="projectPagination"></div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="projectModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" id="projectModalOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl px-4 max-h-screen overflow-y-auto py-6">
            <div class="rounded-2xl shadow-2xl p-6 relative animate-slideIn overflow-hidden"
                 style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-indigo-500/10 to-purple-500/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl blur-md animate-pulse"></div>
                            <div class="relative w-14 h-14 rounded-xl bg-gradient-to-r from-purple-500 to-pink-600 flex items-center justify-center">
                                <i class="ri-folder-2-line text-2xl text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent" id="projectModalTitle">Add New Project</h2>
                            <p class="text-gray-400 text-sm">Fill in the project details below</p>
                        </div>
                        <button id="closeProjectModalBtn" class="ml-auto w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center border border-white/10 transition-all">
                            <i class="ri-close-line text-gray-400"></i>
                        </button>
                    </div>

                    <!-- Tabs -->
                    <div class="flex border-b border-white/10 mb-4">
                        <button type="button" class="tab-btn active px-4 py-2 text-purple-400 border-b-2 border-purple-500 font-medium text-sm" data-tab="basic">Basic Info</button>
                        <button type="button" class="tab-btn px-4 py-2 text-gray-400 hover:text-white font-medium text-sm transition-all" data-tab="details">Details</button>
                        <button type="button" class="tab-btn px-4 py-2 text-gray-400 hover:text-white font-medium text-sm transition-all" data-tab="resources">Resources</button>
                    </div>

                    <form id="projectForm" class="space-y-4">
                        <input type="hidden" id="projectId">

                        <!-- Tab Basic -->
                        <div class="tab-pane" id="tab-basic">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-folder-2-line text-purple-400 mr-1"></i> Project Name *</label>
                                    <input type="text" id="projectName" placeholder="e.g., Community Health Outreach"
                                           class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-archive-line text-purple-400 mr-1"></i> Program</label>
                                        <select id="projectProgram" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                                            <option value="" class="bg-gray-800">Select Program</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-flag-line text-purple-400 mr-1"></i> Status</label>
                                        <select id="projectStatus" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                                            <option value="Planned" class="bg-gray-800">Planned</option>
                                            <option value="Ongoing" class="bg-gray-800">Ongoing</option>
                                            <option value="On Hold" class="bg-gray-800">On Hold</option>
                                            <option value="Completed" class="bg-gray-800">Completed</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-bullseye-line text-purple-400 mr-1"></i> Objectives</label>
                                    <textarea id="projectObjectives" rows="3" placeholder="Describe the project objectives..."
                                              class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 resize-none"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Details -->
                        <div class="tab-pane hidden" id="tab-details">
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-group-line text-purple-400 mr-1"></i> Target Beneficiaries</label>
                                        <input type="text" id="projectBeneficiaries" placeholder="e.g., Children, Elderly"
                                               class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-user-star-line text-purple-400 mr-1"></i> Coordinators</label>
                                        <input type="text" id="projectCoordinators" placeholder="e.g., Juan Dela Cruz"
                                               class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-calendar-line text-purple-400 mr-1"></i> Start Date</label>
                                        <input type="date" id="projectStartDate"
                                               class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-calendar-check-line text-purple-400 mr-1"></i> End Date</label>
                                        <input type="date" id="projectEndDate"
                                               class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-coins-line text-purple-400 mr-1"></i> Budget (₱)</label>
                                    <input type="number" id="projectBudget" placeholder="e.g., 50000"
                                           class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-progress-5-line text-purple-400 mr-1"></i> Progress (%)</label>
                                    <input type="range" id="projectProgress" min="0" max="100" value="0" class="w-full">
                                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                                        <span>0%</span><span id="progressValue" class="text-purple-400 font-medium">0%</span><span>100%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Resources -->
                        <div class="tab-pane hidden" id="tab-resources">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-tools-line text-purple-400 mr-1"></i> Resources & Locations</label>
                                    <textarea id="projectResources" rows="3" placeholder="List required resources and locations..."
                                              class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 resize-none"></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-team-line text-purple-400 mr-1"></i> Team Members</label>
                                        <input type="text" id="projectTeam" placeholder="e.g., 5 staff, 10 volunteers"
                                               class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-stack-line text-purple-400 mr-1"></i> Priority</label>
                                        <select id="projectPriority" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                                            <option value="Low" class="bg-gray-800">Low</option>
                                            <option value="Medium" class="bg-gray-800">Medium</option>
                                            <option value="High" class="bg-gray-800">High</option>
                                            <option value="Critical" class="bg-gray-800">Critical</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                            <button type="button" id="cancelProjectBtn" class="px-6 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 rounded-xl text-white font-medium transition-all shadow-lg flex items-center gap-2 relative overflow-hidden group">
                                <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                                <i class="ri-save-line relative z-10"></i>
                                <span class="relative z-10">Save Project</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="projectDeleteModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" id="projectDeleteOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm px-4">
            <div class="rounded-2xl shadow-2xl p-6 text-center animate-slideIn" style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-500/10 flex items-center justify-center">
                    <i class="ri-delete-bin-line text-3xl text-red-400"></i>
                </div>
                <h3 class="text-white text-lg font-semibold mb-2">Delete Project?</h3>
                <p class="text-gray-400 text-sm mb-6">This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button id="cancelProjectDeleteBtn" class="flex-1 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                    <button id="confirmProjectDeleteBtn" class="flex-1 py-2.5 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl text-white font-medium transition-all">Delete</button>
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
#projectTable tr { transition: all 0.3s ease; }
#projectTable tr:hover { background: rgba(255,255,255,0.05); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
.tab-btn.active { color: #A855F7; border-bottom: 2px solid #A855F7; }
.tab-btn { transition: all 0.2s ease; }
input[type=range] { -webkit-appearance:none; height:6px; background:rgba(255,255,255,0.1); border-radius:5px; outline:none; }
input[type=range]::-webkit-slider-thumb { -webkit-appearance:none; width:18px; height:18px; background:linear-gradient(135deg,#A855F7,#EC4899); border-radius:50%; cursor:pointer; }
.kanban-card { background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.05); border-radius:12px; padding:1rem; transition:all 0.3s ease; cursor:grab; }
.kanban-card:hover { transform:translateY(-2px); background:rgba(255,255,255,0.05); border-color:rgba(168,85,247,0.3); }
</style>

<script>
let projects     = [];
let programsList = [];
let deleteId     = null;
let currentPage  = 1;
let rowsPerPage  = 10;
let currentView  = 'table';

const API = 'api/project_api.php';

const statusColors = {
    'Planned':   'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
    'Ongoing':   'bg-green-500/20 text-green-400 border border-green-500/30',
    'On Hold':   'bg-orange-500/20 text-orange-400 border border-orange-500/30',
    'Completed': 'bg-blue-500/20 text-blue-400 border border-blue-500/30'
};

// ---- Load programs for dropdown ----
function loadPrograms() {
    fetch(`${API}?action=get_programs`)
        .then(r => r.json())
        .then(data => {
            programsList = data;
            populateProgramDropdowns();
        })
        .catch(() => {});
}

function populateProgramDropdowns() {
    ['projectProgramFilter', 'projectProgram'].forEach(id => {
        const sel = document.getElementById(id);
        if (!sel) return;
        const first = id === 'projectProgramFilter' ? '<option value="" class="bg-gray-800">All Programs</option>' : '<option value="" class="bg-gray-800">Select Program</option>';
        sel.innerHTML = first + programsList.map(p =>
            `<option value="${p.id}" class="bg-gray-800">${p.name}</option>`
        ).join('');
    });
}

// ---- Load projects ----
function loadProjects() {
    document.getElementById('projectLoadingState').classList.remove('hidden');
    document.getElementById('projectTableWrapper').classList.add('hidden');
    document.getElementById('projectEmptyState').classList.add('hidden');
    document.getElementById('projectTableFooter')?.classList.add('hidden');

    fetch(`${API}?action=get`)
        .then(r => r.json())
        .then(data => {
            projects = data;
            document.getElementById('projectLoadingState').classList.add('hidden');
            updateStats();
            renderProjects();
        })
        .catch(() => {
            document.getElementById('projectLoadingState').innerHTML =
                '<p class="text-red-400 py-8 text-center">Failed to load. Check API connection.</p>';
        });
}

// ---- Stats ----
function updateStats() {
    const total     = projects.length;
    const ongoing   = projects.filter(p => p.status === 'Ongoing').length;
    const completed = projects.filter(p => p.status === 'Completed').length;
    const budget    = projects.reduce((s, p) => s + (parseFloat(p.budget) || 0), 0);

    document.getElementById('statTotalProjects').textContent    = total;
    document.getElementById('statOngoingProjects').textContent  = ongoing;
    document.getElementById('statCompletedProjects').textContent= completed;
    document.getElementById('statTotalBudget').textContent      = '₱' + budget.toLocaleString();
    document.getElementById('projectHeaderCount').innerHTML     = `<i class="ri-folder-2-line text-purple-400"></i> ${total} Total`;
}

// ---- Render ----
function renderProjects() {
    const search  = document.getElementById('projectSearch').value.toLowerCase();
    const program = document.getElementById('projectProgramFilter').value;
    const status  = document.getElementById('projectStatusFilter').value;

    let filtered = projects.filter(p =>
        (p.name.toLowerCase().includes(search) ||
         (p.objectives||'').toLowerCase().includes(search) ||
         (p.coordinators||'').toLowerCase().includes(search)) &&
        (!program || String(p.program_id) === String(program)) &&
        (!status  || p.status === status)
    );

    const total = filtered.length;
    document.getElementById('projectTotalCount').textContent    = total;
    document.getElementById('projectOngoingCount').textContent  = filtered.filter(p=>p.status==='Ongoing').length;
    document.getElementById('projectCompletedCount').textContent= filtered.filter(p=>p.status==='Completed').length;
    document.getElementById('showingProjectsCount').textContent = total;

    const totalPages = Math.max(1, Math.ceil(total / rowsPerPage));
    if (currentPage > totalPages) currentPage = totalPages;
    const start = (currentPage-1) * rowsPerPage;
    const paged = filtered.slice(start, start+rowsPerPage);

    document.getElementById('projectShowingStart').textContent = total ? start+1 : 0;
    document.getElementById('projectShowingEnd').textContent   = Math.min(start+rowsPerPage, total);
    document.getElementById('projectTotalRecords').textContent = total;

    if (total === 0) {
        document.getElementById('projectTableWrapper').classList.add('hidden');
        document.getElementById('projectEmptyState').classList.remove('hidden');
        document.getElementById('projectTableFooter')?.classList.add('hidden');
        document.getElementById('projectCardView').innerHTML = '';
        document.getElementById('projectPagination').innerHTML = '';
        return;
    }

    document.getElementById('projectEmptyState').classList.add('hidden');
    document.getElementById('projectTableFooter')?.classList.remove('hidden');

    if (currentView === 'table') {
        document.getElementById('projectTableWrapper').classList.remove('hidden');
        renderTableView(paged);
    } else if (currentView === 'cards') {
        document.getElementById('projectTableWrapper').classList.add('hidden');
        renderCardView(paged);
    } else {
        document.getElementById('projectTableWrapper').classList.add('hidden');
        renderKanbanView(filtered);
    }

    renderPagination(total, totalPages);
}

function getProgramName(id) {
    const p = programsList.find(x => String(x.id) === String(id));
    return p ? p.name : (id || '—');
}

function renderTableView(data) {
    document.getElementById('projectTable').innerHTML = data.map(p => {
        const sc = statusColors[p.status] || 'bg-gray-500/20 text-gray-400';
        const pct = parseInt(p.progress)||0;
        const barColor = pct >= 75 ? 'bg-green-500' : pct >= 40 ? 'bg-yellow-500' : 'bg-blue-500';
        const timeline = (p.start_date && p.end_date)
            ? `${p.start_date} → ${p.end_date}`
            : (p.timeline || '—');
        return `
        <tr class="hover:bg-white/5 transition-all">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white flex-shrink-0">
                        <i class="ri-folder-2-line"></i>
                    </div>
                    <div>
                        <span class="text-white font-medium">${p.name}</span>
                        <p class="text-gray-500 text-xs">${p.coordinators||'No coordinator'}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 text-purple-400 text-sm">${getProgramName(p.program_id)}</td>
            <td class="px-6 py-4 text-gray-400 text-sm max-w-[180px] truncate">${p.objectives||'—'}</td>
            <td class="px-6 py-4 text-gray-400 text-sm">${timeline}</td>
            <td class="px-6 py-4"><span class="text-purple-400 font-medium">₱${parseFloat(p.budget||0).toLocaleString()}</span></td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium ${sc}">${p.status||'—'}</span></td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                    <div class="w-20 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full ${barColor}" style="width:${pct}%"></div>
                    </div>
                    <span class="text-gray-400 text-xs">${pct}%</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex gap-2">
                    <button onclick="editProject(${p.id})" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-purple-500/20 border border-white/10 flex items-center justify-center transition-all group">
                        <i class="ri-edit-line text-gray-400 group-hover:text-purple-400"></i>
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
    document.getElementById('projectCardView').innerHTML = data.map(p => {
        const sc  = statusColors[p.status] || 'bg-gray-500/20 text-gray-400';
        const pct = parseInt(p.progress)||0;
        const barColor = pct >= 75 ? 'bg-green-500' : pct >= 40 ? 'bg-yellow-500' : 'bg-blue-500';
        return `
        <div class="rounded-xl p-5 relative overflow-hidden hover:scale-105 transition-all duration-300"
             style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                    <i class="ri-folder-2-line text-white text-xl"></i>
                </div>
                <span class="px-2 py-1 rounded-full text-xs font-medium ${sc}">${p.status||'—'}</span>
            </div>
            <h3 class="text-white font-semibold text-lg mb-1">${p.name}</h3>
            <p class="text-purple-400 text-sm mb-2">${getProgramName(p.program_id)}</p>
            <p class="text-gray-400 text-sm mb-3">${(p.objectives||'').substring(0,60)}${(p.objectives||'').length>60?'...':''}</p>
            <div class="space-y-1.5 mb-4">
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-group-line text-purple-400"></i>${p.target_beneficiaries||'—'}</div>
                <div class="flex items-center gap-2 text-xs text-purple-400 font-medium"><i class="ri-coins-line"></i>₱${parseFloat(p.budget||0).toLocaleString()}</div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-user-star-line text-purple-400"></i>${p.coordinators||'No coordinator'}</div>
                <div class="flex items-center gap-2">
                    <div class="flex-1 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full ${barColor}" style="width:${pct}%"></div>
                    </div>
                    <span class="text-gray-400 text-xs">${pct}%</span>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-white/10">
                <button onclick="editProject(${p.id})" class="px-3 py-1.5 bg-white/5 hover:bg-purple-500/20 rounded-lg text-gray-400 hover:text-purple-400 text-sm transition-all"><i class="ri-edit-line"></i> Edit</button>
                <button onclick="openDeleteModal(${p.id})" class="px-3 py-1.5 bg-white/5 hover:bg-red-500/20 rounded-lg text-gray-400 hover:text-red-400 text-sm transition-all"><i class="ri-delete-bin-line"></i> Delete</button>
            </div>
        </div>`;
    }).join('');
}

function renderKanbanView(data) {
    const planned   = data.filter(p => p.status === 'Planned');
    const ongoing   = data.filter(p => p.status === 'Ongoing');
    const completed = data.filter(p => p.status === 'Completed');
    document.getElementById('plannedCount').textContent   = planned.length;
    document.getElementById('ongoingCount').textContent   = ongoing.length;
    document.getElementById('completedCount').textContent = completed.length;
    const kanbanCard = arr => arr.map(p => `
        <div class="kanban-card" data-id="${p.id}">
            <div class="flex items-start justify-between mb-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                    <i class="ri-folder-2-line text-white text-sm"></i>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full bg-purple-500/20 text-purple-400">₱${parseFloat(p.budget||0).toLocaleString()}</span>
            </div>
            <h4 class="text-white font-medium text-sm mb-1">${p.name}</h4>
            <p class="text-gray-400 text-xs mb-2">${p.coordinators||'No coordinator'}</p>
            <div class="flex items-center gap-2">
                <div class="flex-1 h-1 bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full ${parseInt(p.progress||0)>=75?'bg-green-500':parseInt(p.progress||0)>=40?'bg-yellow-500':'bg-blue-500'}" style="width:${parseInt(p.progress||0)}%"></div>
                </div>
                <span class="text-gray-400 text-xs">${parseInt(p.progress||0)}%</span>
            </div>
        </div>`).join('');
    document.getElementById('plannedColumn').innerHTML   = kanbanCard(planned);
    document.getElementById('ongoingColumn').innerHTML   = kanbanCard(ongoing);
    document.getElementById('completedColumn').innerHTML = kanbanCard(completed);
}

function renderPagination(total, totalPages) {
    document.getElementById('projectPagination').innerHTML = `
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

function changePage(p) { currentPage = p; renderProjects(); }

// ---- Modal ----
function openProjectModal(edit=false) {
    populateProgramDropdowns();
    document.getElementById('projectModal').classList.remove('hidden');
    document.getElementById('projectModalTitle').textContent = edit ? 'Edit Project' : 'Add New Project';
    // reset tabs
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
    document.getElementById('tab-basic').classList.remove('hidden');
    document.querySelectorAll('.tab-btn').forEach(b => { b.classList.remove('active'); b.style.borderBottom=''; b.style.color=''; });
    document.querySelector('[data-tab="basic"]').classList.add('active');
}
function closeProjectModal() {
    document.getElementById('projectModal').classList.add('hidden');
    document.getElementById('projectForm').reset();
    document.getElementById('projectId').value = '';
    document.getElementById('progressValue').textContent = '0%';
}
function openDeleteModal(id) { deleteId = id; document.getElementById('projectDeleteModal').classList.remove('hidden'); }
function closeDeleteModal() { document.getElementById('projectDeleteModal').classList.add('hidden'); deleteId = null; }

// ---- Edit ----
function editProject(id) {
    const p = projects.find(x => x.id == id);
    if (!p) return;
    document.getElementById('projectId').value           = p.id;
    document.getElementById('projectName').value         = p.name;
    document.getElementById('projectObjectives').value   = p.objectives || '';
    document.getElementById('projectBeneficiaries').value= p.target_beneficiaries || '';
    document.getElementById('projectStartDate').value    = p.start_date || '';
    document.getElementById('projectEndDate').value      = p.end_date || '';
    document.getElementById('projectBudget').value       = p.budget || '';
    document.getElementById('projectCoordinators').value = p.coordinators || '';
    document.getElementById('projectResources').value    = p.resources || '';
    document.getElementById('projectStatus').value       = p.status || 'Planned';
    document.getElementById('projectProgress').value     = p.progress || 0;
    document.getElementById('projectTeam').value         = p.team || '';
    document.getElementById('projectPriority').value     = p.priority || 'Medium';
    document.getElementById('progressValue').textContent = (p.progress||0) + '%';
    // set program dropdown
    setTimeout(() => { document.getElementById('projectProgram').value = p.program_id || ''; }, 100);
    openProjectModal(true);
}

// ---- Form Submit ----
document.getElementById('projectForm').addEventListener('submit', e => {
    e.preventDefault();
    const id   = document.getElementById('projectId').value;
    const name = document.getElementById('projectName').value.trim();
    if (!name) { alert('Project name is required.'); return; }

    const start = document.getElementById('projectStartDate').value;
    const end   = document.getElementById('projectEndDate').value;
    const timeline = start && end ? `${start} to ${end}` : '';

    const fd = new FormData();
    fd.append('action',        id ? 'update' : 'save');
    if (id) fd.append('id', id);
    fd.append('name',          name);
    fd.append('program_id',    document.getElementById('projectProgram').value || 0);
    fd.append('objectives',    document.getElementById('projectObjectives').value.trim());
    fd.append('beneficiaries', document.getElementById('projectBeneficiaries').value.trim());
    fd.append('timeline',      timeline);
    fd.append('start_date',    start);
    fd.append('end_date',      end);
    fd.append('budget',        document.getElementById('projectBudget').value || 0);
    fd.append('coordinators',  document.getElementById('projectCoordinators').value.trim());
    fd.append('resources',     document.getElementById('projectResources').value.trim());
    fd.append('status',        document.getElementById('projectStatus').value);
    fd.append('progress',      document.getElementById('projectProgress').value);
    fd.append('team',          document.getElementById('projectTeam').value.trim());
    fd.append('priority',      document.getElementById('projectPriority').value);

    fetch(API, { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closeProjectModal(); loadProjects(); }
            else alert('Error: ' + (data.message||'Unknown error'));
        })
        .catch(() => alert('Network error. Try again.'));
});

// ---- Delete ----
document.getElementById('confirmProjectDeleteBtn').addEventListener('click', () => {
    if (!deleteId) return;
    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('id', deleteId);
    fetch(API, { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closeDeleteModal(); loadProjects(); }
            else alert('Error: ' + (data.message||'Unknown error'));
        });
});

// ---- Export ----
function exportProjects() {
    if (!projects.length) { alert('No projects to export.'); return; }
    const rows = [['Name','Program','Objectives','Beneficiaries','Timeline','Budget','Coordinators','Status','Progress']];
    projects.forEach(p => rows.push([p.name, getProgramName(p.program_id), p.objectives||'',
        p.target_beneficiaries||'', p.timeline||'', p.budget||0,
        p.coordinators||'', p.status||'', p.progress||0]));
    const csv = rows.map(r => r.map(c=>`"${c}"`).join(',')).join('\n');
    Object.assign(document.createElement('a'),{href:'data:text/csv,'+encodeURIComponent(csv),download:'projects.csv'}).click();
}

// ---- Event Listeners ----
document.getElementById('addProjectBtn').addEventListener('click', () => openProjectModal());
document.getElementById('addFirstProjectBtn')?.addEventListener('click', () => openProjectModal());
document.getElementById('cancelProjectBtn').addEventListener('click', closeProjectModal);
document.getElementById('closeProjectModalBtn').addEventListener('click', closeProjectModal);
document.getElementById('projectModalOverlay').addEventListener('click', closeProjectModal);
document.getElementById('cancelProjectDeleteBtn').addEventListener('click', closeDeleteModal);
document.getElementById('projectDeleteOverlay').addEventListener('click', closeDeleteModal);
document.getElementById('projectSearch').addEventListener('input', () => { currentPage=1; renderProjects(); });
document.getElementById('projectProgramFilter').addEventListener('change', () => { currentPage=1; renderProjects(); });
document.getElementById('projectStatusFilter').addEventListener('change', () => { currentPage=1; renderProjects(); });
document.getElementById('clearProjectFilters').addEventListener('click', () => {
    document.getElementById('projectSearch').value = '';
    document.getElementById('projectProgramFilter').value = '';
    document.getElementById('projectStatusFilter').value = '';
    currentPage=1; renderProjects();
});
document.getElementById('projectRowsPerPage').addEventListener('change', e => {
    rowsPerPage = parseInt(e.target.value); currentPage=1; renderProjects();
});
document.getElementById('projectProgress').addEventListener('input', e => {
    document.getElementById('progressValue').textContent = e.target.value + '%';
});

// View toggle
function setView(view) {
    currentView = view;
    ['table','cards','kanban'].forEach(v => {
        const btn = document.getElementById(`project${v.charAt(0).toUpperCase()+v.slice(1)}ViewBtn`)||
                    document.getElementById(`projectTableViewBtn`);
        if (v === 'table') {
            document.getElementById('projectTableView').classList.toggle('hidden', view!=='table');
        } else if (v === 'cards') {
            document.getElementById('projectCardView').classList.toggle('hidden', view!=='cards');
        } else {
            document.getElementById('projectKanbanView').classList.toggle('hidden', view!=='kanban');
        }
    });
    ['projectTableViewBtn','projectCardViewBtn','projectKanbanViewBtn'].forEach(id => {
        const btn = document.getElementById(id);
        btn.className = 'px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    });
    const activeMap = { table:'projectTableViewBtn', cards:'projectCardViewBtn', kanban:'projectKanbanViewBtn' };
    document.getElementById(activeMap[view]).className = 'px-3 py-1.5 bg-purple-500/20 text-purple-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    renderProjects();
}

document.getElementById('projectTableViewBtn').addEventListener('click', () => setView('table'));
document.getElementById('projectCardViewBtn').addEventListener('click', () => setView('cards'));
document.getElementById('projectKanbanViewBtn').addEventListener('click', () => setView('kanban'));

// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        const tab = e.currentTarget.dataset.tab;
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        e.currentTarget.classList.add('active');
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
        document.getElementById(`tab-${tab}`).classList.remove('hidden');
    });
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeProjectModal(); closeDeleteModal(); }
});

// ---- Init ----
loadPrograms();
loadProjects();
</script>
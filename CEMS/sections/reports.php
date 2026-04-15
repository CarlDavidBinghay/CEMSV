<?php include_once __DIR__ . '/../db.php'; ?>

<section id="reports-section" class="space-y-6">

    <!-- Premium Header -->
    <div class="rounded-3xl p-6 relative overflow-hidden group"
         style="background: linear-gradient(135deg, #212529 0%, #343A40 50%, #495057 100%);">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-indigo-500/20 to-purple-500/20 rounded-full blur-3xl -mr-20 -mt-20 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-gradient-to-tr from-blue-500/20 to-indigo-500/20 rounded-full blur-2xl -ml-10 -mb-10 animate-pulse delay-1000"></div>
        </div>
        <div class="absolute top-10 right-20 w-16 h-16 border border-indigo-500/20 rounded-2xl rotate-45 animate-float-slow"></div>
        <div class="absolute bottom-10 left-20 w-12 h-12 border border-indigo-500/20 rounded-full animate-float"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.5) 1px, transparent 0); background-size: 40px 40px;"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-xs text-white/40 uppercase tracking-[0.2em] font-light">REPORTS & ANALYTICS</span>
                <span class="w-1 h-1 rounded-full bg-indigo-400 animate-pulse"></span>
                <span class="text-xs bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-white/80 border border-white/20 flex items-center gap-1" id="reportHeaderCount">
                    <i class="ri-bar-chart-2-line text-indigo-400"></i> 0 Total
                </span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2 text-white">
                        Reports & Analytics
                        <span class="text-white/40 mx-2">/</span>
                        <span class="text-white/60 text-2xl">Dashboard</span>
                    </h1>
                    <p class="text-white/30 text-sm flex items-center gap-2">
                        <i class="ri-sparkling-line text-indigo-400 animate-pulse"></i>
                        Generate insights and visualize community impact data
                    </p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportReports()"
                            class="px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all">
                        <i class="ri-download-line text-indigo-400"></i>
                        <span class="hidden md:inline">Export CSV</span>
                    </button>
                    <button id="addReportBtn"
                            class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all shadow-lg shadow-indigo-500/20 relative overflow-hidden group">
                        <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                        <i class="ri-add-line relative z-10"></i>
                        <span class="hidden md:inline relative z-10">Generate Report</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Stats from DB -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Reports</p>
                <p class="text-3xl font-bold text-white mt-1" id="statTotalReports">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center shadow-lg">
                    <i class="ri-bar-chart-2-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs">All generated reports</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Beneficiaries</p>
                <p class="text-3xl font-bold text-white mt-1" id="statBeneficiary">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg">
                    <i class="ri-user-3-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-blue-400 text-xs">From beneficiaries table</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-green-500/10 to-emerald-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Programs</p>
                <p class="text-3xl font-bold text-white mt-1" id="statPerformance">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg">
                    <i class="ri-line-chart-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-green-400 text-xs">Active programs in DB</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-amber-500/10 to-orange-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Resources</p>
                <p class="text-3xl font-bold text-white mt-1" id="statResources">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg">
                    <i class="ri-stack-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-amber-400 text-xs">Total resources in DB</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="rounded-2xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="reportSearch" placeholder="Search reports by name, category, or description..."
                       class="w-full pl-12 pr-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all hover:bg-black/30">
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <select id="reportCategoryFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none cursor-pointer min-w-[200px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Categories</option>
                        <option value="Beneficiary Statistics" class="bg-gray-800">Beneficiary Statistics</option>
                        <option value="Volunteer Statistics" class="bg-gray-800">Volunteer Statistics</option>
                        <option value="Program/Project Performance" class="bg-gray-800">Program/Project Performance</option>
                        <option value="Resource Utilization" class="bg-gray-800">Resource Utilization</option>
                        <option value="Partner & Donor Contributions" class="bg-gray-800">Partner & Donor Contributions</option>
                        <option value="Evaluation Results" class="bg-gray-800">Evaluation Results</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <div class="relative">
                    <select id="reportDateFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none cursor-pointer min-w-[140px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Time</option>
                        <option value="today" class="bg-gray-800">Today</option>
                        <option value="week" class="bg-gray-800">This Week</option>
                        <option value="month" class="bg-gray-800">This Month</option>
                        <option value="year" class="bg-gray-800">This Year</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <button id="clearReportFilters"
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
            <button id="reportTableViewBtn" class="px-3 py-1.5 bg-indigo-500/20 text-indigo-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-table-line"></i> Table</button>
            <button id="reportCardViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-layout-grid-line"></i> Cards</button>
            <button id="reportAnalyticsViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-pie-chart-line"></i> Analytics</button>
        </div>
        <div class="text-white/40 text-sm"><span id="showingReportsCount">0</span> reports</div>
    </div>

    <!-- Table View -->
    <div id="reportTableView" class="rounded-2xl overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div id="reportLoadingState" class="text-center py-12">
            <div class="relative inline-block">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full blur-xl opacity-50 animate-pulse"></div>
                <div class="relative w-16 h-16 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center mb-4 mx-auto">
                    <i class="ri-loader-4-line animate-spin text-2xl text-white"></i>
                </div>
            </div>
            <p class="text-gray-400 mt-4">Loading reports...</p>
        </div>
        <div id="reportTableWrapper" class="hidden overflow-x-auto">
            <table class="w-full">
                <thead style="background:#1E2228; border-bottom:1px solid rgba(255,255,255,0.05);">
                    <tr>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Report</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Category</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Description</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Date</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Format</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="reportTable" style="background:#2A2F37;" class="divide-y divide-white/5"></tbody>
            </table>
        </div>
        <div id="reportEmptyState" class="hidden text-center py-12">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-indigo-500/20 to-purple-500/20 flex items-center justify-center">
                <i class="ri-bar-chart-2-line text-4xl text-indigo-400/50"></i>
            </div>
            <h3 class="text-white text-lg font-semibold mb-2">No reports found</h3>
            <p class="text-gray-400 text-sm mb-4">Get started by generating your first report</p>
            <button id="addFirstReportBtn" class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl font-medium transition-all inline-flex items-center gap-2">
                <i class="ri-add-line"></i> Generate Report
            </button>
        </div>
        <div id="reportTableFooter" class="hidden flex items-center justify-between px-6 py-4" style="background:#1E2228; border-top:1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center gap-4 text-sm">
                <span class="text-gray-400">Total: <b id="reportTotalCount" class="text-white">0</b></span>
                <span class="text-gray-400">PDF: <b id="reportPdfCount" class="text-red-400">0</b></span>
                <span class="text-gray-400">Excel: <b id="reportExcelCount" class="text-green-400">0</b></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-400 text-sm">Rows:</span>
                <select id="reportRowsPerPage" class="bg-black/20 border border-white/10 rounded-lg text-white px-3 py-1.5 text-sm focus:outline-none">
                    <option value="5" class="bg-gray-800">5</option>
                    <option value="10" selected class="bg-gray-800">10</option>
                    <option value="25" class="bg-gray-800">25</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Card View -->
    <div id="reportCardView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>

    <!-- Analytics View — Live from DB -->
    <div id="reportAnalyticsView" class="hidden grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="rounded-xl p-5" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <h3 class="text-white font-semibold mb-4 flex items-center gap-2"><span class="w-1 h-5 bg-indigo-400 rounded-full"></span>Reports by Category</h3>
            <div class="space-y-4" id="categoryDistribution"></div>
        </div>
        <div class="rounded-xl p-5" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <h3 class="text-white font-semibold mb-4 flex items-center gap-2"><span class="w-1 h-5 bg-green-400 rounded-full"></span>Report Formats</h3>
            <div class="space-y-4" id="formatDistribution"></div>
        </div>
        <div class="lg:col-span-2 rounded-xl p-5" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <h3 class="text-white font-semibold mb-4 flex items-center gap-2"><span class="w-1 h-5 bg-amber-400 rounded-full"></span>Live System Summary (from DB)</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="quickStats"></div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-2">
        <div class="text-gray-400 text-sm">
            Showing <span id="reportShowingStart">0</span> to <span id="reportShowingEnd">0</span> of <span id="reportTotalRecords">0</span>
        </div>
        <div class="flex gap-2" id="reportPagination"></div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="reportModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" id="reportModalOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl px-4 max-h-screen overflow-y-auto py-6">
            <div class="rounded-2xl shadow-2xl p-6 relative animate-slideIn overflow-hidden"
                 style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-blue-500/10 to-indigo-500/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl blur-md animate-pulse"></div>
                            <div class="relative w-14 h-14 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                                <i class="ri-file-chart-line text-2xl text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent" id="reportModalTitle">Generate Report</h2>
                            <p class="text-gray-400 text-sm">Create a new report with custom parameters</p>
                        </div>
                        <button id="closeReportModalBtn" class="ml-auto w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center border border-white/10 transition-all">
                            <i class="ri-close-line text-gray-400"></i>
                        </button>
                    </div>
                    <form id="reportForm" class="space-y-4">
                        <input type="hidden" id="reportId">

                        <!-- Basic Info -->
                        <h3 class="text-white text-sm font-semibold flex items-center gap-2"><i class="ri-information-line text-indigo-400"></i> Basic Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-file-text-line text-indigo-400 mr-1"></i> Report Name *</label>
                                <input type="text" id="reportName" placeholder="e.g., Q1 2025 Beneficiary Analysis"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-calendar-line text-indigo-400 mr-1"></i> Report Date</label>
                                <input type="date" id="reportDate"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Config -->
                        <div class="pt-4 border-t border-white/10">
                            <h3 class="text-white text-sm font-semibold flex items-center gap-2 mb-4"><i class="ri-settings-line text-indigo-400"></i> Report Configuration</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-price-tag-3-line text-indigo-400 mr-1"></i> Category *</label>
                                    <select id="reportCategory" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="" class="bg-gray-800">Select Category</option>
                                        <option value="Beneficiary Statistics" class="bg-gray-800">Beneficiary Statistics</option>
                                        <option value="Volunteer Statistics" class="bg-gray-800">Volunteer Statistics</option>
                                        <option value="Program/Project Performance" class="bg-gray-800">Program/Project Performance</option>
                                        <option value="Resource Utilization" class="bg-gray-800">Resource Utilization</option>
                                        <option value="Partner & Donor Contributions" class="bg-gray-800">Partner & Donor Contributions</option>
                                        <option value="Evaluation Results" class="bg-gray-800">Evaluation Results</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-file-copy-line text-indigo-400 mr-1"></i> Format</label>
                                    <select id="reportFormat" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="PDF" class="bg-gray-800">PDF Document</option>
                                        <option value="Excel" class="bg-gray-800">Excel Spreadsheet</option>
                                        <option value="CSV" class="bg-gray-800">CSV File</option>
                                        <option value="HTML" class="bg-gray-800">HTML Report</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-file-text-line text-indigo-400 mr-1"></i> Description / Metrics</label>
                                <textarea id="reportDesc" rows="3" placeholder="Describe the report content, key metrics, and insights..."
                                          class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="pt-4 border-t border-white/10">
                            <h3 class="text-white text-sm font-semibold flex items-center gap-2 mb-4"><i class="ri-filter-line text-indigo-400"></i> Data Filters</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Time Period</label>
                                    <select id="reportTimePeriod" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="all" class="bg-gray-800">All Time</option>
                                        <option value="today" class="bg-gray-800">Today</option>
                                        <option value="week" class="bg-gray-800">This Week</option>
                                        <option value="month" class="bg-gray-800">This Month</option>
                                        <option value="quarter" class="bg-gray-800">This Quarter</option>
                                        <option value="year" class="bg-gray-800">This Year</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Data Source</label>
                                    <select id="reportDataSource" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="all" class="bg-gray-800">All Sources</option>
                                        <option value="beneficiaries" class="bg-gray-800">Beneficiaries</option>
                                        <option value="programs" class="bg-gray-800">Programs</option>
                                        <option value="projects" class="bg-gray-800">Projects</option>
                                        <option value="resources" class="bg-gray-800">Resources</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule -->
                        <div class="pt-4 border-t border-white/10">
                            <h3 class="text-white text-sm font-semibold flex items-center gap-2 mb-4"><i class="ri-time-line text-indigo-400"></i> Schedule & Sharing</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Schedule Report</label>
                                    <select id="reportSchedule" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="none" class="bg-gray-800">No Schedule</option>
                                        <option value="daily" class="bg-gray-800">Daily</option>
                                        <option value="weekly" class="bg-gray-800">Weekly</option>
                                        <option value="monthly" class="bg-gray-800">Monthly</option>
                                        <option value="quarterly" class="bg-gray-800">Quarterly</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Recipients</label>
                                    <input type="text" id="reportRecipients" placeholder="email@example.com"
                                           class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                            <button type="button" id="cancelReportBtn" class="px-6 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 rounded-xl text-white font-medium transition-all shadow-lg flex items-center gap-2 relative overflow-hidden group">
                                <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                                <i class="ri-save-line relative z-10"></i>
                                <span class="relative z-10">Generate Report</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="reportDeleteModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" id="reportDeleteOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm px-4">
            <div class="rounded-2xl shadow-2xl p-6 text-center animate-slideIn" style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-500/10 flex items-center justify-center">
                    <i class="ri-delete-bin-line text-3xl text-red-400"></i>
                </div>
                <h3 class="text-white text-lg font-semibold mb-2">Delete Report?</h3>
                <p class="text-gray-400 text-sm mb-6">This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button id="cancelReportDeleteBtn" class="flex-1 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                    <button id="confirmReportDeleteBtn" class="flex-1 py-2.5 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl text-white font-medium transition-all">Delete</button>
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
#reportTable tr { transition: all 0.3s ease; }
#reportTable tr:hover { background:rgba(255,255,255,0.05); transform:translateY(-2px); box-shadow:0 5px 15px rgba(0,0,0,0.3); }
</style>

<script>
let reports    = [];
let dbStats    = {};
let deleteId   = null;
let currentPage= 1;
let rowsPerPage= 10;
let currentView= 'table';

const API = 'api/report_api.php';

const categoryColors = {
    'Beneficiary Statistics':        'from-blue-500 to-cyan-500',
    'Volunteer Statistics':          'from-green-500 to-emerald-500',
    'Program/Project Performance':   'from-purple-500 to-pink-500',
    'Resource Utilization':          'from-amber-500 to-orange-500',
    'Partner & Donor Contributions': 'from-indigo-500 to-purple-500',
    'Evaluation Results':            'from-red-500 to-rose-500'
};
const formatBadge = {
    'PDF':   'bg-red-500/20 text-red-400',
    'Excel': 'bg-green-500/20 text-green-400',
    'CSV':   'bg-blue-500/20 text-blue-400',
    'HTML':  'bg-purple-500/20 text-purple-400'
};

// ---- Load ----
function loadReports() {
    document.getElementById('reportLoadingState').classList.remove('hidden');
    document.getElementById('reportTableWrapper').classList.add('hidden');
    document.getElementById('reportEmptyState').classList.add('hidden');
    document.getElementById('reportTableFooter')?.classList.add('hidden');

    Promise.all([
        fetch(`${API}?action=get`).then(r=>r.json()).catch(()=>[]),
        fetch(`${API}?action=get_stats`).then(r=>r.json()).catch(()=>({}))
    ]).then(([reps, stats]) => {
        reports  = Array.isArray(reps) ? reps : [];
        dbStats  = stats || {};
        document.getElementById('reportLoadingState').classList.add('hidden');
        updateStats();
        renderReports();
        if (currentView === 'analytics') renderAnalytics();
    }).catch(err => {
        document.getElementById('reportLoadingState').innerHTML =
            `<p class="text-red-400 py-8 text-center">Failed to load: ${err.message||'Check API connection.'}</p>`;
    });
}

function updateStats() {
    const total = reports.length;
    document.getElementById('statTotalReports').textContent = total;
    document.getElementById('statBeneficiary').textContent  = dbStats.beneficiaries || 0;
    document.getElementById('statPerformance').textContent  = dbStats.programs || 0;
    document.getElementById('statResources').textContent    = dbStats.resources || 0;
    document.getElementById('reportTotalCount').textContent = total;
    document.getElementById('reportPdfCount').textContent   = reports.filter(r=>r.format==='PDF').length;
    document.getElementById('reportExcelCount').textContent = reports.filter(r=>r.format==='Excel').length;
    document.getElementById('reportHeaderCount').innerHTML  = `<i class="ri-bar-chart-2-line text-indigo-400"></i> ${total} Total`;
}

function renderReports() {
    const search   = document.getElementById('reportSearch').value.toLowerCase();
    const category = document.getElementById('reportCategoryFilter').value;
    const dateF    = document.getElementById('reportDateFilter').value;
    const now      = new Date();

    let filtered = reports.filter(r => {
        const matchText = (r.name||'').toLowerCase().includes(search) ||
                          (r.description||'').toLowerCase().includes(search);
        const matchCat  = !category || r.category === category;
        let   matchDate = true;
        if (dateF && r.report_date) {
            const d = new Date(r.report_date);
            if (dateF === 'today')  matchDate = d.toDateString() === now.toDateString();
            if (dateF === 'week')   matchDate = (now-d) <= 7*86400000;
            if (dateF === 'month')  matchDate = d.getMonth()===now.getMonth() && d.getFullYear()===now.getFullYear();
            if (dateF === 'year')   matchDate = d.getFullYear()===now.getFullYear();
        }
        return matchText && matchCat && matchDate;
    });

    const total = filtered.length;
    document.getElementById('showingReportsCount').textContent = total;
    const totalPages = Math.max(1, Math.ceil(total/rowsPerPage));
    if (currentPage > totalPages) currentPage = totalPages;
    const start = (currentPage-1)*rowsPerPage;
    const paged = filtered.slice(start, start+rowsPerPage);

    document.getElementById('reportShowingStart').textContent = total ? start+1 : 0;
    document.getElementById('reportShowingEnd').textContent   = Math.min(start+rowsPerPage, total);
    document.getElementById('reportTotalRecords').textContent = total;

    if (total === 0) {
        document.getElementById('reportTableWrapper').classList.add('hidden');
        document.getElementById('reportEmptyState').classList.remove('hidden');
        document.getElementById('reportTableFooter')?.classList.add('hidden');
        document.getElementById('reportCardView').innerHTML = '';
        document.getElementById('reportPagination').innerHTML = '';
        return;
    }
    document.getElementById('reportEmptyState').classList.add('hidden');
    document.getElementById('reportTableFooter')?.classList.remove('hidden');

    if (currentView === 'table') {
        document.getElementById('reportTableWrapper').classList.remove('hidden');
        renderTableView(paged);
    } else if (currentView === 'cards') {
        document.getElementById('reportTableWrapper').classList.add('hidden');
        renderCardView(paged);
    } else {
        document.getElementById('reportTableWrapper').classList.add('hidden');
        renderAnalytics();
    }
    renderPagination(total, totalPages);
}

function renderTableView(data) {
    document.getElementById('reportTable').innerHTML = data.map(r => {
        const cc = categoryColors[r.category] || 'from-gray-500 to-gray-600';
        const fb = formatBadge[r.format]      || 'bg-gray-500/20 text-gray-400';
        return `
        <tr class="hover:bg-white/5 transition-all">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white flex-shrink-0">
                        <i class="ri-file-chart-line"></i>
                    </div>
                    <div>
                        <span class="text-white font-medium">${r.name}</span>
                        <p class="text-gray-500 text-xs">REP-${r.id}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r ${cc} text-white">${r.category||'—'}</span></td>
            <td class="px-6 py-4 text-gray-400 text-sm max-w-[200px] truncate">${r.description||'—'}</td>
            <td class="px-6 py-4 text-gray-400 text-sm">${r.report_date||'—'}</td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium ${fb}">${r.format||'—'}</span></td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">${r.status||'Generated'}</span></td>
            <td class="px-6 py-4">
                <div class="flex gap-2">
                    <button onclick="editReport(${r.id})" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-indigo-500/20 border border-white/10 flex items-center justify-center transition-all group">
                        <i class="ri-edit-line text-gray-400 group-hover:text-indigo-400"></i>
                    </button>
                    <button onclick="openDeleteModal(${r.id})" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-red-500/20 border border-white/10 flex items-center justify-center transition-all group">
                        <i class="ri-delete-bin-line text-gray-400 group-hover:text-red-400"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderCardView(data) {
    document.getElementById('reportCardView').innerHTML = data.map(r => {
        const fb = formatBadge[r.format] || 'bg-gray-500/20 text-gray-400';
        return `
        <div class="rounded-xl p-5 relative overflow-hidden hover:scale-105 transition-all duration-300"
             style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center">
                    <i class="ri-file-chart-line text-white text-xl"></i>
                </div>
                <span class="px-2 py-1 rounded-full text-xs font-medium ${fb}">${r.format||'—'}</span>
            </div>
            <h3 class="text-white font-semibold text-lg mb-1">${r.name}</h3>
            <p class="text-indigo-400 text-sm mb-3">${r.category||'—'}</p>
            <div class="space-y-1.5 mb-4">
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-calendar-line text-indigo-400"></i>${r.report_date||'No date'}</div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-information-line text-indigo-400"></i>${(r.description||'No description').substring(0,60)}...</div>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-white/10">
                <button onclick="editReport(${r.id})" class="px-3 py-1.5 bg-white/5 hover:bg-indigo-500/20 rounded-lg text-gray-400 hover:text-indigo-400 text-sm transition-all"><i class="ri-edit-line"></i> Edit</button>
                <button onclick="openDeleteModal(${r.id})" class="px-3 py-1.5 bg-white/5 hover:bg-red-500/20 rounded-lg text-gray-400 hover:text-red-400 text-sm transition-all"><i class="ri-delete-bin-line"></i> Delete</button>
            </div>
        </div>`;
    }).join('');
}

function renderAnalytics() {
    const total = reports.length || 1;

    // Category distribution
    const cats = {};
    reports.forEach(r => { cats[r.category||'Other'] = (cats[r.category||'Other']||0)+1; });
    document.getElementById('categoryDistribution').innerHTML = Object.entries(cats).map(([cat, cnt]) => {
        const pct = Math.round((cnt/total)*100);
        const cc = categoryColors[cat] || 'from-gray-500 to-gray-600';
        return `<div>
            <div class="flex justify-between text-sm mb-1"><span class="text-gray-400">${cat}</span><span class="text-indigo-400 font-medium">${cnt} (${pct}%)</span></div>
            <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div class="h-full bg-gradient-to-r ${cc} rounded-full" style="width:${pct}%"></div></div>
        </div>`;
    }).join('') || '<p class="text-gray-400 text-sm">No data yet.</p>';

    // Format distribution
    const fmts = {};
    reports.forEach(r => { fmts[r.format||'Other'] = (fmts[r.format||'Other']||0)+1; });
    document.getElementById('formatDistribution').innerHTML = Object.entries(fmts).map(([fmt, cnt]) => {
        const pct = Math.round((cnt/total)*100);
        const fb  = formatBadge[fmt] || 'bg-gray-500/20 text-gray-400';
        const barColor = fb.split(' ')[0].replace('/20','').replace('bg-','bg-');
        return `<div>
            <div class="flex justify-between text-sm mb-1"><span class="text-gray-400">${fmt}</span><span class="font-medium ${fb.split(' ')[1]}">${cnt} (${pct}%)</span></div>
            <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div class="h-full ${barColor} rounded-full" style="width:${pct}%"></div></div>
        </div>`;
    }).join('') || '<p class="text-gray-400 text-sm">No data yet.</p>';

    // Live system summary from DB
    const s = dbStats;
    document.getElementById('quickStats').innerHTML = [
        { label:'Beneficiaries', val: (s.beneficiaries||0).toLocaleString(), color:'text-blue-400' },
        { label:'Programs',      val: (s.programs||0).toLocaleString(),      color:'text-green-400' },
        { label:'Projects',      val: (s.projects||0).toLocaleString(),      color:'text-purple-400' },
        { label:'Activities',    val: (s.activities||0).toLocaleString(),    color:'text-amber-400' },
        { label:'Partners',      val: (s.partners||0).toLocaleString(),      color:'text-teal-400' },
        { label:'Resources',     val: (s.resources||0).toLocaleString(),     color:'text-orange-400' },
        { label:'Staff',         val: (s.staff||0).toLocaleString(),         color:'text-cyan-400' },
        { label:'Evaluations',   val: (s.evaluations||0).toLocaleString(),   color:'text-red-400' },
    ].map(item=>`
        <div class="text-center p-3 rounded-lg" style="background:rgba(255,255,255,0.03);">
            <p class="text-2xl font-bold ${item.color}">${item.val}</p>
            <p class="text-xs text-gray-400 mt-1">${item.label}</p>
        </div>`).join('');
}

function renderPagination(total, totalPages) {
    document.getElementById('reportPagination').innerHTML = `
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

function changePage(p) { currentPage=p; renderReports(); }

// ---- Modal ----
function openReportModal(edit=false) {
    document.getElementById('reportModal').classList.remove('hidden');
    document.getElementById('reportModalTitle').textContent = edit ? 'Edit Report' : 'Generate New Report';
}
function closeReportModal() {
    document.getElementById('reportModal').classList.add('hidden');
    document.getElementById('reportForm').reset();
    document.getElementById('reportId').value = '';
}
function openDeleteModal(id) { deleteId=id; document.getElementById('reportDeleteModal').classList.remove('hidden'); }
function closeDeleteModal() { document.getElementById('reportDeleteModal').classList.add('hidden'); deleteId=null; }

// ---- Edit ----
function editReport(id) {
    const r = reports.find(x=>x.id==id);
    if (!r) return;
    document.getElementById('reportId').value          = r.id;
    document.getElementById('reportName').value        = r.name;
    document.getElementById('reportDate').value        = r.report_date || '';
    document.getElementById('reportCategory').value   = r.category || '';
    document.getElementById('reportFormat').value     = r.format || 'PDF';
    document.getElementById('reportDesc').value       = r.description || '';
    document.getElementById('reportTimePeriod').value = r.time_period || 'all';
    document.getElementById('reportDataSource').value = r.data_source || 'all';
    document.getElementById('reportSchedule').value   = r.schedule || 'none';
    document.getElementById('reportRecipients').value = r.recipients || '';
    openReportModal(true);
}

// ---- Form Submit ----
document.getElementById('reportForm').addEventListener('submit', e => {
    e.preventDefault();
    const id   = document.getElementById('reportId').value;
    const name = document.getElementById('reportName').value.trim();
    if (!name) { alert('Report name is required.'); return; }

    const fd = new FormData();
    fd.append('action',      id ? 'update' : 'save');
    if (id) fd.append('id', id);
    fd.append('name',        name);
    fd.append('report_date', document.getElementById('reportDate').value || new Date().toISOString().split('T')[0]);
    fd.append('category',    document.getElementById('reportCategory').value);
    fd.append('format',      document.getElementById('reportFormat').value);
    fd.append('description', document.getElementById('reportDesc').value.trim());
    fd.append('time_period', document.getElementById('reportTimePeriod').value);
    fd.append('data_source', document.getElementById('reportDataSource').value);
    fd.append('schedule',    document.getElementById('reportSchedule').value);
    fd.append('recipients',  document.getElementById('reportRecipients').value.trim());

    fetch(API, { method:'POST', body:fd })
        .then(r => {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.status === 'success') { closeReportModal(); loadReports(); }
                else alert('Error: ' + (data.message||'Unknown'));
            } catch(e) {
                alert('Server returned invalid response:\n' + text.substring(0,300));
            }
        })
        .catch(err => alert('Network error: ' + err.message));
});

// ---- Delete ----
document.getElementById('confirmReportDeleteBtn').addEventListener('click', () => {
    if (!deleteId) return;
    const fd = new FormData();
    fd.append('action','delete'); fd.append('id',deleteId);
    fetch(API,{method:'POST',body:fd}).then(r=>r.json()).then(data=>{
        if (data.status==='success') { closeDeleteModal(); loadReports(); }
        else alert('Error: '+(data.message||'Unknown'));
    });
});

// ---- Export ----
function exportReports() {
    if (!reports.length) { alert('No data to export.'); return; }
    const rows = [['Name','Category','Description','Date','Format','Status']];
    reports.forEach(r=>rows.push([r.name,r.category||'',r.description||'',r.report_date||'',r.format||'',r.status||'']));
    const csv = rows.map(r=>r.map(c=>`"${c}"`).join(',')).join('\n');
    Object.assign(document.createElement('a'),{href:'data:text/csv,'+encodeURIComponent(csv),download:'reports.csv'}).click();
}

// ---- View Toggle ----
function setView(view) {
    currentView = view;
    document.getElementById('reportTableView').classList.toggle('hidden', view!=='table');
    document.getElementById('reportCardView').classList.toggle('hidden', view!=='cards');
    document.getElementById('reportAnalyticsView').classList.toggle('hidden', view!=='analytics');
    ['reportTableViewBtn','reportCardViewBtn','reportAnalyticsViewBtn'].forEach(id=>{
        document.getElementById(id).className='px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    });
    const map={table:'reportTableViewBtn',cards:'reportCardViewBtn',analytics:'reportAnalyticsViewBtn'};
    document.getElementById(map[view]).className='px-3 py-1.5 bg-indigo-500/20 text-indigo-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    if (view==='analytics') renderAnalytics();
    else renderReports();
}

// ---- Events ----
document.getElementById('addReportBtn').addEventListener('click',()=>openReportModal());
document.getElementById('addFirstReportBtn')?.addEventListener('click',()=>openReportModal());
document.getElementById('cancelReportBtn').addEventListener('click',closeReportModal);
document.getElementById('closeReportModalBtn').addEventListener('click',closeReportModal);
document.getElementById('reportModalOverlay').addEventListener('click',closeReportModal);
document.getElementById('cancelReportDeleteBtn').addEventListener('click',closeDeleteModal);
document.getElementById('reportDeleteOverlay').addEventListener('click',closeDeleteModal);
document.getElementById('reportSearch').addEventListener('input',()=>{currentPage=1;renderReports();});
document.getElementById('reportCategoryFilter').addEventListener('change',()=>{currentPage=1;renderReports();});
document.getElementById('reportDateFilter').addEventListener('change',()=>{currentPage=1;renderReports();});
document.getElementById('clearReportFilters').addEventListener('click',()=>{
    ['reportSearch','reportCategoryFilter','reportDateFilter'].forEach(id=>{const el=document.getElementById(id);if(el)el.value='';});
    currentPage=1; renderReports();
});
document.getElementById('reportRowsPerPage').addEventListener('change',e=>{rowsPerPage=parseInt(e.target.value);currentPage=1;renderReports();});
document.getElementById('reportTableViewBtn').addEventListener('click',()=>setView('table'));
document.getElementById('reportCardViewBtn').addEventListener('click',()=>setView('cards'));
document.getElementById('reportAnalyticsViewBtn').addEventListener('click',()=>setView('analytics'));
document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeReportModal();closeDeleteModal();}});

loadReports();
</script> 
<?php include_once __DIR__ . '/../db.php'; ?>

<section id="evaluation-section" class="space-y-6">

    <!-- Premium Header -->
    <div class="rounded-3xl p-6 relative overflow-hidden group"
         style="background: linear-gradient(135deg, #212529 0%, #343A40 50%, #495057 100%);">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-red-500/20 to-pink-500/20 rounded-full blur-3xl -mr-20 -mt-20 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-gradient-to-tr from-rose-500/20 to-red-500/20 rounded-full blur-2xl -ml-10 -mb-10 animate-pulse delay-1000"></div>
        </div>
        <div class="absolute top-10 right-20 w-16 h-16 border border-red-500/20 rounded-2xl rotate-45 animate-float-slow"></div>
        <div class="absolute bottom-10 left-20 w-12 h-12 border border-red-500/20 rounded-full animate-float"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.5) 1px, transparent 0); background-size: 40px 40px;"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-xs text-white/40 uppercase tracking-[0.2em] font-light">EVALUATION & MONITORING</span>
                <span class="w-1 h-1 rounded-full bg-red-400 animate-pulse"></span>
                <span class="text-xs bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-white/80 border border-white/20 flex items-center gap-1" id="evalHeaderCount">
                    <i class="ri-survey-line text-red-400"></i> 0 Total
                </span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2 text-white">
                        Evaluation & Monitoring
                        <span class="text-white/40 mx-2">/</span>
                        <span class="text-white/60 text-2xl">Dashboard</span>
                    </h1>
                    <p class="text-white/30 text-sm flex items-center gap-2">
                        <i class="ri-sparkling-line text-red-400 animate-pulse"></i>
                        Track, assess, and monitor program performance and impact
                    </p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportEvaluations()"
                            class="px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all">
                        <i class="ri-download-line text-red-400"></i>
                        <span class="hidden md:inline">Export CSV</span>
                    </button>
                    <button id="addEvalBtn"
                            class="px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all shadow-lg shadow-red-500/20 relative overflow-hidden group">
                        <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                        <i class="ri-add-line relative z-10"></i>
                        <span class="hidden md:inline relative z-10">Add Evaluation</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-red-500/10 to-pink-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Evaluations</p>
                <p class="text-3xl font-bold text-white mt-1" id="statTotalEvals">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-red-500 to-pink-500 flex items-center justify-center shadow-lg">
                    <i class="ri-survey-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs">All recorded evaluations</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Needs Assessment</p>
                <p class="text-3xl font-bold text-white mt-1" id="statNeedsAssessment">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg">
                    <i class="ri-search-eye-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-blue-400 text-xs">Community assessments</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-purple-500/10 to-indigo-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Pre/Post Surveys</p>
                <p class="text-3xl font-bold text-white mt-1" id="statSurveys">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center shadow-lg">
                    <i class="ri-questionnaire-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-purple-400 text-xs">Before & after studies</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-green-500/10 to-emerald-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Feedback Forms</p>
                <p class="text-3xl font-bold text-white mt-1" id="statFeedback">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg">
                    <i class="ri-chat-smile-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-green-400 text-xs">Participant feedback</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="rounded-2xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="evalSearch" placeholder="Search by title, program, or findings..."
                       class="w-full pl-12 pr-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all hover:bg-black/30">
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <select id="evalTypeFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-red-500 appearance-none cursor-pointer min-w-[170px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Types</option>
                        <option value="Needs Assessment" class="bg-gray-800">Needs Assessment</option>
                        <option value="Pre/Post Survey" class="bg-gray-800">Pre/Post Survey</option>
                        <option value="Feedback" class="bg-gray-800">Feedback</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <div class="relative">
                    <select id="evalProgramFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-red-500 appearance-none cursor-pointer min-w-[170px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Programs</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <button id="clearEvalFilters"
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
            <button id="evalTableViewBtn" class="px-3 py-1.5 bg-red-500/20 text-red-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-table-line"></i> Table</button>
            <button id="evalCardViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-layout-grid-line"></i> Cards</button>
            <button id="evalAnalyticsViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-pie-chart-line"></i> Analytics</button>
        </div>
        <div class="text-white/40 text-sm"><span id="showingEvalsCount">0</span> evaluations</div>
    </div>

    <!-- Table View -->
    <div id="evalTableView" class="rounded-2xl overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div id="evalLoadingState" class="text-center py-12">
            <div class="relative inline-block">
                <div class="absolute inset-0 bg-gradient-to-r from-red-500 to-pink-500 rounded-full blur-xl opacity-50 animate-pulse"></div>
                <div class="relative w-16 h-16 rounded-full bg-gradient-to-r from-red-500 to-pink-500 flex items-center justify-center mb-4 mx-auto">
                    <i class="ri-loader-4-line animate-spin text-2xl text-white"></i>
                </div>
            </div>
            <p class="text-gray-400 mt-4">Loading evaluations...</p>
        </div>
        <div id="evalTableWrapper" class="hidden overflow-x-auto">
            <table class="w-full">
                <thead style="background:#1E2228; border-bottom:1px solid rgba(255,255,255,0.05);">
                    <tr>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Evaluation</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Program</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Type</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Findings</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Progress</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Date</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="evalTable" style="background:#2A2F37;" class="divide-y divide-white/5"></tbody>
            </table>
        </div>
        <div id="evalEmptyState" class="hidden text-center py-12">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-red-500/20 to-pink-500/20 flex items-center justify-center">
                <i class="ri-survey-line text-4xl text-red-400/50"></i>
            </div>
            <h3 class="text-white text-lg font-semibold mb-2">No evaluations found</h3>
            <p class="text-gray-400 text-sm mb-4">Get started by adding your first evaluation</p>
            <button id="addFirstEvalBtn" class="px-6 py-2.5 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-xl font-medium transition-all inline-flex items-center gap-2">
                <i class="ri-add-line"></i> Add Evaluation
            </button>
        </div>
        <div id="evalTableFooter" class="hidden flex items-center justify-between px-6 py-4" style="background:#1E2228; border-top:1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center gap-4 text-sm">
                <span class="text-gray-400">Total: <b id="evalTotalCount" class="text-white">0</b></span>
                <span class="text-gray-400">Completed: <b id="evalCompletedCount" class="text-green-400">0</b></span>
                <span class="text-gray-400">In Progress: <b id="evalInProgressCount" class="text-yellow-400">0</b></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-400 text-sm">Rows:</span>
                <select id="evalRowsPerPage" class="bg-black/20 border border-white/10 rounded-lg text-white px-3 py-1.5 text-sm focus:outline-none">
                    <option value="5" class="bg-gray-800">5</option>
                    <option value="10" selected class="bg-gray-800">10</option>
                    <option value="25" class="bg-gray-800">25</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Card View -->
    <div id="evalCardView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>

    <!-- Analytics View -->
    <div id="evalAnalyticsView" class="hidden grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="rounded-xl p-5" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <h3 class="text-white font-semibold mb-4 flex items-center gap-2"><span class="w-1 h-5 bg-red-400 rounded-full"></span>Type Distribution</h3>
            <div class="space-y-4">
                <div><div class="flex justify-between text-sm mb-1"><span class="text-gray-400">Needs Assessment</span><span class="text-blue-400 font-medium" id="analyticsNeedsPercent">0%</span></div>
                <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsNeedsBar" class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full" style="width:0%"></div></div></div>
                <div><div class="flex justify-between text-sm mb-1"><span class="text-gray-400">Pre/Post Surveys</span><span class="text-purple-400 font-medium" id="analyticsSurveysPercent">0%</span></div>
                <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsSurveysBar" class="h-full bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full" style="width:0%"></div></div></div>
                <div><div class="flex justify-between text-sm mb-1"><span class="text-gray-400">Feedback</span><span class="text-green-400 font-medium" id="analyticsFeedbackPercent">0%</span></div>
                <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsFeedbackBar" class="h-full bg-gradient-to-r from-green-500 to-emerald-500 rounded-full" style="width:0%"></div></div></div>
            </div>
        </div>
        <div class="rounded-xl p-5" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <h3 class="text-white font-semibold mb-4 flex items-center gap-2"><span class="w-1 h-5 bg-red-400 rounded-full"></span>Progress Overview</h3>
            <div class="space-y-4">
                <div><div class="flex justify-between text-sm mb-1"><span class="text-gray-400">Completed</span><span class="text-green-400 font-medium" id="analyticsCompletedPercent">0%</span></div>
                <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsCompletedBar" class="h-full bg-gradient-to-r from-green-500 to-emerald-500 rounded-full" style="width:0%"></div></div></div>
                <div><div class="flex justify-between text-sm mb-1"><span class="text-gray-400">In Progress</span><span class="text-yellow-400 font-medium" id="analyticsInProgressPercent">0%</span></div>
                <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsInProgressBar" class="h-full bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full" style="width:0%"></div></div></div>
                <div><div class="flex justify-between text-sm mb-1"><span class="text-gray-400">Not Started</span><span class="text-gray-400 font-medium" id="analyticsNotStartedPercent">0%</span></div>
                <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsNotStartedBar" class="h-full bg-gray-500 rounded-full" style="width:0%"></div></div></div>
            </div>
        </div>
        <div class="lg:col-span-2 rounded-xl p-5" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <h3 class="text-white font-semibold mb-4 flex items-center gap-2"><span class="w-1 h-5 bg-red-400 rounded-full"></span>Key Findings & Recommendations</h3>
            <div id="recentFindings" class="space-y-3"></div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-2">
        <div class="text-gray-400 text-sm">
            Showing <span id="evalShowingStart">0</span> to <span id="evalShowingEnd">0</span> of <span id="evalTotalRecords">0</span>
        </div>
        <div class="flex gap-2" id="evalPagination"></div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="evalModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" id="evalModalOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl px-4 max-h-screen overflow-y-auto py-6">
            <div class="rounded-2xl shadow-2xl p-6 relative animate-slideIn overflow-hidden"
                 style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-red-500/10 to-pink-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-rose-500/10 to-red-500/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl blur-md animate-pulse"></div>
                            <div class="relative w-14 h-14 rounded-xl bg-gradient-to-r from-red-500 to-pink-600 flex items-center justify-center">
                                <i class="ri-survey-line text-2xl text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-red-400 to-pink-400 bg-clip-text text-transparent" id="evalModalTitle">Add Evaluation</h2>
                            <p class="text-gray-400 text-sm">Enter evaluation details below</p>
                        </div>
                        <button id="closeEvalModalBtn" class="ml-auto w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center border border-white/10 transition-all">
                            <i class="ri-close-line text-gray-400"></i>
                        </button>
                    </div>
                    <form id="evalForm" class="space-y-4">
                        <input type="hidden" id="evalId">

                        <!-- Basic Info -->
                        <h3 class="text-white text-sm font-semibold flex items-center gap-2"><i class="ri-information-line text-red-400"></i> Basic Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-file-text-line text-red-400 mr-1"></i> Evaluation Title *</label>
                                <input type="text" id="evalTitle" placeholder="e.g., Q1 2025 Needs Assessment"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all">
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-calendar-line text-red-400 mr-1"></i> Evaluation Date</label>
                                <input type="date" id="evalDate"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-folder-2-line text-red-400 mr-1"></i> Program/Project *</label>
                                <select id="evalProgram" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="" class="bg-gray-800">Select Program</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-price-tag-3-line text-red-400 mr-1"></i> Evaluation Type *</label>
                                <select id="evalType" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="" class="bg-gray-800">Select Type</option>
                                    <option value="Needs Assessment" class="bg-gray-800">Needs Assessment</option>
                                    <option value="Pre/Post Survey" class="bg-gray-800">Pre/Post Survey</option>
                                    <option value="Feedback" class="bg-gray-800">Feedback</option>
                                </select>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="pt-4 border-t border-white/10">
                            <h3 class="text-white text-sm font-semibold flex items-center gap-2 mb-4"><i class="ri-bar-chart-2-line text-red-400"></i> Evaluation Details</h3>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-find-replace-line text-red-400 mr-1"></i> Findings / Recommendations</label>
                                <textarea id="evalFindings" rows="3" placeholder="Describe the key findings and recommendations..."
                                          class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"></textarea>
                            </div>
                            <div class="mt-4">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-progress-5-line text-red-400 mr-1"></i> Progress Notes</label>
                                <textarea id="evalProgressNotes" rows="3" placeholder="Track progress and milestones..."
                                          class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-percent-line text-red-400 mr-1"></i> Completion %</label>
                                    <input type="range" id="evalProgressPercent" min="0" max="100" value="0" class="w-full">
                                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                                        <span>0%</span>
                                        <span id="progressValue" class="text-red-400 font-medium">0%</span>
                                        <span>100%</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-flag-line text-red-400 mr-1"></i> Status</label>
                                    <select id="evalStatus" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-red-500">
                                        <option value="Not Started" class="bg-gray-800">Not Started</option>
                                        <option value="In Progress" class="bg-gray-800">In Progress</option>
                                        <option value="Completed" class="bg-gray-800">Completed</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="pt-4 border-t border-white/10">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-user-star-line text-red-400 mr-1"></i> Evaluator</label>
                                    <input type="text" id="evalEvaluator" placeholder="e.g., Dr. Juan Dela Cruz"
                                           class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-group-line text-red-400 mr-1"></i> Participants</label>
                                    <input type="text" id="evalParticipants" placeholder="e.g., 50 beneficiaries"
                                           class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                            <button type="button" id="cancelEvalBtn" class="px-6 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 rounded-xl text-white font-medium transition-all shadow-lg flex items-center gap-2 relative overflow-hidden group">
                                <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                                <i class="ri-save-line relative z-10"></i>
                                <span class="relative z-10">Save Evaluation</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="evalDeleteModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" id="evalDeleteOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm px-4">
            <div class="rounded-2xl shadow-2xl p-6 text-center animate-slideIn" style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-500/10 flex items-center justify-center">
                    <i class="ri-delete-bin-line text-3xl text-red-400"></i>
                </div>
                <h3 class="text-white text-lg font-semibold mb-2">Delete Evaluation?</h3>
                <p class="text-gray-400 text-sm mb-6">This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button id="cancelEvalDeleteBtn" class="flex-1 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                    <button id="confirmEvalDeleteBtn" class="flex-1 py-2.5 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl text-white font-medium transition-all">Delete</button>
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
#evalTable tr { transition: all 0.3s ease; }
#evalTable tr:hover { background:rgba(255,255,255,0.05); transform:translateY(-2px); box-shadow:0 5px 15px rgba(0,0,0,0.3); }
input[type=range] { -webkit-appearance:none; height:6px; background:rgba(255,255,255,0.1); border-radius:5px; outline:none; width:100%; }
input[type=range]::-webkit-slider-thumb { -webkit-appearance:none; width:18px; height:18px; background:linear-gradient(135deg,#EF4444,#EC4899); border-radius:50%; cursor:pointer; box-shadow:0 0 10px rgba(239,68,68,0.5); }
</style>

<script>
let evaluations = [];
let deleteId    = null;
let currentPage = 1;
let rowsPerPage = 10;
let currentView = 'table';

const API = 'api/evaluation_api.php';

const typeBadge = {
    'Needs Assessment': 'bg-blue-500/20 text-blue-400',
    'Pre/Post Survey':  'bg-purple-500/20 text-purple-400',
    'Feedback':         'bg-green-500/20 text-green-400'
};
const statusBadge = {
    'Completed':   'bg-green-500/20 text-green-400 border border-green-500/30',
    'In Progress': 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
    'Not Started': 'bg-gray-500/20 text-gray-400 border border-gray-500/30'
};

// ---- Load ----
function loadEvaluations() {
    document.getElementById('evalLoadingState').classList.remove('hidden');
    document.getElementById('evalTableWrapper').classList.add('hidden');
    document.getElementById('evalEmptyState').classList.add('hidden');
    document.getElementById('evalTableFooter')?.classList.add('hidden');

    // Load programs for dropdowns + evaluations in parallel
    Promise.all([
        fetch(`${API}?action=get`).then(r=>r.json()),
        fetch(`${API}?action=get_programs`).then(r=>r.json()).catch(()=>[])
    ]).then(([evals, progs]) => {
        evaluations = evals;
        document.getElementById('evalLoadingState').classList.add('hidden');
        populateProgramDropdowns(progs);
        updateStats();
        renderEvaluations();
    }).catch(() => {
        document.getElementById('evalLoadingState').innerHTML =
            '<p class="text-red-400 py-8 text-center">Failed to load. Check API connection.</p>';
    });
}

function populateProgramDropdowns(progs) {
    const base = '<option value="" class="bg-gray-800">Select Program</option>';
    const filterBase = '<option value="" class="bg-gray-800">All Programs</option>';
    const opts = progs.map(p=>`<option value="${p.name}" class="bg-gray-800">${p.name}</option>`).join('');
    document.getElementById('evalProgram').innerHTML       = base + opts;
    document.getElementById('evalProgramFilter').innerHTML = filterBase + opts;
}

function updateStats() {
    const total = evaluations.length;
    document.getElementById('statTotalEvals').textContent      = total;
    document.getElementById('statNeedsAssessment').textContent = evaluations.filter(e=>e.type==='Needs Assessment').length;
    document.getElementById('statSurveys').textContent         = evaluations.filter(e=>e.type==='Pre/Post Survey').length;
    document.getElementById('statFeedback').textContent        = evaluations.filter(e=>e.type==='Feedback').length;
    document.getElementById('evalHeaderCount').innerHTML       = `<i class="ri-survey-line text-red-400"></i> ${total} Total`;
}

function renderEvaluations() {
    const search  = document.getElementById('evalSearch').value.toLowerCase();
    const type    = document.getElementById('evalTypeFilter').value;
    const program = document.getElementById('evalProgramFilter').value;

    let filtered = evaluations.filter(e =>
        ((e.title||'').toLowerCase().includes(search) ||
         (e.findings||'').toLowerCase().includes(search) ||
         (e.program||'').toLowerCase().includes(search)) &&
        (!type    || e.type    === type) &&
        (!program || e.program === program)
    );

    const total = filtered.length;
    document.getElementById('evalTotalCount').textContent     = total;
    document.getElementById('evalCompletedCount').textContent = filtered.filter(e=>e.status==='Completed').length;
    document.getElementById('evalInProgressCount').textContent= filtered.filter(e=>e.status==='In Progress').length;
    document.getElementById('showingEvalsCount').textContent  = total;

    const totalPages = Math.max(1, Math.ceil(total/rowsPerPage));
    if (currentPage > totalPages) currentPage = totalPages;
    const start = (currentPage-1)*rowsPerPage;
    const paged = filtered.slice(start, start+rowsPerPage);

    document.getElementById('evalShowingStart').textContent = total ? start+1 : 0;
    document.getElementById('evalShowingEnd').textContent   = Math.min(start+rowsPerPage, total);
    document.getElementById('evalTotalRecords').textContent = total;

    if (total === 0) {
        document.getElementById('evalTableWrapper').classList.add('hidden');
        document.getElementById('evalEmptyState').classList.remove('hidden');
        document.getElementById('evalTableFooter')?.classList.add('hidden');
        document.getElementById('evalCardView').innerHTML = '';
        document.getElementById('evalPagination').innerHTML = '';
        return;
    }

    document.getElementById('evalEmptyState').classList.add('hidden');
    document.getElementById('evalTableFooter')?.classList.remove('hidden');

    if (currentView === 'table') {
        document.getElementById('evalTableWrapper').classList.remove('hidden');
        renderTableView(paged);
    } else if (currentView === 'cards') {
        document.getElementById('evalTableWrapper').classList.add('hidden');
        renderCardView(paged);
    } else {
        document.getElementById('evalTableWrapper').classList.add('hidden');
        renderAnalytics();
    }

    renderPagination(total, totalPages);
}

function renderTableView(data) {
    document.getElementById('evalTable').innerHTML = data.map(e => {
        const tb = typeBadge[e.type]    || 'bg-gray-500/20 text-gray-400';
        const sb = statusBadge[e.status]|| 'bg-gray-500/20 text-gray-400';
        const pct = parseInt(e.progress_percent)||0;
        return `
        <tr class="hover:bg-white/5 transition-all">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-red-500 to-pink-500 flex items-center justify-center text-white flex-shrink-0">
                        <i class="ri-survey-line"></i>
                    </div>
                    <div>
                        <span class="text-white font-medium">${e.title}</span>
                        <p class="text-gray-500 text-xs">${e.eval_date||''}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 text-red-400 text-sm">${e.program||'—'}</td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium ${tb} border border-white/10">${e.type||'—'}</span></td>
            <td class="px-6 py-4 text-gray-400 text-sm max-w-[180px]">${e.findings?(e.findings.substring(0,60)+(e.findings.length>60?'...':'')):'—'}</td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                    <div class="w-16 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-red-500 to-pink-500 rounded-full" style="width:${pct}%"></div>
                    </div>
                    <span class="text-red-400 text-xs">${pct}%</span>
                </div>
            </td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium ${sb}">${e.status||'—'}</span></td>
            <td class="px-6 py-4 text-gray-400 text-sm">${e.eval_date||'—'}</td>
            <td class="px-6 py-4">
                <div class="flex gap-2">
                    <button onclick="editEval(${e.id})" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-red-500/20 border border-white/10 flex items-center justify-center transition-all group">
                        <i class="ri-edit-line text-gray-400 group-hover:text-red-400"></i>
                    </button>
                    <button onclick="openDeleteModal(${e.id})" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-red-500/20 border border-white/10 flex items-center justify-center transition-all group">
                        <i class="ri-delete-bin-line text-gray-400 group-hover:text-red-400"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function renderCardView(data) {
    document.getElementById('evalCardView').innerHTML = data.map(e => {
        const tb  = typeBadge[e.type]    || 'bg-gray-500/20 text-gray-400';
        const sb  = statusBadge[e.status]|| 'bg-gray-500/20 text-gray-400';
        const pct = parseInt(e.progress_percent)||0;
        return `
        <div class="rounded-xl p-5 relative overflow-hidden hover:scale-105 transition-all duration-300"
             style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-500/10 to-pink-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-red-500 to-pink-500 flex items-center justify-center">
                    <i class="ri-survey-line text-white text-xl"></i>
                </div>
                <span class="px-2 py-1 rounded-full text-xs font-medium ${sb}">${e.status||'—'}</span>
            </div>
            <h3 class="text-white font-semibold text-lg mb-1">${e.title}</h3>
            <p class="text-red-400 text-sm mb-3">${e.program||'—'}</p>
            <div class="space-y-1.5 mb-4">
                <div class="flex items-center gap-2 text-xs"><i class="ri-price-tag-3-line text-red-400"></i><span class="px-2 py-0.5 rounded-full text-xs font-medium ${tb}">${e.type||'—'}</span></div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-calendar-line text-red-400"></i>${e.eval_date||'No date'}</div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-user-star-line text-red-400"></i>${e.evaluator||'—'}</div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-group-line text-red-400"></i>${e.participants||'—'}</div>
                <div class="mt-2">
                    <div class="flex justify-between text-xs mb-1"><span class="text-gray-400">Progress</span><span class="text-red-400">${pct}%</span></div>
                    <div class="h-1.5 bg-white/10 rounded-full overflow-hidden"><div class="h-full bg-gradient-to-r from-red-500 to-pink-500 rounded-full" style="width:${pct}%"></div></div>
                </div>
            </div>
            ${e.findings ? `<p class="text-gray-500 text-xs mb-3 border-t border-white/10 pt-3"><span class="text-red-400">Findings: </span>${e.findings.substring(0,80)}...</p>` : ''}
            <div class="flex justify-end gap-2">
                <button onclick="editEval(${e.id})" class="px-3 py-1.5 bg-white/5 hover:bg-red-500/20 rounded-lg text-gray-400 hover:text-red-400 text-sm transition-all"><i class="ri-edit-line"></i> Edit</button>
                <button onclick="openDeleteModal(${e.id})" class="px-3 py-1.5 bg-white/5 hover:bg-red-500/20 rounded-lg text-gray-400 hover:text-red-400 text-sm transition-all"><i class="ri-delete-bin-line"></i> Delete</button>
            </div>
        </div>`;
    }).join('');
}

function renderAnalytics() {
    const total = evaluations.length || 1;
    const needs  = evaluations.filter(e=>e.type==='Needs Assessment').length;
    const surv   = evaluations.filter(e=>e.type==='Pre/Post Survey').length;
    const feed   = evaluations.filter(e=>e.type==='Feedback').length;
    const comp   = evaluations.filter(e=>e.status==='Completed').length;
    const inp    = evaluations.filter(e=>e.status==='In Progress').length;
    const ns     = evaluations.filter(e=>e.status==='Not Started').length;

    const pct = v => Math.round((v/total)*100);
    document.getElementById('analyticsNeedsPercent').textContent     = pct(needs)+'%';
    document.getElementById('analyticsNeedsBar').style.width         = pct(needs)+'%';
    document.getElementById('analyticsSurveysPercent').textContent   = pct(surv)+'%';
    document.getElementById('analyticsSurveysBar').style.width       = pct(surv)+'%';
    document.getElementById('analyticsFeedbackPercent').textContent  = pct(feed)+'%';
    document.getElementById('analyticsFeedbackBar').style.width      = pct(feed)+'%';
    document.getElementById('analyticsCompletedPercent').textContent = pct(comp)+'%';
    document.getElementById('analyticsCompletedBar').style.width     = pct(comp)+'%';
    document.getElementById('analyticsInProgressPercent').textContent= pct(inp)+'%';
    document.getElementById('analyticsInProgressBar').style.width    = pct(inp)+'%';
    document.getElementById('analyticsNotStartedPercent').textContent= pct(ns)+'%';
    document.getElementById('analyticsNotStartedBar').style.width    = pct(ns)+'%';

    const recent = [...evaluations].sort((a,b)=>new Date(b.eval_date)-new Date(a.eval_date)).slice(0,5);
    document.getElementById('recentFindings').innerHTML = recent.map(e => `
        <div class="flex items-start gap-3 py-2 border-b border-white/5 last:border-0">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-red-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                <i class="ri-survey-line text-white text-xs"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <h4 class="text-white text-sm font-medium truncate">${e.title}</h4>
                    <span class="text-gray-500 text-xs ml-2 flex-shrink-0">${e.eval_date||''}</span>
                </div>
                <p class="text-gray-400 text-xs mt-1">${e.findings?(e.findings.substring(0,100)+'...'):'No findings recorded.'}</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-1.5 py-0.5 rounded text-xs ${e.status==='Completed'?'bg-green-500/20 text-green-400':'bg-yellow-500/20 text-yellow-400'}">${e.status}</span>
                    <span class="text-gray-500 text-xs">${e.program||''}</span>
                </div>
            </div>
        </div>`).join('');
}

function renderPagination(total, totalPages) {
    document.getElementById('evalPagination').innerHTML = `
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

function changePage(p) { currentPage = p; renderEvaluations(); }

// ---- Modal ----
function openEvalModal(edit=false) {
    document.getElementById('evalModal').classList.remove('hidden');
    document.getElementById('evalModalTitle').textContent = edit ? 'Edit Evaluation' : 'Add New Evaluation';
    if (!edit) {
        document.getElementById('evalProgressPercent').value = 0;
        document.getElementById('progressValue').textContent = '0%';
    }
}
function closeEvalModal() {
    document.getElementById('evalModal').classList.add('hidden');
    document.getElementById('evalForm').reset();
    document.getElementById('evalId').value = '';
    document.getElementById('progressValue').textContent = '0%';
}
function openDeleteModal(id) { deleteId = id; document.getElementById('evalDeleteModal').classList.remove('hidden'); }
function closeDeleteModal() { document.getElementById('evalDeleteModal').classList.add('hidden'); deleteId = null; }

// ---- Edit ----
function editEval(id) {
    const e = evaluations.find(x => x.id == id);
    if (!e) return;
    document.getElementById('evalId').value                                  = e.id;
    document.getElementById('evalTitle').value                               = e.title || '';
    document.getElementById('evalDate').value                                = e.eval_date || '';
    document.getElementById('evalProgram').value                             = e.program || '';
    document.getElementById('evalType').value                                = e.type || '';
    document.getElementById('evalFindings').value                            = e.findings || '';
    document.getElementById('evalProgressNotes').value                      = e.progress_notes || '';
    document.getElementById('evalProgressPercent').value                    = e.progress_percent || 0;
    document.getElementById('progressValue').textContent                    = (e.progress_percent||0) + '%';
    document.getElementById('evalStatus').value                              = e.status || 'Not Started';
    document.getElementById('evalEvaluator').value                           = e.evaluator || '';
    document.getElementById('evalParticipants').value                        = e.participants || '';
    openEvalModal(true);
}

// ---- Form Submit ----
document.getElementById('evalForm').addEventListener('submit', ev => {
    ev.preventDefault();
    const id    = document.getElementById('evalId').value;
    const title = document.getElementById('evalTitle').value.trim();
    if (!title) { alert('Title is required.'); return; }

    const fd = new FormData();
    fd.append('action',           id ? 'update' : 'save');
    if (id) fd.append('id', id);
    fd.append('title',            title);
    fd.append('eval_date',        document.getElementById('evalDate').value);
    fd.append('program',          document.getElementById('evalProgram').value);
    fd.append('type',             document.getElementById('evalType').value);
    fd.append('findings',         document.getElementById('evalFindings').value.trim());
    fd.append('progress_notes',   document.getElementById('evalProgressNotes').value.trim());
    fd.append('progress_percent', document.getElementById('evalProgressPercent').value);
    fd.append('status',           document.getElementById('evalStatus').value);
    fd.append('evaluator',        document.getElementById('evalEvaluator').value.trim());
    fd.append('participants',     document.getElementById('evalParticipants').value.trim());

    fetch(API, { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closeEvalModal(); loadEvaluations(); }
            else alert('Error: ' + (data.message||'Unknown'));
        })
        .catch(() => alert('Network error. Try again.'));
});

// ---- Delete ----
document.getElementById('confirmEvalDeleteBtn').addEventListener('click', () => {
    if (!deleteId) return;
    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('id', deleteId);
    fetch(API, { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closeDeleteModal(); loadEvaluations(); }
            else alert('Error: ' + (data.message||'Unknown'));
        });
});

// ---- Export ----
function exportEvaluations() {
    if (!evaluations.length) { alert('No data to export.'); return; }
    const rows = [['Title','Program','Type','Findings','Progress Notes','Progress %','Status','Date','Evaluator','Participants']];
    evaluations.forEach(e => rows.push([e.title,e.program||'',e.type||'',e.findings||'',
        e.progress_notes||'',e.progress_percent||0,e.status||'',e.eval_date||'',
        e.evaluator||'',e.participants||'']));
    const csv = rows.map(r => r.map(c=>`"${c}"`).join(',')).join('\n');
    Object.assign(document.createElement('a'),{href:'data:text/csv,'+encodeURIComponent(csv),download:'evaluations.csv'}).click();
}

// ---- View Toggle ----
function setView(view) {
    currentView = view;
    document.getElementById('evalTableView').classList.toggle('hidden', view!=='table');
    document.getElementById('evalCardView').classList.toggle('hidden', view!=='cards');
    document.getElementById('evalAnalyticsView').classList.toggle('hidden', view!=='analytics');
    ['evalTableViewBtn','evalCardViewBtn','evalAnalyticsViewBtn'].forEach(id => {
        document.getElementById(id).className = 'px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    });
    const map = { table:'evalTableViewBtn', cards:'evalCardViewBtn', analytics:'evalAnalyticsViewBtn' };
    document.getElementById(map[view]).className = 'px-3 py-1.5 bg-red-500/20 text-red-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    if (view === 'analytics') renderAnalytics();
    else renderEvaluations();
}

// ---- Slider ----
document.getElementById('evalProgressPercent').addEventListener('input', e => {
    document.getElementById('progressValue').textContent = e.target.value + '%';
});

// ---- Events ----
document.getElementById('addEvalBtn').addEventListener('click', () => openEvalModal());
document.getElementById('addFirstEvalBtn')?.addEventListener('click', () => openEvalModal());
document.getElementById('cancelEvalBtn').addEventListener('click', closeEvalModal);
document.getElementById('closeEvalModalBtn').addEventListener('click', closeEvalModal);
document.getElementById('evalModalOverlay').addEventListener('click', closeEvalModal);
document.getElementById('cancelEvalDeleteBtn').addEventListener('click', closeDeleteModal);
document.getElementById('evalDeleteOverlay').addEventListener('click', closeDeleteModal);
document.getElementById('evalSearch').addEventListener('input', () => { currentPage=1; renderEvaluations(); });
document.getElementById('evalTypeFilter').addEventListener('change', () => { currentPage=1; renderEvaluations(); });
document.getElementById('evalProgramFilter').addEventListener('change', () => { currentPage=1; renderEvaluations(); });
document.getElementById('clearEvalFilters').addEventListener('click', () => {
    ['evalSearch','evalTypeFilter','evalProgramFilter'].forEach(id => { const el=document.getElementById(id); if(el) el.value=''; });
    currentPage=1; renderEvaluations();
});
document.getElementById('evalRowsPerPage').addEventListener('change', e => {
    rowsPerPage=parseInt(e.target.value); currentPage=1; renderEvaluations();
});
document.getElementById('evalTableViewBtn').addEventListener('click', () => setView('table'));
document.getElementById('evalCardViewBtn').addEventListener('click', () => setView('cards'));
document.getElementById('evalAnalyticsViewBtn').addEventListener('click', () => setView('analytics'));
document.addEventListener('keydown', e => { if(e.key==='Escape'){closeEvalModal();closeDeleteModal();} });

loadEvaluations();
</script>
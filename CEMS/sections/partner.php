<?php include_once __DIR__ . '/../db.php'; ?>

<section id="partner-section" class="space-y-6">

    <!-- Premium Header -->
    <div class="rounded-3xl p-6 relative overflow-hidden group"
         style="background: linear-gradient(135deg, #212529 0%, #343A40 50%, #495057 100%);">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-teal-500/20 to-emerald-500/20 rounded-full blur-3xl -mr-20 -mt-20 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-gradient-to-tr from-cyan-500/20 to-teal-500/20 rounded-full blur-2xl -ml-10 -mb-10 animate-pulse delay-1000"></div>
        </div>
        <div class="absolute top-10 right-20 w-16 h-16 border border-teal-500/20 rounded-2xl rotate-45 animate-float-slow"></div>
        <div class="absolute bottom-10 left-20 w-12 h-12 border border-teal-500/20 rounded-full animate-float"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.5) 1px, transparent 0); background-size: 40px 40px;"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-xs text-white/40 uppercase tracking-[0.2em] font-light">PARTNER & DONOR MANAGEMENT</span>
                <span class="w-1 h-1 rounded-full bg-teal-400 animate-pulse"></span>
                <span class="text-xs bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-white/80 border border-white/20 flex items-center gap-1" id="partnerHeaderCount">
                    <i class="ri-hand-heart-line text-teal-400"></i> 0 Total
                </span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2 text-white">
                        Partners & Donors
                        <span class="text-white/40 mx-2">/</span>
                        <span class="text-white/60 text-2xl">Management</span>
                    </h1>
                    <p class="text-white/30 text-sm flex items-center gap-2">
                        <i class="ri-sparkling-line text-teal-400 animate-pulse"></i>
                        Manage and track all partners, donors, and contributors
                    </p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportPartners()"
                            class="px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all">
                        <i class="ri-download-line text-teal-400"></i>
                        <span class="hidden md:inline">Export CSV</span>
                    </button>
                    <button id="addPartnerBtn"
                            class="px-4 py-2 bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all shadow-lg shadow-teal-500/20 relative overflow-hidden group">
                        <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                        <i class="ri-add-line relative z-10"></i>
                        <span class="hidden md:inline relative z-10">Add Partner/Donor</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-teal-500/10 to-emerald-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Partners</p>
                <p class="text-3xl font-bold text-white mt-1" id="statTotalPartners">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg">
                    <i class="ri-hand-heart-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs">Organizations & individuals</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Donors</p>
                <p class="text-3xl font-bold text-white mt-1" id="statTotalDonors">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
                    <i class="ri-gift-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-purple-400 text-xs">♥ Active donors</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-amber-500/10 to-orange-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Contributions</p>
                <p class="text-3xl font-bold text-white mt-1" id="statTotalContributions">₱0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg">
                    <i class="ri-coins-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-amber-400 text-xs">Financial + In-kind</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Active Partnerships</p>
                <p class="text-3xl font-bold text-white mt-1" id="statActivePartnerships">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg">
                    <i class="ri-shake-hands-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-blue-400 text-xs">● Currently active</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="rounded-2xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="partnerSearch" placeholder="Search by name, type, contact, roles..."
                       class="w-full pl-12 pr-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all hover:bg-black/30">
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <select id="partnerTypeFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-teal-500 appearance-none cursor-pointer min-w-[130px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Types</option>
                        <option value="Partner" class="bg-gray-800">Partners</option>
                        <option value="Donor" class="bg-gray-800">Donors</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <div class="relative">
                    <select id="partnerContributionFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-teal-500 appearance-none cursor-pointer min-w-[150px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Contributions</option>
                        <option value="Financial" class="bg-gray-800">Financial</option>
                        <option value="In-kind" class="bg-gray-800">In-kind</option>
                        <option value="Both" class="bg-gray-800">Both</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <button id="clearPartnerFilters"
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
            <button id="partnerTableViewBtn" class="px-3 py-1.5 bg-teal-500/20 text-teal-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-table-line"></i> Table</button>
            <button id="partnerCardViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-layout-grid-line"></i> Cards</button>
            <button id="partnerAnalyticsViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-pie-chart-line"></i> Analytics</button>
        </div>
        <div class="text-white/40 text-sm"><span id="showingPartnersCount">0</span> displayed</div>
    </div>

    <!-- Table View -->
    <div id="partnerTableView" class="rounded-2xl overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div id="partnerLoadingState" class="text-center py-12">
            <div class="relative inline-block">
                <div class="absolute inset-0 bg-gradient-to-r from-teal-500 to-emerald-500 rounded-full blur-xl opacity-50 animate-pulse"></div>
                <div class="relative w-16 h-16 rounded-full bg-gradient-to-r from-teal-500 to-emerald-500 flex items-center justify-center mb-4 mx-auto">
                    <i class="ri-loader-4-line animate-spin text-2xl text-white"></i>
                </div>
            </div>
            <p class="text-gray-400 mt-4">Loading partners and donors...</p>
        </div>
        <div id="partnerTableWrapper" class="hidden overflow-x-auto">
            <table class="w-full">
                <thead style="background:#1E2228; border-bottom:1px solid rgba(255,255,255,0.05);">
                    <tr>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Partner/Donor</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Type</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Contact</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Roles / Programs</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Contribution</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Total Value</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="partnerTable" style="background:#2A2F37;" class="divide-y divide-white/5"></tbody>
            </table>
        </div>
        <div id="partnerEmptyState" class="hidden text-center py-12">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-teal-500/20 to-emerald-500/20 flex items-center justify-center">
                <i class="ri-hand-heart-line text-4xl text-teal-400/50"></i>
            </div>
            <h3 class="text-white text-lg font-semibold mb-2">No partners or donors found</h3>
            <p class="text-gray-400 text-sm mb-4">Get started by adding your first partner or donor</p>
            <button id="addFirstPartnerBtn" class="px-6 py-2.5 bg-gradient-to-r from-teal-500 to-emerald-600 text-white rounded-xl font-medium transition-all inline-flex items-center gap-2">
                <i class="ri-add-line"></i> Add Partner/Donor
            </button>
        </div>
        <div id="partnerTableFooter" class="hidden flex items-center justify-between px-6 py-4" style="background:#1E2228; border-top:1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center gap-4 text-sm">
                <span class="text-gray-400">Total: <b id="partnerTotalCount" class="text-white">0</b></span>
                <span class="text-gray-400">Partners: <b id="partnerCount" class="text-teal-400">0</b></span>
                <span class="text-gray-400">Donors: <b id="donorCount" class="text-purple-400">0</b></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-400 text-sm">Rows:</span>
                <select id="partnerRowsPerPage" class="bg-black/20 border border-white/10 rounded-lg text-white px-3 py-1.5 text-sm focus:outline-none">
                    <option value="5" class="bg-gray-800">5</option>
                    <option value="10" selected class="bg-gray-800">10</option>
                    <option value="25" class="bg-gray-800">25</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Card View -->
    <div id="partnerCardView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>

    <!-- Analytics View -->
    <div id="partnerAnalyticsView" class="hidden grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="rounded-xl p-5" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <h3 class="text-white font-semibold mb-4 flex items-center gap-2"><span class="w-1 h-5 bg-teal-400 rounded-full"></span>Partner/Donor Distribution</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1"><span class="text-gray-400">Partners</span><span class="text-teal-400 font-medium" id="analyticsPartnerPercent">0%</span></div>
                    <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsPartnerBar" class="h-full bg-gradient-to-r from-teal-500 to-emerald-500 rounded-full" style="width:0%"></div></div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1"><span class="text-gray-400">Donors</span><span class="text-purple-400 font-medium" id="analyticsDonorPercent">0%</span></div>
                    <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsDonorBar" class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full" style="width:0%"></div></div>
                </div>
            </div>
        </div>
        <div class="rounded-xl p-5" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <h3 class="text-white font-semibold mb-4 flex items-center gap-2"><span class="w-1 h-5 bg-amber-400 rounded-full"></span>Contribution Types</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1"><span class="text-gray-400">Financial</span><span class="text-amber-400 font-medium" id="analyticsFinancialPercent">0%</span></div>
                    <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsFinancialBar" class="h-full bg-gradient-to-r from-amber-500 to-orange-500 rounded-full" style="width:0%"></div></div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1"><span class="text-gray-400">In-kind</span><span class="text-blue-400 font-medium" id="analyticsInkindPercent">0%</span></div>
                    <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsInkindBar" class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full" style="width:0%"></div></div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1"><span class="text-gray-400">Both</span><span class="text-green-400 font-medium" id="analyticsBothPercent">0%</span></div>
                    <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsBothBar" class="h-full bg-gradient-to-r from-green-500 to-emerald-500 rounded-full" style="width:0%"></div></div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-2 rounded-xl p-5" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <h3 class="text-white font-semibold mb-4 flex items-center gap-2"><span class="w-1 h-5 bg-teal-400 rounded-full"></span>Top Contributors</h3>
            <div id="topContributors" class="space-y-3"></div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-2">
        <div class="text-gray-400 text-sm">
            Showing <span id="partnerShowingStart">0</span> to <span id="partnerShowingEnd">0</span> of <span id="partnerTotalRecords">0</span>
        </div>
        <div class="flex gap-2" id="partnerPagination"></div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="partnerModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" id="partnerModalOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl px-4 max-h-screen overflow-y-auto py-6">
            <div class="rounded-2xl shadow-2xl p-6 relative animate-slideIn overflow-hidden"
                 style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-teal-500/10 to-emerald-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-cyan-500/10 to-teal-500/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-teal-500 to-emerald-500 rounded-xl blur-md animate-pulse"></div>
                            <div class="relative w-14 h-14 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 flex items-center justify-center">
                                <i class="ri-hand-heart-line text-2xl text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-teal-400 to-emerald-400 bg-clip-text text-transparent" id="partnerModalTitle">Add Partner/Donor</h2>
                            <p class="text-gray-400 text-sm">Enter partner/donor information below</p>
                        </div>
                        <button id="closePartnerModalBtn" class="ml-auto w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center border border-white/10 transition-all">
                            <i class="ri-close-line text-gray-400"></i>
                        </button>
                    </div>
                    <form id="partnerForm" class="space-y-4">
                        <input type="hidden" id="partnerId">

                        <!-- Basic Info -->
                        <h3 class="text-white text-sm font-semibold flex items-center gap-2"><i class="ri-information-line text-teal-400"></i> Basic Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-user-line text-teal-400 mr-1"></i> Name/Organization *</label>
                                <input type="text" id="partnerName" placeholder="e.g., ABC Corporation"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-price-tag-3-line text-teal-400 mr-1"></i> Type *</label>
                                <select id="partnerType" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <option value="" class="bg-gray-800">Select Type</option>
                                    <option value="Partner" class="bg-gray-800">Partner</option>
                                    <option value="Donor" class="bg-gray-800">Donor</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-mail-line text-teal-400 mr-1"></i> Email</label>
                                <input type="email" id="partnerEmail" placeholder="email@example.com"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-phone-line text-teal-400 mr-1"></i> Phone</label>
                                <input type="text" id="partnerPhone" placeholder="+63 912 345 6789"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-map-pin-line text-teal-400 mr-1"></i> Address</label>
                            <input type="text" id="partnerAddress" placeholder="Full address"
                                   class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                        </div>

                        <!-- Partnership Details -->
                        <div class="pt-4 border-t border-white/10">
                            <h3 class="text-white text-sm font-semibold flex items-center gap-2 mb-4"><i class="ri-shake-hands-line text-teal-400"></i> Partnership Details</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-user-settings-line text-teal-400 mr-1"></i> Roles / Linked Programs</label>
                                    <input type="text" id="partnerRoles" placeholder="e.g., Program Support"
                                           class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-calendar-line text-teal-400 mr-1"></i> Partnership Since</label>
                                    <input type="month" id="partnerSince"
                                           class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-gift-line text-teal-400 mr-1"></i> Contribution Type</label>
                                <select id="partnerContributionType" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                                    <option value="" class="bg-gray-800">Select Type</option>
                                    <option value="Financial" class="bg-gray-800">Financial</option>
                                    <option value="In-kind" class="bg-gray-800">In-kind</option>
                                    <option value="Both" class="bg-gray-800">Both</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-coins-line text-teal-400 mr-1"></i> Financial Amount (₱)</label>
                                    <input type="number" id="partnerFinancialAmount" placeholder="0.00"
                                           class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-tools-line text-teal-400 mr-1"></i> In-kind Value (₱)</label>
                                    <input type="number" id="partnerInkindValue" placeholder="0.00"
                                           class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-file-text-line text-teal-400 mr-1"></i> Description of Contributions</label>
                                <textarea id="partnerContributions" rows="3" placeholder="Describe contributions in detail..."
                                          class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"></textarea>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="pt-4 border-t border-white/10">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-flag-line text-teal-400 mr-1"></i> Status</label>
                                    <select id="partnerStatus" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                                        <option value="Active" class="bg-gray-800">Active</option>
                                        <option value="Inactive" class="bg-gray-800">Inactive</option>
                                        <option value="Pending" class="bg-gray-800">Pending</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-star-line text-teal-400 mr-1"></i> Priority</label>
                                    <select id="partnerPriority" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                                        <option value="High" class="bg-gray-800">High</option>
                                        <option value="Medium" class="bg-gray-800">Medium</option>
                                        <option value="Low" class="bg-gray-800">Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                            <button type="button" id="cancelPartnerBtn" class="px-6 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 rounded-xl text-white font-medium transition-all shadow-lg flex items-center gap-2 relative overflow-hidden group">
                                <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                                <i class="ri-save-line relative z-10"></i>
                                <span class="relative z-10">Save Partner/Donor</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="partnerDeleteModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" id="partnerDeleteOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm px-4">
            <div class="rounded-2xl shadow-2xl p-6 text-center animate-slideIn" style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-500/10 flex items-center justify-center">
                    <i class="ri-delete-bin-line text-3xl text-red-400"></i>
                </div>
                <h3 class="text-white text-lg font-semibold mb-2">Delete Partner/Donor?</h3>
                <p class="text-gray-400 text-sm mb-6">This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button id="cancelPartnerDeleteBtn" class="flex-1 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                    <button id="confirmPartnerDeleteBtn" class="flex-1 py-2.5 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl text-white font-medium transition-all">Delete</button>
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
#partnerTable tr { transition: all 0.3s ease; }
#partnerTable tr:hover { background:rgba(255,255,255,0.05); transform:translateY(-2px); box-shadow:0 5px 15px rgba(0,0,0,0.3); }
</style>

<script>
let partners    = [];
let deleteId    = null;
let currentPage = 1;
let rowsPerPage = 10;
let currentView = 'table';

const API = 'api/partner_api.php';

const statusColors = {
    'Active':   'bg-green-500/20 text-green-400 border border-green-500/30',
    'Inactive': 'bg-gray-500/20 text-gray-400 border border-gray-500/30',
    'Pending':  'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30'
};
const contColors = {
    'Financial': 'bg-amber-500/20 text-amber-400',
    'In-kind':   'bg-blue-500/20 text-blue-400',
    'Both':      'bg-purple-500/20 text-purple-400'
};

// ---- Load ----
function loadPartners() {
    document.getElementById('partnerLoadingState').classList.remove('hidden');
    document.getElementById('partnerTableWrapper').classList.add('hidden');
    document.getElementById('partnerEmptyState').classList.add('hidden');
    document.getElementById('partnerTableFooter')?.classList.add('hidden');

    fetch(`${API}?action=get`)
        .then(r => r.json())
        .then(data => {
            partners = data;
            document.getElementById('partnerLoadingState').classList.add('hidden');
            updateStats();
            renderPartners();
        })
        .catch(() => {
            document.getElementById('partnerLoadingState').innerHTML =
                '<p class="text-red-400 py-8 text-center">Failed to load. Check API connection.</p>';
        });
}

// ---- Stats ----
function updateStats() {
    const partnerCount = partners.filter(p => p.type === 'Partner').length;
    const donorCount   = partners.filter(p => p.type === 'Donor').length;
    const totalVal     = partners.reduce((s,p) => s+(parseFloat(p.financial_amount)||0)+(parseFloat(p.inkind_value)||0), 0);
    const active       = partners.filter(p => p.status === 'Active').length;

    document.getElementById('statTotalPartners').textContent    = partnerCount;
    document.getElementById('statTotalDonors').textContent      = donorCount;
    document.getElementById('statTotalContributions').textContent = '₱' + totalVal.toLocaleString();
    document.getElementById('statActivePartnerships').textContent = active;
    document.getElementById('partnerHeaderCount').innerHTML     = `<i class="ri-hand-heart-line text-teal-400"></i> ${partners.length} Total`;
}

// ---- Render ----
function renderPartners() {
    const search   = document.getElementById('partnerSearch').value.toLowerCase();
    const type     = document.getElementById('partnerTypeFilter').value;
    const contType = document.getElementById('partnerContributionFilter').value;

    let filtered = partners.filter(p =>
        (p.name.toLowerCase().includes(search) ||
         (p.email||'').toLowerCase().includes(search) ||
         (p.roles||'').toLowerCase().includes(search)) &&
        (!type     || p.type === type) &&
        (!contType || p.contribution_type === contType)
    );

    const total = filtered.length;
    document.getElementById('partnerTotalCount').textContent = total;
    document.getElementById('partnerCount').textContent      = filtered.filter(p=>p.type==='Partner').length;
    document.getElementById('donorCount').textContent        = filtered.filter(p=>p.type==='Donor').length;
    document.getElementById('showingPartnersCount').textContent = total;

    const totalPages = Math.max(1, Math.ceil(total/rowsPerPage));
    if (currentPage > totalPages) currentPage = totalPages;
    const start = (currentPage-1)*rowsPerPage;
    const paged = filtered.slice(start, start+rowsPerPage);

    document.getElementById('partnerShowingStart').textContent = total ? start+1 : 0;
    document.getElementById('partnerShowingEnd').textContent   = Math.min(start+rowsPerPage, total);
    document.getElementById('partnerTotalRecords').textContent = total;

    if (total === 0) {
        document.getElementById('partnerTableWrapper').classList.add('hidden');
        document.getElementById('partnerEmptyState').classList.remove('hidden');
        document.getElementById('partnerTableFooter')?.classList.add('hidden');
        document.getElementById('partnerCardView').innerHTML = '';
        document.getElementById('partnerPagination').innerHTML = '';
        return;
    }

    document.getElementById('partnerEmptyState').classList.add('hidden');
    document.getElementById('partnerTableFooter')?.classList.remove('hidden');

    if (currentView === 'table') {
        document.getElementById('partnerTableWrapper').classList.remove('hidden');
        renderTableView(paged);
    } else if (currentView === 'cards') {
        document.getElementById('partnerTableWrapper').classList.add('hidden');
        renderCardView(paged);
    } else {
        document.getElementById('partnerTableWrapper').classList.add('hidden');
        renderAnalytics();
    }

    renderPagination(total, totalPages);
}

function renderTableView(data) {
    document.getElementById('partnerTable').innerHTML = data.map(p => {
        const sc  = statusColors[p.status] || 'bg-gray-500/20 text-gray-400';
        const cc  = contColors[p.contribution_type] || 'bg-gray-500/20 text-gray-400';
        const val = (parseFloat(p.financial_amount)||0) + (parseFloat(p.inkind_value)||0);
        return `
        <tr class="hover:bg-white/5 transition-all">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center text-white flex-shrink-0">
                        <i class="ri-hand-heart-line"></i>
                    </div>
                    <div>
                        <span class="text-white font-medium">${p.name}</span>
                        <p class="text-gray-500 text-xs">${p.email||''}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium ${p.type==='Partner'?'bg-teal-500/20 text-teal-400':'bg-purple-500/20 text-purple-400'}">${p.type||'—'}</span></td>
            <td class="px-6 py-4 text-gray-400 text-sm">
                <p>${p.phone||'—'}</p>
                <p class="text-xs text-gray-500">${p.address||''}</p>
            </td>
            <td class="px-6 py-4 text-gray-400 text-sm">${p.roles||'—'}</td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium ${cc}">${p.contribution_type||'—'}</span></td>
            <td class="px-6 py-4"><span class="text-teal-400 font-medium">₱${val.toLocaleString()}</span></td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium ${sc}">${p.status||'—'}</span></td>
            <td class="px-6 py-4">
                <div class="flex gap-2">
                    <button onclick="editPartner(${p.id})" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-teal-500/20 border border-white/10 flex items-center justify-center transition-all group">
                        <i class="ri-edit-line text-gray-400 group-hover:text-teal-400"></i>
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
    document.getElementById('partnerCardView').innerHTML = data.map(p => {
        const sc  = statusColors[p.status] || 'bg-gray-500/20 text-gray-400';
        const val = (parseFloat(p.financial_amount)||0) + (parseFloat(p.inkind_value)||0);
        return `
        <div class="rounded-xl p-5 relative overflow-hidden hover:scale-105 transition-all duration-300"
             style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-teal-500/10 to-emerald-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center">
                    <i class="ri-hand-heart-line text-white text-xl"></i>
                </div>
                <span class="px-2 py-1 rounded-full text-xs font-medium ${sc}">${p.status||'—'}</span>
            </div>
            <h3 class="text-white font-semibold text-lg mb-1">${p.name}</h3>
            <p class="text-teal-400 text-sm mb-3">${p.type||'—'}</p>
            <div class="space-y-1.5 mb-4">
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-mail-line text-teal-400"></i>${p.email||'No email'}</div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-phone-line text-teal-400"></i>${p.phone||'No phone'}</div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-user-settings-line text-teal-400"></i>${p.roles||'No roles'}</div>
                <div class="flex items-center gap-2 text-xs text-teal-400 font-medium"><i class="ri-coins-line"></i>₱${val.toLocaleString()}</div>
            </div>
            ${p.contribution_desc ? `<p class="text-gray-500 text-xs mb-3 border-t border-white/10 pt-3">${p.contribution_desc.substring(0,80)}...</p>` : ''}
            <div class="flex justify-end gap-2">
                <button onclick="editPartner(${p.id})" class="px-3 py-1.5 bg-white/5 hover:bg-teal-500/20 rounded-lg text-gray-400 hover:text-teal-400 text-sm transition-all"><i class="ri-edit-line"></i> Edit</button>
                <button onclick="openDeleteModal(${p.id})" class="px-3 py-1.5 bg-white/5 hover:bg-red-500/20 rounded-lg text-gray-400 hover:text-red-400 text-sm transition-all"><i class="ri-delete-bin-line"></i> Delete</button>
            </div>
        </div>`;
    }).join('');
}

function renderAnalytics() {
    const total   = partners.length || 1;
    const pCount  = partners.filter(p=>p.type==='Partner').length;
    const dCount  = partners.filter(p=>p.type==='Donor').length;
    const fCount  = partners.filter(p=>p.contribution_type==='Financial'||p.contribution_type==='Both').length;
    const iCount  = partners.filter(p=>p.contribution_type==='In-kind'||p.contribution_type==='Both').length;
    const bCount  = partners.filter(p=>p.contribution_type==='Both').length;

    const pct = v => Math.round((v/total)*100);
    document.getElementById('analyticsPartnerPercent').textContent  = pct(pCount)+'%';
    document.getElementById('analyticsPartnerBar').style.width      = pct(pCount)+'%';
    document.getElementById('analyticsDonorPercent').textContent    = pct(dCount)+'%';
    document.getElementById('analyticsDonorBar').style.width        = pct(dCount)+'%';
    document.getElementById('analyticsFinancialPercent').textContent= pct(fCount)+'%';
    document.getElementById('analyticsFinancialBar').style.width    = pct(fCount)+'%';
    document.getElementById('analyticsInkindPercent').textContent   = pct(iCount)+'%';
    document.getElementById('analyticsInkindBar').style.width       = pct(iCount)+'%';
    document.getElementById('analyticsBothPercent').textContent     = pct(bCount)+'%';
    document.getElementById('analyticsBothBar').style.width         = pct(bCount)+'%';

    const top5 = [...partners].sort((a,b) =>
        ((parseFloat(b.financial_amount)||0)+(parseFloat(b.inkind_value)||0)) -
        ((parseFloat(a.financial_amount)||0)+(parseFloat(a.inkind_value)||0))
    ).slice(0,5);

    document.getElementById('topContributors').innerHTML = top5.map(p => {
        const val = (parseFloat(p.financial_amount)||0)+(parseFloat(p.inkind_value)||0);
        return `<div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center">
                    <i class="ri-hand-heart-line text-white text-xs"></i>
                </div>
                <div>
                    <p class="text-white text-sm font-medium">${p.name}</p>
                    <p class="text-gray-500 text-xs">${p.type||'—'}</p>
                </div>
            </div>
            <span class="text-teal-400 font-medium">₱${val.toLocaleString()}</span>
        </div>`;
    }).join('');
}

function renderPagination(total, totalPages) {
    document.getElementById('partnerPagination').innerHTML = `
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

function changePage(p) { currentPage = p; renderPartners(); }

// ---- Modal ----
function openPartnerModal(edit=false) {
    document.getElementById('partnerModal').classList.remove('hidden');
    document.getElementById('partnerModalTitle').textContent = edit ? 'Edit Partner/Donor' : 'Add New Partner/Donor';
}
function closePartnerModal() {
    document.getElementById('partnerModal').classList.add('hidden');
    document.getElementById('partnerForm').reset();
    document.getElementById('partnerId').value = '';
}
function openDeleteModal(id) { deleteId = id; document.getElementById('partnerDeleteModal').classList.remove('hidden'); }
function closeDeleteModal() { document.getElementById('partnerDeleteModal').classList.add('hidden'); deleteId = null; }

// ---- Edit ----
function editPartner(id) {
    const p = partners.find(x => x.id == id);
    if (!p) return;
    document.getElementById('partnerId').value                = p.id;
    document.getElementById('partnerName').value             = p.name;
    document.getElementById('partnerType').value             = p.type || '';
    document.getElementById('partnerEmail').value            = p.email || '';
    document.getElementById('partnerPhone').value            = p.phone || '';
    document.getElementById('partnerAddress').value          = p.address || '';
    document.getElementById('partnerRoles').value            = p.roles || '';
    document.getElementById('partnerSince').value            = p.partner_since || '';
    document.getElementById('partnerContributionType').value = p.contribution_type || '';
    document.getElementById('partnerFinancialAmount').value  = p.financial_amount || '';
    document.getElementById('partnerInkindValue').value      = p.inkind_value || '';
    document.getElementById('partnerContributions').value    = p.contribution_desc || '';
    document.getElementById('partnerStatus').value           = p.status || 'Active';
    document.getElementById('partnerPriority').value         = p.priority || 'Medium';
    openPartnerModal(true);
}

// ---- Form Submit ----
document.getElementById('partnerForm').addEventListener('submit', e => {
    e.preventDefault();
    const id   = document.getElementById('partnerId').value;
    const name = document.getElementById('partnerName').value.trim();
    if (!name) { alert('Name is required.'); return; }

    const fd = new FormData();
    fd.append('action',            id ? 'update' : 'save');
    if (id) fd.append('id', id);
    fd.append('name',              name);
    fd.append('type',              document.getElementById('partnerType').value);
    fd.append('email',             document.getElementById('partnerEmail').value.trim());
    fd.append('phone',             document.getElementById('partnerPhone').value.trim());
    fd.append('address',           document.getElementById('partnerAddress').value.trim());
    fd.append('roles',             document.getElementById('partnerRoles').value.trim());
    fd.append('since',             document.getElementById('partnerSince').value);
    fd.append('contribution_type', document.getElementById('partnerContributionType').value);
    fd.append('financial_amount',  document.getElementById('partnerFinancialAmount').value || 0);
    fd.append('inkind_value',      document.getElementById('partnerInkindValue').value || 0);
    fd.append('contributions',     document.getElementById('partnerContributions').value.trim());
    fd.append('status',            document.getElementById('partnerStatus').value);
    fd.append('priority',          document.getElementById('partnerPriority').value);

    fetch(API, { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closePartnerModal(); loadPartners(); }
            else alert('Error: ' + (data.message||'Unknown'));
        })
        .catch(() => alert('Network error. Try again.'));
});

// ---- Delete ----
document.getElementById('confirmPartnerDeleteBtn').addEventListener('click', () => {
    if (!deleteId) return;
    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('id', deleteId);
    fetch(API, { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closeDeleteModal(); loadPartners(); }
            else alert('Error: ' + (data.message||'Unknown'));
        });
});

// ---- Export ----
function exportPartners() {
    if (!partners.length) { alert('No data to export.'); return; }
    const rows = [['Name','Type','Email','Phone','Address','Roles','Since','Contribution Type','Financial','In-kind','Status','Priority']];
    partners.forEach(p => rows.push([p.name,p.type||'',p.email||'',p.phone||'',p.address||'',
        p.roles||'',p.partner_since||'',p.contribution_type||'',
        p.financial_amount||0,p.inkind_value||0,p.status||'',p.priority||'']));
    const csv = rows.map(r => r.map(c=>`"${c}"`).join(',')).join('\n');
    Object.assign(document.createElement('a'),{href:'data:text/csv,'+encodeURIComponent(csv),download:'partners.csv'}).click();
}

// ---- View Toggle ----
function setView(view) {
    currentView = view;
    document.getElementById('partnerTableView').classList.toggle('hidden', view!=='table');
    document.getElementById('partnerCardView').classList.toggle('hidden', view!=='cards');
    document.getElementById('partnerAnalyticsView').classList.toggle('hidden', view!=='analytics');
    ['partnerTableViewBtn','partnerCardViewBtn','partnerAnalyticsViewBtn'].forEach(id => {
        document.getElementById(id).className = 'px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    });
    const map = { table:'partnerTableViewBtn', cards:'partnerCardViewBtn', analytics:'partnerAnalyticsViewBtn' };
    document.getElementById(map[view]).className = 'px-3 py-1.5 bg-teal-500/20 text-teal-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    if (view === 'analytics') renderAnalytics();
    else renderPartners();
}

// ---- Event Listeners ----
document.getElementById('addPartnerBtn').addEventListener('click', () => openPartnerModal());
document.getElementById('addFirstPartnerBtn')?.addEventListener('click', () => openPartnerModal());
document.getElementById('cancelPartnerBtn').addEventListener('click', closePartnerModal);
document.getElementById('closePartnerModalBtn').addEventListener('click', closePartnerModal);
document.getElementById('partnerModalOverlay').addEventListener('click', closePartnerModal);
document.getElementById('cancelPartnerDeleteBtn').addEventListener('click', closeDeleteModal);
document.getElementById('partnerDeleteOverlay').addEventListener('click', closeDeleteModal);
document.getElementById('partnerSearch').addEventListener('input', () => { currentPage=1; renderPartners(); });
document.getElementById('partnerTypeFilter').addEventListener('change', () => { currentPage=1; renderPartners(); });
document.getElementById('partnerContributionFilter').addEventListener('change', () => { currentPage=1; renderPartners(); });
document.getElementById('clearPartnerFilters').addEventListener('click', () => {
    ['partnerSearch','partnerTypeFilter','partnerContributionFilter'].forEach(id => { const el=document.getElementById(id); if(el) el.value=''; });
    currentPage=1; renderPartners();
});
document.getElementById('partnerRowsPerPage').addEventListener('change', e => {
    rowsPerPage=parseInt(e.target.value); currentPage=1; renderPartners();
});
document.getElementById('partnerTableViewBtn').addEventListener('click', () => setView('table'));
document.getElementById('partnerCardViewBtn').addEventListener('click', () => setView('cards'));
document.getElementById('partnerAnalyticsViewBtn').addEventListener('click', () => setView('analytics'));
document.addEventListener('keydown', e => { if(e.key==='Escape'){closePartnerModal();closeDeleteModal();} });

loadPartners();
</script>
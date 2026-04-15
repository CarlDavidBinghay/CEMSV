<?php include_once __DIR__ . '/../db.php'; ?>

<section id="resource-section" class="space-y-6">

    <!-- Premium Header -->
    <div class="rounded-3xl p-6 relative overflow-hidden group"
         style="background: linear-gradient(135deg, #212529 0%, #343A40 50%, #495057 100%);">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-indigo-500/20 to-blue-500/20 rounded-full blur-3xl -mr-20 -mt-20 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-gradient-to-tr from-violet-500/20 to-indigo-500/20 rounded-full blur-2xl -ml-10 -mb-10 animate-pulse delay-1000"></div>
        </div>
        <div class="absolute top-10 right-20 w-16 h-16 border border-indigo-500/20 rounded-2xl rotate-45 animate-float-slow"></div>
        <div class="absolute bottom-10 left-20 w-12 h-12 border border-indigo-500/20 rounded-full animate-float"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.5) 1px, transparent 0); background-size: 40px 40px;"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-xs text-white/40 uppercase tracking-[0.2em] font-light">RESOURCE MANAGEMENT</span>
                <span class="w-1 h-1 rounded-full bg-indigo-400 animate-pulse"></span>
                <span class="text-xs bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-white/80 border border-white/20 flex items-center gap-1" id="resourceHeaderCount">
                    <i class="ri-stack-line text-indigo-400"></i> 0 Total
                </span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2 text-white">
                        Resources
                        <span class="text-white/40 mx-2">/</span>
                        <span class="text-white/60 text-2xl">Management</span>
                    </h1>
                    <p class="text-white/30 text-sm flex items-center gap-2">
                        <i class="ri-sparkling-line text-indigo-400 animate-pulse"></i>
                        Manage and track all equipment, materials, and facilities
                    </p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportResources()"
                            class="px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all">
                        <i class="ri-download-line text-indigo-400"></i>
                        <span class="hidden md:inline">Export CSV</span>
                    </button>
                    <button id="addResourceBtn"
                            class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all shadow-lg shadow-indigo-500/20 relative overflow-hidden group">
                        <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                        <i class="ri-add-line relative z-10"></i>
                        <span class="hidden md:inline relative z-10">Add Resource</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-indigo-500/10 to-blue-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Resources</p>
                <p class="text-3xl font-bold text-white mt-1" id="statTotalResources">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center shadow-lg">
                    <i class="ri-stack-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-gray-500 text-xs">All registered items</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Equipment</p>
                <p class="text-3xl font-bold text-white mt-1" id="statEquipment">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
                    <i class="ri-tools-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-purple-400 text-xs">Devices & tools</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-amber-500/10 to-orange-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Materials</p>
                <p class="text-3xl font-bold text-white mt-1" id="statMaterials">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg">
                    <i class="ri-box-3-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-amber-400 text-xs">Supplies & consumables</p>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-green-500/10 to-emerald-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div><p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Facilities</p>
                <p class="text-3xl font-bold text-white mt-1" id="statFacilities">0</p></div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg">
                    <i class="ri-building-line text-white text-lg"></i>
                </div>
            </div>
            <p class="text-green-400 text-xs">Rooms & spaces</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="rounded-2xl p-5 relative overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="resourceSearch" placeholder="Search by name, type, location..."
                       class="w-full pl-12 pr-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all hover:bg-black/30">
            </div>
            <div class="flex gap-2 flex-wrap">
                <div class="relative">
                    <select id="resourceTypeFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none cursor-pointer min-w-[140px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Types</option>
                        <option value="Equipment" class="bg-gray-800">Equipment</option>
                        <option value="Material" class="bg-gray-800">Materials</option>
                        <option value="Facility" class="bg-gray-800">Facilities</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <div class="relative">
                    <select id="resourceLocationFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none cursor-pointer min-w-[150px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Locations</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <div class="relative">
                    <select id="resourceStatusFilter"
                            class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none cursor-pointer min-w-[140px] hover:bg-black/30">
                        <option value="" class="bg-gray-800">All Status</option>
                        <option value="Available" class="bg-gray-800">Available</option>
                        <option value="In Use" class="bg-gray-800">In Use</option>
                        <option value="Maintenance" class="bg-gray-800">Maintenance</option>
                        <option value="Out of Stock" class="bg-gray-800">Out of Stock</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
                <button id="clearResourceFilters"
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
            <button id="resourceTableViewBtn" class="px-3 py-1.5 bg-indigo-500/20 text-indigo-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-table-line"></i> Table</button>
            <button id="resourceCardViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-layout-grid-line"></i> Cards</button>
            <button id="resourceAnalyticsViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all"><i class="ri-pie-chart-line"></i> Analytics</button>
        </div>
        <div class="text-white/40 text-sm"><span id="showingResourcesCount">0</span> resources</div>
    </div>

    <!-- Table View -->
    <div id="resourceTableView" class="rounded-2xl overflow-hidden" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
        <div id="resourceLoadingState" class="text-center py-12">
            <div class="relative inline-block">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-full blur-xl opacity-50 animate-pulse"></div>
                <div class="relative w-16 h-16 rounded-full bg-gradient-to-r from-indigo-500 to-blue-500 flex items-center justify-center mb-4 mx-auto">
                    <i class="ri-loader-4-line animate-spin text-2xl text-white"></i>
                </div>
            </div>
            <p class="text-gray-400 mt-4">Loading resources...</p>
        </div>
        <div id="resourceTableWrapper" class="hidden overflow-x-auto">
            <table class="w-full">
                <thead style="background:#1E2228; border-bottom:1px solid rgba(255,255,255,0.05);">
                    <tr>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Resource</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Type</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Quantity</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Location</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Condition</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Last Maintenance</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="resourceTable" style="background:#2A2F37;" class="divide-y divide-white/5"></tbody>
            </table>
        </div>
        <div id="resourceEmptyState" class="hidden text-center py-12">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-indigo-500/20 to-blue-500/20 flex items-center justify-center">
                <i class="ri-stack-line text-4xl text-indigo-400/50"></i>
            </div>
            <h3 class="text-white text-lg font-semibold mb-2">No resources found</h3>
            <p class="text-gray-400 text-sm mb-4">Get started by adding your first resource</p>
            <button id="addFirstResourceBtn" class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-blue-600 text-white rounded-xl font-medium transition-all inline-flex items-center gap-2">
                <i class="ri-add-line"></i> Add Resource
            </button>
        </div>
        <div id="resourceTableFooter" class="hidden flex items-center justify-between px-6 py-4" style="background:#1E2228; border-top:1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center gap-4 text-sm">
                <span class="text-gray-400">Total: <b id="resourceTotalCount" class="text-white">0</b></span>
                <span class="text-gray-400">Available: <b id="resourceAvailableCount" class="text-green-400">0</b></span>
                <span class="text-gray-400">In Use: <b id="resourceInUseCount" class="text-blue-400">0</b></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-400 text-sm">Rows:</span>
                <select id="resourceRowsPerPage" class="bg-black/20 border border-white/10 rounded-lg text-white px-3 py-1.5 text-sm focus:outline-none">
                    <option value="5" class="bg-gray-800">5</option>
                    <option value="10" selected class="bg-gray-800">10</option>
                    <option value="25" class="bg-gray-800">25</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Card View -->
    <div id="resourceCardView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>

    <!-- Analytics View -->
    <div id="resourceAnalyticsView" class="hidden grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="rounded-xl p-5" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <h3 class="text-white font-semibold mb-4 flex items-center gap-2"><span class="w-1 h-5 bg-indigo-400 rounded-full"></span>Distribution by Type</h3>
            <div class="space-y-4">
                <div><div class="flex justify-between text-sm mb-1"><span class="text-gray-400">Equipment</span><span class="text-indigo-400 font-medium" id="analyticsEquipmentPercent">0%</span></div>
                <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsEquipmentBar" class="h-full bg-gradient-to-r from-indigo-500 to-blue-500 rounded-full" style="width:0%"></div></div></div>
                <div><div class="flex justify-between text-sm mb-1"><span class="text-gray-400">Materials</span><span class="text-amber-400 font-medium" id="analyticsMaterialsPercent">0%</span></div>
                <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsMaterialsBar" class="h-full bg-gradient-to-r from-amber-500 to-orange-500 rounded-full" style="width:0%"></div></div></div>
                <div><div class="flex justify-between text-sm mb-1"><span class="text-gray-400">Facilities</span><span class="text-green-400 font-medium" id="analyticsFacilitiesPercent">0%</span></div>
                <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsFacilitiesBar" class="h-full bg-gradient-to-r from-green-500 to-emerald-500 rounded-full" style="width:0%"></div></div></div>
            </div>
        </div>
        <div class="rounded-xl p-5" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <h3 class="text-white font-semibold mb-4 flex items-center gap-2"><span class="w-1 h-5 bg-blue-400 rounded-full"></span>Status Overview</h3>
            <div class="space-y-4">
                <div><div class="flex justify-between text-sm mb-1"><span class="text-gray-400">Available</span><span class="text-green-400 font-medium" id="analyticsAvailablePercent">0%</span></div>
                <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsAvailableBar" class="h-full bg-gradient-to-r from-green-500 to-emerald-500 rounded-full" style="width:0%"></div></div></div>
                <div><div class="flex justify-between text-sm mb-1"><span class="text-gray-400">In Use</span><span class="text-blue-400 font-medium" id="analyticsInUsePercent">0%</span></div>
                <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsInUseBar" class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full" style="width:0%"></div></div></div>
                <div><div class="flex justify-between text-sm mb-1"><span class="text-gray-400">Maintenance</span><span class="text-yellow-400 font-medium" id="analyticsMaintenancePercent">0%</span></div>
                <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsMaintenanceBar" class="h-full bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full" style="width:0%"></div></div></div>
                <div><div class="flex justify-between text-sm mb-1"><span class="text-gray-400">Out of Stock</span><span class="text-red-400 font-medium" id="analyticsOutOfStockPercent">0%</span></div>
                <div class="h-2 bg-white/5 rounded-full overflow-hidden"><div id="analyticsOutOfStockBar" class="h-full bg-gradient-to-r from-red-500 to-pink-500 rounded-full" style="width:0%"></div></div></div>
            </div>
        </div>
        <div class="lg:col-span-2 rounded-xl p-5" style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <h3 class="text-white font-semibold mb-4 flex items-center gap-2"><span class="w-1 h-5 bg-indigo-400 rounded-full"></span>Resources by Location</h3>
            <div id="locationSummary" class="space-y-3"></div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-2">
        <div class="text-gray-400 text-sm">
            Showing <span id="resourceShowingStart">0</span> to <span id="resourceShowingEnd">0</span> of <span id="resourceTotalRecords">0</span>
        </div>
        <div class="flex gap-2" id="resourcePagination"></div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="resourceModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" id="resourceModalOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl px-4 max-h-screen overflow-y-auto py-6">
            <div class="rounded-2xl shadow-2xl p-6 relative animate-slideIn overflow-hidden"
                 style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-indigo-500/10 to-blue-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-violet-500/10 to-indigo-500/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-xl blur-md animate-pulse"></div>
                            <div class="relative w-14 h-14 rounded-xl bg-gradient-to-r from-indigo-500 to-blue-600 flex items-center justify-center">
                                <i class="ri-stack-line text-2xl text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-400 to-blue-400 bg-clip-text text-transparent" id="resourceModalTitle">Add Resource</h2>
                            <p class="text-gray-400 text-sm">Enter resource information below</p>
                        </div>
                        <button id="closeResourceModalBtn" class="ml-auto w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center border border-white/10 transition-all">
                            <i class="ri-close-line text-gray-400"></i>
                        </button>
                    </div>
                    <form id="resourceForm" class="space-y-4">
                        <input type="hidden" id="resourceId">

                        <!-- Basic Info -->
                        <h3 class="text-white text-sm font-semibold flex items-center gap-2"><i class="ri-information-line text-indigo-400"></i> Basic Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-stack-line text-indigo-400 mr-1"></i> Resource Name *</label>
                                <input type="text" id="resourceName" placeholder="e.g., Laptop, Conference Room"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-price-tag-3-line text-indigo-400 mr-1"></i> Resource Type *</label>
                                <select id="resourceType" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="" class="bg-gray-800">Select Type</option>
                                    <option value="Equipment" class="bg-gray-800">Equipment</option>
                                    <option value="Material" class="bg-gray-800">Material</option>
                                    <option value="Facility" class="bg-gray-800">Facility</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-numbers-line text-indigo-400 mr-1"></i> Quantity</label>
                                <input type="number" id="resourceQuantity" placeholder="e.g., 10" min="0"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-map-pin-line text-indigo-400 mr-1"></i> Location</label>
                                <input type="text" id="resourceLocation" placeholder="e.g., Storage Room A"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                        </div>

                        <!-- Status & Condition -->
                        <div class="pt-4 border-t border-white/10">
                            <h3 class="text-white text-sm font-semibold flex items-center gap-2 mb-4"><i class="ri-information-line text-indigo-400"></i> Status & Condition</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-flag-line text-indigo-400 mr-1"></i> Status</label>
                                    <select id="resourceStatus" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="Available" class="bg-gray-800">Available</option>
                                        <option value="In Use" class="bg-gray-800">In Use</option>
                                        <option value="Maintenance" class="bg-gray-800">Maintenance</option>
                                        <option value="Out of Stock" class="bg-gray-800">Out of Stock</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-checkbox-circle-line text-indigo-400 mr-1"></i> Condition</label>
                                    <select id="resourceCondition" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="New" class="bg-gray-800">New</option>
                                        <option value="Good" class="bg-gray-800">Good</option>
                                        <option value="Fair" class="bg-gray-800">Fair</option>
                                        <option value="Poor" class="bg-gray-800">Poor</option>
                                        <option value="Needs Repair" class="bg-gray-800">Needs Repair</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-calendar-line text-indigo-400 mr-1"></i> Last Maintenance Date</label>
                                <input type="date" id="resourceMaintenanceDate"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="pt-4 border-t border-white/10">
                            <h3 class="text-white text-sm font-semibold flex items-center gap-2 mb-4"><i class="ri-file-copy-line text-indigo-400"></i> Additional Information</h3>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-price-tag-3-line text-indigo-400 mr-1"></i> Category/Department</label>
                                <input type="text" id="resourceCategory" placeholder="e.g., IT Equipment, Office Supplies"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div class="mt-4">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><i class="ri-file-text-line text-indigo-400 mr-1"></i> Notes</label>
                                <textarea id="resourceNotes" rows="3" placeholder="Additional details or notes..."
                                          class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                            <button type="button" id="cancelResourceBtn" class="px-6 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 rounded-xl text-white font-medium transition-all shadow-lg flex items-center gap-2 relative overflow-hidden group">
                                <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                                <i class="ri-save-line relative z-10"></i>
                                <span class="relative z-10">Save Resource</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="resourceDeleteModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" id="resourceDeleteOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm px-4">
            <div class="rounded-2xl shadow-2xl p-6 text-center animate-slideIn" style="background: linear-gradient(135deg, #2A2F37 0%, #1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-500/10 flex items-center justify-center">
                    <i class="ri-delete-bin-line text-3xl text-red-400"></i>
                </div>
                <h3 class="text-white text-lg font-semibold mb-2">Delete Resource?</h3>
                <p class="text-gray-400 text-sm mb-6">This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button id="cancelResourceDeleteBtn" class="flex-1 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                    <button id="confirmResourceDeleteBtn" class="flex-1 py-2.5 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl text-white font-medium transition-all">Delete</button>
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
#resourceTable tr { transition: all 0.3s ease; }
#resourceTable tr:hover { background:rgba(255,255,255,0.05); transform:translateY(-2px); box-shadow:0 5px 15px rgba(0,0,0,0.3); }
</style>

<script>
let resources   = [];
let deleteId    = null;
let currentPage = 1;
let rowsPerPage = 10;
let currentView = 'table';

const API = 'api/resource_api.php';

const statusColors = {
    'Available':    'bg-green-500/20 text-green-400 border border-green-500/30',
    'In Use':       'bg-blue-500/20 text-blue-400 border border-blue-500/30',
    'Maintenance':  'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
    'Out of Stock': 'bg-red-500/20 text-red-400 border border-red-500/30'
};
const conditionColors = {
    'New':          'bg-emerald-500/20 text-emerald-400',
    'Good':         'bg-green-500/20 text-green-400',
    'Fair':         'bg-yellow-500/20 text-yellow-400',
    'Poor':         'bg-orange-500/20 text-orange-400',
    'Needs Repair': 'bg-red-500/20 text-red-400'
};
const typeColors = {
    'Equipment': 'bg-purple-500/20 text-purple-400',
    'Material':  'bg-amber-500/20 text-amber-400',
    'Facility':  'bg-green-500/20 text-green-400'
};

// ---- Load ----
function loadResources() {
    document.getElementById('resourceLoadingState').classList.remove('hidden');
    document.getElementById('resourceTableWrapper').classList.add('hidden');
    document.getElementById('resourceEmptyState').classList.add('hidden');
    document.getElementById('resourceTableFooter')?.classList.add('hidden');

    fetch(`${API}?action=get`)
        .then(r => r.json())
        .then(data => {
            resources = data;
            document.getElementById('resourceLoadingState').classList.add('hidden');
            updateStats();
            populateLocationFilter();
            renderResources();
        })
        .catch(() => {
            document.getElementById('resourceLoadingState').innerHTML =
                '<p class="text-red-400 py-8 text-center">Failed to load. Check API connection.</p>';
        });
}

function updateStats() {
    const total = resources.length;
    document.getElementById('statTotalResources').textContent = total;
    document.getElementById('statEquipment').textContent     = resources.filter(r=>r.type==='Equipment').length;
    document.getElementById('statMaterials').textContent     = resources.filter(r=>r.type==='Material').length;
    document.getElementById('statFacilities').textContent    = resources.filter(r=>r.type==='Facility').length;
    document.getElementById('resourceHeaderCount').innerHTML = `<i class="ri-stack-line text-indigo-400"></i> ${total} Total`;
}

function populateLocationFilter() {
    const locs = [...new Set(resources.map(r=>r.location).filter(Boolean))];
    const sel  = document.getElementById('resourceLocationFilter');
    sel.innerHTML = '<option value="" class="bg-gray-800">All Locations</option>' +
        locs.map(l=>`<option value="${l}" class="bg-gray-800">${l}</option>`).join('');
}

function renderResources() {
    const search   = document.getElementById('resourceSearch').value.toLowerCase();
    const type     = document.getElementById('resourceTypeFilter').value;
    const location = document.getElementById('resourceLocationFilter').value;
    const status   = document.getElementById('resourceStatusFilter').value;

    let filtered = resources.filter(r =>
        (r.name.toLowerCase().includes(search) ||
         (r.type||'').toLowerCase().includes(search) ||
         (r.location||'').toLowerCase().includes(search)) &&
        (!type     || r.type === type) &&
        (!location || r.location === location) &&
        (!status   || r.status === status)
    );

    const total = filtered.length;
    document.getElementById('resourceTotalCount').textContent    = total;
    document.getElementById('resourceAvailableCount').textContent= filtered.filter(r=>r.status==='Available').length;
    document.getElementById('resourceInUseCount').textContent    = filtered.filter(r=>r.status==='In Use').length;
    document.getElementById('showingResourcesCount').textContent = total;

    const totalPages = Math.max(1, Math.ceil(total/rowsPerPage));
    if (currentPage > totalPages) currentPage = totalPages;
    const start = (currentPage-1)*rowsPerPage;
    const paged = filtered.slice(start, start+rowsPerPage);

    document.getElementById('resourceShowingStart').textContent = total ? start+1 : 0;
    document.getElementById('resourceShowingEnd').textContent   = Math.min(start+rowsPerPage, total);
    document.getElementById('resourceTotalRecords').textContent = total;

    if (total === 0) {
        document.getElementById('resourceTableWrapper').classList.add('hidden');
        document.getElementById('resourceEmptyState').classList.remove('hidden');
        document.getElementById('resourceTableFooter')?.classList.add('hidden');
        document.getElementById('resourceCardView').innerHTML = '';
        document.getElementById('resourcePagination').innerHTML = '';
        return;
    }

    document.getElementById('resourceEmptyState').classList.add('hidden');
    document.getElementById('resourceTableFooter')?.classList.remove('hidden');

    if (currentView === 'table') {
        document.getElementById('resourceTableWrapper').classList.remove('hidden');
        renderTableView(paged);
    } else if (currentView === 'cards') {
        document.getElementById('resourceTableWrapper').classList.add('hidden');
        renderCardView(paged);
    } else {
        document.getElementById('resourceTableWrapper').classList.add('hidden');
        renderAnalytics();
    }

    renderPagination(total, totalPages);
}

function renderTableView(data) {
    document.getElementById('resourceTable').innerHTML = data.map(r => {
        const sc  = statusColors[r.status]            || 'bg-gray-500/20 text-gray-400';
        const cc  = conditionColors[r.condition_status] || 'bg-gray-500/20 text-gray-400';
        const tc  = typeColors[r.type]                || 'bg-gray-500/20 text-gray-400';
        return `
        <tr class="hover:bg-white/5 transition-all">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center text-white flex-shrink-0">
                        <i class="ri-stack-line"></i>
                    </div>
                    <div>
                        <span class="text-white font-medium">${r.name}</span>
                        <p class="text-gray-500 text-xs">${r.category||''}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium ${tc}">${r.type||'—'}</span></td>
            <td class="px-6 py-4"><span class="text-white font-medium">${r.quantity||0}</span> <span class="text-gray-500 text-xs">units</span></td>
            <td class="px-6 py-4 text-gray-400 text-sm">${r.location||'—'}</td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium ${sc}">${r.status||'—'}</span></td>
            <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium ${cc}">${r.condition_status||'—'}</span></td>
            <td class="px-6 py-4 text-gray-400 text-sm">${r.maintenance_date||'—'}</td>
            <td class="px-6 py-4">
                <div class="flex gap-2">
                    <button onclick="editResource(${r.id})" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-indigo-500/20 border border-white/10 flex items-center justify-center transition-all group">
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
    document.getElementById('resourceCardView').innerHTML = data.map(r => {
        const sc = statusColors[r.status] || 'bg-gray-500/20 text-gray-400';
        return `
        <div class="rounded-xl p-5 relative overflow-hidden hover:scale-105 transition-all duration-300"
             style="background:#2A2F37; border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-500/10 to-blue-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center">
                    <i class="ri-stack-line text-white text-xl"></i>
                </div>
                <span class="px-2 py-1 rounded-full text-xs font-medium ${sc}">${r.status||'—'}</span>
            </div>
            <h3 class="text-white font-semibold text-lg mb-1">${r.name}</h3>
            <p class="text-indigo-400 text-sm mb-3">${r.type||'—'}</p>
            <div class="space-y-1.5 mb-4">
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-numbers-line text-indigo-400"></i>Qty: <span class="text-white font-medium">${r.quantity||0}</span></div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-map-pin-line text-indigo-400"></i>${r.location||'—'}</div>
                <div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-checkbox-circle-line text-indigo-400"></i>Condition: ${r.condition_status||'—'}</div>
                ${r.category ? `<div class="flex items-center gap-2 text-xs text-gray-400"><i class="ri-price-tag-3-line text-indigo-400"></i>${r.category}</div>` : ''}
            </div>
            ${r.notes ? `<p class="text-gray-500 text-xs mb-3 border-t border-white/10 pt-3">${r.notes.substring(0,80)}...</p>` : ''}
            <div class="flex justify-end gap-2">
                <button onclick="editResource(${r.id})" class="px-3 py-1.5 bg-white/5 hover:bg-indigo-500/20 rounded-lg text-gray-400 hover:text-indigo-400 text-sm transition-all"><i class="ri-edit-line"></i> Edit</button>
                <button onclick="openDeleteModal(${r.id})" class="px-3 py-1.5 bg-white/5 hover:bg-red-500/20 rounded-lg text-gray-400 hover:text-red-400 text-sm transition-all"><i class="ri-delete-bin-line"></i> Delete</button>
            </div>
        </div>`;
    }).join('');
}

function renderAnalytics() {
    const total = resources.length || 1;
    const eq = resources.filter(r=>r.type==='Equipment').length;
    const mt = resources.filter(r=>r.type==='Material').length;
    const fc = resources.filter(r=>r.type==='Facility').length;
    const av = resources.filter(r=>r.status==='Available').length;
    const iu = resources.filter(r=>r.status==='In Use').length;
    const mn = resources.filter(r=>r.status==='Maintenance').length;
    const os = resources.filter(r=>r.status==='Out of Stock').length;

    const pct = v => Math.round((v/total)*100);
    document.getElementById('analyticsEquipmentPercent').textContent  = pct(eq)+'%';
    document.getElementById('analyticsEquipmentBar').style.width      = pct(eq)+'%';
    document.getElementById('analyticsMaterialsPercent').textContent  = pct(mt)+'%';
    document.getElementById('analyticsMaterialsBar').style.width      = pct(mt)+'%';
    document.getElementById('analyticsFacilitiesPercent').textContent = pct(fc)+'%';
    document.getElementById('analyticsFacilitiesBar').style.width     = pct(fc)+'%';
    document.getElementById('analyticsAvailablePercent').textContent  = pct(av)+'%';
    document.getElementById('analyticsAvailableBar').style.width      = pct(av)+'%';
    document.getElementById('analyticsInUsePercent').textContent      = pct(iu)+'%';
    document.getElementById('analyticsInUseBar').style.width          = pct(iu)+'%';
    document.getElementById('analyticsMaintenancePercent').textContent= pct(mn)+'%';
    document.getElementById('analyticsMaintenanceBar').style.width    = pct(mn)+'%';
    document.getElementById('analyticsOutOfStockPercent').textContent = pct(os)+'%';
    document.getElementById('analyticsOutOfStockBar').style.width     = pct(os)+'%';

    const locMap = {};
    resources.forEach(r => {
        const loc = r.location || 'Unknown';
        if (!locMap[loc]) locMap[loc] = { total:0, available:0 };
        locMap[loc].total     += parseInt(r.quantity)||0;
        if (r.status === 'Available') locMap[loc].available += parseInt(r.quantity)||0;
    });
    document.getElementById('locationSummary').innerHTML = Object.entries(locMap).map(([loc, s]) => `
        <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
            <div class="flex items-center gap-2"><i class="ri-map-pin-line text-indigo-400"></i><span class="text-white text-sm">${loc}</span></div>
            <div class="flex items-center gap-4">
                <span class="text-gray-400 text-sm">Total: <span class="text-white font-medium">${s.total}</span></span>
                <span class="text-gray-400 text-sm">Available: <span class="text-green-400 font-medium">${s.available}</span></span>
            </div>
        </div>`).join('');
}

function renderPagination(total, totalPages) {
    document.getElementById('resourcePagination').innerHTML = `
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

function changePage(p) { currentPage = p; renderResources(); }

// ---- Modal ----
function openResourceModal(edit=false) {
    document.getElementById('resourceModal').classList.remove('hidden');
    document.getElementById('resourceModalTitle').textContent = edit ? 'Edit Resource' : 'Add New Resource';
}
function closeResourceModal() {
    document.getElementById('resourceModal').classList.add('hidden');
    document.getElementById('resourceForm').reset();
    document.getElementById('resourceId').value = '';
}
function openDeleteModal(id) { deleteId = id; document.getElementById('resourceDeleteModal').classList.remove('hidden'); }
function closeDeleteModal() { document.getElementById('resourceDeleteModal').classList.add('hidden'); deleteId = null; }

// ---- Edit ----
function editResource(id) {
    const r = resources.find(x => x.id == id);
    if (!r) return;
    document.getElementById('resourceId').value              = r.id;
    document.getElementById('resourceName').value           = r.name;
    document.getElementById('resourceType').value           = r.type || '';
    document.getElementById('resourceQuantity').value       = r.quantity || '';
    document.getElementById('resourceLocation').value       = r.location || '';
    document.getElementById('resourceStatus').value         = r.status || 'Available';
    document.getElementById('resourceCondition').value      = r.condition_status || 'Good';
    document.getElementById('resourceMaintenanceDate').value= r.maintenance_date || '';
    document.getElementById('resourceCategory').value       = r.category || '';
    document.getElementById('resourceNotes').value          = r.notes || '';
    openResourceModal(true);
}

// ---- Form Submit ----
document.getElementById('resourceForm').addEventListener('submit', e => {
    e.preventDefault();
    const id   = document.getElementById('resourceId').value;
    const name = document.getElementById('resourceName').value.trim();
    if (!name) { alert('Resource name is required.'); return; }

    const fd = new FormData();
    fd.append('action',           id ? 'update' : 'save');
    if (id) fd.append('id', id);
    fd.append('name',             name);
    fd.append('type',             document.getElementById('resourceType').value);
    fd.append('quantity',         document.getElementById('resourceQuantity').value || 0);
    fd.append('location',         document.getElementById('resourceLocation').value.trim());
    fd.append('status',           document.getElementById('resourceStatus').value);
    fd.append('condition',        document.getElementById('resourceCondition').value);
    fd.append('maintenance_date', document.getElementById('resourceMaintenanceDate').value);
    fd.append('category',         document.getElementById('resourceCategory').value.trim());
    fd.append('notes',            document.getElementById('resourceNotes').value.trim());

    fetch(API, { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closeResourceModal(); loadResources(); }
            else alert('Error: ' + (data.message||'Unknown'));
        })
        .catch(() => alert('Network error. Try again.'));
});

// ---- Delete ----
document.getElementById('confirmResourceDeleteBtn').addEventListener('click', () => {
    if (!deleteId) return;
    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('id', deleteId);
    fetch(API, { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') { closeDeleteModal(); loadResources(); }
            else alert('Error: ' + (data.message||'Unknown'));
        });
});

// ---- Export ----
function exportResources() {
    if (!resources.length) { alert('No data to export.'); return; }
    const rows = [['Name','Type','Quantity','Location','Status','Condition','Category','Notes']];
    resources.forEach(r => rows.push([r.name,r.type||'',r.quantity||0,r.location||'',
        r.status||'',r.condition_status||'',r.category||'',r.notes||'']));
    const csv = rows.map(r => r.map(c=>`"${c}"`).join(',')).join('\n');
    Object.assign(document.createElement('a'),{href:'data:text/csv,'+encodeURIComponent(csv),download:'resources.csv'}).click();
}

// ---- View Toggle ----
function setView(view) {
    currentView = view;
    document.getElementById('resourceTableView').classList.toggle('hidden', view!=='table');
    document.getElementById('resourceCardView').classList.toggle('hidden', view!=='cards');
    document.getElementById('resourceAnalyticsView').classList.toggle('hidden', view!=='analytics');
    ['resourceTableViewBtn','resourceCardViewBtn','resourceAnalyticsViewBtn'].forEach(id => {
        document.getElementById(id).className = 'px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    });
    const map = { table:'resourceTableViewBtn', cards:'resourceCardViewBtn', analytics:'resourceAnalyticsViewBtn' };
    document.getElementById(map[view]).className = 'px-3 py-1.5 bg-indigo-500/20 text-indigo-400 rounded-lg text-sm font-medium flex items-center gap-1 transition-all';
    if (view === 'analytics') renderAnalytics();
    else renderResources();
}

// ---- Event Listeners ----
document.getElementById('addResourceBtn').addEventListener('click', () => openResourceModal());
document.getElementById('addFirstResourceBtn')?.addEventListener('click', () => openResourceModal());
document.getElementById('cancelResourceBtn').addEventListener('click', closeResourceModal);
document.getElementById('closeResourceModalBtn').addEventListener('click', closeResourceModal);
document.getElementById('resourceModalOverlay').addEventListener('click', closeResourceModal);
document.getElementById('cancelResourceDeleteBtn').addEventListener('click', closeDeleteModal);
document.getElementById('resourceDeleteOverlay').addEventListener('click', closeDeleteModal);
document.getElementById('resourceSearch').addEventListener('input', () => { currentPage=1; renderResources(); });
document.getElementById('resourceTypeFilter').addEventListener('change', () => { currentPage=1; renderResources(); });
document.getElementById('resourceLocationFilter').addEventListener('change', () => { currentPage=1; renderResources(); });
document.getElementById('resourceStatusFilter').addEventListener('change', () => { currentPage=1; renderResources(); });
document.getElementById('clearResourceFilters').addEventListener('click', () => {
    ['resourceSearch','resourceTypeFilter','resourceLocationFilter','resourceStatusFilter'].forEach(id => {
        const el = document.getElementById(id); if(el) el.value='';
    });
    currentPage=1; renderResources();
});
document.getElementById('resourceRowsPerPage').addEventListener('change', e => {
    rowsPerPage=parseInt(e.target.value); currentPage=1; renderResources();
});
document.getElementById('resourceTableViewBtn').addEventListener('click', () => setView('table'));
document.getElementById('resourceCardViewBtn').addEventListener('click', () => setView('cards'));
document.getElementById('resourceAnalyticsViewBtn').addEventListener('click', () => setView('analytics'));
document.addEventListener('keydown', e => { if(e.key==='Escape'){closeResourceModal();closeDeleteModal();} });

loadResources();
</script>
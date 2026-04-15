<?php include_once __DIR__ . '/../db.php'; ?>

<!-- Leaflet CSS — FREE, no API key needed -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<section id="location-section" class="space-y-6">

    <!-- Header -->
    <div class="rounded-3xl p-6 relative overflow-hidden group"
         style="background: linear-gradient(135deg, #212529 0%, #343A40 50%, #495057 100%);">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-orange-500/20 to-red-500/20 rounded-full blur-3xl -mr-20 -mt-20 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-gradient-to-tr from-amber-500/20 to-orange-500/20 rounded-full blur-2xl -ml-10 -mb-10 animate-pulse delay-1000"></div>
        </div>
        <div class="absolute top-10 right-20 w-16 h-16 border border-orange-500/20 rounded-2xl rotate-45 animate-float-slow"></div>
        <div class="absolute bottom-10 left-20 w-12 h-12 border border-orange-500/20 rounded-full animate-float"></div>
        <div class="absolute top-20 left-40 w-8 h-8 bg-orange-500/10 rounded-lg rotate-12 animate-float-delayed"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.5) 1px, transparent 0); background-size: 40px 40px;"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-xs text-white/40 uppercase tracking-[0.2em] font-light relative">
                    LOCATION MANAGEMENT
                    <span class="absolute -bottom-1 left-0 w-8 h-0.5 bg-gradient-to-r from-orange-400 to-red-400 rounded-full"></span>
                </span>
                <span class="w-1 h-1 rounded-full bg-orange-400 animate-pulse"></span>
                <span class="text-xs bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-white/80 border border-white/20 flex items-center gap-1">
                    <i class="ri-map-pin-line text-orange-400"></i> <span id="totalLocations">0</span> Total
                </span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="relative">
                    <h1 class="text-4xl md:text-5xl font-bold mb-2">
                        <span class="text-white relative inline-block">
                            Locations
                            <span class="absolute -bottom-2 left-0 w-full h-1 bg-gradient-to-r from-orange-400 to-red-400 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></span>
                        </span>
                        <span class="text-white/40 mx-2">/</span>
                        <span class="text-white/60 text-2xl">Management</span>
                    </h1>
                    <p class="text-white/30 text-sm flex items-center gap-2">
                        <i class="ri-sparkling-line text-orange-400 animate-pulse"></i>
                        Manage and visualize all community locations on an interactive map
                    </p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportLocations()"
                            class="px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all group relative overflow-hidden">
                        <span class="absolute inset-0 bg-gradient-to-r from-orange-500/20 to-red-500/20 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        <i class="ri-download-line text-white/70 group-hover:text-white relative z-10"></i>
                        <span class="hidden md:inline relative z-10">Export</span>
                    </button>
                    <button id="addLocationBtn"
                            class="px-4 py-2 bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 rounded-xl text-white font-medium text-sm flex items-center gap-2 transition-all shadow-lg shadow-orange-500/20 relative overflow-hidden group">
                        <span class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span>
                        <i class="ri-add-line relative z-10"></i>
                        <span class="hidden md:inline relative z-10">Add Location</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="rounded-xl p-5 relative overflow-hidden" style="background: #2A2F37; border: 1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-orange-500/10 to-red-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Locations</p>
                    <p class="text-3xl font-bold text-white mt-1" id="statTotalLocations">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center shadow-lg">
                    <i class="ri-map-pin-line text-white text-lg"></i>
                </div>
            </div>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background: #2A2F37; border: 1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Facilities</p>
                    <p class="text-3xl font-bold text-white mt-1" id="statTotalFacilities">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg">
                    <i class="ri-building-line text-white text-lg"></i>
                </div>
            </div>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden" style="background: #2A2F37; border: 1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-green-500/10 to-emerald-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Regions</p>
                    <p class="text-3xl font-bold text-white mt-1" id="statRegions">0</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-lg">
                    <i class="ri-earth-line text-white text-lg"></i>
                </div>
            </div>
        </div>
        <div class="rounded-xl p-5 relative overflow-hidden cursor-pointer" id="toggleMapView" style="background: #2A2F37; border: 1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Current View</p>
                    <p class="text-2xl font-bold text-white mt-1" id="currentViewLabel">Table View</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
                    <i class="ri-map-2-line text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-500">Click to toggle views</div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="rounded-2xl p-5" style="background: #2A2F37; border: 1px solid rgba(255,255,255,0.05);">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="locationSearch" placeholder="Search by name, address, or facilities..."
                       class="w-full pl-12 pr-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all">
            </div>
            <div class="flex gap-2">
                <select id="regionFilter" class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-orange-500 appearance-none cursor-pointer min-w-[150px]">
                    <option value="" class="bg-gray-800">All Regions</option>
                </select>
                <select id="facilityTypeFilter" class="px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-orange-500 appearance-none cursor-pointer min-w-[150px]">
                    <option value="" class="bg-gray-800">All Facilities</option>
                    <option value="Community Hall" class="bg-gray-800">Community Hall</option>
                    <option value="Training Center" class="bg-gray-800">Training Center</option>
                    <option value="Health Center" class="bg-gray-800">Health Center</option>
                    <option value="School" class="bg-gray-800">School</option>
                </select>
                <button id="clearLocationFilters" class="px-4 py-3 bg-black/20 hover:bg-black/30 border border-white/10 rounded-xl text-gray-400 hover:text-white transition-all relative group">
                    <i class="ri-close-line"></i>
                    <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Clear</span>
                </button>
            </div>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="flex items-center justify-between px-2">
        <div class="flex items-center gap-2">
            <span class="text-white/60 text-sm">View:</span>
            <button id="locationTableViewBtn" class="px-3 py-1.5 bg-orange-500/20 text-orange-400 rounded-lg text-sm font-medium flex items-center gap-1">
                <i class="ri-table-line"></i> Table
            </button>
            <button id="locationMapViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all">
                <i class="ri-map-2-line"></i> Map
            </button>
            <button id="locationCardViewBtn" class="px-3 py-1.5 bg-white/5 text-gray-400 hover:bg-white/10 rounded-lg text-sm font-medium flex items-center gap-1 transition-all">
                <i class="ri-layout-grid-line"></i> Cards
            </button>
        </div>
        <div class="text-white/40 text-sm"><span id="showingLocationsCount">0</span> locations</div>
    </div>

    <!-- Table View -->
    <div id="locationTableView" class="rounded-2xl overflow-hidden" style="background: #2A2F37; border: 1px solid rgba(255,255,255,0.05);">
        <div id="locationLoadingState" class="text-center py-12 hidden">
            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-orange-500 to-red-500 flex items-center justify-center mb-4 mx-auto">
                <i class="ri-loader-4-line animate-spin text-2xl text-white"></i>
            </div>
            <p class="text-gray-400 mt-4">Loading locations...</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background: #1E2228; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <tr>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Location</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Address</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Region</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Facilities</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Coordinates</th>
                        <th class="text-left py-4 px-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="locationTable" class="divide-y divide-white/5"></tbody>
            </table>
        </div>
        <div id="locationEmptyState" class="hidden text-center py-12">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-orange-500/20 to-red-500/20 flex items-center justify-center">
                <i class="ri-map-pin-line text-4xl text-orange-400/50"></i>
            </div>
            <h3 class="text-white text-lg font-semibold mb-2">No locations found</h3>
            <p class="text-gray-400 text-sm mb-4">Get started by adding your first location</p>
            <button id="addFirstLocationBtn" class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-xl font-medium transition-all inline-flex items-center gap-2">
                <i class="ri-add-line"></i> Add Location
            </button>
        </div>
        <div class="flex items-center justify-between px-6 py-4" style="background: #1E2228; border-top: 1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center gap-4 text-sm">
                <span class="text-gray-400">Total: <b id="locationTotalCount" class="text-white">0</b></span>
                <span class="text-gray-400">Facilities: <b id="locationFacilityCount" class="text-orange-400">0</b></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-400 text-sm">Rows:</span>
                <select id="locationRowsPerPage" class="bg-black/20 border border-white/10 rounded-lg text-white px-3 py-1.5 text-sm">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Map View (Leaflet / OpenStreetMap — FREE) -->
    <div id="locationMapView" class="hidden rounded-2xl overflow-hidden" style="background: #2A2F37; border: 1px solid rgba(255,255,255,0.05);">
        <div class="p-4 border-b border-white/10 flex items-center justify-between">
            <h3 class="text-white font-semibold flex items-center gap-2">
                <i class="ri-map-pin-line text-orange-400"></i> Interactive Map
                <span class="text-xs text-green-400 font-normal bg-green-500/10 px-2 py-0.5 rounded-full border border-green-500/20 ml-1">
                    <i class="ri-check-line"></i> Free · No API Key
                </span>
            </h3>
            <div class="flex gap-2">
                <button id="centerMapBtn" class="px-3 py-1.5 bg-white/5 hover:bg-white/10 rounded-lg text-gray-400 text-sm transition-all">
                    <i class="ri-focus-3-line"></i> Center
                </button>
                <button id="refreshMapBtn" class="px-3 py-1.5 bg-white/5 hover:bg-white/10 rounded-lg text-gray-400 text-sm transition-all">
                    <i class="ri-refresh-line"></i> Refresh
                </button>
            </div>
        </div>
        <div id="map" style="height: 500px; width: 100%;"></div>
        <div class="p-3 border-t border-white/10 text-gray-500 text-xs flex items-center gap-2">
            <i class="ri-information-line"></i>
            Click pins to view details · Click map in Add/Edit to drop a pin · Powered by OpenStreetMap
        </div>
    </div>

    <!-- Card View -->
    <div id="locationCardView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-2">
        <div class="text-gray-400 text-sm">
            Showing <span id="locationShowingStart">0</span>–<span id="locationShowingEnd">0</span> of <span id="locationTotalRecords">0</span>
        </div>
        <div class="flex gap-2" id="locationPagination"></div>
    </div>

    <!-- Toast -->
    <div id="locationToast" class="fixed bottom-6 right-6 z-[9999] hidden">
        <div id="locationToastContent" class="flex items-center gap-3 px-5 py-3 rounded-xl shadow-2xl text-white text-sm font-medium animate-fadeInUp">
            <i id="locationToastIcon" class="text-lg"></i>
            <span id="locationToastMsg"></span>
        </div>
    </div>

    <!-- Add / Edit Modal -->
    <div id="locationModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" id="locationModalOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-3xl px-4">
            <div class="rounded-2xl shadow-2xl p-6 relative animate-slideIn max-h-[90vh] overflow-y-auto"
                 style="background: linear-gradient(135deg,#2A2F37 0%,#1E2228 100%); border: 1px solid rgba(255,255,255,0.1);">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-orange-500/10 to-red-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl blur-md opacity-70"></div>
                            <div class="relative w-14 h-14 rounded-xl bg-gradient-to-r from-orange-500 to-red-600 flex items-center justify-center">
                                <i class="ri-map-pin-line text-2xl text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-orange-400 to-red-400 bg-clip-text text-transparent" id="locationModalTitle">Add Location</h2>
                            <p class="text-gray-400 text-sm">Enter details or click the map to drop a pin</p>
                        </div>
                        <button id="closeLocationModalBtn" class="ml-auto w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center border border-white/10">
                            <i class="ri-close-line text-gray-400"></i>
                        </button>
                    </div>

                    <form id="locationForm" class="space-y-4">
                        <input type="hidden" id="locationId">
                        <input type="hidden" id="locationLat" value="10.3157">
                        <input type="hidden" id="locationLng" value="123.8854">

                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                    <i class="ri-building-line text-orange-400 mr-1"></i> Location Name *
                                </label>
                                <input type="text" id="locationName" placeholder="e.g., Community Hall"
                                       class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all">
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                    <i class="ri-price-tag-3-line text-orange-400 mr-1"></i> Region
                                </label>
                                <select id="locationRegion" class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                                    <option value="" class="bg-gray-800">Select Region</option>
                                    <option value="Metro Manila" class="bg-gray-800">Metro Manila</option>
                                    <option value="Central Luzon" class="bg-gray-800">Central Luzon</option>
                                    <option value="CALABARZON" class="bg-gray-800">CALABARZON</option>
                                    <option value="Visayas" class="bg-gray-800">Visayas</option>
                                    <option value="Mindanao" class="bg-gray-800">Mindanao</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                <i class="ri-map-pin-line text-orange-400 mr-1"></i> Address *
                            </label>
                            <div class="flex gap-2">
                                <input type="text" id="locationAddress" placeholder="e.g., Kamagayan, Cebu City"
                                       class="flex-1 px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all">
                                <button type="button" id="geocodeAddressBtn"
                                        class="px-4 py-3 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl text-white text-sm font-medium hover:shadow-lg transition-all flex items-center gap-1 whitespace-nowrap">
                                    <i class="ri-search-line"></i> Find
                                </button>
                            </div>
                        </div>

                        <!-- Leaflet Modal Map -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                <i class="ri-map-2-line text-orange-400 mr-1"></i> Pin Location
                                <span class="text-gray-600 font-normal normal-case ml-1">(click map or drag pin)</span>
                            </label>
                            <div id="modalMap" style="height:260px; width:100%; border-radius:12px; overflow:hidden; border:1px solid rgba(255,255,255,0.1);"></div>
                            <div class="flex justify-between mt-2 text-xs text-gray-500">
                                <span>Lat: <span id="latDisplay">10.3157</span></span>
                                <span>Lng: <span id="lngDisplay">123.8854</span></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                <i class="ri-building-2-line text-orange-400 mr-1"></i> Facilities (comma-separated)
                            </label>
                            <input type="text" id="locationFacilities" placeholder="e.g., Stage, Chairs, Sound System"
                                   class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                <i class="ri-file-text-line text-orange-400 mr-1"></i> Notes
                            </label>
                            <textarea id="locationNotes" rows="2" placeholder="Additional information..."
                                      class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                            <button type="button" id="cancelLocationBtn"
                                    class="px-6 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                            <button type="submit" id="locationSubmitBtn"
                                    class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 rounded-xl text-white font-medium transition-all shadow-lg shadow-orange-500/20 flex items-center gap-2">
                                <i class="ri-save-line"></i> Save Location
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="locationDeleteModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" id="locationDeleteOverlay"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm px-4">
            <div class="rounded-2xl shadow-2xl p-6 text-center animate-slideIn"
                 style="background:linear-gradient(135deg,#2A2F37 0%,#1E2228 100%); border:1px solid rgba(255,255,255,0.1);">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-500/10 flex items-center justify-center">
                    <i class="ri-delete-bin-line text-3xl text-red-400"></i>
                </div>
                <h3 class="text-white text-lg font-semibold mb-2">Delete Location?</h3>
                <p class="text-gray-400 text-sm mb-6">This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button id="cancelLocationDeleteBtn" class="flex-1 py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 font-medium transition-all">Cancel</button>
                    <button id="confirmLocationDeleteBtn" class="flex-1 py-2.5 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl text-white font-medium transition-all">Delete</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leaflet JS — FREE, no API key needed -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
@keyframes float         { 0%,100%{transform:translateY(0)}          50%{transform:translateY(-10px)} }
@keyframes float-slow    { 0%,100%{transform:translateY(0)}          50%{transform:translateY(-15px)} }
@keyframes float-delayed { 0%,100%{transform:translateY(0) scale(1)} 50%{transform:translateY(-8px) scale(1.05)} }
@keyframes slideIn       { from{opacity:0;transform:translateY(-20px) scale(0.97)} to{opacity:1;transform:translateY(0) scale(1)} }
@keyframes fadeInUp      { from{opacity:0;transform:translateY(8px)}  to{opacity:1;transform:translateY(0)} }

.animate-float         { animation: float 4s ease-in-out infinite; }
.animate-float-slow    { animation: float-slow 6s ease-in-out infinite; }
.animate-float-delayed { animation: float-delayed 5s ease-in-out infinite; }
.animate-slideIn       { animation: slideIn 0.35s cubic-bezier(0.175,0.885,0.32,1.275) forwards; }
.animate-fadeInUp      { animation: fadeInUp 0.3s ease forwards; }

#locationTable tr { transition: background 0.15s; }
#locationTable tr:hover { background: rgba(255,255,255,0.04); }
button { cursor: pointer; transition: all 0.18s ease; }
button:not(:disabled):hover  { transform: translateY(-1px); }
button:not(:disabled):active { transform: translateY(0); }

/* Dark Leaflet popups */
.leaflet-popup-content-wrapper {
    background: #1E2228 !important;
    color: #fff !important;
    border-radius: 12px !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    box-shadow: 0 8px 32px rgba(0,0,0,0.6) !important;
    padding: 0 !important;
}
.leaflet-popup-content { margin: 0 !important; }
.leaflet-popup-tip     { background: #1E2228 !important; }
.leaflet-popup-close-button { color: #888 !important; top: 8px !important; right: 10px !important; font-size: 18px !important; }
.leaflet-popup-close-button:hover { color: #fff !important; background: none !important; }

/* Dark map tiles */
#map .leaflet-tile,
#modalMap .leaflet-tile {
    filter: brightness(0.6) invert(1) contrast(3) hue-rotate(200deg) saturate(0.3) brightness(0.7);
}
</style>

<script>
// ══════════════════════════════════════════════════════
//  CONFIG
// ══════════════════════════════════════════════════════
const LOCATION_API = 'api/location_api.php';
const DEFAULT_LAT  = 10.3157;   // Cebu City center
const DEFAULT_LNG  = 123.8854;

// ══════════════════════════════════════════════════════
//  STATE
// ══════════════════════════════════════════════════════
let locations         = [];
let editLocationIndex = null;
let currentLocPage    = 1;
let locRowsPerPage    = 10;
let currentLocView    = 'table';
let mainMap           = null;
let modalMap          = null;
let mainMarkers       = [];
let modalMarker       = null;
let locationDeleteIdx = null;

const $ = id => document.getElementById(id);

// ══════════════════════════════════════════════════════
//  TOAST
// ══════════════════════════════════════════════════════
function showToast(msg, type = 'success') {
    const colors = { success:'linear-gradient(135deg,#22c55e,#16a34a)', error:'linear-gradient(135deg,#ef4444,#dc2626)', info:'linear-gradient(135deg,#f97316,#ef4444)' };
    const icons  = { success:'ri-checkbox-circle-line', error:'ri-error-warning-line', info:'ri-information-line' };
    $('locationToastContent').style.background = colors[type]||colors.info;
    $('locationToastIcon').className = (icons[type]||icons.info)+' text-lg';
    $('locationToastMsg').textContent = msg;
    $('locationToast').classList.remove('hidden');
    clearTimeout(window._toastT);
    window._toastT = setTimeout(() => $('locationToast').classList.add('hidden'), 3000);
}

// ══════════════════════════════════════════════════════
//  API — LOAD
// ══════════════════════════════════════════════════════
async function initLocations() {
    $('locationLoadingState').classList.remove('hidden');
    $('locationTable').innerHTML = '';
    try {
        const res  = await fetch(LOCATION_API);
        const json = await res.json();
        if (json.success) {
            locations = json.data || [];
            updateStats(); populateRegionFilter(); renderLocations();
        } else { showToast('Load failed: '+json.error,'error'); }
    } catch(e) { showToast('Network error: '+e.message,'error'); }
    finally { $('locationLoadingState').classList.add('hidden'); }
}

// ══════════════════════════════════════════════════════
//  STATS
// ══════════════════════════════════════════════════════
function updateStats() {
    let fac=0; const reg=new Set();
    locations.forEach(l => {
        if(l.facilities) fac+=l.facilities.split(',').filter(f=>f.trim()).length;
        if(l.region) reg.add(l.region);
    });
    $('statTotalLocations').textContent=$('totalLocations').textContent=$('locationTotalCount').textContent=locations.length;
    $('statTotalFacilities').textContent=$('locationFacilityCount').textContent=fac;
    $('statRegions').textContent=reg.size;
}

function populateRegionFilter() {
    const regs=[...new Set(locations.map(l=>l.region).filter(Boolean))], cur=regionFilter.value;
    regionFilter.innerHTML='<option value="" class="bg-gray-800">All Regions</option>';
    regs.forEach(r=>{ const o=document.createElement('option'); o.value=r; o.textContent=r; o.className='bg-gray-800'; regionFilter.appendChild(o); });
    regionFilter.value=cur;
}

// ══════════════════════════════════════════════════════
//  RENDER
// ══════════════════════════════════════════════════════
const locationSearch     = $('locationSearch');
const regionFilter       = $('regionFilter');
const facilityTypeFilter = $('facilityTypeFilter');
const tableViewBtn       = $('locationTableViewBtn');
const mapViewBtn         = $('locationMapViewBtn');
const cardViewBtn        = $('locationCardViewBtn');
const tableView          = $('locationTableView');
const locationEmptyState = $('locationEmptyState');
const locationCardView   = $('locationCardView');
const locationMapView    = $('locationMapView');

function renderLocations() {
    const s=locationSearch.value.toLowerCase(), r=regionFilter.value, f=facilityTypeFilter.value;
    const filtered=locations.filter(l=>
        (l.name.toLowerCase().includes(s)||(l.address&&l.address.toLowerCase().includes(s))||(l.facilities&&l.facilities.toLowerCase().includes(s)))
        &&(!r||l.region===r)&&(!f||(l.facilities&&l.facilities.includes(f)))
    );
    const total=filtered.length, start=(currentLocPage-1)*locRowsPerPage, paged=filtered.slice(start,start+locRowsPerPage);
    $('showingLocationsCount').textContent=total;
    $('locationShowingStart').textContent=total?start+1:0;
    $('locationShowingEnd').textContent=Math.min(start+locRowsPerPage,total);
    $('locationTotalRecords').textContent=total;
    if(total===0){ locationEmptyState.classList.remove('hidden'); $('locationTable').innerHTML=''; locationCardView.innerHTML=''; }
    else { locationEmptyState.classList.add('hidden'); if(currentLocView==='table') renderTable(paged); if(currentLocView==='cards') renderCards(paged); }
    renderPagination(total);
    if(currentLocView==='map'&&mainMap) loadMainMarkers();
}

function renderTable(locs) {
    $('locationTable').innerHTML = locs.map((l,i)=>`
        <tr>
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center text-white text-sm flex-shrink-0"><i class="ri-map-pin-line"></i></div>
                    <div><span class="text-white font-medium">${esc(l.name)}</span><p class="text-gray-500 text-xs">${esc(l.region||'')}</p></div>
                </div>
            </td>
            <td class="px-6 py-4"><span class="text-gray-400 text-sm">${esc(l.address||'—')}</span></td>
            <td class="px-6 py-4"><span class="text-orange-400 text-sm">${esc(l.region||'—')}</span></td>
            <td class="px-6 py-4"><span class="text-gray-400 text-sm">${esc(l.facilities||'—')}</span></td>
            <td class="px-6 py-4"><span class="text-xs text-gray-500">${l.lat?parseFloat(l.lat).toFixed(4):'—'}, ${l.lng?parseFloat(l.lng).toFixed(4):'—'}</span></td>
            <td class="px-6 py-4">
                <div class="flex gap-2">
                    <button onclick="editLocation(${i})" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-orange-500/20 flex items-center justify-center transition-all group" title="Edit"><i class="ri-edit-line text-gray-400 group-hover:text-orange-400"></i></button>
                    <button onclick="viewOnMap('${l.lat}','${l.lng}')" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-blue-500/20 flex items-center justify-center transition-all group" title="Map"><i class="ri-map-pin-line text-gray-400 group-hover:text-blue-400"></i></button>
                    <button onclick="openDeleteModal(${i})" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-red-500/20 flex items-center justify-center transition-all group" title="Delete"><i class="ri-delete-bin-line text-gray-400 group-hover:text-red-400"></i></button>
                </div>
            </td>
        </tr>`).join('');
}

function renderCards(locs) {
    locationCardView.innerHTML = locs.map((l,i)=>{
        const facs=l.facilities?l.facilities.split(',').map(f=>f.trim()).filter(Boolean):[];
        return `<div class="rounded-xl p-5 relative overflow-hidden hover:scale-[1.02] transition-all duration-300" style="background:#2A2F37;border:1px solid rgba(255,255,255,0.05);">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-500/10 to-red-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center"><i class="ri-map-pin-line text-white text-xl"></i></div>
                <span class="px-2 py-1 rounded-full text-xs bg-orange-500/20 text-orange-400 border border-white/10">${esc(l.region||'No Region')}</span>
            </div>
            <h3 class="text-white font-semibold text-lg mb-1">${esc(l.name)}</h3>
            <p class="text-gray-400 text-sm mb-3">${esc(l.address||'')}</p>
            <div class="flex flex-wrap gap-1 mb-3">${facs.map(f=>`<span class="px-2 py-0.5 bg-white/5 rounded-full text-xs text-gray-300">${esc(f)}</span>`).join('')}</div>
            ${l.notes?`<p class="text-xs text-gray-500 mb-2"><i class="ri-file-text-line mr-1"></i>${esc(l.notes)}</p>`:''}
            ${l.lat&&l.lng?`<p class="text-xs text-gray-600 mb-3"><i class="ri-map-2-line mr-1"></i>${parseFloat(l.lat).toFixed(4)}, ${parseFloat(l.lng).toFixed(4)}</p>`:''}
            <div class="flex justify-end gap-2 pt-2 border-t border-white/5">
                <button onclick="viewOnMap('${l.lat}','${l.lng}')" class="px-3 py-1.5 bg-white/5 hover:bg-blue-500/20 rounded-lg text-gray-400 hover:text-blue-400 text-xs transition-all"><i class="ri-map-pin-line"></i> Map</button>
                <button onclick="editLocation(${i})" class="px-3 py-1.5 bg-white/5 hover:bg-orange-500/20 rounded-lg text-gray-400 hover:text-orange-400 text-xs transition-all"><i class="ri-edit-line"></i> Edit</button>
                <button onclick="openDeleteModal(${i})" class="px-3 py-1.5 bg-white/5 hover:bg-red-500/20 rounded-lg text-gray-400 hover:text-red-400 text-xs transition-all"><i class="ri-delete-bin-line"></i> Delete</button>
            </div>
        </div>`;
    }).join('');
}

function renderPagination(total) {
    const pages=Math.ceil(total/locRowsPerPage)||1;
    $('locationPagination').innerHTML=`
        <button onclick="changeLocPage(${currentLocPage-1})" ${currentLocPage<=1?'disabled':''} class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 text-gray-400 hover:bg-white/10 disabled:opacity-30 disabled:cursor-not-allowed transition-all"><i class="ri-arrow-left-s-line"></i></button>
        <span class="text-gray-400 text-sm px-2">${currentLocPage}/${pages}</span>
        <button onclick="changeLocPage(${currentLocPage+1})" ${currentLocPage>=pages?'disabled':''} class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 text-gray-400 hover:bg-white/10 disabled:opacity-30 disabled:cursor-not-allowed transition-all"><i class="ri-arrow-right-s-line"></i></button>`;
}
function changeLocPage(p){currentLocPage=p; renderLocations();}

// ══════════════════════════════════════════════════════
//  VIEW SWITCH
// ══════════════════════════════════════════════════════
function updateViewBtns(v) {
    [tableViewBtn,mapViewBtn,cardViewBtn].forEach(b=>{b.classList.remove('bg-orange-500/20','text-orange-400');b.classList.add('bg-white/5','text-gray-400');});
    const a=v==='table'?tableViewBtn:v==='map'?mapViewBtn:cardViewBtn;
    a.classList.add('bg-orange-500/20','text-orange-400');a.classList.remove('bg-white/5','text-gray-400');
    $('currentViewLabel').textContent=v==='table'?'Table View':v==='map'?'Map View':'Card View';
}
function switchView(v) {
    currentLocView=v;
    tableView.classList.toggle('hidden',v!=='table');
    locationMapView.classList.toggle('hidden',v!=='map');
    locationCardView.classList.toggle('hidden',v!=='cards');
    updateViewBtns(v);
    if(v==='map') setTimeout(initMainMap,150); else renderLocations();
}
tableViewBtn.addEventListener('click',()=>switchView('table'));
mapViewBtn.addEventListener('click',()=>switchView('map'));
cardViewBtn.addEventListener('click',()=>switchView('cards'));
$('toggleMapView').addEventListener('click',()=>switchView(currentLocView==='map'?'table':'map'));

// ══════════════════════════════════════════════════════
//  LEAFLET — MAIN MAP
// ══════════════════════════════════════════════════════
function initMainMap() {
    if(mainMap){mainMap.invalidateSize(); loadMainMarkers(); return;}
    mainMap=L.map('map').setView([DEFAULT_LAT,DEFAULT_LNG],12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
        attribution:'© <a href="https://www.openstreetmap.org/copyright" style="color:#f97316">OpenStreetMap</a>',maxZoom:19
    }).addTo(mainMap);
    loadMainMarkers();
}

const pinSvg=(color='#f97316')=>`<div style="width:30px;height:30px;background:linear-gradient(135deg,${color},#ef4444);border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid rgba(255,255,255,0.9);box-shadow:0 4px 14px rgba(0,0,0,0.5);"></div>`;

function loadMainMarkers() {
    mainMarkers.forEach(m=>mainMap.removeLayer(m)); mainMarkers=[];
    locations.forEach(loc=>{
        if(!loc.lat||!loc.lng) return;
        const icon=L.divIcon({className:'',html:pinSvg(),iconSize:[30,30],iconAnchor:[15,30],popupAnchor:[0,-32]});
        const m=L.marker([parseFloat(loc.lat),parseFloat(loc.lng)],{icon}).addTo(mainMap)
            .bindPopup(`<div style="padding:12px 14px;min-width:190px;">
                <div style="font-weight:700;font-size:14px;color:#fff;margin-bottom:6px;">${esc(loc.name)}</div>
                <div style="font-size:12px;color:#aaa;margin-bottom:4px;">📍 ${esc(loc.address||'')}</div>
                ${loc.region?`<div style="font-size:11px;color:#f97316;margin-bottom:4px;">🗺 ${esc(loc.region)}</div>`:''}
                ${loc.facilities?`<div style="font-size:11px;color:#888;">🏢 ${esc(loc.facilities)}</div>`:''}
            </div>`);
        mainMarkers.push(m);
    });
}

// ══════════════════════════════════════════════════════
//  LEAFLET — MODAL MAP
// ══════════════════════════════════════════════════════
function initModalMap() {
    const lat=parseFloat($('locationLat').value)||DEFAULT_LAT;
    const lng=parseFloat($('locationLng').value)||DEFAULT_LNG;
    if(!modalMap) {
        modalMap=L.map('modalMap').setView([lat,lng],15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap',maxZoom:19}).addTo(modalMap);
        const icon=L.divIcon({className:'',html:pinSvg('#f97316'),iconSize:[28,28],iconAnchor:[14,28],popupAnchor:[0,-30]});
        modalMarker=L.marker([lat,lng],{draggable:true,icon}).addTo(modalMap);
        modalMarker.on('dragend',e=>{const p=e.target.getLatLng(); setCoords(p.lat,p.lng);});
        modalMap.on('click',e=>{modalMarker.setLatLng(e.latlng); setCoords(e.latlng.lat,e.latlng.lng);});
    } else {
        modalMap.setView([lat,lng],15);
        modalMarker.setLatLng([lat,lng]);
        modalMap.invalidateSize();
    }
}

function setCoords(lat,lng) {
    $('locationLat').value=$('latDisplay').textContent=parseFloat(lat).toFixed(6);
    $('locationLng').value=$('lngDisplay').textContent=parseFloat(lng).toFixed(6);
}

// ══════════════════════════════════════════════════════
//  GEOCODE — Nominatim (FREE, no key)
// ══════════════════════════════════════════════════════
async function geocodeAddress(address) {
    const btn=$('geocodeAddressBtn');
    btn.disabled=true; btn.innerHTML='<i class="ri-loader-4-line animate-spin"></i>';
    try {
        const res=await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`,{headers:{'Accept-Language':'en'}});
        const data=await res.json();
        if(data&&data.length>0){
            const lat=parseFloat(data[0].lat),lng=parseFloat(data[0].lon);
            setCoords(lat,lng);
            if(modalMap&&modalMarker){modalMap.setView([lat,lng],16);modalMarker.setLatLng([lat,lng]);}
            showToast('Location found on map!','success');
        } else { showToast('Address not found. Try a more specific search.','error'); }
    } catch(e){ showToast('Search error: '+e.message,'error'); }
    finally { btn.disabled=false; btn.innerHTML='<i class="ri-search-line"></i> Find'; }
}

function viewOnMap(lat,lng){
    if(!lat||!lng){showToast('No coordinates available.','info');return;}
    switchView('map');
    setTimeout(()=>mainMap.setView([parseFloat(lat),parseFloat(lng)],16),220);
}

// ══════════════════════════════════════════════════════
//  MODAL
// ══════════════════════════════════════════════════════
function openLocationModal(edit=false){
    $('locationModal').classList.remove('hidden');
    $('locationModalTitle').textContent=edit?'Edit Location':'Add New Location';
    setTimeout(()=>{initModalMap(); if(modalMap) modalMap.invalidateSize();},350);
}
function closeLocationModal(){
    $('locationModal').classList.add('hidden');
    $('locationForm').reset();
    $('locationId').value='';
    setCoords(DEFAULT_LAT,DEFAULT_LNG);
    editLocationIndex=null;
}
function editLocation(i){
    editLocationIndex=i; const l=locations[i];
    $('locationId').value=l.id||''; $('locationName').value=l.name||''; $('locationRegion').value=l.region||'';
    $('locationAddress').value=l.address||''; $('locationFacilities').value=l.facilities||''; $('locationNotes').value=l.notes||'';
    $('locationLat').value=l.lat||DEFAULT_LAT; $('locationLng').value=l.lng||DEFAULT_LNG;
    $('latDisplay').textContent=l.lat||DEFAULT_LAT; $('lngDisplay').textContent=l.lng||DEFAULT_LNG;
    openLocationModal(true);
}

// ══════════════════════════════════════════════════════
//  FORM SUBMIT
// ══════════════════════════════════════════════════════
$('locationForm').addEventListener('submit',async e=>{
    e.preventDefault();
    const isEdit=editLocationIndex!==null;
    const payload={
        id:$('locationId').value, name:$('locationName').value.trim(), region:$('locationRegion').value,
        address:$('locationAddress').value.trim(), facilities:$('locationFacilities').value.trim(),
        lat:$('locationLat').value, lng:$('locationLng').value, notes:$('locationNotes').value.trim()
    };
    if(!payload.name||!payload.address){showToast('Name and address are required.','error');return;}
    const btn=$('locationSubmitBtn');
    btn.disabled=true; btn.innerHTML='<i class="ri-loader-4-line animate-spin"></i> Saving...';
    try {
        const res=await fetch(LOCATION_API,{method:isEdit?'PUT':'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)});
        const json=await res.json();
        if(json.success){showToast(isEdit?'Location updated!':'Location added!','success');await initLocations();if(mainMap)loadMainMarkers();closeLocationModal();}
        else showToast('Error: '+json.error,'error');
    } catch(e){showToast('Network error: '+e.message,'error');}
    finally{btn.disabled=false;btn.innerHTML='<i class="ri-save-line"></i> Save Location';}
});

// ══════════════════════════════════════════════════════
//  DELETE
// ══════════════════════════════════════════════════════
function openDeleteModal(i){locationDeleteIdx=i; $('locationDeleteModal').classList.remove('hidden');}
function closeDeleteModal(){$('locationDeleteModal').classList.add('hidden'); locationDeleteIdx=null;}

$('confirmLocationDeleteBtn').addEventListener('click',async()=>{
    if(locationDeleteIdx===null)return;
    const id=locations[locationDeleteIdx].id;
    const btn=$('confirmLocationDeleteBtn');
    btn.disabled=true; btn.textContent='Deleting...';
    try{
        const res=await fetch(LOCATION_API,{method:'DELETE',headers:{'Content-Type':'application/json'},body:JSON.stringify({id})});
        const json=await res.json();
        if(json.success){showToast('Location deleted.','success');await initLocations();if(mainMap)loadMainMarkers();closeDeleteModal();}
        else showToast('Error: '+json.error,'error');
    }catch(e){showToast('Network error: '+e.message,'error');}
    finally{btn.disabled=false;btn.textContent='Delete';}
});

// ══════════════════════════════════════════════════════
//  EXPORT
// ══════════════════════════════════════════════════════
function exportLocations(){
    if(!locations.length){showToast('No data to export.','info');return;}
    const rows=[['ID','Name','Address','Region','Facilities','Lat','Lng','Notes','Created']];
    locations.forEach(l=>rows.push([l.id||'',l.name||'',l.address||'',l.region||'',l.facilities||'',l.lat||'',l.lng||'',l.notes||'',l.created_at||'']));
    const csv=rows.map(r=>r.map(c=>`"${String(c).replace(/"/g,'""')}"`).join(',')).join('\n');
    const a=document.createElement('a');
    a.href=URL.createObjectURL(new Blob([csv],{type:'text/csv'}));
    a.download=`locations_${new Date().toISOString().split('T')[0]}.csv`;
    a.click(); showToast('Export started!');
}

// ══════════════════════════════════════════════════════
//  HELPERS
// ══════════════════════════════════════════════════════
function esc(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}

// ══════════════════════════════════════════════════════
//  EVENTS
// ══════════════════════════════════════════════════════
$('addLocationBtn').addEventListener('click',()=>openLocationModal());
$('addFirstLocationBtn')?.addEventListener('click',()=>openLocationModal());
$('cancelLocationBtn').addEventListener('click',closeLocationModal);
$('closeLocationModalBtn').addEventListener('click',closeLocationModal);
$('locationModalOverlay').addEventListener('click',closeLocationModal);
$('cancelLocationDeleteBtn').addEventListener('click',closeDeleteModal);
$('locationDeleteOverlay').addEventListener('click',closeDeleteModal);
$('geocodeAddressBtn').addEventListener('click',()=>{
    const a=$('locationAddress').value.trim();
    a?geocodeAddress(a):showToast('Enter an address first.','error');
});
locationSearch.addEventListener('input',()=>{currentLocPage=1;renderLocations();});
regionFilter.addEventListener('change',()=>{currentLocPage=1;renderLocations();});
facilityTypeFilter.addEventListener('change',()=>{currentLocPage=1;renderLocations();});
$('clearLocationFilters').addEventListener('click',()=>{
    locationSearch.value='';regionFilter.value='';facilityTypeFilter.value='';
    currentLocPage=1;renderLocations();
});
$('locationRowsPerPage').addEventListener('change',e=>{locRowsPerPage=parseInt(e.target.value);currentLocPage=1;renderLocations();});
$('centerMapBtn').addEventListener('click',()=>{if(mainMap)mainMap.setView([DEFAULT_LAT,DEFAULT_LNG],12);});
$('refreshMapBtn').addEventListener('click',()=>{if(mainMap){mainMap.invalidateSize();loadMainMarkers();}});
document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeLocationModal();closeDeleteModal();}});

// ══════════════════════════════════════════════════════
//  BOOT
// ══════════════════════════════════════════════════════
initLocations();
</script>
@extends('layouts.app')

@section('title', 'Admin Dashboard - NepXMedica')
@section('page-title', 'Admin Dashboard')

@section('content')
    <!-- Top Actions and Stats -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Appointment
            </button>
        </div>
        <div class="flex items-center gap-4">
            <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm">
                Schedule Availability
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Doctors Card -->
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Doctors</p>
                        <div class="flex items-center gap-2">
                            <p class="text-3xl font-bold text-gray-900">247</p>
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                </svg>
                                +18.7%
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">In Last 7 Days</p>
                </div>
            </div>
            <div class="mt-2">
                <svg class="w-full h-10" viewBox="0 0 100 30">
                    <polyline fill="none" stroke="#6366f1" stroke-width="2" points="0,25 20,15 40,20 60,10 80,18 100,5"></polyline>
                </svg>
            </div>
        </div>
        
        <!-- Patients Card -->
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-yellow-100 rounded-full flex items-center justify-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-red-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Patients</p>
                        <div class="flex items-center gap-2">
                            <p class="text-3xl font-bold text-gray-900">4178</p>
                            <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-semibold rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                                -2.5%
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">In Last 7 Days</p>
                </div>
            </div>
            <div class="mt-2">
                <svg class="w-full h-10" viewBox="0 0 100 30">
                    <path d="M0,25 Q10,15 20,20 T40,18 T60,22 T80,15 T100,20" fill="none" stroke="#f97316" stroke-width="2"></path>
                    <path d="M0,25 Q10,15 20,20 T40,18 T60,22 T80,15 T100,20 L100,30 L0,30 Z" fill="#ffedd5"></path>
                </svg>
            </div>
        </div>
        
        <!-- Appointments Card -->
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-100 to-blue-100 rounded-full flex items-center justify-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Appointments</p>
                        <div class="flex items-center gap-2">
                            <p class="text-3xl font-bold text-gray-900">12178</p>
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                </svg>
                                +6.1%
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">In Last 7 Days</p>
                </div>
            </div>
            <div class="mt-2">
                <svg class="w-full h-10" viewBox="0 0 100 30">
                    <polyline fill="none" stroke="#06b6d4" stroke-width="2" points="0,20 15,10 30,15 45,5 60,12 75,8 90,18 100,10"></polyline>
                </svg>
            </div>
        </div>
        
        <!-- Earnings Card -->
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-teal-100 rounded-full flex items-center justify-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Earnings</p>
                        <div class="flex items-center gap-2">
                            <p class="text-3xl font-bold text-gray-900">$55,124</p>
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                </svg>
                                +12.4%
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">In Last 7 Days</p>
                </div>
            </div>
            <div class="mt-2">
                <svg class="w-full h-10" viewBox="0 0 100 30">
                    <path d="M0,25 Q15,20 30,22 T60,18 T80,20 T100,15 L100,30 L0,30 Z" fill="#d1fae5"></path>
                    <path d="M0,25 Q15,20 30,22 T60,18 T80,20 T100,15" fill="none" stroke="#10b981" stroke-width="2"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Appointment Statistics Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Appointment Statistics</h3>
                    <div class="flex items-center gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                            <span class="text-gray-600">All Appointments</span>
                            <span class="font-semibold text-gray-900">6314</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <span class="text-gray-600">Canceled</span>
                            <span class="font-semibold text-gray-900">456</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span class="text-gray-600">Completed</span>
                            <span class="font-semibold text-gray-900">4578</span>
                        </div>
                    </div>
                </div>
                
                <div class="h-64">
                    <svg class="w-full h-full" viewBox="0 0 700 200">
                        <!-- Bars for each month -->
                        <g transform="translate(50, 20)">
                            <!-- Jan -->
                            <rect x="0" y="120" width="35" height="60" fill="#3b82f6" rx="4"></rect>
                            <rect x="37" y="140" width="35" height="40" fill="#10b981" rx="4"></rect>
                            <!-- Feb -->
                            <rect x="90" y="80" width="35" height="100" fill="#3b82f6" rx="4"></rect>
                            <rect x="127" y="100" width="35" height="80" fill="#10b981" rx="4"></rect>
                            <!-- Mar -->
                            <rect x="180" y="100" width="35" height="80" fill="#3b82f6" rx="4"></rect>
                            <rect x="217" y="130" width="35" height="50" fill="#10b981" rx="4"></rect>
                            <!-- Apr -->
                            <rect x="270" y="60" width="35" height="120" fill="#3b82f6" rx="4"></rect>
                            <rect x="307" y="90" width="35" height="90" fill="#06b6d4" rx="4"></rect>
                            <!-- May -->
                            <rect x="360" y="110" width="35" height="70" fill="#3b82f6" rx="4"></rect>
                            <rect x="397" y="135" width="35" height="45" fill="#10b981" rx="4"></rect>
                            <!-- Jun -->
                            <rect x="450" y="70" width="35" height="110" fill="#3b82f6" rx="4"></rect>
                            <rect x="487" y="95" width="35" height="85" fill="#06b6d4" rx="4"></rect>
                            <!-- Jul -->
                            <rect x="540" y="90" width="35" height="90" fill="#3b82f6" rx="4"></rect>
                            <rect x="577" y="120" width="35" height="60" fill="#10b981" rx="4"></rect>
                        </g>
                        
                        <!-- Labels -->
                        <g transform="translate(50, 195)" class="text-xs text-gray-500">
                            <text x="17" y="0" text-anchor="middle">Jan</text>
                            <text x="107" y="0" text-anchor="middle">Feb</text>
                            <text x="197" y="0" text-anchor="middle">Mar</text>
                            <text x="287" y="0" text-anchor="middle">Apr</text>
                            <text x="377" y="0" text-anchor="middle">May</text>
                            <text x="467" y="0" text-anchor="middle">Jun</text>
                            <text x="557" y="0" text-anchor="middle">Jul</text>
                        </g>
                    </svg>
                </div>
            </div>
            
            <!-- Popular Doctors -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Popular Doctors</h3>
                    <button class="text-sm text-indigo-600 font-medium hover:text-indigo-700">View All</button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border border-gray-200 rounded-xl p-4 text-center">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-100 to-indigo-200 mx-auto mb-3 flex items-center justify-center text-2xl font-bold text-indigo-700">
                            MT
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">Dr. Mick Thompson</h4>
                        <p class="text-sm text-gray-600 mb-3">Cardiologist</p>
                        <div class="flex items-center justify-center gap-1 text-yellow-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-700">4.9</span>
                            <span class="text-xs text-gray-500">(258 Bookings)</span>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-xl p-4 text-center">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-pink-100 to-purple-200 mx-auto mb-3 flex items-center justify-center text-2xl font-bold text-purple-700">
                            EC
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">Dr. Emily Carter</h4>
                        <p class="text-sm text-gray-600 mb-3">Pediatrician</p>
                        <div class="flex items-center justify-center gap-1 text-yellow-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-700">4.8</span>
                            <span class="text-xs text-gray-500">(195 Bookings)</span>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-xl p-4 text-center">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-100 to-emerald-200 mx-auto mb-3 flex items-center justify-center text-2xl font-bold text-emerald-700">
                            DL
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">Dr. David Lee</h4>
                        <p class="text-sm text-gray-600 mb-3">Orthopedist</p>
                        <div class="flex items-center justify-center gap-1 text-yellow-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-700">4.7</span>
                            <span class="text-xs text-gray-500">(156 Bookings)</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Top 3 Departments and Doctors Schedule -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top 3 Departments -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Top 3 Departments</h3>
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1 text-xs font-medium bg-indigo-600 text-white rounded-full">Weekly</button>
                            <button class="px-3 py-1 text-xs font-medium text-gray-600 hover:bg-gray-100 rounded-full">Monthly</button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-center mb-6">
                        <svg class="w-48 h-48" viewBox="0 0 200 200">
                            <circle cx="100" cy="100" r="80" fill="none" stroke="#e5e7eb" stroke-width="30"></circle>
                            <circle cx="100" cy="100" r="80" fill="none" stroke="#6366f1" stroke-width="30" stroke-dasharray="200 500" stroke-dashoffset="0" transform="rotate(-90 100 100)"></circle>
                            <circle cx="100" cy="100" r="80" fill="none" stroke="#06b6d4" stroke-width="30" stroke-dasharray="150 500" stroke-dashoffset="-200" transform="rotate(-90 100 100)"></circle>
                            <circle cx="100" cy="100" r="80" fill="none" stroke="#f59e0b" stroke-width="30" stroke-dasharray="100 500" stroke-dashoffset="-350" transform="rotate(-90 100 100)"></circle>
                            <text x="100" y="105" text-anchor="middle" class="text-2xl font-bold text-gray-900">569</text>
                        </svg>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-indigo-600"></div>
                                <span class="text-sm text-gray-700">Cardiology</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">245</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-cyan-500"></div>
                                <span class="text-sm text-gray-700">Pediatrics</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">185</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                <span class="text-sm text-gray-700">Neurology</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">139</span>
                        </div>
                    </div>
                </div>
                
                <!-- Doctors Schedule -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Doctors Schedule</h3>
                        <button class="text-sm text-indigo-600 font-medium hover:text-indigo-700">View All</button>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-indigo-200 flex items-center justify-center text-indigo-700 font-semibold">
                                MT
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">Dr. Mick Thompson</p>
                                <p class="text-xs text-gray-500">Cardiologist</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">09:00 AM</span>
                        </div>
                        
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-100 to-purple-200 flex items-center justify-center text-purple-700 font-semibold">
                                EC
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">Dr. Emily Carter</p>
                                <p class="text-xs text-gray-500">Pediatrician</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">10:30 AM</span>
                        </div>
                        
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-100 to-emerald-200 flex items-center justify-center text-emerald-700 font-semibold">
                                DL
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">Dr. David Lee</p>
                                <p class="text-xs text-gray-500">Orthopedist</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">02:00 PM</span>
                        </div>
                        
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-100 to-amber-200 flex items-center justify-center text-amber-700 font-semibold">
                                MS
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">Dr. Michael Smith</p>
                                <p class="text-xs text-gray-500">Neurologist</p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">04:15 PM</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- All Appointments -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">All Appointments</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Doctor</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Time</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Mode</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold text-xs">MT</div>
                                        <span class="text-sm font-medium text-gray-900">Dr. John Smith</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-semibold text-xs">JA</div>
                                        <span class="text-sm font-medium text-gray-900">Jesus Adams</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">28 May 2025 - 11:15 AM</td>
                                <td class="py-3 px-4 text-sm text-gray-600">In-Person</td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">Confirmed</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-semibold text-xs">EC</div>
                                        <span class="text-sm font-medium text-gray-900">Dr. Lisa White</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-pink-700 font-semibold text-xs">ER</div>
                                        <span class="text-sm font-medium text-gray-900">Emma Rose</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">28 May 2025 - 09:30 AM</td>
                                <td class="py-3 px-4 text-sm text-gray-600">Online</td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">Canceled</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-semibold text-xs">DL</div>
                                        <span class="text-sm font-medium text-gray-900">Dr. Robert Brown</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold text-xs">OL</div>
                                        <span class="text-sm font-medium text-gray-900">Oliver Lopez</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">30 May 2025 - 02:00 PM</td>
                                <td class="py-3 px-4 text-sm text-gray-600">Online</td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">Confirmed</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-700 font-semibold text-xs">MS</div>
                                        <span class="text-sm font-medium text-gray-900">Dr. Michael Scott</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-700 font-semibold text-xs">GR</div>
                                        <span class="text-sm font-medium text-gray-900">Grace Rivas</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">30 May 2025 - 10:00 AM</td>
                                <td class="py-3 px-4 text-sm text-gray-600">Online</td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">Scheduled</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Right Column -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Appointments Calendar -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Appointments</h3>
                    <div class="text-sm font-medium text-gray-700">April 2025</div>
                </div>
                
                <div class="mb-4">
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option>All Types</option>
                        <option>In-Person</option>
                        <option>Online</option>
                    </select>
                </div>
                
                <div class="grid grid-cols-7 gap-2 mb-2 text-center text-xs font-semibold text-gray-500">
                    <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                </div>
                
                <div class="grid grid-cols-7 gap-2 text-center text-sm">
                    <div class="py-2 text-gray-400">30</div>
                    <div class="py-2 text-gray-400">31</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">1</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">2</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">3</div>
                    <div class="py-2 rounded-lg bg-blue-50 text-blue-700 font-semibold relative">
                        4
                        <span class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-blue-500 rounded-full"></span>
                    </div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">5</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">6</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">7</div>
                    <div class="py-2 rounded-lg bg-blue-50 text-blue-700 font-semibold relative">
                        8
                        <span class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-blue-500 rounded-full"></span>
                    </div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">9</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">10</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">11</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">12</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">13</div>
                    <div class="py-2 rounded-lg bg-indigo-600 text-white font-semibold">14</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">15</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">16</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">17</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">18</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">19</div>
                    <div class="py-2 rounded-lg bg-blue-50 text-blue-700 font-semibold relative">
                        20
                        <span class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-blue-500 rounded-full"></span>
                    </div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">21</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">22</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">23</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">24</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">25</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">26</div>
                    <div class="py-2 rounded-lg bg-blue-50 text-blue-700 font-semibold relative">
                        27
                        <span class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-blue-500 rounded-full"></span>
                    </div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">28</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">29</div>
                    <div class="py-2 rounded-lg hover:bg-gray-100 cursor-pointer">30</div>
                    <div class="py-2 text-gray-400">1</div>
                    <div class="py-2 text-gray-400">2</div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">General Visit</h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-xs text-gray-600">Wed, 09 Apr 2025</p>
                                <p class="text-sm font-medium text-gray-900">08:00 PM</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold text-xs">
                                SM
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-xs text-gray-600">Wed, 09 Apr 2025</p>
                                <p class="text-sm font-medium text-gray-900">04:10 PM</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-semibold text-xs">
                                AB
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-xs text-gray-600">Wed, 09 Apr 2025</p>
                                <p class="text-sm font-medium text-gray-900">10:00 AM</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-semibold text-xs">
                                JW
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Income by Treatment -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Income by Treatment</h3>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-1 text-xs font-medium bg-indigo-600 text-white rounded-full">Weekly</button>
                        <button class="px-3 py-1 text-xs font-medium text-gray-600 hover:bg-gray-100 rounded-full">Monthly</button>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Cardiology</p>
                                <p class="text-xs text-gray-500">4350 Appointments</p>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-gray-900">$5,851</p>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Dental Surgery</p>
                                <p class="text-xs text-gray-500">1876 Appointments</p>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-gray-900">$3,716</p>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Orthopedics</p>
                                <p class="text-xs text-gray-500">3250 Appointments</p>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-gray-900">$2,854</p>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 003.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 000 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 00-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 00-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 00-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 000-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">General Medicine</p>
                                <p class="text-xs text-gray-500">8934 Appointments</p>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-gray-900">$6,450</p>
                    </div>
                </div>
            </div>
            
            <!-- Top 5 Patients, Recent Transactions, Leave Requests -->
            <div class="grid grid-cols-1 gap-6">
                <!-- Top 5 Patients -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Top 5 Patients</h3>
                        <button class="text-sm text-indigo-600 font-medium hover:text-indigo-700">View All</button>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-indigo-200 flex items-center justify-center text-indigo-700 font-semibold">
                                    JA
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Jesus Adams</p>
                                    <p class="text-xs text-gray-500">187 Appointments</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="p-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-100 to-purple-200 flex items-center justify-center text-purple-700 font-semibold">
                                    EB
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Emma Brown</p>
                                    <p class="text-xs text-gray-500">165 Appointments</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="p-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-100 to-emerald-200 flex items-center justify-center text-emerald-700 font-semibold">
                                    OL
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Oliver Lopez</p>
                                    <p class="text-xs text-gray-500">142 Appointments</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="p-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-100 to-amber-200 flex items-center justify-center text-amber-700 font-semibold">
                                    BG
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Benjamin Garcia</p>
                                    <p class="text-xs text-gray-500">128 Appointments</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="p-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-100 to-teal-200 flex items-center justify-center text-teal-700 font-semibold">
                                    JE
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">John Evans</p>
                                    <p class="text-xs text-gray-500">115 Appointments</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="p-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Transactions -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1 text-xs font-medium bg-indigo-600 text-white rounded-full">Weekly</button>
                            <button class="px-3 py-1 text-xs font-medium text-gray-600 hover:bg-gray-100 rounded-full">Monthly</button>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">General Check-up</p>
                                    <p class="text-xs text-gray-500">Completed</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-green-600">+$350</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Online Consultation</p>
                                    <p class="text-xs text-gray-500">Pending</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-green-600">+$180</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Medicine Purchase</p>
                                    <p class="text-xs text-gray-500">Refund</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-red-600">-$50</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 012-2V6a2 2 0 01-2-2H5a2 2 0 01-2-2v6a2 2 0 012 2h2v4l.586-.586z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Online Consultation</p>
                                    <p class="text-xs text-gray-500">Completed</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-green-600">+$165</span>
                        </div>
                    </div>
                </div>
                
                <!-- Leave Requests -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Leave Requests</h3>
                        <button class="text-sm text-indigo-600 font-medium hover:text-indigo-700">Today</button>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-indigo-200 flex items-center justify-center text-indigo-700 font-semibold">
                                    JA
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">James Allison</p>
                                    <p class="text-xs text-gray-500">Sick Leave</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-100 to-purple-200 flex items-center justify-center text-purple-700 font-semibold">
                                    LP
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Lisa Parker</p>
                                    <p class="text-xs text-gray-500">Vacation</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                <div class="w-3 h-3 rounded-full bg-gray-300"></div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-100 to-emerald-200 flex items-center justify-center text-emerald-700 font-semibold">
                                    DW
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">David Wilson</p>
                                    <p class="text-xs text-gray-500">Casual Leave</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-100 to-amber-200 flex items-center justify-center text-amber-700 font-semibold">
                                    DN
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Diane Nelson</p>
                                    <p class="text-xs text-gray-500">Vacation</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-gray-300"></div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-100 to-teal-200 flex items-center justify-center text-teal-700 font-semibold">
                                    SC
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Sarah Cook</p>
                                    <p class="text-xs text-gray-500">Sick Leave</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="mt-6 text-center text-xs text-gray-500">
        <p>© 2025 NepXMedica. All Rights Reserved.</p>
    </div>
@endsection
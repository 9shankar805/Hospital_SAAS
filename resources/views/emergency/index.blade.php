@extends('layouts.app')

@section('title', 'Emergency - Hospital Management System')
@section('page-title', 'Emergency Department')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Triage Queue</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-4 border-l-4 border-red-600 bg-red-50 rounded-r-lg">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 text-xs font-bold bg-red-600 text-white rounded-full">CRITICAL</span>
                            <div>
                                <p class="font-medium text-gray-900">John Doe</p>
                                <p class="text-sm text-gray-600">Chest Pain - 5 mins ago</p>
                            </div>
                        </div>
                        <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium text-sm">
                            Attend Now
                        </button>
                    </div>
                    <div class="flex items-center justify-between p-4 border-l-4 border-orange-600 bg-orange-50 rounded-r-lg">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 text-xs font-bold bg-orange-600 text-white rounded-full">URGENT</span>
                            <div>
                                <p class="font-medium text-gray-900">Jane Smith</p>
                                <p class="text-sm text-gray-600">Fracture - 15 mins ago</p>
                            </div>
                        </div>
                        <button class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium text-sm">
                            Attend
                        </button>
                    </div>
                    <div class="flex items-center justify-between p-4 border-l-4 border-yellow-600 bg-yellow-50 rounded-r-lg">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 text-xs font-bold bg-yellow-600 text-white rounded-full">MODERATE</span>
                            <div>
                                <p class="font-medium text-gray-900">Robert Johnson</p>
                                <p class="text-sm text-gray-600">Fever - 30 mins ago</p>
                            </div>
                        </div>
                        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-sm">
                            Waiting
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ambulance Tracking</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">AMB-001</p>
                            <p class="text-xs text-green-600">Available</p>
                        </div>
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    </div>
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">AMB-002</p>
                            <p class="text-xs text-orange-600">On Call</p>
                        </div>
                        <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                    </div>
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">AMB-003</p>
                            <p class="text-xs text-gray-500">Maintenance</p>
                        </div>
                        <div class="w-3 h-3 bg-gray-500 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
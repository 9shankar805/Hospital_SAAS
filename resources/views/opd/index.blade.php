@extends('layouts.app')

@section('title', 'OPD - Hospital Management System')
@section('page-title', 'Outpatient Department')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Patient Queue</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center gap-4">
                            <span class="text-lg font-bold text-blue-600">01</span>
                            <div>
                                <p class="font-medium text-gray-900">Sarah Miller</p>
                                <p class="text-sm text-gray-600">Cardiology</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">In Consultation</span>
                    </div>
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center gap-4">
                            <span class="text-lg font-bold text-gray-600">02</span>
                            <div>
                                <p class="font-medium text-gray-900">James Harris</p>
                                <p class="text-sm text-gray-600">Orthopedics</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Waiting</span>
                    </div>
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center gap-4">
                            <span class="text-lg font-bold text-gray-600">03</span>
                            <div>
                                <p class="font-medium text-gray-900">Emily Wilson</p>
                                <p class="text-sm text-gray-600">Pediatrics</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">Waiting</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <button class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        + New Registration
                    </button>
                    <button class="w-full px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                        Create Prescription
                    </button>
                    <button class="w-full px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                        Lab Orders
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('title', 'Appointments - Hospital Management System')
@section('page-title', 'Appointment Management')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium">Day</button>
                <button class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg font-medium">Week</button>
                <button class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg font-medium">Month</button>
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                + New Appointment
            </button>
        </div>
        
        <div class="space-y-4">
            <div class="border-l-4 border-green-500 bg-green-50 rounded-r-lg p-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-500">10:00 AM</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Sarah Miller</h4>
                        <p class="text-sm text-gray-600">Dr. Anna Smith - Cardiology</p>
                    </div>
                </div>
                <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Completed</span>
            </div>
            
            <div class="border-l-4 border-yellow-500 bg-yellow-50 rounded-r-lg p-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-500">11:30 AM</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">James Harris</h4>
                        <p class="text-sm text-gray-600">Dr. Michael Johnson - Orthopedics</p>
                    </div>
                </div>
                <span class="px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">In Progress</span>
            </div>
            
            <div class="border-l-4 border-blue-500 bg-blue-50 rounded-r-lg p-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-500">02:00 PM</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Emily Wilson</h4>
                        <p class="text-sm text-gray-600">Dr. Emily Williams - Pediatrics</p>
                    </div>
                </div>
                <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">Scheduled</span>
            </div>
        </div>
    </div>
@endsection
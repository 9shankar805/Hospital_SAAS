@extends('layouts.app')

@section('title', 'Doctors - Hospital Management System')
@section('page-title', 'Doctor Directory')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <input type="text" placeholder="Search doctors..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                + Add Doctor
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="border border-gray-200 rounded-xl p-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xl font-bold">
                        AS
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Dr. Anna Smith</h4>
                        <p class="text-sm text-gray-600">Cardiology</p>
                        <p class="text-xs text-gray-500">15 years experience</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Available</span>
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800 text-sm">View</button>
                        <button class="text-gray-600 hover:text-gray-800 text-sm">Edit</button>
                    </div>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-xl p-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center text-green-700 text-xl font-bold">
                        MJ
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Dr. Michael Johnson</h4>
                        <p class="text-sm text-gray-600">Orthopedics</p>
                        <p class="text-xs text-gray-500">10 years experience</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">In Surgery</span>
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800 text-sm">View</button>
                        <button class="text-gray-600 hover:text-gray-800 text-sm">Edit</button>
                    </div>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-xl p-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 text-xl font-bold">
                        EW
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Dr. Emily Williams</h4>
                        <p class="text-sm text-gray-600">Pediatrics</p>
                        <p class="text-xs text-gray-500">8 years experience</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Available</span>
                    <div class="flex gap-2">
                        <button class="text-blue-600 hover:text-blue-800 text-sm">View</button>
                        <button class="text-gray-600 hover:text-gray-800 text-sm">Edit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
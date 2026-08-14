@extends('layouts.app')

@section('title', 'Patients - Hospital Management System')
@section('page-title', 'Patient Management')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <input type="text" placeholder="Search patients..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                + Add Patient
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-medium text-gray-600">ID</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Patient Name</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Age</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Gender</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Phone</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Department</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Status</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-3 px-4 text-gray-700">#P001</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-medium">SM</div>
                                <span class="font-medium text-gray-900">Sarah Miller</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-700">34</td>
                        <td class="py-3 px-4 text-gray-700">Female</td>
                        <td class="py-3 px-4 text-gray-700">+1 234-567-8900</td>
                        <td class="py-3 px-4 text-gray-700">Cardiology</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Active</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <button class="text-blue-600 hover:text-blue-800">View</button>
                                <button class="text-gray-600 hover:text-gray-800">Edit</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-gray-700">#P002</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-medium">JH</div>
                                <span class="font-medium text-gray-900">James Harris</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-700">45</td>
                        <td class="py-3 px-4 text-gray-700">Male</td>
                        <td class="py-3 px-4 text-gray-700">+1 234-567-8901</td>
                        <td class="py-3 px-4 text-gray-700">Orthopedics</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Active</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <button class="text-blue-600 hover:text-blue-800">View</button>
                                <button class="text-gray-600 hover:text-gray-800">Edit</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-gray-700">#P003</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-medium">EW</div>
                                <span class="font-medium text-gray-900">Emily Wilson</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-700">8</td>
                        <td class="py-3 px-4 text-gray-700">Female</td>
                        <td class="py-3 px-4 text-gray-700">+1 234-567-8902</td>
                        <td class="py-3 px-4 text-gray-700">Pediatrics</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <button class="text-blue-600 hover:text-blue-800">View</button>
                                <button class="text-gray-600 hover:text-gray-800">Edit</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
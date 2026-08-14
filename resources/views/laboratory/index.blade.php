@extends('layouts.app')

@section('title', 'Laboratory - Hospital Management System')
@section('page-title', 'Laboratory Management')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Test Orders</h3>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                + New Test Order
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Order ID</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Patient</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Test Type</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Doctor</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Date</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Status</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-3 px-4 text-gray-700">#T001</td>
                        <td class="py-3 px-4 font-medium text-gray-900">Sarah Miller</td>
                        <td class="py-3 px-4 text-gray-700">Blood Test</td>
                        <td class="py-3 px-4 text-gray-700">Dr. Anna Smith</td>
                        <td class="py-3 px-4 text-gray-700">2024-01-15</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Completed</span>
                        </td>
                        <td class="py-3 px-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm">View Report</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-gray-700">#T002</td>
                        <td class="py-3 px-4 font-medium text-gray-900">James Harris</td>
                        <td class="py-3 px-4 text-gray-700">X-Ray</td>
                        <td class="py-3 px-4 text-gray-700">Dr. Michael Johnson</td>
                        <td class="py-3 px-4 text-gray-700">2024-01-15</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Processing</span>
                        </td>
                        <td class="py-3 px-4">
                            <button class="text-gray-600 hover:text-gray-800 text-sm">Update</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 text-gray-700">#T003</td>
                        <td class="py-3 px-4 font-medium text-gray-900">Emily Wilson</td>
                        <td class="py-3 px-4 text-gray-700">Urine Test</td>
                        <td class="py-3 px-4 text-gray-700">Dr. Emily Williams</td>
                        <td class="py-3 px-4 text-gray-700">2024-01-15</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">Pending</span>
                        </td>
                        <td class="py-3 px-4">
                            <button class="text-gray-600 hover:text-gray-800 text-sm">Start</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
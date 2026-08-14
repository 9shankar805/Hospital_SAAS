@extends('layouts.app')

@section('title', 'IPD - Hospital Management System')
@section('page-title', 'Inpatient Department')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600">Total Beds</p>
            <p class="text-3xl font-bold text-gray-900">200</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600">Occupied</p>
            <p class="text-3xl font-bold text-orange-600">170</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600">Available</p>
            <p class="text-3xl font-bold text-green-600">30</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600">ICU</p>
            <p class="text-3xl font-bold text-red-600">15/20</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ward Allocation</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-3">General Ward</h4>
                <div class="grid grid-cols-5 gap-2">
                    @for($i=1; $i<=10; $i++)
                        <div class="{{ $i <= 8 ? 'bg-red-100 border-red-300 text-red-700' : 'bg-green-100 border-green-300 text-green-700' }} border rounded p-2 text-center text-sm font-medium">
                            {{ $i }}
                        </div>
                    @endfor
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-3">Semi-Private</h4>
                <div class="grid grid-cols-4 gap-2">
                    @for($i=1; $i<=8; $i++)
                        <div class="{{ $i <= 6 ? 'bg-red-100 border-red-300 text-red-700' : 'bg-green-100 border-green-300 text-green-700' }} border rounded p-2 text-center text-sm font-medium">
                            {{ $i }}
                        </div>
                    @endfor
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-3">ICU</h4>
                <div class="grid grid-cols-4 gap-2">
                    @for($i=1; $i<=5; $i++)
                        <div class="{{ $i <= 4 ? 'bg-red-100 border-red-300 text-red-700' : 'bg-green-100 border-green-300 text-green-700' }} border rounded p-2 text-center text-sm font-medium">
                            {{ $i }}
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
@endsection
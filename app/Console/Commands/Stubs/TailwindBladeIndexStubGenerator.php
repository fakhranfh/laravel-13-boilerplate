<?php

namespace App\Console\Commands\Stubs;

use Illuminate\Support\Str;

class TailwindBladeIndexStubGenerator
{
    public function generate(string $name, string $label): string
    {
        $labelKebab = Str::kebab($label);
        $pluralTitle = $label;

        return <<<'BLADE'
@extends('layouts.app')

@section('title', __('LABEL'))

@php
    $topbarTitle = __('LABEL');
@endphp

@section('app-content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ __('LABEL') }}
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    {{ __('Manage your LABEL') }}
                </p>
            </div>
            <a href="{{ route('ROUTENAME.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-50 transition ease-in-out duration-150">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 9.414V17a1 1 0 11-2 0V9.414L7.707 10.707a1 1 0 01-1.414-1.414l4-4z" clip-rule="evenodd" />
                </svg>
                {{ __('New LABEL') }}
            </a>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded dark:bg-green-900 dark:border-green-600 dark:text-green-100">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded dark:bg-red-900 dark:border-red-600 dark:text-red-100">
                {{ session('error') }}
            </div>
        @endif

        <!-- Table Card -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('All LABEL') }}
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-700 dark:text-gray-300 display" id="ROUTENAME-table">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">{{ __('ID') }}</th>
                            <th class="px-6 py-3 text-left font-semibold">{{ __('Name') }}</th>
                            <th class="px-6 py-3 text-left font-semibold">{{ __('Created') }}</th>
                            <th class="px-6 py-3 text-right font-semibold">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('ROUTENAME-table');
            if (table) {
                const dataTable = new DataTable('#ROUTENAME-table', {
                    ajax: {
                        url: '{{ route("ROUTENAME.list") }}',
                        type: 'GET',
                        dataSrc: 'data'
                    },
                    columns: [
                        { data: 'id', title: '{{ __("ID") }}' },
                        { data: 'name', title: '{{ __("Name") }}' },
                        { data: 'created_at', title: '{{ __("Created") }}' },
                        {
                            data: 'actions',
                            title: '{{ __("Actions") }}',
                            orderable: false,
                            searchable: false,
                            render: function(data) {
                                if (!data) return '';
                                return `
                                    <div class="flex gap-2 justify-end">
                                        <a href="${data.show}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">{{ __('View') }}</a>
                                        <a href="${data.edit}" class="text-amber-600 hover:text-amber-900 text-sm font-medium">{{ __('Edit') }}</a>
                                        <button onclick="deleteItem('${data.delete}')" class="text-red-600 hover:text-red-900 text-sm font-medium">{{ __('Delete') }}</button>
                                    </div>
                                `;
                            }
                        }
                    ],
                    order: [[0, 'desc']],
                    pageLength: 10,
                    processing: true,
                    serverSide: false
                });
            }

            window.deleteItem = function(url) {
                if (!confirm('{{ __("Are you sure?") }}')) return;

                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('{{ __("Failed to delete item") }}');
                });
            };
        });
    </script>
@endpush
@endsection
BLADE;
    }
}

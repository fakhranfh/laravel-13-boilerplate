@extends('layouts.app')

@section('title', 'Roles')

@php
    $topbarTitle = 'Roles';
@endphp

@section('app-content')
    <div class="space-y-space-lg">
        @if (session('success'))
            <div class="px-gutter py-space-md bg-success/10 border border-success/20 rounded-lg flex items-center gap-space-md">
                <span class="material-symbols-outlined text-success text-[20px]" data-weight="fill">check_circle</span>
                <p class="font-body-md text-body-md text-success">{{ session('success') }}</p>
            </div>
        @endif

        <div class="flex items-center justify-between">
            <h1 class="font-headline-sm text-headline-sm text-on-surface">Roles</h1>
            <a href="{{ route('roles.create') }}" class="px-space-lg py-space-sm bg-primary text-on-primary rounded-lg font-label-md text-label-md hover:opacity-90 transition-opacity">
                New Role
            </a>
        </div>

        <div class="bg-surface border border-outline-variant rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-outline-variant bg-surface-container-lowest">
                            <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">Name</th>
                            <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">Permissions</th>
                            <th scope="col" class="px-space-lg py-space-md text-right font-label-md text-label-md text-secondary uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr class="border-b border-outline-variant last:border-0">
                                <td class="px-space-lg py-space-md font-body-md text-body-md text-on-surface">{{ $role->name }}</td>
                                <td class="px-space-lg py-space-md font-body-sm text-body-sm text-secondary">
                                    {{ $role->permissions->map(fn ($permission) => $permission->label ?? $permission->name)->join(', ') ?: '—' }}
                                </td>
                                <td class="px-space-lg py-space-md text-right space-x-space-sm whitespace-nowrap">
                                    <a href="{{ route('roles.edit', $role) }}" class="font-label-md text-label-md text-primary hover:underline">Edit</a>
                                    @unless ($role->name === 'admin')
                                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('Delete this role?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-label-md text-label-md text-error hover:underline">Delete</button>
                                        </form>
                                    @endunless
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-space-lg py-space-lg text-center font-body-md text-body-md text-secondary">No roles yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

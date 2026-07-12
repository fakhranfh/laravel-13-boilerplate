@extends('layouts.app')

@section('title', 'Edit Role')

@php
    $topbarTitle = 'Edit Role';
    $rolePermissionIds = old('permissions', $role->permissions->pluck('id')->toArray());
@endphp

@section('app-content')
    <div class="max-w-2xl">
        <form action="{{ route('roles.update', $role) }}" method="POST" class="bg-surface border border-outline-variant rounded-lg p-space-lg space-y-space-lg">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block font-label-md text-label-md text-on-surface mb-space-xs">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required
                    {{ $role->name === 'admin' ? 'readonly' : '' }}
                    class="w-full px-space-md py-space-sm border border-outline-variant rounded-lg font-body-md text-body-md">
                @error('name')
                    <p class="mt-space-xs font-body-sm text-body-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-space-sm">
                    <label class="font-label-md text-label-md text-on-surface">Permissions</label>
                    <label class="flex items-center gap-space-sm font-label-sm text-label-sm text-on-surface">
                        <input type="checkbox" id="select-all-permissions">
                        Select All
                    </label>
                </div>
                <div class="space-y-space-lg divide-y divide-outline-variant">
                    @foreach ($groupedPermissions as $group => $permissions)
                        <div class="{{ $loop->first ? '' : 'pt-space-lg' }}">
                            <label class="flex items-center gap-space-sm font-label-sm text-label-sm text-secondary uppercase mb-space-xs">
                                <input type="checkbox" class="select-group-permissions" data-group="{{ $group }}">
                                {{ $group }}
                            </label>
                            <div class="grid grid-cols-2 gap-space-sm">
                                @foreach ($permissions as $permission)
                                    <label class="flex items-center gap-space-sm font-body-md text-body-md text-on-surface">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                            class="permission-checkbox" data-group="{{ $group }}"
                                            {{ in_array($permission->id, $rolePermissionIds) ? 'checked' : '' }}>
                                        {{ $permission->label ?? $permission->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('permissions')
                    <p class="mt-space-xs font-body-sm text-body-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-space-md">
                <button type="submit" class="px-space-lg py-space-sm bg-primary text-on-primary rounded-lg font-label-md text-label-md hover:opacity-90 transition-opacity">
                    Save
                </button>
                <a href="{{ route('roles.index') }}" class="font-label-md text-label-md text-secondary hover:underline">Cancel</a>
            </div>
        </form>
    </div>

    <x-permission-checkbox-script />
@endsection

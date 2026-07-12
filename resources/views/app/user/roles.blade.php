@extends('layouts.app')

@section('title', 'Assign Roles')

@php
    $topbarTitle = 'Assign Roles';
    $userRoleIds = old('roles', $user->roles->pluck('id')->toArray());
@endphp

@section('app-content')
    <div class="max-w-2xl">
        @if ($errors->has('roles'))
            <div class="mb-space-lg px-gutter py-space-md bg-error/10 border border-error/20 rounded-lg flex items-center gap-space-md">
                <span class="material-symbols-outlined text-error text-[20px]" data-weight="fill">error</span>
                <p class="font-body-md text-body-md text-error">{{ $errors->first('roles') }}</p>
            </div>
        @endif

        <form action="{{ route('users.roles.update', $user) }}" method="POST" class="bg-surface border border-outline-variant rounded-lg p-space-lg space-y-space-lg">
            @csrf
            @method('PUT')

            <div>
                <p class="font-label-md text-label-md text-secondary uppercase mb-space-xs">User</p>
                <p class="font-body-md text-body-md text-on-surface">{{ $user->name }} ({{ $user->email }})</p>
            </div>

            <div>
                <label class="block font-label-md text-label-md text-on-surface mb-space-xs">Roles</label>
                <div class="space-y-space-sm">
                    @foreach ($roles as $role)
                        <label class="flex items-center gap-space-sm font-body-md text-body-md text-on-surface">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                {{ in_array($role->id, $userRoleIds) ? 'checked' : '' }}>
                            {{ $role->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-space-md">
                <button type="submit" class="px-space-lg py-space-sm bg-primary text-on-primary rounded-lg font-label-md text-label-md hover:opacity-90 transition-opacity">
                    Save
                </button>
                <a href="{{ route('users.index') }}" class="font-label-md text-label-md text-secondary hover:underline">Cancel</a>
            </div>
        </form>
    </div>
@endsection

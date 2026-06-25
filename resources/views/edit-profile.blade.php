@extends('layouts.app')

@section('title', 'Edit Profile')

@php
    $topbarTitle = 'Edit Profile';
@endphp

@section('app-content')
    @push('styles')
        <style>
            .profile-card {
                position: relative;
                overflow: hidden;
            }

            .profile-card::before {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                width: 300px;
                height: 300px;
                background: linear-gradient(135deg, #004ac6, #2563eb);
                border-radius: 50%;
                opacity: 0.05;
                pointer-events: none;
            }
        </style>
    @endpush

    <div class="flex items-center justify-center py-space-xl px-gutter">
        <div class="bg-surface w-full max-w-2xl rounded-xl border border-outline-variant p-space-lg md:p-space-xl shadow-[0_2px_8px_rgba(0,0,0,0.06)] hover:shadow-[0_4px_12px_rgba(0,0,0,0.1)] transition-shadow duration-200 profile-card">
            <div class="relative z-10">
                <h2 class="font-headline-lg text-headline-lg text-on-surface mb-space-xs">Edit Profile</h2>
                <p class="font-body-md text-body-md text-secondary mb-space-xl">Update your personal information and profile settings.</p>

                <!-- Profile Photo Section -->
                <div class="flex flex-col md:flex-row items-center md:items-start gap-space-lg mb-space-xl pb-space-lg border-b border-outline-variant">
                    <div class="relative group/avatar cursor-pointer flex-shrink-0">
                        <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-surface-container-low shadow-sm relative">
                            @if(auth()->user()->profile_photo_path)
                                <img src="{{ auth()->user()->profile_photo_path }}" alt="Profile" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-primary flex items-center justify-center text-on-primary font-headline-lg text-headline-lg">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-on-surface/40 flex items-center justify-center opacity-0 group-hover/avatar:opacity-100 transition-opacity duration-200">
                                <span class="material-symbols-outlined text-surface text-[32px]">photo_camera</span>
                            </div>
                        </div>
                        <button class="absolute bottom-0 right-0 bg-surface border border-outline-variant rounded-full p-space-xs shadow-sm text-secondary hover:text-primary hover:border-primary transition-colors">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </button>
                    </div>

                    <div class="text-center md:text-left flex flex-col justify-center flex-1">
                        <h3 class="font-headline-sm text-headline-sm text-on-surface">Profile Picture</h3>
                        <p class="font-body-sm text-body-sm text-secondary mt-space-xs">JPG, GIF or PNG. Max size of 5MB. Square image works best.</p>
                        <div class="mt-space-md flex gap-space-md justify-center md:justify-start">
                            <button type="button" class="font-label-md text-label-md text-primary hover:text-primary-fixed-variant transition-colors">Change Photo</button>
                            <button type="button" class="font-label-md text-label-md text-error hover:text-error transition-colors">Remove</button>
                        </div>
                    </div>
                </div>

                <!-- Form Fields -->
                <form class="space-y-space-lg" method="POST" action="{{ route('edit-profile') }}">
                    @csrf

                    <div class="grid grid-cols-1 gap-space-lg">
                        <!-- Email Address -->
                        <div class="space-y-space-xs">
                            <div class="flex items-center gap-space-xs">
                                <label class="font-label-md text-label-md text-on-surface" for="email">Email Address</label>
                                <span class="inline-flex items-center gap-space-xs px-space-xs py-space-xxs rounded-full bg-success/10 border border-success/20">
                                    <span class="material-symbols-outlined text-[12px] text-success" data-weight="fill">check_circle</span>
                                    <span class="font-label-sm text-label-sm text-success">Verified</span>
                                </span>
                            </div>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary/60 text-[20px]">mail</span>
                                <input class="w-full bg-surface-container-lowest border border-outline-variant text-on-surface font-body-md text-body-md rounded-lg py-space-sm pl-10 pr-space-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('email') border-error @enderror" id="email" name="email" type="email" value="{{ auth()->user()->email }}" required />
                            </div>
                            @error('email')
                                <p class="text-error text-body-sm font-body-sm mt-space-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Full Name -->
                        <div class="space-y-space-xs">
                            <div class="flex items-center">
                                <label class="font-label-md text-label-md text-on-surface" for="name">Full Name</label>
                            </div>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary/60 text-[20px]">person</span>
                                <input class="w-full bg-surface-container-lowest border border-outline-variant text-on-surface font-body-md text-body-md rounded-lg py-space-sm pl-10 pr-space-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('name') border-error @enderror" id="name" name="name" type="text" value="{{ auth()->user()->name }}" required />
                            </div>
                            @error('name')
                                <p class="text-error text-body-sm font-body-sm mt-space-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-space-lg mt-space-lg border-t border-outline-variant flex flex-col sm:flex-row justify-between gap-space-md">
                        <a href="{{ route('change-password') }}" class="px-space-lg py-space-sm rounded-lg border border-outline-variant bg-surface text-on-surface font-label-md text-label-md hover:bg-surface-container-low transition-colors flex items-center justify-center sm:justify-start gap-space-sm">
                            <span class="material-symbols-outlined text-[18px]">lock</span>
                            Change Password
                        </a>
                        <div class="flex gap-space-md">
                            <a href="{{ route('dashboard') }}" class="px-space-lg py-space-sm rounded-lg border border-outline-variant bg-surface text-on-surface font-label-md text-label-md hover:bg-surface-container-low transition-colors inline-block">
                                Cancel
                            </a>
                            <button class="px-space-lg py-space-sm rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:bg-on-primary-fixed-variant transition-colors flex items-center gap-space-sm shadow-sm" type="submit">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
    </div>
@endsection
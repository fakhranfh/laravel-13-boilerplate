@extends('master')

@section('body_class', 'bg-background text-on-background min-h-screen flex flex-col font-body-md')

@section('content')
    @if(!isset($skipTopbar) || !$skipTopbar)
        <x-topbar :title="$topbarTitle ?? 'Dashboard'" :showBackButton="$showBackButton ?? false" />
    @endif

    <!-- Main Content -->
    <main class="flex-grow py-space-lg px-gutter">
        <div class="max-w-7xl mx-auto">
            @yield('app-content')
        </div>
    </main>
@endsection

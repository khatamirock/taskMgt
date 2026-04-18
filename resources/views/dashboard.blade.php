@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-in fade-in duration-1000">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-bold tracking-tight">Your <span class="gradient-text">Projects</span></h1>
            <p class="text-slate-400 mt-1">Ready to manage your workload today?</p>
        </div>
        <button class="bg-brand-500 hover:bg-brand-600 px-6 py-3 rounded-2xl flex items-center gap-2 font-semibold transition-all shadow-lg shadow-brand-500/20 active:scale-[0.98]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            New Project
        </button>
    </div>

    <!-- Stats Row (Optional, for premium feel) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass p-6 rounded-3xl group hover:border-brand-500/50 transition-all">
            <p class="text-sm font-medium text-slate-400 uppercase tracking-widest">Active Projects</p>
            <p id="stat-count" class="text-3xl font-bold mt-2">0</p>
        </div>
        <div class="glass p-6 rounded-3xl group hover:border-brand-500/50 transition-all">
            <p class="text-sm font-medium text-slate-400 uppercase tracking-widest">Team Size</p>
            <p id="user-count" class="text-3xl font-bold mt-2">12</p>
        </div>
        <div class="glass p-6 rounded-3xl group hover:border-brand-500/50 transition-all">
            <p class="text-sm font-medium text-slate-400 uppercase tracking-widest">Completion</p>
            <p class="text-3xl font-bold mt-2">68%</p>
        </div>
    </div>

    <!-- Projects Grid -->
    <div id="projects-loader" class="flex flex-col items-center justify-center py-20 space-y-4">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-brand-500"></div>
        <p class="text-slate-400 animate-pulse uppercase text-xs tracking-widest font-bold">Synchronizing projects...</p>
    </div>

    <div id="projects-grid" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Projects will be injected here -->
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="hidden glass p-20 rounded-[2.5rem] text-center space-y-4">
        <div class="w-20 h-20 bg-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
        </div>
        <h3 class="text-2xl font-bold">No projects yet</h3>
        <p class="text-slate-400 max-w-xs mx-auto">Create your first project to start managing tasks with your team.</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        // Auth Check
        if (!api.getToken()) {
            window.location.href = '/';
            return;
        }

        const grid = document.getElementById('projects-grid');
        const loader = document.getElementById('projects-loader');
        const emptyState = document.getElementById('empty-state');
        const statCount = document.getElementById('stat-count');

        try {
            const projects = await api.getProjects();
            loader.classList.add('hidden');

            if (!projects || projects.length === 0) {
                emptyState.classList.remove('hidden');
                return;
            }

            statCount.innerText = projects.length;
            grid.classList.remove('hidden');
            grid.innerHTML = projects.map(project => `
                <div class="glass p-8 rounded-[2rem] hover:scale-[1.02] hover:shadow-2xl hover:shadow-brand-500/10 transition-all cursor-pointer group border border-transparent hover:border-brand-500/30">
                    <div class="flex justify-between items-start mb-6">
                        <div class="p-4 bg-brand-500/10 rounded-2xl text-brand-400 group-hover:bg-brand-500 group-hover:text-white transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400">Stable</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">${project.name}</h3>
                    <p class="text-slate-400 text-sm line-clamp-2 mb-6">${project.description || 'No description provided.'}</p>
                    
                    <div class="pt-6 border-t border-slate-700/50 flex items-center justify-between">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full border-2 border-dark-bg bg-brand-500 flex items-center justify-center text-[10px] font-bold">JD</div>
                            <div class="w-8 h-8 rounded-full border-2 border-dark-bg bg-purple-500 flex items-center justify-center text-[10px] font-bold">AK</div>
                            <div class="w-8 h-8 rounded-full border-2 border-dark-bg bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-400">+5</div>
                        </div>
                        <span class="text-xs text-slate-500 font-medium">Updated 2h ago</span>
                    </div>
                </div>
            `).join('');


        // Inside your <script> on the dashboard
    

            const userCountEl = document.getElementById('user-count');

            const userData = await api.get_totalUsers();
            userCountEl.innerText = userData.count;

        } catch (error) {
            console.error('Failed to load dashboard data:', error);
            loader.innerHTML = '<p class="text-red-400">Failed to load data. Please refresh.</p>';
        }
    });

  


</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-in fade-in duration-1000">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-bold tracking-tight">Project <span class="gradient-text">Tasks</span></h1>
            <p class="text-slate-400 mt-1">Organize and track progress across all projects.</p>
        </div>
        <button onclick="toggleModal('task-modal')" class="bg-brand-500 hover:bg-brand-600 px-6 py-3 rounded-2xl flex items-center gap-2 font-semibold transition-all shadow-lg shadow-brand-500/20 active:scale-[0.98]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            New Task
        </button>
    </div>

    <!-- Kanban Board -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Todo Column -->
        <div class="flex flex-col gap-6">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-slate-400"></div>
                    <h2 class="text-lg font-bold">To Do</h2>
                </div>
                <span id="todo-count" class="text-xs font-bold px-2 py-1 rounded-lg bg-slate-800 text-slate-400">0</span>
            </div>
            <div id="todo-list" class="space-y-4 min-h-[500px] p-2 rounded-3xl transition-colors">
                <!-- Tasks will be injected here -->
            </div>
        </div>

        <!-- In Progress Column -->
        <div class="flex flex-col gap-6">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-brand-500"></div>
                    <h2 class="text-lg font-bold">In Progress</h2>
                </div>
                <span id="in_progress-count" class="text-xs font-bold px-2 py-1 rounded-lg bg-brand-500/10 text-brand-400">0</span>
            </div>
            <div id="in_progress-list" class="space-y-4 min-h-[500px] p-2 rounded-3xl transition-colors">
                <!-- Tasks will be injected here -->
            </div>
        </div>

        <!-- Done Column -->
        <div class="flex flex-col gap-6">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    <h2 class="text-lg font-bold">Done</h2>
                </div>
                <span id="done-count" class="text-xs font-bold px-2 py-1 rounded-lg bg-emerald-500/10 text-emerald-400">0</span>
            </div>
            <div id="done-list" class="space-y-4 min-h-[500px] p-2 rounded-3xl transition-colors">
                <!-- Tasks will be injected here -->
            </div>
        </div>
    </div>
</div>

<!-- New Task Modal -->
<div id="task-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-dark-bg/80 backdrop-blur-sm" onclick="toggleModal('task-modal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-6">
        <div class="glass rounded-[2.5rem] p-8 shadow-2xl border border-white/10">
            <h3 class="text-2xl font-bold mb-6 italic">Create New <span class="gradient-text">Task</span></h3>
            <form id="task-form" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Task Title</label>
                    <input type="text" id="task-title" required class="w-full bg-slate-800/50 border border-slate-700 rounded-2xl px-5 py-3 outline-none focus:border-brand-500 transition-all" placeholder="Enter task title...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Project</label>
                    <select id="task-project" required class="w-full bg-slate-800/50 border border-slate-700 rounded-2xl px-5 py-3 outline-none focus:border-brand-500 transition-all appearance-none cursor-pointer">
                        <option value="">Select a project</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Assigned To</label>
                    <select id="task-assignee" class="w-full bg-slate-800/50 border border-slate-700 rounded-2xl px-5 py-3 outline-none focus:border-brand-500 transition-all appearance-none cursor-pointer">
                        <option value="">Unassigned</option>
                    </select>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="toggleModal('task-modal')" class="flex-1 px-6 py-4 rounded-2xl border border-slate-700 font-semibold hover:bg-white/5 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 bg-brand-500 hover:bg-brand-600 px-6 py-4 rounded-2xl font-semibold transition-all shadow-lg shadow-brand-500/20">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }

    document.addEventListener('DOMContentLoaded', async () => {
        if (!api.getToken()) {
            window.location.href = '/';
            return;
        }

        const taskForm = document.getElementById('task-form');
        const projectSelect = document.getElementById('task-project');
        const assigneeSelect = document.getElementById('task-assignee');

        // Load Initial Data
        async function loadData() {
            try {
                const [tasksResp, projects, usersResp] = await Promise.all([
                    api.getTasks(),
                    api.getProjects(),
                    api.get_totalUsers()
                ]);

                const tasks = tasksResp.data || tasksResp; // Handle pagination wrapper if present
                const users = usersResp.users || [];

                // Populate Selects
                projectSelect.innerHTML = '<option value="">Select a project</option>' + 
                    projects.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
                
                assigneeSelect.innerHTML = '<option value="">Unassigned</option>' + 
                    users.map(u => `<option value="${u.id}">${u.name}</option>`).join('');

                renderTasks(tasks);
            } catch (err) {
                console.error('Failed to load tasks data:', err);
            }
        }

        function renderTasks(tasks) {
            const columns = {
                todo: document.getElementById('todo-list'),
                in_progress: document.getElementById('in_progress-list'),
                done: document.getElementById('done-list')
            };

            const counts = {
                todo: document.getElementById('todo-count'),
                in_progress: document.getElementById('in_progress-count'),
                done: document.getElementById('done-count')
            };

            // Clear columns
            Object.values(columns).forEach(col => col.innerHTML = '');

            const taskGroups = { todo: [], in_progress: [], done: [] };
            tasks.forEach(task => {
                const status = task.status || 'todo';
                if (taskGroups[status]) taskGroups[status].push(task);
            });

            Object.keys(taskGroups).forEach(status => {
                counts[status].innerText = taskGroups[status].length;
                
                if (taskGroups[status].length === 0) {
                    columns[status].innerHTML = `
                        <div class="border-2 border-dashed border-slate-800 rounded-3xl p-8 text-center">
                            <p class="text-slate-500 text-sm italic">Empty</p>
                        </div>
                    `;
                    return;
                }

                columns[status].innerHTML = taskGroups[status].map(task => `
                    <div class="glass p-6 rounded-3xl hover:border-brand-500/30 transition-all group cursor-move">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded bg-slate-800 text-slate-400">
                                ${task.project ? task.project.name : 'No Project'}
                            </span>
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                ${status !== 'todo' ? `<button onclick="updateStatus(${task.id}, 'todo')" class="p-1 hover:text-slate-400">○</button>` : ''}
                                ${status !== 'in_progress' ? `<button onclick="updateStatus(${task.id}, 'in_progress')" class="p-1 hover:text-brand-400">●</button>` : ''}
                                ${status !== 'done' ? `<button onclick="updateStatus(${task.id}, 'done')" class="p-1 hover:text-emerald-400">✓</button>` : ''}
                            </div>
                        </div>
                        <h4 class="font-bold mb-3">${task.title}</h4>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-brand-500/20 text-brand-400 flex items-center justify-center text-[8px] font-bold">
                                    ${task.assigned_to ? task.assigned_to.name.substring(0,2).toUpperCase() : '?'}
                                </div>
                                <span class="text-xs text-slate-500">${task.assigned_to ? task.assigned_to.name : 'Unassigned'}</span>
                            </div>
                        </div>
                    </div>
                `).join('');
            });
        }

        window.updateStatus = async (taskId, status) => {
            try {
                await api.updateTaskStatus(taskId, status);
                loadData();
            } catch (err) {
                console.error('Update status failed:', err);
            }
        };

        taskForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const taskData = {
                title: document.getElementById('task-title').value,
                project_id: document.getElementById('task-project').value,
                assigned_to: document.getElementById('task-assignee').value || null
            };

            try {
                await api.createTask(taskData);
                toggleModal('task-modal');
                taskForm.reset();
                loadData();
            } catch (err) {
                console.error('Create task failed:', err);
            }
        });

        loadData();
    });
</script>
@endsection

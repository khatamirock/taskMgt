<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMgt - Premium Task Management</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1', // Indigo
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        dark: {
                            bg: '#0f172a',
                            card: '#1e293b',
                            border: '#334155'
                        }
                    }
                }
            }
        }
    </script>
    <style type="text/css">
        body {
            background-color: #0f172a;
            color: #f1f5f9;
        }
        .glass {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(51, 65, 85, 0.5);
        }
        .gradient-text {
            background: linear-gradient(135deg, #818cf8 0%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased">
    <div id="app" class="relative overflow-hidden">
        <!-- Background Decorative Elements -->
        <div class="absolute top-0 left-0 w-full h-full -z-10">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-brand-500/10 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[10%] right-[-5%] w-[30%] h-[30%] bg-purple-500/10 rounded-full blur-[100px]"></div>
        </div>

        <nav class="glass sticky top-0 z-50 px-6 py-4 flex justify-between items-center transition-all duration-300">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-brand-500 rounded-lg flex items-center justify-center shadow-lg shadow-brand-500/30">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <span class="text-xl font-bold tracking-tight">Task<span class="gradient-text">Mgt</span></span>
            </div>
            
            <div class="flex items-center gap-8 ml-12">
                <a href="/dashboard" class="text-sm font-semibold opacity-60 hover:opacity-100 transition-opacity">Dashboard</a>
                <a href="/tasks" class="text-sm font-semibold opacity-60 hover:opacity-100 transition-opacity">Tasks</a>
            </div>

            <div id="nav-user" class="hidden flex items-center gap-4">
                <span id="user-name" class="text-sm font-medium opacity-80"></span>
                <button onclick="api.logout()" class="text-sm px-4 py-2 rounded-full border border-dark-border hover:bg-white/5 transition-colors">
                    Logout
                </button>
            </div>
        </nav>

        <main class="container mx-auto px-6 py-12">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const user = api.getUser();
            if (user) {
                document.getElementById('nav-user').classList.remove('hidden');
                document.getElementById('user-name').innerText = user.name;
            }
        });
    </script>
</body>
</html>

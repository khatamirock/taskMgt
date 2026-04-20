@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-12">
    <div class="glass p-8 rounded-3xl shadow-2xl space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <div class="text-center space-y-2">
            <h2 class="text-3xl font-bold tracking-tight">Welcome Back</h2>
            <p class="text-slate-400 text-sm">Please enter your details to sign in.</p>
        </div>

        <form id="login-form" class="space-y-6">
            <div class="space-y-2">
                <label class="text-xs font-semibold uppercase tracking-wider text-slate-400 ml-1">Email Address</label>
                <input type="email" name="email" required 
                    class="w-full bg-slate-800/50 border border-slate-700 rounded-2xl px-5 py-3.5 focus:outline-none focus:ring-2 focus:ring-brand-500/50 transition-all placeholder:text-slate-600"
                    placeholder="name@company.com">
            </div>

            <div class="space-y-2">
                <div class="flex justify-between items-center px-1">
                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Password</label>
                    <a href="#" class="text-xs text-brand-400 hover:text-brand-300 transition-colors">Forgot?</a>
                </div>
                <input type="password" name="password" required
                    class="w-full bg-slate-800/50 border border-slate-700 rounded-2xl px-5 py-3.5 focus:outline-none focus:ring-2 focus:ring-brand-500/50 transition-all">
            </div>

            <button type="submit" 
                class="w-full bg-brand-500 hover:bg-brand-600 active:scale-[0.98] text-white font-semibold py-4 rounded-2xl shadow-lg shadow-brand-500/25 transition-all flex justify-center items-center gap-2">
                Sign In
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </form>

        <div id="error-msg" class="hidden p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm text-center">
        </div>

        <p class="text-center text-sm text-slate-400">
            Don't have an account? 
            <a href="#" class="text-brand-400 font-semibold hover:underline">Sign up</a>
        </p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('login-form');
        const errorDiv = document.getElementById('error-msg');

        // If already logged in, redirect
        if (api.getToken()) {
            window.location.href = '/dashboard';
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorDiv.classList.add('hidden');
            
            const btn = e.target.querySelector('button');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span> Signing in...';

            const email = form.email.value;
            const password = form.password.value;

            try {
                const { ok, data } = await api.login(email, password);
                
                if (ok) {
                    window.location.href = '/dashboard';1
                } else {
                    errorDiv.innerText = data.message || 'Login failed. Please check your credentials.';
                    errorDiv.classList.remove('hidden');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                errorDiv.innerText = 'Something went wrong. Please try again later.';
                errorDiv.classList.remove('hidden');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });
    });
</script>
@endsection

<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-8">
        @csrf
        @method('put')

        <div class="space-y-2">
            <label class="text-[11px] font-black uppercase tracking-widest text-ui-muted ml-1">Legacy Cipher
                (Current)</label>
            <input id="update_password_current_password" name="current_password" type="password" required
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 dark:text-white focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all placeholder:text-slate-300">
            @if($errors->updatePassword->has('current_password'))
            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mt-1 ml-1">{{
                $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div class="space-y-2">
            <label class="text-[11px] font-black uppercase tracking-widest text-ui-muted ml-1">New Structural
                Cipher</label>
            <input id="update_password_password" name="password" type="password" required
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 dark:text-white focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all placeholder:text-slate-300">
            @if($errors->updatePassword->has('password'))
            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mt-1 ml-1">{{
                $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div class="space-y-2">
            <label class="text-[11px] font-black uppercase tracking-widest text-ui-muted ml-1">Verify Target
                Cipher</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" required
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 dark:text-white focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all placeholder:text-slate-300">
            @if($errors->updatePassword->has('password_confirmation'))
            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mt-1 ml-1">{{
                $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-6 pt-4">
            <button type="submit"
                class="bg-slate-900 text-white px-10 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-slate-800 transition-all shadow-2xl active:scale-95 group flex items-center gap-3">
                Rotate Credentials
                <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
            </button>

            @if (session('status') === 'password-updated')
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                class="flex items-center gap-2 text-emerald-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600 italic">Credentials
                    Rotation Successful</span>
            </div>
            @endif
        </div>
    </form>
</section>
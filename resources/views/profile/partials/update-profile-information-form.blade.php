<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
        @csrf
        @method('patch')

        <div class="space-y-2">
            <label class="text-[11px] font-black uppercase tracking-widest text-ui-muted ml-1">Full Identity
                Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 dark:text-white focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all placeholder:text-slate-300">
            @if($errors->has('name'))
            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mt-1 ml-1">{{ $errors->first('name')
                }}</p>
            @endif
        </div>

        <div class="space-y-2">
            <label class="text-[11px] font-black uppercase tracking-widest text-ui-muted ml-1">Corporate Email
                Coordinate</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 dark:text-white focus:ring-4 focus:ring-brand-500/10 focus:bg-white transition-all placeholder:text-slate-300">
            @if($errors->has('email'))
            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mt-1 ml-1">{{
                $errors->first('email') }}</p>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-4 p-4 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-100">
                <p class="text-xs font-bold text-amber-700">
                    Personnel email address unverified.
                    <button form="send-verification"
                        class="ml-2 underline hover:text-amber-900 transition-colors">Invoke re-verification</button>
                </p>
                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-emerald-600">Verification packet
                    dispatched.</p>
                @endif
            </div>
            @endif
        </div>

        <div class="flex items-center gap-6 pt-4">
            <button type="submit"
                class="bg-brand-600 text-white px-10 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-brand-700 transition-all shadow-2xl shadow-brand-500/30 active:scale-95 group flex items-center gap-3">
                Commit Changes
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 5l7 7-7 7M5 5l7 7-7 7">
                    </path>
                </svg>
            </button>

            @if (session('status') === 'profile-updated')
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                class="flex items-center gap-2 text-emerald-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600 italic">State
                    Synchronized</span>
            </div>
            @endif
        </div>
    </form>
</section>
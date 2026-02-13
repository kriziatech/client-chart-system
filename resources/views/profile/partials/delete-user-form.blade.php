<section x-data="{ confirmingDeletion: false }">
    <div class="space-y-6">
        <button x-on:click.prevent="confirmingDeletion = true"
            class="bg-rose-600 text-white px-8 py-3 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-rose-700 transition-all shadow-xl shadow-rose-500/20 active:scale-95">
            Terminate Identity
        </button>
    </div>

    <!-- Termination Confirmation Modal -->
    <div x-show="confirmingDeletion" x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/50 backdrop-blur-sm">
        <div x-show="confirmingDeletion" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white dark:bg-dark-surface rounded-[2.5rem] w-full max-w-lg shadow-2xl border border-rose-100 dark:border-rose-500/20 overflow-hidden"
            @click.away="confirmingDeletion = false">

            <div class="p-10">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-2xl font-black text-rose-600">Identity Termination</h3>
                        <p class="text-sm text-ui-muted dark:text-dark-muted font-medium mt-2 leading-relaxed">System
                            state reversal. This will permanently purge all dossiers and encryption keys associated with
                            this entity.</p>
                    </div>
                    <button @click="confirmingDeletion = false"
                        class="text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l18 18"></path>
                        </svg>
                    </button>
                </div>

                <form method="post" action="{{ route('profile.destroy') }}" class="space-y-8">
                    @csrf
                    @method('delete')

                    <div class="space-y-4">
                        <p
                            class="text-xs font-bold text-slate-500 uppercase tracking-widest text-center px-4 leading-relaxed">
                            To authorize permanent erasure, please input your current secure cipher.</p>

                        <div class="max-w-xs mx-auto">
                            <input id="password" name="password" type="password" required
                                placeholder="Authorization Cipher"
                                class="w-full bg-slate-50 dark:bg-dark-bg border-rose-100 text-center rounded-2xl px-5 py-4 text-sm font-black focus:ring-4 focus:ring-rose-500/10 transition-all placeholder:text-slate-300">
                            @if($errors->userDeletion->has('password'))
                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mt-2 text-center">{{
                                $errors->userDeletion->first('password') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex flex-col gap-4">
                        <button type="submit"
                            class="w-full bg-rose-600 text-white px-10 py-5 rounded-2xl text-[11px] font-black uppercase tracking-[0.25em] hover:bg-rose-700 transition-all shadow-2xl shadow-rose-500/30 active:scale-95">
                            Purge Global Identity
                        </button>
                        <button type="button" @click="confirmingDeletion = false"
                            class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors py-2">
                            Abort Termination
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@extends('layouts.app')

@section('content')
<div x-data="estimateBuilder()" class="max-w-6xl mx-auto space-y-8 animate-in fade-in duration-700">

    <!-- Branding Header -->
    <div
        class="flex items-center justify-between bg-white dark:bg-dark-surface p-8 rounded-3xl border border-ui-border dark:border-dark-border shadow-premium">
        <div>
            <span
                class="px-3 py-1 bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 text-[10px] font-black uppercase tracking-[2px] rounded-full">Pitching
                Tool</span>
            <h1 class="text-3xl font-black text-ui-primary dark:text-white mt-2">Instant Estimate Builder</h1>
            <p class="text-ui-muted dark:text-dark-muted font-medium mt-1">Generate tentative project budgets on the
                spot for clients.</p>
        </div>
        <div
            class="w-16 h-16 bg-brand-50 dark:bg-brand-500/10 rounded-2xl flex items-center justify-center text-brand-600">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2-2v14a2 2 0 002 2z">
                </path>
            </svg>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Configuration Side -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Quality Selection -->
            <div
                class="bg-white dark:bg-dark-surface p-6 rounded-2xl border border-ui-border dark:border-dark-border shadow-premium">
                <h3 class="text-sm font-black text-ui-primary dark:text-white uppercase tracking-wider mb-6">1. Select
                    Finish Quality</h3>
                <div class="grid grid-cols-3 gap-4">
                    <template x-for="(q, key) in qualities" :key="key">
                        <button @click="quality = key"
                            :class="quality === key ? 'border-brand-600 bg-brand-50/50 dark:bg-brand-500/5 shadow-lg scale-[1.02]' : 'border-ui-border dark:border-dark-border hover:border-brand-300'"
                            class="p-4 border-2 rounded-2xl transition-all text-left relative overflow-hidden group">
                            <div x-show="quality === key" class="absolute top-2 right-2 text-brand-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="block text-xs font-black uppercase text-ui-muted dark:text-dark-muted mb-1"
                                x-text="key"></span>
                            <span class="block text-xl font-black text-ui-primary dark:text-white"
                                x-text="'₹' + q.rate"></span>
                            <span class="block text-[10px] text-ui-muted mt-2 leading-relaxed" x-text="q.desc"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Rooms Configuration -->
            <div
                class="bg-white dark:bg-dark-surface p-6 rounded-2xl border border-ui-border dark:border-dark-border shadow-premium">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-sm font-black text-ui-primary dark:text-white uppercase tracking-wider">2. Configure
                        Areas (Sq Ft)</h3>
                    <button @click="addRoom()"
                        class="flex items-center gap-2 text-brand-600 dark:text-brand-400 text-xs font-bold hover:underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Area
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(room, index) in rooms" :key="index">
                        <div
                            class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-ui-border dark:border-dark-border group animate-in slide-in-from-left duration-300">
                            <div class="flex-grow grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-ui-muted uppercase mb-1.5 ml-1">Room/Area
                                        Name</label>
                                    <input type="text" x-model="room.type" placeholder="e.g. Living Room"
                                        class="w-full bg-white dark:bg-dark-surface border-ui-border dark:border-dark-border rounded-lg text-xs font-semibold focus:ring-brand-600">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-ui-muted uppercase mb-1.5 ml-1">Area
                                        (Sq Ft)</label>
                                    <input type="number" x-model.number="room.area"
                                        class="w-full bg-white dark:bg-dark-surface border-ui-border dark:border-dark-border rounded-lg text-xs font-semibold focus:ring-brand-600">
                                </div>
                            </div>
                            <button @click="removeRoom(index)"
                                class="p-2 text-red-400 hover:text-red-600 transition-colors opacity-0 group-hover:opacity-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Result Card -->
        <div class="sticky top-24 h-fit space-y-4">
            <div
                class="bg-brand-600 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-brand-500/20 overflow-hidden relative group">
                <div
                    class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-700">
                </div>

                <h3 class="text-[10px] font-black uppercase tracking-[2px] opacity-80 mb-6">Tentative Estimate</h3>

                <div class="space-y-4 mb-8">
                    <div class="flex justify-between items-end">
                        <span class="text-xs font-medium opacity-80">Sub-Total</span>
                        <span class="text-lg font-bold" x-text="'₹' + formatPrice(subtotal)"></span>
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="text-xs font-medium opacity-80">GST @ 18%</span>
                        <span class="text-lg font-bold" x-text="'₹' + formatPrice(gst)"></span>
                    </div>
                    <div class="pt-4 border-t border-white/20">
                        <span class="text-[10px] font-black uppercase tracking-[2px] opacity-80">Total Amount</span>
                        <div class="text-4xl font-black mt-1" x-text="'₹' + formatPrice(total)"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <button @click="shareEstimate()"
                        class="w-full py-4 bg-white text-brand-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-50 transition-all active:scale-95 shadow-xl">
                        Share on WhatsApp
                    </button>
                    <button @click="window.print()"
                        class="w-full py-4 bg-brand-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-brand-800 transition-all border border-white/10">
                        Print / Save PDF
                    </button>
                </div>
            </div>

            <div
                class="bg-white dark:bg-dark-surface p-6 rounded-2xl border border-ui-border dark:border-dark-border shadow-premium">
                <h4 class="text-[10px] font-black uppercase text-ui-muted mb-4 tracking-[1px]">Area Breakdown</h4>
                <div class="space-y-3">
                    <template x-for="room in rooms">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-ui-primary dark:text-white"
                                x-text="room.type || 'Unnamed Area'"></span>
                            <span class="text-ui-muted" x-text="room.area + ' sqft'"></span>
                        </div>
                    </template>
                    <div class="pt-3 border-t border-ui-border flex justify-between items-center text-xs">
                        <span class="font-black text-ui-primary dark:text-white uppercase">Total Area</span>
                        <span class="font-black text-brand-600" x-text="totalArea + ' sqft'"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function estimateBuilder() {
        return {
            quality: 'Premium',
            qualities: {
                'Standard': { rate: 1200, desc: 'Essential finishes, standard paint and basic tiling. Ideal for rentals.' },
                'Premium': { rate: 2200, desc: 'Quality woodwork, designer laminates and premium paints.' },
                'Luxury': { rate: 4500, desc: 'Imported marble, high-end veneer, duco paint and smart automation.' }
            },
            rooms: [
                { type: 'Master Bedroom', area: 250 },
                { type: 'Kitchen', area: 120 },
                { type: 'Living Area', area: 350 }
            ],
            addRoom() {
                this.rooms.push({ type: '', area: 0 });
            },
            removeRoom(index) {
                this.rooms.splice(index, 1);
            },
            get subtotal() {
                let rate = this.qualities[this.quality].rate;
                return this.rooms.reduce((acc, room) => acc + (room.area * rate), 0);
            },
            get gst() {
                return this.subtotal * 0.18;
            },
            get total() {
                return this.subtotal + this.gst;
            },
            get totalArea() {
                return this.rooms.reduce((acc, room) => acc + (room.area || 0), 0);
            },
            formatPrice(val) {
                return new Intl.NumberFormat('en-IN').format(Math.round(val));
            },
            shareEstimate() {
                let msg = `*Tentative Project Estimate*\nQuality: ${this.quality}\nTotal Area: ${this.totalArea}sqft\nTotal Amount: ₹${this.formatPrice(this.total)}\nGenerated via InteriorTouch`;
                window.open(`https://wa.me/?text=${encodeURIComponent(msg)}`, '_blank');
            }
        }
    }
</script>
@endsection
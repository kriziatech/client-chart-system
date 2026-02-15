@csrf

<!-- Section 1: Client Chart -->
<div
    class="mb-8 bg-white dark:bg-dark-surface rounded-2xl border border-ui-border dark:border-dark-border shadow-premium p-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
    <h3 class="text-xs font-bold uppercase tracking-[2px] text-brand-600 mb-8 flex items-center gap-3">
        <span
            class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600">01</span>
        Client Signature Details
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- File Number -->
        <div class="space-y-2">
            <label class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">File
                Number <span class="text-brand-500 font-normal italic">(Auto-generates if empty)</span></label>
            <input type="text" name="file_number" value="{{ old('file_number', $client->file_number ?? '') }}"
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-brand-500/10 focus:bg-white dark:focus:bg-dark-surface transition-all placeholder:text-slate-400 @error('file_number') ring-2 ring-rose-500/20 border-rose-500 @enderror"
                placeholder="Leave blank for: IT-{{ now()->format('dmy') }}-001">
            @error('file_number') <p class="text-[10px] text-rose-500 font-bold mt-1 italic">{{ $message }}</p>
            @enderror
        </div>

        <!-- First Name -->
        <div class="space-y-2">
            <label class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">First Name
                <span class="text-rose-500">*</span></label>
            <input type="text" name="first_name" value="{{ old('first_name', $client->first_name ?? '') }}"
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-brand-500/10 focus:bg-white dark:focus:bg-dark-surface transition-all placeholder:text-slate-400 @error('first_name') ring-2 ring-rose-500/20 border-rose-500 @enderror"
                placeholder="John" required>
            @error('first_name') <p class="text-[10px] text-rose-500 font-bold mt-1 italic">{{ $message }}</p> @enderror
        </div>

        <!-- Last Name -->
        <div class="space-y-2">
            <label class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Last
                Name</label>
            <input type="text" name="last_name" value="{{ old('last_name', $client->last_name ?? '') }}"
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-brand-500/10 focus:bg-white dark:focus:bg-dark-surface transition-all placeholder:text-slate-400"
                placeholder="Doe">
            @error('last_name') <p class="text-[10px] text-rose-500 font-bold mt-1 italic">{{ $message }}</p> @enderror
        </div>

        <!-- Mobile Number -->
        <div class="space-y-2">
            <label class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Mobile /
                Contact</label>
            <input type="text" name="mobile" value="{{ old('mobile', $client->mobile ?? '') }}"
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-brand-500/10 focus:bg-white dark:focus:bg-dark-surface transition-all placeholder:text-slate-400"
                placeholder="+91 99999 00000">
        </div>

        <!-- Start Date -->
        <div class="space-y-2">
            <label class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Possession
                / Start Date</label>
            <input type="date" name="start_date"
                value="{{ old('start_date', $client->start_date ? $client->start_date->format('Y-m-d') : '') }}"
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-brand-500/10 focus:bg-white dark:focus:bg-dark-surface transition-all">
        </div>

        <!-- Delivery Date -->
        <div class="space-y-2">
            <label class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Target
                Delivery Date</label>
            <input type="date" name="delivery_date"
                value="{{ old('delivery_date', $client->delivery_date ? $client->delivery_date->format('Y-m-d') : '') }}"
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-brand-500/10 focus:bg-white dark:focus:bg-dark-surface transition-all">
        </div>

        <!-- Linked Account -->
        <div class="md:col-span-3 space-y-2 pt-4">
            <label class="text-[11px] font-bold uppercase tracking-widest text-brand-600 flex items-center gap-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
                Client Portal Access
            </label>
            <select name="user_id"
                class="w-full bg-brand-50/50 dark:bg-brand-500/5 border-transparent rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-brand-500/10 focus:bg-white dark:focus:bg-dark-surface transition-all appearance-none cursor-pointer">
                <option value="">-- No Account Linked (Portal Disabled) --</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id', $client->user_id) == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->email }}) — [{{ ucfirst($user->role->description ?? 'Client') }}]
                </option>
                @endforeach
            </select>
            <p class="text-[10px] text-ui-muted dark:text-dark-muted mt-1 italic pl-1">Link an account to enable the
                <b>Client Portal</b>. They will only see updates for this specific project.
            </p>
        </div>

        <!-- Address -->
        <div class="md:col-span-3 space-y-2">
            <label class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Site
                Address</label>
            <textarea name="address" rows="2"
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-brand-500/10 focus:bg-white dark:focus:bg-dark-surface transition-all placeholder:text-slate-400"
                placeholder="Complete postal address of the site...">{{ old('address', $client->address ?? '') }}</textarea>
        </div>

        <!-- Work Description -->
        <div class="md:col-span-3 space-y-2">
            <label class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Executive
                Summary / Scope</label>
            <textarea name="work_description" rows="3"
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-brand-500/10 focus:bg-white dark:focus:bg-dark-surface transition-all placeholder:text-slate-400"
                placeholder="Briefly describe the nature of work (e.g. 3BHK Full Interior, Painting & Flooring works)...">{{ old('work_description', $client->work_description ?? '') }}</textarea>
        </div>
    </div>
</div>

<!-- Section 2: Site Logistics & Intelligence -->
<div
    class="mb-8 bg-white dark:bg-dark-surface rounded-[2rem] border border-ui-border dark:border-dark-border shadow-premium overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500 delay-100">
    <div
        class="px-8 py-6 bg-slate-50/50 dark:bg-dark-bg/50 border-b border-ui-border dark:border-dark-border flex items-center justify-between">
        <h3
            class="text-xs font-bold uppercase tracking-[2px] text-slate-500 dark:text-dark-muted flex items-center gap-3">
            <span
                class="w-8 h-8 rounded-lg bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border flex items-center justify-center text-slate-400">02</span>
            Site Logistics & Intelligence
        </h3>
        <div class="flex gap-4">
            <label class="flex items-center gap-2 cursor-pointer group">
                <input type="checkbox" name="permission[work_permit]" value="1"
                    class="w-5 h-5 rounded-lg border-ui-border dark:border-dark-border text-brand-600 focus:ring-brand-500/20 transition-all"
                    {{ old("permission.work_permit", $client->permission?->work_permit ?? false) ? 'checked' : '' }}>
                <span
                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-brand-600 transition-colors">Work
                    Permit</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer group">
                <input type="checkbox" name="permission[gate_pass]" value="1"
                    class="w-5 h-5 rounded-lg border-ui-border dark:border-dark-border text-brand-600 focus:ring-brand-500/20 transition-all"
                    {{ old("permission.gate_pass", $client->permission?->gate_pass ?? false) ? 'checked' : '' }}>
                <span
                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-brand-600 transition-colors">Gate
                    Pass</span>
            </label>
        </div>
    </div>

    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="space-y-2">
            <label class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Project
                Authorized By</label>
            <input type="text" name="site_info[signed_by]"
                value="{{ old('site_info.signed_by', $client->siteInfo?->signed_by ?? '') }}"
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-brand-500/10 transition-all"
                placeholder="Manager or Owner Name">
        </div>
        <div class="space-y-2">
            <label class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Critical
                Site Facts</label>
            <input type="text" name="site_info[site_facts]"
                value="{{ old('site_info.site_facts', $client->siteInfo?->site_facts ?? '') }}"
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-brand-500/10 transition-all"
                placeholder="e.g. 10th Floor, No Lift Service, Tight Corridors">
        </div>
        <div class="md:col-span-2 space-y-2">
            <label class="text-[11px] font-bold uppercase tracking-widest text-ui-muted dark:text-dark-muted">Standard
                Operating Procedures (SOP)</label>
            <textarea name="site_info[working_instructions]" rows="3"
                class="w-full bg-slate-50 dark:bg-dark-bg border-transparent rounded-xl px-4 py-3 text-sm focus:ring-4 focus:ring-brand-500/10 transition-all"
                placeholder="Specific instructions for workers, timing restrictions, vendor entry rules...">{{ old('site_info.working_instructions', $client->siteInfo?->working_instructions ?? '') }}</textarea>
        </div>
    </div>
</div>

<!-- Section 3: Project Lifecycle Milestones -->
<div
    class="mb-8 bg-white dark:bg-dark-surface rounded-[2rem] border border-ui-border dark:border-dark-border shadow-premium overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500 delay-200">
    <div class="px-8 py-6 border-b border-ui-border dark:border-dark-border flex items-center justify-between">
        <h3 class="text-xs font-bold uppercase tracking-[2px] text-brand-600 flex items-center gap-3">
            <span
                class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600 font-black">03</span>
            Project Lifecycle Milestones
        </h3>
        <button type="button" onclick="addChecklistRow()"
            class="px-4 py-2 bg-slate-900 dark:bg-brand-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:scale-105 active:scale-95 transition-all shadow-lg shadow-brand-500/10">+
            Add Category</button>
    </div>
    <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="checklist-container">
            @forelse($client->checklistItems ?? [] as $index => $item)
            <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-dark-bg/30 rounded-2xl border border-ui-border dark:border-dark-border group"
                id="checklist-row-{{ $index }}">
                @if($item->id)<input type="hidden" name="checklist_items[{{ $index }}][id]"
                    value="{{ $item->id }}">@endif
                <div class="flex-1">
                    <input type="text" name="checklist_items[{{ $index }}][name]"
                        value="{{ old('checklist_items.' . $index . '.name', $item->name) }}"
                        class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-0 placeholder:text-slate-300"
                        placeholder="Milestone Name" required>
                </div>
                <div class="flex items-center gap-3 border-l border-ui-border dark:border-dark-border pl-4">
                    <input type="checkbox" name="checklist_items[{{ $index }}][is_checked]" value="1"
                        class="w-5 h-5 rounded-lg border-ui-border dark:border-dark-border text-emerald-500 focus:ring-emerald-500/20"
                        {{ old("checklist_items.$index.is_checked", $item->is_checked ?? false) ? 'checked' : '' }}>
                    <button type="button" onclick="document.getElementById('checklist-row-{{ $index }}').remove()"
                        class="text-rose-400 hover:text-rose-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            @empty
            @endforelse
        </div>
    </div>
</div>

<!-- Section 4: Executive Internal Logs -->
<div
    class="mb-8 bg-white dark:bg-dark-surface rounded-[2rem] border border-ui-border dark:border-dark-border shadow-premium overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500 delay-300">
    <div class="px-8 py-6 border-b border-ui-border dark:border-dark-border flex items-center justify-between">
        <h3 class="text-xs font-bold uppercase tracking-[2px] text-slate-500 flex items-center gap-3">
            <span
                class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-dark-bg border border-ui-border dark:border-dark-border flex items-center justify-center text-slate-400 font-bold">04</span>
            Historical Execution Logs
        </h3>
        <button type="button" onclick="addCommentRow()"
            class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-all">+
            Append Log</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr
                    class="bg-slate-50/50 dark:bg-dark-bg/50 text-[10px] font-black uppercase tracking-widest text-slate-400 border-b border-ui-border dark:border-dark-border">
                    <th class="py-4 px-8 w-40">Entry Date</th>
                    <th class="py-4 px-4 w-40">Module/Trade</th>
                    <th class="py-4 px-4 w-24 text-center">Inits</th>
                    <th class="py-4 px-4">Detailed Observation</th>
                    <th class="py-4 px-8 w-16"></th>
                </tr>
            </thead>
            <tbody id="comments-container" class="divide-y divide-ui-border dark:divide-dark-border">
                @forelse($client->comments ?? [] as $index => $comment)
                <tr class="group hover:bg-slate-50/50 dark:hover:bg-brand-500/5 transition-all">
                    <td class="py-4 px-8">
                        <input type="date" name="comments[{{$index}}][date]" value="{{ $comment->date }}"
                            class="w-full bg-slate-100 dark:bg-dark-bg border-none rounded-lg text-xs font-bold py-2 px-3 focus:ring-2 focus:ring-brand-500/10">
                    </td>
                    <td class="py-4 px-4">
                        <input type="text" name="comments[{{$index}}][work]" value="{{ $comment->work }}"
                            class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-xs font-bold py-2 px-3 focus:ring-2 focus:ring-brand-500/10">
                    </td>
                    <td class="py-4 px-4">
                        <input type="text" name="comments[{{$index}}][initials]" value="{{ $comment->initials }}"
                            class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-xs font-black py-2 px-3 text-center focus:ring-2 focus:ring-brand-500/10 uppercase"
                            placeholder="ABC">
                    </td>
                    <td class="py-4 px-4">
                        <input type="text" name="comments[{{$index}}][comment]" value="{{ $comment->comment }}"
                            class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-xs font-medium py-2 px-3 focus:ring-2 focus:ring-brand-500/10"
                            placeholder="Note down specifics...">
                    </td>
                    <td class="py-4 px-8 text-right">
                        <button type="button" onclick="this.closest('tr').remove()"
                            class="text-slate-300 hover:text-rose-500 transition-colors">&times;</button>
                        <input type="hidden" name="comments[{{$index}}][id]" value="{{ $comment->id }}">
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Section 5: Initial Capital Registry (Payments) -->
<div
    class="mb-8 bg-white dark:bg-dark-surface rounded-[2rem] border border-ui-border dark:border-dark-border shadow-premium overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500 delay-[400ms]">
    <div class="px-8 py-6 border-b border-ui-border dark:border-dark-border flex items-center justify-between">
        <h3 class="text-xs font-bold uppercase tracking-[2px] text-emerald-600 flex items-center gap-3">
            <span
                class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 font-black">05</span>
            Financial Capital Inflow/Outflow
        </h3>
        <button type="button" onclick="addPaymentRow()"
            class="px-4 py-2 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-600 transition-all">+
            Register Payment</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr
                    class="bg-slate-50/50 dark:bg-dark-bg/50 text-[10px] font-black uppercase tracking-widest text-slate-400 border-b border-ui-border dark:border-dark-border">
                    <th class="py-4 px-8">Client Name</th>
                    <th class="py-4 px-4">Role</th>
                    <th class="py-4 px-4">Transaction</th>
                    <th class="py-4 px-4 w-48">Amount (₹)</th>
                    <th class="py-4 px-4">Date</th>
                    <th class="py-4 px-8 w-16"></th>
                </tr>
            </thead>
            <tbody id="payments-container" class="divide-y divide-ui-border dark:divide-dark-border">
                @forelse($client->payments ?? [] as $index => $payment)
                <tr class="hover:bg-emerald-50/30 dark:hover:bg-emerald-500/5 transition-all">
                    <td class="py-4 px-8">
                        <input type="text" name="payments[{{$index}}][name]" value="{{ $payment->name }}"
                            class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-xs font-bold py-2 px-3 focus:ring-2 focus:ring-emerald-500/10">
                    </td>
                    <td class="py-4 px-4">
                        <input type="text" name="payments[{{$index}}][role]" value="{{ $payment->role }}"
                            class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-xs font-medium py-2 px-3 focus:ring-2 focus:ring-emerald-500/10">
                    </td>
                    <td class="py-4 px-4">
                        <select name="payments[{{$index}}][type]"
                            class="w-full bg-slate-100 dark:bg-dark-bg border-none rounded-lg text-xs font-black py-2 px-3 focus:ring-2 focus:ring-emerald-500/10">
                            <option value="Credit" {{ ($payment->type ?? 'Credit') == 'Credit' ? 'selected' : ''
                                }}>CREDIT
                                (+)</option>
                            <option value="Debit" {{ ($payment->type ?? '') == 'Debit' ? 'selected' : '' }}>DEBIT (-)
                            </option>
                        </select>
                    </td>
                    <td class="py-4 px-4">
                        <input type="number" step="0.01" name="payments[{{$index}}][amount]"
                            value="{{ $payment->amount }}"
                            class="w-full bg-emerald-50/50 dark:bg-emerald-500/5 border border-emerald-100 dark:border-emerald-500/20 rounded-lg text-sm font-black text-emerald-600 py-2 px-3 focus:ring-2 focus:ring-emerald-500/10">
                    </td>
                    <td class="py-4 px-4">
                        <input type="date" name="payments[{{$index}}][date]"
                            value="{{ $payment->date ? \Carbon\Carbon::parse($payment->date)->format('Y-m-d') : '' }}"
                            class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-xs font-bold py-2 px-3 focus:ring-2 focus:ring-emerald-500/10">
                    </td>
                    <td class="py-4 px-8 text-right">
                        <button type="button" onclick="this.closest('tr').remove()"
                            class="text-slate-300 hover:text-rose-500 transition-colors">&times;</button>
                        <input type="hidden" name="payments[{{$index}}][id]" value="{{ $payment->id }}">
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Section 6: Operational Units (Tasks) -->
<div
    class="mb-8 bg-white dark:bg-dark-surface rounded-[2rem] border border-ui-border dark:border-dark-border shadow-premium overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500 delay-[500ms]">
    <div class="px-8 py-6 border-b border-ui-border dark:border-dark-border flex items-center justify-between">
        <h3 class="text-xs font-bold uppercase tracking-[2px] text-brand-600 flex items-center gap-3">
            <span
                class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-600 font-black">06</span>
            Project Initialization Tasks
        </h3>
        <button type="button" onclick="addTaskRow()"
            class="px-4 py-2 bg-slate-900 dark:bg-brand-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:scale-105 transition-all">+
            Add Unit</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr
                    class="bg-slate-50/50 dark:bg-dark-bg/50 text-[10px] font-black uppercase tracking-widest text-slate-400 border-b border-ui-border dark:border-dark-border">
                    <th class="py-4 px-8">Resource Requirement / Task Description</th>
                    <th class="py-4 px-4 w-48">Stakeholder</th>
                    <th class="py-4 px-4 w-40">Timeline</th>
                    <th class="py-4 px-4 w-40">Priority</th>
                    <th class="py-4 px-8 w-16"></th>
                </tr>
            </thead>
            <tbody id="tasks-table-body" class="divide-y divide-ui-border dark:divide-dark-border">
                @forelse($client->tasks ?? [] as $index => $task)
                <tr class="group hover:bg-slate-50 dark:hover:bg-dark-bg/50 transition-all" id="task-row-{{ $index }}">
                    <td class="py-4 px-8">
                        <input type="hidden" name="tasks[{{ $index }}][id]" value="{{ $task->id }}">
                        <input type="text" name="tasks[{{ $index }}][description]"
                            value="{{ old('tasks.' . $index . '.description', $task->description) }}"
                            class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-xs font-bold py-2 px-3 focus:ring-2 focus:ring-brand-500/10"
                            placeholder="e.g. Dismantling Kitchen Cabinets" required>
                    </td>
                    <td class="py-4 px-4">
                        <input type="text" name="tasks[{{ $index }}][assigned_to]"
                            value="{{ old('tasks.' . $index . '.assigned_to', $task->assigned_to) }}"
                            class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-[11px] font-black py-2 px-3 focus:ring-2 focus:ring-brand-500/10 uppercase"
                            placeholder="Assigned To">
                    </td>
                    <td class="py-4 px-4">
                        <input type="date" name="tasks[{{ $index }}][deadline]"
                            value="{{ old('tasks.' . $index . '.deadline', $task->deadline ? $task->deadline->format('Y-m-d') : '') }}"
                            class="w-full bg-slate-100 dark:bg-dark-bg border-none rounded-lg text-[11px] font-bold py-2 px-3 focus:ring-2 focus:ring-brand-500/10">
                    </td>
                    <td class="py-4 px-4">
                        <select name="tasks[{{ $index }}][status]"
                            class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-[10px] font-black py-2 px-3 focus:ring-2 focus:ring-brand-500/10 uppercase">
                            <option value="Pending" {{ old("tasks.$index.status", $task->status) == 'Pending' ?
                                'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ old("tasks.$index.status", $task->status) == 'In Progress' ?
                                'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ old("tasks.$index.status", $task->status) == 'Completed' ?
                                'selected' : '' }}>Completed</option>
                        </select>
                    </td>
                    <td class="py-4 px-8 text-right">
                        <button type="button" onclick="document.getElementById('task-row-{{ $index }}').remove()"
                            class="text-slate-300 hover:text-rose-500 transition-colors">&times;</button>
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="flex justify-end pt-8 animate-in fade-in slide-in-from-bottom-4 duration-500 delay-[600ms]">
    <button type="submit"
        class="group relative bg-brand-600 hover:bg-brand-700 text-white px-12 py-5 rounded-[2rem] text-sm font-black uppercase tracking-[3px] shadow-2xl shadow-brand-500/30 transition-all hover:scale-[1.02] active:scale-95 flex items-center gap-4 overflow-hidden">
        <div
            class="absolute inset-0 bg-white/10 translate-y-20 group-hover:translate-y-0 transition-transform duration-500">
        </div>
        <span class="relative z-10">Commit Project Journey</span>
        <svg class="w-5 h-5 relative z-10 group-hover:translate-x-2 transition-transform duration-300" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 5l7 7-7 7M5 5l7 7-7 7">
            </path>
        </svg>
    </button>
</div>

<script>
    function addCommentRow() {
        const index = document.querySelectorAll('#comments-container tr').length + Date.now();
        const html = `
            <tr class="group hover:bg-slate-50/50 dark:hover:bg-brand-500/5 transition-all">
                <td class="py-4 px-8">
                    <input type="date" name="comments[${index}][date]" class="w-full bg-slate-100 dark:bg-dark-bg border-none rounded-lg text-xs font-bold py-2 px-3 focus:ring-2 focus:ring-brand-500/10">
                </td>
                <td class="py-4 px-4">
                    <input type="text" name="comments[${index}][work]" class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-xs font-bold py-2 px-3 focus:ring-2 focus:ring-brand-500/10" placeholder="e.g. Electrical">
                </td>
                <td class="py-4 px-4">
                    <input type="text" name="comments[${index}][initials]" class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-xs font-black py-2 px-3 text-center focus:ring-2 focus:ring-brand-500/10 uppercase" placeholder="ABC">
                </td>
                <td class="py-4 px-4">
                    <input type="text" name="comments[${index}][comment]" class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-xs font-medium py-2 px-3 focus:ring-2 focus:ring-brand-500/10" placeholder="Note down specifics...">
                </td>
                <td class="py-4 px-8 text-right">
                    <button type="button" onclick="this.closest('tr').remove()" class="text-slate-300 hover:text-rose-500 transition-colors">&times;</button>
                </td>
            </tr>`;
        document.getElementById('comments-container').insertAdjacentHTML('beforeend', html);
    }

    function addPaymentRow() {
        const index = document.querySelectorAll('#payments-container tr').length + Date.now();
        const html = `
            <tr class="hover:bg-emerald-50/30 dark:hover:bg-emerald-500/5 transition-all">
                <td class="py-4 px-8">
                    <input type="text" name="payments[${index}][name]" class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-xs font-bold py-2 px-3 focus:ring-2 focus:ring-emerald-500/10" placeholder="Payee/Sender">
                </td>
                <td class="py-4 px-4">
                    <input type="text" name="payments[${index}][role]" class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-xs font-medium py-2 px-3 focus:ring-2 focus:ring-emerald-500/10" placeholder="Role">
                </td>
                <td class="py-4 px-4">
                    <select name="payments[${index}][type]" class="w-full bg-slate-100 dark:bg-dark-bg border-none rounded-lg text-xs font-black py-2 px-3 focus:ring-2 focus:ring-emerald-500/10">
                        <option value="Credit">CREDIT (+)</option>
                        <option value="Debit">DEBIT (-)</option>
                    </select>
                </td>
                <td class="py-4 px-4">
                    <input type="number" step="0.01" name="payments[${index}][amount]" class="w-full bg-emerald-50/50 dark:bg-emerald-500/5 border border-emerald-100 dark:border-emerald-500/20 rounded-lg text-sm font-black text-emerald-600 py-2 px-3 focus:ring-2 focus:ring-emerald-500/10" placeholder="0.00">
                </td>
                <td class="py-4 px-4">
                    <input type="date" name="payments[${index}][date]" class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-xs font-bold py-2 px-3 focus:ring-2 focus:ring-emerald-500/10">
                </td>
                <td class="py-4 px-8 text-right">
                    <button type="button" onclick="this.closest('tr').remove()" class="text-slate-300 hover:text-rose-500 transition-colors">&times;</button>
                </td>
            </tr>`;
        document.getElementById('payments-container').insertAdjacentHTML('beforeend', html);
    }

    let checklistIndex = {{ count($client -> checklistItems ?? []) }} + 100;
    function addChecklistRow() {
        const html = `
            <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-dark-bg/30 rounded-2xl border border-ui-border dark:border-dark-border group animate-in zoom-in-95" id="checklist-row-${checklistIndex}">
                <div class="flex-1">
                    <input type="text" name="checklist_items[${checklistIndex}][name]"
                        class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-0 placeholder:text-slate-300"
                        placeholder="New Milestone" required>
                </div>
                <div class="flex items-center gap-3 border-l border-ui-border dark:border-dark-border pl-4">
                    <input type="checkbox" name="checklist_items[${checklistIndex}][is_checked]" value="1"
                        class="w-5 h-5 rounded-lg border-ui-border dark:border-dark-border text-emerald-500 focus:ring-emerald-500/20">
                    <button type="button" onclick="document.getElementById('checklist-row-${checklistIndex}').remove()"
                        class="text-rose-400 hover:text-rose-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>`;
        document.getElementById('checklist-container').insertAdjacentHTML('beforeend', html);
        checklistIndex++;
    }

    let taskIndex = {{ count($client -> tasks ?? []) }};
    function addTaskRow() {
        const html = `
            <tr class="group hover:bg-slate-50 dark:hover:bg-dark-bg/50 transition-all animate-in slide-in-from-right-4" id="task-row-${taskIndex}">
                <td class="py-4 px-8">
                    <input type="text" name="tasks[${taskIndex}][description]" class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-xs font-bold py-2 px-3 focus:ring-2 focus:ring-brand-500/10" placeholder="Task details" required>
                </td>
                <td class="py-4 px-4">
                     <input type="text" name="tasks[${taskIndex}][assigned_to]" class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-[11px] font-black py-2 px-3 focus:ring-2 focus:ring-brand-500/10 uppercase" placeholder="Name">
                </td>
                <td class="py-4 px-4">
                     <input type="date" name="tasks[${taskIndex}][deadline]" class="w-full bg-slate-100 dark:bg-dark-bg border-none rounded-lg text-[11px] font-bold py-2 px-3 focus:ring-2 focus:ring-brand-500/10">
                </td>
                <td class="py-4 px-4">
                    <select name="tasks[${taskIndex}][status]" class="w-full bg-white dark:bg-dark-surface border border-ui-border dark:border-dark-border rounded-lg text-[10px] font-black py-2 px-3 focus:ring-2 focus:ring-brand-500/10 uppercase">
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </td>
                <td class="py-4 px-8 text-right">
                    <button type="button" onclick="document.getElementById('task-row-${taskIndex}').remove()" class="text-slate-300 hover:text-rose-500 transition-colors">&times;</button>
                </td>
            </tr>`;
        document.getElementById('tasks-table-body').insertAdjacentHTML('beforeend', html);
        taskIndex++;
    }
</script>
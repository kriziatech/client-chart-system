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

<!-- Section 2: Work Checklist (Customizable) -->
<div class="mb-8 border border-gray-200 rounded-lg p-6 bg-white shadow-sm">
    <h3 class="text-lg font-semibold text-teal-700 mb-4 border-b pb-2 flex justify-between items-center">
        2. Work Checklist
        <button type="button" onclick="addChecklistRow()"
            class="text-sm bg-teal-100 text-teal-700 px-3 py-1 rounded hover:bg-teal-200 transition">+ Add Item</button>
    </h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="checklist-table">
            <thead>
                <tr class="border-b text-gray-600 uppercase text-xs">
                    <th class="px-2 py-2 text-left w-1/2">Item Name</th>
                    <th class="px-2 py-2 text-center w-24">Done?</th>
                    <th class="px-2 py-2 text-center w-20">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($client->checklistItems ?? [] as $index => $item)
                <tr class="border-b" id="checklist-row-{{ $index }}">
                    <td class="px-2 py-2">
                        @if($item->id)<input type="hidden" name="checklist_items[{{ $index }}][id]"
                            value="{{ $item->id }}">@endif
                        <input type="text" name="checklist_items[{{ $index }}][name]"
                            value="{{ old('checklist_items.' . $index . '.name', $item->name) }}"
                            class="w-full border-gray-300 rounded focus:ring-teal-500 focus:border-teal-500"
                            placeholder="e.g. Civil Work" required>
                    </td>
                    <td class="px-2 py-2 text-center">
                        <input type="checkbox" name="checklist_items[{{ $index }}][is_checked]" value="1"
                            class="form-checkbox h-5 w-5 text-teal-600 rounded" {{
                            old("checklist_items.$index.is_checked", $item->is_checked ?? false) ? 'checked' : '' }}>
                    </td>
                    <td class="px-2 py-2 text-center">
                        <button type="button" onclick="document.getElementById('checklist-row-{{ $index }}').remove()"
                            class="text-red-500 hover:text-red-700 text-xs font-bold">✕ Remove</button>
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Section 3: Site Information -->
<div class="mb-8 border border-gray-200 rounded-lg p-6 bg-white">
    <h3 class="text-lg font-semibold text-teal-700 mb-4 border-b pb-2">3. Site Information</h3>
    <div class="grid grid-cols-1 gap-6">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Signed By</label>
            <input type="text" name="site_info[signed_by]"
                value="{{ old('site_info.signed_by', $client->siteInfo->signed_by ?? '') }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Site Facts</label>
            <textarea name="site_info[site_facts]" rows="3"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('site_info.site_facts', $client->siteInfo->site_facts ?? '') }}</textarea>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Working Instructions</label>
            <textarea name="site_info[working_instructions]" rows="4"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('site_info.working_instructions', $client->siteInfo->working_instructions ?? '') }}</textarea>
        </div>
    </div>
</div>

<!-- Section 4: Permissions -->
<div class="mb-8 border border-gray-200 rounded-lg p-6 bg-white">
    <h3 class="text-lg font-semibold text-teal-700 mb-4 border-b pb-2">4. Permissions Needed</h3>
    <div class="flex gap-8">
        <label class="inline-flex items-center">
            <input type="checkbox" name="permission[work_permit]" value="1" class="form-checkbox h-5 w-5 text-teal-600"
                {{ old("permission.work_permit", $client->permission->work_permit ?? false) ? 'checked' : '' }}>
            <span class="ml-2 text-gray-700">Work Permit</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="permission[gate_pass]" value="1" class="form-checkbox h-5 w-5 text-teal-600" {{
                old("permission.gate_pass", $client->permission->gate_pass ?? false) ? 'checked' : '' }}>
            <span class="ml-2 text-gray-700">Gate Pass</span>
        </label>
    </div>
</div>

<!-- Section 5: Comments -->
<div class="mb-8 border border-gray-200 rounded-lg p-6 bg-white">
    <h3 class="text-lg font-semibold text-teal-700 mb-4 border-b pb-2 flex justify-between items-center">
        5. Comments
        <button type="button" onclick="addCommentRow()"
            class="text-sm bg-teal-100 text-teal-700 px-3 py-1 rounded hover:bg-teal-200">+ Add Row</button>
    </h3>
    <table class="w-full text-sm text-left">
        <thead>
            <tr class="bg-gray-50 text-gray-600">
                <th class="p-2">Date</th>
                <th class="p-2">Work</th>
                <th class="p-2">Initials</th>
                <th class="p-2 w-1/2">Comment</th>
                <th class="p-2"></th>
            </tr>
        </thead>
        <tbody id="comments-container">
            @forelse($client->comments ?? [] as $index => $comment)
            <tr>
                <td class="p-2"><input type="date" name="comments[{{$index}}][date]" value="{{ $comment->date }}"
                        class="border rounded w-full p-1"></td>
                <td class="p-2"><input type="text" name="comments[{{$index}}][work]" value="{{ $comment->work }}"
                        class="border rounded w-full p-1"></td>
                <td class="p-2"><input type="text" name="comments[{{$index}}][initials]"
                        value="{{ $comment->initials }}" class="border rounded w-full p-1"></td>
                <td class="p-2"><input type="text" name="comments[{{$index}}][comment]" value="{{ $comment->comment }}"
                        class="border rounded w-full p-1"></td>
                <td class="p-2 text-center">
                    <button type="button" onclick="this.closest('tr').remove()"
                        class="text-red-500 hover:text-red-700">&times;</button>
                    <input type="hidden" name="comments[{{$index}}][id]" value="{{ $comment->id }}">
                </td>
            </tr>
            @empty
            <!-- Initial Empty Row if new -->
            @endforelse
        </tbody>
    </table>
</div>

<!-- Section 6: Payments -->
<div class="mb-8 border border-gray-200 rounded-lg p-6 bg-white">
    <h3 class="text-lg font-semibold text-teal-700 mb-4 border-b pb-2 flex justify-between items-center">
        6. Payments
        <button type="button" onclick="addPaymentRow()"
            class="text-sm bg-teal-100 text-teal-700 px-3 py-1 rounded hover:bg-teal-200">+ Add Row</button>
    </h3>
    <table class="w-full text-sm text-left">
        <thead>
            <tr class="bg-gray-50 text-gray-600">
                <th class="p-2">Name</th>
                <th class="p-2">Role</th>
                <th class="p-2">Amount</th>
                <th class="p-2">Date</th>
                <th class="p-2 w-1/3">Purpose</th>
                <th class="p-2"></th>
            </tr>
        </thead>
        <tbody id="payments-container">
            @forelse($client->payments ?? [] as $index => $payment)
            <tr>
                <td class="p-2"><input type="text" name="payments[{{$index}}][name]" value="{{ $payment->name }}"
                        class="border rounded w-full p-1"></td>
                <td class="p-2"><input type="text" name="payments[{{$index}}][role]" value="{{ $payment->role }}"
                        class="border rounded w-full p-1"></td>
                <td class="p-2"><input type="number" step="0.01" name="payments[{{$index}}][amount]"
                        value="{{ $payment->amount }}" class="border rounded w-full p-1"></td>
                <td class="p-2"><input type="date" name="payments[{{$index}}][date]" value="{{ $payment->date }}"
                        class="border rounded w-full p-1"></td>
                <td class="p-2"><input type="text" name="payments[{{$index}}][purpose]" value="{{ $payment->purpose }}"
                        class="border rounded w-full p-1"></td>
                <td class="p-2 text-center">
                    <button type="button" onclick="this.closest('tr').remove()"
                        class="text-red-500 hover:text-red-700">&times;</button>
                    <input type="hidden" name="payments[{{$index}}][id]" value="{{ $payment->id }}">
                </td>
            </tr>
            @empty
            @endforelse
        </tbody>
    </table>
</div>

<!-- Section 7: Tasks & Timeline -->
<div class="mb-8 border border-gray-200 rounded-lg p-6 bg-white shadow-sm">
    <h3 class="text-lg font-semibold text-teal-700 mb-4 border-b pb-2 flex justify-between items-center">
        7. Tasks & Timeline
        <button type="button" onclick="addTaskRow()"
            class="text-sm bg-teal-100 text-teal-700 px-3 py-1 rounded hover:bg-teal-200 transaction">+ Add
            Task</button>
    </h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left" id="tasks-table">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-4 py-2 w-1/3">Task / Description</th>
                    <th class="px-4 py-2">Assigned To</th>
                    <th class="px-4 py-2">Deadline</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($client->tasks ?? [] as $index => $task)
                <tr class="border-b" id="task-row-{{ $index }}">
                    <td class="px-2 py-2">
                        <input type="hidden" name="tasks[{{ $index }}][id]" value="{{ $task->id }}">
                        <input type="text" name="tasks[{{ $index }}][description]"
                            value="{{ old('tasks.' . $index . '.description', $task->description) }}" class="w-full border-gray-300 rounded
                        focus:ring-teal-500 focus:border-teal-500" placeholder="Task details" required>
                    </td>
                    <td class="px-2 py-2">
                        <input type="text" name="tasks[{{ $index }}][assigned_to]"
                            value="{{ old('tasks.' . $index . '.assigned_to', $task->assigned_to) }}" class="w-full border-gray-300 rounded
                        focus:ring-teal-500 focus:border-teal-500" placeholder="Name">
                    </td>
                    <td class="px-2 py-2">
                        <input type="date" name="tasks[{{ $index }}][deadline]"
                            value="{{ old('tasks.' . $index . '.deadline', $task->deadline ? $task->deadline->format('Y-m-d') : '') }}"
                            class="w-full border-gray-300
                        rounded focus:ring-teal-500 focus:border-teal-500">
                    </td>
                    <td class="px-2 py-2">
                        <select name="tasks[{{ $index }}][status]"
                            class="w-full border-gray-300 rounded focus:ring-teal-500 focus:border-teal-500">
                            <option value="Pending" {{ old("tasks.$index.status", $task->status) == 'Pending' ?
                                'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ old("tasks.$index.status", $task->status) == 'In Progress' ?
                                'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ old("tasks.$index.status", $task->status) == 'Completed' ?
                                'selected' : '' }}>Completed</option>
                        </select>
                    </td>
                    <td class="px-2 py-2 text-center">
                        <button type="button" onclick="removeTaskRow({{ $index }})"
                            class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="flex justify-end mt-8">
    <button type="submit"
        class="bg-teal-700 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-teal-800 transition shadow-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        Save Client Project
    </button>
</div>

<script>
    function addCommentRow() {
        const index = document.querySelectorAll('#comments-container tr').length + Date.now();
        const html = `
            <tr>
                <td class="p-2"><input type="date" name="comments[${index}][date]" class="border rounded w-full p-1"></td>
                <td class="p-2"><input type="text" name="comments[${index}][work]" class="border rounded w-full p-1"></td>
                <td class="p-2"><input type="text" name="comments[${index}][initials]" class="border rounded w-full p-1"></td>
                <td class="p-2"><input type="text" name="comments[${index}][comment]" class="border rounded w-full p-1"></td>
                <td class="p-2 text-center"><button type="button" onclick="this.closest('tr').remove()" class="text-red-500 hover:text-red-700">&times;</button></td>
            </tr>`;
        document.getElementById('comments-container').insertAdjacentHTML('beforeend', html);
    }

    function addPaymentRow() {
        const index = document.querySelectorAll('#payments-container tr').length + Date.now();
        const html = `
            <tr>
                <td class="p-2"><input type="text" name="payments[${index}][name]" class="border rounded w-full p-1"></td>
                <td class="p-2"><input type="text" name="payments[${index}][role]" class="border rounded w-full p-1"></td>
                <td class="p-2"><input type="number" step="0.01" name="payments[${index}][amount]" class="border rounded w-full p-1"></td>
                <td class="p-2"><input type="date" name="payments[${index}][date]" class="border rounded w-full p-1"></td>
                <td class="p-2"><input type="text" name="payments[${index}][purpose]" class="border rounded w-full p-1"></td>
                <td class="p-2 text-center"><button type="button" onclick="this.closest('tr').remove()" class="text-red-500 hover:text-red-700">&times;</button></td>
            </tr>`;
        document.getElementById('payments-container').insertAdjacentHTML('beforeend', html);
    }

    let checklistIndex = {{ count($client -> checklistItems ?? []) }} + 100;
    function addChecklistRow() {
        const table = document.getElementById('checklist-table').getElementsByTagName('tbody')[0];
        const row = table.insertRow();
        row.id = `checklist-row-${checklistIndex}`;
        row.className = "border-b";
        row.innerHTML = `
            <td class="px-2 py-2">
                <input type="text" name="checklist_items[${checklistIndex}][name]"
                    class="w-full border-gray-300 rounded focus:ring-teal-500 focus:border-teal-500"
                    placeholder="e.g. Plumbing" required>
            </td>
            <td class="px-2 py-2 text-center">
                <input type="checkbox" name="checklist_items[${checklistIndex}][is_checked]" value="1"
                    class="form-checkbox h-5 w-5 text-teal-600 rounded">
            </td>
            <td class="px-2 py-2 text-center">
                <button type="button" onclick="document.getElementById('checklist-row-${checklistIndex}').remove()"
                    class="text-red-500 hover:text-red-700 text-xs font-bold">✕ Remove</button>
            </td>
        `;
        checklistIndex++;
    }

    function removeTaskRow(index) {
        document.getElementById(`task-row-${index}`).remove();
    }

    let taskIndex = {{ count($client -> tasks ?? []) }};
    function addTaskRow() {
        const table = document.getElementById('tasks-table').getElementsByTagName('tbody')[0];
        const row = table.insertRow();
        row.id = `task-row-${taskIndex}`;
        row.className = "border-b";
        row.innerHTML = `
            <td class="px-2 py-2">
                <input type="text" name="tasks[${taskIndex}][description]" class="w-full border-gray-300 rounded focus:ring-teal-500 focus:border-teal-500" placeholder="Task details" required>
            </td>
            <td class="px-2 py-2">
                 <input type="text" name="tasks[${taskIndex}][assigned_to]" class="w-full border-gray-300 rounded focus:ring-teal-500 focus:border-teal-500" placeholder="Name">
            </td>
            <td class="px-2 py-2">
                 <input type="date" name="tasks[${taskIndex}][deadline]" class="w-full border-gray-300 rounded focus:ring-teal-500 focus:border-teal-500">
            </td>
            <td class="px-2 py-2">
                <select name="tasks[${taskIndex}][status]" class="w-full border-gray-300 rounded focus:ring-teal-500 focus:border-teal-500">
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
            </td>
            <td class="px-2 py-2 text-center">
                <button type="button" onclick="removeTaskRow(${taskIndex})" class="text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </td>
        `;
        taskIndex++;
    }
</script>
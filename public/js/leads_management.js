window.leadManagement = function () {
    return {
        leads: [], searchQuery: '', selectedLead: null, modalOpen: false, editMode: false, viewMode: 'kanban',
        isOnline: navigator.onLine, isSyncing: false, hasPendingChanges: false, stages: ['New', 'Contacted', 'Qualified', 'Lost', 'Converted'],
        form: { offline_uuid: '', name: '', phone: '', status: 'New', temperature: 'Warm', score: 50, metadata: { notes: '' }, next_follow_up_at: null },
        init() {
            const data = localStorage.getItem('it_leads_v5');
            if (data) { this.leads = JSON.parse(data); } else {
                this.leads = window.initialLeads.map(l => ({ ...l, offline_uuid: l.offline_uuid || self.crypto.randomUUID(), sync_pending: false, temperature: l.temperature || 'Warm', score: l.score || 0 }));
                this.saveToDisk();
            }
            window.addEventListener('online', () => { this.isOnline = true; this.syncWithServer(); });
            window.addEventListener('offline', () => { this.isOnline = false; });
            this.checkPendingChanges();
            setInterval(() => { if (this.isOnline && this.hasPendingChanges && !this.isSyncing) this.syncWithServer(); }, 5000);
        },
        get filteredLeads() { return this.leads.filter(l => l.name.toLowerCase().includes(this.searchQuery.toLowerCase())); },
        saveToDisk() { localStorage.setItem('it_leads_v5', JSON.stringify(this.leads)); this.checkPendingChanges(); },
        checkPendingChanges() { this.hasPendingChanges = this.leads.some(l => l.sync_pending); },
        selectLead(lead) { this.selectedLead = lead; },
        openCreateModal() {
            this.editMode = false;
            this.form = { offline_uuid: self.crypto.randomUUID(), name: '', phone: '', status: 'New', temperature: 'Warm', score: 50, metadata: { notes: '' }, next_follow_up_at: null, sync_pending: true };
            this.modalOpen = true;
        },
        editLead(lead) { this.editMode = true; this.form = JSON.parse(JSON.stringify(lead)); this.modalOpen = true; },
        saveLead() {
            if (this.editMode) {
                const idx = this.leads.findIndex(l => l.offline_uuid === this.form.offline_uuid);
                this.leads[idx] = { ...this.form, sync_pending: true };
                if (this.selectedLead?.offline_uuid === this.form.offline_uuid) this.selectedLead = this.leads[idx];
            } else { this.calculateScore(this.form); this.leads.unshift({ ...this.form, sync_pending: true }); }
            this.saveToDisk(); this.modalOpen = false; if (this.isOnline) this.syncWithServer();
        },
        saveLeadChanges() { if (this.selectedLead) { this.selectedLead.sync_pending = true; this.saveToDisk(); if (this.isOnline) this.syncWithServer(); } },
        onDragStart(lead, e) { e.dataTransfer.setData('lead_id', lead.offline_uuid); e.dataTransfer.effectAllowed = 'move'; },
        moveLead(targetStage, e) {
            const id = e.dataTransfer.getData('lead_id'); const lead = this.leads.find(l => l.offline_uuid === id);
            if (lead && lead.status !== targetStage) { lead.status = targetStage; lead.sync_pending = true; this.saveToDisk(); if (this.isOnline) this.syncWithServer(); }
        },
        calculateScore(l) { let s = 20; if (l.phone) s += 20; if (l.work_description?.length > 30) s += 30; if (l.temperature === 'Hot') s += 30; l.score = Math.min(s, 100); },
        getTemperatureColor(t) { return t === 'Hot' ? 'text-rose-500' : (t === 'Warm' ? 'text-amber-500' : 'text-sky-500'); },
        getScoreProgressColor(s) { return s >= 80 ? 'bg-emerald-500' : (s >= 50 ? 'bg-amber-500' : 'bg-slate-400'); },
        getStageColor(s) { const c = { 'New': 'bg-brand-500', 'Contacted': 'bg-amber-500', 'Qualified': 'bg-emerald-500', 'Lost': 'bg-slate-400', 'Converted': 'bg-purple-500' }; return c[s] || 'bg-slate-300'; },
        getStatusBadgeClass(s) { return { 'bg-brand-500 text-white': s === 'New', 'bg-amber-500 text-white': s === 'Contacted', 'bg-emerald-500 text-white': s === 'Qualified', 'bg-slate-400 text-white': s === 'Lost', 'bg-purple-500 text-white': s === 'Converted' }; },
        async syncWithServer() {
            if (this.isSyncing) return;
            const pending = this.leads.filter(l => l.sync_pending); if (pending.length === 0) return; this.isSyncing = true;
            try {
                const res = await fetch("/leads/sync", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ leads: pending })
                });
                const data = await res.json();
                if (data.success) {
                    this.leads = this.leads.map(l => { if (data.synced_uuids.includes(l.offline_uuid)) return { ...l, sync_pending: false }; return l; });
                    this.saveToDisk();
                }
            } catch (e) { console.error('Sync failed:', e); } finally { this.isSyncing = false; }
        }
    };
}

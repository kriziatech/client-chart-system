# Software UI Redesign: Journey-Based Project Management

## Objective
Design and structure the UI to follow a one-clear-journey model. Every screen clearly indicates the current stage and next step.

## UI Rules
- **One screen = one main action.**
- **Journey Visibility:** Show stage and next step prominently.
- **Color Coding:** 
  - Blue: Work running
  - Green: Complete
  - Red: Problem/Warning
- **Dynamic Access:** Unlock tabs/features as the journey progresses.

---

## 🏗️ Implementation Phase 1: Global Journey Shell
- [ ] Update Sidebar to reflect the journey flow (Lead → Pipeline → Quotations → Workspaces → Tasks → Team → Reports).
- [ ] Create a `JourneyHeader` component to display "Stage" and "Next Step" across all pages.

## 🏗️ Implementation Phase 2: Lead & Pipeline Refinement
- [ ] **Lead Screen:** 
  - List/Board view.
  - Actions: Call, WhatsApp, Add Note, Set Follow-up.
  - Integration with "Next Step" logic.
- [ ] **Pipeline Screen:**
  - Board view (New → Visit → Quote → Approved/Lost).
  - Site photo upload (already partly exists in gallery).
  - Inactivity warnings (red alerts for > 7 days).

## 🏗️ Implementation Phase 3: Estimate & Quotation
- [ ] **Quotation View:**
  - Standard item list with clear totals.
  - Approval flow (Sign & Accept).
  - **CTA:** Show "Create Project" button only when status is 'Approved'.

## 🏗️ Implementation Phase 4: Project / Workspace Hub
- [ ] **Top Section:** Redesign the project header with a progress bar and status.
- [ ] **Tab System:**
  - Overview
  - Tasks
  - Chat
  - Files
  - Inventory
  - Attendance
- [ ] **Dynamic Tabs:** Hide/Lock tabs until status moves from 'Sales' to 'Work in Progress'.

## 🏗️ Implementation Phase 5: Executional Modules
- [ ] **Task Management:**
  - Group by Trade (Design, Civil, Carpentry, Electrical).
  - Prioritize "Today's Tasks".
  - Color-code late tasks in red.
- [ ] **Team Chat:**
  - Refine UI to be WhatsApp-like.
  - Add task linking (Reference task ID in messages).
- [ ] **Inventory:**
  - Low stock warnings.
  - Project-specific usage view.

## 🏗️ Implementation Phase 6: Closure & Reports
- [ ] **Handover Checklist:**
  - Ensure "Mark Completed" button is only enabled when all items are checked.
- [ ] **Reports:**
  - Budget vs Spend card.
  - Audit log list.

---

## 🚦 Color Map
| Component | Blue (Running) | Green (Complete) | Red (Problem) |
| :--- | :--- | :--- | :--- |
| **Project** | Work In Progress | Completed | Overdue / At Risk |
| **Tasks** | In Progress | Done | Overdue |
| **Inventory** | Normal | — | Low Stock |
| **Leads** | Active | Won | Lost / Inactive |

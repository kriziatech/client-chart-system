<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PresentationSlide;

class PresentationSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PresentationSlide::truncate();

        $slides = [
            [
                'title' => '<span class="gradient-text">Interior Touch</span>',
                'subtitle' => 'Bespoke Enterprise Systems',
                'content' => '<h3 class="animate-up">Advanced Project Orchestration</h3><div class="animate-up" style="margin-top: 30px; animation-delay: 0.5s;"><p style="font-size: 0.45em; font-weight: 800; opacity: 0.6; letter-spacing: 0.3em; text-transform: uppercase;">Engineered By <span class="highlight">Krizia Technologies</span> @ 2026</p></div>',
                'layout_type' => 'center',
                'bg_color' => '#0F172A',
                'order' => 1,
            ],
            [
                'title' => 'Indresh Singh',
                'subtitle' => 'Architect & SME',
                'content' => '<p class="animate-up" style="font-size: 0.55em; color: #94A3B8; margin-bottom: 15px;">Cloud Solution Architect | 7+ Years IT Experience<br>Proven Expertise in 100+ Enterprise Transformations</p><ul class="bullet-list"><li class="fragment fade-right">Design Lead for Interior Touch Core Execution Engine.</li><li class="fragment fade-right">Architecture optimized for Data Consistency & Integrity.</li><li class="fragment fade-right">Integrated Cloud Security & Rapid Recovery frameworks.</li></ul>',
                'layout_type' => 'profile',
                'bg_color' => '#0F172A',
                'order' => 2,
            ],
            [
                'title' => 'Journey: <span class="highlight">Lead ➔ Handover</span>',
                'subtitle' => null,
                'content' => '<div class="card animate-up" style="border-top: 4px solid #8B5CF6;"><div style="display: flex; flex-direction: column; gap: 12px;"><div class="fragment fade-up" style="display: flex; align-items: center;"><span class="step-pill">01</span><p style="font-size: 0.5em; color: #fff;"><strong>Sales Victory:</strong> Lead converted from Pipeline ➔ Won status.</p></div><div class="fragment fade-up" style="display: flex; align-items: center;"><span class="step-pill">02</span><p style="font-size: 0.5em; color: #fff;"><strong>Auto-Provisioning:</strong> System initializes Client Dossier & Workspace.</p></div><div class="fragment fade-up" style="display: flex; align-items: center;"><span class="step-pill">03</span><p style="font-size: 0.5em; color: #fff;"><strong>Resource Mapping:</strong> Site-admins & materials are allocated instantly.</p></div><div class="fragment fade-up" style="display: flex; align-items: center;"><span class="step-pill">04</span><p style="font-size: 0.5em; color: #fff;"><strong>Site Kick-off:</strong> Visual Timeline activated for professional delivery.</p></div></div></div>',
                'layout_type' => 'standard',
                'bg_color' => '#0F172A',
                'order' => 3,
            ],
            [
                'title' => 'Visual <span class="highlight">Timeline</span> (Gantt)',
                'subtitle' => null,
                'content' => '<div class="grid-2"><div class="card fragment zoom-in" style="margin: 0;"><h3 style="font-size: 0.85em !important; color: #4F70FA;">Multi-Project Mastery</h3><p style="font-size: 0.4em; color: #CBD5E1;">View all active projects on a single synchronized plane. Identify labor bottlenecks and resource overlaps before they occur.</p></div><div class="card fragment zoom-in" style="margin: 0;"><h3 style="font-size: 0.85em !important; color: #10B981;">Real-time Scaling</h3><p style="font-size: 0.4em; color: #CBD5E1;">Switch perspectives from Monthly milestones to Granular Daily tasks. High-performance drag-and-drop orchestration.</p></div></div><div class="card fragment fade-up" style="text-align: center; border: 1px dashed #F43F5E; margin-top: 20px;"><p style="font-size: 0.45em;"><span class="danger font-black animate-pulse">ALERT:</span> Automatic red-flagging of overdue dependencies across site reports.</p></div>',
                'layout_type' => 'standard',
                'bg_color' => '#0F172A',
                'order' => 4,
            ],
            [
                'title' => 'Deep <span class="success">Financial Analysis</span>',
                'subtitle' => null,
                'content' => '<div class="grid-3">
                    <div class="card fragment fade-up">
                        <div class="card-accent"><span></span><span></span><span></span></div>
                        <svg class="icon-2d" viewBox="0 0 24 24"><path d="M3 3v18h18M7 17l4-4 4 4 5-5"/></svg>
                        <h3 class="success" style="font-size: 0.7em !important;">P&L Tracking</h3>
                        <p style="font-size: 0.35em; color: #CBD5E1;">Real-time Profit & Loss analysis per project. Track material vs labor costs with precision.</p>
                    </div>
                    <div class="card fragment fade-up" style="animation-delay: 0.2s;">
                        <div class="card-accent"><span></span><span></span><span></span></div>
                        <svg class="icon-2d" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 class="highlight" style="font-size: 0.7em !important;">Vendor Audit</h3>
                        <p style="font-size: 0.35em; color: #CBD5E1;">Mode-of-payment verification (UPI/Chq/Cash) with mandatory receipt archives.</p>
                    </div>
                    <div class="card fragment fade-up" style="animation-delay: 0.4s;">
                        <div class="card-accent"><span></span><span></span><span></span></div>
                        <svg class="icon-2d" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <h3 style="color: #10B981; font-size: 0.7em !important;">Safety Lock</h3>
                        <p style="font-size: 0.35em; color: #CBD5E1;">Integrated "Finance Lock" mechanism prevents budget tampering after approval.</p>
                    </div>
                </div>',
                'layout_type' => 'standard',
                'bg_color' => '#0F172A',
                'order' => 5,
            ],
            [
                'title' => 'Security: <span class="warning">Backup & Restore</span>',
                'subtitle' => null,
                'content' => '<div class="grid-2"><div class="card fragment fade-up"><div class="icon-box animate-float">☁️</div><h3>Cloud Backups</h3><p style="font-size: 0.4em; color: #CBD5E1;">Daily automated snapshots of all site data secured in encrypted off-site vaults.</p></div><div class="card fragment fade-up"><div class="icon-box animate-float">🔄</div><h3>1-Click Recovery</h3><p style="font-size: 0.4em; color: #CBD5E1;">Instant system restoration in case of emergencies. Zero data loss guarantee for critical assets.</p></div></div><div class="card fragment fade-in" style="background: rgba(245, 158, 11, 0.08); margin-top: 20px;"><p style="font-size: 0.45em; color: #fff; text-align: center;"><strong>Resilience:</strong> Enterprise-grade CRC verification for every restore point.</p></div>',
                'layout_type' => 'standard',
                'bg_color' => '#0F172A',
                'order' => 6,
            ],
            [
                'title' => 'Field <span class="highlight">Photo Journal</span>',
                'subtitle' => 'Eliminating site disputes through Mandatory Visual Tracking.',
                'content' => '<div class="grid-2"><div class="card fragment fade-right" style="text-align: center; border-color: rgba(255,255,255,0.1);"><p style="font-size: 0.4em; color: #64748B;">[MANDATORY]</p><h4 style="font-size: 0.7em; margin: 8px 0;">BEFORE PHOTO</h4><div style="height: 80px; background: rgba(255,255,255,0.02); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.45em;">Morning Status</div></div><div class="card fragment fade-left" style="text-align: center; border-color: #10B981;"><p style="font-size: 0.4em; color: #10B981;">[MANDATORY]</p><h4 style="font-size: 0.7em; margin: 8px 0;">AFTER PHOTO</h4><div style="height: 80px; background: rgba(16, 185, 129, 0.05); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.45em;">Work Done (EOD)</div></div></div><p class="fragment fade-up" style="font-size: 0.45em; margin-top: 15px; color: #CBD5E1;">Reports are <strong>REJECTED</strong> if photo metadata doesn\'t match geofence.</p>',
                'layout_type' => 'standard',
                'bg_color' => '#0F172A',
                'order' => 7,
            ],
            [
                'title' => 'System <span class="warning">Efficiency</span>',
                'subtitle' => null,
                'content' => '<div class="card fragment fade-right"><h3 style="color: #F59E0B; font-size: 0.85em !important;">Team Geofencing</h3><p style="font-size: 0.45em; color: #CBD5E1;">GPS-tagged attendance eliminates proxy logins and calculates precise labor costs.</p></div><div class="card fragment fade-left" style="margin-top: 15px;"><h3 style="color: #8B5CF6; font-size: 0.85em !important;">Smart Inventory</h3><p style="font-size: 0.45em; color: #CBD5E1;">Track material inward with visual proof and auto-generate PDF Work Orders (WO).</p></div>',
                'layout_type' => 'standard',
                'bg_color' => '#0F172A',
                'order' => 8,
            ],
            [
                'title' => 'FUTURE READY <span class="gradient-text">EXECUTION</span>',
                'subtitle' => null,
                'content' => '<div style="display: flex; justify-content: space-around; margin-top: 40px;"><div class="fragment zoom-in" style="text-align: center;"><h2 style="font-size: 1.1em !important; color: #10B981;">40%</h2><p style="font-size: 0.35em; font-weight: 800;">Optimization</p></div><div class="fragment zoom-in" style="text-align: center;"><h2 style="font-size: 1.1em !important; color: #4F70FA;">100%</h2><p style="font-size: 0.35em; font-weight: 800;">Visibility</p></div><div class="fragment zoom-in" style="text-align: center;"><h2 style="font-size: 1.1em !important; color: #F43F5E;">ZERO</h2><p style="font-size: 0.35em; font-weight: 800;">Disputes</p></div></div><div class="animate-up" style="margin-top: 50px; animation-delay: 1s;"><p style="font-size: 0.45em; opacity: 0.7;">Engineered by <span class="highlight font-black">Indresh Singh</span></p><p style="font-size: 0.35em; letter-spacing: 0.4em; margin-top: 8px;">KRIZIA TECHNOLOGIES</p></div>',
                'layout_type' => 'center',
                'bg_color' => '#0F172A',
                'order' => 9,
            ],
            [
                'title' => 'Get In Touch',
                'subtitle' => 'Contact Us',
                'content' => '<div class="card animate-up" style="text-align: center; padding: 40px;"><div class="fragment fade-up" style="margin-bottom: 25px;"><p style="font-size: 0.5em; color: #94A3B8; text-transform: uppercase; font-weight: 800; letter-spacing: 2px;">Contact Us</p><h3 style="font-size: 1.4em !important; color: #fff; margin: 5px 0;">+91-8376097938</h3></div><div class="fragment fade-up" style="margin-bottom: 25px;"><p style="font-size: 0.5em; color: #94A3B8; text-transform: uppercase; font-weight: 800; letter-spacing: 2px;">Mail for Enquiries</p><h3 style="font-size: 1.1em !important; color: #4F70FA; margin: 5px 0;">Contact@Krizia.in</h3></div><div class="fragment fade-in" style="margin-top: 35px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 25px;"><p style="font-size: 0.4em; color: #64748B; margin-bottom: 12px;">Visit our website for more services</p><a href="https://krizia.in" target="_blank" class="animate-pulse" style="font-family: \'Outfit\'; font-weight: 800; font-size: 1.4em; color: #fff; text-decoration: none; letter-spacing: 3px;">KRIZIA.IN</a></div></div>',
                'layout_type' => 'standard',
                'bg_color' => '#0F172A',
                'order' => 10,
            ],
        ];

        foreach ($slides as $slide) {
            PresentationSlide::create($slide);
        }
    }
}
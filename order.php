<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Will Banwalo - Create Your Will</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-tertiary-container": "#f7f3e8", "error": "#93000a", "surface-container-low": "#fbf9f2",
                        "on-primary": "#f7f3e8", "outline-variant": "#0b2016", "surface-tint": "#0b2016",
                        "surface-container": "#efe7d1", "on-background": "#0b2016", "surface-container-highest": "#efe7d1",
                        "surface-container-lowest": "#ffffff", "surface": "#f7f3e8", "on-primary-container": "#e3c766",
                        "outline": "#0b2016", "on-tertiary-fixed": "#f7f3e8", "on-error": "#ffffff", "surface-dim": "#efe7d1",
                        "secondary-container": "#f3e9c9", "tertiary-fixed": "#e3c9c2", "tertiary": "#7a3b2e",
                        "on-surface-variant": "#0b2016", "on-secondary-fixed-variant": "#0b2016", "on-error-container": "#93000a",
                        "on-secondary-container": "#0b2016", "surface-bright": "#fbf9f2", "background": "#f7f3e8",
                        "surface-variant": "#efe7d1", "primary-fixed-dim": "#122c1f", "on-primary-fixed-variant": "#e3c766",
                        "secondary": "#c9a227", "secondary-fixed-dim": "#e3c766", "primary-fixed": "#122c1f",
                        "inverse-primary": "#c9a227", "on-tertiary-fixed-variant": "#f7f3e8", "tertiary-container": "#7a3b2e",
                        "inverse-on-surface": "#f7f3e8", "surface-container-high": "#efe7d1", "secondary-fixed": "#f3e9c9",
                        "primary-container": "#122c1f", "on-secondary": "#0b2016", "on-primary-fixed": "#f7f3e8",
                        "on-surface": "#0b2016", "on-secondary-fixed": "#0b2016", "on-tertiary": "#ffffff", "primary": "#0b2016",
                        "error-container": "#f3ddd8", "tertiary-fixed-dim": "#e3c9c2", "inverse-surface": "#0b2016"
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "2xl": "1.5rem", "full": "9999px" },
                    spacing: {
                        "margin-desktop": "40px", "gutter": "24px", "margin-mobile": "16px",
                        "container-max-width": "1200px", "section-gap": "80px", "unit": "8"
                    },
                    fontFamily: {
                        "headline-lg": ["Fraunces"], "body-lg": ["Plus Jakarta Sans"], "label-md": ["Plus Jakarta Sans"],
                        "headline-lg-mobile": ["Fraunces"], "headline-xl": ["Fraunces"],
                        "headline-md": ["Fraunces"], "label-sm": ["Plus Jakarta Sans"], "body-md": ["Plus Jakarta Sans"]
                    },
                    fontSize: {
                        "headline-lg": ["32px", { "lineHeight": "40px", "fontWeight": "700" }],
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                        "label-md": ["14px", { "lineHeight": "20px", "letterSpacing": "0.02em", "fontWeight": "600" }],
                        "headline-lg-mobile": ["28px", { "lineHeight": "36px", "fontWeight": "700" }],
                        "headline-xl": ["48px", { "lineHeight": "60px", "fontWeight": "700" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                        "label-sm": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "700" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }]
                    }
                }
            }
        }
    </script>
    <style>
        * { cursor: none; }
        input, textarea, select { cursor: auto !important; }
        @media (max-width: 900px) { * { cursor: auto; } }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }

        .card-active {
            border-color: #0b2016 !important;
            background-color: #fbf9f2 !important;
            box-shadow: 0 4px 12px rgba(11, 32, 22, 0.08);
        }

        .step-panel {
            display: none;
        }

        .step-panel.active {
            display: block;
        }

        .form-floating-label {
            transition: all 0.2s ease-in-out;
            pointer-events: none;
        }

        .input-peer:focus~.form-floating-label,
        .input-peer:not(:placeholder-shown)~.form-floating-label {
            transform: translateY(-24px) scale(0.85);
            color: #0b2016;
            background-color: white;
            padding: 0 4px;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c9a227;
            border-radius: 10px;
        }

        .accordion-content {
            height: 0;
            overflow: hidden;
        }

        .drag-over {
            border-color: #0b2016 !important;
            background-color: rgba(201, 162, 39, 0.12) !important;
        }

        /* ---------- custom cursor ---------- */
        .cur-dot, .cur-ring { position: fixed; top: 0; left: 0; pointer-events: none; z-index: 9999; border-radius: 50%; transform: translate(-50%, -50%); }
        .cur-dot { width: 6px; height: 6px; background: #c9a227; }
        .cur-ring { width: 34px; height: 34px; border: 1px solid rgba(201,162,39,0.55); transition: width .25s ease, height .25s ease, border-color .25s ease, background .25s ease; }
        .cur-ring.grow { width: 66px; height: 66px; background: rgba(201,162,39,0.08); border-color: rgba(201,162,39,0.9); }
        @media (max-width: 900px) { .cur-dot, .cur-ring { display: none; } }

        .mobile-panel { height: 0; overflow: hidden; }

        /* Sidebar item base — actual step-state colors are now set as
           inline styles directly by updateSidebar() in JS, since the
           Tailwind class-toggle approach for those states proved
           unreliable (classes added post-load weren't always getting
           CSS generated for them). This is just a resting-state default. */
        .sidebar-item { transition: background-color .3s ease, color .3s ease; }

        /* Same dynamic-class issue affects a few other JS-driven states
           (selected funeral preference, selected plan card, valid form
           fields) — hardcoded here for the same reason as above. */
        .funeral-btn.border-primary { border-color: #0b2016 !important; }
        .funeral-btn.text-primary { color: #0b2016 !important; }
        .funeral-btn.bg-secondary-container\/20 { background-color: rgba(243, 233, 201, 0.5) !important; }
        .plan-card.ring-2.ring-primary { box-shadow: 0 0 0 2px #0b2016 !important; }
        input.border-primary, select.border-primary, textarea.border-primary { border-color: #0b2016 !important; }
    </style>
</head>

<body class="bg-surface font-body-md text-on-surface overflow-x-hidden">

    <div class="cur-ring" id="curRing"></div>
    <div class="cur-dot" id="curDot"></div>

    <!-- ===== HEADER — same links as original ===== -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/70 backdrop-blur-xl border-b border-outline-variant/10">
        <nav class="max-w-container-max-width mx-auto px-margin-desktop h-20 md:h-24 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="font-headline-md text-primary text-2xl font-bold tracking-tight">Will Banwalo</div>
            </div>
            <div class="hidden lg:flex items-center gap-10">
                <a class="text-primary font-bold text-sm" href="./index.html">Home</a>
                <a class="text-primary font-bold text-sm" href="./how_it_works.html">How it Works</a>
                <a class="text-primary font-bold text-sm" href="./pricing.html">Pricing</a>
                <a class="text-primary font-bold text-sm" href="./faq.html">FAQs</a>
                <a class="text-primary font-bold text-sm" href="./track_order.html">Track order</a>
                <a class="text-primary font-bold text-sm" href="./contact_us.html">Contact Us</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="./order.php"><button
                        class="bg-primary text-on-primary px-6 md:px-8 py-3 rounded-full font-label-md text-sm">Create
                        My Will</button></a>
                <button id="menuBtn" class="lg:hidden text-primary w-10 h-10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">menu</span>
                </button>
            </div>
        </nav>
        <div class="mobile-panel bg-surface lg:hidden" id="mobilePanel">
            <div class="flex flex-col px-6 py-6 gap-5 text-primary font-semibold">
                <a href="./index.html">Home</a>
                <a href="./how_it_works.html">How it Works</a>
                <a href="./pricing.html">Pricing</a>
                <a href="./faq.html">FAQs</a>
                <a href="./track_order.html">Track order</a>
                <a href="./contact_us.html">Contact Us</a>
            </div>
        </div>
    </header>

    <!-- ===== MAIN WRAPPER ===== -->
    <div class="flex min-h-screen pt-24">

        <!-- ===== SIDEBAR ===== -->
        <aside
            class="hidden lg:flex flex-col p-6 gap-2 h-screen w-80 sticky top-24 left-0 bg-surface-container-low border-r border-outline-variant overflow-y-auto custom-scrollbar shrink-0">
            <div class="mb-6 px-4">
                <h2 class="font-headline-md text-headline-md text-primary">Will Progress</h2>
                <p id="sidebar-step-label" class="font-label-md text-on-surface-variant">Step 1 of 8</p>
                <div class="w-full bg-outline-variant h-1.5 mt-3 rounded-full overflow-hidden">
                    <div id="progress-bar" class="bg-primary h-full transition-all duration-500" style="width:12.5%">
                    </div>
                </div>
                
            </div>
            <nav class="flex flex-col gap-1" id="sidebar-nav">
                <div class="sidebar-item active flex items-center gap-4 px-4 py-3 rounded-xl transition-all cursor-pointer"
                    data-step="1" onclick="goToStep(1)">
                    <span class="material-symbols-outlined">check_circle</span><span class="font-label-md">Will
                        Type</span>
                </div>
                <div class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-xl transition-all cursor-pointer"
                    data-step="2" onclick="goToStep(2)">
                    <span class="material-symbols-outlined">person</span><span class="font-label-md">Personal
                        Details</span>
                </div>
                <div class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-xl transition-all cursor-pointer"
                    data-step="3" onclick="goToStep(3)">
                    <span class="material-symbols-outlined">group</span><span class="font-label-md">Beneficiaries</span>
                </div>
                <div class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-xl transition-all cursor-pointer"
                    data-step="4" onclick="goToStep(4)">
                    <span class="material-symbols-outlined">account_balance</span><span class="font-label-md">Asset
                        Portfolio</span>
                </div>
                <div class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-xl transition-all cursor-pointer"
                    data-step="5" onclick="goToStep(5)">
                    <span class="material-symbols-outlined">gavel</span><span class="font-label-md">Special
                        Instructions</span>
                </div>
                <div class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-xl transition-all cursor-pointer"
                    data-step="6" onclick="goToStep(6)">
                    <span class="material-symbols-outlined">upload_file</span><span class="font-label-md">Document
                        Upload</span>
                </div>
                <div class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-xl transition-all cursor-pointer"
                    data-step="7" onclick="goToStep(7)">
                    <span class="material-symbols-outlined">rate_review</span><span class="font-label-md">Review &amp;
                        Payment</span>
                </div>
                <div class="sidebar-item flex items-center gap-4 px-4 py-3 rounded-xl transition-all cursor-pointer"
                    data-step="8" onclick="goToStep(8)">
                    <span class="material-symbols-outlined">task_alt</span><span
                        class="font-label-md">Confirmation</span>
                </div>
            </nav>
            <div class="mt-auto p-4 bg-secondary-container/30 rounded-2xl border border-outline-variant/30 text-center">
                <p class="font-label-sm text-label-sm text-on-secondary-container mb-3 uppercase tracking-wider">Expert
                    Guidance</p>
                <button
                    class="w-full bg-primary text-on-primary font-bold py-3 px-4 rounded-xl shadow-sm hover:opacity-90 transition-all">Get
                    Legal Help</button>
            </div>
        </aside>

        <!-- ===== MAIN CONTENT ===== -->
        <main class="flex-1 px-4 md:px-margin-desktop py-12 pb-36 min-w-0">
            <div class="max-w-4xl mx-auto">

                <!-- ========== STEP 1: WILL TYPE ========== -->
                <div class="step-panel active" id="panel-1">
                    <div class="mb-10">
                        <span
                            class="inline-flex items-center gap-2 text-tertiary-container font-label-sm uppercase tracking-widest mb-4">
                            <span class="material-symbols-outlined text-[16px]">verified_user</span>SECURE LEGAL
                            PLANNING
                        </span>
                        <h1 class="font-headline-xl text-headline-xl text-primary mb-4 leading-tight">Select your Will
                            type</h1>
                        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">Tailor your legacy plan
                            to your unique family structure and financial goals. Most users complete this step in under
                            15 minutes.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10" id="will-type-grid">
                        <div class="will-card card-active group relative bg-white p-8 rounded-2xl shadow-sm border-2 border-outline-variant/20 hover:border-primary/50 cursor-pointer transition-all duration-300"
                            onclick="selectWillCard(this,'Individual Will')">
                            <div
                                class="absolute top-4 right-4 bg-primary-container text-on-primary-container px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase">
                                Recommended</div>
                            <div
                                class="mb-6 h-14 w-14 bg-secondary-container flex items-center justify-center rounded-xl">
                                <span class="material-symbols-outlined text-primary text-3xl">person</span></div>
                            <h3 class="font-headline-md text-headline-md text-primary mb-2">Individual Will</h3>
                            <p class="font-body-md text-body-md text-on-surface-variant mb-6">A comprehensive legal
                                document outlining asset distribution for a single individual.</p>
                            <div class="flex items-center gap-2 text-on-surface-variant opacity-60"><span
                                    class="material-symbols-outlined text-sm">schedule</span><span
                                    class="font-label-sm text-label-sm">Est. 15 mins</span></div>
                        </div>
                        <div class="will-card group relative bg-white p-8 rounded-2xl shadow-sm border-2 border-outline-variant/20 hover:border-primary/50 cursor-pointer transition-all duration-300"
                            onclick="selectWillCard(this,'Joint Will')">
                            <div
                                class="mb-6 h-14 w-14 bg-surface-container flex items-center justify-center rounded-xl group-hover:bg-secondary-container transition-colors">
                                <span class="material-symbols-outlined text-primary text-3xl">favorite</span></div>
                            <h3 class="font-headline-md text-headline-md text-primary mb-2">Joint Will</h3>
                            <p class="font-body-md text-body-md text-on-surface-variant mb-6">Designed for married
                                couples or partners wanting to coordinate their legacies together.</p>
                            <div class="flex items-center gap-2 text-on-surface-variant opacity-60"><span
                                    class="material-symbols-outlined text-sm">schedule</span><span
                                    class="font-label-sm text-label-sm">Est. 25 mins</span></div>
                        </div>
                        <div class="will-card group relative bg-white p-8 rounded-2xl shadow-sm border-2 border-outline-variant/20 hover:border-primary/50 cursor-pointer transition-all duration-300"
                            onclick="selectWillCard(this,'Senior Citizen Will')">
                            <div
                                class="mb-6 h-14 w-14 bg-surface-container flex items-center justify-center rounded-xl group-hover:bg-secondary-container transition-colors">
                                <span class="material-symbols-outlined text-primary text-3xl">elderly</span></div>
                            <h3 class="font-headline-md text-headline-md text-primary mb-2">Senior Citizen Will</h3>
                            <p class="font-body-md text-body-md text-on-surface-variant mb-6">Simplified process with
                                enhanced support for elderly individuals and medical directives.</p>
                            <div class="flex items-center gap-2 text-on-surface-variant opacity-60"><span
                                    class="material-symbols-outlined text-sm">schedule</span><span
                                    class="font-label-sm text-label-sm">Est. 20 mins</span></div>
                        </div>
                        <div class="will-card group relative bg-white p-8 rounded-2xl shadow-sm border-2 border-outline-variant/20 hover:border-primary/50 cursor-pointer transition-all duration-300"
                            onclick="selectWillCard(this,'Property Will')">
                            <div
                                class="mb-6 h-14 w-14 bg-surface-container flex items-center justify-center rounded-xl group-hover:bg-secondary-container transition-colors">
                                <span class="material-symbols-outlined text-primary text-3xl">domain</span></div>
                            <h3 class="font-headline-md text-headline-md text-primary mb-2">Property Will</h3>
                            <p class="font-body-md text-body-md text-on-surface-variant mb-6">Focuses specifically on
                                complex real estate holdings across multiple jurisdictions.</p>
                            <div class="flex items-center gap-2 text-on-surface-variant opacity-60"><span
                                    class="material-symbols-outlined text-sm">schedule</span><span
                                    class="font-label-sm text-label-sm">Est. 20 mins</span></div>
                        </div>
                        <div class="will-card md:col-span-2 group relative bg-white p-8 rounded-2xl shadow-sm border-2 border-outline-variant/20 hover:border-primary/50 cursor-pointer transition-all duration-300"
                            onclick="selectWillCard(this,'Business Asset Will')">
                            <div class="flex flex-col md:flex-row md:items-center gap-8">
                                <div
                                    class="h-14 w-14 bg-surface-container flex shrink-0 items-center justify-center rounded-xl group-hover:bg-secondary-container transition-colors">
                                    <span class="material-symbols-outlined text-primary text-3xl">work</span></div>
                                <div class="flex-1">
                                    <h3 class="font-headline-md text-headline-md text-primary mb-2">Business Asset Will
                                    </h3>
                                    <p class="font-body-md text-body-md text-on-surface-variant">Strategic planning for
                                        business owners, covering succession, shares, and intellectual property
                                        protection.</p>
                                </div>
                                <div class="flex items-center gap-2 text-on-surface-variant opacity-60 shrink-0"><span
                                        class="material-symbols-outlined text-sm">schedule</span><span
                                        class="font-label-sm text-label-sm">Est. 30 mins</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center py-6 border-t border-outline-variant/30">
                        <a href="./index.html"><button
                                class="px-8 py-3 text-on-surface-variant font-bold rounded-xl border border-outline hover:bg-surface-container-high transition-all">Cancel</button></a>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2 px-4 py-2 bg-surface-container-high rounded-full">
                                <span class="material-symbols-outlined text-primary text-[18px]"
                                    style="font-variation-settings:'FILL' 1;">lock</span>
                                <span class="font-label-sm text-label-sm text-primary uppercase">Encrypted &amp;
                                    Secure</span>
                            </div>
                            <button onclick="nextStep()"
                                class="bg-primary text-on-primary font-bold py-4 px-12 rounded-xl shadow-lg hover:opacity-90 active:scale-95 transition-all text-body-lg">Continue</button>
                        </div>
                    </div>
                </div>

                <!-- ========== STEP 2: PERSONAL DETAILS ========== -->
                <div class="step-panel" id="panel-2">
                    <div class="mb-10">
                        <span
                            class="inline-flex items-center gap-2 text-tertiary-container font-label-sm uppercase tracking-widest mb-4">
                            <span class="material-symbols-outlined text-[16px]">person</span>STEP 2 OF 8
                        </span>
                        <h1 class="font-headline-xl text-headline-xl text-primary mb-4 leading-tight">Personal Details
                        </h1>
                        <p id="personal-will-type-note"
                            class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">Please provide your
                            personal and legal identification details.</p>
                    </div>
                    <div id="personal-details-form">
                        <!-- Individual / Senior / Property / Business forms -->
                        <div id="form-individual">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                <div class="relative"><input
                                        class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all text-on-surface font-body-md"
                                        id="full-name" placeholder=" " type="text" required /><label
                                        class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                        for="full-name">Full Legal Name</label></div>
                                <div class="relative"><input
                                        class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all text-on-surface font-body-md"
                                        id="dob" placeholder=" " type="date" required /><label
                                        class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                        for="dob">Date of Birth</label></div>
                                <div class="relative"><select
                                        class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all text-on-surface font-body-md"
                                        id="gender">
                                        <option value="">Gender</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                        <option>Other</option>
                                    </select></div>
                                <div class="relative"><input
                                        class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all text-on-surface font-body-md"
                                        id="mobile" maxlength="10" placeholder=" " type="tel" required /><label
                                        class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                        for="mobile">Mobile Number</label></div>
                                <div class="relative"><input
                                        class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all text-on-surface font-body-md"
                                        id="email" placeholder=" " type="email" required /><label
                                        class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                        for="email">Email Address</label></div>
                                <div class="relative"><input
                                        class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all text-on-surface font-body-md"
                                        id="religion" placeholder=" " type="text" /><label
                                        class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                        for="religion">Religion</label></div>
                                <div class="relative"><input
                                        class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all text-on-surface font-body-md"
                                        id="nationality" placeholder=" " type="text" /><label
                                        class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                        for="nationality">Nationality</label></div>
                                <div class="relative"><input
                                        class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all text-on-surface font-body-md"
                                        id="marital" placeholder=" " type="text" /><label
                                        class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                        for="marital">Marital Status</label></div>
                                <div class="md:col-span-2 pt-4">
                                    <h3 class="font-label-md text-primary-fixed-dim uppercase tracking-wider">Identity
                                        Documents</h3>
                                </div>
                                <div class="relative"><input
                                        class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all text-on-surface font-body-md"
                                        id="aadhaar" maxlength="12" placeholder=" " type="text" required /><label
                                        class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                        for="aadhaar">Aadhaar Number</label></div>
                                <div class="relative"><input
                                        class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all text-on-surface font-body-md"
                                        id="pan" maxlength="10" placeholder=" " type="text" required /><label
                                        class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                        for="pan">PAN Card Number</label></div>
                                <div class="md:col-span-2 pt-4">
                                    <h3 class="font-label-md text-primary-fixed-dim uppercase tracking-wider">
                                        Residential Address</h3>
                                </div>
                                <div class="relative md:col-span-2"><input
                                        class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all text-on-surface font-body-md"
                                        id="address" placeholder=" " type="text" required /><label
                                        class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                        for="address">House No, Building, Street Name</label></div>
                                <div class="relative"><input
                                        class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all text-on-surface font-body-md"
                                        id="city" placeholder=" " type="text" required /><label
                                        class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                        for="city">City</label></div>
                                <div class="relative"><input
                                        class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all text-on-surface font-body-md"
                                        id="state" placeholder=" " type="text" required /><label
                                        class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                        for="state">State</label></div>
                                <div class="relative"><input
                                        class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all text-on-surface font-body-md"
                                        id="pin" maxlength="6" placeholder=" " type="text" required /><label
                                        class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                        for="pin">PIN Code</label></div>
                            </div>
                        </div>
                        <!-- Extra fields for Joint Will -->
                        <div id="form-joint" class="hidden">
                            <div class="p-6 bg-secondary-container/20 rounded-2xl border border-secondary/20 mb-8">
                                <h3 class="font-headline-md text-primary mb-4">Spouse / Partner Details</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="relative"><input
                                            class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all"
                                            id="spouse-name" placeholder=" " type="text" /><label
                                            class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                            for="spouse-name">Spouse Full Name</label></div>
                                    <div class="relative"><input
                                            class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all"
                                            id="spouse-dob" placeholder=" " type="date" /><label
                                            class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                            for="spouse-dob">Spouse Date of Birth</label></div>
                                    <div class="relative"><input
                                            class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all"
                                            id="spouse-aadhaar" maxlength="12" placeholder=" " type="text" /><label
                                            class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                            for="spouse-aadhaar">Spouse Aadhaar Number</label></div>
                                    <div class="relative"><input
                                            class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all"
                                            id="spouse-pan" maxlength="10" placeholder=" " type="text" /><label
                                            class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                            for="spouse-pan">Spouse PAN Number</label></div>
                                    <div class="relative"><input
                                            class="input-peer w-full h-14 bg-transparent border-b-2 border-outline-variant focus:border-primary focus:ring-0 px-0 outline-none transition-all"
                                            id="marriage-date" placeholder=" " type="date" /><label
                                            class="form-floating-label absolute left-0 top-4 text-on-surface-variant font-label-md"
                                            for="marriage-date">Marriage Date</label></div>
                                </div>
                            </div>
                        </div>
                        <!-- Extra fields for Senior Citizen -->
                        <div id="form-senior" class="hidden">
                            <div class="p-6 bg-tertiary-fixed/30 rounded-2xl border border-tertiary/20 mb-8">
                                <h3 class="font-headline-md text-primary mb-4">Medical &amp; Care Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2"><label class="font-label-md text-on-surface-variant">Primary
                                            Doctor / Caregiver Name</label><input
                                            class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                            type="text" placeholder="Dr. Name or Caregiver" /></div>
                                    <div class="space-y-2"><label class="font-label-md text-on-surface-variant">Doctor
                                            Contact</label><input
                                            class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                            type="tel" placeholder="+91 XXXXX XXXXX" /></div>
                                    <div class="md:col-span-2 space-y-2"><label
                                            class="font-label-md text-on-surface-variant">Special Medical Directives
                                            (optional)</label><textarea
                                            class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none resize-none h-24"
                                            placeholder="e.g. Do Not Resuscitate, organ donation preferences..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Extra fields for Business Asset -->
                        <div id="form-business" class="hidden">
                            <div class="p-6 bg-primary-container/10 rounded-2xl border border-primary/20 mb-8">
                                <h3 class="font-headline-md text-primary mb-4">Business Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2"><label class="font-label-md text-on-surface-variant">Business
                                            Name</label><input
                                            class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                            type="text" placeholder="Legal entity name" /></div>
                                    <div class="space-y-2"><label class="font-label-md text-on-surface-variant">Business
                                            Type</label><select
                                            class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none">
                                            <option>Sole Proprietorship</option>
                                            <option>Partnership</option>
                                            <option>Private Limited</option>
                                            <option>LLP</option>
                                            <option>HUF</option>
                                        </select></div>
                                    <div class="space-y-2"><label class="font-label-md text-on-surface-variant">GST /
                                            CIN Number</label><input
                                            class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                            type="text" placeholder="Registration number" /></div>
                                    <div class="space-y-2"><label class="font-label-md text-on-surface-variant">% Share
                                            Owned</label><input
                                            class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                            type="number" placeholder="0" min="0" max="100" /></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="mt-8 p-6 bg-surface-container flex items-start gap-4 rounded-xl border-l-4 border-primary">
                        <span class="material-symbols-outlined text-primary">verified_user</span>
                        <div>
                            <p class="font-label-md text-primary font-bold">Secure Information</p>
                            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Your data is encrypted
                                using banking-grade security protocols. We do not share your personal identification
                                numbers with third parties.</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mt-10">
                        <button onclick="prevStep()"
                            class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-all px-6 py-3 rounded-xl border border-outline-variant"><span
                                class="material-symbols-outlined">arrow_back</span>Back</button>
                        <button onclick="nextStep()"
                            class="bg-primary text-on-primary px-12 py-3 rounded-xl font-label-md shadow-lg hover:opacity-90 transition-all flex items-center gap-2">Continue
                            to Step 3<span class="material-symbols-outlined">arrow_forward</span></button>
                    </div>
                </div>

                <!-- ========== STEP 3: BENEFICIARIES ========== -->
                <div class="step-panel" id="panel-3">
                    <div class="mb-10">
                        <span
                            class="inline-flex items-center gap-2 text-tertiary-container font-label-sm uppercase tracking-widest mb-4"><span
                                class="material-symbols-outlined text-[16px]">group</span>STEP 3 OF 8</span>
                        <h1 class="font-headline-xl text-headline-xl text-primary mb-4 leading-tight">Beneficiaries
                            &amp; Executor</h1>
                        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">Add the people who will
                            inherit your estate. Ensure the total allocation equals 100%.</p>
                    </div>
                    <section class="mb-10">
                        <h2 class="font-headline-md text-headline-md text-primary mb-6">Beneficiaries</h2>
                        <div id="beneficiary-list" class="space-y-6">
                            <div
                                class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 shadow-sm">
                                <div class="flex justify-between items-center mb-6">
                                    <h4 class="font-label-md text-primary uppercase tracking-wider">Beneficiary #1</h4>
                                    <button class="text-error text-label-sm hover:underline remove-beneficiary"
                                        style="display:none">Remove</button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2"><label class="font-label-md text-on-surface-variant">Full
                                            Name</label><input
                                            class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                            type="text" placeholder="Legal full name" /></div>
                                    <div class="space-y-2"><label
                                            class="font-label-md text-on-surface-variant">Relationship</label><select
                                            class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none">
                                            <option>Spouse</option>
                                            <option>Son</option>
                                            <option>Daughter</option>
                                            <option>Father</option>
                                            <option>Mother</option>
                                            <option>Brother</option>
                                            <option>Sister</option>
                                            <option>Other</option>
                                        </select></div>
                                    <div class="space-y-2"><label class="font-label-md text-on-surface-variant">Date of
                                            Birth</label><input
                                            class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                            type="date" /></div>
                                    <div class="space-y-2"><label class="font-label-md text-on-surface-variant">% Share
                                            of Estate</label>
                                        <div class="relative"><input
                                                class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                                type="number" placeholder="0" min="0" max="100" /><span
                                                class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant font-bold">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="add-beneficiary-btn"
                            class="w-full mt-6 flex items-center justify-center gap-2 py-6 border-2 border-dashed border-outline-variant rounded-2xl text-primary font-label-md hover:bg-primary/5 transition-colors group">
                            <span
                                class="material-symbols-outlined group-hover:scale-110 transition-transform">add_circle</span>Add
                            Another Beneficiary
                        </button>
                    </section>
                    <section class="mb-10">
                        <div class="flex items-center gap-3 mb-6">
                            <h2 class="font-headline-md text-headline-md text-primary">Executor Information</h2>
                            <div class="group relative"><span
                                    class="material-symbols-outlined text-outline cursor-help text-sm">info</span>
                                <div
                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-64 p-3 bg-inverse-surface text-inverse-on-surface text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-xl">
                                    The Executor is responsible for managing your estate and distributing assets as per
                                    the Will.</div>
                            </div>
                        </div>
                        <div class="bg-secondary-container/20 p-8 rounded-2xl border border-secondary/20">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                                <div class="space-y-2"><label class="font-label-md text-on-surface-variant">Executor's
                                        Full Name</label><input
                                        class="w-full bg-surface-container-lowest border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                        placeholder="Typically a trusted lawyer or relative" type="text" /></div>
                                <div class="space-y-2"><label class="font-label-md text-on-surface-variant">Phone
                                        Number</label><input
                                        class="w-full bg-surface-container-lowest border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                        placeholder="+91 00000 00000" type="tel" /></div>
                            </div>
                            <div class="mt-4 flex items-start gap-3 p-4 bg-surface-container-lowest/50 rounded-xl"><span
                                    class="material-symbols-outlined text-secondary"
                                    style="font-variation-settings:'FILL' 1;">security</span>
                                <p class="text-xs text-on-surface-variant leading-relaxed">Choose someone you trust
                                    implicitly. It's advisable to inform them beforehand that they are being named as an
                                    executor.</p>
                            </div>
                        </div>
                    </section>
                    <div class="flex justify-between items-center mt-10">
                        <button onclick="prevStep()"
                            class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-all px-6 py-3 rounded-xl border border-outline-variant"><span
                                class="material-symbols-outlined">arrow_back</span>Back</button>
                        <button onclick="nextStep()"
                            class="bg-primary text-on-primary px-12 py-3 rounded-xl font-label-md shadow-lg hover:opacity-90 transition-all flex items-center gap-2">Save
                            &amp; Continue<span class="material-symbols-outlined">arrow_forward</span></button>
                    </div>
                </div>

                <!-- ========== STEP 4: ASSET PORTFOLIO ========== -->
                <div class="step-panel" id="panel-4">
                    <div class="mb-10">
                        <span
                            class="inline-flex items-center gap-2 text-tertiary-container font-label-sm uppercase tracking-widest mb-4"><span
                                class="material-symbols-outlined text-[16px]">account_balance</span>STEP 4 OF 8</span>
                        <h1 class="font-headline-xl text-headline-xl text-primary mb-4 leading-tight">Asset Portfolio
                        </h1>
                        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">Declare all assets to be
                            included in your Will. Click on an asset category to add details.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                        <!-- Real Estate -->
                        <div
                            class="group relative bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden transition-all hover:shadow-md hover:-translate-y-1 p-8">
                            <div class="flex justify-between items-start mb-6">
                                <div class="p-4 bg-secondary-container rounded-2xl"><span
                                        class="material-symbols-outlined text-[32px]">home</span></div><span
                                    class="bg-surface-container text-on-surface-variant px-3 py-1 rounded-full text-[12px] font-bold"
                                    id="re-badge">Empty</span>
                            </div>
                            <h4 class="font-headline-md text-on-background mb-2">Real Estate</h4>
                            <p class="text-on-surface-variant text-sm mb-6">Residential, commercial, or agricultural
                                land.</p>
                            <button onclick="toggleAssetForm('re-form')"
                                class="w-full border-2 border-dashed border-outline-variant text-outline hover:border-primary hover:text-primary py-3 rounded-xl transition-all font-label-md">Add
                                Property</button>
                            <div id="re-form" class="hidden mt-6 space-y-4">
                                <input
                                    class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                    type="text" placeholder="Property name / description" />
                                <input
                                    class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                    type="text" placeholder="Survey/Registration number" />
                                <input
                                    class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                    type="text" placeholder="Estimated value (₹)" />
                                <button onclick="markAssetAdded('re-badge','re-form')"
                                    class="w-full bg-primary text-on-primary py-2 rounded-xl font-label-md">Save
                                    Property</button>
                            </div>
                        </div>
                        <!-- Bank Accounts -->
                        <div
                            class="group relative bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden transition-all hover:shadow-md hover:-translate-y-1 p-8">
                            <div class="flex justify-between items-start mb-6">
                                <div class="p-4 bg-primary-container/20 rounded-2xl"><span
                                        class="material-symbols-outlined text-[32px]">savings</span></div><span
                                    class="bg-surface-container text-on-surface-variant px-3 py-1 rounded-full text-[12px] font-bold"
                                    id="bank-badge">Empty</span>
                            </div>
                            <h4 class="font-headline-md text-on-background mb-2">Bank Accounts</h4>
                            <p class="text-on-surface-variant text-sm mb-6">Savings, current, FDs, and deposits.</p>
                            <button onclick="toggleAssetForm('bank-form')"
                                class="w-full border-2 border-dashed border-outline-variant text-outline hover:border-primary hover:text-primary py-3 rounded-xl transition-all font-label-md">Add
                                Account</button>
                            <div id="bank-form" class="hidden mt-6 space-y-4">
                                <input
                                    class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                    type="text" placeholder="Bank name" />
                                <input
                                    class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                    type="text" placeholder="Account number (last 4 digits)" />
                                <input
                                    class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                    type="text" placeholder="Approximate balance (₹)" />
                                <button onclick="markAssetAdded('bank-badge','bank-form')"
                                    class="w-full bg-primary text-on-primary py-2 rounded-xl font-label-md">Save
                                    Account</button>
                            </div>
                        </div>
                        <!-- Investments -->
                        <div
                            class="group relative bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden transition-all hover:shadow-md hover:-translate-y-1 p-8">
                            <div class="flex justify-between items-start mb-6">
                                <div class="p-4 bg-secondary-container rounded-2xl"><span
                                        class="material-symbols-outlined text-[32px]">trending_up</span></div><span
                                    class="bg-surface-container text-on-surface-variant px-3 py-1 rounded-full text-[12px] font-bold"
                                    id="inv-badge">Empty</span>
                            </div>
                            <h4 class="font-headline-md text-on-background mb-2">Investments</h4>
                            <p class="text-on-surface-variant text-sm mb-6">Stocks, mutual funds, bonds, and demat
                                holdings.</p>
                            <button onclick="toggleAssetForm('inv-form')"
                                class="w-full border-2 border-dashed border-outline-variant text-outline hover:border-primary hover:text-primary py-3 rounded-xl transition-all font-label-md">Add
                                Investment</button>
                            <div id="inv-form" class="hidden mt-6 space-y-4">
                                <select
                                    class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none">
                                    <option>Stocks / Shares</option>
                                    <option>Mutual Funds</option>
                                    <option>Bonds / Debentures</option>
                                    <option>PPF / NPS</option>
                                    <option>Insurance Policies</option>
                                </select>
                                <input
                                    class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                    type="text" placeholder="Folio / DP / Account number" />
                                <input
                                    class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                    type="text" placeholder="Estimated value (₹)" />
                                <button onclick="markAssetAdded('inv-badge','inv-form')"
                                    class="w-full bg-primary text-on-primary py-2 rounded-xl font-label-md">Save
                                    Investment</button>
                            </div>
                        </div>
                        <!-- Digital Assets -->
                        <div
                            class="group relative bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden transition-all hover:shadow-md hover:-translate-y-1 p-8">
                            <div class="flex justify-between items-start mb-6">
                                <div class="p-4 bg-tertiary-container text-on-tertiary-container rounded-2xl"><span
                                        class="material-symbols-outlined text-[32px]">token</span></div><span
                                    class="bg-surface-container text-on-surface-variant px-3 py-1 rounded-full text-[12px] font-bold"
                                    id="dig-badge">Empty</span>
                            </div>
                            <h4 class="font-headline-md text-on-background mb-2">Digital Assets</h4>
                            <p class="text-on-surface-variant text-sm mb-6">Crypto, NFTs, and social accounts.</p>
                            <button onclick="toggleAssetForm('dig-form')"
                                class="w-full border-2 border-dashed border-outline-variant text-outline hover:border-primary hover:text-primary py-3 rounded-xl transition-all font-label-md">Add
                                Digital Asset</button>
                            <div id="dig-form" class="hidden mt-6 space-y-4">
                                <input
                                    class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                    type="text" placeholder="Asset name (e.g. Bitcoin wallet)" />
                                <input
                                    class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                    type="text" placeholder="Wallet / account identifier" />
                                <input
                                    class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                    type="text" placeholder="Estimated value (₹)" />
                                <button onclick="markAssetAdded('dig-badge','dig-form')"
                                    class="w-full bg-primary text-on-primary py-2 rounded-xl font-label-md">Save
                                    Asset</button>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-secondary-container/20 rounded-2xl p-6 flex items-start gap-4 border border-secondary-container mb-8">
                        <span class="material-symbols-outlined text-primary"
                            style="font-variation-settings:'FILL' 1;">security</span>
                        <div>
                            <p class="font-label-md text-on-secondary-container mb-1">End-to-End Encrypted</p>
                            <p class="text-xs text-on-secondary-container/80">Your asset details are encrypted with
                                bank-grade security and only accessible to you and your nominated executors.</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <button onclick="prevStep()"
                            class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-all px-6 py-3 rounded-xl border border-outline-variant"><span
                                class="material-symbols-outlined">arrow_back</span>Back</button>
                        <button onclick="nextStep()"
                            class="bg-primary text-on-primary px-12 py-3 rounded-xl font-label-md shadow-lg hover:opacity-90 transition-all flex items-center gap-2">Next:
                            Instructions<span class="material-symbols-outlined">arrow_forward</span></button>
                    </div>
                </div>

                <!-- ========== STEP 5: SPECIAL INSTRUCTIONS ========== -->
                <div class="step-panel" id="panel-5">
                    <div class="mb-10">
                        <span
                            class="inline-flex items-center gap-2 text-tertiary-container font-label-sm uppercase tracking-widest mb-4"><span
                                class="material-symbols-outlined text-[16px]">gavel</span>STEP 5 OF 8</span>
                        <h1 class="font-headline-xl text-headline-xl text-primary mb-4 leading-tight">Distribution &amp;
                            Special Instructions</h1>
                        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">Add specific conditions,
                            guardian appointments, funeral wishes, and charitable donations.</p>
                    </div>
                    <!-- Guardian Section -->
                    <section class="mb-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container">
                                <span class="material-symbols-outlined">supervisor_account</span></div>
                            <h2 class="font-headline-md text-headline-md text-primary">Guardian Appointment</h2>
                        </div>
                        <div
                            class="bg-surface-container-lowest p-8 rounded-2xl shadow-sm border border-outline-variant/30">
                            <p class="text-on-surface-variant mb-6 text-sm">If you have minor children, appoint a
                                trusted guardian to care for them.</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2"><label class="font-label-md text-on-surface-variant">Guardian's
                                        Full Name</label><input
                                        class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                        type="text" placeholder="Legal full name" /></div>
                                <div class="space-y-2"><label class="font-label-md text-on-surface-variant">Relationship
                                        to Children</label><input
                                        class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                        type="text" placeholder="e.g. Uncle, Aunt, Grandparent" /></div>
                                <div class="space-y-2"><label class="font-label-md text-on-surface-variant">Guardian
                                        Phone</label><input
                                        class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                        type="tel" placeholder="+91 XXXXX XXXXX" /></div>
                                <div class="space-y-2"><label class="font-label-md text-on-surface-variant">Alternate
                                        Guardian (optional)</label><input
                                        class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"
                                        type="text" placeholder="Backup guardian name" /></div>
                            </div>
                        </div>
                    </section>
                    <!-- Special Bequests -->
                    <section class="mb-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container">
                                <span class="material-symbols-outlined">redeem</span></div>
                            <h2 class="font-headline-md text-headline-md text-primary">Special Bequests</h2>
                        </div>
                        <div
                            class="bg-surface-container-lowest p-8 rounded-2xl shadow-sm border border-outline-variant/30">
                            <label class="block font-label-md text-on-surface-variant mb-3">Specific items or amounts to
                                specific people</label>
                            <textarea
                                class="w-full bg-surface-container-low border border-outline-variant rounded-xl p-4 focus:ring-2 focus:ring-primary outline-none resize-none"
                                rows="4"
                                placeholder="e.g. My gold jewellery to my eldest daughter Priya. My vintage watch collection to my son Arjun..."></textarea>
                        </div>
                    </section>
                    <!-- Funeral Wishes -->
                    <section class="mb-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container">
                                <span class="material-symbols-outlined">local_florist</span></div>
                            <h2 class="font-headline-md text-headline-md text-primary">Funeral Wishes</h2>
                        </div>
                        <div
                            class="bg-surface-container-lowest p-8 rounded-2xl shadow-sm border border-outline-variant/30">
                            <label class="block font-label-md text-on-surface-variant mb-4">Preferred funeral
                                arrangement</label>
                            <div class="flex flex-wrap gap-4 mb-6">
                                <button
                                    class="funeral-btn flex flex-col items-center gap-2 p-4 rounded-xl border border-outline-variant hover:border-primary text-on-surface-variant transition-all"
                                    onclick="selectFuneral(this)"><span
                                        class="material-symbols-outlined">local_fire_department</span><span
                                        class="font-label-md">Cremation</span></button>
                                <button
                                    class="funeral-btn flex flex-col items-center gap-2 p-4 rounded-xl border border-outline-variant hover:border-primary text-on-surface-variant transition-all"
                                    onclick="selectFuneral(this)"><span
                                        class="material-symbols-outlined">height</span><span
                                        class="font-label-md">Burial</span></button>
                                <button
                                    class="funeral-btn flex flex-col items-center gap-2 p-4 rounded-xl border border-outline-variant hover:border-primary text-on-surface-variant transition-all"
                                    onclick="selectFuneral(this)"><span
                                        class="material-symbols-outlined">question_mark</span><span
                                        class="font-label-md">Undecided</span></button>
                            </div>
                            <label class="block font-label-md text-on-surface-variant mb-3">Additional ceremonies or
                                specific location requests</label>
                            <textarea
                                class="w-full bg-surface-container-low border border-outline-variant rounded-xl p-4 focus:ring-2 focus:ring-primary outline-none resize-none"
                                rows="3" placeholder="e.g. Traditional ceremony in my hometown..."></textarea>
                        </div>
                    </section>
                    <!-- Charitable Donations -->
                    <section class="mb-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container">
                                <span class="material-symbols-outlined">volunteer_activism</span></div>
                            <h2 class="font-headline-md text-headline-md text-primary">Charitable Donations</h2>
                        </div>
                        <div
                            class="bg-surface-container-lowest p-8 rounded-2xl shadow-sm border border-outline-variant/30">
                            <div class="flex flex-col gap-4">
                                <div
                                    class="flex items-center justify-between p-4 bg-surface-container-low rounded-xl border border-outline-variant/20">
                                    <div>
                                        <p class="font-label-md text-primary">Organ Donation</p>
                                        <p class="text-label-sm text-on-surface-variant">I wish for my organs to be
                                            donated after death.</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer"><input
                                            class="sr-only peer" type="checkbox" />
                                        <div
                                            class="w-11 h-6 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                        </div>
                                    </label>
                                </div>
                                <div><label class="block font-label-md text-on-surface-variant mb-3">Legacy Gifts to
                                        Non-Profits</label><textarea
                                        class="w-full bg-surface-container-low border border-outline-variant rounded-xl p-4 focus:ring-2 focus:ring-primary outline-none resize-none"
                                        rows="3"
                                        placeholder="List organization names and percentage or amount..."></textarea>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="flex justify-between items-center">
                        <button onclick="prevStep()"
                            class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-all px-6 py-3 rounded-xl border border-outline-variant"><span
                                class="material-symbols-outlined">arrow_back</span>Back</button>
                        <button onclick="nextStep()"
                            class="bg-primary text-on-primary px-12 py-3 rounded-xl font-label-md shadow-lg hover:opacity-90 transition-all flex items-center gap-2">Confirm
                            &amp; Continue<span class="material-symbols-outlined">arrow_forward</span></button>
                    </div>
                </div>

                <!-- ========== STEP 6: DOCUMENT UPLOAD ========== -->
                <div class="step-panel" id="panel-6">
                    <div class="mb-10">
                        <span
                            class="inline-flex items-center gap-2 text-tertiary-container font-label-sm uppercase tracking-widest mb-4"><span
                                class="material-symbols-outlined text-[16px]">upload_file</span>STEP 6 OF 8</span>
                        <h1 class="font-headline-xl text-headline-xl text-primary mb-4 leading-tight">Document Upload
                        </h1>
                        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">Upload supporting
                            documents for legal verification. All files are encrypted immediately.</p>
                    </div>
                    <!-- Identity Docs -->
                    <section class="bg-white rounded-2xl p-8 shadow-sm border border-outline-variant/30 mb-8">
                        <div class="flex items-center gap-3 mb-6"><span
                                class="material-symbols-outlined text-primary text-3xl">badge</span>
                            <h3 class="font-headline-md text-headline-md">Identity Documents</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="upload-card bg-surface-container rounded-xl p-4 border-2 border-dashed border-outline-variant hover:border-primary transition-all cursor-pointer group"
                                onclick="triggerUpload('aadhaar-file')">
                                <div class="flex justify-between items-start mb-3"><span
                                        class="material-symbols-outlined text-primary">upload_file</span><span
                                        class="text-label-sm text-outline group-hover:text-primary">UPLOAD</span></div>
                                <p class="font-label-md text-on-surface">Aadhaar Card</p>
                                <p class="text-[10px] text-on-surface-variant">Front &amp; back scan, PDF or JPG</p>
                                <input id="aadhaar-file" data-doctype="aadhaar" type="file" accept=".pdf,.jpg,.png" class="hidden"
                                    onchange="fileUploaded(this,'aadhaar-card-status')" />
                                <p id="aadhaar-card-status" class="text-[10px] text-primary mt-2 hidden">✓ File selected
                                </p>
                            </div>
                            <div class="upload-card bg-surface-container rounded-xl p-4 border-2 border-dashed border-outline-variant hover:border-primary transition-all cursor-pointer group"
                                onclick="triggerUpload('pan-file')">
                                <div class="flex justify-between items-start mb-3"><span
                                        class="material-symbols-outlined text-primary">upload_file</span><span
                                        class="text-label-sm text-outline group-hover:text-primary">UPLOAD</span></div>
                                <p class="font-label-md text-on-surface">PAN Card</p>
                                <p class="text-[10px] text-on-surface-variant">Clear scan, PDF or JPG</p>
                                <input id="pan-file" data-doctype="pan" type="file" accept=".pdf,.jpg,.png" class="hidden"
                                    onchange="fileUploaded(this,'pan-status')" />
                                <p id="pan-status" class="text-[10px] text-primary mt-2 hidden">✓ File selected</p>
                            </div>
                        </div>
                    </section>
                    <!-- Asset Docs -->
                    <section class="bg-white rounded-2xl p-8 shadow-sm border border-outline-variant/30 mb-8">
                        <div class="flex items-center gap-3 mb-6"><span
                                class="material-symbols-outlined text-primary text-3xl">description</span>
                            <h3 class="font-headline-md text-headline-md">Asset Documents (Optional)</h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-surface-container rounded-xl p-4 border border-outline-variant/20 hover:shadow-md transition-shadow cursor-pointer group"
                                onclick="triggerUpload('asset1-file')">
                                <div class="flex justify-between items-start mb-3"><span
                                        class="material-symbols-outlined text-primary">upload_file</span><span
                                        class="text-label-sm text-outline group-hover:text-primary">UPLOAD</span></div>
                                <p class="font-label-md text-on-surface">Property Title Deed</p><input id="asset1-file" data-doctype="property_deed"
                                    type="file" accept=".pdf,.jpg,.png" class="hidden"
                                    onchange="fileUploaded(this,'asset1-status')" />
                                <p id="asset1-status" class="text-[10px] text-primary mt-2 hidden">✓ Uploaded</p>
                            </div>
                            <div class="bg-surface-container rounded-xl p-4 border border-outline-variant/20 hover:shadow-md transition-shadow cursor-pointer group"
                                onclick="triggerUpload('asset2-file')">
                                <div class="flex justify-between items-start mb-3"><span
                                        class="material-symbols-outlined text-primary">upload_file</span><span
                                        class="text-label-sm text-outline group-hover:text-primary">UPLOAD</span></div>
                                <p class="font-label-md text-on-surface">Bank Passbook</p><input id="asset2-file" data-doctype="bank_passbook"
                                    type="file" accept=".pdf,.jpg,.png" class="hidden"
                                    onchange="fileUploaded(this,'asset2-status')" />
                                <p id="asset2-status" class="text-[10px] text-primary mt-2 hidden">✓ Uploaded</p>
                            </div>
                            <div class="bg-surface-container rounded-xl p-4 border border-outline-variant/20 hover:shadow-md transition-shadow cursor-pointer group"
                                onclick="triggerUpload('asset3-file')">
                                <div class="flex justify-between items-start mb-3"><span
                                        class="material-symbols-outlined text-primary">upload_file</span><span
                                        class="text-label-sm text-outline group-hover:text-primary">UPLOAD</span></div>
                                <p class="font-label-md text-on-surface">Demat Statement</p><input id="asset3-file" data-doctype="demat"
                                    type="file" accept=".pdf,.jpg,.png" class="hidden"
                                    onchange="fileUploaded(this,'asset3-status')" />
                                <p id="asset3-status" class="text-[10px] text-primary mt-2 hidden">✓ Uploaded</p>
                            </div>
                        </div>
                    </section>
                    <!-- Passport Photo -->
                    <section class="bg-white rounded-2xl p-8 shadow-sm border border-outline-variant/30 mb-8">
                        <div class="flex items-center gap-3 mb-6"><span
                                class="material-symbols-outlined text-primary text-3xl">face</span>
                            <h3 class="font-headline-md text-headline-md">Passport Photo</h3>
                        </div>
                        <div class="flex gap-6 items-center">
                            <div class="w-32 h-40 bg-surface-container rounded-lg border-2 border-dashed border-outline-variant flex flex-col items-center justify-center text-center p-2 group cursor-pointer hover:border-primary"
                                onclick="triggerUpload('photo-file')"><span
                                    class="material-symbols-outlined text-3xl text-outline mb-1">add_a_photo</span>
                                <p class="text-[10px] font-bold uppercase tracking-tighter">Capture or Upload</p><input
                                    id="photo-file" data-doctype="passport_photo" type="file" accept="image/*" class="hidden"
                                    onchange="fileUploaded(this,'photo-status')" />
                            </div>
                            <div class="flex-1">
                                <p class="text-label-sm text-on-surface-variant mb-3 italic">"A clear portrait photo
                                    helps in physical verification during Will registration."</p>
                                <div class="flex flex-wrap gap-2"><span
                                        class="bg-surface-container-high px-2 py-1 rounded text-[10px] text-on-surface">WHITE
                                        BG</span><span
                                        class="bg-surface-container-high px-2 py-1 rounded text-[10px] text-on-surface">NO
                                        GLASSES</span><span
                                        class="bg-surface-container-high px-2 py-1 rounded text-[10px] text-on-surface">RECENT</span>
                                </div>
                                <p id="photo-status" class="text-[10px] text-primary mt-2 hidden">✓ Photo selected</p>
                            </div>
                        </div>
                    </section>
                    <!-- Security Banner -->
                    <section
                        class="bg-primary text-on-primary rounded-2xl p-8 shadow-lg flex flex-col md:flex-row items-center gap-6 overflow-hidden relative mb-8">
                        <div class="absolute -right-10 -bottom-10 opacity-10"><span
                                class="material-symbols-outlined text-[200px]"
                                style="font-variation-settings:'FILL' 1;">security</span></div>
                        <div class="z-10 bg-on-primary/20 w-16 h-16 rounded-full flex items-center justify-center"><span
                                class="material-symbols-outlined text-3xl">verified</span></div>
                        <div class="z-10 text-center md:text-left">
                            <h4 class="font-headline-md text-headline-md mb-2">Our Data Security Promise</h4>
                            <p class="font-body-md text-white/80">Documents are purged from our temporary servers
                                immediately after your legal drafts are finalized. We never share your data with 3rd
                                parties.</p>
                        </div>
                    </section>
                    <div class="flex justify-between items-center">
                        <button onclick="prevStep()"
                            class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-all px-6 py-3 rounded-xl border border-outline-variant"><span
                                class="material-symbols-outlined">arrow_back</span>Back</button>
                        <button onclick="nextStep()"
                            class="bg-primary text-on-primary px-12 py-4 rounded-xl font-headline-md shadow-xl hover:opacity-90 transition-all flex items-center gap-3">Save
                            &amp; Continue<span class="material-symbols-outlined">chevron_right</span></button>
                    </div>
                </div>

                <!-- ========== STEP 7: REVIEW & PAYMENT ========== -->
                <div class="step-panel" id="panel-7">
                    <div class="mb-10">
                        <span
                            class="inline-flex items-center gap-2 text-tertiary-container font-label-sm uppercase tracking-widest mb-4"><span
                                class="material-symbols-outlined text-[16px]">rate_review</span>STEP 7 OF 8</span>
                        <h1 class="font-headline-xl text-headline-xl text-primary mb-4 leading-tight">Review &amp;
                            Payment</h1>
                        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">Review your Will summary
                            and choose a plan to complete your legal document.</p>
                    </div>
                    <div class="flex flex-col lg:flex-row gap-8">
                        <!-- Review Accordion -->
                        <div class="flex-1 space-y-4">
                            <!-- Section 1 -->
                            <div
                                class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden">
                                <button onclick="toggleAccordion('acc-personal')"
                                    class="w-full flex items-center justify-between p-6 hover:bg-surface-container-low transition-colors">
                                    <div class="flex items-center gap-4"><span
                                            class="material-symbols-outlined text-primary">person</span><span
                                            class="font-label-md text-on-surface">Personal Details</span></div>
                                    <span id="acc-personal-icon"
                                        class="material-symbols-outlined text-outline transition-transform">expand_more</span>
                                </button>
                                <div id="acc-personal-content" class="accordion-content">
                                    <div class="px-6 pb-6 space-y-3 text-sm text-on-surface-variant">
                                        <div class="flex justify-between"><span>Will Type</span><span
                                                id="summary-will-type" class="font-bold text-on-surface">Individual
                                                Will</span></div>
                                        <div class="flex justify-between"><span>Full Name</span><span id="summary-name"
                                                class="font-bold text-on-surface">—</span></div>
                                        <div class="flex justify-between"><span>Date of Birth</span><span
                                                id="summary-dob" class="font-bold text-on-surface">—</span></div>
                                        <div class="flex justify-between"><span>Mobile</span><span id="summary-mobile"
                                                class="font-bold text-on-surface">—</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Section 2 -->
                            <div
                                class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden">
                                <button onclick="toggleAccordion('acc-assets')"
                                    class="w-full flex items-center justify-between p-6 hover:bg-surface-container-low transition-colors">
                                    <div class="flex items-center gap-4"><span
                                            class="material-symbols-outlined text-primary">account_balance</span><span
                                            class="font-label-md text-on-surface">Assets &amp; Beneficiaries</span>
                                    </div>
                                    <span id="acc-assets-icon"
                                        class="material-symbols-outlined text-outline transition-transform">expand_more</span>
                                </button>
                                <div id="acc-assets-content" class="accordion-content">
                                    <div class="px-6 pb-6 space-y-3 text-sm text-on-surface-variant">
                                        <p>Your asset portfolio and beneficiary shares have been recorded. Please verify
                                            them above before payment.</p>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <!-- Pricing Sidebar -->
                        <div class="w-full lg:w-96">
                            <div
                        class="sticky top-28 bg-surface-container-lowest rounded-2xl shadow-md border border-outline-variant/30 p-8 space-y-8">
                        <div>
                            <h3 class="font-headline-md text-on-surface mb-6">Choose Your Plan</h3>
                            <div class="space-y-4">
                                <div id="plan-card-basic"
                                    class="plan-card relative bg-surface-container-low border border-outline-variant/30 rounded-2xl p-6 hover:shadow-md transition-shadow cursor-pointer">
                                    <p class="text-label-sm text-outline uppercase tracking-wider mb-2">Essential
                                        Protection</p>
                                    <div class="flex justify-between items-start mb-4">
                                        <h4 class="font-headline-md text-primary">Basic</h4>
                                        <div class="text-right"><span class="text-lg mr-1">‹</span><span
                                                class="font-headline-md text-primary">999</span></div>
                                    </div>
                                    <ul class="space-y-3 mb-6">
                                        <li class="flex items-center gap-2 text-on-surface-variant font-label-md"><span
                                                class="material-symbols-outlined text-primary text-sm">check_circle</span>Single
                                            Legal Will</li>
                                        <li class="flex items-center gap-2 text-on-surface-variant font-label-md"><span
                                                class="material-symbols-outlined text-primary text-sm">check_circle</span>Instant
                                            Download</li>
                                    </ul><input class="hidden" name="plan_choice" type="radio" />
                                </div>
                                <div id="plan-card-standard"
                                    class="plan-card relative bg-primary text-on-primary border-2 border-primary rounded-2xl p-6 shadow-xl cursor-pointer">
                                    <div
                                        class="absolute -top-3 left-1/2 -translate-x-1/2 bg-on-secondary-fixed-variant text-on-primary text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                                        Recommended</div>
                                    <p class="text-label-sm text-on-primary/70 uppercase tracking-wider mb-2">Most
                                        Popular Choice</p>
                                    <div class="flex justify-between items-start mb-4">
                                        <h4 class="font-headline-md text-on-primary">Standard</h4>
                                        <div class="text-right"><span class="text-lg mr-1">‹</span><span
                                                class="font-headline-md text-on-primary">4,499</span></div>
                                    </div>
                                    <ul class="space-y-3 mb-6">
                                        <li class="flex items-center gap-2 font-label-md"><span
                                                class="material-symbols-outlined text-sm">check_circle</span>Professional
                                            Review</li>
                                        <li class="flex items-center gap-2 font-label-md"><span
                                                class="material-symbols-outlined text-sm">check_circle</span>Lawyer
                                            Verification</li>
                                        <li class="flex items-center gap-2 font-label-md"><span
                                                class="material-symbols-outlined text-sm">check_circle</span>Hardcopy
                                            Delivery</li>
                                    </ul><input checked="" class="hidden" name="plan_choice" type="radio" />
                                </div>
                                <div id="plan-card-premium"
                                    class="plan-card relative bg-surface-container-low border border-outline-variant/30 rounded-2xl p-6 hover:shadow-md transition-shadow cursor-pointer">
                                    <p class="text-label-sm text-outline uppercase tracking-wider mb-2">Maximum Security
                                    </p>
                                    <div class="flex justify-between items-start mb-4">
                                        <h4 class="font-headline-md text-primary">Premium</h4>
                                        <div class="text-right"><span class="text-lg mr-1">‹</span><span
                                                class="font-headline-md text-primary">9,999</span></div>
                                    </div>
                                    <ul class="space-y-3 mb-6">
                                        <li class="flex items-center gap-2 text-on-surface-variant font-label-md"><span
                                                class="material-symbols-outlined text-primary text-sm">check_circle</span>Joint
                                            Will Option</li>
                                        <li class="flex items-center gap-2 text-on-surface-variant font-label-md"><span
                                                class="material-symbols-outlined text-primary text-sm">check_circle</span>Priority
                                            Support</li>
                                    </ul><input class="hidden" name="plan_choice" type="radio" />
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3 pt-6 border-t border-outline-variant/20">
                            <div class="flex justify-between items-center text-sm text-on-surface-variant"><span>Subtotal</span><span id="subtotal-display">₹4,499.00</span></div>
                            <div class="flex justify-between items-center text-sm text-on-surface-variant"><span>GST (18%)</span><span id="gst-display">₹809.82</span></div>
                            <div class="flex justify-between items-center"><span class="font-headline-md">Total Amount</span><span class="font-headline-md text-primary" id="total-display">₹5,308.82</span></div>
                        </div>
                        <div class="space-y-4">
                            <button id="proceed-to-payment-btn"
                                class="w-full bg-primary text-on-primary font-label-md py-5 rounded-xl shadow-lg hover:shadow-xl active:scale-95 transition-all flex justify-center items-center gap-2">
                                Proceed to Payment
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </button>
                            <div
                                class="flex items-center justify-center gap-2 text-label-sm text-outline py-2 px-4 bg-surface-container-low rounded-lg">
                                <span class="material-symbols-outlined text-primary text-[16px]">verified_user</span>
                                256-bit Secure Encryption
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button onclick="prevStep()"
                            class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-all px-6 py-3 rounded-xl border border-outline-variant"><span
                                class="material-symbols-outlined">arrow_back</span>Back</button>
                    </div>
                </div>

                <!-- ========== STEP 8: CONFIRMATION ========== -->
                <div class="step-panel" id="panel-8">
                    <div class="flex flex-col items-center text-center py-12">
                        <div class="relative mb-12">
                            <div id="confirm-circle"
                                class="w-28 h-28 bg-primary rounded-full flex items-center justify-center shadow-2xl shadow-primary/30">
                                <span id="confirm-check" class="material-symbols-outlined text-on-primary text-5xl"
                                    style="font-variation-settings:'FILL' 1;">check_circle</span>
                            </div>
                            <div id="confirm-ring"
                                class="absolute inset-0 border-2 border-primary/20 rounded-full">
                            </div>
                        </div>
                        <h1 class="font-headline-xl text-headline-xl text-primary mb-4">Will Successfully Submitted</h1>
                        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mx-auto">Congratulations!
                            You have taken a significant step toward securing your family's future. Our legal team has
                            received your instructions and will begin the review process immediately.</p>
                        <div
                            class="mt-6 inline-flex items-center gap-2 bg-surface-container-high px-6 py-3 rounded-full">
                            <span class="font-label-md text-on-surface" id="order-id">Order ID: LW-2025-8842-BNW</span>
                            <button onclick="copyOrderId()"
                                class="material-symbols-outlined text-primary text-lg hover:scale-110 transition-transform"
                                id="copy-btn">content_copy</button>
                        </div>
                    </div>
                    <!-- Bento Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-section-gap">
                        <div
                            class="md:col-span-2 bg-surface-container-lowest rounded-2xl p-8 shadow-sm border border-outline-variant/30">
                            <h3 class="font-headline-md text-headline-md text-primary mb-8 flex items-center gap-3">
                                <span class="material-symbols-outlined">timeline</span>What Happens Next</h3>
                            <div class="relative space-y-8">
                                <div class="absolute left-6 top-4 bottom-4 w-0.5 bg-outline-variant"></div>
                                <div class="flex gap-6 relative">
                                    <div
                                        class="z-10 w-12 h-12 bg-primary rounded-full flex items-center justify-center text-on-primary shadow-sm">
                                        <span class="material-symbols-outlined text-xl">done</span></div>
                                    <div>
                                        <p class="font-label-md text-primary">Step 1: Submitted</p>
                                        <p class="font-body-md text-on-surface-variant">Your details have been securely
                                            transmitted to our vault.</p>
                                        <p class="text-[10px] text-primary/60 font-bold uppercase mt-1">Completed</p>
                                    </div>
                                </div>
                                <div class="flex gap-6 relative">
                                    <div
                                        class="z-10 w-12 h-12 bg-tertiary-fixed rounded-full flex items-center justify-center text-tertiary shadow-sm border-2 border-tertiary">
                                        <span class="material-symbols-outlined text-xl">gavel</span></div>
                                    <div>
                                        <p class="font-label-md text-tertiary">Step 2: Lawyer Review</p>
                                        <p class="font-body-md text-on-surface-variant">A verified legal expert is
                                            reviewing your assets and family structure for compliance.</p>
                                        <p class="text-[10px] text-tertiary font-bold uppercase mt-1">In Progress</p>
                                    </div>
                                </div>
                                <div class="flex gap-6 relative opacity-50">
                                    <div
                                        class="z-10 w-12 h-12 bg-surface-container rounded-full flex items-center justify-center text-on-surface-variant">
                                        <span class="material-symbols-outlined text-xl">description</span></div>
                                    <div>
                                        <p class="font-label-md text-on-surface">Step 3: Drafting &amp; Final Review</p>
                                        <p class="font-body-md text-on-surface-variant">The legal document will be
                                            drafted with precise technical language.</p>
                                    </div>
                                </div>
                                <div class="flex gap-6 relative opacity-50">
                                    <div
                                        class="z-10 w-12 h-12 bg-surface-container rounded-full flex items-center justify-center text-on-surface-variant">
                                        <span class="material-symbols-outlined text-xl">local_shipping</span></div>
                                    <div>
                                        <p class="font-label-md text-on-surface">Step 4: Delivery</p>
                                        <p class="font-body-md text-on-surface-variant">Digitally signed copy and
                                            optional physical home delivery.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-gutter">
                            <div
                                class="bg-primary-container text-on-primary-container p-8 rounded-2xl flex flex-col items-center text-center shadow-sm">
                                <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-white text-3xl"
                                        style="font-variation-settings:'FILL' 1;">verified_user</span></div>
                                <h4 class="font-label-md text-white mb-2 uppercase tracking-widest">Legally Binding</h4>
                                <p class="font-body-md text-on-primary-container/80">Every Will drafted via Will Banwalo
                                    is vetted against current Indian Succession Acts.</p>
                            </div>
                            <div
                                class="bg-surface-container-lowest border border-outline-variant/30 p-8 rounded-2xl flex-grow shadow-sm">
                                <h4 class="font-label-md text-on-surface mb-6">Immediate Actions</h4>
                                <div class="space-y-4">
                                    <button
                                        class="w-full bg-primary text-on-primary py-4 px-6 rounded-xl font-label-md flex items-center justify-center gap-3 hover:opacity-90 transition-all active:scale-95"><span
                                            class="material-symbols-outlined">receipt_long</span>Download
                                        Receipt</button>
                                    <a href="./track_order.html"><button
                                            class="w-full border-2 border-primary text-primary py-4 px-6 rounded-xl font-label-md flex items-center justify-center gap-3 hover:bg-primary/5 transition-all active:scale-95"><span
                                                class="material-symbols-outlined">track_changes</span>Track My
                                            Will</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-8">
                        <a href="./index.html"><button
                                class="bg-primary text-on-primary px-12 py-4 rounded-full font-label-md shadow-lg hover:opacity-90 transition-all">Return
                                to Home</button></a>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- ===== MOBILE BOTTOM NAV ===== -->
    <nav class="lg:hidden fixed bottom-0 left-0 w-full flex justify-around items-center px-4 py-3 pb-safe bg-surface shadow-[0_-4px_10px_rgba(0,0,0,0.05)] border-t border-outline-variant z-50"
        id="mobile-bottom-nav">
        <button onclick="prevStep()"
            class="flex flex-col items-center justify-center text-on-surface-variant p-2 hover:text-primary transition-all"><span
                class="material-symbols-outlined">arrow_back</span><span
                class="font-label-sm text-label-sm mt-1">Previous</span></button>
        <button
            class="flex flex-col items-center justify-center bg-primary text-on-primary rounded-full w-14 h-14 shadow-lg active:scale-90 transition-transform"><span
                class="material-symbols-outlined">save</span></button>
        <button onclick="nextStep()"
            class="flex flex-col items-center justify-center text-on-surface-variant p-2 hover:text-primary transition-all"><span
                class="material-symbols-outlined">arrow_forward</span><span
                class="font-label-sm text-label-sm mt-1">Next</span></button>
    </nav>

    <!-- ===== HELP FAB ===== -->
    <button
        class="fixed bottom-24 right-8 lg:bottom-8 lg:right-8 bg-tertiary text-on-tertiary flex items-center gap-3 px-6 py-4 rounded-full shadow-xl hover:scale-105 active:scale-95 transition-all z-40">
        <span class="material-symbols-outlined">support_agent</span>
        <span class="font-label-md text-label-md hidden sm:inline">Need Help?</span>
    </button>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-primary pt-section-gap pb-12">
        <div class="max-w-container-max-width mx-auto px-margin-desktop">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-20 mb-28">
                <div class="lg:col-span-5 space-y-12">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-secondary flex items-center justify-center rounded-xl"><span
                                class="material-symbols-outlined text-primary text-2xl font-bold">account_balance_wallet</span>
                        </div>
                        <div class="font-headline-md text-on-primary text-2xl font-bold tracking-tight">Will Banwalo
                        </div>
                    </div>
                    <p class="text-white/40 leading-relaxed text-lg max-w-sm">The intersection of legal excellence and
                        digital innovation. Redefining how India plans its future.</p>
                    <div class="flex gap-6">
                        <a class="w-12 h-12 rounded-full border border-white/10 flex items-center justify-center text-white/60 hover:text-secondary hover:border-secondary transition-all"
                            href="#"><svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg></a>
                        <a class="w-12 h-12 rounded-full border border-white/10 flex items-center justify-center text-white/60 hover:text-secondary hover:border-secondary transition-all"
                            href="#"><svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg></a>
                    </div>
                </div>
                <div class="lg:col-span-7 grid grid-cols-2 md:grid-cols-3 gap-12">
                    <div>
                        <h4 class="text-on-primary font-bold mb-8 text-sm uppercase tracking-widest">Solutions</h4>
                        <ul class="space-y-4 text-white/40">
                            <li><a class="hover:text-secondary transition-colors" href="#">Personal Will</a></li>
                            <li><a class="hover:text-secondary transition-colors" href="#">Joint Will</a></li>
                            <li><a class="hover:text-secondary transition-colors" href="#">Power of Attorney</a></li>
                            <li><a class="hover:text-secondary transition-colors" href="#">Asset Vault</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-on-primary font-bold mb-8 text-sm uppercase tracking-widest">Company</h4>
                        <ul class="space-y-4 text-white/40">
                            <li><a class="hover:text-secondary transition-colors" href="#">About Us</a></li>
                            <li><a class="hover:text-secondary transition-colors" href="#">Legal Experts</a></li>
                            <li><a class="hover:text-secondary transition-colors" href="#">Careers</a></li>
                            <li><a class="hover:text-secondary transition-colors" href="#">Press</a></li>
                        </ul>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <h4 class="text-on-primary font-bold mb-8 text-sm uppercase tracking-widest">Support</h4>
                        <ul class="space-y-4 text-white/40">
                            <li><a class="hover:text-secondary transition-colors" href="#">Help Center</a></li>
                            <li><a class="hover:text-secondary transition-colors" href="#">WhatsApp Support</a></li>
                            <li><a class="hover:text-secondary transition-colors" href="#">Terms of Use</a></li>
                            <li><a class="hover:text-secondary transition-colors" href="#">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-8">
                <p class="text-white/20 text-sm italic">© 2024 Will Banwalo. A premium legal technology boutique. All
                    rights reserved.</p>
                <div class="flex gap-12 items-center text-white/20 text-xs tracking-widest font-bold">
                    <span class="flex items-center gap-2 uppercase"><span
                            class="material-symbols-outlined text-secondary text-sm">verified</span> Verified legal
                        flow</span>
                    <span class="flex items-center gap-2 uppercase"><span
                            class="material-symbols-outlined text-secondary text-sm">shield</span> AES-256
                        Encrypted</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Razorpay Checkout SDK -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <!-- GSAP (no ScrollSmoother here — this page has a `position: sticky`
         step sidebar, and ScrollSmoother's transform-based scrolling is
         known to break native sticky positioning, which we hit and fixed
         on the FAQ page. Keeping native scroll protects the sidebar and
         the Razorpay payment flow.) -->
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollToPlugin.min.js"></script>

    <script>
    gsap.registerPlugin(ScrollToPlugin);

    // ── Custom cursor ────────────────────────────────────────────
    const curDot = document.getElementById('curDot');
    const curRing = document.getElementById('curRing');
    const dotX = gsap.quickTo(curDot, "x", { duration: 0.1, ease: "power3" });
    const dotY = gsap.quickTo(curDot, "y", { duration: 0.1, ease: "power3" });
    const ringX = gsap.quickTo(curRing, "x", { duration: 0.45, ease: "power3" });
    const ringY = gsap.quickTo(curRing, "y", { duration: 0.45, ease: "power3" });
    window.addEventListener('mousemove', (e) => {
        dotX(e.clientX); dotY(e.clientY);
        ringX(e.clientX); ringY(e.clientY);
    });
    function bindCursorGrow(el) {
        el.addEventListener('mouseenter', () => curRing.classList.add('grow'));
        el.addEventListener('mouseleave', () => curRing.classList.remove('grow'));
    }
    document.querySelectorAll('a, button, .sidebar-item, .will-card, .plan-card').forEach(bindCursorGrow);

    // ── Magnetic feel on primary buttons (applied globally so no
    //    markup changes were needed across the 8 steps) ────────────
    document.querySelectorAll('button, a').forEach((btn) => {
        btn.addEventListener('mousemove', (e) => {
            const r = btn.getBoundingClientRect();
            const x = e.clientX - r.left - r.width / 2;
            const y = e.clientY - r.top - r.height / 2;
            gsap.to(btn, { x: x * 0.15, y: y * 0.25, duration: 0.4, ease: 'power3' });
        });
        btn.addEventListener('mouseleave', () => {
            gsap.to(btn, { x: 0, y: 0, duration: 0.6, ease: 'elastic.out(1, 0.5)' });
        });
    });

    // ── Mobile nav menu ──────────────────────────────────────────
    const menuBtn = document.getElementById('menuBtn');
    const mobilePanel = document.getElementById('mobilePanel');
    let menuOpen = false;
    if (menuBtn) {
        menuBtn.addEventListener('click', () => {
            menuOpen = !menuOpen;
            gsap.to(mobilePanel, { height: menuOpen ? 'auto' : 0, duration: 0.45, ease: 'power2.inOut' });
        });
        mobilePanel.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
            menuOpen = false;
            gsap.to(mobilePanel, { height: 0, duration: 0.4 });
        }));
    }

    // ── Initial reveal for step 1 (on load) ─────────────────────
    // Guarded + given a hard fallback: this is a decorative fade-in, and
    // it must never be able to leave the sidebar (or the first panel)
    // stuck at opacity 0 if GSAP is slow to load or a tween gets
    // interrupted. The timeout forces full visibility no matter what.
    window.addEventListener('DOMContentLoaded', () => {
        if (typeof gsap !== 'undefined') {
            gsap.from('#panel-1', { opacity: 0, y: 24, duration: 0.7, ease: 'power3.out' });
            gsap.from('.sidebar-item', { opacity: 0, x: -16, duration: 0.5, stagger: 0.06, ease: 'power2.out', delay: 0.1 });
        }
        setTimeout(() => {
            document.querySelectorAll('.sidebar-item').forEach(el => {
                el.style.opacity = '1';
                el.style.transform = 'none';
            });
        }, 1500);
    });
    </script>

    <script>
    // ============================================================
    //  STATE
    // ============================================================
    let currentStep     = 1;
    let selectedWillType = 'Individual Will';
    let selectedPlan    = 'standard';
    let orderId         = null;   // set after Step 1 API call
    let orderRef        = null;
    const totalSteps    = 8;

    const PLAN_PRICES = { basic: 999, standard: 4499, premium: 9999 };

    // ============================================================
    //  TOAST NOTIFICATION
    // ============================================================
    function showToast(msg, type = 'info') {
        const el = document.createElement('div');
        const colors = { info:'bg-primary', error:'bg-error', success:'bg-secondary' };
        el.className = `fixed bottom-6 left-1/2 -translate-x-1/2 z-[999] px-6 py-3 rounded-full text-white font-label-md shadow-xl transition-all text-sm ${colors[type]||colors.info}`;
        el.innerText = msg;
        document.body.appendChild(el);
        setTimeout(() => { el.style.opacity = '0'; setTimeout(() => el.remove(), 400); }, 3200);
    }

    // ============================================================
    //  LOADING OVERLAY
    // ============================================================
    function setLoading(on) {
        let overlay = document.getElementById('api-loading-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'api-loading-overlay';
            overlay.className = 'fixed inset-0 bg-black/30 z-[998] flex items-center justify-center backdrop-blur-sm';
            overlay.innerHTML = `<div class="bg-white rounded-2xl px-10 py-8 flex flex-col items-center gap-4 shadow-2xl">
                <div class="w-10 h-10 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                <p class="font-label-md text-primary">Please wait…</p>
            </div>`;
            document.body.appendChild(overlay);
        }
        overlay.style.display = on ? 'flex' : 'none';
    }

    // ============================================================
    //  API HELPER
    // ============================================================
    async function apiPost(url, body) {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body),
        });
        const json = await res.json();
        if (!json.success) throw new Error(json.error || 'Unknown error');
        return json;
    }

    // ============================================================
    //  STEP NAVIGATION (with save-on-advance)
    // ============================================================
    function goToStep(step) {
        if (step < 1 || step > totalSteps) return;
        document.getElementById('panel-' + currentStep).classList.remove('active');
        currentStep = step;
        const panel = document.getElementById('panel-' + currentStep);
        panel.classList.add('active');
        // Sidebar/state updates must never depend on GSAP succeeding —
        // run them first and unconditionally so a slow/blocked CDN load
        // (or any animation error) can't freeze the sidebar mid-flow.
        updateSidebar();
        updatePersonalForm();
        if (currentStep === 7) populateSummary();
        if (currentStep === 8) playConfirmationAnimation();
        // GSAP entrance for the newly active panel (replaces the old
        // CSS @keyframes fadeSlideIn, which fired via a class rule).
        // Guarded: if GSAP hasn't loaded, skip the flourish rather than
        // throwing and breaking the rest of the step-navigation flow.
        if (typeof gsap !== 'undefined') {
            gsap.fromTo(panel, { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: 0.4, ease: 'power2.out' });
            gsap.to(window, { duration: 0.6, scrollTo: 0, ease: 'power2.out' });
        } else {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    // Replaces the old CSS .animate-check / .animate-ping combo with an
    // explicit GSAP timeline, triggered only when step 8 is actually shown.
    function playConfirmationAnimation() {
        gsap.set('#confirm-check', { scale: 0, opacity: 0 });
        gsap.set('#confirm-ring', { scale: 1, opacity: 1 });
        gsap.to('#confirm-check', { scale: 1, opacity: 1, duration: 0.6, ease: 'back.out(2.5)' });
        gsap.fromTo('#confirm-ring',
            { scale: 1, opacity: 0.5 },
            { scale: 1.4, opacity: 0, duration: 1.4, repeat: -1, ease: 'sine.out' }
        );
    }

    function prevStep() { goToStep(currentStep - 1); }

    // nextStep triggers API save for the current step, then advances
    async function nextStep() {
        try {
            setLoading(true);
            await saveCurrentStep();
            goToStep(currentStep + 1);
        } catch (err) {
            showToast(err.message, 'error');
        } finally {
            setLoading(false);
        }
    }

    // ============================================================
    //  SAVE EACH STEP
    // ============================================================
    async function saveCurrentStep() {
        switch (currentStep) {
            case 1: return saveStep1();
            case 2: return saveStep2();
            case 3: return saveStep3();
            case 4: return saveStep4();
            case 5: return saveStep5();
            case 6: return saveStep6();
            case 7: return saveStep7();
            default: return;
        }
    }

    // ── Step 1: Will Type ────────────────────────
    async function saveStep1() {
        if (!selectedWillType) throw new Error('Please select a will type.');
        const data = await apiPost('api/save_order.php', { step: 1, will_type: selectedWillType });
        orderId  = data.order_id;
        orderRef = data.order_ref;
    }

    // ── Step 2: Personal Details ─────────────────
    async function saveStep2() {
        if (!orderId) throw new Error('Session lost. Please refresh.');
        const g = id => (document.getElementById(id) || {}).value || '';
        const fullName = g('full-name');
        const dob      = g('dob');
        const mobile   = g('mobile');
        if (!fullName) throw new Error('Full name is required.');
        if (!dob)      throw new Error('Date of birth is required.');
        if (!mobile || mobile.replace(/\D/g,'').length < 10) throw new Error('Valid mobile number is required.');
        await apiPost('api/save_order.php', {
            step: 2, order_id: orderId,
            full_name: fullName, dob, mobile,
            gender:   g('gender'), religion: g('religion'),
            email:    g('email'),
            aadhaar:  g('aadhaar'), pan: g('pan'),
            address:  g('address'), city: g('city'),
            state:    g('state'),   pin: g('pin'),
            // Joint Will
            spouse_name:    g('spouse-name'), spouse_dob: g('spouse-dob'), spouse_aadhaar: g('spouse-aadhaar'),
            // Senior
            medical_conditions: g('medical-conditions'), primary_caretaker: g('primary-caretaker'),
            // Business
            business_name: g('business-name'), business_reg: g('business-reg'),
            // Executor
            executor_name: g('executor-name'), executor_phone: g('executor-phone'),
        });
    }

    // ── Step 3: Beneficiaries ────────────────────
    async function saveStep3() {
        if (!orderId) throw new Error('Session lost. Please refresh.');
        const cards = document.querySelectorAll('#beneficiary-list > div');
        const beneficiaries = [];
        cards.forEach(card => {
            const inputs = card.querySelectorAll('input, select');
            beneficiaries.push({
                full_name:    inputs[0]?.value || '',
                relationship: inputs[1]?.value || 'Other',
                dob:          inputs[2]?.value || null,
                share_pct:    parseFloat(inputs[3]?.value || 0),
            });
        });
        if (!beneficiaries.length || !beneficiaries[0].full_name) {
            throw new Error('Please add at least one beneficiary.');
        }
        const total = beneficiaries.reduce((s, b) => s + (b.share_pct || 0), 0);
        if (total > 100) throw new Error('Total share cannot exceed 100%.');
        await apiPost('api/save_order.php', { step: 3, order_id: orderId, beneficiaries });
    }

    // ── Step 4: Assets ───────────────────────────
    async function saveStep4() {
        if (!orderId) throw new Error('Session lost. Please refresh.');
        const assets = [];
        // Real Estate
        const reDesc = document.querySelector('#re-form input')?.value;
        if (reDesc) assets.push({ asset_type: 'real_estate', description: reDesc,
            details: { reg_no: document.querySelectorAll('#re-form input')[1]?.value, location: document.querySelectorAll('#re-form input')[2]?.value }});
        // Bank
        const bankDesc = document.querySelector('#bank-form input')?.value;
        if (bankDesc) assets.push({ asset_type: 'bank', description: bankDesc,
            details: { account_no: document.querySelectorAll('#bank-form input')[1]?.value, ifsc: document.querySelectorAll('#bank-form input')[2]?.value }});
        // Investments
        const invDesc = document.querySelector('#inv-form input')?.value;
        if (invDesc) assets.push({ asset_type: 'investments', description: invDesc });
        // Vehicles
        const vehDesc = document.querySelector('#veh-form input')?.value;
        if (vehDesc) assets.push({ asset_type: 'vehicles', description: vehDesc,
            details: { reg_no: document.querySelectorAll('#veh-form input')[1]?.value }});
        // Jewellery
        const jewDesc = document.querySelector('#jew-form textarea')?.value;
        if (jewDesc) assets.push({ asset_type: 'jewellery', description: jewDesc });
        // Digital
        const digDesc = document.querySelector('#dig-form textarea')?.value;
        if (digDesc) assets.push({ asset_type: 'digital', description: digDesc });
        // Save even if empty (optional step)
        await apiPost('api/save_order.php', { step: 4, order_id: orderId, assets });
    }

    // ── Step 5: Special Instructions ─────────────
    async function saveStep5() {
        if (!orderId) throw new Error('Session lost. Please refresh.');
        const activeFuneral = document.querySelector('.funeral-btn.border-primary');
        const funeralPref   = activeFuneral?.querySelector('.font-label-md')?.innerText || '';
        const funeralNotes  = document.querySelector('#panel-5 textarea')?.value || '';
        const organDonation = document.querySelector('#panel-5 input[type=checkbox]')?.checked || false;
        const charityNotes  = document.querySelectorAll('#panel-5 textarea')[1]?.value || '';
        await apiPost('api/save_order.php', {
            step: 5, order_id: orderId,
            funeral_preference: funeralPref,
            funeral_notes:      funeralNotes,
            organ_donation:     organDonation,
            charity_notes:      charityNotes,
        });
    }

    // ── Step 6: Documents (auto-upload on file select) ─
    async function saveStep6() {
        // File uploads happen live via uploadFile(); nothing more needed here.
        if (!orderId) throw new Error('Session lost. Please refresh.');
        // Just pass through
    }

    // ── Step 7: Plan selection ────────────────────
    async function saveStep7() {
        if (!orderId) throw new Error('Session lost. Please refresh.');
        await apiPost('api/save_order.php', { step: 7, order_id: orderId, plan: selectedPlan });
    }

    // ============================================================
    //  SIDEBAR
    // ============================================================
    function updateSidebar() {
        // Colors are set as inline styles here rather than via Tailwind
        // classes — inline styles always win regardless of any CSS
        // specificity or class-generation timing issues, so this can't
        // silently fail the way the class-toggle approach did.
        const items = document.querySelectorAll('.sidebar-item');
        items.forEach((item, i) => {
            const step = i + 1;
            item.classList.remove('active');
            // Defensive reset: guarantees every sidebar item is fully
            // visible and in place, regardless of what any entrance/step
            // animation left it at (e.g. if GSAP was interrupted or
            // never finished tweening opacity/x back to normal).
            item.style.opacity = '1';
            item.style.transform = 'none';
            const icon = item.querySelector('.material-symbols-outlined');
            if (step === currentStep) {
                item.style.backgroundColor = '#7a3b2e';
                item.style.color = '#ffffff';
                if (icon) icon.style.color = '#ffffff';
            } else if (step < currentStep) {
                item.style.backgroundColor = 'transparent';
                item.style.color = '#0b2016';
                if (icon) { icon.style.color = '#0b2016'; icon.textContent = 'check_circle'; }
            } else {
                item.style.backgroundColor = 'transparent';
                item.style.color = 'rgba(11, 32, 22, 0.5)';
                if (icon) icon.style.color = 'rgba(11, 32, 22, 0.5)';
            }
        });
        const stepLabel = document.getElementById('sidebar-step-label');
        if (stepLabel) stepLabel.innerText = 'Step ' + currentStep + ' of ' + totalSteps;
        const bar = document.getElementById('progress-bar');
        if (bar) bar.style.width = ((currentStep / totalSteps) * 100) + '%';
    }

    // ============================================================
    //  STEP 1: WILL TYPE SELECTION
    // ============================================================
    function selectWillCard(el, willType) {
        document.querySelectorAll('.will-card').forEach(c => c.classList.remove('card-active'));
        el.classList.add('card-active');
        selectedWillType = willType;
    }

    // ============================================================
    //  STEP 2: PERSONAL FORM
    // ============================================================
    function updatePersonalForm() {
        if (currentStep !== 2) return;
        const note      = document.getElementById('personal-will-type-note');
        const jointForm = document.getElementById('form-joint');
        const seniorForm= document.getElementById('form-senior');
        const bizForm   = document.getElementById('form-business');
        jointForm.classList.add('hidden');
        seniorForm.classList.add('hidden');
        bizForm.classList.add('hidden');
        if (selectedWillType === 'Joint Will') {
            note.innerText = 'Please provide your personal details and your spouse/partner\'s information for a Joint Will.';
            jointForm.classList.remove('hidden');
        } else if (selectedWillType === 'Senior Citizen Will') {
            note.innerText = 'Please provide your personal details and any medical directives for the Senior Citizen Will.';
            seniorForm.classList.remove('hidden');
        } else if (selectedWillType === 'Business Asset Will') {
            note.innerText = 'Please provide your personal details and your business information for the Business Asset Will.';
            bizForm.classList.remove('hidden');
        } else if (selectedWillType === 'Property Will') {
            note.innerText = 'Please provide your personal details. Detailed property information will be collected in the Asset Portfolio step.';
        } else {
            note.innerText = 'Please provide your personal and legal identification details.';
        }
    }

    // ============================================================
    //  STEP 3: BENEFICIARIES
    // ============================================================
    let beneficiaryCount = 1;
    document.getElementById('add-beneficiary-btn').addEventListener('click', () => {
        beneficiaryCount++;
        const list = document.getElementById('beneficiary-list');
        const card = document.createElement('div');
        card.className = 'bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 shadow-sm';
        card.innerHTML = `
    <div class="flex justify-between items-center mb-6">
      <h4 class="font-label-md text-primary uppercase tracking-wider">Beneficiary #${beneficiaryCount}</h4>
      <button class="text-error text-label-sm hover:underline remove-beneficiary">Remove</button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="space-y-2"><label class="font-label-md text-on-surface-variant">Full Name</label><input class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none" type="text" placeholder="Legal full name"/></div>
      <div class="space-y-2"><label class="font-label-md text-on-surface-variant">Relationship</label><select class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none"><option>Spouse</option><option>Son</option><option>Daughter</option><option>Father</option><option>Mother</option><option>Brother</option><option>Sister</option><option>Other</option></select></div>
      <div class="space-y-2"><label class="font-label-md text-on-surface-variant">Date of Birth</label><input class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none" type="date"/></div>
      <div class="space-y-2"><label class="font-label-md text-on-surface-variant">% Share of Estate</label><div class="relative"><input class="w-full bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary outline-none" type="number" placeholder="0" min="0" max="100"/><span class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant font-bold">%</span></div></div>
    </div>`;
        card.style.opacity = '0'; card.style.transform = 'translateY(16px)';
        list.appendChild(card);
        setTimeout(() => { card.style.transition = 'all 0.4s ease-out'; card.style.opacity = '1'; card.style.transform = 'translateY(0)'; }, 30);
        card.querySelector('.remove-beneficiary').addEventListener('click', () => card.remove());
    });

    // ============================================================
    //  STEP 4: ASSETS
    // ============================================================
    function toggleAssetForm(id) {
        document.getElementById(id).classList.toggle('hidden');
    }
    function markAssetAdded(badgeId, formId) {
        const badge = document.getElementById(badgeId);
        badge.innerText = 'Added ✓';
        badge.className = 'bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full text-[12px] font-bold';
        document.getElementById(formId).classList.add('hidden');
    }

    // ============================================================
    //  STEP 5: FUNERAL
    // ============================================================
    function selectFuneral(el) {
        document.querySelectorAll('.funeral-btn').forEach(b => b.classList.remove('border-primary', 'text-primary', 'bg-secondary-container/20'));
        el.classList.add('border-primary', 'text-primary', 'bg-secondary-container/20');
    }

    // ============================================================
    //  STEP 6: FILE UPLOAD (live upload on file select)
    // ============================================================
    function triggerUpload(id) { document.getElementById(id).click(); }

    function fileUploaded(input, statusId) {
        if (!input.files || !input.files[0]) return;
        const statusEl = document.getElementById(statusId);
        if (!orderId) {
            showToast('Please complete Step 1 before uploading documents.', 'error');
            input.value = '';
            return;
        }
        const file    = input.files[0];
        const docType = input.dataset.doctype || input.id.replace('-file', '');
        const maxMB   = 5;
        if (file.size > maxMB * 1024 * 1024) {
            showToast('File too large. Maximum ' + maxMB + ' MB.', 'error');
            input.value = '';
            return;
        }
        if (statusEl) { statusEl.innerText = '⏳ Uploading…'; statusEl.classList.remove('hidden'); }

        const formData = new FormData();
        formData.append('order_id', orderId);
        formData.append('doc_type', docType);
        formData.append('file',     file);

        fetch('api/upload_document.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(json => {
                if (json.success) {
                    if (statusEl) statusEl.innerText = '✓ ' + file.name;
                    showToast('Document uploaded.', 'success');
                } else {
                    if (statusEl) { statusEl.innerText = '✗ Upload failed'; }
                    showToast(json.error || 'Upload failed.', 'error');
                    input.value = '';
                }
            })
            .catch(() => {
                if (statusEl) statusEl.innerText = '✗ Upload error';
                showToast('Network error during upload.', 'error');
                input.value = '';
            });
    }

    // ============================================================
    //  STEP 7: REVIEW & PLAN SELECTION
    // ============================================================
    function toggleAccordion(id) {
        const content = document.getElementById(id + '-content');
        const icon    = document.getElementById(id + '-icon');
        const isOpen  = content.classList.contains('open');
        if (isOpen) {
            content.classList.remove('open');
            gsap.to(content, { height: 0, duration: 0.4, ease: 'power2.inOut' });
            gsap.to(icon, { rotate: 0, duration: 0.35 });
        } else {
            content.classList.add('open');
            gsap.to(content, { height: 'auto', duration: 0.4, ease: 'power2.inOut' });
            gsap.to(icon, { rotate: 180, duration: 0.35 });
        }
    }

    function selectPlan(plan) {
        selectedPlan = plan;
        updateTotal(PLAN_PRICES[plan]);
        // Highlight selected plan card
        document.querySelectorAll('.plan-card').forEach(c => {
            c.classList.remove('ring-2', 'ring-primary');
        });
        const card = document.getElementById('plan-card-' + plan);
        if (card) card.classList.add('ring-2', 'ring-primary');
    }

    function populateSummary() {
        document.getElementById('summary-will-type').innerText = selectedWillType;
        const name   = document.getElementById('full-name');
        const dob    = document.getElementById('dob');
        const mobile = document.getElementById('mobile');
        if (name)   document.getElementById('summary-name').innerText   = name.value   || '—';
        if (dob)    document.getElementById('summary-dob').innerText    = dob.value    || '—';
        if (mobile) document.getElementById('summary-mobile').innerText = mobile.value || '—';
        updateTotal(PLAN_PRICES[selectedPlan]);
        toggleAccordion('acc-personal');
    }

    function updateTotal(amount) {
        const gst   = Math.round(amount * 0.18 * 100) / 100;
        const total = amount + gst;
        const fmt   = n => '₹' + n.toLocaleString('en-IN', { minimumFractionDigits: 2 });
        document.getElementById('subtotal-display').innerText = '₹' + amount.toLocaleString('en-IN') + '.00';
        document.getElementById('gst-display').innerText      = fmt(gst);
        document.getElementById('total-display').innerText    = fmt(total);
    }

    // ============================================================
    //  RAZORPAY PAYMENT
    // ============================================================
    async function proceedToPayment() {
        if (!orderId) { showToast('Please complete all steps first.', 'error'); return; }
        try {
            setLoading(true);
            // Save plan first
            await apiPost('api/save_order.php', { step: 7, order_id: orderId, plan: selectedPlan });
            // Create Razorpay order
            const payData = await apiPost('api/create_payment.php', { order_id: orderId });
            setLoading(false);

            const options = {
                key:        payData.key_id,
                amount:     payData.amount,
                currency:   payData.currency,
                name:       'Will Banwalo',
                description:'Legal Will — ' + selectedWillType,
                order_id:   payData.razorpay_order_id,
                prefill:    payData.prefill || {},
                theme:      { color: '#08391b' },
                modal: {
                    ondismiss: () => showToast('Payment cancelled.', 'info'),
                },
                handler: async function(response) {
                    try {
                        setLoading(true);
                        const verify = await apiPost('api/verify_payment.php', {
                            order_id:              orderId,
                            razorpay_order_id:     response.razorpay_order_id,
                            razorpay_payment_id:   response.razorpay_payment_id,
                            razorpay_signature:    response.razorpay_signature,
                        });
                        // Success — show confirmation step
                        document.getElementById('order-id').innerText = 'Order ID: ' + (orderRef || payData.order_ref);
                        goToStep(8);
                        showToast('Payment successful! Your will has been submitted.', 'success');
                    } catch(err) {
                        showToast('Payment verification failed: ' + err.message, 'error');
                    } finally {
                        setLoading(false);
                    }
                },
            };

            const rzp = new Razorpay(options);
            rzp.on('payment.failed', function(resp) {
                showToast('Payment failed: ' + (resp.error.description || 'Unknown error'), 'error');
            });
            rzp.open();

        } catch(err) {
            setLoading(false);
            showToast(err.message, 'error');
        }
    }

    // ============================================================
    //  STEP 8: COPY ORDER ID
    // ============================================================
    function copyOrderId() {
        const id  = document.getElementById('order-id').innerText;
        navigator.clipboard.writeText(id).catch(() => {});
        const btn = document.getElementById('copy-btn');
        btn.innerText = 'check';
        setTimeout(() => btn.innerText = 'content_copy', 2000);
        showToast('Order ID copied!', 'success');
    }

    // ============================================================
    //  PLAN CARD CLICK HANDLERS (Step 7)
    // ============================================================
    document.addEventListener('DOMContentLoaded', () => {
        // Wire plan cards
        const planMap = { 'plan-card-basic': 'basic', 'plan-card-standard': 'standard', 'plan-card-premium': 'premium' };
        Object.entries(planMap).forEach(([cardId, plan]) => {
            const card = document.getElementById(cardId);
            if (card) card.addEventListener('click', () => selectPlan(plan));
        });

        // Wire "Proceed to Payment" button
        const payBtn = document.getElementById('proceed-to-payment-btn');
        if (payBtn) payBtn.addEventListener('click', proceedToPayment);

        // Input validation
        document.querySelectorAll('.input-peer').forEach(input => {
            input.addEventListener('blur', () => {
                if (input.value && input.checkValidity()) input.classList.add('border-primary');
                else input.classList.remove('border-primary');
            });
        });
        ['aadhaar', 'pin', 'mobile'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', e => e.target.value = e.target.value.replace(/[^0-9]/g, ''));
        });

        updateSidebar();
        updateTotal(PLAN_PRICES[selectedPlan]);
    });
    </script>
</body>
</html>

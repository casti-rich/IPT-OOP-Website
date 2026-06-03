<!DOCTYPE html>

<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Rent - Rhythm Link</title>
    <!-- Tailwind CSS v3 CDN with plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="CSS/Rent.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#007aff',
                        'on-primary': '#ffffff',
                        secondary: '#313a48',
                        'on-secondary': '#ffffff',
                        'secondary-container': '#131c2a',
                        'on-secondary-container': '#007aff',
                        surface: '#0a1421',
                        'on-surface': '#f0f0f0',
                        'surface-container-lowest': '#060e1c',
                        'surface-container-low': '#131c2a',
                        'surface-container': '#17212f',
                        'surface-container-high': '#212a39',
                        'surface-dim': '#0a1421',
                        'surface-bright': '#313a48',
                        'on-surface-variant': '#94a3b8',
                        outline: '#475569',
                        'outline-variant': '#1e293b',
                    },
                    spacing: {
                        'base': '1rem',
                        'margin-desktop': '2rem',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        headline: ['Inter', 'sans-serif'],
                    },
                    borderRadius: {
                        'round-eight': '8px',
                        '2xl': '1.5rem',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-surface text-on-surface font-sans antialiased">
    <!-- BEGIN: MainContainer -->
    <div class="min-h-screen flex flex-col" data-purpose="app-wrapper">
        <!-- BEGIN: Header (TopNavBar Component) -->
        <header class="w-full h-16 bg-surface border-b border-outline-variant/20 shadow-sm z-20" data-purpose="page-header">
            <div class="flex justify-center items-center px-margin-desktop w-full h-full relative">
                <!-- Brand/Product Name (Center) -->
                <div class="text-xl font-extrabold text-primary tracking-tight">
                    Rhythm Link
                </div>
            </div>
        </header>
        <!-- END: Header -->
        <!-- BEGIN: MainContentArea -->
        <main class="flex-grow flex overflow-hidden">
            <!--Sidebar (SideNavBar Component) -->
            <aside class="w-64 bg-surface-container-low border-r border-outline-variant/10 hidden md:block" data-purpose="sidebar-navigation">
                <div class="flex flex-col h-full p-base space-y-2">
                    <!-- Sidebar Header -->
                    <div class="flex items-center gap-3 mb-8 px-2">
                        <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-white">graphic_eq</span>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-primary leading-tight">Rhythm Link</div>
                        </div>
                    </div>
                    <!-- Navigation Tabs -->
                    <nav class="space-y-1 flex-grow">
                        <a class="flex items-center gap-4 text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface rounded-lg px-4 py-3 transition-all" onclick="document.getElementById('instrument rental').scrollIntoView({behavior:'smooth'})">
                            <span class="material-symbols-outlined">piano</span>
                            <span class="text-sm font-medium">Instruments</span>
                        </a>
                        <a class="flex items-center gap-4 text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface rounded-lg px-4 py-3 transition-all" onclick="document.getElementById('studio booking').scrollIntoView({behavior:'smooth'})">
                            <span class="material-symbols-outlined">mic_external_on</span>
                            <span class="text-sm font-medium">Studios</span>
                        </a>
                        <a class="flex items-center gap-4 text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface rounded-lg px-4 py-3 transition-all" href="#">
                            <span class="material-symbols-outlined">event_available</span>
                            <span class="text-sm font-medium">Bookings</span>
                        </a>
                    </nav>
                </div>
            </aside>
            <!-- END: Sidebar -->
            <!-- BEGIN: InteractiveContent -->
            <section class="flex-grow bg-surface p-8 lg:p-12 overflow-y-auto" data-purpose="main-interactive-grid">
                <div class="max-w-6xl mx-auto">
                    <!-- Page Label -->
                    <div class="mb-8">
                        <h2 class="text-on-surface-variant text-sm font-medium uppercase tracking-wider mb-2">Rent</h2>
                        <h1 class="text-3xl font-bold text-on-surface">Available Gear &amp; Spaces</h1>
                    </div>
                    <!-- BEGIN: PrimaryActions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12" data-purpose="primary-action-cards">
                        <!-- Instrument Rental Card -->
                        <button class="action-card bg-primary rounded-2xl flex flex-col items-center justify-center p-8 shadow-xl shadow-primary/20 group relative overflow-hidden" onclick="document.getElementById('instrument rental').scrollIntoView({behavior:'smooth'})">
                            <div class="absolute inset-0 bg-white/5 group-hover:bg-transparent transition-colors"></div>
                            <span class="material-symbols-outlined text-5xl mb-4 text-white/90">piano</span>
                            <span class="text-white text-2xl md:text-3xl font-bold tracking-tight relative z-10">Instrument Rental</span>
                            <span class="mt-2 text-white/70 text-sm font-medium">Professional grade equipment</span>
                        </button>
                        <!-- Book A Studio Card -->
                        <button class="action-card bg-primary rounded-2xl flex flex-col items-center justify-center p-8 shadow-xl shadow-primary/20 group relative overflow-hidden" onclick="document.getElementById('studio booking').scrollIntoView({behavior:'smooth'})">
                            <div class="absolute inset-0 bg-white/5 group-hover:bg-transparent transition-colors"></div>
                            <span class="material-symbols-outlined text-5xl mb-4 text-white/90">mic_external_on</span>
                            <span class="text-white text-2xl md:text-3xl font-bold tracking-tight relative z-10">Book A Studio</span>
                            <span class="mt-2 text-white/70 text-sm font-medium">Acoustically treated spaces</span>
                        </button>
                    </div>
                    <!-- END: PrimaryActions -->
                    <!-- BEGIN: Instrument Rental Section -->
                    <div class="mb-12" id="instrument rental">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-on-surface">Featured Instrument Rentals</h3>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6" data-purpose="instrument-grid">
                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">piano</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Yamaha C3 Grand</h4>
                                    <p class="text-xs text-on-surface-variant">$120 / day</p>
                                </div>
                            </div>
                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">music_note</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Fender Stratocaster</h4>
                                    <p class="text-xs text-on-surface-variant">$45 / day</p>
                                </div>
                            </div>
                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">headphones</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Beyerdynamic DT 770</h4>
                                    <p class="text-xs text-on-surface-variant">$15 / day</p>
                                </div>
                            </div>
                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">speaker</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Marshall JCM800</h4>
                                    <p class="text-xs text-on-surface-variant">$60 / day</p>
                                </div>
                            </div>

                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">radio</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Podcast Studio</h4>
                                    <p class="text-xs text-on-surface-variant">$40 / hr</p>
                                </div>
                            </div>

                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">radio</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Podcast Studio</h4>
                                    <p class="text-xs text-on-surface-variant">$40 / hr</p>
                                </div>
                            </div>

                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">radio</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Podcast Studio</h4>
                                    <p class="text-xs text-on-surface-variant">$40 / hr</p>
                                </div>
                            </div>

                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">radio</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Podcast Studio</h4>
                                    <p class="text-xs text-on-surface-variant">$40 / hr</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Instrument Rental Section -->
                    <!-- BEGIN: Studio Booking Section -->
                    <div class="mb-12" id="studio booking">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-on-surface">Premium Recording Spaces</h3>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6" data-purpose="studio-grid">
                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">mic_external_on</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Studio A (Live Room)</h4>
                                    <p class="text-xs text-on-surface-variant">$75 / hr</p>
                                </div>
                            </div>

                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">equalizer</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Mixing Suite B</h4>
                                    <p class="text-xs text-on-surface-variant">$50 / hr</p>
                                </div>
                            </div>

                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">settings_voice</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Vocal Booth</h4>
                                    <p class="text-xs text-on-surface-variant">$35 / hr</p>
                                </div>
                            </div>

                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">radio</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Podcast Studio</h4>
                                    <p class="text-xs text-on-surface-variant">$40 / hr</p>
                                </div>
                            </div>

                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">radio</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Podcast Studio</h4>
                                    <p class="text-xs text-on-surface-variant">$40 / hr</p>
                                </div>
                            </div>

                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">radio</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Podcast Studio</h4>
                                    <p class="text-xs text-on-surface-variant">$40 / hr</p>
                                </div>
                            </div>

                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">radio</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Podcast Studio</h4>
                                    <p class="text-xs text-on-surface-variant">$40 / hr</p>
                                </div>
                            </div>

                            <div class="grid-card bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:border-primary/50 transition-all cursor-pointer group flex flex-col p-4">
                                <div class="flex-grow bg-surface-container rounded-xl mb-4 overflow-hidden flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-4xl">radio</span>
                                </div>
                                <div class="px-1">
                                    <h4 class="text-sm font-bold text-on-surface mb-1">Podcast Studio</h4>
                                    <p class="text-xs text-on-surface-variant">$40 / hr</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Studio Booking Section -->
                </div>
            </section>
            <!-- END: InteractiveContent -->
        </main>
        <!-- END: MainContentArea -->
    </div>
    <!-- END: MainContainer -->
</body>

</html>

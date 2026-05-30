<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Rent - Instrument &amp; Studio Booking</title>
    <!-- Tailwind CSS v3 CDN with plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style data-purpose="custom-colors">
        /* Custom theme colors based on the provided image */
        :root {
            --bg-dark: #12122b;
            --sidebar-dark: #1a1a40;
            --content-blue: #153495;
            --card-vibrant: #2e37f2;
        }

        body {
            background-color: var(--bg-dark);
            color: white;
        }

        .bg-sidebar {
            background-color: var(--sidebar-dark);
        }

        .bg-content-area {
            background-color: var(--content-blue);
        }

        .bg-card-vibrant {
            background-color: var(--card-vibrant);
        }
    </style>
    <style data-purpose="layout-refinement">
        /* Ensuring proper aspect ratios and layout constraints */
        .action-card {
            aspect-ratio: 16 / 9;
            transition: transform 0.2s ease-in-out, background-color 0.2s;
        }

        .action-card:hover {
            transform: scale(1.02);
            filter: brightness(1.1);
        }

        .grid-card {
            aspect-ratio: 1 / 1;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- BEGIN: MainContainer -->
    <div class="min-h-screen flex flex-col" data-purpose="app-wrapper">
        <!-- BEGIN: Header -->
        <header class="p-4 bg-[#0a0a0a]" data-purpose="page-header">
            <h1 class="text-gray-400 text-sm font-medium">Rent</h1>
        </header>
        <!-- END: Header -->
        <!-- BEGIN: MainContentArea -->
        <main class="flex-grow flex overflow-hidden">
            <!-- BEGIN: SidebarPlaceholder -->
            <!-- Visual representation of the dark left-side panel seen in the reference -->
            <aside class="w-1/5 bg-sidebar hidden md:block" data-purpose="sidebar-navigation">
                <!-- Content for sidebar could go here -->
            </aside>
            <!-- END: SidebarPlaceholder -->
            <!-- BEGIN: InteractiveContent -->
            <section class="flex-grow bg-content-area p-8 lg:p-12" data-purpose="main-interactive-grid">
                <div class="max-w-6xl mx-auto">
                    <!-- BEGIN: PrimaryActions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12" data-purpose="primary-action-cards">
                        <!-- Instrument Rental Card -->
                        <button class="action-card bg-card-vibrant rounded-2xl flex items-center justify-center p-6 shadow-lg group">
                            <span class="text-white text-xl md:text-2xl font-bold tracking-tight">Instrument Rental</span>
                        </button>
                        <!-- Book A Studio Card -->
                        <button class="action-card bg-card-vibrant rounded-2xl flex items-center justify-center p-6 shadow-lg group">
                            <span class="text-white text-xl md:text-2xl font-bold tracking-tight">Book A Studio</span>
                        </button>
                    </div>
                    <!-- END: PrimaryActions -->
                    <!-- BEGIN: SecondaryGrid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4" data-purpose="secondary-grid-items">
                        <!-- Grid Item 1 -->
                        <div class="grid-card bg-card-vibrant rounded-xl opacity-90 hover:opacity-100 transition-opacity cursor-pointer"></div>
                        <!-- Grid Item 2 -->
                        <div class="grid-card bg-card-vibrant rounded-xl opacity-90 hover:opacity-100 transition-opacity cursor-pointer"></div>
                        <!-- Grid Item 3 -->
                        <div class="grid-card bg-card-vibrant rounded-xl opacity-90 hover:opacity-100 transition-opacity cursor-pointer"></div>
                        <!-- Grid Item 4 -->
                        <div class="grid-card bg-card-vibrant rounded-xl opacity-90 hover:opacity-100 transition-opacity cursor-pointer"></div>
                        <!-- Grid Item 5 -->
                        <div class="grid-card bg-card-vibrant rounded-xl opacity-90 hover:opacity-100 transition-opacity cursor-pointer"></div>
                        <!-- Grid Item 6 -->
                        <div class="grid-card bg-card-vibrant rounded-xl opacity-90 hover:opacity-100 transition-opacity cursor-pointer"></div>
                        <!-- Grid Item 7 -->
                        <div class="grid-card bg-card-vibrant rounded-xl opacity-90 hover:opacity-100 transition-opacity cursor-pointer"></div>
                        <!-- Grid Item 8 -->
                        <div class="grid-card bg-card-vibrant rounded-xl opacity-90 hover:opacity-100 transition-opacity cursor-pointer"></div>
                    </div>
                    <!-- END: SecondaryGrid -->
                </div>
            </section>
            <!-- END: InteractiveContent -->
        </main>
        <!-- END: MainContentArea -->
    </div>
    <!-- END: MainContainer -->
</body>

</html>
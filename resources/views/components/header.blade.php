<header class="fixed top-0 w-full z-50 bg-surface/70 backdrop-blur-xl shadow-[0_4px_20px_-5px_rgba(160,82,45,0.05)] transition-all duration-300" id="main-nav">
        <div class="max-w-[1280px] mx-auto px-4 md:px-margin-desktop flex justify-between items-center h-20">
            <!-- Brand Logo -->
            <a class="font-display-lg text-headline-lg tracking-tighter text-terracotta-earth transition-opacity duration-200 hover:opacity-80" href="#top">
                KEKELI
            </a>
            <!-- Navigation Links (Desktop) -->
            <nav class="hidden md:flex gap-8 items-center font-label-caps text-label-caps" aria-label="Navigation principale">
                @foreach ($navItems as $item)
                <a href="{{ $item['href'] }}" class="nav-link {{ $loop->first ? 'active' : '' }}">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </nav>
            <!-- Trailing Icons -->
            <div class="flex gap-4 items-center text-primary">
                <button aria-label="Search" class="hover:opacity-80 transition-opacity duration-200 cursor-pointer active:scale-95">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">search</span>
                </button>
                <button aria-label="Shopping Bag" class="hover:opacity-80 transition-opacity duration-200 cursor-pointer active:scale-95">
                    <span class="material-symbols-outlined" data-icon="shopping_bag" style="font-variation-settings: 'FILL' 0;">shopping_bag</span>
                </button>
                <button aria-label="Account" class="hover:opacity-80 transition-opacity duration-200 cursor-pointer active:scale-95">
                    <span class="material-symbols-outlined" data-icon="person" style="font-variation-settings: 'FILL' 0;">person</span>
                </button>
                <!-- Mobile Menu Toggle -->
                <button id="menu-toggle" aria-label="Menu" aria-expanded="false" aria-controls="mobile-menu" class="md:hidden hover:opacity-80 transition-opacity duration-200 cursor-pointer active:scale-95">
                    <span id="menu-icon" class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>
        <div id="mobile-menu" class="md:hidden hidden border-t border-terracotta-earth/10 bg-surface/95 backdrop-blur-xl">
            <div class="max-w-[1280px] mx-auto px-4 py-4 space-y-2">
                @foreach ($navItems as $item)
                <a href="{{ $item['href'] }}" class="mobile-nav-link {{ $loop->first ? 'active' : '' }}">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </div>
        </div>
    </header>

<template>
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="bg-white shadow-md sticky top-0 z-50">
            <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo & Name -->
                    <Link :href="route('home')" class="flex items-center space-x-3 flex-shrink-0">
                        <img src="/Logo Riyad.png" alt="Logo Riyadussalikin" class="h-12 w-12" />
                        <!-- Mobile: Nama Pondok -->
                        <div class="block lg:hidden">
                            <div class="text-emerald-700 font-bold text-base leading-tight">Riyadussalikin</div>
                            <div class="text-emerald-600 text-xs font-semibold">Padaherang</div>
                        </div>
                        <!-- Desktop: Nama lengkap -->
                        <div class="hidden lg:block">
                            <div class="text-emerald-700 font-bold text-lg leading-tight">Pondok Pesantren</div>
                            <div class="text-emerald-600 text-sm font-semibold">Riyadussalikin Padaherang</div>
                        </div>
                    </Link>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex items-center space-x-3 lg:space-x-6 xl:space-x-8">
                        <Link 
                            :href="route('dashboard')" 
                            :class="isActive('dashboard') ? 'navbar-link-active' : 'navbar-link'"
                        >
                            Dashboard
                        </Link>
                        <Link 
                            :href="route('home')" 
                            class="navbar-link"
                        >
                            Beranda Utama
                        </Link>
                        <Link 
                            :href="route('berita')" 
                            :class="isActive('berita') ? 'navbar-link-active' : 'navbar-link'"
                        >
                            Informasi
                        </Link>
                        <Link 
                            :href="route('kontak')" 
                            :class="isActive('kontak') ? 'navbar-link-active' : 'navbar-link'"
                        >
                            Kontak Panitia
                        </Link>

                        <Link 
                            :href="route('spmb.register')"
                            class="btn-primary"
                        >
                            Pendaftaran
                        </Link>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2">
                        <svg class="h-6 w-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Menu -->
                <div v-show="mobileMenuOpen" class="md:hidden pb-4">
                    <div class="flex flex-col space-y-3">
                        <Link :href="route('dashboard')" :class="isActive('dashboard') ? 'navbar-link-active' : 'navbar-link'">
                            Dashboard
                        </Link>
                        <Link :href="route('home')" class="navbar-link">
                            Beranda Utama
                        </Link>
                        <Link :href="route('berita')" :class="isActive('berita') ? 'navbar-link-active' : 'navbar-link'">
                            Informasi
                        </Link>
                        <Link :href="route('kontak')" :class="isActive('kontak') ? 'navbar-link-active' : 'navbar-link'">
                            Kontak Panitia
                        </Link>
                        <Link :href="route('spmb.register')" class="btn-primary text-center">
                            Pendaftaran
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="grow">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-emerald-900 text-white" style="box-shadow: 0 -8px 24px -4px rgba(0,0,0,0.25);">
            <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- About -->
                    <div>
                        <h3 class="text-xl font-bold mb-4">Pondok Pesantren Riyadussalikin</h3>
                        <p class="text-emerald-100 text-sm leading-relaxed">
                            Lembaga pendidikan Islam yang mengedepankan akhlak mulia, keilmuan yang mendalam, dan kemandirian santri.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-xl font-bold mb-4">SPMB Online</h3>
                        <div class="flex flex-col space-y-2">
                            <Link :href="route('dashboard')" class="text-emerald-100 hover:text-white transition-colors">Dashboard</Link>
                            <Link :href="route('spmb.register')" class="text-emerald-100 hover:text-white transition-colors">Formulir Pendaftaran</Link>
                            <Link :href="route('berita')" class="text-emerald-100 hover:text-white transition-colors">Informasi & Pengumuman</Link>
                            <Link :href="route('kontak')" class="text-emerald-100 hover:text-white transition-colors">Kontak Panitia</Link>
                            <a href="/" class="text-emerald-100 hover:text-white transition-colors pt-4 border-t border-emerald-800">Beranda Utama Website</a>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-xl font-bold mb-4">Kontak</h3>
                        <div class="text-emerald-100 text-sm space-y-2">
                            <p v-if="$page.props.kontak?.alamat">{{ $page.props.kontak.alamat }}</p>
                            <p v-else>Jl. Raya Padaherang, Pangandaran</p>
                            
                            <p v-if="$page.props.kontak?.whatsapp">WhatsApp: {{ $page.props.kontak.whatsapp }}</p>
                            <p v-else-if="$page.props.kontak?.telepon">Telepon: {{ $page.props.kontak.telepon }}</p>
                            <p v-else>WhatsApp: +62 812-3456-7890</p>
                            
                            <p v-if="$page.props.kontak?.email">Email: {{ $page.props.kontak.email }}</p>
                            <p v-else>Email: info@riyadussalikin.sch.id</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-emerald-800 mt-8 pt-8 text-center text-emerald-200 text-sm">
                    <p>&copy; {{ new Date().getFullYear() }} Pondok Pesantren Riyadussalikin Padaherang. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const mobileMenuOpen = ref(false);
const isDropdownOpen = ref(false);
const isTentangOpen = ref(false);
const page = usePage();

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const isActive = (routeName) => {
    const currentUrl = page.url;
    
    // Home page
    if (routeName === 'home') {
        return currentUrl === '/' || currentUrl === '';
    }
    
    // Other pages
    const routePath = '/' + routeName;
    return currentUrl === routePath || currentUrl.startsWith(routePath + '/') || currentUrl.startsWith(routePath + '?');
};
</script>

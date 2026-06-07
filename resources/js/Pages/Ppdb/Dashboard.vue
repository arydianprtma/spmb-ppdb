<style>
@media print {
    @page {
        size: A4 portrait;
        margin: 0;
    }
    body {
        margin: 0;
        padding: 0;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .print-container, .statement-print-container {
        display: none !important;
    }
    body.print-card-active .print-container {
        display: block !important;
        width: 210mm;
        height: 297mm;
        padding: 10mm 15mm !important;
        margin: 0 auto;
        background: white !important;
        box-sizing: border-box;
        position: relative;
        overflow: hidden;
        font-family: 'Times New Roman', Times, serif !important;
    }
    body.print-statement-active .statement-print-container {
        display: block !important;
        width: 210mm;
        height: 297mm;
        padding: 15mm 20mm !important;
        margin: 0 auto;
        background: white !important;
        box-sizing: border-box;
        position: relative;
        overflow: hidden;
        font-family: 'Times New Roman', Times, serif !important;
        color: black;
    }
}

/* Custom Celebration Animations for Graduation */
@keyframes float-slow {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-12px) rotate(4deg); }
}
@keyframes float-fast {
    0%, 100% { transform: translateY(0) rotate(0deg) scale(1); }
    50% { transform: translateY(-16px) rotate(-8deg) scale(1.05); }
}
@keyframes sparkle {
    0%, 100% { opacity: 0.2; transform: scale(0.8); }
    50% { opacity: 0.9; transform: scale(1.2); }
}
@keyframes scaleUp {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-float-slow {
    animation: float-slow 7s ease-in-out infinite;
}
.animate-float-fast {
    animation: float-fast 5s ease-in-out infinite;
}
.animate-sparkle {
    animation: sparkle 4s ease-in-out infinite;
}
.animate-scaleUp {
    animation: scaleUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>

<template>
    <Head title="Dashboard Pendaftaran" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-emerald-50 print:hidden">
        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-2 sm:gap-3">
                    <img src="/logo_pondok.png" alt="Logo" class="h-8 w-8 sm:h-10 sm:w-10 flex-shrink-0" />
                    <div class="font-['Poppins']">
                        <div class="text-emerald-700 font-extrabold text-xs sm:text-sm leading-tight tracking-wide">PONDOK PESANTREN</div>
                        <div class="text-emerald-600 font-semibold text-[9px] sm:text-xs tracking-wider">Riyadussalikin Padaherang</div>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- Profile Link (Avatar & Name) -->
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full overflow-hidden bg-emerald-100 border border-emerald-200 flex items-center justify-center flex-shrink-0">
                            <img v-if="$page.props.auth.user.avatar_url" :src="$page.props.auth.user.avatar_url" loading="lazy" class="w-full h-full object-cover" />
                            <span v-else class="text-emerald-600 font-bold text-xs">{{ $page.props.auth.user.name.charAt(0).toUpperCase() }}</span>
                        </div>
                        <span class="text-gray-700 text-sm font-semibold hidden md:block">{{ $page.props.auth.user.name }}</span>
                    </div>

                    <div class="h-5 w-px bg-gray-200"></div>

                    <!-- Explicit Edit Profile Button -->
                    <Link :href="route('profile.edit')" 
                        class="group flex items-center gap-1.5 text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 px-2.5 py-1.5 rounded-xl transition-all duration-200">
                        <svg class="w-4 h-4 flex-shrink-0 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Edit Profil</span>
                    </Link>

                    <div class="h-5 w-px bg-gray-200"></div>

                    <!-- Keluar Link -->
                    <Link :href="route('logout')" method="post" as="button"
                        class="text-xs sm:text-sm font-semibold text-red-500 hover:text-red-700 transition-colors px-2">
                        Keluar
                    </Link>
                </div>
            </div>
        </header>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

            <!-- Welcome Card -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-600 p-8 shadow-xl shadow-emerald-200">
                <div class="absolute top-0 right-0 w-56 h-56 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                <div class="relative">
                    <p class="text-emerald-100 text-sm font-medium mb-1">Assalamu'alaikum,</p>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white mb-4">{{ pendaftaran?.siswa?.nama_lengkap ?? $page.props.auth.user.name }}</h1>
                    <div class="flex flex-wrap gap-3">
                        <div class="bg-white/20 rounded-2xl px-4 py-2">
                            <p class="text-emerald-100 text-xs">Periode</p>
                            <p class="text-white font-extrabold text-lg tracking-tight">{{ ppdbSetting?.tahunAjaran ?? '-' }}</p>
                        </div>
                        <div class="bg-white/20 rounded-2xl px-4 py-2">
                            <p class="text-emerald-100 text-xs">No. Registrasi</p>
                            <p class="text-white font-extrabold text-lg tracking-widest">{{ pendaftaran?.no_reg ?? '-' }}</p>
                        </div>
                        <div class="bg-white/20 rounded-2xl px-4 py-2">
                            <p class="text-emerald-100 text-xs">Tingkat</p>
                            <p class="text-white font-bold text-lg uppercase">{{ pendaftaran?.tingkat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transition Banner (SMP to SMA) -->
            <div v-if="canTransition" class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-600 via-teal-700 to-cyan-800 p-6 sm:p-10 shadow-2xl shadow-emerald-900/30 text-white animate-fadeIn border border-white/20">
                <!-- Floating Sparks/Stars and Graduation Caps for Celebration Effect -->
                <div class="absolute top-10 left-10 w-2 h-2 bg-yellow-300 rounded-full blur-[1px] animate-sparkle" style="animation-delay: 0.5s"></div>
                <div class="absolute top-24 right-24 w-3.5 h-3.5 bg-cyan-300 rounded-full blur-[1px] opacity-75 animate-sparkle" style="animation-delay: 1.2s"></div>
                <div class="absolute bottom-12 left-1/3 w-3 h-3 bg-white/30 rounded-full blur-[1px] animate-sparkle" style="animation-delay: 2.1s"></div>
                <div class="absolute bottom-20 right-1/4 w-2 h-2 bg-yellow-200 rounded-full blur-[1px] animate-sparkle" style="animation-delay: 0.8s"></div>
                
                <div class="absolute top-1/3 left-12 text-white/10 select-none pointer-events-none animate-float-slow">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
                <div class="absolute top-8 right-20 text-white/10 select-none pointer-events-none animate-float-fast" style="animation-delay: 1.5s">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
                <div class="absolute bottom-6 right-12 text-white/5 select-none pointer-events-none animate-float-slow" style="animation-delay: 3s">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>

                <div class="absolute -top-12 -right-12 w-64 h-64 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-12 -left-12 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative flex flex-col lg:flex-row gap-8 items-stretch justify-between">
                    <!-- Left Section: Celebration Card -->
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-[10px] font-extrabold uppercase tracking-widest text-yellow-300 mb-4 border border-white/10 shadow-sm">
                                <svg class="w-3.5 h-3.5 text-yellow-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                                Prestasi Kelulusan SMP
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold mb-3 leading-tight tracking-tight">Selamat atas Kelulusan Anda!</h2>
                            <p class="text-emerald-100/90 text-sm sm:text-base leading-relaxed max-w-xl mb-6">
                                Sebagai alumni <strong>SMP Pondok Pesantren Riyadussalikin</strong>, Anda telah dinyatakan <strong>LULUS</strong> dan berhak untuk langsung melanjutkan pendidikan ke jenjang <strong>SMA Ksatria Nusantara</strong>.
                            </p>
                        </div>

                        <!-- SMP School Graduation Badge Card (Glassmorphism) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-white/5 backdrop-blur-md rounded-2xl p-4 border border-white/10 max-w-lg shadow-inner">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-emerald-200/70 text-[10px] font-bold uppercase tracking-wider">Asal Sekolah</p>
                                    <p class="text-white text-xs sm:text-sm font-extrabold">SMP Riyadussalikin</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-yellow-400/20 flex items-center justify-center text-yellow-300">
                                    <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-emerald-200/70 text-[10px] font-bold uppercase tracking-wider">Status Kelulusan</p>
                                    <span class="inline-flex items-center gap-1.5 text-xs font-black text-yellow-300">
                                        <span class="w-2 h-2 rounded-full bg-yellow-300 animate-ping"></span>
                                        LULUS / ALUMNI
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Section: Transition Actions -->
                    <div class="lg:w-80 flex flex-col justify-center items-stretch bg-white/5 backdrop-blur-md rounded-3xl p-6 border border-white/10 shadow-lg relative">
                        <div class="text-center mb-5">
                            <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center mx-auto mb-3 shadow-inner">
                                <svg class="w-9 h-9 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-black tracking-tight text-white">Lanjut Ke SMA</h3>
                            <p class="text-emerald-100/70 text-xs mt-1 leading-normal">
                                Salin seluruh biodata, data keluarga, dan berkas Anda ke pendaftaran SMA secara instan dengan 1 klik.
                            </p>
                        </div>

                        <button @click="startTransitionFlow" :disabled="isTransitioning"
                            class="w-full inline-flex items-center justify-center gap-3 bg-gradient-to-r from-yellow-400 to-amber-500 text-gray-900 py-3.5 rounded-2xl font-black text-sm sm:text-base hover:from-yellow-300 hover:to-amber-400 transition-all duration-300 shadow-xl shadow-amber-950/20 hover:scale-102 active:scale-98 disabled:opacity-70 disabled:cursor-not-allowed">
                            <span v-if="isTransitioning" class="inline-block animate-spin h-5 w-5 border-2 border-gray-900 border-t-transparent rounded-full"></span>
                            <svg v-else class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                            </svg>
                            Lanjut Daftar ke SMA
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistik Pendaftar -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div class="bg-white rounded-3xl p-4 sm:p-5 border border-emerald-100 shadow-sm flex items-center gap-3 sm:gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Pendaftar SMP</p>
                        <p class="text-2xl font-black text-gray-800">{{ stats.total_smp }} <span class="text-sm font-medium text-gray-400">Siswa</span></p>
                    </div>
                </div>
                <div class="bg-white rounded-3xl p-4 sm:p-5 border border-blue-100 shadow-sm flex items-center gap-3 sm:gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center flex-shrink-0 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Pendaftar SMA</p>
                        <p class="text-2xl font-black text-gray-800">{{ stats.total_sma }} <span class="text-sm font-medium text-gray-400">Siswa</span></p>
                    </div>
                </div>
            </div>

            <!-- Timeline Alur Pendaftaran -->
            <div v-if="pendaftaran" class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Alur Seleksi Penerimaan Santri</h2>
                        <p class="text-xs text-gray-400">Ikuti setiap tahapan seleksi hingga selesai</p>
                    </div>
                </div>

                <!-- Timeline steps -->
                <div class="space-y-0">
                    <div v-for="(stage, index) in registrationFlow" :key="stage.key" class="flex gap-4">

                        <!-- Connector column -->
                        <div class="flex flex-col items-center w-10 flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 border-2 transition-all duration-300 relative"
                                :class="{
                                    'bg-emerald-500 border-emerald-500 text-white shadow-lg shadow-emerald-200': stageStatus(stage) === 'done',
                                    'bg-white border-emerald-500 text-emerald-600 ring-4 ring-emerald-100': stageStatus(stage) === 'active',
                                    'bg-white border-gray-200 text-gray-300': stageStatus(stage) === 'pending',
                                    'bg-red-100 border-red-300 text-red-500': stageStatus(stage) === 'rejected',
                                }">
                                <span class="text-xs font-bold">{{ index + 1 }}</span>
                                
                                <!-- Checkmark badge for completed steps -->
                                <div v-if="stageStatus(stage) === 'done'" class="absolute -top-1 -right-1 w-4 h-4 bg-white rounded-full flex items-center justify-center shadow-sm">
                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                
                                <svg v-if="stageStatus(stage) === 'rejected'" class="absolute inset-0 m-auto w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <div v-if="stageStatus(stage) === 'active'" class="absolute -bottom-1 -right-1 w-3 h-3 bg-emerald-500 rounded-full border-2 border-white animate-pulse"></div>
                            </div>
                            <!-- Vertical connector line -->
                            <div v-if="index < registrationFlow.length - 1"
                                class="w-0.5 flex-1 my-1 min-h-4 transition-all duration-500"
                                :class="stageStatus(stage) === 'done' ? 'bg-emerald-400' : 'bg-gray-200'">
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 pb-5" :class="index === registrationFlow.length - 1 ? 'pb-0' : ''">
                            <div class="rounded-2xl p-4 border transition-all duration-300"
                                :class="{
                                    'bg-emerald-50 border-emerald-200': stageStatus(stage) === 'done',
                                    'bg-white border-emerald-300 shadow-md shadow-emerald-100': stageStatus(stage) === 'active',
                                    'bg-gray-50 border-gray-100': stageStatus(stage) === 'pending',
                                    'bg-red-50 border-red-200': stageStatus(stage) === 'rejected',
                                }">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1">
                                        <p class="font-bold text-sm"
                                            :class="{
                                                'text-emerald-700': stageStatus(stage) === 'done',
                                                'text-emerald-900': stageStatus(stage) === 'active',
                                                'text-gray-400': stageStatus(stage) === 'pending',
                                                'text-red-700': stageStatus(stage) === 'rejected',
                                            }">{{ stage.label }}</p>
                                        <p class="text-xs mt-0.5 leading-relaxed"
                                            :class="{
                                                'text-emerald-600': stageStatus(stage) === 'done',
                                                'text-gray-600': stageStatus(stage) === 'active',
                                                'text-gray-400': stageStatus(stage) === 'pending',
                                                'text-red-500': stageStatus(stage) === 'rejected',
                                            }">{{ stageDesc(stage) }}</p>
                                    </div>
                                    <!-- Badge: berbeda untuk jadwal_tes vs tes_berlangsung -->
                                    <span v-if="stage.key === 'tes' && pendaftaran.status === 'tes_berlangsung'"
                                        class="flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full flex-shrink-0 bg-orange-500 text-white">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        Berlangsung
                                    </span>
                                    <span v-else-if="stage.key === 'tes' && pendaftaran.status === 'jadwal_tes'"
                                        class="text-xs font-bold px-2.5 py-1 rounded-full flex-shrink-0 bg-blue-100 text-blue-700">
                                        Terjadwal
                                    </span>
                                    <span v-else
                                        class="text-xs font-bold px-2.5 py-1 rounded-full flex-shrink-0"
                                        :class="{
                                            'bg-emerald-200 text-emerald-800': stageStatus(stage) === 'done',
                                            'bg-emerald-500 text-white': stageStatus(stage) === 'active',
                                            'bg-gray-100 text-gray-400': stageStatus(stage) === 'pending',
                                            'bg-red-200 text-red-700': stageStatus(stage) === 'rejected',
                                        }">
                                        {{ stageStatus(stage) === 'done' ? 'Selesai' : stageStatus(stage) === 'active' ? 'Proses' : stageStatus(stage) === 'rejected' ? 'Tidak Lulus' : 'Menunggu' }}
                                    </span>
                                </div>

                                <!-- Catatan admin only on active stage -->
                                <div v-if="stageStatus(stage) === 'active' && pendaftaran.catatan_admin"
                                    class="mt-3 bg-amber-50 rounded-xl border border-amber-200 p-3">
                                    <p class="text-xs text-amber-600 font-bold mb-1">Catatan Panitia:</p>
                                    <p class="text-sm text-gray-700">{{ pendaftaran.catatan_admin }}</p>
                                </div>

                                <!-- Jadwal Tes detail card — jadwal_tes: upcoming style, tes_berlangsung: LIVE style -->
                                <div v-if="stage.key === 'tes' && stageStatus(stage) === 'active' && pendaftaran.jadwal_tes_tanggal"
                                    class="mt-3 rounded-xl overflow-hidden border"
                                    :class="pendaftaran.status === 'tes_berlangsung' ? 'border-orange-300' : 'border-blue-200'">
                                    <!-- Header -->
                                    <div class="px-4 py-2.5 flex items-center gap-2"
                                        :class="pendaftaran.status === 'tes_berlangsung' ? 'bg-orange-500' : 'bg-blue-500'">
                                        <span v-if="pendaftaran.status === 'tes_berlangsung'"
                                            class="w-2 h-2 rounded-full bg-white animate-ping flex-shrink-0"></span>
                                        <p class="text-xs font-bold text-white tracking-wide uppercase">
                                            {{ pendaftaran.status === 'tes_berlangsung' ? 'Ujian Sedang Berlangsung' : 'Informasi Jadwal Ujian' }}
                                        </p>
                                    </div>
                                    <!-- Body -->
                                    <div class="p-4 space-y-3 bg-white">
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="rounded-xl p-3"
                                                :class="pendaftaran.status === 'tes_berlangsung' ? 'bg-orange-50' : 'bg-blue-50'">
                                                <p class="text-xs font-semibold mb-0.5"
                                                    :class="pendaftaran.status === 'tes_berlangsung' ? 'text-orange-600' : 'text-blue-600'">Tanggal</p>
                                                <p class="text-sm font-bold text-gray-800">{{ formatTanggal(pendaftaran.jadwal_tes_tanggal) }}</p>
                                            </div>
                                            <div class="rounded-xl p-3"
                                                :class="pendaftaran.status === 'tes_berlangsung' ? 'bg-orange-50' : 'bg-blue-50'">
                                                <p class="text-xs font-semibold mb-0.5"
                                                    :class="pendaftaran.status === 'tes_berlangsung' ? 'text-orange-600' : 'text-blue-600'">Jam Mulai</p>
                                                <p class="text-sm font-bold text-gray-800">{{ formatJam(pendaftaran.jadwal_tes_jam) }}</p>
                                            </div>
                                        </div>
                                        <div v-if="pendaftaran.jadwal_tes_lokasi" class="rounded-xl p-3"
                                            :class="pendaftaran.status === 'tes_berlangsung' ? 'bg-orange-50' : 'bg-blue-50'">
                                            <p class="text-xs font-semibold mb-0.5"
                                                :class="pendaftaran.status === 'tes_berlangsung' ? 'text-orange-600' : 'text-blue-600'">Lokasi</p>
                                            <p class="text-sm font-bold text-gray-800">{{ pendaftaran.jadwal_tes_lokasi }}</p>
                                        </div>
                                        <div v-if="pendaftaran.jadwal_tes_jenis && pendaftaran.jadwal_tes_jenis.length">
                                            <p class="text-xs font-semibold mb-2"
                                                :class="pendaftaran.status === 'tes_berlangsung' ? 'text-orange-600' : 'text-blue-600'">Jenis Ujian</p>
                                            <div class="flex flex-wrap gap-2">
                                                <span v-for="jenis in pendaftaran.jadwal_tes_jenis" :key="jenis"
                                                    class="text-xs font-semibold px-3 py-1 rounded-full"
                                                    :class="pendaftaran.status === 'tes_berlangsung' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800'">
                                                    {{ tesJenisLabel[jenis] ?? jenis }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Klasifikasi badge when accepted -->
                                <div v-if="['diterima_ula','diterima_idadiyah','diterima_wustho','diterima_ulya','diterima'].includes(pendaftaran.status) && stage.key === 'hasil'"
                                    class="mt-3 inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-md shadow-emerald-200">
                                    🎉 Selamat! Kelas: {{ klasifikasiLabel }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Registration Yet -->
            <div v-else class="bg-white rounded-3xl shadow-lg border border-orange-100 p-8 text-center">
                <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Pendaftaran</h3>
                <p class="text-gray-500 mb-6">Anda belum mengisi formulir pendaftaran. Silakan lengkapi data Anda sekarang.</p>
                <Link :href="route('ppdb.register')" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-8 py-3 rounded-2xl font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Isi Formulir Sekarang
                </Link>
            </div>

            <!-- Data Ringkasan -->
            <div v-if="pendaftaran" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 space-y-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-50">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h3 class="font-bold text-gray-800">Data Diri Siswa</h3>
                    </div>
                    <dl class="space-y-3">
                        <div class="flex justify-between gap-4">
                            <dt class="text-sm text-gray-500 flex-shrink-0">Nama Lengkap</dt>
                            <dd class="text-sm font-semibold text-gray-800 text-right">{{ pendaftaran.siswa?.nama_lengkap }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-sm text-gray-500 flex-shrink-0">Tempat, Tgl Lahir</dt>
                            <dd class="text-sm font-semibold text-gray-800 text-right">{{ pendaftaran.siswa?.tempat_lahir }}, {{ pendaftaran.siswa?.tanggal_lahir }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-sm text-gray-500 flex-shrink-0">NISN</dt>
                            <dd class="text-sm font-semibold text-gray-800 text-right">{{ pendaftaran.siswa?.nisn ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-sm text-gray-500 flex-shrink-0">No. HP</dt>
                            <dd class="text-sm font-semibold text-gray-800 text-right">{{ pendaftaran.siswa?.no_hp }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 space-y-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-50">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="font-bold text-gray-800">Kelengkapan Berkas</h3>
                    </div>
                    <dl class="space-y-3">
                        <div v-for="item in berkasStatus" :key="item.key" class="flex justify-between items-center gap-4">
                            <dt class="text-sm text-gray-500 flex-shrink-0">{{ item.label }}</dt>
                            <dd>
                                <span v-if="pendaftaran.berkas?.[item.key]" class="text-xs font-bold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">✓ Ada</span>
                                <span v-else class="text-xs font-bold bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">— Belum</span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Download Surat Pernyataan Section -->
            <div v-if="pendaftaran" class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-gray-50">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l3-3m-3 3l-3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Unduh Formulir Surat Pernyataan</h3>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">Silakan unduh, cetak, isi, dan kumpulkan formulir berikut saat registrasi/daftar ulang</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Surat Pernyataan Orang Tua (Semua Pendaftar) -->
                    <a href="/assets/Dokument/surat_pernyataan_orang_tua.pdf" download target="_blank"
                        class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100 border border-slate-100 rounded-2xl transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs flex-shrink-0">PDF</div>
                            <div class="text-left">
                                <p class="font-bold text-sm text-gray-800">Surat Pernyataan Orang Tua</p>
                                <p class="text-xs text-gray-400">Untuk Orang Tua / Wali Siswa</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </a>

                    <!-- Surat Pernyataan Siswa SMA (Hanya untuk pendaftar SMA) -->
                    <a v-if="isSma" href="/assets/Dokument/formulir_surat_pernyataan_siswa_sma.pdf" download target="_blank"
                        class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100 border border-slate-100 rounded-2xl transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs flex-shrink-0">PDF</div>
                            <div class="text-left">
                                <p class="font-bold text-sm text-gray-800">Surat Pernyataan Siswa SMA</p>
                                <p class="text-xs text-gray-400">Khusus Calon Siswa SMA</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </a>
                </div>
            </div>

            <!-- Action Buttons -->
            <div v-if="pendaftaran" class="flex flex-wrap gap-4">
                <Link :href="route('ppdb.register')"

                    class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit / Perbarui Data
                </Link>
                <button @click="printCard" :disabled="isDownloading"
                    class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-white text-gray-700 border-2 border-gray-200 px-6 py-3 rounded-2xl font-bold hover:bg-gray-50 transition-all disabled:opacity-75 disabled:cursor-not-allowed">
                    <svg v-if="isDownloading" class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    {{ printButtonText }}
                </button>
            </div>

        </div>
    </div>

    <!-- Printable Registration Card (Visible ONLY on Print) -->
    <!-- MODIFIKASI VERSI ATM (AMATI TIRU MODIFIKASI) -->
    <div class="hidden print:block print-container bg-white text-black" style="font-family: 'Times New Roman', Times, serif;">
        <div class="flex items-start justify-between animate-fadeIn">
            <!-- Logo Kiri -->
            <img
                loading="eager"
                :src="isSma ? '/logo_provinsi.png' : '/Logo_Kabupaten.png'"
                :alt="isSma ? 'Logo Provinsi' : 'Logo Kabupaten'"
                class="h-24 w-24 object-contain"
            />
            
            <!-- Teks Tengah (Header Instansi) -->
            <div class="text-center flex-1 mx-4" style="font-family: 'Times New Roman', Times, serif; color: black;">
                <template v-if="isSma">
                    <div style="font-size: 12pt; font-weight: bold; line-height: 1.25;">
                        <div>PEMERINTAH PROVINSI JAWA BARAT</div>
                        <div>DINAS PENDIDIKAN</div>
                        <div>YAYASAN AL MASRUHIYAH</div>
                        <div>SMA KSATRIA NUSANTARA</div>
                    </div>
                </template>
                <template v-else>
                    <div style="font-size: 12pt; font-weight: bold; line-height: 1.25;">
                        <div>PEMERINTAH KABUPATEN PANGANDARAN</div>
                        <div>DINAS PENDIDIKAN KEPEMUDAAN DAN OLAHRAGA</div>
                        <div>YAYASAN AL MASRUHIYAH</div>
                        <div>SMP DHARMA KSATRIA</div>
                    </div>
                </template>
            </div>
            
            <!-- Logo Kanan -->
            <img
                loading="eager"
                :src="isSma ? '/assets/Logo_Sekolah/sma_ksatria_nusantara.png' : '/assets/Logo_Sekolah/smp_dharma_ksatria.png'"
                :alt="isSma ? 'Logo SMA' : 'Logo SMP'"
                class="h-24 w-24 object-contain"
            />
        </div>

        <!-- Teks Alamat Bawah (Full-Width, ditarik naik dengan margin negatif agar rapat tanpa space) -->
        <div class="text-center w-full pb-2" style="font-family: 'Times New Roman', Times, serif; color: black; margin-top: -14px; position: relative; z-index: 10;">
            <template v-if="isSma">
                <div style="font-size: 11pt; line-height: 1.25; font-weight: normal;">
                    <div>No Izin Operasional : 421.3/0442-Disdikbudpora/2016 &nbsp; NPSN : 69935515</div>
                    <div>Jalan Paledah Dusun Patinggen II RT 020 / RW 005 Desa Karangpawitan Kecamatan Padaherang</div>
                    <div>Kabupaten Pangandaran Kode Pos 46384 No HP 087725807558*</div>
                    <div>email: <span class="text-blue-600 underline">smaksatrianusantara01@gmail.com</span></div>
                </div>
            </template>
            <template v-else>
                <div style="font-size: 11pt; line-height: 1.25; font-weight: normal;">
                    <div>No Izin Operasional : 421/3143/Disdikbudpora/2016 &nbsp; NPSN : 69950605</div>
                    <div>Jalan Paledah Dusun Patinggen II RT 020 / RW 005 Desa Karangpawitan Kecamatan Padaherang</div>
                    <div>Kabupaten Pangandaran Kode Pos 46384 No HP 087725807558</div>
                    <div>email: <span class="text-blue-600 underline">smpdharmaksatria@gmail.com</span></div>
                </div>
            </template>
        </div>

        <div class="space-y-[1px] mb-4 w-full">
            <div class="border-t border-black"></div>
            <div class="border-t-[3px] border-black"></div>
        </div>

        <!-- 2. KARTU BUKTI PENDAFTARAN (Centered, Bold, No Underline) -->
        <h3 class="text-center font-bold uppercase mb-6" style="font-size: 14pt; tracking: 0.05em;">
            KARTU BUKTI PENDAFTARAN
        </h3>

        <!-- 3. DATA DIRI & FOTO (Align Left, Vertical Colons, Borderless) -->
        <div class="grid grid-cols-12 gap-6 items-start mb-6" style="font-size: 11pt;">
            <!-- Details -->
            <div class="col-span-8">
                <table style="width: 100%; table-layout: fixed; border: none; border-collapse: collapse; font-family: 'Times New Roman', Times, serif;">
                    <tbody>
                        <tr>
                            <td style="width: 180px; vertical-align: top; padding: 0 0 8px 0;" class="font-bold uppercase">NOMOR REGISTRASI</td>
                            <td style="width: 15px; vertical-align: top; padding: 0 0 8px 0;" class="font-bold text-center">:</td>
                            <td style="vertical-align: top; padding: 0 0 8px 0;" class="font-bold uppercase">{{ pendaftaran?.no_reg }}</td>
                        </tr>
                        <tr>
                            <td style="width: 180px; vertical-align: top; padding: 0 0 8px 0;" class="font-bold uppercase">NAMA LENGKAP</td>
                            <td style="width: 15px; vertical-align: top; padding: 0 0 8px 0;" class="font-bold text-center">:</td>
                            <td style="vertical-align: top; padding: 0 0 8px 0;" class="font-bold uppercase">{{ pendaftaran?.siswa?.nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td style="width: 180px; vertical-align: top; padding: 0 0 8px 0;" class="font-bold uppercase">TEMPAT, TGL LAHIR</td>
                            <td style="width: 15px; vertical-align: top; padding: 0 0 8px 0;" class="font-bold text-center">:</td>
                            <td style="vertical-align: top; padding: 0 0 8px 0;" class="font-bold uppercase">
                                {{ pendaftaran?.siswa?.tempat_lahir }}, {{ formatTanggal(pendaftaran?.siswa?.tanggal_lahir) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 180px; vertical-align: top; padding: 0 0 8px 0;" class="font-bold uppercase">NISN / NIK</td>
                            <td style="width: 15px; vertical-align: top; padding: 0 0 8px 0;" class="font-bold text-center">:</td>
                            <td style="vertical-align: top; padding: 0 0 8px 0;" class="font-bold uppercase">
                                {{ pendaftaran?.siswa?.nisn ?? '-' }} / {{ pendaftaran?.siswa?.nik ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 180px; vertical-align: top; padding: 0 0 8px 0;" class="font-bold uppercase">ASAL SEKOLAH</td>
                            <td style="width: 15px; vertical-align: top; padding: 0 0 8px 0;" class="font-bold text-center">:</td>
                            <td style="vertical-align: top; padding: 0 0 8px 0;" class="font-bold uppercase">{{ pendaftaran?.siswa?.asal_sekolah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="width: 180px; vertical-align: top; padding: 0 0 8px 0;" class="font-bold uppercase">TINGKAT SEKOLAH</td>
                            <td style="width: 15px; vertical-align: top; padding: 0 0 8px 0;" class="font-bold text-center">:</td>
                            <td style="vertical-align: top; padding: 0 0 8px 0;" class="font-bold uppercase">{{ pendaftaran?.tingkat }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pas Foto Box (3x4 cm) -->
            <div class="col-span-4 flex flex-col items-center">
                <div class="w-[3cm] h-[4cm] border border-black flex flex-col items-center justify-center bg-white p-0.5">
                    <img v-if="$page.props.auth.user.avatar_url" :src="$page.props.auth.user.avatar_url" loading="eager" class="w-full h-full object-cover" />
                    <div v-else class="text-center p-2 text-[10px] font-bold uppercase leading-tight w-full h-full flex items-center justify-center">
                        PAS FOTO
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. TABEL STATUS (Simple black border, White header background, Capitalized) -->
        <div class="mb-6">
            <table class="w-full border-collapse border border-black text-[11pt]">
                <thead>
                    <tr class="font-bold text-center">
                        <th class="border border-black p-2 uppercase w-1/4">PENDAFTARAN</th>
                        <th class="border border-black p-2 uppercase">PILIHAN SEKOLAH / TINGKAT</th>
                        <th class="border border-black p-2 uppercase w-1/4">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td class="border border-black p-3 align-middle">
                            <div class="font-bold uppercase">Gelombang I</div>
                            <div class="text-[9pt] mt-0.5 italic">{{ formatTanggal(pendaftaran?.tanggal_daftar) }}</div>
                        </td>
                        <td class="border border-black p-3 text-left align-middle">
                            <div class="font-bold uppercase">{{ ppdbSetting?.kartuHeader2 || 'PP. RIYADUSSALIKIN PADAHERANG' }}</div>
                            <div class="text-[9pt] mt-0.5">Jenjang Pendidikan: <span class="uppercase font-bold">{{ pendaftaran?.tingkat }}</span></div>
                        </td>
                        <td class="border border-black p-3 align-middle font-bold uppercase">
                            {{ stageStatusLabel(pendaftaran?.status) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- 5. INFORMASI PENTING (List standard, No background/border box) -->
        <div class="mb-6 text-[11pt]">
            <p class="font-bold mb-2">
                Informasi Penting Sebagai Calon Siswa/Santri:
            </p>
            <div class="space-y-1.5 leading-normal" style="font-family: 'Times New Roman', Times, serif;">
                <div class="flex items-start">
                    <span class="w-5 flex-shrink-0">1.</span>
                    <span>Kartu Pendaftaran Ini Wajib Di Bawa Saat Seleksi Atau Daftar Ulang</span>
                </div>
                <div class="flex items-start">
                    <span class="w-5 flex-shrink-0">2.</span>
                    <span>Membawa Dokumen Asli (KTP Orang Tua, KK, Akte Kelahiran)</span>
                </div>
                <div class="flex items-start">
                    <span class="w-5 flex-shrink-0">3.</span>
                    <span>Membawa Seluruh Berkas Dokumen Fisik Yang Diunggah Di <a href="https://ppdb.riyadussalikin.my.id/" target="_blank" class="text-blue-600 underline">https://ppdb.riyadussalikin.my.id/</a></span>
                </div>
                <div class="flex items-start">
                    <span class="w-5 flex-shrink-0">4.</span>
                    <span>Berpakaian Rapih, Sopan, Menutup Aurat, Dan Menjaga Adab</span>
                </div>
                <div class="flex items-start">
                    <span class="w-5 flex-shrink-0">5.</span>
                    <span>Pantau Terus Status Pendaftaran Anda Melalui <a href="https://ppdb.riyadussalikin.my.id/dashboard" target="_blank" class="text-blue-600 underline">https://ppdb.riyadussalikin.my.id/dashboard</a></span>
                </div>
                <div class="flex items-start">
                    <span class="w-5 flex-shrink-0">6.</span>
                    <span>Membawa Surat Pernyataan Orang Tua yang telah diisi dan ditandatangani di atas meterai 10.000</span>
                </div>
                <div v-if="isSma" class="flex items-start">
                    <span class="w-5 flex-shrink-0">7.</span>
                    <span>Membawa Surat Pernyataan Siswa SMA yang telah diisi dan ditandatangani di atas meterai 10.000</span>
                </div>
            </div>
        </div>

        <!-- 6. QR CODE & TANDA TANGAN (Aligned, Signature Table) -->
        <div class="grid grid-cols-12 gap-4 items-end mt-8 text-[11pt]">
            <!-- QR Code -->
            <div class="col-span-4 flex flex-col items-center gap-1">
                <div class="border border-black p-1 bg-white" style="width: 3cm; height: 3cm;">
                    <div class="w-full h-full bg-white flex items-center justify-center border border-dashed border-gray-300 rounded overflow-hidden">
                        <img v-if="qrCodeUrl" :src="qrCodeUrl" loading="eager" class="w-full h-full object-contain" />
                        <div v-else class="grid grid-cols-4 gap-1 w-14 h-14 opacity-30">
                            <div v-for="i in 16" :key="i" class="bg-emerald-900" :style="{ opacity: Math.random() > 0.4 ? 1 : 0.1 }"></div>
                        </div>
                    </div>
                </div>
                <p class="text-[9pt] font-bold text-center tracking-wider uppercase mt-1">E-VERIFIKATION QR</p>
            </div>

            <!-- Signature Table -->
            <div class="col-span-8">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="font-bold text-center text-[10pt]">
                            <th class="p-2 w-1/2 align-middle font-bold">
                                MENGETAHUI,<br>ORANG TUA/WALI
                            </th>
                            <th class="p-2 w-1/2 align-middle uppercase font-bold">
                                PANGANDARAN, {{ formatTanggal(new Date()).toUpperCase() }}<br>CALON PESERTA
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-2 h-[75px] text-center align-bottom text-[10pt]">
                                <div class="mb-1">
                                    ({{ namaOrtuWali || '........................................' }})
                                </div>
                            </td>
                            <td class="p-2 h-[75px] text-center align-bottom text-[10pt] font-bold">
                                <div class="mb-1 underline">
                                    {{ pendaftaran?.siswa?.nama_lengkap }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 7. WATERMARK FOOTER -->
        <div class="absolute text-center text-gray-400 uppercase font-bold" style="left: 15mm; right: 15mm; bottom: 8mm; font-size: 9pt; line-height: 1.3;">
            <div>SIMPANLAH LEMBAR PENDAFTARAN INI SEBAGAI BUKTI PENDAFTARAN ANDA</div>
            <div>SISTEM PPDB ONLINE PP. RIYADUSSALIKIN</div>
        </div>
    </div>

    <!-- Printable Student Statement Letter (Visible ONLY on Print if active) -->
    <div class="hidden print:block statement-print-container bg-white text-black" style="font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.5; color: black; padding: 15mm 20mm;">
        <!-- Header / Kop Surat (Matches the attached image) -->
        <div class="text-center" style="font-family: 'Times New Roman', Times, serif;">
            <div style="font-size: 16pt; font-weight: bold; letter-spacing: 0.5px;">YAYASAN AL - MASRUHIYAH</div>
            <div style="font-size: 9pt; margin-top: 2px;">Akta Notaris : 01/2009. NPWP : 02.733.936.5-442.000</div>
            <div style="font-size: 9pt;">SK. MENHUNKAM Nomor : AHU-5057.AH.01.04.Tahun 2010</div>
            <div style="font-size: 11pt; font-weight: bold; margin-top: 2px;">KARANGPAWITAN PADAHERANG PANGANDARAN</div>
            <div style="font-size: 8.5pt; margin-top: 2px; line-height: 1.3;">
                <div>Sekretariat : Jln. Paledah Dusun Patinggen II 020/005 Desa Karangpawitan</div>
                <div>Kec. Padaherang Kab. Pangandaran Provinsi Jawa Barat Kode Pos 46384</div>
                <div>Telp. (0265) 650738 / 087725807558</div>
            </div>
        </div>

        <!-- Double border under Kop -->
        <div class="space-y-[1px] mb-4 w-full">
            <div class="border-t border-black"></div>
            <div class="border-t-[3px] border-black"></div>
        </div>

        <!-- Registration Number Boxes (Top Right) -->
        <div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 25px; font-size: 11pt;">
            <span style="margin-right: 8px;">No Reg :</span>
            <div style="display: flex; gap: 0;">
                <span v-for="(char, idx) in formatRegNoForBoxes" :key="idx" 
                      style="border: 1px solid #000; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; font-family: monospace; font-size: 10pt; font-weight: bold; background: white;">
                    {{ char }}
                </span>
            </div>
        </div>

        <!-- Title -->
        <h3 class="text-center font-bold uppercase mb-8" style="font-size: 12pt; letter-spacing: 0.5px;">
            SURAT PERNYATAAN SISWA
        </h3>

        <!-- Body -->
        <div style="margin-bottom: 15px;">
            Yang bertanda tangan dibawah ini saya :
        </div>

        <table style="width: 100%; border: none; margin-left: 20px; margin-bottom: 25px; border-collapse: collapse;">
            <tr style="height: 30px;">
                <td style="width: 150px; vertical-align: top;">Nama Lengkap</td>
                <td style="width: 15px; vertical-align: top;">:</td>
                <td style="vertical-align: top; font-weight: bold; text-transform: uppercase;">{{ pendaftaran?.siswa?.nama_lengkap }}</td>
            </tr>
            <tr style="height: 30px;">
                <td style="vertical-align: top;">NISN</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">{{ pendaftaran?.siswa?.nisn || '..................................................................' }}</td>
            </tr>
            <tr style="height: 45px;">
                <td style="vertical-align: top;">Alamat Lengkap</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top; line-height: 1.4;">
                    <div>{{ pendaftaran?.siswa?.alamat }}</div>
                    <div style="margin-top: 15px; border-bottom: 1px dotted #888; width: 95%;"></div>
                    <div style="margin-top: 20px; border-bottom: 1px dotted #888; width: 95%;"></div>
                </td>
            </tr>
        </table>

        <!-- Declarations -->
        <div style="text-align: justify; text-indent: 30px; margin-bottom: 15px; line-height: 1.6;">
            Menyatakan bersedia mengikuti segala kegiatan terprogram yang ada di sekolah SMA Ksatria Nusantara. Apabila saya tidak mengikuti kegiatan terprogram maka saya tetap berkewajiban untuk membayar biaya kegiatan terprogram tersebut.
        </div>

        <div style="text-align: justify; text-indent: 30px; margin-bottom: 40px; line-height: 1.6;">
            Demikian pernyataan ini saya buat dengan sadar dan sungguh-sungguh tanpa ada paksaan.
        </div>

        <!-- Signature Area -->
        <div style="float: right; width: 280px; text-align: center; margin-top: 20px;">
            <div>Padaherang, .................................................... 20...</div>
            <div style="height: 110px; display: flex; flex-direction: column; justify-content: center; align-items: center; margin: 15px 0;">
                <div style="border: 1px solid #000; padding: 12px 18px; font-size: 8pt; color: #555; background: #fff; font-family: sans-serif;">
                    Materai 10.000
                </div>
            </div>
            <div style="border-bottom: 1px solid #000; width: 220px; margin: 0 auto; font-weight: bold; text-transform: uppercase; padding-bottom: 3px;">
                {{ pendaftaran?.siswa?.nama_lengkap }}
            </div>
            <div style="margin-top: 6px;">Siswa</div>
        </div>
    </div>

    <!-- Stepped Transition Progress Modal -->
    <div v-if="showTransitionModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity"></div>

        <!-- Modal Content -->
        <div class="relative bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-gray-100 overflow-hidden transform transition-all animate-scaleUp z-10">
            <!-- Top Decoration Circle -->
            <div class="absolute -top-12 -right-12 w-36 h-36 bg-emerald-50 rounded-full -z-10"></div>
            
            <div class="text-center mb-6">
                <div class="w-16 h-16 rounded-3xl bg-emerald-50 flex items-center justify-center mx-auto mb-4 text-emerald-600 shadow-sm border border-emerald-100">
                    <svg class="w-8 h-8 text-emerald-600 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-xl font-extrabold text-gray-900">Memproses Pindah Jenjang</h3>
                <p class="text-xs text-gray-500 mt-1 leading-normal">
                    Mohon tunggu sebentar, sistem sedang memindahkan data akademik Anda dari SMP ke jenjang SMA.
                </p>
            </div>

            <!-- Steps List -->
            <div class="space-y-4 mb-6">
                <div v-for="(step, sIdx) in transitionSteps" :key="sIdx"
                    class="flex items-center gap-3 p-3.5 rounded-2xl border transition-all duration-300"
                    :class="{
                        'bg-emerald-50 border-emerald-200 text-emerald-900': step.status === 'done',
                        'bg-white border-emerald-300 shadow-sm ring-2 ring-emerald-50': step.status === 'active',
                        'bg-gray-50 border-gray-100 text-gray-400': step.status === 'pending',
                        'bg-red-50 border-red-200 text-red-900': step.status === 'error',
                    }">
                    <!-- Icon/Indicator -->
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                        :class="{
                            'bg-emerald-500 text-white': step.status === 'done',
                            'bg-white border-2 border-emerald-500 text-emerald-500': step.status === 'active',
                            'bg-gray-100 text-gray-300': step.status === 'pending',
                            'bg-red-500 text-white': step.status === 'error',
                        }">
                        <svg v-if="step.status === 'done'" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span v-else-if="step.status === 'active'" class="inline-block animate-spin h-4 w-4 border-2 border-emerald-500 border-t-transparent rounded-full"></span>
                        <svg v-else-if="step.status === 'error'" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span v-else class="text-xs font-bold">{{ sIdx + 1 }}</span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-extrabold uppercase tracking-wider"
                            :class="step.status === 'pending' ? 'text-gray-400' : step.status === 'done' ? 'text-emerald-800' : step.status === 'error' ? 'text-red-800' : 'text-emerald-900'">
                            {{ step.title }}
                        </p>
                        <p class="text-[11px] mt-0.5 leading-normal"
                            :class="step.status === 'pending' ? 'text-gray-300' : 'text-gray-500'">
                            {{ step.desc }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Progress bar -->
            <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden mb-2">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-full transition-all duration-500"
                    :style="{ width: `${transitionProgress}%` }"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import html2pdf from 'html2pdf.js';
import Swal from 'sweetalert2';

const isTransitioning = ref(false);
const showTransitionModal = ref(false);
const transitionProgress = ref(0);
const transitionSteps = ref([
    { title: 'Validasi Syarat', desc: 'Memverifikasi status kelulusan SMP Anda...', status: 'pending' },
    { title: 'Inisialisasi SMA', desc: 'Mempersiapkan pendaftaran SMA Ksatria Nusantara...', status: 'pending' },
    { title: 'Menyalin Biodata', desc: 'Menyalin biodata lengkap, data orang tua & wali...', status: 'pending' },
    { title: 'Mengkloning Dokumen', desc: 'Menyalin seluruh berkas dokumen pendaftaran...', status: 'pending' },
]);

const isDownloading = ref(false);
const isDownloadingStatement = ref(false);

const printButtonText = computed(() => {
    return isDownloading.value ? 'Mengunduh...' : 'Unduh Bukti Pendaftaran';
});

const formatRegNoForBoxes = computed(() => {
    const reg = props.pendaftaran?.no_reg || '';
    const cleanReg = reg.replace(/[^a-zA-Z0-9]/g, '');
    const suffix = cleanReg.slice(-8) || '        ';
    return suffix.padEnd(8, ' ').split('');
});

const printCard = async () => {
    if (isDownloading.value) return;
    isDownloading.value = true;
    
    try {
        const element = document.querySelector('.print-container');
        const originalClass = element.className;
        const originalStyle = element.getAttribute('style') || '';
        
        // Render visible and force exact A4 dimensions with relative positioning
        element.className = 'block bg-white text-black relative';
        element.style.fontFamily = "'Times New Roman', Times, serif";
        element.style.width = '210mm';
        element.style.height = '297mm';
        element.style.padding = '10mm 15mm';
        element.style.boxSizing = 'border-box';
        element.style.overflow = 'hidden';
        
        // Force browser to load non-lazy images in the print layout before rendering
        await new Promise(resolve => setTimeout(resolve, 300));
        
        const opt = {
            margin:       0,
            filename:     `Bukti_Pendaftaran_${props.pendaftaran?.no_reg || 'PPDB'}.pdf`,
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, logging: false },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        
        await html2pdf().from(element).set(opt).save();
        
        // Restore original class and inline styles
        element.className = originalClass;
        element.setAttribute('style', originalStyle);
    } catch (error) {
        console.error('PDF Generation failed:', error);
        
        // Fallback to window.print if html2pdf fails
        document.body.classList.add('print-card-active');
        document.body.classList.remove('print-statement-active');
        setTimeout(() => {
            window.print();
            document.body.classList.remove('print-card-active');
        }, 100);
    } finally {
        isDownloading.value = false;
    }
};

const printStatement = async () => {
    const isMobile = window.innerWidth < 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    if (isMobile) {
        if (isDownloadingStatement.value) return;
        isDownloadingStatement.value = true;
        
        try {
            const element = document.querySelector('.statement-print-container');
            const originalClass = element.className;
            const originalStyle = element.getAttribute('style') || '';
            
            element.className = 'block bg-white text-black relative';
            element.style.fontFamily = "'Times New Roman', Times, serif";
            element.style.width = '210mm';
            element.style.height = '297mm';
            element.style.padding = '15mm 20mm';
            element.style.boxSizing = 'border-box';
            element.style.overflow = 'hidden';
            element.style.lineHeight = '1.5';
            
            await new Promise(resolve => setTimeout(resolve, 300));
            
            const opt = {
                margin:       0,
                filename:     `Surat_Pernyataan_${props.pendaftaran?.siswa?.nama_lengkap || 'Siswa'}.pdf`,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, logging: false },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            
            await html2pdf().from(element).set(opt).save();
            
            element.className = originalClass;
            element.setAttribute('style', originalStyle);
        } catch (error) {
            console.error('PDF Generation failed:', error);
            window.print();
        } finally {
            isDownloadingStatement.value = false;
        }
    } else {
        document.body.classList.add('print-statement-active');
        document.body.classList.remove('print-card-active');
        
        setTimeout(() => {
            window.print();
            document.body.classList.remove('print-statement-active');
        }, 100);
    }
};

const props = defineProps({
    pendaftaran: Object,
    qrCodeUrl: String,
    stats: Object,
    ppdbSetting: Object,
    canTransition: Boolean,
});

const startTransitionFlow = () => {
    if (isTransitioning.value) return;
    
    Swal.fire({
        title: 'Lanjut Daftar ke SMA?',
        text: 'Sistem akan membuat pendaftaran SMA baru dan menyalin seluruh biodata Anda dari data SMP sebelumnya.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Lanjutkan',
        cancelButtonText: 'Batal'
    }).then(async (result) => {
        if (result.isConfirmed) {
            isTransitioning.value = true;
            showTransitionModal.value = true;
            transitionProgress.value = 0;
            
            // Reset steps
            transitionSteps.value.forEach(s => s.status = 'pending');
            
            try {
                // Step 1: Validasi Syarat
                transitionSteps.value[0].status = 'active';
                transitionProgress.value = 15;
                await new Promise(r => setTimeout(r, 600));
                transitionSteps.value[0].status = 'done';
                
                // Step 2: Inisialisasi SMA
                transitionSteps.value[1].status = 'active';
                transitionProgress.value = 40;
                await new Promise(r => setTimeout(r, 700));
                transitionSteps.value[1].status = 'done';
                
                // Step 3: Menyalin Biodata
                transitionSteps.value[2].status = 'active';
                transitionProgress.value = 65;
                await new Promise(r => setTimeout(r, 600));
                transitionSteps.value[2].status = 'done';
                
                // Step 4: Mengkloning Dokumen
                transitionSteps.value[3].status = 'active';
                transitionProgress.value = 90;
                await new Promise(r => setTimeout(r, 700));
                
                // Call post route
                router.post(route('ppdb.transition'), {}, {
                    onSuccess: () => {
                        transitionSteps.value[3].status = 'done';
                        transitionProgress.value = 100;
                        setTimeout(() => {
                            showTransitionModal.value = false;
                            isTransitioning.value = false;
                        }, 500);
                    },
                    onError: (errors) => {
                        transitionSteps.value[3].status = 'error';
                        const activeStep = transitionSteps.value.find(s => s.status === 'active');
                        if (activeStep) activeStep.status = 'error';
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Pindah Jenjang',
                            text: errors.message || 'Terjadi kesalahan saat melakukan perpindahan jenjang.',
                            confirmButtonColor: '#10b981',
                        }).then(() => {
                            showTransitionModal.value = false;
                            isTransitioning.value = false;
                        });
                    },
                    onFinish: () => {
                        setTimeout(() => {
                            isTransitioning.value = false;
                        }, 2000);
                    }
                });
            } catch (err) {
                showTransitionModal.value = false;
                isTransitioning.value = false;
            }
        }
    });
};

// Logo: pakai dari settings jika ada, fallback ke default
const logoUrl = computed(() => props.ppdbSetting?.kartuLogo || '/logo_pondok.png');
const onLogoError = (e) => { e.target.src = '/logo_pondok.png'; };

const isSma = computed(() => {
    return props.pendaftaran?.tingkat?.toLowerCase() === 'sma';
});

onMounted(() => {
    if (props.pendaftaran?.id) {
        window.Echo.channel(`pendaftaran.${props.pendaftaran.id}`)
            .listen('.updated', (e) => {
                // Refresh data dashboard secara otomatis
                router.reload({
                    only: ['pendaftaran', 'stats'],
                    preserveScroll: true,
                });
            });
    }
});

onUnmounted(() => {
    if (props.pendaftaran?.id) {
        window.Echo.leaveChannel(`pendaftaran.${props.pendaftaran.id}`);
    }
});

// Alur resmi penerimaan santri pondok
const registrationFlow = [
    {
        key: 'formulir',
        label: 'Pendaftaran & Isi Formulir',
        desc: 'Lengkapi formulir pendaftaran and upload seluruh berkas yang diperlukan.',
        doneStatuses: ['pending', 'jadwal_tes', 'tes_berlangsung', 'wawancara', 'diterima_ula', 'diterima_idadiyah', 'diterima_wustho', 'diterima_ulya', 'diterima', 'ditolak', 'mengundurkan_diri'],
        activeStatuses: [],
    },
    {
        key: 'tes',
        label: 'Seleksi & Ujian Masuk',
        desc: 'Psikotes · Tes Akademik · Tes Baca Al-Qur\'an · Tes Ibadah',
        doneStatuses: ['wawancara', 'diterima_ula', 'diterima_idadiyah', 'diterima_wustho', 'diterima_ulya', 'diterima', 'ditolak', 'mengundurkan_diri'],
        activeStatuses: ['jadwal_tes', 'tes_berlangsung'],
    },
    {
        key: 'wawancara',
        label: 'Wawancara Orang Tua & Calon Santri',
        desc: 'Sesi wawancara bersama orang tua dan calon santri/siswa oleh panitia seleksi.',
        doneStatuses: ['diterima_ula', 'diterima_idadiyah', 'diterima_wustho', 'diterima_ulya', 'diterima', 'ditolak', 'mengundurkan_diri'],
        activeStatuses: ['wawancara'],
    },
    {
        key: 'hasil',
        label: 'Pengumuman Hasil Seleksi',
        desc: 'Penerimaan berdasarkan hasil klasifikasi: Ula (Dasar), Idadiyah (Persiapan), Wustho (Menengah), atau Ulya (Lanjutan).',
        doneStatuses: ['diterima_ula', 'diterima_idadiyah', 'diterima_wustho', 'diterima_ulya', 'diterima'],
        activeStatuses: [],
        rejectedStatuses: ['ditolak', 'mengundurkan_diri'],
    },
];

const stageStatus = (stage) => {
    const s = props.pendaftaran?.status;
    if (!s) return 'pending';
    if (stage.rejectedStatuses?.includes(s)) return 'rejected';
    if (stage.doneStatuses?.includes(s)) return 'done';
    if (stage.activeStatuses?.includes(s)) return 'active';
    return 'pending';
};

const stageDesc = (stage) => {
    const s = props.pendaftaran?.status;
    if (stage.key === 'formulir' && s === 'pending') return 'Formulir Anda telah dikirim. Panitia sedang melakukan verifikasi berkas.';
    if (stage.key === 'tes') {
        if (s === 'jadwal_tes') return 'Jadwal ujian masuk telah ditentukan. Pantau informasi lebih lanjut dari panitia.';
        if (s === 'tes_berlangsung') return 'Proses ujian sedang berlangsung. Harap hadir tepat waktu sesuai jadwal yang diberikan.';
    }
    if (stage.key === 'wawancara' && s === 'wawancara') return 'Anda dijadwalkan untuk sesi wawancara. Hadir bersama orang tua/wali sesuai jadwal panitia.';
    if (stage.key === 'hasil') {
        if (s === 'ditolak') return 'Mohon maaf, Anda belum berhasil dalam seleksi ini. Silakan hubungi panitia untuk informasi lebih lanjut.';
        if (s === 'mengundurkan_diri') return 'Anda telah mengundurkan diri dari proses pendaftaran PPDB.';
    }
    return stage.desc;
};

const klasifikasiLabel = computed(() => {
    const map = {
        diterima_ula: 'Ula (Dasar)',
        diterima_idadiyah: 'Idadiyah (Persiapan)',
        diterima_wustho: 'Wustho (Menengah)',
        diterima_ulya: 'Ulya (Lanjutan)',
        diterima: 'Diterima',
    };
    return map[props.pendaftaran?.status] ?? '';
});

// Nama orang tua/wali: prioritas ayah (jika ada), lalu ibu (jika ada), lalu wali
const namaOrtuWali = computed(() => {
    const ortu = props.pendaftaran?.orange_tua ?? props.pendaftaran?.orangTua ?? [];
    const ayah = Array.isArray(ortu) ? ortu.find(o => o.jenis === 'ayah') : null;
    const ibu  = Array.isArray(ortu) ? ortu.find(o => o.jenis === 'ibu')  : null;
    const wali = props.pendaftaran?.wali;

    if (ayah?.nama) return ayah.nama;
    if (ibu?.nama)  return ibu.nama;
    if (wali?.nama) return wali.nama;
    return '';
});

const stageStatusLabel = (status) => {
    const map = {
        pending: 'Menunggu Verifikasi',
        jadwal_tes: 'Terjadwal Ujian',
        tes_berlangsung: 'Sedang Ujian',
        wawancara: 'Sesi Wawancara',
        diterima: 'Diterima',
        diterima_ula: 'Diterima (Ula)',
        diterima_idadiyah: 'Diterima (Idadiyah)',
        diterima_wustho: 'Diterima (Wustho)',
        diterima_ulya: 'Diterima (Ulya)',
        ditolak: 'Tidak Lulus',
        mengundurkan_diri: 'Mengundurkan Diri',
    };
    return map[status] || 'Menunggu';
};

const tesJenisLabel = {
    psikotes:   'Psikotes',
    akademik:   'Tes Akademik',
    baca_quran: 'Tes Baca Al-Quran',
    sholat:     'Tes Ibadah',
};

const formatTanggal = (tgl) => {
    if (!tgl) return '-';
    const d = new Date(tgl);
    return d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
};

const formatJam = (jam) => {
    if (!jam) return '-';
    // jam format: "HH:MM:SS" or "HH:MM"
    return jam.substring(0, 5) + ' WIB';
};

const berkasStatus = [
    { key: 'ijazah_skhu',        label: 'Ijazah / SKHU' },
    { key: 'akte_kelahiran',     label: 'Akte Kelahiran' },
    { key: 'ktp_orang_tua',      label: 'KTP Orang Tua' },
    { key: 'kartu_keluarga',     label: 'Kartu Keluarga' },
    { key: 'surat_sehat',        label: 'Surat Sehat' },
    { key: 'surat_kelakuan_baik',label: 'Surat Kelakuan Baik' },
];
</script>

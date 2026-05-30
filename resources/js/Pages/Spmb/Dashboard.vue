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
    .print-container {
        width: 210mm;
        height: 297mm;
        padding: 10mm 15mm !important;
        margin: 0 auto;
        background: white !important;
        box-sizing: border-box;
        position: relative;
        overflow: hidden;
    }
}
</style>

<template>
    <Head title="Dashboard Pendaftaran" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-emerald-50 print:hidden">
        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-2 sm:gap-3">
                    <img src="/Logo Riyad.png" alt="Logo" class="h-8 w-8 sm:h-10 sm:w-10 flex-shrink-0" />
                    <div>
                        <div class="text-emerald-700 font-bold text-xs sm:text-sm leading-tight">Pondok Pesantren</div>
                        <div class="text-emerald-600 text-[10px] sm:text-xs">Riyadussalikin Padaherang</div>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <Link :href="route('profile.edit')" class="flex items-center gap-1 sm:gap-2 hover:bg-gray-50 px-2 sm:px-3 py-1 sm:py-1.5 rounded-xl transition-colors">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full overflow-hidden bg-emerald-100 border border-emerald-200 flex items-center justify-center flex-shrink-0">
                            <img v-if="$page.props.auth.user.avatar_url" :src="$page.props.auth.user.avatar_url" loading="lazy" class="w-full h-full object-cover" />
                            <span v-else class="text-emerald-600 font-bold text-xs">{{ $page.props.auth.user.name.charAt(0).toUpperCase() }}</span>
                        </div>
                        <span class="text-gray-700 text-sm font-semibold hidden sm:block">{{ $page.props.auth.user.name }}</span>
                    </Link>
                    <div class="h-5 w-px bg-gray-200 hidden sm:block"></div>
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
                            <p class="text-white font-extrabold text-lg tracking-tight">{{ spmbSetting?.tahunAjaran ?? '-' }}</p>
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
                                <div v-if="['diterima_ula','diterima_wustho','diterima_ulya','diterima'].includes(pendaftaran.status) && stage.key === 'hasil'"
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

            <!-- Action Buttons -->
            <div v-if="pendaftaran" class="flex flex-wrap gap-4">
                <Link :href="route('ppdb.register')"

                    class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit / Perbarui Data
                </Link>
                <button @click="printCard"
                    class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-white text-gray-700 border-2 border-gray-200 px-6 py-3 rounded-2xl font-bold hover:bg-gray-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Bukti
                </button>
            </div>

        </div>
    </div>

    <!-- Printable Registration Card (Visible ONLY on Print) -->
    <!-- MODIFIKASI VERSI ATM (AMATI TIRU MODIFIKASI) -->
    <div class="hidden print:block print-container bg-white text-black font-sans">
        <!-- 1. Kop Surat dinamis dari settings -->
        <div class="flex items-center justify-between border-b-4 border-double border-black pb-3 mb-4">
            <img
                :src="logoUrl"
                alt="Logo Pesantren"
                class="h-16 w-16 object-contain"
                @error="onLogoError"
            />
            <div class="text-center flex-1 mx-6">
                <h2 class="text-[10px] font-bold tracking-widest uppercase text-gray-800">KARTU TANDA PESERTA</h2>
                <h1 class="text-lg font-black tracking-tight uppercase text-emerald-900 leading-tight">PPDB ONLINE {{ spmbSetting?.tahunAjaran || '2024/2025' }}</h1>
                <p class="text-xs font-bold text-emerald-700 mt-0.5 uppercase">{{ spmbSetting?.kartuHeader2 || 'Pondok Pesantren Riyadussalikin Padaherang' }}</p>
                <p class="text-[8px] text-gray-600 mt-0.5 italic">{{ spmbSetting?.kartuAlamat || 'Jl. Raya Padaherang, Kab. Pangandaran, Jawa Barat' }}</p>
            </div>
            <img
                :src="logoUrl"
                alt="Logo Pesantren"
                class="h-16 w-16 object-contain"
                @error="onLogoError"
            />
        </div>

        <!-- 2. TIRU: Judul dengan garis bawah -->
        <h3 class="text-center text-sm font-black tracking-[0.3em] uppercase mb-6 underline underline-offset-4 decoration-2">
            KARTU BUKTI PENDAFTARAN
        </h3>

        <!-- 3. MODIFIKASI: Layout Grid untuk Data dan Foto -->
        <div class="grid grid-cols-12 gap-6 items-start mb-6">
            <!-- Details -->
            <div class="col-span-8 space-y-2 text-[11px] border-2 border-black p-3 rounded-xl">
                <div class="grid grid-cols-12 gap-2 border-b border-gray-200 pb-1.5">
                    <span class="col-span-4 text-gray-800 font-semibold uppercase tracking-tighter">Nomor Registrasi</span>
                    <span class="col-span-8 font-black text-emerald-800 text-xs tracking-widest">: {{ pendaftaran?.no_reg }}</span>
                </div>
                <div class="grid grid-cols-12 gap-2 border-b border-gray-200 pb-1.5">
                    <span class="col-span-4 text-gray-800 font-semibold uppercase tracking-tighter">Nama Lengkap</span>
                    <span class="col-span-8 font-bold uppercase">: {{ pendaftaran?.siswa?.nama_lengkap }}</span>
                </div>
                <div class="grid grid-cols-12 gap-2 border-b border-gray-200 pb-1.5">
                    <span class="col-span-4 text-gray-800 font-semibold uppercase tracking-tighter">Tempat, Tgl Lahir</span>
                    <span class="col-span-8 font-bold">: {{ pendaftaran?.siswa?.tempat_lahir }}, {{ formatTanggal(pendaftaran?.siswa?.tanggal_lahir) }}</span>
                </div>
                <div class="grid grid-cols-12 gap-2 border-b border-gray-200 pb-1.5">
                    <span class="col-span-4 text-gray-800 font-semibold uppercase tracking-tighter">NISN / NIK</span>
                    <span class="col-span-8 font-bold">: {{ pendaftaran?.siswa?.nisn ?? '-' }} / {{ pendaftaran?.siswa?.nik ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-12 gap-2 border-b border-gray-200 pb-1.5">
                    <span class="col-span-4 text-gray-800 font-semibold uppercase tracking-tighter">Asal Sekolah</span>
                    <span class="col-span-8 font-bold">: {{ pendaftaran?.siswa?.asal_sekolah ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-12 gap-2">
                    <span class="col-span-4 text-gray-800 font-semibold uppercase tracking-tighter">Tingkat Sekolah</span>
                    <span class="col-span-8 font-black text-emerald-700 uppercase">: {{ pendaftaran?.tingkat }}</span>
                </div>
            </div>

            <!-- Photo (Tiru Image 1) -->
            <div class="col-span-4 flex flex-col items-center gap-2">
                <div class="w-32 h-40 border-4 border-gray-200 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center p-1 shadow-inner">
                    <img v-if="$page.props.auth.user.avatar_url" :src="$page.props.auth.user.avatar_url" class="w-full h-full object-cover rounded" />
                    <div v-else class="text-center p-4 text-[10px] text-gray-300 font-black uppercase leading-tight border-2 border-dashed border-gray-200 w-full h-full flex items-center justify-center">
                        PAS FOTO<br>3 X 4
                    </div>
                </div>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Pas Foto Pendaftar</p>
            </div>
        </div>

        <!-- 4. Tabel Status dengan border hitam -->
        <div class="mb-6">
            <table class="w-full border-collapse border border-black text-[10px]">
                <thead class="bg-emerald-700 text-white">
                    <tr>
                        <th class="border border-black p-2 text-left uppercase tracking-widest font-black w-1/4">Pendaftaran</th>
                        <th class="border border-black p-2 text-left uppercase tracking-widest font-black">Pilihan Sekolah / Tingkat</th>
                        <th class="border border-black p-2 text-left uppercase tracking-widest font-black w-1/4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr class="font-medium">
                        <td class="border border-black p-3">
                            <p class="font-black text-emerald-800 uppercase">Gelombang I</p>
                            <p class="text-[9px] text-gray-700 mt-0.5 italic">{{ formatTanggal(pendaftaran?.tanggal_daftar) }}</p>
                        </td>
                        <td class="border border-black p-3">
                            <p class="font-black uppercase text-gray-800 text-[11px]">{{ spmbSetting?.kartuHeader2 || 'PP. RIYADUSSALIKIN PADAHERANG' }}</p>
                            <p class="text-[9px] text-gray-700 mt-0.5">Jenjang Pendidikan: <span class="uppercase font-black text-emerald-700">{{ pendaftaran?.tingkat }}</span></p>
                        </td>
                        <td class="border border-black p-3 text-center">
                            <div class="inline-block px-3 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-black uppercase tracking-tighter text-[9px]">
                                {{ stageStatusLabel(pendaftaran?.status) }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- 5. TIRU: Informasi Penting (Tiru Image 1 & 2) -->
        <div class="mb-6 p-4 border-2 border-gray-200 rounded-xl bg-gray-50/50">
            <p class="text-[10px] font-black mb-2 uppercase tracking-[0.2em] text-gray-700 border-b-2 border-gray-200 pb-1 flex items-center gap-2">
                Informasi Penting Bagi Calon Santri:
            </p>
            <ol class="text-[9px] space-y-1 list-decimal pl-5 text-gray-600 font-semibold leading-relaxed">
                <li>Kartu Pendaftaran ini <strong>WAJIB</strong> dibawa saat seleksi atau daftar ulang.</li>
                <li>Membawa dokumen asli (KTP Orang Tua, KK, Akte Kelahiran).</li>
                <li>Membawa seluruh dokumen fisik yang telah diunggah di portal.</li>
                <li>Berpakaian rapi, sopan, menutup aurat, dan menjaga adab.</li>
                <li>Pantau terus status pendaftaran Anda melalui dashboard user.</li>
            </ol>
        </div>

        <!-- 6. QR Code (lebih besar) dan Tanda Tangan Ganda -->
        <div class="flex justify-between items-end mt-8 text-[11px]">
            <!-- QR Verification -->
            <div class="flex flex-col items-center gap-1">
                <div class="border-2 border-black p-1 bg-white rounded" style="width: 3cm; height: 3cm;">
                    <div class="w-full h-full bg-white flex items-center justify-center border border-dashed border-gray-300 rounded overflow-hidden">
                        <img v-if="qrCodeUrl" :src="qrCodeUrl" class="w-full h-full object-contain" />
                        <div v-else class="grid grid-cols-4 gap-1 w-14 h-14 opacity-30">
                            <div v-for="i in 16" :key="i" class="bg-emerald-900" :style="{ opacity: Math.random() > 0.4 ? 1 : 0.1 }"></div>
                        </div>
                    </div>
                </div>
                <p class="text-[8px] font-black text-emerald-700 tracking-widest uppercase">E-VERIFICATION QR</p>
                <p class="text-[7px] text-gray-600 font-semibold">Scan untuk verifikasi</p>
            </div>

            <!-- Double Signature -->
            <div class="flex gap-12 text-center">
                <div class="min-w-[140px]">
                    <p class="mb-1 font-bold text-gray-800 uppercase tracking-tighter">Mengetahui,<br><span class="text-[9px] text-gray-600 font-medium">Orang Tua / Wali</span></p>
                    <p class="text-[10px] font-semibold text-gray-800 mb-14">{{ namaOrtuWali }}</p>
                    <p class="font-black border-t border-gray-400 pt-1 text-gray-800 text-[10px]">( __________________________ )</p>
                </div>
                <div class="min-w-[140px]">
                    <p class="mb-16 font-bold text-gray-800 uppercase tracking-tighter">Pangandaran, {{ formatTanggal(new Date()) }}<br><span class="text-[9px] text-gray-600 font-medium">Calon Peserta</span></p>
                    <p class="font-black border-t border-gray-400 pt-1 text-emerald-900 uppercase underline decoration-emerald-200 decoration-2 text-[10px]">{{ pendaftaran?.siswa?.nama_lengkap }}</p>
                </div>
            </div>
        </div>

        <!-- 7. TIRU: Footer Watermark (Tiru Image 2) -->
        <div class="absolute bottom-6 left-15 right-15 pt-3 border-t-2 border-dotted border-gray-200 text-center">
            <p class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-300">Simpanlah Lembar Pendaftaran ini sebagai bukti pendaftaran anda</p>
            <div class="flex justify-center mt-2 px-2">
                <p class="text-[8px] text-emerald-600 font-black tracking-tight italic uppercase">Sistem PPDB Online PP. Riyadussalikin</p>
            </div>
        </div>
    </div>


</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

const printCard = () => {
    window.print();
};

const props = defineProps({
    pendaftaran: Object,
    qrCodeUrl: String,
    stats: Object,
    spmbSetting: Object,
});

// Logo: pakai dari settings jika ada, fallback ke default
const logoUrl = computed(() => props.spmbSetting?.kartuLogo || '/Logo Riyad.png');
const onLogoError = (e) => { e.target.src = '/Logo Riyad.png'; };

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
        desc: 'Lengkapi formulir pendaftaran dan upload seluruh berkas yang diperlukan.',
        doneStatuses: ['pending', 'jadwal_tes', 'tes_berlangsung', 'wawancara', 'diterima_ula', 'diterima_wustho', 'diterima_ulya', 'diterima', 'ditolak'],
        activeStatuses: [],
    },
    {
        key: 'tes',
        label: 'Seleksi & Ujian Masuk',
        desc: 'Psikotes · Tes Akademik · Tes Baca Al-Qur\'an · Tes Sholat',
        doneStatuses: ['wawancara', 'diterima_ula', 'diterima_wustho', 'diterima_ulya', 'diterima', 'ditolak'],
        activeStatuses: ['jadwal_tes', 'tes_berlangsung'],
    },
    {
        key: 'wawancara',
        label: 'Wawancara Orang Tua & Calon Santri',
        desc: 'Sesi wawancara bersama orang tua dan calon santri/siswa oleh panitia seleksi.',
        doneStatuses: ['diterima_ula', 'diterima_wustho', 'diterima_ulya', 'diterima', 'ditolak'],
        activeStatuses: ['wawancara'],
    },
    {
        key: 'hasil',
        label: 'Pengumuman Hasil Seleksi',
        desc: 'Penerimaan berdasarkan hasil klasifikasi: Ula (Dasar), Wustho (Menengah), atau Ulya (Lanjutan).',
        doneStatuses: ['diterima_ula', 'diterima_wustho', 'diterima_ulya', 'diterima'],
        activeStatuses: [],
        rejectedStatuses: ['ditolak'],
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
    if (stage.key === 'hasil' && s === 'ditolak') return 'Mohon maaf, Anda belum berhasil dalam seleksi ini. Silakan hubungi panitia untuk informasi lebih lanjut.';
    return stage.desc;
};

const klasifikasiLabel = computed(() => {
    const map = {
        diterima_ula: 'Ula (Dasar)',
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
        diterima_wustho: 'Diterima (Wustho)',
        diterima_ulya: 'Diterima (Ulya)',
        ditolak: 'Tidak Lulus',
    };
    return map[status] || 'Menunggu';
};

const tesJenisLabel = {
    psikotes:   'Psikotes',
    akademik:   'Tes Akademik',
    baca_quran: 'Tes Baca Al-Quran',
    sholat:     'Tes Sholat',
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

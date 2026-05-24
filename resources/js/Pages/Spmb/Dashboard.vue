<template>
    <Head title="Dashboard Pendaftaran" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-emerald-50">
        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="/Logo Riyad.png" alt="Logo" class="h-10 w-10" />
                    <div>
                        <div class="text-emerald-700 font-bold text-sm leading-tight">Pondok Pesantren</div>
                        <div class="text-emerald-600 text-xs">Riyadussalikin Padaherang</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <Link :href="route('profile.edit')" class="flex items-center gap-2 hover:bg-gray-50 px-3 py-1.5 rounded-xl transition-colors">
                        <div class="w-8 h-8 rounded-full overflow-hidden bg-emerald-100 border border-emerald-200 flex items-center justify-center flex-shrink-0">
                            <img v-if="$page.props.auth.user.avatar" :src="'/storage/' + $page.props.auth.user.avatar" loading="lazy" class="w-full h-full object-cover" />
                            <span v-else class="text-emerald-600 font-bold text-xs">{{ $page.props.auth.user.name.charAt(0).toUpperCase() }}</span>
                        </div>
                        <span class="text-gray-700 text-sm font-semibold hidden sm:block">{{ $page.props.auth.user.name }}</span>
                    </Link>
                    <div class="h-5 w-px bg-gray-200 hidden sm:block"></div>
                    <Link :href="route('logout')" method="post" as="button"
                        class="text-sm font-semibold text-red-500 hover:text-red-700 transition-colors px-2">
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
            <div class="grid grid-cols-2 gap-4 sm:gap-6">
                <div class="bg-white rounded-3xl p-5 border border-emerald-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Pendaftar SMP</p>
                        <p class="text-2xl font-black text-gray-800">{{ stats.total_smp }} <span class="text-sm font-medium text-gray-400">Siswa</span></p>
                    </div>
                </div>
                <div class="bg-white rounded-3xl p-5 border border-blue-100 shadow-sm flex items-center gap-4">
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
                <Link :href="route('spmb.register')" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-8 py-3 rounded-2xl font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
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
                <Link :href="route('spmb.register')"
                    class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit / Perbarui Data
                </Link>
                <button @click="window.print()"
                    class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-white text-gray-700 border-2 border-gray-200 px-6 py-3 rounded-2xl font-bold hover:bg-gray-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Bukti
                </button>
            </div>

        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    pendaftaran: Object,
    stats: Object,
    spmbSetting: Object,
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

<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    pendaftaran: Object,
    user: Object,
});

const statusConfig = {
    pending: {
        label: 'Menunggu Verifikasi',
        color: '#f59e0b',
        bg: '#fffbeb',
        border: '#fcd34d',
        icon: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
    },
    diterima: {
        label: 'Diterima',
        color: '#059669',
        bg: '#ecfdf5',
        border: '#6ee7b7',
        icon: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
    },
    ditolak: {
        label: 'Tidak Diterima',
        color: '#ef4444',
        bg: '#fef2f2',
        border: '#fca5a5',
        icon: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
    },
};

const status = props.pendaftaran ? (statusConfig[props.pendaftaran.status] || statusConfig.pending) : null;
</script>

<template>
    <Head title="Dashboard - SPMB Riyadussalikin" />

    <div style="min-height:100vh;background:#f8fafc;font-family:'Figtree',sans-serif;">

        <!-- Top Bar -->
        <header style="background:#fff;border-bottom:1px solid #e5e7eb;position:sticky;top:0;z-index:50;">
            <div style="max-width:1100px;margin:0 auto;padding:0 24px;height:64px;display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <img src="/Logo Riyad.png" alt="Logo" style="height:40px;width:40px;object-fit:contain;" />
                    <div>
                        <div style="color:#047857;font-weight:700;font-size:14px;line-height:1.3;">Pondok Pesantren</div>
                        <div style="color:#059669;font-size:12px;">Riyadussalikin Padaherang</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:20px;">
                    <span style="color:#6b7280;font-size:14px;">{{ $page.props.auth.user.name }}</span>
                    <Link :href="route('logout')" method="post" as="button"
                        style="font-size:13px;font-weight:600;color:#ef4444;background:none;border:none;cursor:pointer;padding:0;">
                        Keluar
                    </Link>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div style="max-width:900px;margin:0 auto;padding:40px 24px;">

            <!-- Welcome Banner -->
            <div style="background:linear-gradient(135deg,#064e35,#0a5c3d,#0f7a52);border-radius:20px;padding:36px 40px;color:#fff;margin-bottom:32px;position:relative;overflow:hidden;">
                <div style="position:absolute;top:-40px;right:-40px;width:200px;height:200px;background:rgba(255,255,255,0.04);border-radius:50%;"></div>
                <div style="position:absolute;bottom:-60px;right:80px;width:140px;height:140px;background:rgba(255,255,255,0.04);border-radius:50%;"></div>
                <p style="font-size:13px;color:rgba(255,255,255,0.7);margin-bottom:6px;font-weight:500;">Selamat Datang,</p>
                <h1 style="font-size:1.75rem;font-weight:800;margin-bottom:8px;">{{ $page.props.auth.user.name }}</h1>
                <p style="font-size:14px;color:rgba(255,255,255,0.75);">Sistem Penerimaan Murid Baru · Pondok Pesantren Riyadussalikin</p>
            </div>

            <!-- NO PENDAFTARAN YET -->
            <div v-if="!pendaftaran" style="background:#fff;border-radius:16px;border:1px solid #e5e7eb;padding:48px;text-align:center;">
                <div style="width:80px;height:80px;background:#ecfdf5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                    <svg style="width:40px;height:40px;color:#059669;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 style="font-size:1.25rem;font-weight:700;color:#111827;margin-bottom:8px;">Belum Ada Pendaftaran</h2>
                <p style="color:#6b7280;font-size:14px;margin-bottom:28px;line-height:1.6;">Anda belum mengisi formulir pendaftaran. Silakan isi formulir untuk melanjutkan proses seleksi.</p>
                <Link :href="route('spmb.register')"
                    style="display:inline-block;background:#059669;color:#fff;padding:12px 32px;border-radius:10px;font-weight:700;font-size:14px;text-decoration:none;transition:background 0.2s;">
                    Isi Formulir Pendaftaran
                </Link>
            </div>

            <!-- HAS PENDAFTARAN -->
            <div v-else>
                <!-- Status Card -->
                <div :style="`background:${status.bg};border:1.5px solid ${status.border};border-radius:16px;padding:28px 32px;margin-bottom:24px;display:flex;align-items:center;gap:20px;`">
                    <div :style="`width:52px;height:52px;background:${status.color}20;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;`">
                        <svg style="width:28px;height:28px;" :style="`color:${status.color}`" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path v-if="pendaftaran.status === 'diterima'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            <path v-else-if="pendaftaran.status === 'ditolak'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:2px;">Status Pendaftaran</p>
                        <p :style="`font-size:1.2rem;font-weight:800;color:${status.color};`">{{ status.label }}</p>
                        <p style="font-size:13px;color:#6b7280;margin-top:2px;">No. Registrasi: <strong style="color:#111;">{{ pendaftaran.no_reg }}</strong></p>
                    </div>
                </div>

                <!-- Info Cards Grid -->
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
                    <!-- Biodata -->
                    <div style="background:#fff;border-radius:16px;border:1px solid #e5e7eb;padding:24px;">
                        <h3 style="font-size:14px;font-weight:700;color:#374151;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                            <span style="width:4px;height:16px;background:#059669;border-radius:2px;display:inline-block;margin-right:8px;"></span>
                            Data Calon Santri
                        </h3>
                        <div style="space-y:8px;">
                            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f3f4f6;">
                                <span style="font-size:13px;color:#6b7280;">Nama Lengkap</span>
                                <span style="font-size:13px;font-weight:600;color:#111;">{{ pendaftaran.siswa?.nama_lengkap ?? '-' }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f3f4f6;">
                                <span style="font-size:13px;color:#6b7280;">Tingkat</span>
                                <span style="font-size:13px;font-weight:600;color:#111;text-transform:uppercase;">{{ pendaftaran.tingkat }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f3f4f6;">
                                <span style="font-size:13px;color:#6b7280;">Jenis Kelamin</span>
                                <span style="font-size:13px;font-weight:600;color:#111;">{{ pendaftaran.siswa?.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f3f4f6;">
                                <span style="font-size:13px;color:#6b7280;">Berkebutuhan Khusus</span>
                                <span style="font-size:13px;font-weight:600;color:#111;">{{ pendaftaran.siswa?.berkebutuhan_khusus || 'Tidak Ada' }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;padding:8px 0;">
                                <span style="font-size:13px;color:#6b7280;">Tanggal Daftar</span>
                                <span style="font-size:13px;font-weight:600;color:#111;">{{ new Date(pendaftaran.tanggal_daftar).toLocaleDateString('id-ID', {day:'2-digit',month:'long',year:'numeric'}) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div style="background:#fff;border-radius:16px;border:1px solid #e5e7eb;padding:24px;">
                        <h3 style="font-size:14px;font-weight:700;color:#374151;margin-bottom:16px;">
                            <span style="width:4px;height:16px;background:#059669;border-radius:2px;display:inline-block;margin-right:8px;"></span>
                            Tahapan Seleksi
                        </h3>
                        <div style="display:flex;flex-direction:column;gap:16px;">
                            <div v-for="(tahap, idx) in [
                                { label: 'Formulir Dikirim', done: true },
                                { label: 'Verifikasi Berkas', done: pendaftaran.status !== 'pending' },
                                { label: 'Pengumuman Hasil', done: pendaftaran.status === 'diterima' || pendaftaran.status === 'ditolak' },
                                { label: 'Daftar Ulang', done: pendaftaran.status === 'diterima' },
                            ]" :key="idx" style="display:flex;align-items:center;gap:12px;">
                                <div :style="`width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;${tahap.done ? 'background:#059669;' : 'background:#e5e7eb;'}`">
                                    <svg v-if="tahap.done" style="width:14px;height:14px;color:#fff;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <div v-else style="width:8px;height:8px;border-radius:50%;background:#9ca3af;"></div>
                                </div>
                                <span :style="`font-size:13px;font-weight:${tahap.done ? '600' : '400'};color:${tahap.done ? '#111' : '#9ca3af'};`">{{ tahap.label }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Bantuan (If any) -->
                <div v-if="pendaftaran.siswa?.penerima_kps_pkh || pendaftaran.siswa?.penerima_kip" style="background:#fff;border-radius:16px;border:1px solid #e5e7eb;padding:24px;margin-bottom:24px;">
                    <h3 style="font-size:14px;font-weight:700;color:#374151;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                        <span style="width:4px;height:16px;background:#3b82f6;border-radius:2px;display:inline-block;margin-right:8px;"></span>
                        Data Bantuan Sosial
                    </h3>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                        <div v-if="pendaftaran.siswa?.penerima_kps_pkh">
                            <p style="font-size:12px;color:#6b7280;margin-bottom:4px;">KPS / PKH / KKS</p>
                            <p style="font-size:14px;font-weight:700;color:#111;">Penerima ({{ pendaftaran.siswa.no_kps_pkh || pendaftaran.siswa.no_kks }})</p>
                        </div>
                        <div v-if="pendaftaran.siswa?.penerima_kip">
                            <p style="font-size:12px;color:#6b7280;margin-bottom:4px;">KIP (Program Indonesia Pintar)</p>
                            <p style="font-size:14px;font-weight:700;color:#111;">Penerima ({{ pendaftaran.siswa.no_kip }})</p>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:20px 24px;display:flex;gap:14px;align-items:flex-start;">
                    <svg style="width:20px;height:20px;color:#3b82f6;flex-shrink:0;margin-top:2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p style="font-size:13px;color:#1e40af;line-height:1.7;">
                        Pengumuman hasil seleksi akan diinformasikan melalui halaman ini dan/atau melalui WhatsApp. Pantau terus status pendaftaran Anda di halaman ini.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

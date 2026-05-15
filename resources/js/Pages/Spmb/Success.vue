<template>
    <Head title="Pendaftaran Berhasil" />
    <MainLayout>
        <div class="py-20 bg-gray-50 min-h-[80vh] flex items-center">
            <div class="max-w-2xl mx-auto px-4 text-center">
                <!-- Success Icon -->
                <div class="mb-8 flex justify-center">
                    <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center animate-bounce shadow-lg shadow-emerald-100">
                        <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-4xl font-black text-gray-900 mb-4">Alhamdulillah!</h1>
                <p class="text-xl text-gray-600 mb-12">
                    Pendaftaran atas nama <strong class="text-emerald-700">{{ nama }}</strong> telah berhasil kami terima.
                </p>

                <!-- Registration Card -->
                <div class="bg-white rounded-3xl p-8 shadow-2xl border border-gray-100 mb-12 text-left relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-bl-full -z-0 opacity-50"></div>
                    
                    <div class="relative z-10">
                        <p class="text-sm text-gray-400 uppercase tracking-widest font-bold mb-1">Nomor Registrasi Anda</p>
                        <h2 class="text-3xl font-black text-emerald-600 mb-6">{{ no_reg }}</h2>
                        
                        <div class="space-y-4 border-t border-gray-50 pt-6">
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                    <span class="text-emerald-600 font-bold">1</span>
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Simpan atau screenshot nomor registrasi di atas sebagai bukti pendaftaran.
                                </p>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                    <span class="text-emerald-600 font-bold">2</span>
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Panitia akan menghubungi Anda melalui nomor WhatsApp yang telah didaftarkan untuk proses verifikasi selanjutnya.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <Link 
                        :href="route('home')"
                        class="w-full sm:w-auto px-8 py-4 bg-emerald-600 text-white rounded-2xl font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200"
                    >
                        Kembali ke Beranda
                    </Link>
                    <button 
                        @click="window.print()"
                        class="w-full sm:w-auto px-8 py-4 bg-white text-emerald-700 border-2 border-emerald-100 rounded-2xl font-bold hover:bg-emerald-50 transition-all"
                    >
                        Cetak Bukti
                    </button>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '../../Layouts/MainLayout.vue';

defineProps({
    no_reg: String,
    nama: String
});
</script>

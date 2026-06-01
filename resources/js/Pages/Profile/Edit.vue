<script setup>
import { ref, computed, nextTick, watch } from 'vue';
import { useForm, router, Head, Link } from '@inertiajs/vue3';
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

const props = defineProps({
    user: Object,
});

const activeTab = ref('profile');
const avatarPreview = ref(props.user.avatar);
const fileInput = ref(null);

// Profile form
const profileForm = useForm({
    name:   props.user.name,
    email:  props.user.email,
    avatar: null,
});

// Password form
const passwordForm = useForm({
    current_password:      '',
    password:              '',
    password_confirmation: '',
});

const showCurrent = ref(false);
const showNew     = ref(false);
const showConfirm = ref(false);

const successMsg = ref('');
const showSuccess = (msg) => {
    successMsg.value = msg;
    setTimeout(() => successMsg.value = '', 4000);
};

// Cropper State
const showCropModal = ref(false);
const cropImageSrc = ref('');
const cropImageRef = ref(null);
let cropper = null;
let originalFile = null;

const onAvatarChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;
    originalFile = file;
    cropImageSrc.value = URL.createObjectURL(file);
    showCropModal.value = true;
    
    nextTick(() => {
        if (cropper) {
            cropper.destroy();
        }
        cropper = new Cropper(cropImageRef.value, {
            aspectRatio: 3 / 4,
            viewMode: 1,
            autoCropArea: 1,
            responsive: true,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
        });
    });
};

const cancelCrop = () => {
    showCropModal.value = false;
    cropImageSrc.value = '';
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const applyCrop = () => {
    if (!cropper) return;
    const canvas = cropper.getCroppedCanvas({
        width: 600,
        height: 800,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    });
    
    canvas.toBlob((blob) => {
        if (!blob) return;
        const croppedFile = new File([blob], originalFile.name, {
            type: originalFile.type,
            lastModified: Date.now()
        });
        
        profileForm.avatar = croppedFile;
        avatarPreview.value = URL.createObjectURL(croppedFile);
        
        showCropModal.value = false;
        cropper.destroy();
        cropper = null;
        
        // Auto-submit form so that "Potong & Simpan" button actually saves to backend
        nextTick(() => {
            submitProfile();
        });
    }, originalFile.type || 'image/jpeg', 0.9);
};

const submitProfile = () => {
    profileForm.post(route('profile.update'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => showSuccess('Profil berhasil diperbarui.'),
    });
};

const submitPassword = () => {
    passwordForm.patch(route('profile.password'), {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
            showSuccess('Password berhasil diubah.');
        },
    });
};

const initials = computed(() => {
    return props.user.name?.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase() ?? 'S';
});

watch(() => props.user.avatar, (newAvatar) => {
    avatarPreview.value = newAvatar;
    profileForm.avatar = null;
});
</script>

<template>
    <Head title="Profil Saya" />
    <div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 flex flex-col">
        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="/logo_pondok.png" alt="Logo" class="h-10 w-10" />
                    <div class="font-['Poppins']">
                        <div class="text-emerald-700 font-extrabold text-sm leading-tight tracking-wide">PONDOK PESANTREN</div>
                        <div class="text-emerald-600 font-semibold text-xs tracking-wider">Riyadussalikin Padaherang</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <Link :href="route('dashboard')" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                        Kembali
                    </Link>
                </div>
            </div>
        </header>

        <div class="flex-1 py-8 px-4">
            <div class="max-w-3xl mx-auto">

                <!-- Header Card -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-6">
                    <div class="h-28 bg-gradient-to-r from-emerald-500 to-teal-400"></div>
                    <div class="px-6 pb-6 -mt-14 flex items-end gap-4">
                        <!-- Avatar -->
                        <div class="relative flex-shrink-0">
                            <div class="w-24 h-24 rounded-2xl border-4 border-white shadow-lg overflow-hidden bg-emerald-100 flex items-center justify-center">
                                <img v-if="avatarPreview" :src="avatarPreview" loading="lazy" class="w-full h-full object-cover" alt="Foto Profil" />
                                <span v-else class="text-3xl font-bold text-emerald-600">{{ initials }}</span>
                            </div>
                            <button @click="fileInput.click()"
                                class="absolute -bottom-1 -right-1 w-7 h-7 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-md hover:bg-emerald-600 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </button>
                            <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onAvatarChange" />
                        </div>
                        <div class="pb-1">
                            <h1 class="text-xl font-bold text-gray-900">{{ user.name }}</h1>
                            <p class="text-sm text-gray-500">{{ user.email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Success Toast -->
                <Transition enter-active-class="transition-all duration-300" enter-from-class="opacity-0 -translate-y-2"
                    leave-active-class="transition-all duration-300" leave-to-class="opacity-0 -translate-y-2">
                    <div v-if="successMsg" class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl px-5 py-3 text-sm font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ successMsg }}
                    </div>
                </Transition>

                <!-- Tabs -->
                <div class="flex gap-2 mb-5 bg-white rounded-2xl p-1.5 shadow-sm border border-gray-100">
                    <button @click="activeTab = 'profile'" class="flex-1 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200"
                        :class="activeTab === 'profile' ? 'bg-emerald-500 text-white shadow-md' : 'text-gray-500 hover:text-emerald-600'">
                        Informasi Profil
                    </button>
                    <button @click="activeTab = 'password'" class="flex-1 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200"
                        :class="activeTab === 'password' ? 'bg-emerald-500 text-white shadow-md' : 'text-gray-500 hover:text-emerald-600'">
                        Ganti Password
                    </button>
                </div>

                <!-- Profile Tab -->
                <div v-if="activeTab === 'profile'" class="bg-white rounded-3xl shadow-xl p-6 border border-gray-100">
                    <h2 class="text-base font-bold text-gray-800 mb-5">Perbarui Informasi Akun</h2>
                    <form @submit.prevent="submitProfile" class="space-y-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap</label>
                            <input v-model="profileForm.name" type="text" placeholder="Nama lengkap Anda"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                                :class="profileForm.errors.name ? 'border-red-400 bg-red-50' : ''" />
                            <p v-if="profileForm.errors.name" class="text-xs text-red-500 mt-1">{{ profileForm.errors.name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email</label>
                            <input v-model="profileForm.email" type="email" placeholder="email@example.com"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                                :class="profileForm.errors.email ? 'border-red-400 bg-red-50' : ''" />
                            <p v-if="profileForm.errors.email" class="text-xs text-red-500 mt-1">{{ profileForm.errors.email }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Foto Profil</label>
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 rounded-xl border border-gray-200 overflow-hidden bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                    <img v-if="avatarPreview" :src="avatarPreview" loading="lazy" class="w-full h-full object-cover" />
                                    <span v-else class="text-lg font-bold text-emerald-400">{{ initials }}</span>
                                </div>
                                <button type="button" @click="fileInput.click()"
                                    class="px-4 py-2 text-sm border border-emerald-300 text-emerald-600 rounded-xl hover:bg-emerald-50 transition-colors font-medium">
                                    Pilih Foto
                                </button>
                                <span v-if="profileForm.avatar" class="text-xs text-gray-500 truncate max-w-[120px]">{{ profileForm.avatar.name }}</span>
                            </div>
                            <p v-if="profileForm.errors.avatar" class="text-xs text-red-500 mt-1">{{ profileForm.errors.avatar }}</p>
                        </div>
                        <div class="pt-2">
                            <button type="submit" :disabled="profileForm.processing"
                                class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 text-white py-3 rounded-xl font-semibold text-sm hover:from-emerald-600 hover:to-teal-600 transition-all shadow-md shadow-emerald-200 disabled:opacity-60 disabled:cursor-not-allowed">
                                {{ profileForm.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Tab -->
                <div v-if="activeTab === 'password'" class="bg-white rounded-3xl shadow-xl p-6 border border-gray-100">
                    <h2 class="text-base font-bold text-gray-800 mb-5">Ubah Password Akun</h2>
                    <form @submit.prevent="submitPassword" class="space-y-5">
                        <!-- Current Password -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Password Saat Ini</label>
                            <div class="relative">
                                <input v-model="passwordForm.current_password"
                                    :type="showCurrent ? 'text' : 'password'" placeholder="••••••••"
                                    class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                                    :class="passwordForm.errors.current_password ? 'border-red-400 bg-red-50' : ''" />
                                <button type="button" @click="showCurrent = !showCurrent" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg v-if="!showCurrent" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg v-else class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            <p v-if="passwordForm.errors.current_password" class="text-xs text-red-500 mt-1">{{ passwordForm.errors.current_password }}</p>
                        </div>

                        <!-- New Password -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Password Baru</label>
                            <div class="relative">
                                <input v-model="passwordForm.password"
                                    :type="showNew ? 'text' : 'password'" placeholder="Min. 8 karakter"
                                    class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                                    :class="passwordForm.errors.password ? 'border-red-400 bg-red-50' : ''" />
                                <button type="button" @click="showNew = !showNew" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg v-if="!showNew" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg v-else class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            <p v-if="passwordForm.errors.password" class="text-xs text-red-500 mt-1">{{ passwordForm.errors.password }}</p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input v-model="passwordForm.password_confirmation"
                                    :type="showConfirm ? 'text' : 'password'" placeholder="Ulangi password baru"
                                    class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all" />
                                <button type="button" @click="showConfirm = !showConfirm" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg v-if="!showConfirm" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg v-else class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" :disabled="passwordForm.processing"
                                class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 text-white py-3 rounded-xl font-semibold text-sm hover:from-emerald-600 hover:to-teal-600 transition-all shadow-md shadow-emerald-200 disabled:opacity-60 disabled:cursor-not-allowed">
                                {{ passwordForm.processing ? 'Menyimpan...' : 'Ubah Password' }}
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <!-- Crop Modal -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-200 ease-in"
            leave-to-class="opacity-0 scale-95"
        >
            <div v-if="showCropModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                <div class="bg-white w-full max-w-md rounded-3xl p-6 shadow-2xl border border-gray-100 animate-fadeIn relative">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Sesuaikan Pas Foto</h3>
                        <p class="text-xs text-gray-500 mt-1">Geser, perbesar, dan atur posisi foto calon santri agar pas di dalam kotak potong (Rasio 3:4).</p>
                    </div>

                    <!-- Cropper Container -->
                    <div class="w-full max-h-[320px] bg-gray-900 rounded-2xl overflow-hidden flex items-center justify-center mb-6">
                        <img ref="cropImageRef" :src="cropImageSrc" class="max-w-full block max-h-[300px]" alt="Foto untuk dipotong" />
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3">
                        <button type="button" @click="cancelCrop"
                            class="flex-1 py-3 border-2 border-gray-100 text-gray-500 rounded-xl font-semibold text-sm hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="button" @click="applyCrop"
                            class="flex-1 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-semibold text-sm hover:from-emerald-600 hover:to-teal-600 transition-colors shadow-md shadow-emerald-100">
                            Potong & Simpan
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

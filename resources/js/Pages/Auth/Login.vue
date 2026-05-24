<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import logoUrl from '../../../assets/logo/logo_pondok.png';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login" />

    <div style="min-height:100vh;display:flex;flex-direction:row;background:#fff;font-family:'Figtree',sans-serif;">

        <!-- LEFT PANEL -->
        <div class="auth-left-panel" style="width:60%;min-height:100vh;position:relative;display:flex;align-items:center;justify-content:center;overflow:hidden;background:#0a3320;">
            <!-- Dark green gradient overlay -->
            <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(6,30,18,0.97) 0%,rgba(10,62,35,0.92) 60%,rgba(16,90,52,0.88) 100%);z-index:1;"></div>
            <!-- Subtle pattern overlay -->
            <div style="position:absolute;inset:0;opacity:0.04;background-image:radial-gradient(#fff 1px,transparent 1px);background-size:24px 24px;z-index:2;"></div>

            <!-- Content -->
            <div style="position:relative;z-index:3;display:flex;flex-direction:column;align-items:center;text-align:center;padding:0 64px;width:100%;">
                <!-- Logo -->
                <img :src="logoUrl" alt="Logo Riyadussalikin" style="width:120px;height:120px;object-fit:contain;margin-bottom:32px;filter:drop-shadow(0 4px 24px rgba(0,0,0,0.5));" />

                <h1 style="font-size:2rem;font-weight:800;color:#fff;line-height:1.3;margin-bottom:8px;">
                    Selamat Datang Kembali di<br/>
                    <span style="color:#34d399;">Riyadussalikin</span>
                </h1>

                <p style="color:rgba(255,255,255,0.7);font-style:italic;font-size:0.97rem;margin-top:16px;max-width:360px;line-height:1.7;">
                    "Barangsiapa menempuh jalan untuk mencari ilmu, maka Allah akan memudahkan baginya jalan menuju surga."
                </p>

                <div style="margin-top:36px;padding:8px 20px;border-radius:999px;border:1px solid rgba(255,255,255,0.12);background:rgba(0,0,0,0.25);display:inline-flex;align-items:center;gap:8px;">
                    <span style="width:7px;height:7px;border-radius:50%;background:#34d399;display:inline-block;"></span>
                    <span style="color:rgba(255,255,255,0.85);font-size:0.78rem;font-weight:500;">Sistem Penerimaan Santri Baru</span>
                </div>
            </div>

            <!-- Footer -->
            <div style="position:absolute;bottom:28px;left:0;width:100%;padding:0 56px;display:flex;justify-content:space-between;z-index:3;">
                <span style="color:rgba(255,255,255,0.2);font-size:10px;font-weight:700;letter-spacing:0.2em;">TRADISI</span>
                <span style="color:rgba(255,255,255,0.2);font-size:10px;font-weight:700;letter-spacing:0.2em;">MODERNITAS</span>
                <span style="color:rgba(255,255,255,0.2);font-size:10px;font-weight:700;letter-spacing:0.2em;">INTEGRITAS</span>
            </div>
        </div>

        <!-- RIGHT PANEL: Form -->
        <div style="flex:1;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:48px;background:#fff;position:relative;">
            <!-- Green glow at bottom -->
            <div style="position:absolute;bottom:0;left:0;right:0;height:120px;background:linear-gradient(to top, rgba(52,211,153,0.07), transparent);pointer-events:none;"></div>

            <div style="width:100%;max-width:380px;position:relative;z-index:1;">
                <!-- Header -->
                <h2 style="font-size:2rem;font-weight:700;color:#111;margin-bottom:4px;letter-spacing:-0.5px;">Masuk Akun</h2>
                <p style="font-size:0.88rem;color:#6b7280;line-height:1.6;margin-bottom:36px;">
                    Silakan masuk menggunakan email dan kata sandi Anda.
                </p>

                <!-- Success Flash Alert -->
                <div v-if="$page.props.flash?.success" style="margin-bottom: 24px; padding: 12px 16px; background: #e6fcf5; border-left: 4px solid #059669; border-radius: 6px; color: #065f46; font-size: 0.85rem; line-height: 1.5; font-weight: 500;">
                    {{ $page.props.flash.success }}
                </div>

                <form @submit.prevent="submit">
                    <!-- Email -->
                    <div style="margin-bottom:20px;">
                        <label style="display:block;font-size:10px;font-weight:700;color:#4b5563;letter-spacing:0.12em;text-transform:uppercase;margin-bottom:6px;">Alamat Email</label>
                        <input
                            type="email"
                            v-model="form.email"
                            placeholder="email@contoh.com"
                            style="display:block;width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:0.88rem;color:#111;background:#fff;outline:none;transition:border-color 0.15s;box-sizing:border-box;"
                            required autofocus
                        />
                        <div v-if="form.errors.email" style="margin-top:4px;font-size:0.78rem;color:#ef4444;">{{ form.errors.email }}</div>
                    </div>

                    <!-- Password -->
                    <div style="margin-bottom:16px;">
                        <label style="display:block;font-size:10px;font-weight:700;color:#4b5563;letter-spacing:0.12em;text-transform:uppercase;margin-bottom:6px;">Kata Sandi</label>
                        <div style="position:relative;">
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                v-model="form.password"
                                placeholder="••••••••"
                                style="display:block;width:100%;padding:10px 36px 10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:0.88rem;color:#111;background:#fff;outline:none;box-sizing:border-box;"
                                required
                            />
                            <button type="button" @click="showPassword = !showPassword" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;padding:0;display:flex;align-items:center;">
                                <svg v-if="!showPassword" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg v-else style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        <div v-if="form.errors.password" style="margin-top:4px;font-size:0.78rem;color:#ef4444;">{{ form.errors.password }}</div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div style="margin-bottom:28px;display:flex;align-items:center;justify-content:space-between;width:100%;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" id="remember" v-model="form.remember" style="width:13px;height:13px;accent-color:#059669;cursor:pointer;" />
                            <label for="remember" style="font-size:0.8rem;color:#6b7280;cursor:pointer;">Ingat Saya</label>
                        </div>
                        <Link :href="route('password.request')" style="font-size:0.8rem;color:#059669;font-weight:600;text-decoration:none;">Lupa Kata Sandi?</Link>
                    </div>

                    <!-- Submit Button with glow -->
                    <div style="position:relative;margin-bottom:24px;">
                        <div style="position:absolute;inset:-4px;background:rgba(52,211,153,0.18);border-radius:10px;filter:blur(12px);transform:translateY(6px);pointer-events:none;"></div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            style="position:relative;width:100%;background:#059669;color:#fff;font-weight:700;font-size:11px;letter-spacing:0.15em;padding:14px;border-radius:8px;border:none;cursor:pointer;transition:background 0.2s;"
                            @mouseover="e => e.target.style.background='#047857'"
                            @mouseout="e => e.target.style.background='#059669'"
                        >
                            MASUK SEKARANG
                        </button>
                    </div>

                    <p style="text-align:center;font-size:0.82rem;color:#9ca3af;">
                        Belum memiliki akun?
                        <Link :href="route('register')" style="color:#059669;font-weight:600;text-decoration:none;">Daftar Sekarang</Link>
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
@media (max-width: 767px) {
    .auth-left-panel {
        display: none !important;
    }
}
input[type="email"]:focus,
input[type="password"]:focus {
    border-color: #059669 !important;
    box-shadow: 0 0 0 1px #059669 !important;
    outline: none !important;
}
</style>

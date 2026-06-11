<template>
    <Head title="Pendaftaran Siswa Baru" />

    <!-- Minimal App Shell (no navbar/footer) -->
    <div class="min-h-screen flex flex-col bg-gray-50">

        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <img src="/logo_pondok.png" alt="Logo" class="h-10 w-10" />
                    <div class="font-['Poppins']">
                        <div class="text-emerald-700 font-extrabold text-sm leading-tight tracking-wide">PONDOK PESANTREN</div>
                        <div class="text-emerald-600 font-semibold text-xs tracking-wider">Riyadussalikin Padaherang</div>
                    </div>
                </div>
                <!-- User & Logout -->
                <div class="flex items-center gap-4">
                    <Link :href="route('dashboard')" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </Link>
                    <div class="h-4 w-px bg-gray-200"></div>
                    <span class="text-gray-500 text-sm hidden sm:block">{{ $page.props.auth.user.name }}</span>
                    <Link :href="route('logout')" method="post" as="button"
                        class="text-sm font-semibold text-red-500 hover:text-red-700 transition-colors">
                        Keluar
                    </Link>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="pt-6 pb-24 md:py-10 flex-1">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Back to Dashboard Link -->
                <div class="mb-6">
                    <Link :href="route('dashboard')" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-bold transition-all group">
                        <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center group-hover:bg-emerald-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <span>Kembali ke Dashboard</span>
                    </Link>
                </div>

                <!-- Header -->
                <div class="text-center mb-8 md:mb-12">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        Formulir Pendaftaran
                    </h1>
                    <p class="mt-2 text-base md:text-lg text-gray-600">
                        Pondok Pesantren Riyadussalikin Periode {{ ppdbSetting?.tahunAjaran ?? '-' }}
                    </p>
                </div>

                <!-- Registration Closed Alert -->
                <div v-if="!ppdbSetting?.isOpen" class="mb-8 bg-red-50 border-2 border-red-200 rounded-2xl p-6 text-center shadow-lg shadow-red-100 animate-fadeIn">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-xl font-black text-red-800 mb-2">Pendaftaran Sedang Ditutup</h2>
                    <p class="text-red-600 text-sm font-medium mb-4">
                        {{ ppdbSetting?.pesanTutup || 'Mohon maaf, saat ini pendaftaran santri baru sedang tidak dibuka.' }}
                    </p>
                    <Link :href="route('dashboard')" class="inline-flex items-center gap-2 bg-red-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-red-700 transition-all">
                        Kembali ke Dashboard
                    </Link>
                </div>

                <!-- Multi-step Progress Bar -->
                <div v-else class="mb-8 md:mb-10 overflow-x-auto pb-4">
                    <div class="flex items-start min-w-[500px] md:min-w-0 justify-between px-2">
                        <template v-for="s in totalSteps" :key="s">
                            <!-- Step circle + label -->
                            <div class="flex flex-col items-center flex-shrink-0">
                                <div
                                    class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-bold text-xs md:text-sm border-2 transition-all duration-300 relative"
                                    :class="step > s
                                        ? (form.tingkat === 'smp' ? 'bg-[#2E5FA3] border-[#2E5FA3] text-white shadow-lg shadow-[#2E5FA3]/30' : 'bg-slate-500 border-slate-500 text-white shadow-lg shadow-slate-200')
                                        : step === s
                                            ? (form.tingkat === 'smp' ? 'bg-white border-[#2E5FA3] text-[#2E5FA3] ring-4 ring-[#2E5FA3]/10' : 'bg-white border-slate-500 text-slate-600 ring-4 ring-slate-100')
                                            : 'bg-white border-gray-300 text-gray-400'"
                                >
                                    <span>{{ s }}</span>
                                    <!-- Checkmark badge for completed steps -->
                                    <div v-if="step > s" class="absolute -top-1 -right-1 w-4 h-4 bg-white rounded-full flex items-center justify-center shadow-sm">
                                        <svg class="w-3 h-3 transition-colors duration-500" :class="form.tingkat === 'smp' ? 'text-[#2E5FA3]' : 'text-slate-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                                <span
                                    class="mt-2 text-[10px] md:text-xs font-semibold tracking-wide text-center w-20 md:w-24 transition-colors duration-500"
                                    :class="step >= s ? (form.tingkat === 'smp' ? 'text-[#2E5FA3]' : 'text-slate-600') : 'text-gray-400'"
                                >{{ stepLabels[s-1] }}</span>
                            </div>
                            <!-- Connector line between steps -->
                            <div v-if="s < totalSteps" class="flex-1 mt-4 md:mt-5 mx-1">
                                <div class="h-0.5 w-full transition-all duration-500"
                                    :class="step > s ? (form.tingkat === 'smp' ? 'bg-[#2E5FA3]' : 'bg-slate-500') : 'bg-gray-200'"
                                ></div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Form Content -->
                <form v-if="ppdbSetting?.isOpen" @submit.prevent="submit" class="bg-white shadow-2xl rounded-2xl md:rounded-3xl mb-8 border border-gray-100">
                    <div class="p-5 sm:p-8 md:p-12">
                        
                        <!-- STEP 1: Registrasi & Identitas Siswa -->
                        <div v-if="step === 1" class="space-y-6 md:space-y-8 animate-fadeIn">
                            <div class="border-b border-gray-100 pb-4">
                                <h2 class="text-xl md:text-2xl font-bold text-gray-800 text-center md:text-left">Identitas Peserta Didik</h2>
                                <p class="text-gray-500 text-sm text-center md:text-left">Lengkapi data diri calon santri sesuai dengan dokumen resmi (KK/Akta).</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="col-span-1 lg:col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-3">Tingkat Pendidikan</label>
                                    <div class="grid grid-cols-2 gap-4 max-w-sm mx-auto md:mx-0">
                                        <!-- SMP Button -->
                                        <button
                                            type="button"
                                            :disabled="!!existingData"
                                            @click="form.tingkat = 'smp'"
                                            class="py-3.5 px-4 rounded-xl border-2 font-bold transition-all text-sm md:text-base flex flex-col items-center gap-1"
                                            :class="[
                                                form.tingkat === 'smp'
                                                    ? 'border-[#2E5FA3] bg-[#2E5FA3] text-white shadow-lg shadow-[#2E5FA3]/30'
                                                    : 'border-[#A8C4E5] bg-white text-[#4A7AC0] hover:border-[#2E5FA3] hover:bg-[#EBF0FB]',
                                                existingData ? 'pointer-events-none opacity-60' : ''
                                            ]"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                            SMP
                                        </button>
                                        <!-- SMA Button -->
                                        <button
                                            type="button"
                                            :disabled="!!existingData"
                                            @click="form.tingkat = 'sma'"
                                            class="py-3.5 px-4 rounded-xl border-2 font-bold transition-all text-sm md:text-base flex flex-col items-center gap-1"
                                            :class="[
                                                form.tingkat === 'sma'
                                                    ? 'border-slate-600 bg-slate-600 text-white shadow-lg shadow-slate-200'
                                                    : 'border-slate-300 bg-white text-slate-500 hover:border-slate-500 hover:bg-slate-50',
                                                existingData ? 'pointer-events-none opacity-60' : ''
                                            ]"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            SMA
                                        </button>
                                    </div>
                                    <!-- Badge informasi jenjang terpilih -->
                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold transition-all duration-500"
                                            :class="form.tingkat === 'smp' ? 'bg-[#D6E4F5] text-[#1A4080]' : 'bg-slate-200 text-slate-700'">
                                            <span class="w-2 h-2 rounded-full animate-pulse" :class="form.tingkat === 'smp' ? 'bg-[#2E5FA3]' : 'bg-slate-500'"></span>
                                            Mendaftar untuk jenjang: <strong>{{ form.tingkat.toUpperCase() }}</strong>
                                        </div>
                                        <span v-if="existingData" class="text-xs text-gray-400 font-medium">
                                            (Jenjang terkunci karena data pendaftaran sudah tersimpan)
                                        </span>
                                    </div>
                                </div>

                                <div class="col-span-1 lg:col-span-2">
                                    <label class="label-form">Nama Lengkap</label>
                                    <input v-model="form.siswa.nama_lengkap" type="text" class="input-form" placeholder="Contoh: Ahmad Abdullah" required>
                                </div>

                                <div class="col-span-1 lg:col-span-2">
                                    <label class="label-form">Asal Sekolah</label>
                                    <input v-model="form.siswa.asal_sekolah" type="text" class="input-form" placeholder="Contoh: SD Negeri 1 Padaherang" required>
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Jenis Kelamin</label>
                                    <select v-model="form.siswa.jenis_kelamin" class="input-form" required>
                                        <option value="">Pilih...</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">NISN</label>
                                    <input v-model="form.siswa.nisn" type="text" class="input-form" placeholder="10 Digit NISN" maxlength="10">
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">NIK (Nomor Induk Kependudukan)</label>
                                    <input v-model="form.siswa.nik" type="text" class="input-form" placeholder="16 Digit NIK" maxlength="16">
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Tempat Lahir</label>
                                    <input v-model="form.siswa.tempat_lahir" type="text" class="input-form" placeholder="Kota Lahir" required>
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Tanggal Lahir</label>
                                    <input v-model="form.siswa.tanggal_lahir" type="date" class="input-form" required>
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Agama</label>
                                    <input v-model="form.siswa.agama" type="text" class="input-form" placeholder="Islam">
                                </div>

                                <div class="col-span-1">
                                     <div class="flex justify-between items-center mb-1">
                                         <label class="label-form" style="margin-bottom: 0; display: inline-block;">No. Registrasi Akta Kelahiran</label>
                                         <button type="button" @click="showAktaExample = true" class="text-xs text-emerald-600 hover:text-emerald-700 font-semibold flex items-center gap-1 focus:outline-none transition-colors duration-200">
                                             <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                             </svg>
                                             Lihat Contoh
                                         </button>
                                     </div>
                                     <input v-model="form.siswa.no_registrasi_akta" type="text" class="input-form" placeholder="Nomor Registrasi Akta">
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Kewarganegaraan</label>
                                    <select v-model="form.siswa.kewarganegaraan" class="input-form">
                                        <option value="Indonesia">Indonesia (WNI)</option>
                                        <option value="Asing">Asing (WNA)</option>
                                    </select>
                                </div>

                                <div v-if="form.siswa.kewarganegaraan === 'Asing'" class="col-span-1">
                                    <label class="label-form">Nama Negara Asal</label>
                                    <input v-model="form.siswa.nama_negara" type="text" class="input-form" placeholder="Nama Negara">
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">No. Ijazah</label>
                                    <input v-model="form.siswa.no_ijazah" type="text" class="input-form" placeholder="Nomor Ijazah">
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">No. SKHUN</label>
                                    <input v-model="form.siswa.no_skhun" type="text" class="input-form" placeholder="Nomor SKHUN">
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">No. Ujian Nasional</label>
                                    <input v-model="form.siswa.no_un" type="text" class="input-form" placeholder="Nomor Ujian Nasional">
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Berkebutuhan Khusus</label>
                                    <select v-model="form.siswa.berkebutuhan_khusus" class="input-form">
                                        <option value="">Tidak Ada</option>
                                        <option value="Tunanetra">Tunanetra</option>
                                        <option value="Tunarungu">Tunarungu</option>
                                        <option value="Tunawicara">Tunawicara</option>
                                        <option value="Tunadaksa">Tunadaksa</option>
                                        <option value="Tunagrahita">Tunagrahita</option>
                                        <option value="Autis">Autis</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 2: Alamat & Kontak -->
                        <div v-if="step === 2" class="space-y-6 md:space-y-8 animate-fadeIn">
                            <div class="border-b border-gray-100 pb-4">
                                <h2 class="text-xl md:text-2xl font-bold text-gray-800 text-center md:text-left">Alamat & Kontak</h2>
                                <p class="text-gray-500 text-sm text-center md:text-left">Informasi tempat tinggal dan kontak yang bisa dihubungi.</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="col-span-1 lg:col-span-2">
                                    <label class="label-form">Alamat Lengkap</label>
                                    <textarea v-model="form.siswa.alamat" class="input-form" rows="3" placeholder="Nama Jalan, No. Rumah, dll" required></textarea>
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Dusun/Kampung</label>
                                    <input v-model="form.siswa.dusun" type="text" class="input-form" placeholder="Nama Dusun / Kampung">
                                </div>

                                <div class="col-span-1 grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="label-form">RT</label>
                                        <input v-model="form.siswa.rt" type="text" class="input-form" placeholder="001">
                                    </div>
                                    <div>
                                        <label class="label-form">RW</label>
                                        <input v-model="form.siswa.rw" type="text" class="input-form" placeholder="001">
                                    </div>
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Provinsi</label>
                                    <select v-model="selectedProvId" @change="onProvChange" class="input-form" required>
                                        <option value="">Pilih Provinsi...</option>
                                        <option v-for="p in provinces" :key="p.id" :value="p.id">{{ p.nama }}</option>
                                    </select>
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Kabupaten/Kota</label>
                                    <select v-model="selectedRegId" @change="onRegChange" class="input-form" :disabled="!regencies.length" required>
                                        <option value="">Pilih Kabupaten/Kota...</option>
                                        <option v-for="r in regencies" :key="r.id" :value="r.id">{{ r.nama }}</option>
                                    </select>
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Kecamatan</label>
                                    <select v-model="selectedDistId" @change="onDistChange" class="input-form" :disabled="!districts.length" required>
                                        <option value="">Pilih Kecamatan...</option>
                                        <option v-for="d in districts" :key="d.id" :value="d.id">{{ d.nama }}</option>
                                    </select>
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Kelurahan/Desa</label>
                                    <select v-model="selectedVillId" @change="onVillChange" class="input-form" :disabled="!villages.length" required>
                                        <option value="">Pilih Kelurahan/Desa...</option>
                                        <option v-for="v in villages" :key="v.id" :value="v.id">{{ v.nama }}</option>
                                    </select>
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">No. HP (WhatsApp)</label>
                                    <input v-model="form.siswa.no_hp" type="text" class="input-form" placeholder="08xxxxxxxxxx" required>
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">No. Telpon Rumah</label>
                                    <input v-model="form.siswa.no_telp_rumah" type="text" class="input-form" placeholder="Nomor Telepon Rumah">
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Alat Transportasi Ke Sekolah</label>
                                    <select v-model="form.siswa.alat_transportasi" class="input-form">
                                        <option value="">Pilih Transportasi...</option>
                                        <option value="Jalan kaki">Jalan kaki</option>
                                        <option value="Sepeda">Sepeda</option>
                                        <option value="Motor">Motor</option>
                                        <option value="Mobil pribadi">Mobil pribadi</option>
                                        <option value="Antar jemput">Antar jemput</option>
                                        <option value="Angkutan umum">Angkutan umum</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Jenis Tempat Tinggal</label>
                                    <select v-model="form.siswa.jenis_tinggal" class="input-form">
                                        <option value="">Pilih Tempat Tinggal...</option>
                                        <option value="Bersama Orangtua">Bersama Orangtua</option>
                                        <option value="Wali">Wali</option>
                                        <option value="Kos">Kos</option>
                                        <option value="Asrama">Asrama</option>
                                        <option value="Panti Asuhan">Panti Asuhan</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Kode Pos</label>
                                    <input v-model="form.siswa.kode_pos" type="text" class="input-form" placeholder="41234" maxlength="10">
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Email Pribadi</label>
                                    <input v-model="form.siswa.email_pribadi" type="email" class="input-form" placeholder="email@contoh.com">
                                </div>
                            </div>
                        </div>

                        <!-- STEP 3: Data Orang Tua -->
                        <div v-if="step === 3" class="space-y-8 md:space-y-12 animate-fadeIn">
                            <!-- AYAH -->
                            <div class="space-y-6">
                                <div class="border-b border-gray-100 pb-4">
                                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 text-center md:text-left">Data Ayah Kandung</h2>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="col-span-1 lg:col-span-2">
                                        <label class="label-form">Nama Lengkap Ayah</label>
                                        <input v-model="form.ayah.nama" type="text" class="input-form" placeholder="Nama Lengkap Ayah Kandung" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">NIK Ayah</label>
                                        <input v-model="form.ayah.nik" type="text" class="input-form" placeholder="16 Digit NIK Ayah">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Tahun Lahir</label>
                                        <select v-model="form.ayah.tahun_lahir" class="input-form">
                                            <option value="">Pilih Tahun</option>
                                            <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Pendidikan</label>
                                        <input v-model="form.ayah.pendidikan" type="text" class="input-form" placeholder="Contoh: S1, SMA, SMP">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Pekerjaan</label>
                                        <input v-model="form.ayah.pekerjaan" type="text" class="input-form" placeholder="Contoh: Petani, Wiraswasta">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Penghasilan Bulanan</label>
                                        <select v-model="form.ayah.penghasilan_bulanan" class="input-form">
                                            <option value="">Pilih Penghasilan...</option>
                                            <option value="Kurang dari Rp 1.000.000">Kurang dari Rp 1.000.000</option>
                                            <option value="Rp 1.000.000 - Rp 2.000.000">Rp 1.000.000 - Rp 2.000.000</option>
                                            <option value="Rp 2.000.000 - Rp 5.000.000">Rp 2.000.000 - Rp 5.000.000</option>
                                            <option value="Rp 5.000.000 - Rp 10.000.000">Rp 5.000.000 - Rp 10.000.000</option>
                                            <option value="Lebih dari Rp 10.000.000">Lebih dari Rp 10.000.000</option>
                                            <option value="Tidak berpenghasilan">Tidak berpenghasilan</option>
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Berkebutuhan Khusus</label>
                                        <select v-model="form.ayah.berkebutuhan_khusus" class="input-form">
                                            <option value="">Tidak Ada</option>
                                            <option value="Tunanetra">Tunanetra</option>
                                            <option value="Tunarungu">Tunarungu</option>
                                            <option value="Tunawicara">Tunawicara</option>
                                            <option value="Tunadaksa">Tunadaksa</option>
                                            <option value="Tunagrahita">Tunagrahita</option>
                                            <option value="Autis">Autis</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- IBU -->
                            <div class="space-y-6">
                                <div class="border-b border-gray-100 pb-4">
                                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 text-center md:text-left">Data Ibu Kandung</h2>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="col-span-1 lg:col-span-2">
                                        <label class="label-form">Nama Lengkap Ibu</label>
                                        <input v-model="form.ibu.nama" type="text" class="input-form" placeholder="Nama Lengkap Ibu Kandung" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">NIK Ibu</label>
                                        <input v-model="form.ibu.nik" type="text" class="input-form" placeholder="16 Digit NIK Ibu">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Tahun Lahir</label>
                                        <select v-model="form.ibu.tahun_lahir" class="input-form">
                                            <option value="">Pilih Tahun</option>
                                            <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Pendidikan</label>
                                        <input v-model="form.ibu.pendidikan" type="text" class="input-form" placeholder="Contoh: S1, SMA, SMP">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Pekerjaan</label>
                                        <input v-model="form.ibu.pekerjaan" type="text" class="input-form" placeholder="Contoh: Ibu Rumah Tangga, Guru">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Penghasilan Bulanan</label>
                                        <select v-model="form.ibu.penghasilan_bulanan" class="input-form">
                                            <option value="">Pilih Penghasilan...</option>
                                            <option value="Kurang dari Rp 1.000.000">Kurang dari Rp 1.000.000</option>
                                            <option value="Rp 1.000.000 - Rp 2.000.000">Rp 1.000.000 - Rp 2.000.000</option>
                                            <option value="Rp 2.000.000 - Rp 5.000.000">Rp 2.000.000 - Rp 5.000.000</option>
                                            <option value="Rp 5.000.000 - Rp 10.000.000">Rp 5.000.000 - Rp 10.000.000</option>
                                            <option value="Lebih dari Rp 10.000.000">Lebih dari Rp 10.000.000</option>
                                            <option value="Tidak berpenghasilan">Tidak berpenghasilan</option>
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Berkebutuhan Khusus</label>
                                        <select v-model="form.ibu.berkebutuhan_khusus" class="input-form">
                                            <option value="">Tidak Ada</option>
                                            <option value="Tunanetra">Tunanetra</option>
                                            <option value="Tunarungu">Tunarungu</option>
                                            <option value="Tunawicara">Tunawicara</option>
                                            <option value="Tunadaksa">Tunadaksa</option>
                                            <option value="Tunagrahita">Tunagrahita</option>
                                            <option value="Autis">Autis</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- WALI (OPSIONAL) -->
                            <div class="space-y-6">
                                <div class="border-b border-gray-100 pb-4">
                                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 text-center md:text-left">Data Wali Siswa (Opsional)</h2>
                                    <p class="text-gray-500 text-sm">Lengkapi jika calon santri tidak tinggal bersama orang tua kandung.</p>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="col-span-1 lg:col-span-2">
                                        <label class="label-form">Nama Lengkap Wali</label>
                                        <input v-model="form.wali.nama" type="text" class="input-form" placeholder="Nama Lengkap Wali">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">NIK Wali</label>
                                        <input v-model="form.wali.nik" type="text" class="input-form" placeholder="16 Digit NIK Wali" maxlength="16">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Tahun Lahir</label>
                                        <select v-model="form.wali.tahun_lahir" class="input-form">
                                            <option value="">Pilih Tahun</option>
                                            <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Pendidikan</label>
                                        <input v-model="form.wali.pendidikan" type="text" class="input-form" placeholder="SD/SMP/SMA/S1">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Pekerjaan</label>
                                        <input v-model="form.wali.pekerjaan" type="text" class="input-form" placeholder="Pekerjaan Wali">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Penghasilan Bulanan</label>
                                        <select v-model="form.wali.penghasilan_bulanan" class="input-form">
                                            <option value="">Pilih Penghasilan...</option>
                                            <option value="Kurang dari Rp 1.000.000">Kurang dari Rp 1.000.000</option>
                                            <option value="Rp 1.000.000 - Rp 2.000.000">Rp 1.000.000 - Rp 2.000.000</option>
                                            <option value="Rp 2.000.000 - Rp 5.000.000">Rp 2.000.000 - Rp 5.000.000</option>
                                            <option value="Rp 5.000.000 - Rp 10.000.000">Rp 5.000.000 - Rp 10.000.000</option>
                                            <option value="Lebih dari Rp 10.000.000">Lebih dari Rp 10.000.000</option>
                                            <option value="Tidak berpenghasilan">Tidak berpenghasilan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 4: Data Periodik & Tambahan -->
                        <div v-if="step === 4" class="space-y-6 md:space-y-8 animate-fadeIn">
                            <div class="border-b border-gray-100 pb-4">
                                <h2 class="text-xl md:text-2xl font-bold text-gray-800 text-center md:text-left">Data Periodik & Lainnya</h2>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="col-span-1 grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="label-form">Tinggi (cm)</label>
                                        <input v-model="form.periodik.tinggi_badan" type="number" class="input-form" placeholder="150">
                                    </div>
                                    <div>
                                        <label class="label-form">Berat (kg)</label>
                                        <input v-model="form.periodik.berat_badan" type="number" class="input-form" placeholder="45">
                                    </div>
                                </div>

                                <div class="col-span-1 text-center md:text-left">
                                    <label class="label-form">Jumlah Saudara Kandung</label>
                                    <input v-model="form.periodik.jumlah_saudara_kandung" type="number" class="input-form" placeholder="Contoh: 2">
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Anak Keberapa</label>
                                    <input v-model="form.siswa.anak_keberapa" type="number" class="input-form" placeholder="Contoh: 1">
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Ukuran Pakaian</label>
                                    <select v-model="form.siswa.ukuran_pakaian" class="input-form">
                                        <option value="">Pilih Ukuran</option>
                                        <option v-for="u in ['S', 'M', 'L', 'XL', 'XXL', 'XXXL']" :key="u" :value="u">{{ u }}</option>
                                    </select>
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">No. Sepatu</label>
                                    <input v-model="form.siswa.no_sepatu" type="text" class="input-form" placeholder="Contoh: 38">
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">No. Kopiyah</label>
                                    <input v-model="form.siswa.no_kopiyah" type="text" class="input-form" placeholder="Contoh: 56">
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Jarak Tempat Tinggal ke Sekolah</label>
                                    <select v-model="form.periodik.jarak_ke_sekolah" class="input-form">
                                        <option value="">Pilih Jarak...</option>
                                        <option value="Kurang dari 1 km">Kurang dari 1 km</option>
                                        <option value="Lebih dari 1 km">Lebih dari 1 km</option>
                                    </select>
                                </div>

                                <div v-if="form.periodik.jarak_ke_sekolah === 'Lebih dari 1 km'" class="col-span-1">
                                    <label class="label-form">Sebutkan Jarak (km)</label>
                                    <input v-model="form.periodik.jarak_km" type="number" class="input-form" placeholder="Jarak dalam Kilometer">
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Waktu Tempuh Berangkat ke Sekolah</label>
                                    <select v-model="form.periodik.waktu_tempuh" class="input-form">
                                        <option value="">Pilih Waktu...</option>
                                        <option value="Kurang dari 60 menit">Kurang dari 60 menit</option>
                                        <option value="Lebih dari 60 menit">Lebih dari 60 menit</option>
                                    </select>
                                </div>

                                <div v-if="form.periodik.waktu_tempuh === 'Lebih dari 60 menit'" class="col-span-1">
                                    <label class="label-form">Sebutkan Waktu (menit)</label>
                                    <input v-model="form.periodik.waktu_menit" type="number" class="input-form" placeholder="Waktu dalam Menit">
                                </div>
                            </div>

                            <!-- DATA BANTUAN SOSIAL & REKENING -->
                            <div class="space-y-4 pt-6">
                                <div class="border-b border-gray-100 pb-2 mb-4">
                                    <h3 class="text-lg font-bold text-gray-800">Data Bantuan Sosial & Rekening</h3>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- KPS / PKH -->
                                    <div class="space-y-4 bg-gray-50 p-4 rounded-2xl">
                                        <h4 class="font-bold text-gray-700 text-sm">Program KPS / PKH</h4>
                                        <div>
                                            <label class="label-form">Penerima KPS/PKH?</label>
                                            <select v-model="form.siswa.penerima_kps_pkh" class="input-form">
                                                <option :value="false">Tidak</option>
                                                <option :value="true">Ya</option>
                                            </select>
                                        </div>
                                        <div v-if="form.siswa.penerima_kps_pkh">
                                            <label class="label-form">No. KPS/PKH</label>
                                            <input v-model="form.siswa.no_kps_pkh" type="text" class="input-form" placeholder="Nomor KPS/PKH">
                                        </div>
                                        <div v-if="form.siswa.penerima_kps_pkh">
                                            <label class="label-form">No. KKS</label>
                                            <input v-model="form.siswa.no_kks" type="text" class="input-form" placeholder="Nomor Kartu Keluarga Sejahtera">
                                        </div>
                                        <div v-if="form.siswa.penerima_kps_pkh" class="pt-2">
                                            <label class="label-form text-xs">Upload Kartu KPS/PKH (Opsional)</label>
                                            <input type="file" @change="handleFileUpload($event, 'kartu_kps')" class="input-form-sm" accept="image/*,.pdf">
                                            <p v-if="form.berkas.kartu_kps" class="text-[10px] text-emerald-600 font-bold mt-1">✓ {{ form.berkas.kartu_kps.name }}</p>
                                        </div>
                                    </div>

                                    <!-- KIP -->
                                    <div class="space-y-4 bg-gray-50 p-4 rounded-2xl">
                                        <h4 class="font-bold text-gray-700 text-sm">Program Indonesia Pintar (KIP)</h4>
                                        <div>
                                            <label class="label-form">Penerima KIP?</label>
                                            <select v-model="form.siswa.penerima_kip" class="input-form">
                                                <option :value="false">Tidak</option>
                                                <option :value="true">Ya</option>
                                            </select>
                                        </div>
                                        <div v-if="form.siswa.penerima_kip">
                                            <label class="label-form">No. KIP</label>
                                            <input v-model="form.siswa.no_kip" type="text" class="input-form" placeholder="Nomor Kartu Indonesia Pintar">
                                        </div>
                                        <div v-if="form.siswa.penerima_kip">
                                            <label class="label-form">Nama di KIP</label>
                                            <input v-model="form.siswa.nama_tertera_kip" type="text" class="input-form" placeholder="Nama tertera di kartu KIP">
                                        </div>
                                        <div v-if="form.siswa.penerima_kip">
                                            <label class="label-form">Terima Fisik Kartu KIP?</label>
                                            <select v-model="form.siswa.terima_fisik_kip" class="input-form">
                                                <option :value="false">Tidak</option>
                                                <option :value="true">Ya</option>
                                            </select>
                                        </div>
                                        <div v-if="form.siswa.penerima_kip" class="pt-2">
                                            <label class="label-form text-xs">Upload Kartu KIP (Opsional)</label>
                                            <input type="file" @change="handleFileUpload($event, 'kartu_kip')" class="input-form-sm" accept="image/*,.pdf">
                                            <p v-if="form.berkas.kartu_kip" class="text-[10px] text-emerald-600 font-bold mt-1">✓ {{ form.berkas.kartu_kip.name }}</p>
                                        </div>
                                    </div>

                                    <!-- PIP -->
                                    <div class="space-y-4 bg-gray-50 p-4 rounded-2xl">
                                        <h4 class="font-bold text-gray-700 text-sm">Status PIP</h4>
                                        <div>
                                            <label class="label-form">Layak PIP?</label>
                                            <select v-model="form.siswa.layak_pip" class="input-form">
                                                <option :value="false">Tidak</option>
                                                <option :value="true">Ya</option>
                                            </select>
                                        </div>
                                        <div v-if="form.siswa.layak_pip">
                                            <label class="label-form">Alasan PIP</label>
                                            <select v-model="form.siswa.alasan_layak_pip" class="input-form">
                                                <option value="">Pilih Alasan...</option>
                                                <option value="Pemegang PKH/KPS/KIP">Pemegang PKH/KPS/KIP</option>
                                                <option value="Penerima BSM 2014">Penerima BSM 2014</option>
                                                <option value="Yatim Piatu / Panti Asuhan / Panti Sosial">Yatim Piatu / Panti Asuhan / Panti Sosial</option>
                                                <option value="Pernah DO (Drop Out)">Pernah DO (Drop Out)</option>
                                                <option value="Siswa Miskin / Rentan Miskin">Siswa Miskin / Rentan Miskin</option>
                                                <option value="Daerah Konflik">Daerah Konflik</option>
                                                <option value="Keluarga Terpidana">Keluarga Terpidana</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Rekening -->
                                    <div class="space-y-4 bg-gray-50 p-4 rounded-2xl">
                                        <h4 class="font-bold text-gray-700 text-sm">Data Rekening Bank</h4>
                                        <div>
                                            <label class="label-form">Bank Rekening</label>
                                            <input v-model="form.siswa.bank_rekening" type="text" class="input-form" placeholder="Contoh: BRI, BNI">
                                        </div>
                                        <div>
                                            <label class="label-form">No. Rekening</label>
                                            <input v-model="form.siswa.no_rek_bank" type="text" class="input-form" placeholder="Nomor rekening bank">
                                        </div>
                                        <div>
                                            <label class="label-form">Rekening Atas Nama</label>
                                            <input v-model="form.siswa.rekening_atas_nama" type="text" class="input-form" placeholder="Nama pemilik rekening">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PRESTASI -->
                            <div class="space-y-4 pt-6">
                                <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                                    <h3 class="text-lg font-bold text-gray-800">Data Prestasi (Jika Ada)</h3>
                                    <button type="button" @click="addPrestasi" class="text-sm font-bold text-emerald-600 hover:text-emerald-700">+ Tambah Baris</button>
                                </div>
                                <div v-for="(item, index) in form.prestasi" :key="index" class="grid grid-cols-1 md:grid-cols-5 gap-3 p-4 bg-gray-50 rounded-2xl relative">
                                    <div class="col-span-1"><label class="text-[10px] font-bold text-gray-400 uppercase md:hidden">Jenis</label><input v-model="item.jenis_prestasi" type="text" class="input-form-sm" placeholder="Jenis"></div>
                                    <div class="col-span-1"><label class="text-[10px] font-bold text-gray-400 uppercase md:hidden">Tingkat</label><input v-model="item.tingkat" type="text" class="input-form-sm" placeholder="Tingkat"></div>
                                    <div class="col-span-1"><label class="text-[10px] font-bold text-gray-400 uppercase md:hidden">Nama Prestasi</label><input v-model="item.nama_prestasi" type="text" class="input-form-sm" placeholder="Nama Prestasi"></div>
                                    <div class="col-span-1"><label class="text-[10px] font-bold text-gray-400 uppercase md:hidden">Tahun</label><input v-model="item.tahun" type="number" class="input-form-sm" placeholder="Tahun"></div>
                                    <div class="col-span-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase md:hidden">Penyelenggara</label>
                                        <div class="flex gap-2">
                                            <input v-model="item.penyelenggara" type="text" class="input-form-sm flex-1" placeholder="Penyelenggara">
                                            <button @click="removePrestasi(index)" type="button" class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- BEASISWA -->
                            <div class="space-y-4 pt-6">
                                <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                                    <h3 class="text-lg font-bold text-gray-800">Data Beasiswa (Jika Ada)</h3>
                                    <button type="button" @click="addBeasiswa" class="text-sm font-bold text-emerald-600 hover:text-emerald-700">+ Tambah Baris</button>
                                </div>
                                <div v-for="(item, index) in form.beasiswa" :key="index" class="grid grid-cols-1 md:grid-cols-4 gap-3 p-4 bg-gray-50 rounded-2xl relative">
                                    <div class="col-span-1"><label class="text-[10px] font-bold text-gray-400 uppercase md:hidden">Jenis Beasiswa</label><input v-model="item.jenis_beasiswa" type="text" class="input-form-sm" placeholder="Jenis Beasiswa"></div>
                                    <div class="col-span-1"><label class="text-[10px] font-bold text-gray-400 uppercase md:hidden">Sumber</label><input v-model="item.penyelenggara_sumber" type="text" class="input-form-sm" placeholder="Sumber"></div>
                                    <div class="col-span-1"><label class="text-[10px] font-bold text-gray-400 uppercase md:hidden">Tahun Mulai</label><input v-model="item.tahun_mulai" type="number" class="input-form-sm" placeholder="Thn Mulai"></div>
                                    <div class="col-span-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase md:hidden">Tahun Selesai</label>
                                        <div class="flex gap-2">
                                            <input v-model="item.tahun_selesai" type="number" class="input-form-sm flex-1" placeholder="Thn Selesai">
                                            <button @click="removeBeasiswa(index)" type="button" class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Persetujuan Data Valid -->
                            <div class="bg-blue-50 border border-blue-100 p-6 rounded-2xl mt-8">
                                <p class="text-sm text-blue-800 font-medium leading-relaxed">
                                    Dengan melanjutkan pendaftaran, saya menyatakan bahwa seluruh data yang saya isi adalah benar dan dapat dipertanggungjawabkan secara hukum.
                                </p>
                            </div>
                        </div>

                        <!-- STEP 5: Upload Berkas -->
                        <div v-if="step === 5" class="space-y-10 animate-fadeIn">

                            <!-- Section Header -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-extrabold text-gray-900">Upload Berkas Pendaftaran</h2>
                                    <p class="text-gray-500 text-sm mt-1">Pastikan berkas jelas dan dapat terbaca. Format: <span class="font-semibold text-emerald-600">JPG, PNG, atau PDF</span> · Maks <span class="font-semibold text-emerald-600">2MB</span> per file.</p>
                                </div>
                            </div>

                            <!-- Berkas Wajib -->
                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center gap-1.5 bg-red-100 text-red-700 text-xs font-bold px-3 py-1.5 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
                                        WAJIB DILENGKAPI
                                    </span>
                                    <div class="flex-1 h-px bg-gradient-to-r from-red-100 to-transparent"></div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                                    <div v-for="field in berkasWajib" :key="field.key" class="flex flex-col">
                                        <div class="mb-3">
                                            <p class="text-sm font-bold text-gray-800 flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                {{ field.label }}
                                            </p>
                                            <p class="text-[11px] text-gray-400 font-medium mt-1 min-h-[32px] leading-tight">
                                                {{ field.desc }}
                                            </p>
                                        </div>
                                        <label class="relative block cursor-pointer group flex-1 h-full flex flex-col">
                                            <!-- Empty State -->
                                            <div v-if="!form.berkas[field.key] && !existingBerkas[field.key]"
                                                class="relative overflow-hidden rounded-2xl border-2 border-dashed border-gray-200 bg-gradient-to-b from-gray-50 to-white px-6 py-8 text-center transition-all duration-200 group-hover:border-emerald-400 group-hover:from-emerald-50/50 group-hover:to-white group-hover:shadow-lg group-hover:shadow-emerald-100 flex-1 flex flex-col justify-center">
                                                <div class="w-14 h-14 rounded-xl bg-emerald-100 flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-200 transition-colors">
                                                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                    </svg>
                                                </div>
                                                <p class="text-sm font-semibold text-gray-700 group-hover:text-emerald-700">Pilih File</p>
                                                <p class="text-xs text-gray-400 mt-1">JPG · PNG · PDF · Maks 2MB</p>
                                            </div>
                                            <!-- Existing file (from server) -->
                                            <div v-else-if="!form.berkas[field.key] && existingBerkas[field.key]"
                                                class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-b from-emerald-50 to-white px-6 py-8 text-center transition-all duration-200 group-hover:border-emerald-500 group-hover:shadow-lg group-hover:shadow-emerald-100 flex-1 flex flex-col justify-center">
                                                <div class="w-14 h-14 rounded-xl bg-emerald-100 flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-200 transition-colors">
                                                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                                <p class="text-xs font-bold text-emerald-700">Sudah Diunggah</p>
                                                <p class="text-xs text-emerald-600 mt-1 truncate px-2">{{ existingBerkas[field.key].split('/').pop() }}</p>
                                                <p class="text-xs text-gray-400 mt-2">Klik untuk ganti file</p>
                                                <span class="absolute top-3 right-3 bg-emerald-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">✓</span>
                                            </div>
                                            <!-- Filled State (new file selected) -->
                                            <div v-else
                                                class="relative overflow-hidden rounded-2xl border-2 border-emerald-400 bg-gradient-to-b from-emerald-50 to-white px-6 py-8 text-center shadow-md shadow-emerald-100 flex-1 flex flex-col justify-center">
                                                <div class="w-14 h-14 rounded-xl bg-emerald-500 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-200">
                                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                                <p class="text-sm font-bold text-emerald-700">Berhasil Dipilih</p>
                                                <p class="text-xs text-emerald-600 mt-1 truncate px-2">{{ form.berkas[field.key].name }}</p>
                                                <span class="absolute top-3 right-3 bg-emerald-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">✓</span>
                                            </div>
                                            <input type="file" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" @change="e => handleFileUpload(e, field.key)" accept=".jpg,.jpeg,.png,.pdf">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Berkas Opsional -->
                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                        OPSIONAL — Bagi yang Memiliki
                                    </span>
                                    <div class="flex-1 h-px bg-gradient-to-r from-blue-100 to-transparent"></div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                                    <div v-for="field in berkasOpsional" :key="field.key" class="flex flex-col">
                                        <div class="mb-3">
                                            <p class="text-sm font-bold text-gray-800 flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                {{ field.label }}
                                            </p>
                                            <p class="text-[11px] text-gray-400 font-medium mt-1 min-h-[32px] leading-tight">
                                                {{ field.desc }}
                                            </p>
                                        </div>
                                        <label class="relative block cursor-pointer group flex-1 h-full flex flex-col">
                                            <div v-if="!form.berkas[field.key] && !existingBerkas[field.key]"
                                                class="relative overflow-hidden rounded-2xl border-2 border-dashed border-gray-200 bg-gradient-to-b from-gray-50 to-white px-6 py-8 text-center transition-all duration-200 group-hover:border-blue-400 group-hover:from-blue-50/50 group-hover:to-white group-hover:shadow-lg group-hover:shadow-blue-100 flex-1 flex flex-col justify-center">
                                                <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                                                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                    </svg>
                                                </div>
                                                <p class="text-sm font-semibold text-gray-700 group-hover:text-blue-700">Pilih File</p>
                                                <p class="text-xs text-gray-400 mt-1">JPG · PNG · PDF · Maks 2MB</p>
                                            </div>
                                            <!-- Existing file (from server) -->
                                            <div v-else-if="!form.berkas[field.key] && existingBerkas[field.key]"
                                                class="relative overflow-hidden rounded-2xl border-2 border-blue-300 bg-gradient-to-b from-blue-50 to-white px-6 py-8 text-center transition-all duration-200 group-hover:border-blue-500 group-hover:shadow-lg flex-1 flex flex-col justify-center">
                                                <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center mx-auto mb-4">
                                                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                                <p class="text-xs font-bold text-blue-700">Sudah Diunggah</p>
                                                <p class="text-xs text-blue-600 mt-1 truncate px-2">{{ existingBerkas[field.key].split('/').pop() }}</p>
                                                <p class="text-xs text-gray-400 mt-2">Klik untuk ganti file</p>
                                                <span class="absolute top-3 right-3 bg-blue-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">✓</span>
                                            </div>
                                            <div v-else
                                                class="relative overflow-hidden rounded-2xl border-2 border-blue-400 bg-gradient-to-b from-blue-50 to-white px-6 py-8 text-center shadow-md shadow-blue-100 flex-1 flex flex-col justify-center">
                                                <div class="w-14 h-14 rounded-xl bg-blue-500 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-200">
                                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                                <p class="text-sm font-bold text-blue-700">Berhasil Dipilih</p>
                                                <p class="text-xs text-blue-600 mt-1 truncate px-2">{{ form.berkas[field.key].name }}</p>
                                                <span class="absolute top-3 right-3 bg-blue-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">✓</span>
                                            </div>
                                            <input type="file" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" @change="e => handleFileUpload(e, field.key)" accept=".jpg,.jpeg,.png,.pdf">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Pernyataan -->
                            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 p-6 shadow-xl shadow-emerald-200">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                                <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                                <div class="relative flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-white/90 font-medium leading-relaxed">
                                        Dengan menekan tombol <strong class="text-white">{{ isAccepted ? '"Simpan Perubahan"' : '"Kirim Pendaftaran"' }}</strong>, saya menyatakan bahwa seluruh data dan berkas yang saya unggah adalah <strong class="text-white">benar dan dapat dipertanggungjawabkan</strong> secara hukum. Data palsu dapat menyebabkan pembatalan pendaftaran.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Footer Navigation -->
                    <div class="bg-gray-50 px-4 md:px-8 py-4 md:py-6 flex items-center justify-between border-t border-gray-100 rounded-b-2xl md:rounded-b-3xl">
                        <button 
                            v-if="step > 1" 
                            type="button" 
                            @click="step--"
                            class="px-3 md:px-6 py-2.5 md:py-3 text-sm md:text-base font-bold rounded-xl transition-colors flex items-center gap-1 md:gap-2"
                            :class="form.tingkat === 'smp' ? 'text-[#1A4080] hover:bg-[#D6E4F5]' : 'text-slate-700 hover:bg-slate-200'"
                        >
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Sebelumnya
                        </button>
                        <div v-else></div>

                        <button 
                            v-if="step < totalSteps" 
                            type="button" 
                            @click="nextStep"
                            class="text-white px-4 md:px-8 py-2.5 md:py-3 rounded-xl text-sm md:text-base font-bold transition-all shadow-lg flex items-center gap-1 md:gap-2"
                            :class="form.tingkat === 'smp' ? 'bg-[#2E5FA3] hover:bg-[#245090] shadow-[#2E5FA3]/40' : 'bg-slate-600 hover:bg-slate-700 shadow-slate-200'"
                        >
                            Selanjutnya
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <button 
                            v-if="step === totalSteps"
                            type="submit"
                            :disabled="form.processing"
                            class="relative text-white px-5 md:px-10 py-2.5 md:py-3 rounded-xl text-sm md:text-base font-bold active:scale-95 transition-all shadow-lg disabled:opacity-60 disabled:cursor-not-allowed flex items-center gap-1 md:gap-2"
                            :class="form.tingkat === 'smp' ? 'bg-[#2E5FA3] hover:bg-[#245090] shadow-[#2E5FA3]/40' : 'bg-slate-600 hover:bg-slate-700 shadow-slate-200'"
                        >
                            <svg v-if="form.processing" class="animate-spin h-4 w-4 md:h-5 md:w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            {{ form.processing ? (isAccepted ? 'Menyimpan...' : 'Mengirim...') : (isAccepted ? 'Simpan Perubahan' : 'Kirim') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Contoh No. Registrasi Akta Kelahiran -->
    <transition name="fade">
        <div v-if="showAktaExample" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="showAktaExample = false">
            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100 transform transition-all animate-scaleUp">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Posisi Nomor Registrasi Akta Kelahiran
                    </h3>
                    <button type="button" @click="showAktaExample = false" class="text-white/80 hover:text-white hover:bg-white/10 rounded-full p-1 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="p-6">
                    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-amber-800 font-medium">
                                    Perhatian: Masukkan nomor registrasi resmi yang tertera di bagian kanan atas atau tengah lembar kutipan akta kelahiran Anda (bukan NIK).
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-center border border-gray-200 rounded-xl overflow-hidden shadow-inner bg-gray-50 p-2">
                        <img src="/No Registrasi Akta Kelahiran.jpg" alt="Contoh No Registrasi Akta Kelahiran" loading="lazy" class="max-h-[60vh] object-contain rounded-lg">
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 flex justify-end border-t border-gray-100">
                    <button type="button" @click="showAktaExample = false" class="px-5 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-xl shadow-md transition-all">
                        Mengerti, Tutup
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

// Gunakan key yang unik per user agar data tidak bocor antar akun di browser yang sama
const page = usePage();
const STORAGE_KEY = computed(() => {
    const userId = page.props.auth?.user?.id;
    return userId ? `ppdb_draft_user_${userId}` : null;
});

// Props from server
const props = defineProps({
    existingData: Object,
    ppdbSetting: Object,
});

const isAccepted = computed(() => {
    return ['diterima', 'diterima_ula', 'diterima_idadiyah', 'diterima_wustho', 'diterima_ulya'].includes(props.existingData?.status);
});

const step = ref(1);
const showAktaExample = ref(false);
const totalSteps = 5;
const stepLabels = ['Data Diri', 'Alamat', 'Orang Tua', 'Tambahan', 'Berkas'];

// Generate years for dropdown (e.g., from current year down to 1940)
const years = ref([]);
const generateYears = () => {
    const currentYear = new Date().getFullYear();
    for (let y = currentYear; y >= 1940; y--) {
        years.value.push(y);
    }
};

// Region API States
const apiUrl = 'https://ibnux.github.io/data-indonesia';
const provinces = ref([]);
const regencies = ref([]);
const districts = ref([]);
const villages = ref([]);

const selectedProvId = ref('');
const selectedRegId = ref('');
const selectedDistId = ref('');
const selectedVillId = ref('');

// Berkas yang sudah ada di server (edit mode)
const existingBerkas = ref({});

// Priority: existingData (server) > localStorage draft > empty
const loadDraft = () => {
    if (props.existingData) return null; // skip draft if server data exists
    if (!STORAGE_KEY.value) return null; // skip if no user ID (should not happen on protected route)
    try {
        const saved = localStorage.getItem(STORAGE_KEY.value);
        return saved ? JSON.parse(saved) : null;
    } catch {
        return null;
    }
};

const draft = loadDraft();
const ex = props.existingData; // shorthand

// Populate existingBerkas from server data (edit mode)
if (ex?.berkas) {
    const berkasKeys = [
        'ijazah_skhu', 'rapot_legalisir', 'akte_kelahiran', 'ktp_orang_tua', 'kartu_keluarga',
        'surat_sehat', 'surat_kelakuan_baik', 'kartu_kks_pkh', 'kartu_kps',
        'kartu_kip', 'kartu_kis_bpjs', 'kartu_nisn'
    ];
    berkasKeys.forEach(key => {
        if (ex.berkas[key]) existingBerkas.value[key] = ex.berkas[key];
    });
}

const form = useForm({
    tingkat: ex?.tingkat ?? draft?.tingkat ?? 'smp',
    siswa: {
        nama_lengkap:    ex?.siswa?.nama_lengkap    ?? draft?.siswa?.nama_lengkap    ?? '',
        asal_sekolah:    ex?.siswa?.asal_sekolah    ?? draft?.siswa?.asal_sekolah    ?? '',
        jenis_kelamin:   ex?.siswa?.jenis_kelamin   ?? draft?.siswa?.jenis_kelamin   ?? '',
        nisn:            ex?.siswa?.nisn            ?? draft?.siswa?.nisn            ?? '',
        nik:             ex?.siswa?.nik             ?? draft?.siswa?.nik             ?? '',
        tempat_lahir:    ex?.siswa?.tempat_lahir    ?? draft?.siswa?.tempat_lahir    ?? '',
        tanggal_lahir:   ex?.siswa?.tanggal_lahir   ?? draft?.siswa?.tanggal_lahir   ?? '',
        agama:           ex?.siswa?.agama           ?? draft?.siswa?.agama           ?? 'Islam',
        no_registrasi_akta: ex?.siswa?.no_registrasi_akta ?? draft?.siswa?.no_registrasi_akta ?? '',
        kewarganegaraan: ex?.siswa?.kewarganegaraan ?? draft?.siswa?.kewarganegaraan ?? 'Indonesia',
        nama_negara:     ex?.siswa?.nama_negara     ?? draft?.siswa?.nama_negara     ?? '',
        alamat:          ex?.siswa?.alamat          ?? draft?.siswa?.alamat          ?? '',
        dusun:           ex?.siswa?.dusun           ?? draft?.siswa?.dusun           ?? '',
        rt:              ex?.siswa?.rt              ?? draft?.siswa?.rt              ?? '',
        rw:              ex?.siswa?.rw              ?? draft?.siswa?.rw              ?? '',
        kelurahan_desa:  ex?.siswa?.kelurahan_desa  ?? draft?.siswa?.kelurahan_desa  ?? '',
        kecamatan:       ex?.siswa?.kecamatan       ?? draft?.siswa?.kecamatan       ?? '',
        kabupaten_kota:  ex?.siswa?.kabupaten_kota  ?? draft?.siswa?.kabupaten_kota  ?? '',
        provinsi:        ex?.siswa?.provinsi        ?? draft?.siswa?.provinsi        ?? '',
        no_hp:              ex?.siswa?.no_hp           ?? draft?.siswa?.no_hp           ?? '',
        no_telp_rumah:      ex?.siswa?.no_telp_rumah   ?? draft?.siswa?.no_telp_rumah   ?? '',
        email_pribadi:      ex?.siswa?.email_pribadi   ?? draft?.siswa?.email_pribadi   ?? '',
        kode_pos:           ex?.siswa?.kode_pos        ?? draft?.siswa?.kode_pos        ?? '',
        no_ijazah:          ex?.siswa?.no_ijazah       ?? draft?.siswa?.no_ijazah       ?? '',
        no_skhun:           ex?.siswa?.no_skhun        ?? draft?.siswa?.no_skhun        ?? '',
        no_un:              ex?.siswa?.no_un           ?? draft?.siswa?.no_un           ?? '',
        berkebutuhan_khusus: ex?.siswa?.berkebutuhan_khusus ?? draft?.siswa?.berkebutuhan_khusus ?? '',
        ukuran_pakaian:     ex?.siswa?.ukuran_pakaian  ?? draft?.siswa?.ukuran_pakaian  ?? '',
        no_sepatu:          ex?.siswa?.no_sepatu       ?? draft?.siswa?.no_sepatu       ?? '',
        no_kopiyah:         ex?.siswa?.no_kopiyah      ?? draft?.siswa?.no_kopiyah      ?? '',
        alat_transportasi:  ex?.siswa?.alat_transportasi ?? draft?.siswa?.alat_transportasi ?? '',
        jenis_tinggal:      ex?.siswa?.jenis_tinggal   ?? draft?.siswa?.jenis_tinggal   ?? '',
        anak_keberapa:      ex?.siswa?.anak_keberapa   ?? draft?.siswa?.anak_keberapa   ?? '',
        
        penerima_kps_pkh:   ex?.siswa?.penerima_kps_pkh ?? draft?.siswa?.penerima_kps_pkh ?? false,
        no_kps_pkh:         ex?.siswa?.no_kps_pkh       ?? draft?.siswa?.no_kps_pkh       ?? '',
        no_kks:             ex?.siswa?.no_kks           ?? draft?.siswa?.no_kks           ?? '',
        
        penerima_kip:       ex?.siswa?.penerima_kip     ?? draft?.siswa?.penerima_kip     ?? false,
        no_kip:             ex?.siswa?.no_kip           ?? draft?.siswa?.no_kip           ?? '',
        nama_tertera_kip:   ex?.siswa?.nama_tertera_kip ?? draft?.siswa?.nama_tertera_kip ?? '',
        terima_fisik_kip:   ex?.siswa?.terima_fisik_kip ?? draft?.siswa?.terima_fisik_kip ?? false,
        
        layak_pip:          ex?.siswa?.layak_pip        ?? draft?.siswa?.layak_pip        ?? false,
        alasan_layak_pip:   ex?.siswa?.alasan_layak_pip ?? draft?.siswa?.alasan_layak_pip ?? '',
        
        bank_rekening:      ex?.siswa?.bank_rekening    ?? draft?.siswa?.bank_rekening    ?? '',
        no_rek_bank:        ex?.siswa?.no_rek_bank      ?? draft?.siswa?.no_rek_bank      ?? '',
        rekening_atas_nama: ex?.siswa?.rekening_atas_nama ?? draft?.siswa?.rekening_atas_nama ?? '',
    },
    ayah: {
        nama:        ex?.ayah?.nama        ?? draft?.ayah?.nama        ?? '',
        nik:         ex?.ayah?.nik         ?? draft?.ayah?.nik         ?? '',
        tahun_lahir: ex?.ayah?.tahun_lahir ?? draft?.ayah?.tahun_lahir ?? '',
        pekerjaan:   ex?.ayah?.pekerjaan   ?? draft?.ayah?.pekerjaan   ?? '',
        pendidikan:  ex?.ayah?.pendidikan  ?? draft?.ayah?.pendidikan  ?? '',
        penghasilan_bulanan: ex?.ayah?.penghasilan_bulanan ?? draft?.ayah?.penghasilan_bulanan ?? '',
        berkebutuhan_khusus: ex?.ayah?.berkebutuhan_khusus ?? draft?.ayah?.berkebutuhan_khusus ?? '',
    },
    ibu: {
        nama:        ex?.ibu?.nama        ?? draft?.ibu?.nama        ?? '',
        nik:         ex?.ibu?.nik         ?? draft?.ibu?.nik         ?? '',
        tahun_lahir: ex?.ibu?.tahun_lahir ?? draft?.ibu?.tahun_lahir ?? '',
        pekerjaan:   ex?.ibu?.pekerjaan   ?? draft?.ibu?.pekerjaan   ?? '',
        pendidikan:  ex?.ibu?.pendidikan  ?? draft?.ibu?.pendidikan  ?? '',
        penghasilan_bulanan: ex?.ibu?.penghasilan_bulanan ?? draft?.ibu?.penghasilan_bulanan ?? '',
        berkebutuhan_khusus: ex?.ibu?.berkebutuhan_khusus ?? draft?.ibu?.berkebutuhan_khusus ?? '',
    },
    wali: {
        nama:      ex?.wali?.nama      ?? draft?.wali?.nama      ?? '',
        nik:       ex?.wali?.nik       ?? draft?.wali?.nik       ?? '',
        tahun_lahir: ex?.wali?.tahun_lahir ?? draft?.wali?.tahun_lahir ?? '',
        pendidikan:  ex?.wali?.pendidikan  ?? draft?.wali?.pendidikan  ?? '',
        pekerjaan: ex?.wali?.pekerjaan ?? draft?.wali?.pekerjaan ?? '',
        penghasilan_bulanan: ex?.wali?.penghasilan_bulanan ?? draft?.wali?.penghasilan_bulanan ?? '',
    },
    periodik: {
        tinggi_badan:           ex?.periodik?.tinggi_badan           ?? draft?.periodik?.tinggi_badan           ?? '',
        berat_badan:            ex?.periodik?.berat_badan            ?? draft?.periodik?.berat_badan            ?? '',
        jumlah_saudara_kandung: ex?.periodik?.jumlah_saudara_kandung ?? draft?.periodik?.jumlah_saudara_kandung ?? '',
        jarak_ke_sekolah:       ex?.periodik?.jarak_ke_sekolah       ?? draft?.periodik?.jarak_ke_sekolah       ?? '',
        jarak_km:               ex?.periodik?.jarak_km               ?? draft?.periodik?.jarak_km               ?? '',
        waktu_tempuh:           ex?.periodik?.waktu_tempuh           ?? draft?.periodik?.waktu_tempuh           ?? '',
        waktu_menit:            ex?.periodik?.waktu_menit            ?? draft?.periodik?.waktu_menit            ?? '',
    },
    prestasi:  ex?.prestasi  ?? draft?.prestasi  ?? [],
    beasiswa:  ex?.beasiswa  ?? draft?.beasiswa  ?? [],
    berkas: {
        ijazah_skhu: null, rapot_legalisir: null, akte_kelahiran: null, ktp_orang_tua: null,
        kartu_keluarga: null, surat_sehat: null, surat_kelakuan_baik: null,
        kartu_kks_pkh: null, kartu_kps: null, kartu_kip: null,
        kartu_kis_bpjs: null, kartu_nisn: null,
    },
});

// Auto-save draft to localStorage (exclude berkas/files)
watch(
    () => ({
        tingkat:  form.tingkat,
        siswa:    { ...form.siswa },
        ayah:     { ...form.ayah },
        ibu:      { ...form.ibu },
        wali:     { ...form.wali },
        periodik: { ...form.periodik },
        prestasi: [ ...form.prestasi ],
        beasiswa: [ ...form.beasiswa ],
    }),
    (val) => {
        if (STORAGE_KEY.value) {
            try { localStorage.setItem(STORAGE_KEY.value, JSON.stringify(val)); } catch {}
        }
    },
    { deep: true }
);

// Region Loading Logic
onMounted(async () => {
    generateYears();
    
    // Cleanup old/deprecated storage keys to prevent data leaking
    try {
        localStorage.removeItem('ppdb_draft'); // old generic key
        localStorage.removeItem('ppdb_draft_user_guest'); // old guest key
    } catch (e) {}

    try {
        const res = await fetch(`${apiUrl}/provinsi.json`);
        const data = await res.json();
        // Tampilkan seluruh provinsi di Indonesia
        provinces.value = data;

        // Jika ada data (edit mode atau draft)
        if (form.siswa.provinsi) {
            const p = provinces.value.find(x => x.nama.toUpperCase() === form.siswa.provinsi.toUpperCase());
            if (p) {
                selectedProvId.value = p.id;
                await loadRegencies();
                if (form.siswa.kabupaten_kota) {
                    const r = regencies.value.find(x => x.nama.toUpperCase() === form.siswa.kabupaten_kota.toUpperCase());
                    if (r) {
                        selectedRegId.value = r.id;
                        await loadDistricts();
                        if (form.siswa.kecamatan) {
                            const d = districts.value.find(x => x.nama.toUpperCase() === form.siswa.kecamatan.toUpperCase());
                            if (d) {
                                selectedDistId.value = d.id;
                                await loadVillages();
                                if (form.siswa.kelurahan_desa) {
                                    const v = villages.value.find(x => x.nama.toUpperCase() === form.siswa.kelurahan_desa.toUpperCase());
                                    if (v) selectedVillId.value = v.id;
                                }
                            }
                        }
                    }
                }
            }
        }
    } catch (e) { console.error('Gagal memuat provinsi', e); }
});

const loadRegencies = async () => {
    if (!selectedProvId.value) return;
    try {
        const res = await fetch(`${apiUrl}/kabupaten/${selectedProvId.value}.json`);
        regencies.value = await res.json();
    } catch (e) { console.error(e); }
};

const loadDistricts = async () => {
    if (!selectedRegId.value) return;
    try {
        const res = await fetch(`${apiUrl}/kecamatan/${selectedRegId.value}.json`);
        districts.value = await res.json();
    } catch (e) { console.error(e); }
};

const loadVillages = async () => {
    if (!selectedDistId.value) return;
    try {
        const res = await fetch(`${apiUrl}/kelurahan/${selectedDistId.value}.json`);
        villages.value = await res.json();
    } catch (e) { console.error(e); }
};

const onProvChange = async () => {
    form.siswa.provinsi = provinces.value.find(p => p.id === selectedProvId.value)?.nama || '';
    selectedRegId.value = ''; form.siswa.kabupaten_kota = ''; regencies.value = [];
    selectedDistId.value = ''; form.siswa.kecamatan = ''; districts.value = [];
    selectedVillId.value = ''; form.siswa.kelurahan_desa = ''; villages.value = [];
    await loadRegencies();
};

const onRegChange = async () => {
    form.siswa.kabupaten_kota = regencies.value.find(r => r.id === selectedRegId.value)?.nama || '';
    selectedDistId.value = ''; form.siswa.kecamatan = ''; districts.value = [];
    selectedVillId.value = ''; form.siswa.kelurahan_desa = ''; villages.value = [];
    await loadDistricts();
};

const onDistChange = async () => {
    form.siswa.kecamatan = districts.value.find(d => d.id === selectedDistId.value)?.nama || '';
    selectedVillId.value = ''; form.siswa.kelurahan_desa = ''; villages.value = [];
    await loadVillages();
};

const onVillChange = () => {
    form.siswa.kelurahan_desa = villages.value.find(v => v.id === selectedVillId.value)?.nama || '';
};

const handleFileUpload = (event, field) => {
    const file = event.target.files[0];
    if (!file) return;
    
    // Validasi tipe file
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'application/pdf'];
    if (!allowedTypes.includes(file.type)) {
        Swal.fire({
            icon: 'error',
            title: 'Tipe File Tidak Valid',
            text: 'Harap unggah berkas dengan format JPG, JPEG, PNG, WEBP, atau PDF.',
            confirmButtonColor: '#10b981',
        });
        event.target.value = '';
        return;
    }
    
    // Validasi ukuran (maksimal 2MB = 2 * 1024 * 1024 bytes)
    const maxSize = 2 * 1024 * 1024;
    if (file.size > maxSize) {
        Swal.fire({
            icon: 'error',
            title: 'Ukuran File Terlalu Besar',
            text: 'Ukuran berkas maksimal adalah 2MB.',
            confirmButtonColor: '#10b981',
        });
        event.target.value = '';
        return;
    }
    
    form.berkas[field] = file;
};

const berkasWajib = computed(() => [
    { key: 'ijazah_skhu',        label: 'Ijazah / SKHU', desc: 'Legalisir' },
    {
        key: 'rapot_legalisir',
        label: 'Rapot Semester Terakhir',
        desc: form.tingkat === 'smp'
            ? 'SD/MI (Legalisir) + Data Siswa (1 Lembar)'
            : 'SMP/MTs (Legalisir) + Data Siswa (1 Lembar)'
    },
    { key: 'akte_kelahiran',     label: 'Akte Kelahiran', desc: 'Fotokopi / Salinan Resmi' },
    { key: 'ktp_orang_tua',      label: 'KTP Orang Tua', desc: 'KTP Ayah atau Ibu' },
    { key: 'kartu_keluarga',     label: 'Kartu Keluarga (KK)', desc: 'Fotokopi / Salinan Resmi' },
    { key: 'surat_sehat',        label: 'Surat Keterangan Sehat', desc: 'Asli dari Dokter / Puskesmas' },
    { key: 'surat_kelakuan_baik',label: 'Surat Kelakuan Baik', desc: 'Asli dari Sekolah / Madrasah Asal' },
]);

const berkasOpsional = [
    { key: 'kartu_kks_pkh',  label: 'Kartu KKS / PKH', desc: 'Keluarga Sejahtera / Harapan' },
    { key: 'kartu_kps',      label: 'Kartu KPS', desc: 'Perlindungan Sosial' },
    { key: 'kartu_kip',      label: 'Kartu KIP', desc: 'Indonesia Pintar' },
    { key: 'kartu_kis_bpjs', label: 'Kartu KIS / BPJS', desc: 'Indonesia Sehat / BPJS Kesehatan' },
    { key: 'kartu_nisn',     label: 'Kartu / Bukti NISN', desc: 'Cetak Lembar NISN Kemendikbud' },
];

const submit = () => {
    form.post(route('ppdb.store'), {
        forceFormData: true,
        onSuccess: () => {
            if (STORAGE_KEY.value) {
                localStorage.removeItem(STORAGE_KEY.value);
            }
            Swal.fire({
                icon: 'success',
                title: isAccepted.value ? 'Data Berhasil Disimpan' : 'Pendaftaran Berhasil',
                html: isAccepted.value 
                    ? '<p class="text-gray-600">Perubahan data pendaftaran Anda telah berhasil disimpan.</p><p class="text-sm text-gray-400 mt-2">Anda akan diarahkan ke halaman dashboard.</p>'
                    : '<p class="text-gray-600">Data pendaftaran Anda telah berhasil dikirim dan sedang menunggu proses verifikasi dari panitia.</p><p class="text-sm text-gray-400 mt-2">Anda akan diarahkan ke halaman dashboard.</p>',
                confirmButtonText: 'Lihat Dashboard',
                confirmButtonColor: '#059669',
                allowOutsideClick: false,
            }).then(() => {
                router.visit(route('dashboard'));
            });
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            const allErrors = Object.values(errors).join('<br>');

            Swal.fire({
                icon: 'error',
                title: 'Ada Data yang Perlu Diperbaiki',
                html: `<div class="text-left text-sm text-gray-600">
                    <ul class="list-disc pl-5 space-y-1">
                        ${ Object.values(errors).map(e => `<li>${e}</li>`).join('') }
                    </ul>
                </div>`,
                confirmButtonText: 'Periksa Kembali',
                confirmButtonColor: '#dc2626',
            });
        },
    });
};

const addPrestasi = () => {
    form.prestasi.push({ jenis_prestasi: '', tingkat: '', nama_prestasi: '', tahun: '', penyelenggara: '' });
};
const removePrestasi = (index) => { form.prestasi.splice(index, 1); };

const addBeasiswa = () => {
    form.beasiswa.push({ jenis_beasiswa: '', penyelenggara_sumber: '', tahun_mulai: '', tahun_selesai: '' });
};
const removeBeasiswa = (index) => { form.beasiswa.splice(index, 1); };

const nextStep = () => {
    if (step.value === 1) {
        if (!form.siswa.nama_lengkap || !form.siswa.nama_lengkap.trim()) {
            showValidationError('Nama Lengkap Siswa wajib diisi.');
            return;
        }
        if (!form.siswa.asal_sekolah || !form.siswa.asal_sekolah.trim()) {
            showValidationError('Asal Sekolah wajib diisi.');
            return;
        }
        if (!form.siswa.jenis_kelamin) {
            showValidationError('Jenis Kelamin wajib dipilih.');
            return;
        }
        if (!form.siswa.tempat_lahir || !form.siswa.tempat_lahir.trim()) {
            showValidationError('Tempat Lahir wajib diisi.');
            return;
        }
        if (!form.siswa.tanggal_lahir) {
            showValidationError('Tanggal Lahir wajib diisi.');
            return;
        }
    } else if (step.value === 2) {
        if (!form.siswa.alamat || !form.siswa.alamat.trim()) {
            showValidationError('Alamat Lengkap wajib diisi.');
            return;
        }
        if (!selectedProvId.value || !form.siswa.provinsi) {
            showValidationError('Provinsi wajib dipilih.');
            return;
        }
        if (!selectedRegId.value || !form.siswa.kabupaten_kota) {
            showValidationError('Kabupaten/Kota wajib dipilih.');
            return;
        }
        if (!selectedDistId.value || !form.siswa.kecamatan) {
            showValidationError('Kecamatan wajib dipilih.');
            return;
        }
        if (!selectedVillId.value || !form.siswa.kelurahan_desa) {
            showValidationError('Kelurahan/Desa wajib dipilih.');
            return;
        }
        if (!form.siswa.no_hp || !form.siswa.no_hp.trim()) {
            showValidationError('Nomor HP (WhatsApp) wajib diisi.');
            return;
        }
    } else if (step.value === 3) {
        if (!form.ayah.nama || !form.ayah.nama.trim()) {
            showValidationError('Nama Lengkap Ayah Kandung wajib diisi.');
            return;
        }
        if (!form.ibu.nama || !form.ibu.nama.trim()) {
            showValidationError('Nama Lengkap Ibu Kandung wajib diisi.');
            return;
        }
    }

    step.value++;
};

const showValidationError = (message) => {
    Swal.fire({
        icon: 'warning',
        title: 'Data Belum Lengkap',
        text: message,
        confirmButtonText: 'Lengkapi Sekarang',
        confirmButtonColor: form.tingkat === 'smp' ? '#2E5FA3' : '#475569',
    });
};
</script>

<style scoped>
/* Gunakan utility Tailwind langsung di class HTML daripada @apply jika memungkinkan */
.label-form {
    font-weight: 700;
    font-size: 0.875rem;
    line-height: 1.25rem;
    color: rgb(55 65 81);
    margin-bottom: 0.5rem;
    display: block;
}

.input-form {
    width: 100%;
    border-radius: 0.75rem;
    border-width: 1px;
    border-color: rgb(229 231 235);
    background-color: rgb(249 250 251);
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
    padding-left: 1rem;
    padding-right: 1rem;
    color: rgb(31 41 55);
    font-weight: 500;
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

.input-form:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
    border-color: rgb(16 185 129);
    --tw-ring-opacity: 0.5;
    --tw-ring-color: rgb(167 243 208);
}

.input-form-sm {
    width: 100%;
    border-radius: 0.5rem;
    border-width: 1px;
    border-color: rgb(229 231 235);
    background-color: rgb(255 255 255);
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    padding-left: 0.75rem;
    padding-right: 0.75rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
    color: rgb(31 41 55);
    font-weight: 500;
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

.input-form-sm:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
    border-color: rgb(16 185 129);
    --tw-ring-opacity: 0.5;
    --tw-ring-color: rgb(167 243 208);
}

.animate-fadeIn {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Modal Animations */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

@keyframes scaleUp {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
.animate-scaleUp {
    animation: scaleUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}
</style>

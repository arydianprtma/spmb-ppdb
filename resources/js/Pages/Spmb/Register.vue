<template>
    <Head title="Pendaftaran Siswa Baru" />

    <!-- Minimal App Shell (no navbar/footer) -->
    <div class="min-h-screen bg-gray-50 flex flex-col">

        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <img src="/Logo Riyad.png" alt="Logo" class="h-10 w-10" />
                    <div>
                        <div class="text-emerald-700 font-bold text-sm leading-tight">Pondok Pesantren</div>
                        <div class="text-emerald-600 text-xs">Riyadussalikin Padaherang</div>
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
                        Pondok Pesantren Riyadussalikin Periode {{ spmbSetting?.tahunAjaran ?? '-' }}
                    </p>
                </div>

                <!-- Registration Closed Alert -->
                <div v-if="!spmbSetting?.isOpen" class="mb-8 bg-red-50 border-2 border-red-200 rounded-2xl p-6 text-center shadow-lg shadow-red-100 animate-fadeIn">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-xl font-black text-red-800 mb-2">Pendaftaran Sedang Ditutup</h2>
                    <p class="text-red-600 text-sm font-medium mb-4">
                        {{ spmbSetting?.pesanTutup || 'Mohon maaf, saat ini pendaftaran santri baru sedang tidak dibuka.' }}
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
                                        ? 'bg-emerald-500 border-emerald-500 text-white shadow-lg shadow-emerald-200'
                                        : step === s
                                            ? 'bg-white border-emerald-500 text-emerald-600 ring-4 ring-emerald-50'
                                            : 'bg-white border-gray-300 text-gray-400'"
                                >
                                    <span>{{ s }}</span>
                                    <!-- Checkmark badge for completed steps -->
                                    <div v-if="step > s" class="absolute -top-1 -right-1 w-4 h-4 bg-white rounded-full flex items-center justify-center shadow-sm">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                                <span
                                    class="mt-2 text-[10px] md:text-xs font-semibold tracking-wide text-center w-20 md:w-24"
                                    :class="step >= s ? 'text-emerald-600' : 'text-gray-400'"
                                >{{ stepLabels[s-1] }}</span>
                            </div>
                            <!-- Connector line between steps -->
                            <div v-if="s < totalSteps" class="flex-1 mt-4 md:mt-5 mx-1">
                                <div class="h-0.5 w-full transition-all duration-500"
                                    :class="step > s ? 'bg-emerald-500' : 'bg-gray-200'"
                                ></div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Form Content -->
                <form @submit.prevent="submit" class="bg-white shadow-2xl rounded-2xl md:rounded-3xl border border-gray-100 mb-8">
                    <div class="p-5 sm:p-8 md:p-12">
                        
                        <!-- STEP 1: Registrasi & Identitas Siswa -->
                        <div v-if="step === 1" class="space-y-6 md:space-y-8 animate-fadeIn">
                            <div class="border-b border-gray-100 pb-4">
                                <h2 class="text-xl md:text-2xl font-bold text-gray-800 text-center md:text-left">Identitas Peserta Didik</h2>
                                <p class="text-gray-500 text-sm text-center md:text-left">Lengkapi data diri calon santri sesuai dengan dokumen resmi (KK/Akta).</p>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="col-span-1 lg:col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Tingkat Pendidikan</label>
                                    <div class="grid grid-cols-2 gap-4 max-w-xs mx-auto md:mx-0">
                                        <button
                                            v-for="t in ['smp', 'sma']"
                                            :key="t"
                                            type="button"
                                            @click="form.tingkat = t"
                                            class="py-3 px-4 rounded-xl border-2 font-bold transition-all text-sm md:text-base"
                                            :class="form.tingkat === t ? 'border-emerald-600 bg-emerald-50 text-emerald-700' : 'border-gray-100 bg-gray-50 text-gray-500 hover:border-emerald-200'"
                                        >
                                            {{ t.toUpperCase() }}
                                        </button>
                                    </div>
                                </div>

                                <div class="col-span-1 lg:col-span-2">
                                    <label class="label-form">Nama Lengkap</label>
                                    <input v-model="form.siswa.nama_lengkap" type="text" class="input-form" placeholder="Contoh: Ahmad Abdullah" required>
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
                                    <input v-model="form.siswa.dusun" type="text" class="input-form">
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
                                        <input v-model="form.ayah.nama" type="text" class="input-form" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">NIK Ayah</label>
                                        <input v-model="form.ayah.nik" type="text" class="input-form">
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
                                        <input v-model="form.ayah.pendidikan" type="text" class="input-form">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Pekerjaan</label>
                                        <input v-model="form.ayah.pekerjaan" type="text" class="input-form">
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
                                        <input v-model="form.ibu.nama" type="text" class="input-form" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">NIK Ibu</label>
                                        <input v-model="form.ibu.nik" type="text" class="input-form">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Tahun Lahir</label>
                                        <select v-model="form.ibu.tahun_lahir" class="input-form">
                                            <option value="">Pilih Tahun</option>
                                            <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                        </select>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="label-form">Pekerjaan</label>
                                        <input v-model="form.ibu.pekerjaan" type="text" class="input-form">
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
                                        <input v-model="form.periodik.tinggi_badan" type="number" class="input-form">
                                    </div>
                                    <div>
                                        <label class="label-form">Berat (kg)</label>
                                        <input v-model="form.periodik.berat_badan" type="number" class="input-form">
                                    </div>
                                </div>

                                <div class="col-span-1">
                                    <label class="label-form">Jumlah Saudara Kandung</label>
                                    <input v-model="form.periodik.jumlah_saudara_kandung" type="number" class="input-form">
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
                                            <input v-model="form.siswa.no_kps_pkh" type="text" class="input-form">
                                        </div>
                                        <div v-if="form.siswa.penerima_kps_pkh">
                                            <label class="label-form">No. KKS</label>
                                            <input v-model="form.siswa.no_kks" type="text" class="input-form">
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
                                            <input v-model="form.siswa.no_kip" type="text" class="input-form">
                                        </div>
                                        <div v-if="form.siswa.penerima_kip">
                                            <label class="label-form">Nama di KIP</label>
                                            <input v-model="form.siswa.nama_tertera_kip" type="text" class="input-form">
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
                                            <input v-model="form.siswa.alasan_layak_pip" type="text" class="input-form">
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
                                            <input v-model="form.siswa.no_rek_bank" type="text" class="input-form">
                                        </div>
                                        <div>
                                            <label class="label-form">Rekening Atas Nama</label>
                                            <input v-model="form.siswa.rekening_atas_nama" type="text" class="input-form">
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
                                    <div v-for="field in berkasWajib" :key="field.key">
                                        <p class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            {{ field.label }}
                                        </p>
                                        <label class="relative block cursor-pointer group">
                                            <!-- Empty State -->
                                            <div v-if="!form.berkas[field.key] && !existingBerkas[field.key]"
                                                class="relative overflow-hidden rounded-2xl border-2 border-dashed border-gray-200 bg-gradient-to-b from-gray-50 to-white px-6 py-8 text-center transition-all duration-200 group-hover:border-emerald-400 group-hover:from-emerald-50/50 group-hover:to-white group-hover:shadow-lg group-hover:shadow-emerald-100">
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
                                                class="relative overflow-hidden rounded-2xl border-2 border-emerald-300 bg-gradient-to-b from-emerald-50 to-white px-6 py-8 text-center transition-all duration-200 group-hover:border-emerald-500 group-hover:shadow-lg group-hover:shadow-emerald-100">
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
                                                class="relative overflow-hidden rounded-2xl border-2 border-emerald-400 bg-gradient-to-b from-emerald-50 to-white px-6 py-8 text-center shadow-md shadow-emerald-100">
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
                                    <div v-for="field in berkasOpsional" :key="field.key">
                                        <p class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            {{ field.label }}
                                        </p>
                                        <label class="relative block cursor-pointer group">
                                            <div v-if="!form.berkas[field.key] && !existingBerkas[field.key]"
                                                class="relative overflow-hidden rounded-2xl border-2 border-dashed border-gray-200 bg-gradient-to-b from-gray-50 to-white px-6 py-8 text-center transition-all duration-200 group-hover:border-blue-400 group-hover:from-blue-50/50 group-hover:to-white group-hover:shadow-lg group-hover:shadow-blue-100">
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
                                                class="relative overflow-hidden rounded-2xl border-2 border-blue-300 bg-gradient-to-b from-blue-50 to-white px-6 py-8 text-center transition-all duration-200 group-hover:border-blue-500 group-hover:shadow-lg">
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
                                                class="relative overflow-hidden rounded-2xl border-2 border-blue-400 bg-gradient-to-b from-blue-50 to-white px-6 py-8 text-center shadow-md shadow-blue-100">
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
                                        Dengan menekan tombol <strong class="text-white">"Kirim Pendaftaran"</strong>, saya menyatakan bahwa seluruh data dan berkas yang saya unggah adalah <strong class="text-white">benar dan dapat dipertanggungjawabkan</strong> secara hukum. Data palsu dapat menyebabkan pembatalan pendaftaran.
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
                            class="px-3 md:px-6 py-2.5 md:py-3 text-emerald-700 text-sm md:text-base font-bold hover:bg-emerald-50 rounded-xl transition-colors flex items-center gap-1 md:gap-2"
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
                            @click="step++"
                            class="bg-emerald-600 text-white px-4 md:px-8 py-2.5 md:py-3 rounded-xl text-sm md:text-base font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 flex items-center gap-1 md:gap-2"
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
                            class="relative bg-emerald-600 text-white px-5 md:px-10 py-2.5 md:py-3 rounded-xl text-sm md:text-base font-bold hover:bg-emerald-700 active:scale-95 transition-all shadow-lg shadow-emerald-200 disabled:opacity-60 disabled:cursor-not-allowed flex items-center gap-1 md:gap-2"
                        >
                            <svg v-if="form.processing" class="animate-spin h-4 w-4 md:h-5 md:w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            {{ form.processing ? 'Mengirim...' : 'Kirim' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

// Gunakan key yang unik per user agar data tidak bocor antar akun di browser yang sama
const page = usePage();
const STORAGE_KEY = computed(() => {
    const userId = page.props.auth?.user?.id;
    return userId ? `spmb_draft_user_${userId}` : null;
});

// Props from server
const props = defineProps({
    existingData: Object,
    spmbSetting: Object,
});

const step = ref(1);
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
        'ijazah_skhu', 'akte_kelahiran', 'ktp_orang_tua', 'kartu_keluarga',
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
        jenis_kelamin:   ex?.siswa?.jenis_kelamin   ?? draft?.siswa?.jenis_kelamin   ?? '',
        nisn:            ex?.siswa?.nisn            ?? draft?.siswa?.nisn            ?? '',
        nik:             ex?.siswa?.nik             ?? draft?.siswa?.nik             ?? '',
        tempat_lahir:    ex?.siswa?.tempat_lahir    ?? draft?.siswa?.tempat_lahir    ?? '',
        tanggal_lahir:   ex?.siswa?.tanggal_lahir   ?? draft?.siswa?.tanggal_lahir   ?? '',
        agama:           ex?.siswa?.agama           ?? draft?.siswa?.agama           ?? 'Islam',
        alamat:          ex?.siswa?.alamat          ?? draft?.siswa?.alamat          ?? '',
        dusun:           ex?.siswa?.dusun           ?? draft?.siswa?.dusun           ?? '',
        rt:              ex?.siswa?.rt              ?? draft?.siswa?.rt              ?? '',
        rw:              ex?.siswa?.rw              ?? draft?.siswa?.rw              ?? '',
        kelurahan_desa:  ex?.siswa?.kelurahan_desa  ?? draft?.siswa?.kelurahan_desa  ?? '',
        kecamatan:       ex?.siswa?.kecamatan       ?? draft?.siswa?.kecamatan       ?? '',
        kabupaten_kota:  ex?.siswa?.kabupaten_kota  ?? draft?.siswa?.kabupaten_kota  ?? '',
        provinsi:        ex?.siswa?.provinsi        ?? draft?.siswa?.provinsi        ?? '',
        no_hp:              ex?.siswa?.no_hp           ?? draft?.siswa?.no_hp           ?? '',
        email_pribadi:      ex?.siswa?.email_pribadi   ?? draft?.siswa?.email_pribadi   ?? '',
        kode_pos:           ex?.siswa?.kode_pos        ?? draft?.siswa?.kode_pos        ?? '',
        no_ijazah:          ex?.siswa?.no_ijazah       ?? draft?.siswa?.no_ijazah       ?? '',
        no_skhun:           ex?.siswa?.no_skhun        ?? draft?.siswa?.no_skhun        ?? '',
        no_un:              ex?.siswa?.no_un           ?? draft?.siswa?.no_un           ?? '',
        berkebutuhan_khusus: ex?.siswa?.berkebutuhan_khusus ?? draft?.siswa?.berkebutuhan_khusus ?? '',
        ukuran_pakaian:     ex?.siswa?.ukuran_pakaian  ?? draft?.siswa?.ukuran_pakaian  ?? '',
        no_sepatu:          ex?.siswa?.no_sepatu       ?? draft?.siswa?.no_sepatu       ?? '',
        no_kopiyah:         ex?.siswa?.no_kopiyah      ?? draft?.siswa?.no_kopiyah      ?? '',
        
        penerima_kps_pkh:   ex?.siswa?.penerima_kps_pkh ?? draft?.siswa?.penerima_kps_pkh ?? false,
        no_kps_pkh:         ex?.siswa?.no_kps_pkh       ?? draft?.siswa?.no_kps_pkh       ?? '',
        no_kks:             ex?.siswa?.no_kks           ?? draft?.siswa?.no_kks           ?? '',
        
        penerima_kip:       ex?.siswa?.penerima_kip     ?? draft?.siswa?.penerima_kip     ?? false,
        no_kip:             ex?.siswa?.no_kip           ?? draft?.siswa?.no_kip           ?? '',
        nama_tertera_kip:   ex?.siswa?.nama_tertera_kip ?? draft?.siswa?.nama_tertera_kip ?? '',
        
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
    },
    ibu: {
        nama:        ex?.ibu?.nama        ?? draft?.ibu?.nama        ?? '',
        nik:         ex?.ibu?.nik         ?? draft?.ibu?.nik         ?? '',
        tahun_lahir: ex?.ibu?.tahun_lahir ?? draft?.ibu?.tahun_lahir ?? '',
        pekerjaan:   ex?.ibu?.pekerjaan   ?? draft?.ibu?.pekerjaan   ?? '',
        pendidikan:  ex?.ibu?.pendidikan  ?? draft?.ibu?.pendidikan  ?? '',
    },
    wali: {
        nama:      ex?.wali?.nama      ?? draft?.wali?.nama      ?? '',
        nik:       ex?.wali?.nik       ?? draft?.wali?.nik       ?? '',
        pekerjaan: ex?.wali?.pekerjaan ?? draft?.wali?.pekerjaan ?? '',
    },
    periodik: {
        tinggi_badan:           ex?.periodik?.tinggi_badan           ?? draft?.periodik?.tinggi_badan           ?? '',
        berat_badan:            ex?.periodik?.berat_badan            ?? draft?.periodik?.berat_badan            ?? '',
        jumlah_saudara_kandung: ex?.periodik?.jumlah_saudara_kandung ?? draft?.periodik?.jumlah_saudara_kandung ?? '',
    },
    prestasi:  ex?.prestasi  ?? draft?.prestasi  ?? [],
    beasiswa:  ex?.beasiswa  ?? draft?.beasiswa  ?? [],
    berkas: {
        ijazah_skhu: null, akte_kelahiran: null, ktp_orang_tua: null,
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
        localStorage.removeItem('spmb_draft'); // old generic key
        localStorage.removeItem('spmb_draft_user_guest'); // old guest key
    } catch (e) {}

    try {
        const res = await fetch(`${apiUrl}/provinsi.json`);
        const data = await res.json();
        // Hanya tampilkan Jawa Barat (32) dan Jawa Tengah (33)
        provinces.value = data.filter(p => p.id === '32' || p.id === '33');

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
    if (file) form.berkas[field] = file;
};

const berkasWajib = [
    { key: 'ijazah_skhu',        label: 'Ijazah / SKHU (Dilegalisir)' },
    { key: 'akte_kelahiran',     label: 'Akte Kelahiran' },
    { key: 'ktp_orang_tua',      label: 'KTP Orang Tua (Ayah/Ibu)' },
    { key: 'kartu_keluarga',     label: 'Kartu Keluarga (KK)' },
    { key: 'surat_sehat',        label: 'Surat Keterangan Sehat' },
    { key: 'surat_kelakuan_baik',label: 'Surat Kelakuan Baik (Sekolah)' },
];

const berkasOpsional = [
    { key: 'kartu_kks_pkh',  label: 'Kartu KKS / PKH' },
    { key: 'kartu_kps',      label: 'Kartu KPS' },
    { key: 'kartu_kip',      label: 'Kartu Indonesia Pintar (KIP)' },
    { key: 'kartu_kis_bpjs', label: 'Kartu KIS / BPJS' },
    { key: 'kartu_nisn',     label: 'Kartu / Bukti NISN' },
];

const submit = () => {
    form.post(route('spmb.store'), {
        forceFormData: true,
        onSuccess: () => {
            if (STORAGE_KEY.value) {
                localStorage.removeItem(STORAGE_KEY.value);
            }
            Swal.fire({
                icon: 'success',
                title: 'Pendaftaran Berhasil! 🎉',
                html: '<p class="text-gray-600">Data pendaftaran Anda telah berhasil dikirim dan sedang menunggu proses verifikasi dari panitia.</p><p class="text-sm text-gray-400 mt-2">Anda akan diarahkan ke halaman dashboard.</p>',
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
</style>

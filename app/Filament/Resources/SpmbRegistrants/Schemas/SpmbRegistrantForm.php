<?php

namespace App\Filament\Resources\SpmbRegistrants\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class SpmbRegistrantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Data Pendaftaran')
                    ->tabs([
                        // TAB 1: DATA DIRI
                        Tab::make('Data Diri')
                            ->icon('heroicon-m-user')
                            ->schema([
                                TextInput::make('no_reg')
                                    ->label('No. Registrasi')
                                    ->disabled(),
                                Select::make('tingkat')
                                    ->label('Tingkat')
                                    ->options(['smp' => 'SMP', 'sma' => 'SMA'])
                                    ->required(),
                                Section::make('Identitas Santri')
                                    ->relationship('siswa')
                                    ->schema([
                                        TextInput::make('nama_lengkap')->required(),
                                        TextInput::make('nisn')->label('NISN'),
                                        TextInput::make('nik')->label('NIK'),
                                        Select::make('jenis_kelamin')
                                            ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                                            ->required(),
                                        Grid::make(2)->schema([
                                            TextInput::make('tempat_lahir'),
                                            DatePicker::make('tanggal_lahir'),
                                        ]),
                                        TextInput::make('no_hp')->label('WhatsApp')->tel(),
                                        Select::make('berkebutuhan_khusus')
                                            ->options([
                                                'Tidak Ada' => 'Tidak Ada',
                                                'Tunanetra' => 'Tunanetra',
                                                'Tunarungu' => 'Tunarungu',
                                                'Tunawicara' => 'Tunawicara',
                                                'Tunadaksa' => 'Tunadaksa',
                                                'Tunagrahita' => 'Tunagrahita',
                                                'Autis' => 'Autis',
                                                'Lainnya' => 'Lainnya',
                                            ])->native(false),
                                    ]),
                            ]),

                        // TAB 2: DATA ORANG TUA
                        Tab::make('Data Orang Tua')
                            ->icon('heroicon-m-users')
                            ->schema([
                                Section::make('Data Ayah')
                                    ->relationship('ayah')
                                    ->schema([
                                        TextInput::make('nama'),
                                        TextInput::make('pekerjaan'),
                                        TextInput::make('pendidikan'),
                                    ])->columns(3),
                                Section::make('Data Ibu')
                                    ->relationship('ibu')
                                    ->schema([
                                        TextInput::make('nama'),
                                        TextInput::make('pekerjaan'),
                                        TextInput::make('pendidikan'),
                                    ])->columns(3),
                            ]),

                        // TAB 3: PRESTASI & BEASISWA
                        Tab::make('Prestasi & Beasiswa')
                            ->icon('heroicon-m-academic-cap')
                            ->schema([
                                Section::make('Data Prestasi')
                                    ->schema([
                                        Repeater::make('prestasi')
                                            ->relationship('prestasi')
                                            ->schema([
                                                Grid::make(2)->schema([
                                                    TextInput::make('jenis_prestasi')->label('Jenis'),
                                                    TextInput::make('tingkat')->label('Tingkat'),
                                                ]),
                                                TextInput::make('nama_prestasi')->label('Nama Prestasi'),
                                                Grid::make(2)->schema([
                                                    TextInput::make('tahun')->label('Tahun'),
                                                    TextInput::make('penyelenggara')->label('Penyelenggara'),
                                                ]),
                                            ])
                                            ->itemLabel(fn (array $state): ?string => $state['nama_prestasi'] ?? 'Prestasi Baru')
                                            ->collapsed()
                                            ->collapsible()
                                            ->addActionLabel('Tambah Prestasi'),
                                    ]),
                                Section::make('Data Beasiswa')
                                    ->schema([
                                        Repeater::make('beasiswa')
                                            ->relationship('beasiswa')
                                            ->schema([
                                                TextInput::make('jenis_beasiswa')->label('Jenis Beasiswa'),
                                                TextInput::make('penyelenggara_sumber')->label('Sumber'),
                                                Grid::make(2)->schema([
                                                    TextInput::make('tahun_mulai')->label('Tahun Mulai'),
                                                    TextInput::make('tahun_selesai')->label('Tahun Selesai'),
                                                ]),
                                            ])
                                            ->itemLabel(fn (array $state): ?string => $state['jenis_beasiswa'] ?? 'Beasiswa Baru')
                                            ->collapsed()
                                            ->collapsible()
                                            ->addActionLabel('Tambah Beasiswa'),
                                    ]),
                            ]),

                        // TAB 4: BERKAS
                        Tab::make('Berkas Pendaftaran')
                            ->icon('heroicon-m-document-duplicate')
                            ->schema([
                                Section::make('File Upload')
                                    ->relationship('berkas')
                                    ->schema([
                                        FileUpload::make('ijazah_skhu')
                                            ->image()
                                            ->imageResizeTargetWidth('1200')
                                            ->imageQuality(80)
                                            ->disk('public')
                                            ->directory('Spmb/ijazah'),
                                        FileUpload::make('akte_kelahiran')
                                            ->image()
                                            ->imageResizeTargetWidth('1200')
                                            ->imageQuality(80)
                                            ->disk('public')
                                            ->directory('Spmb/akte'),
                                        FileUpload::make('kartu_keluarga')
                                            ->image()
                                            ->imageResizeTargetWidth('1200')
                                            ->imageQuality(80)
                                            ->disk('public')
                                            ->directory('Spmb/kk'),
                                        FileUpload::make('kartu_kip')
                                            ->image()
                                            ->imageResizeTargetWidth('1200')
                                            ->imageQuality(80)
                                            ->disk('public')
                                            ->directory('Spmb/kip'),
                                    ])->columns(2),
                            ]),

                        // TAB 5: STATUS
                        Tab::make('Status Verifikasi')
                            ->icon('heroicon-m-check-badge')
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'pending'         => 'Menunggu',
                                        'jadwal_tes'      => 'Jadwal Tes',
                                        'tes_berlangsung' => 'Tes Berlangsung',
                                        'wawancara'       => 'Wawancara',
                                        'diterima_ula'    => 'Diterima - Ula',
                                        'diterima_wustho' => 'Diterima - Wustho',
                                        'diterima_ulya'   => 'Diterima - Ulya',
                                        'ditolak'         => 'Tidak Diterima',
                                    ])
                                    ->required(),
                            ]),
                    ])->columnSpanFull()
            ]);
    }
}

<?php

namespace App\Filament\Resources\PpdbRegistrants;

use App\Filament\Resources\PpdbRegistrants\Pages\CreatePpdbRegistrant;
use App\Filament\Resources\PpdbRegistrants\Pages\EditPpdbRegistrant;
use App\Filament\Resources\PpdbRegistrants\Pages\ListPpdbRegistrants;
use App\Filament\Resources\PpdbRegistrants\Schemas\PpdbRegistrantForm;
use App\Filament\Resources\PpdbRegistrants\Tables\PpdbRegistrantsTable;
use App\Models\PpdbPendaftaran;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PpdbRegistrantResource extends Resource
{
    protected static ?string $model = PpdbPendaftaran::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Pendaftar PPDB';

    protected static ?string $pluralModelLabel = 'Pendaftar PPDB';

    protected static ?string $navigationLabel = 'Pendaftar PPDB';

    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Web';

    protected static ?string $slug = 'ppdb';

    public static function form(Schema $schema): Schema
    {
        return PpdbRegistrantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PpdbRegistrantsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPpdbRegistrants::route('/'),
            'create' => CreatePpdbRegistrant::route('/create'),
            'edit' => EditPpdbRegistrant::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->isAdmin() ?? false;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getNavigationBadgePollingInterval(): ?string
    {
        return '10s';
    }
}

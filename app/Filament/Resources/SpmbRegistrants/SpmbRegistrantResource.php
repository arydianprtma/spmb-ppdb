<?php

namespace App\Filament\Resources\SpmbRegistrants;

use App\Filament\Resources\SpmbRegistrants\Pages\CreateSpmbRegistrant;
use App\Filament\Resources\SpmbRegistrants\Pages\EditSpmbRegistrant;
use App\Filament\Resources\SpmbRegistrants\Pages\ListSpmbRegistrants;
use App\Filament\Resources\SpmbRegistrants\Schemas\SpmbRegistrantForm;
use App\Filament\Resources\SpmbRegistrants\Tables\SpmbRegistrantsTable;
use App\Models\SpmbPendaftaran;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SpmbRegistrantResource extends Resource
{
    protected static ?string $model = SpmbPendaftaran::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Pendaftar PPDB';

    protected static ?string $pluralModelLabel = 'Pendaftar PPDB';

    protected static ?string $navigationLabel = 'Pendaftar PPDB';

    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Web';

    protected static ?string $slug = 'ppdb';

    public static function form(Schema $schema): Schema
    {
        return SpmbRegistrantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpmbRegistrantsTable::configure($table);
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
            'index' => ListSpmbRegistrants::route('/'),
            'create' => CreateSpmbRegistrant::route('/create'),
            'edit' => EditSpmbRegistrant::route('/{record}/edit'),
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

<?php

namespace App\Filament\Resources\SpmbRegistrants\Pages;

use App\Filament\Resources\SpmbRegistrants\SpmbRegistrantResource;
use App\Models\SpmbRegistrant;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListSpmbRegistrants extends ListRecords
{
    protected static string $resource = SpmbRegistrantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'pending'))
                ->badge(SpmbRegistrant::where('status', 'pending')->count())
                ->badgeColor('gray'),
            'verified' => Tab::make('Verified')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'verified'))
                ->badge(SpmbRegistrant::where('status', 'verified')->count())
                ->badgeColor('info'),
            'accepted' => Tab::make('Accepted')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'accepted'))
                ->badge(SpmbRegistrant::where('status', 'accepted')->count())
                ->badgeColor('success'),
            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'rejected'))
                ->badge(SpmbRegistrant::where('status', 'rejected')->count())
                ->badgeColor('danger'),
        ];
    }
}

<?php

namespace Postare\FilamentContactLogs\Resources\ContactLogResource\Pages;

use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Str;
use Postare\FilamentContactLogs\Models\ContactLog;
use Postare\FilamentContactLogs\Resources\ContactLogResource;

class ListContactLogs extends ListRecords
{
    protected static string $resource = ContactLogResource::class;

    protected function getActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        $mapping = config('contact-logs.mappings');

        if (ContactLog::count() == 0 || empty($mapping)) {
            return [];
        }

        $contactLogCount = ContactLog::select('contactable_type')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('contactable_type')
            ->get()
            ->keyBy('contactable_type')
            ->map(fn ($item) => $item->total);

        $tabs = [
            'all' => Tab::make(__('filament-contact-logs::contact-logs.list.tabs.all'))->badge($contactLogCount->sum()),
        ];

        foreach ($mapping as $model => $config) {
            $typeCount = $contactLogCount[$model] ?? 0;
            $tabs[Str::slug($config['pluralType'])] = Tab::make($config['pluralType'])
                ->badge($typeCount)
                ->modifyQueryUsing(fn ($query) => $query->where('contactable_type', $model));
        }

        $otherCount = $contactLogCount[null] ?? 0;
        if ($otherCount > 0) {
            $tabs['other'] = Tab::make(__('filament-contact-logs::contact-logs.list.tabs.other'))->badge($otherCount)
                ->modifyQueryUsing(fn ($query) => $query->whereNull('contactable_type'));
        }

        return $tabs;
    }
}

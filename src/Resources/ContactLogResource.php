<?php

namespace Postare\FilamentContactLogs\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Postare\FilamentContactLogs\Models\ContactLog;
use Postare\FilamentContactLogs\Resources\ContactLogResource\Pages\ListContactLogs;

class ContactLogResource extends Resource
{
    protected static ?string $model = ContactLog::class;

    protected static ?string $slug = 'contact_logs';

    public static function getModelLabel(): string
    {
        return __('filament-contact-logs::contact-logs.resource.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-contact-logs::contact-logs.resource.plural');
    }

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static ?string $recordTitleAttribute = 'subject';

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('contactable_type')
                ->label('Sorgente')
                ->formatStateUsing(fn ($state) => config("contact-logs.mappings.$state.type", $state))
                ->toggleable(true, false)
                ->placeholder(__('filament-contact-logs::contact-logs.list.contactable_type_default'))
                ->sortable(),

            TextColumn::make('contactable_id')
                ->label(__('filament-contact-logs::contact-logs.list.contactable_id'))
                ->formatStateUsing(function ($state, $record) {
                    // Recupera il formato della label dal file di configurazione
                    $labelFormat = Arr::get(config('contact-logs.mappings'), "{$record->contactable_type}.label");

                    // Se non esiste un formato di label specifico, usa il valore di default
                    if (! $labelFormat) {
                        return $state;
                    }

                    // Sostituisci il placeholder se esiste, altrimenti restituisci il formato di label
                    return str_contains($labelFormat, '{id}') ? str_replace('{id}', $state, $labelFormat) : $labelFormat;
                })
                ->description(function ($state, $record) {
                    // Usa config con un valore di default
                    $titleField = config("contact-logs.mappings.{$record->contactable_type}.titleField");

                    return $titleField ? str($record->contactable->{$titleField})->limit(50) : false;
                })
                ->url(function ($record) {
                    // Ottieni la configurazione con un valore di default
                    $urlPrefix = config("contact-logs.mappings.{$record->contactable_type}.urlPrefix");
                    $slugField = config("contact-logs.mappings.{$record->contactable_type}.slugField");

                    if ($urlPrefix && $slugField && $record->contactable) {
                        return route($urlPrefix, $record->contactable->{$slugField});
                    }

                    return false;
                }, true)
                ->wrap()
                ->toggleable(true, false),

            TextColumn::make('subject')
                ->label(__('filament-contact-logs::contact-logs.list.subject'))
                ->toggleable(true, true)
                ->sortable(),

            TextColumn::make('content')
                ->label(__('filament-contact-logs::contact-logs.list.content'))
                ->toggleable(true, false)
                ->words(20)
                ->wrap()
                ->searchable(),

            TextColumn::make('sender_name')
                ->label(__('filament-contact-logs::contact-logs.list.sender'))
                ->description(fn ($state, $record) => $record->sender_email)
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('sender_name', 'like', "%{$search}%")
                        ->orWhere('sender_email', 'like', "%{$search}%");
                })
                ->sortable(),

            TextColumn::make('sender_phone')
                ->label(__('filament-contact-logs::contact-logs.list.sender_phone'))
                ->toggleable(true, true)
                ->searchable(),

            TextColumn::make('created_at')
                ->label(__('filament-contact-logs::contact-logs.list.created_at'))
                // description in relative format
                ->description(fn ($state) => $state->diffForHumans())
                ->date('d/m/Y H:i:s')
                ->sortable(),

        ])->actions([
            Tables\Actions\DeleteAction::make(),
        ])->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => ListContactLogs::route('/')];
    }

    //    public static function getGloballySearchableAttributes(): array
    //    {
    //        return ['sender_name', 'sender_email'];
    //    }
}

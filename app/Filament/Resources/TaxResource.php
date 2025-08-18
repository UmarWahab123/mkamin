<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaxResource\Pages;
use App\Models\Tax;
use Filament\Facades\Filament;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Setting;

class TaxResource extends Resource
{
    protected static ?string $model = Tax::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Tax Information'))
                    ->schema([
                        Forms\Components\TextInput::make('name_en')
                            ->label(__('Name (English)'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Sales Tax, VAT, GST'),

                        Forms\Components\TextInput::make('name_ar')
                            ->label(__('Name (Arabic)'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Arabic translation'),

                        Forms\Components\Select::make('type')
                            ->label(__('Tax Type'))
                            ->options([
                                'percentage' => __('Percentage (%)'),
                                'fixed' => __('Fixed Amount'),
                            ])
                            ->required()
                            ->default('percentage')
                            ->reactive(),

                        Forms\Components\TextInput::make('amount')
                            ->label(fn (callable $get) => $get('type') === 'percentage'
                                ? __('Percentage Rate (%)')
                                : new \Illuminate\Support\HtmlString(__('Fixed Amount') . ' <span class="icon-saudi_riyal"></span>'))
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->suffix(fn (callable $get) => $get('type') === 'percentage' ? '%' : null)
                            ->placeholder(fn (callable $get) => $get('type') === 'percentage' ? '5.00' : '10.00'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('Additional Settings'))
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->label(__('Company'))
                            ->relationship('company', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->default(function () {
                                $user = Filament::auth()->user();
                                return $user && $user->company_id ? $user->company_id : null;
                            }),

                        Forms\Components\Toggle::make('is_active')
                            ->label(__('Active'))
                            ->helperText(__('Inactive taxes will not be applied to orders'))
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('Name (English)'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('Name (Arabic)'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'percentage' ? __('Percentage') : __('Fixed'))
                    ->color(fn (string $state): string => $state === 'percentage' ? 'primary' : 'success'),

                Tables\Columns\TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->formatStateUsing(function ($record): string {
                        if ($record->type === 'percentage') {
                            return number_format($record->amount, 2) . '%';
                        }

                        $currency = Setting::get('currency', '<span class="icon-saudi_riyal"></span>');
                        return new \Illuminate\Support\HtmlString("{$currency} " . number_format($record->amount, 2));
                    })
                    ->html()
                    ->sortable(),

                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('Company'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\SelectFilter::make('type')
                    ->label(__('Tax Type'))
                    ->options([
                        'percentage' => __('Percentage'),
                        'fixed' => __('Fixed Amount'),
                    ]),

                Tables\Filters\SelectFilter::make('company_id')
                    ->relationship('company', 'name')
                    ->label(__('Company'))
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('is_active')
                    ->label(__('Status'))
                    ->options([
                        '1' => __('Active'),
                        '0' => __('Inactive'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),

                    Tables\Actions\BulkAction::make('activate')
                        ->label(__('Activate Selected'))
                        ->icon('heroicon-o-check')
                        ->action(fn (array $records) => Tax::whereIn('id', $records)->update(['is_active' => true]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label(__('Deactivate Selected'))
                        ->icon('heroicon-o-x-mark')
                        ->action(fn (array $records) => Tax::whereIn('id', $records)->update(['is_active' => false]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListTaxes::route('/'),
            'create' => Pages\CreateTax::route('/create'),
            'view' => Pages\ViewTax::route('/{record}'),
            'edit' => Pages\EditTax::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return $record->name_en;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name_en', 'name_ar', 'type', 'amount'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Type' => $record->type === 'percentage' ? 'Percentage' : 'Fixed',
            'Amount' => $record->type === 'percentage'
                ? number_format($record->amount, 2) . '%'
                : '$' . number_format($record->amount, 2),
            'Company' => $record->company->name,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }

    public static function getModelLabel(): string
    {
        return __('Tax');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Taxes');
    }
}

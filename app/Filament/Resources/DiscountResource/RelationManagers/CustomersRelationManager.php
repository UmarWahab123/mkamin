<?php

namespace App\Filament\Resources\DiscountResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use App\Models\Customer;

class CustomersRelationManager extends RelationManager
{


    protected static string $relationship = 'customers';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name')),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label(__('Phone')),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email')),
                Tables\Columns\TextColumn::make('pointOfSale.name_en')
                    ->label(__('Point of Sale')),
                Tables\Columns\TextColumn::make('pivot.discount_card_template_id')
                    ->label(__('Discount Card Template'))
                    ->formatStateUsing(fn ($state) => \App\Models\DiscountCardTemplate::find($state)?->name ?? '-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('editTemplate')
                    ->label(__('Change Template'))
                    ->icon('heroicon-m-pencil-square')
                    ->form([
                        Forms\Components\Select::make('discount_card_template_id')
                            ->label(__('Discount Card Template'))
                            ->allowHtml()
                            ->searchable()
                            ->preload()
                            ->options(function() {
                                return \App\Models\DiscountCardTemplate::all()->mapWithKeys(function ($template) {
                                    return [$template->id => view('filament.components.template-option', [
                                        'name' => $template->name,
                                        'image' => Storage::url($template->image)
                                    ])->render()];
                                })->toArray();
                            })
                            ->getOptionLabelUsing(function ($value) {
                                $template = \App\Models\DiscountCardTemplate::find($value);
                                if (!$template) return '';

                                return view('filament.components.template-option', [
                                    'name' => $template->name,
                                    'image' => Storage::url($template->image)
                                ])->render();
                            })
                            ->default(function ($record) {
                                return $record->pivot->discount_card_template_id;
                            })
                            ->required(),
                    ])
                    ->action(function (array $data, $record): void {
                        // Directly update the pivot record
                        \Illuminate\Support\Facades\DB::table('customer_discount')
                            ->where('customer_id', $record->id)
                            ->where('discount_id', $this->ownerRecord->id)
                            ->update(['discount_card_template_id' => $data['discount_card_template_id']]);
                    }),
                Tables\Actions\Action::make('print')
                    ->label(__('Print Card'))
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(function ($record) {
                        // Generate URL to the discount card route
                        return route('discount.card', [
                            'discount' => $this->ownerRecord->id,
                            'customer' => $record->id,
                        ]);
                    })
                    ->extraAttributes([
                        'onclick' => 'let isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent); let isAndroid = /Android/i.test(navigator.userAgent); openPrintPreview(this.href, isMobile, !isAndroid); return false;'
                    ]),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}

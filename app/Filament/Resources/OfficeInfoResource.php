<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfficeInfoResource\Pages;
use App\Models\OfficeInfo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class OfficeInfoResource extends Resource
{
    protected static ?string $model = OfficeInfo::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Office Info';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Office Information')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Contact')
                            ->schema([
                                Forms\Components\TextInput::make('practice_name')
                                    ->label('Practice Name')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email Address')
                                    ->email()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('address_line1')
                                    ->label('Address Line 1')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('address_line2')
                                    ->label('Address Line 2')
                                    ->maxLength(255),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('city')
                                            ->required()
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('state')
                                            ->required()
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('zip')
                                            ->label('ZIP Code')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Hours')
                            ->schema([
                                Forms\Components\TextInput::make('monday_hours')
                                    ->label('Monday')
                                    ->maxLength(255)
                                    ->placeholder('8:00 AM - 5:00 PM'),

                                Forms\Components\TextInput::make('tuesday_hours')
                                    ->label('Tuesday')
                                    ->maxLength(255)
                                    ->placeholder('8:00 AM - 5:00 PM'),

                                Forms\Components\TextInput::make('wednesday_hours')
                                    ->label('Wednesday')
                                    ->maxLength(255)
                                    ->placeholder('8:00 AM - 5:00 PM'),

                                Forms\Components\TextInput::make('thursday_hours')
                                    ->label('Thursday')
                                    ->maxLength(255)
                                    ->placeholder('8:00 AM - 5:00 PM'),

                                Forms\Components\TextInput::make('friday_hours')
                                    ->label('Friday')
                                    ->maxLength(255)
                                    ->placeholder('8:00 AM - 5:00 PM'),

                                Forms\Components\TextInput::make('saturday_hours')
                                    ->label('Saturday')
                                    ->maxLength(255)
                                    ->placeholder('Closed'),

                                Forms\Components\TextInput::make('sunday_hours')
                                    ->label('Sunday')
                                    ->maxLength(255)
                                    ->placeholder('Closed'),
                            ])
                            ->columns(1),

                        Forms\Components\Tabs\Tab::make('Emergency')
                            ->schema([
                                Forms\Components\RichEditor::make('emergency_message')
                                    ->label('After-Hours Emergency Message')
                                    ->columnSpanFull()
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                    ])
                                    ->helperText('Displayed to patients looking for emergency contact information'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOfficeInfo::route('/'),
        ];
    }
}

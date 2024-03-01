<?php

namespace App\Filament\Company\Resources;


use App\Models\Process;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

//use Illuminate\Database\Eloquent\Builder;

class ProcessResource extends Resource
{
    protected static ?string $model = Process::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                TextInput::make('name')->required()->label("Process Name"),
                Builder::make('configuration')
                ->blocks([
                    Builder\Block::make('paragraph')
                        ->schema([
                            RichEditor::make('content')
                                ->label('Paragraph')
                                ->required(),
                        ]),
                    Builder\Block::make('task')
                        ->schema([
                            TextInput::make('content')
                                ->label('Task text')
                                ->required(),
                                Toggle::make('is_required')
                                ->label('Is Required')
                                ->required(),
                        ]),
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Process Name'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => ProcessResource\Pages\ListProcesses::route('/'),
            'create' => ProcessResource\Pages\CreateProcess::route('/create'),
            'view' => ProcessResource\Pages\ViewProcess::route('/{record}'),
            'edit' => ProcessResource\Pages\EditProcess::route('/{record}/edit'),
        ];
    }
}

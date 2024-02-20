<?php

namespace App\Filament\Resources;

use App\Filament\Imports\BacklinkImporter;
use App\Filament\Resources\BacklinkResource\Pages;
use App\Filament\Resources\BacklinkResource\RelationManagers;
use App\Models\Backlink;
use App\Models\Site;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ImportAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;

class BacklinkResource extends Resource
{
    protected static ?string $model = Backlink::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('site_id')->label('Site')
                    ->options(Site::all()->pluck('domain', 'id'))->required(),
                TextInput::make('link_url')->url()->required(),
                TextInput::make('linked_url')->url(),
                TextInput::make('domain_rank')->integer(),
                TextInput::make('contact_name'),
                TextInput::make('contact_email')->email(),
                Hidden::make('user_id')->default(Auth::id())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('link_url'),
                TextColumn::make('status_link_present')->badge()
                ->color(fn (string $state): string => match ($state) {
                    '1' => 'success',
                    '0' => 'danger'})->formatStateUsing(fn (string $state): string => ($state=='1' ? 'Live':'Removed'))->label('Status'),
                TextColumn::make('domain_rank')->label('DR'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(BacklinkImporter::class)->label('Import CSV')
                    ->maxRows(1000)
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
            'index' => Pages\ListBacklinks::route('/'),
            'create' => Pages\CreateBacklink::route('/create'),
            'edit' => Pages\EditBacklink::route('/{record}/edit'),
        ];
    }
}

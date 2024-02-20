<?php

namespace App\Filament\Imports;

use App\Models\Backlink;
use App\Models\Site;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;

class BacklinkImporter extends Importer
{
    protected static ?string $model = Backlink::class;


    public static function getOptionsFormComponents(): array
    {
        return [
            Select::make('site_id')->label('Site')
                    ->options(Site::all()->pluck('domain', 'id'))->required(),
        ];
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('link_url')
            ->requiredMapping()
            ->rules(['required', 'max:500', 'url']),
            ImportColumn::make('domain_rank')
            ->rules(['numeric']),
        ];
    }

    protected function beforeSave(): void
    {
        $this->record['site_id']=$this->options['site_id'];
        $this->record['user_id']=Auth::id();
    }

    public function resolveRecord(): ?Backlink
    {
         return Backlink::firstOrNew([
             'link_url' => $this->data['link_url'],
             'site_id' => $this->options['site_id']
         ]);

        //return new Backlink();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your backlink import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}

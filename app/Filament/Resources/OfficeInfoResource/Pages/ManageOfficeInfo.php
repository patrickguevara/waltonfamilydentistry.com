<?php

namespace App\Filament\Resources\OfficeInfoResource\Pages;

use App\Filament\Resources\OfficeInfoResource;
use App\Models\OfficeInfo;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageOfficeInfo extends ManageRecords
{
    protected static string $resource = OfficeInfoResource::class;

    public function mount(): void
    {
        // Ensure office info record exists
        $officeInfo = OfficeInfo::first();

        if (!$officeInfo) {
            OfficeInfo::create([
                'practice_name' => 'Walton Family Dentistry',
                'phone' => '(512) 953-8362',
                'address_line1' => '4100 East 51st Street',
                'city' => 'Austin',
                'state' => 'TX',
                'zip' => '78723',
            ]);
        }

        // Always load the first (and only) record
        $this->record = OfficeInfo::first();

        $this->fillForm();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Save Changes')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }
}

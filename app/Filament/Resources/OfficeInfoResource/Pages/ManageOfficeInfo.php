<?php

namespace App\Filament\Resources\OfficeInfoResource\Pages;

use App\Filament\Resources\OfficeInfoResource;
use App\Models\OfficeInfo;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class ManageOfficeInfo extends EditRecord
{
    protected static string $resource = OfficeInfoResource::class;

    public function mount(int|string|null $record = null): void
    {
        // Ensure office info record exists
        $officeInfo = OfficeInfo::first();

        if (!$officeInfo) {
            $officeInfo = OfficeInfo::create([
                'practice_name' => 'Walton Family Dentistry',
                'phone' => '(512) 953-8362',
                'address_line1' => '4100 East 51st Street',
                'city' => 'Austin',
                'state' => 'TX',
                'zip' => '78723',
            ]);
        }

        // Mount with the singleton record ID
        parent::mount($officeInfo->id);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Office information updated successfully';
    }
}

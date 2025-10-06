<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('event_id')
                    ->required()
                    ->numeric(),
                TextInput::make('attendee_name')
                    ->required(),
                TextInput::make('attendee_email')
                    ->email(),
                DateTimePicker::make('used_at'),
                TextInput::make('checked_in_by'),
            ]);
    }
}

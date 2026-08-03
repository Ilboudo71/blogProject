<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required(),
                TextInput::make('first_name')
                    ->label('First Name')
                    ->required(),
                TextInput::make('number_phone')
                    ->label('Number Phone')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(),
                FileUpload::make('photo')
                    ->label('Photo')
                    ->image()
                    ->directory('users')
                    ->disk('public')    
            ]);
    }
}

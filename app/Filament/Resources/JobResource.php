<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobResource\Pages;
use App\Filament\Resources\JobResource\RelationManagers;
use App\Models\Job;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(2048),
                        Forms\Components\TextInput::make('company')
                            ->required()
                            ->maxLength(2048),
                        Forms\Components\RichEditor::make('description')
                            ->required(),
                        Forms\Components\RichEditor::make('qualifications')
                            ->required(),
                        Forms\Components\Select::make('faculty')
                            ->options([
                                'computing' => 'Computing',
                                'business' => 'Business',
                                'law' => 'Law',
                            ]),
                        Forms\Components\RichEditor::make('contact')
                            ->required(),
                        Forms\Components\TextInput::make('link')
                            ->required(),
                        Forms\Components\Toggle::make('active')
                            ->required(),
                        Forms\Components\Textarea::make('reason')
                            ->label('Inactive Comment')
                            ->Placeholder('if the post is not active, Write the for why the post is not active ')
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('published_at'),
                    ])->columnSpan(8),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail')
                    ])->columnSpan(4)
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextColumn::make('title')->searchable(['title', 'description'])->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Reason of Inactive'),
                Tables\Columns\TextColumn::make('faculty')
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Active User filter')
                    ->boolean()
                    ->placeholder('Both Active and Inactive')
                    ->falseLabel('Only Inactive Users')
                    ->trueLabel('Only Active Users')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'view' => Pages\ViewJob::route('/{record}'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }
}

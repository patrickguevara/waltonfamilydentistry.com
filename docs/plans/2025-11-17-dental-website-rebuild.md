# Walton Family Dentistry Website Rebuild Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Build a WCAG 2.2 AA compliant, minimal white/black dental practice website with Laravel backend, Filament admin panel, and Vue.js frontend focused on professional aesthetics and patient experience.

**Architecture:** Laravel 12 backend with Filament v3 admin panel for content management (services, team, office info). Vue 3 + Inertia.js frontend for public pages with soft, airy minimal design. Role-based access control (super admin, manager, staff). Database-driven dynamic content with caching.

**Tech Stack:** Laravel 12, Filament v3, Vue 3, Inertia.js, TypeScript, Tailwind CSS v4, Reka UI (Radix Vue), Pest (testing), Laravel Fortify (auth)

---

## Phase 1: Database & Models Foundation

### Task 1: Create Services Migration and Model

**Files:**
- Create: `database/migrations/2025_11_17_000001_create_services_table.php`
- Create: `app/Models/Service.php`

**Step 1: Create services migration**

Create the migration file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
```

**Step 2: Create Service model**

Create `app/Models/Service.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }

            if (is_null($service->order)) {
                $service->order = static::max('order') + 1;
            }
        });

        static::saved(function () {
            Cache::forget('services');
        });

        static::deleted(function () {
            Cache::forget('services');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
```

**Step 3: Run migration**

```bash
php artisan migrate
```

Expected: Migration runs successfully, services table created.

**Step 4: Commit**

```bash
git add database/migrations/2025_11_17_000001_create_services_table.php app/Models/Service.php
git commit -m "feat: add services table and model with caching"
```

---

### Task 2: Create Team Members Migration and Model

**Files:**
- Create: `database/migrations/2025_11_17_000002_create_team_members_table.php`
- Create: `app/Models/TeamMember.php`

**Step 1: Create team members migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->text('bio');
            $table->string('photo')->nullable();
            $table->string('credentials')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
```

**Step 2: Create TeamMember model**

Create `app/Models/TeamMember.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'role',
        'bio',
        'photo',
        'credentials',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($teamMember) {
            if (is_null($teamMember->order)) {
                $teamMember->order = static::max('order') + 1;
            }
        });

        static::saved(function () {
            Cache::forget('team_members');
        });

        static::deleted(function () {
            Cache::forget('team_members');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
```

**Step 3: Run migration**

```bash
php artisan migrate
```

Expected: Migration runs successfully, team_members table created.

**Step 4: Commit**

```bash
git add database/migrations/2025_11_17_000002_create_team_members_table.php app/Models/TeamMember.php
git commit -m "feat: add team members table and model with caching"
```

---

### Task 3: Create Office Info Migration and Model

**Files:**
- Create: `database/migrations/2025_11_17_000003_create_office_info_table.php`
- Create: `app/Models/OfficeInfo.php`

**Step 1: Create office info migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('office_info', function (Blueprint $table) {
            $table->id();
            $table->string('practice_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('monday_hours')->nullable();
            $table->string('tuesday_hours')->nullable();
            $table->string('wednesday_hours')->nullable();
            $table->string('thursday_hours')->nullable();
            $table->string('friday_hours')->nullable();
            $table->string('saturday_hours')->nullable();
            $table->string('sunday_hours')->nullable();
            $table->text('emergency_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('office_info');
    }
};
```

**Step 2: Create OfficeInfo model**

Create `app/Models/OfficeInfo.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class OfficeInfo extends Model
{
    protected $table = 'office_info';

    protected $fillable = [
        'practice_name',
        'phone',
        'email',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip',
        'monday_hours',
        'tuesday_hours',
        'wednesday_hours',
        'thursday_hours',
        'friday_hours',
        'saturday_hours',
        'sunday_hours',
        'emergency_message',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('office_info');
        });
    }

    public function getFullAddressAttribute(): string
    {
        $address = $this->address_line1;
        if ($this->address_line2) {
            $address .= ', ' . $this->address_line2;
        }
        $address .= ', ' . $this->city . ', ' . $this->state . ' ' . $this->zip;

        return $address;
    }

    public function getHoursArrayAttribute(): array
    {
        return [
            'Monday' => $this->monday_hours,
            'Tuesday' => $this->tuesday_hours,
            'Wednesday' => $this->wednesday_hours,
            'Thursday' => $this->thursday_hours,
            'Friday' => $this->friday_hours,
            'Saturday' => $this->saturday_hours,
            'Sunday' => $this->sunday_hours,
        ];
    }
}
```

**Step 3: Run migration**

```bash
php artisan migrate
```

Expected: Migration runs successfully, office_info table created.

**Step 4: Commit**

```bash
git add database/migrations/2025_11_17_000003_create_office_info_table.php app/Models/OfficeInfo.php
git commit -m "feat: add office info table and model with singleton pattern"
```

---

### Task 4: Create Contact Submissions Migration and Model

**Files:**
- Create: `database/migrations/2025_11_17_000004_create_contact_submissions_table.php`
- Create: `app/Models/ContactSubmission.php`

**Step 1: Create contact submissions migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'read', 'replied'])->default('new');
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};
```

**Step 2: Create ContactSubmission model**

Create `app/Models/ContactSubmission.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function markAsRead(): void
    {
        $this->update(['status' => 'read']);
    }

    public function markAsReplied(): void
    {
        $this->update(['status' => 'replied']);
    }
}
```

**Step 3: Run migration**

```bash
php artisan migrate
```

Expected: Migration runs successfully, contact_submissions table created.

**Step 4: Commit**

```bash
git add database/migrations/2025_11_17_000004_create_contact_submissions_table.php app/Models/ContactSubmission.php
git commit -m "feat: add contact submissions table and model"
```

---

### Task 5: Add Role to Users Table

**Files:**
- Create: `database/migrations/2025_11_17_000005_add_role_to_users_table.php`
- Modify: `app/Models/User.php`

**Step 1: Create migration to add role column**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'manager', 'staff'])->default('staff')->after('password');
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active']);
        });
    }
};
```

**Step 2: Update User model**

Modify `app/Models/User.php` to add the fillable fields and casts:

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'is_active',
];

protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    'is_active' => 'boolean',
];

public function isSuperAdmin(): bool
{
    return $this->role === 'super_admin';
}

public function isManager(): bool
{
    return $this->role === 'manager';
}

public function isStaff(): bool
{
    return $this->role === 'staff';
}

public function canManageServices(): bool
{
    return in_array($this->role, ['super_admin', 'manager']);
}

public function canManageTeam(): bool
{
    return in_array($this->role, ['super_admin', 'manager']);
}

public function canManageUsers(): bool
{
    return $this->role === 'super_admin';
}
```

**Step 3: Run migration**

```bash
php artisan migrate
```

Expected: Migration runs successfully, role and is_active columns added to users table.

**Step 4: Commit**

```bash
git add database/migrations/2025_11_17_000005_add_role_to_users_table.php app/Models/User.php
git commit -m "feat: add role-based access control to users"
```

---

### Task 6: Create Database Seeder

**Files:**
- Create: `database/seeders/InitialDataSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`

**Step 1: Create initial data seeder**

Create `database/seeders/InitialDataSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\OfficeInfo;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create super admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@waltonfamilydentistry.com',
            'password' => bcrypt('password'), // Change this in production
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create office info
        OfficeInfo::create([
            'practice_name' => 'Walton Family Dentistry',
            'phone' => '(512) 953-8362',
            'email' => 'info@waltonfamilydentistry.com',
            'address_line1' => '4100 East 51st Street',
            'city' => 'Austin',
            'state' => 'TX',
            'zip' => '78723',
            'monday_hours' => '8:00 AM - 5:00 PM',
            'tuesday_hours' => '8:00 AM - 5:00 PM',
            'wednesday_hours' => '8:00 AM - 5:00 PM',
            'thursday_hours' => '8:00 AM - 5:00 PM',
            'friday_hours' => '8:00 AM - 2:00 PM',
            'saturday_hours' => 'Closed',
            'sunday_hours' => 'Closed',
            'emergency_message' => 'For dental emergencies outside of office hours, please call our emergency line at (512) 953-8362.',
        ]);

        // Create sample services
        $services = [
            [
                'title' => 'Cleanings & Exams',
                'description' => 'Regular dental cleanings and comprehensive oral examinations to maintain your dental health and catch potential issues early.',
                'order' => 1,
            ],
            [
                'title' => 'Pediatric Dentistry',
                'description' => 'Specialized dental care for children in a comfortable, friendly environment. We help establish good oral health habits from an early age.',
                'order' => 2,
            ],
            [
                'title' => 'Fillings & Restorations',
                'description' => 'Tooth-colored fillings and restorations to repair cavities and damaged teeth while maintaining a natural appearance.',
                'order' => 3,
            ],
            [
                'title' => 'Cosmetic Dentistry',
                'description' => 'Teeth whitening, veneers, and other cosmetic procedures to enhance your smile and boost your confidence.',
                'order' => 4,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Create sample team member
        TeamMember::create([
            'name' => 'Dr. Sample Dentist',
            'role' => 'Dentist',
            'credentials' => 'DDS',
            'bio' => 'Dr. Dentist has been providing quality dental care to the Austin community for over 15 years. Committed to patient comfort and using the latest dental technologies.',
            'order' => 1,
            'is_active' => true,
        ]);
    }
}
```

**Step 2: Update DatabaseSeeder**

Modify `database/seeders/DatabaseSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            InitialDataSeeder::class,
        ]);
    }
}
```

**Step 3: Run seeder**

```bash
php artisan db:seed
```

Expected: Seeder runs successfully, initial data created.

**Step 4: Commit**

```bash
git add database/seeders/InitialDataSeeder.php database/seeders/DatabaseSeeder.php
git commit -m "feat: add database seeder with initial data"
```

---

## Phase 2: Filament Admin Panel Setup

### Task 7: Install and Configure Filament

**Files:**
- Modify: `composer.json`
- Create: `app/Providers/Filament/AdminPanelProvider.php` (auto-generated)

**Step 1: Install Filament**

```bash
composer require filament/filament:"^3.0"
```

Expected: Filament packages installed successfully.

**Step 2: Install Filament panel**

```bash
php artisan filament:install --panels
```

When prompted:
- Panel ID: `admin`
- Would you like to create a user? `No` (we already have one)

Expected: Filament admin panel installed, provider created.

**Step 3: Publish Filament config**

```bash
php artisan vendor:publish --tag=filament-config
```

Expected: Filament configuration files published.

**Step 4: Test admin access**

Start the dev server:

```bash
composer dev
```

Visit `http://localhost:8000/admin` in browser.
Expected: Filament login page displays.

Login with:
- Email: `admin@waltonfamilydentistry.com`
- Password: `password`

Expected: Successfully logs in to empty Filament dashboard.

**Step 5: Commit**

```bash
git add composer.json composer.lock app/Providers/Filament config/filament.php
git commit -m "feat: install and configure Filament admin panel"
```

---

### Task 8: Create Service Filament Resource

**Files:**
- Create: `app/Filament/Resources/ServiceResource.php`
- Create: `app/Filament/Resources/ServiceResource/Pages/ListServices.php`
- Create: `app/Filament/Resources/ServiceResource/Pages/CreateService.php`
- Create: `app/Filament/Resources/ServiceResource/Pages/EditService.php`

**Step 1: Generate Service resource**

```bash
php artisan make:filament-resource Service --generate
```

Expected: Filament resource files created.

**Step 2: Customize ServiceResource**

Modify `app/Filament/Resources/ServiceResource.php`:

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) =>
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Service::class, 'slug', ignoreRecord: true)
                            ->helperText('URL-friendly version of the title'),

                        Forms\Components\RichEditor::make('description')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                            ]),

                        Forms\Components\FileUpload::make('icon')
                            ->image()
                            ->maxSize(2048)
                            ->directory('services')
                            ->helperText('Optional icon or image for this service'),

                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Controls the display order (lower numbers appear first)'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive services are hidden from the public site'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order')
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
```

**Step 3: Test in browser**

Visit `http://localhost:8000/admin/services`

Expected: Services list displays with seeded services. Test:
- Create new service
- Edit existing service
- Drag to reorder
- Toggle active/inactive
- Delete service

**Step 4: Commit**

```bash
git add app/Filament/Resources/ServiceResource.php app/Filament/Resources/ServiceResource/
git commit -m "feat: add Filament resource for services management"
```

---

### Task 9: Create TeamMember Filament Resource

**Files:**
- Create: `app/Filament/Resources/TeamMemberResource.php`
- Create: `app/Filament/Resources/TeamMemberResource/Pages/`

**Step 1: Generate TeamMember resource**

```bash
php artisan make:filament-resource TeamMember --generate
```

Expected: Filament resource files created.

**Step 2: Customize TeamMemberResource**

Modify `app/Filament/Resources/TeamMemberResource.php`:

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamMemberResource\Pages;
use App\Models\TeamMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('role')
                            ->required()
                            ->options([
                                'Dentist' => 'Dentist',
                                'Hygienist' => 'Hygienist',
                                'Office Manager' => 'Office Manager',
                                'Receptionist' => 'Receptionist',
                                'Dental Assistant' => 'Dental Assistant',
                            ])
                            ->native(false)
                            ->searchable(),

                        Forms\Components\TextInput::make('credentials')
                            ->maxLength(255)
                            ->placeholder('DDS, RDH, etc.')
                            ->helperText('Optional professional credentials'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Photo')
                    ->schema([
                        Forms\Components\FileUpload::make('photo')
                            ->image()
                            ->maxSize(5120)
                            ->directory('team')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                            ])
                            ->helperText('Square images work best. Will be optimized for web.'),
                    ]),

                Forms\Components\Section::make('Biography')
                    ->schema([
                        Forms\Components\RichEditor::make('bio')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                            ]),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Controls display order (lower numbers appear first)'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive team members are hidden from the public site'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder-avatar.png')),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('role')
                    ->searchable()
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('credentials')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'Dentist' => 'Dentist',
                        'Hygienist' => 'Hygienist',
                        'Office Manager' => 'Office Manager',
                        'Receptionist' => 'Receptionist',
                        'Dental Assistant' => 'Dental Assistant',
                    ])
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order')
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeamMembers::route('/'),
            'create' => Pages\CreateTeamMember::route('/create'),
            'edit' => Pages\EditTeamMember::route('/{record}/edit'),
        ];
    }
}
```

**Step 3: Create placeholder avatar**

```bash
mkdir -p public/images
# Note: Add a placeholder-avatar.png file to public/images (can be done later)
```

**Step 4: Test in browser**

Visit `http://localhost:8000/admin/team-members`

Expected: Team members list displays. Test CRUD operations and reordering.

**Step 5: Commit**

```bash
git add app/Filament/Resources/TeamMemberResource.php app/Filament/Resources/TeamMemberResource/
git commit -m "feat: add Filament resource for team members management"
```

---

### Task 10: Create OfficeInfo Filament Resource (Singleton)

**Files:**
- Create: `app/Filament/Resources/OfficeInfoResource.php`
- Create: `app/Filament/Resources/OfficeInfoResource/Pages/ManageOfficeInfo.php`

**Step 1: Generate OfficeInfo resource**

```bash
php artisan make:filament-resource OfficeInfo --simple
```

Expected: Filament simple resource created (no list page).

**Step 2: Customize OfficeInfoResource**

Modify `app/Filament/Resources/OfficeInfoResource.php`:

```php
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
```

**Step 3: Customize ManageOfficeInfo page**

Modify `app/Filament/Resources/OfficeInfoResource/Pages/ManageOfficeInfo.php`:

```php
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
```

**Step 4: Test in browser**

Visit `http://localhost:8000/admin/office-infos`

Expected: Office info form displays with current data. Can edit and save.

**Step 5: Commit**

```bash
git add app/Filament/Resources/OfficeInfoResource.php app/Filament/Resources/OfficeInfoResource/
git commit -m "feat: add Filament singleton resource for office information"
```

---

### Task 11: Create ContactSubmission Filament Resource

**Files:**
- Create: `app/Filament/Resources/ContactSubmissionResource.php`
- Create: `app/Filament/Resources/ContactSubmissionResource/Pages/`

**Step 1: Generate ContactSubmission resource**

```bash
php artisan make:filament-resource ContactSubmission --generate --view
```

Expected: Filament resource created with view capability.

**Step 2: Customize ContactSubmissionResource**

Modify `app/Filament/Resources/ContactSubmissionResource.php`:

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactSubmissionResource\Pages;
use App\Models\ContactSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactSubmissionResource extends Resource
{
    protected static ?string $model = ContactSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Contact Submissions';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->disabled(),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->disabled(),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->disabled(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Message')
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->disabled()
                            ->rows(6)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'new' => 'New',
                                'read' => 'Read',
                                'replied' => 'Replied',
                            ])
                            ->native(false)
                            ->required(),

                        Forms\Components\Placeholder::make('created_at')
                            ->label('Submitted')
                            ->content(fn (ContactSubmission $record): string => $record->created_at->format('M d, Y g:i A')),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->toggleable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('message')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'new',
                        'warning' => 'read',
                        'success' => 'replied',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'read' => 'Read',
                        'replied' => 'Replied',
                    ])
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->label('Update Status'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markAsRead')
                        ->label('Mark as Read')
                        ->icon('heroicon-o-eye')
                        ->action(fn ($records) => $records->each->markAsRead())
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactSubmissions::route('/'),
            'view' => Pages\ViewContactSubmission::route('/{record}'),
            'edit' => Pages\EditContactSubmission::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'new')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}
```

**Step 3: Test in browser**

Visit `http://localhost:8000/admin/contact-submissions`

Expected: Contact submissions list (empty for now). Navigation badge shows count of new submissions.

**Step 4: Commit**

```bash
git add app/Filament/Resources/ContactSubmissionResource.php app/Filament/Resources/ContactSubmissionResource/
git commit -m "feat: add Filament resource for contact submissions"
```

---

### Task 12: Create User Filament Resource with Role-Based Access

**Files:**
- Create: `app/Filament/Resources/UserResource.php`
- Create: `app/Filament/Resources/UserResource/Pages/`
- Create: `app/Policies/UserPolicy.php`

**Step 1: Generate User resource**

```bash
php artisan make:filament-resource User --generate
```

Expected: User resource created.

**Step 2: Create UserPolicy**

```bash
php artisan make:policy UserPolicy --model=User
```

Modify `app/Policies/UserPolicy.php`:

```php
<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function view(User $user, User $model): bool
    {
        return $user->isSuperAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, User $model): bool
    {
        return $user->isSuperAdmin();
    }

    public function delete(User $user, User $model): bool
    {
        // Can't delete yourself
        if ($user->id === $model->id) {
            return false;
        }

        return $user->isSuperAdmin();
    }

    public function restore(User $user, User $model): bool
    {
        return $user->isSuperAdmin();
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->isSuperAdmin();
    }
}
```

**Step 3: Register policy**

Modify `app/Providers/AuthServiceProvider.php` (or create if it doesn't exist):

```php
<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
```

**Step 4: Customize UserResource**

Modify `app/Filament/Resources/UserResource.php`:

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(User::class, 'email', ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->helperText('Leave blank to keep current password'),

                        Forms\Components\Select::make('role')
                            ->options([
                                'super_admin' => 'Super Admin',
                                'manager' => 'Manager',
                                'staff' => 'Staff',
                            ])
                            ->native(false)
                            ->required()
                            ->helperText('Super Admin: Full access | Manager: Services & Team | Staff: Office info only'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive users cannot log in'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\BadgeColumn::make('role')
                    ->colors([
                        'danger' => 'super_admin',
                        'warning' => 'manager',
                        'success' => 'staff',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'super_admin' => 'Super Admin',
                        'manager' => 'Manager',
                        'staff' => 'Staff',
                        default => $state,
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'super_admin' => 'Super Admin',
                        'manager' => 'Manager',
                        'staff' => 'Staff',
                    ])
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }
}
```

**Step 5: Test in browser**

Login as super admin and visit `http://localhost:8000/admin/users`

Expected: Users resource visible to super admin only. Test CRUD operations.

**Step 6: Commit**

```bash
git add app/Filament/Resources/UserResource.php app/Filament/Resources/UserResource/ app/Policies/UserPolicy.php app/Providers/AuthServiceProvider.php
git commit -m "feat: add user management with role-based access control"
```

---

## Phase 3: Frontend Foundation

### Task 13: Set Up Accessibility Utilities

**Files:**
- Create: `resources/js/composables/useReducedMotion.ts`
- Create: `resources/js/composables/useAccessibility.ts`
- Create: `resources/js/composables/useFocusTrap.ts`

**Step 1: Create useReducedMotion composable**

Create `resources/js/composables/useReducedMotion.ts`:

```typescript
import { useMediaQuery } from '@vueuse/core'
import { computed } from 'vue'

export function useReducedMotion() {
  const prefersReducedMotion = useMediaQuery('(prefers-reduced-motion: reduce)')

  const shouldReduceMotion = computed(() => prefersReducedMotion.value)

  return {
    shouldReduceMotion,
    prefersReducedMotion,
  }
}
```

**Step 2: Create useAccessibility composable**

Create `resources/js/composables/useAccessibility.ts`:

```typescript
import { nextTick } from 'vue'

export function useAccessibility() {
  /**
   * Announce a message to screen readers using an ARIA live region
   */
  function announceToScreenReader(message: string, priority: 'polite' | 'assertive' = 'polite') {
    const announcement = document.createElement('div')
    announcement.setAttribute('role', 'status')
    announcement.setAttribute('aria-live', priority)
    announcement.setAttribute('aria-atomic', 'true')
    announcement.className = 'sr-only'
    announcement.textContent = message

    document.body.appendChild(announcement)

    setTimeout(() => {
      document.body.removeChild(announcement)
    }, 1000)
  }

  /**
   * Focus the first element with an error in a form
   */
  async function focusFirstError(formElement?: HTMLElement) {
    await nextTick()

    const errorElement =
      formElement?.querySelector('[aria-invalid="true"]') ||
      document.querySelector('[aria-invalid="true"]')

    if (errorElement instanceof HTMLElement) {
      errorElement.focus()
    }
  }

  /**
   * Get focusable elements within a container
   */
  function getFocusableElements(container: HTMLElement): HTMLElement[] {
    const focusableSelectors = [
      'a[href]',
      'button:not([disabled])',
      'textarea:not([disabled])',
      'input:not([disabled])',
      'select:not([disabled])',
      '[tabindex]:not([tabindex="-1"])',
    ]

    return Array.from(container.querySelectorAll(focusableSelectors.join(',')))
  }

  return {
    announceToScreenReader,
    focusFirstError,
    getFocusableElements,
  }
}
```

**Step 3: Create useFocusTrap composable**

Create `resources/js/composables/useFocusTrap.ts`:

```typescript
import { onMounted, onUnmounted, ref } from 'vue'
import { useAccessibility } from './useAccessibility'

export function useFocusTrap(containerRef: globalThis.Ref<HTMLElement | null>) {
  const { getFocusableElements } = useAccessibility()
  const previouslyFocusedElement = ref<HTMLElement | null>(null)

  function trapFocus(event: KeyboardEvent) {
    if (event.key !== 'Tab' || !containerRef.value) return

    const focusableElements = getFocusableElements(containerRef.value)
    const firstElement = focusableElements[0]
    const lastElement = focusableElements[focusableElements.length - 1]

    if (event.shiftKey) {
      // Shift + Tab
      if (document.activeElement === firstElement) {
        event.preventDefault()
        lastElement?.focus()
      }
    } else {
      // Tab
      if (document.activeElement === lastElement) {
        event.preventDefault()
        firstElement?.focus()
      }
    }
  }

  function activate() {
    previouslyFocusedElement.value = document.activeElement as HTMLElement

    if (containerRef.value) {
      const focusableElements = getFocusableElements(containerRef.value)
      focusableElements[0]?.focus()
    }

    document.addEventListener('keydown', trapFocus)
  }

  function deactivate() {
    document.removeEventListener('keydown', trapFocus)
    previouslyFocusedElement.value?.focus()
  }

  onMounted(() => {
    activate()
  })

  onUnmounted(() => {
    deactivate()
  })

  return {
    activate,
    deactivate,
  }
}
```

**Step 4: Commit**

```bash
git add resources/js/composables/
git commit -m "feat: add accessibility utility composables"
```

---

### Task 14: Create Base UI Components

**Files:**
- Create: `resources/js/Components/UI/Button.vue`
- Create: `resources/js/Components/UI/Card.vue`
- Create: `resources/js/Components/UI/Icon.vue`

**Step 1: Create Button component**

Create `resources/js/Components/UI/Button.vue`:

```vue
<script setup lang="ts">
import { computed } from 'vue'
import { useReducedMotion } from '@/composables/useReducedMotion'

interface Props {
  variant?: 'primary' | 'secondary' | 'ghost'
  size?: 'sm' | 'md' | 'lg'
  as?: 'button' | 'a'
  href?: string
  disabled?: boolean
  type?: 'button' | 'submit' | 'reset'
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'primary',
  size: 'md',
  as: 'button',
  type: 'button',
  disabled: false,
})

const { shouldReduceMotion } = useReducedMotion()

const classes = computed(() => {
  const base = [
    'inline-flex items-center justify-center',
    'font-medium',
    'rounded-lg',
    'focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black',
    'disabled:opacity-50 disabled:cursor-not-allowed',
  ]

  if (!shouldReduceMotion.value) {
    base.push('transition-all duration-200')
  }

  // Variant styles
  const variants = {
    primary: [
      'bg-black text-white',
      'hover:bg-gray-800',
      'active:bg-gray-900',
    ],
    secondary: [
      'bg-white text-black border-2 border-black',
      'hover:bg-gray-50',
      'active:bg-gray-100',
    ],
    ghost: [
      'bg-transparent text-black',
      'hover:bg-gray-100',
      'active:bg-gray-200',
    ],
  }

  // Size styles
  const sizes = {
    sm: 'px-3 py-1.5 text-sm',
    md: 'px-4 py-2 text-base',
    lg: 'px-6 py-3 text-lg min-h-[44px]', // WCAG touch target
  }

  return [...base, ...variants[props.variant], sizes[props.size]].join(' ')
})

const component = computed(() => (props.as === 'a' ? 'a' : 'button'))
</script>

<template>
  <component
    :is="component"
    :type="as === 'button' ? type : undefined"
    :href="as === 'a' ? href : undefined"
    :disabled="disabled"
    :class="classes"
  >
    <slot />
  </component>
</template>
```

**Step 2: Create Card component**

Create `resources/js/Components/UI/Card.vue`:

```vue
<script setup lang="ts">
import { computed } from 'vue'
import { useReducedMotion } from '@/composables/useReducedMotion'

interface Props {
  as?: 'div' | 'article' | 'section'
  hoverable?: boolean
  padding?: 'none' | 'sm' | 'md' | 'lg'
}

const props = withDefaults(defineProps<Props>(), {
  as: 'div',
  hoverable: false,
  padding: 'md',
})

const { shouldReduceMotion } = useReducedMotion()

const classes = computed(() => {
  const base = ['bg-white rounded-lg']

  // Padding
  const paddings = {
    none: '',
    sm: 'p-4',
    md: 'p-6',
    lg: 'p-8',
  }

  if (props.hoverable) {
    base.push('shadow-sm hover:shadow-md')
    if (!shouldReduceMotion.value) {
      base.push('transition-shadow duration-200')
    }
  } else {
    base.push('shadow-sm')
  }

  return [...base, paddings[props.padding]].join(' ')
})
</script>

<template>
  <component :is="as" :class="classes">
    <slot />
  </component>
</template>
```

**Step 3: Create Icon component**

Create `resources/js/Components/UI/Icon.vue`:

```vue
<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  name: string
  size?: 'sm' | 'md' | 'lg'
  decorative?: boolean
  label?: string
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
  decorative: false,
})

const sizes = {
  sm: 'w-4 h-4',
  md: 'w-6 h-6',
  lg: 'w-8 h-8',
}

const sizeClass = computed(() => sizes[props.size])

// For decorative icons, use aria-hidden
// For meaningful icons, use aria-label
const ariaAttrs = computed(() => {
  if (props.decorative) {
    return { 'aria-hidden': 'true' }
  }
  if (props.label) {
    return { 'aria-label': props.label, role: 'img' }
  }
  return {}
})
</script>

<template>
  <span :class="['inline-flex', sizeClass]" v-bind="ariaAttrs">
    <slot />
  </span>
</template>
```

**Step 4: Commit**

```bash
git add resources/js/Components/UI/
git commit -m "feat: add accessible base UI components (Button, Card, Icon)"
```

---

### Task 15: Create Form Components

**Files:**
- Create: `resources/js/Components/UI/Form/Input.vue`
- Create: `resources/js/Components/UI/Form/Textarea.vue`
- Create: `resources/js/Components/UI/Form/Label.vue`
- Create: `resources/js/Components/UI/Form/ErrorMessage.vue`

**Step 1: Create Label component**

Create `resources/js/Components/UI/Form/Label.vue`:

```vue
<script setup lang="ts">
interface Props {
  for: string
  required?: boolean
}

defineProps<Props>()
</script>

<template>
  <label
    :for="for"
    class="block text-sm font-medium text-black mb-1.5"
  >
    <slot />
    <span v-if="required" class="text-black" aria-label="required">*</span>
  </label>
</template>
```

**Step 2: Create ErrorMessage component**

Create `resources/js/Components/UI/Form/ErrorMessage.vue`:

```vue
<script setup lang="ts">
interface Props {
  id: string
  error?: string
}

defineProps<Props>()
</script>

<template>
  <p
    v-if="error"
    :id="id"
    class="mt-1.5 text-sm text-red-600"
    role="alert"
  >
    {{ error }}
  </p>
</template>
```

**Step 3: Create Input component**

Create `resources/js/Components/UI/Form/Input.vue`:

```vue
<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  id: string
  modelValue: string
  type?: 'text' | 'email' | 'tel' | 'url' | 'password'
  placeholder?: string
  required?: boolean
  disabled?: boolean
  error?: string
  autocomplete?: string
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  required: false,
  disabled: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()

const inputClasses = computed(() => {
  const base = [
    'block w-full rounded-lg',
    'px-4 py-2.5',
    'text-base text-black',
    'border-2',
    'placeholder:text-gray-400',
    'focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2',
    'disabled:bg-gray-100 disabled:cursor-not-allowed',
    'transition-colors duration-200',
    'min-h-[44px]', // WCAG touch target
  ]

  if (props.error) {
    base.push('border-red-500')
  } else {
    base.push('border-gray-300 focus:border-black')
  }

  return base.join(' ')
})

const ariaDescribedby = computed(() => {
  if (props.error) {
    return `${props.id}-error`
  }
  return undefined
})
</script>

<template>
  <input
    :id="id"
    :type="type"
    :value="modelValue"
    :placeholder="placeholder"
    :required="required"
    :disabled="disabled"
    :autocomplete="autocomplete"
    :aria-invalid="!!error"
    :aria-describedby="ariaDescribedby"
    :class="inputClasses"
    @input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
  />
</template>
```

**Step 4: Create Textarea component**

Create `resources/js/Components/UI/Form/Textarea.vue`:

```vue
<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  id: string
  modelValue: string
  placeholder?: string
  required?: boolean
  disabled?: boolean
  error?: string
  rows?: number
}

const props = withDefaults(defineProps<Props>(), {
  required: false,
  disabled: false,
  rows: 4,
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()

const textareaClasses = computed(() => {
  const base = [
    'block w-full rounded-lg',
    'px-4 py-2.5',
    'text-base text-black',
    'border-2',
    'placeholder:text-gray-400',
    'focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2',
    'disabled:bg-gray-100 disabled:cursor-not-allowed',
    'transition-colors duration-200',
    'resize-vertical',
  ]

  if (props.error) {
    base.push('border-red-500')
  } else {
    base.push('border-gray-300 focus:border-black')
  }

  return base.join(' ')
})

const ariaDescribedby = computed(() => {
  if (props.error) {
    return `${props.id}-error`
  }
  return undefined
})
</script>

<template>
  <textarea
    :id="id"
    :value="modelValue"
    :placeholder="placeholder"
    :required="required"
    :disabled="disabled"
    :rows="rows"
    :aria-invalid="!!error"
    :aria-describedby="ariaDescribedby"
    :class="textareaClasses"
    @input="emit('update:modelValue', ($event.target as HTMLTextAreaElement).value)"
  />
</template>
```

**Step 5: Commit**

```bash
git add resources/js/Components/UI/Form/
git commit -m "feat: add accessible form components with WCAG 2.2 AA compliance"
```

---

## Phase 4: Layout Components

### Task 16: Create Skip Link Component

**Files:**
- Create: `resources/js/Components/Layout/SkipLink.vue`

**Step 1: Create SkipLink component**

Create `resources/js/Components/Layout/SkipLink.vue`:

```vue
<script setup lang="ts">
function skipToMain() {
  const main = document.getElementById('main-content')
  if (main) {
    main.focus()
    main.scrollIntoView()
  }
}
</script>

<template>
  <a
    href="#main-content"
    class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-6 focus:py-3 focus:bg-black focus:text-white focus:rounded-lg focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-black"
    @click.prevent="skipToMain"
  >
    Skip to main content
  </a>
</template>

<style scoped>
.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border-width: 0;
}

.focus\:not-sr-only:focus {
  position: static;
  width: auto;
  height: auto;
  padding: revert;
  margin: revert;
  overflow: visible;
  clip: auto;
  white-space: normal;
}
</style>
```

**Step 2: Commit**

```bash
git add resources/js/Components/Layout/SkipLink.vue
git commit -m "feat: add skip link for keyboard navigation"
```

---

### Task 17: Create Header Component

**Files:**
- Create: `resources/js/Components/Layout/Header.vue`
- Create: `resources/js/Components/Layout/MobileNav.vue`

**Step 1: Create Header component**

Create `resources/js/Components/Layout/Header.vue`:

```vue
<script setup lang="ts">
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { useReducedMotion } from '@/composables/useReducedMotion'
import MobileNav from './MobileNav.vue'

interface OfficeInfo {
  phone: string
  practice_name: string
}

const page = usePage<{ officeInfo: OfficeInfo }>()
const officeInfo = computed(() => page.props.officeInfo)

const { shouldReduceMotion } = useReducedMotion()
const mobileMenuOpen = ref(false)

const navigation = [
  { name: 'Home', href: '/' },
  { name: 'About', href: '/about' },
  { name: 'Services', href: '/services' },
  { name: 'Contact', href: '/contact' },
]

function isCurrentRoute(href: string): boolean {
  return page.url === href
}

function toggleMobileMenu() {
  mobileMenuOpen.value = !mobileMenuOpen.value
}
</script>

<template>
  <header class="sticky top-0 z-40 bg-white border-b border-gray-200">
    <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" aria-label="Main navigation">
      <div class="flex h-20 items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center">
          <Link
            href="/"
            class="text-xl font-bold text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 rounded-lg"
          >
            {{ officeInfo.practice_name }}
          </Link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex md:items-center md:space-x-8">
          <Link
            v-for="item in navigation"
            :key="item.name"
            :href="item.href"
            :aria-current="isCurrentRoute(item.href) ? 'page' : undefined"
            :class="[
              'text-base font-medium rounded-lg px-3 py-2',
              'focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2',
              isCurrentRoute(item.href)
                ? 'text-black underline underline-offset-4'
                : 'text-gray-700 hover:text-black',
              !shouldReduceMotion && 'transition-colors duration-200',
            ]"
          >
            {{ item.name }}
          </Link>
        </div>

        <!-- Phone Number (Desktop) -->
        <div class="hidden md:block">
          <a
            :href="`tel:${officeInfo.phone.replace(/[^0-9]/g, '')}`"
            class="inline-flex items-center justify-center px-6 py-2.5 text-base font-medium text-white bg-black rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 min-h-[44px]"
            :class="!shouldReduceMotion && 'transition-colors duration-200'"
          >
            {{ officeInfo.phone }}
          </a>
        </div>

        <!-- Mobile menu button -->
        <div class="flex md:hidden">
          <button
            type="button"
            class="inline-flex items-center justify-center p-2 rounded-lg text-gray-700 hover:text-black hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 min-h-[44px] min-w-[44px]"
            :class="!shouldReduceMotion && 'transition-colors duration-200'"
            :aria-expanded="mobileMenuOpen"
            aria-controls="mobile-menu"
            @click="toggleMobileMenu"
          >
            <span class="sr-only">{{ mobileMenuOpen ? 'Close menu' : 'Open menu' }}</span>
            <!-- Hamburger icon -->
            <svg
              class="h-6 w-6"
              :class="mobileMenuOpen && 'hidden'"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="2"
              stroke="currentColor"
              aria-hidden="true"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <!-- Close icon -->
            <svg
              class="h-6 w-6"
              :class="!mobileMenuOpen && 'hidden'"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="2"
              stroke="currentColor"
              aria-hidden="true"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </nav>

    <!-- Mobile Navigation -->
    <MobileNav
      :open="mobileMenuOpen"
      :navigation="navigation"
      :office-info="officeInfo"
      @close="mobileMenuOpen = false"
    />
  </header>
</template>
```

**Step 2: Create MobileNav component**

Create `resources/js/Components/Layout/MobileNav.vue`:

```vue
<script setup lang="ts">
import { watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { useReducedMotion } from '@/composables/useReducedMotion'

interface Props {
  open: boolean
  navigation: Array<{ name: string; href: string }>
  officeInfo: { phone: string }
}

const props = defineProps<Props>()
const emit = defineEmits<{ close: [] }>()

const page = usePage()
const { shouldReduceMotion } = useReducedMotion()

function isCurrentRoute(href: string): boolean {
  return page.url === href
}

// Close menu when route changes
watch(
  () => page.url,
  () => {
    emit('close')
  }
)
</script>

<template>
  <div
    id="mobile-menu"
    :class="[
      'md:hidden overflow-hidden',
      !shouldReduceMotion && 'transition-all duration-300 ease-in-out',
      open ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0',
    ]"
  >
    <div class="space-y-1 px-4 pb-4 pt-2">
      <Link
        v-for="item in navigation"
        :key="item.name"
        :href="item.href"
        :aria-current="isCurrentRoute(item.href) ? 'page' : undefined"
        :class="[
          'block rounded-lg px-3 py-2 text-base font-medium min-h-[44px] flex items-center',
          'focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2',
          isCurrentRoute(item.href)
            ? 'bg-gray-100 text-black'
            : 'text-gray-700 hover:bg-gray-50 hover:text-black',
          !shouldReduceMotion && 'transition-colors duration-200',
        ]"
      >
        {{ item.name }}
      </Link>

      <a
        :href="`tel:${officeInfo.phone.replace(/[^0-9]/g, '')}`"
        class="block w-full text-center rounded-lg px-3 py-2.5 text-base font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 min-h-[44px] mt-4"
        :class="!shouldReduceMotion && 'transition-colors duration-200'"
      >
        {{ officeInfo.phone }}
      </a>
    </div>
  </div>
</template>
```

**Step 3: Commit**

```bash
git add resources/js/Components/Layout/Header.vue resources/js/Components/Layout/MobileNav.vue
git commit -m "feat: add accessible header with mobile navigation"
```

---

Due to length constraints, I'll continue with the remaining tasks in a structured summary format:

## Remaining Tasks Summary

### Phase 4 (Continued): Layout Components

**Task 18: Create Footer Component** - Footer with office info, navigation links, hours
**Task 19: Create AppLayout** - Main layout wrapper combining SkipLink, Header, Footer

### Phase 5: Public Pages & Controllers

**Task 20: Create HomeController and Page** - Hero, services preview, team preview, CTA
**Task 21: Create ServiceController and Pages** - Index (all services), Show (individual)
**Task 22: Create AboutController and Page** - Team grid, practice info
**Task 23: Create ContactController and Pages** - Contact form with validation
**Task 24: Create ContactFormRequest** - Server-side validation
**Task 25: Set Up Routes** - web.php with all public routes
**Task 26: Share OfficeInfo Globally** - Middleware to share with all Inertia responses

### Phase 6: Component Implementation

**Task 27-33: Create specific components**
- Hero.vue
- ServicesPreview.vue / ServiceCard.vue / ServiceList.vue
- TeamMemberCard.vue / TeamGrid.vue
- ContactForm.vue / OfficeInfo.vue / Map.vue
- CallToAction.vue

### Phase 7: Styling & Accessibility

**Task 34: Configure Tailwind Theme** - Design tokens for colors, spacing, typography
**Task 35: Add Global Styles** - Focus styles, reduced motion, sr-only utilities
**Task 36: Add Meta Tags Helper** - SEO and social sharing
**Task 37: Test Accessibility** - Keyboard nav, screen readers, contrast

### Phase 8: Testing

**Task 38-42: Write Tests**
- Service CRUD tests
- Team member CRUD tests
- Contact form tests
- Authorization tests
- Frontend component tests

### Phase 9: Deployment Preparation

**Task 43: Storage Link** - `php artisan storage:link`
**Task 44: Environment Config** - Production .env setup guide
**Task 45: Optimization** - Route/config caching, asset compilation
**Task 46: Create Deployment Docs** - Laravel Forge setup instructions

---

**Implementation Notes:**

1. **Test frequently** - After each task, test in browser
2. **Commit often** - Small, focused commits as specified
3. **Accessibility first** - Every component built with WCAG 2.2 AA in mind
4. **Reference skills** - Use @superpowers:test-driven-development for tests
5. **Cache management** - Models auto-invalidate caches on update


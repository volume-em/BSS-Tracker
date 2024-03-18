<?php

namespace App\Livewire;

use App\Models\Setting;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Component;

class SetupApplication extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = ['create_sqlite' => true, 'db_connection' => 'mysql', 'db_host' => '127.0.0.1', 'db_port' => '3306', 'connection_failure' => false];

    public function mount(): void
    {
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Database')
                        ->beforeValidation(function (Get $get, Set $set) {
                            $this->testDatabaseConnection($get, $set);
                        })
                        ->schema([
                            Select::make('db_connection')
                                ->reactive()
                                ->options([
                                    'mysql' => 'MySQL/MariaDB',
                                    'postgres' => 'PostgreSQL',
                                    'sqlsrv' => 'SQL Server',
                                    'sqlite' => 'SQLite'
                                ])
                                ->label('Database Connection'),
                            Checkbox::make('create_sqlite')
                                ->reactive()
                                ->label('Create new SQLite File')
                                ->hidden(fn(Get $get) => $get('db_connection') !== 'sqlite')
                                ->helperText(database_path('database.sqlite')),
                            TextInput::make('sqlite_location')
                                ->label('SQLite File Location (absolute)')
                                ->hidden(fn(Get $get) => $get('db_connection') !== 'sqlite')
                                ->disabled(fn(Get $get) => $get('create_sqlite') === true),
                            TextInput::make('db_host')
                                ->label('Database Host')
                                ->hidden(fn(Get $get) => $get('db_connection') === 'sqlite'),
                            TextInput::make('db_port')
                                ->label('Database Port')
                                ->numeric()
                                ->hidden(fn(Get $get) => $get('db_connection') === 'sqlite'),
                            TextInput::make('db_database')
                                ->label('Database Name')
                                ->hidden(fn(Get $get) => $get('db_connection') === 'sqlite'),
                            TextInput::make('db_username')
                                ->label('Database Username')
                                ->hidden(fn(Get $get) => $get('db_connection') === 'sqlite'),
                            TextInput::make('db_password')
                                ->password()
                                ->label('Database Password')
                                ->hidden(fn(Get $get) => $get('db_connection') === 'sqlite'),
                            TextInput::make('connection_failure')
                                ->extraAttributes(['class' => 'hidden'])
                                ->label('')
                                ->rules([
                                    function () {
                                        return function (string $attribute, $value, \Closure $fail) {
                                            if ($this->data['connection_failure'] === true) {
                                                $fail('Failed to establish a connection to the database.');
                                            }
                                        };
                                    }
                                ]),
                        ]),
                    Wizard\Step::make('Configuration')
                        ->schema([
                            Checkbox::make('require_authentication')
                                ->reactive()
                                ->helperText('Check this box if you intend to require authentication to access the panel'),
                            Checkbox::make('use_itrust')
                                ->label('Use iTrust')
                                ->reactive()
                                ->hidden(
                                    fn (Get $get): bool => ! $get('require_authentication')
                                )
                                ->helperText('Use NIH iTrust configuration for authentication (requires additional setup)')
                        ]),
                    Wizard\Step::make('Administration')
                        ->schema([
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('email')
                                ->email()
                                ->required(),
                            TextInput::make('password')
                                ->password()
                                ->required()
                                ->confirmed()
                                ->minLength(6),
                            TextInput::make('password_confirmation')
                                ->password()
                                ->required()
                                ->label('Confirm Password')
                        ])
                ])->submitAction(new HtmlString(Blade::render(<<<BLADE
                    <x-filament::button
                        type="submit"
                        size="sm"
                    >
                        Submit
                    </x-filament::button>
                BLADE)))
            ])
            ->statePath('data');
    }

    public function create()
    {
        $state = $this->form->getState();

        Setting::insert(['setting' => 'require_authentication', 'value' => $state['require_authentication'], 'created_at' => now(), 'updated_at' => now()]);

        $user = new User();

        $user->name = $state['name'];
        $user->email = $state['email'];
        $user->password = Hash::make('password');
        $user->role = 2;

        $user->save();

        touch(base_path('.bss-setup'));

        return redirect()->to('/');
    }

    protected function setEnvironment($key, $value, $initial = 'null')
    {
        $replaced = preg_replace(
            '/'.$key.'=' . $initial . '/',
            $key.'='.$value,
            file_get_contents(app()->environmentFilePath())
        );

        file_put_contents(app()->environmentFilePath(), $replaced);

        return true;
    }

    public function testDatabaseConnection(Get $get, Set $set)
    {
        $connection = [];

        if ($get('db_connection') === 'mysql' || $get('db_connection') === 'postgres' || $get('db_connection') === 'sqlsrv') {
            $connection['driver'] = $get('db_connection');
            $connection['host'] = $get('db_host');
            $connection['port'] = $get('db_port');
            $connection['database'] = $get('db_database');
            $connection['username'] = $get('db_username');
            $connection['password'] = $get('db_password');
        }

        Config::set('database.connections.test', $connection);

        try {
            DB::connection('test')->table('bss_null')->select('bss_null')->get();

            $this->persistDatabase($connection);
        } catch (\Exception $e) {
            if (! Str::contains($e->getMessage(), 'Base table or view not found')) {
                $set('connection_failure', true);
            } else {
                $this->persistDatabase($connection);
                $set('connection_failure', false);
            }
        }
    }

    public function persistDatabase($connection)
    {
        $this->setEnvironment('DB_CONNECTION', $connection['driver']);
        $this->setEnvironment('DB_HOST', $connection['host']);
        $this->setEnvironment('DB_PORT', $connection['port']);
        $this->setEnvironment('DB_DATABASE', $connection['database']);
        $this->setEnvironment('DB_USERNAME', $connection['username']);
        $this->setEnvironment('DB_PASSWORD', $connection['password']);

        Config::set('database.connections.mysql', $connection);

        Artisan::call('migrate', ['--force' => true, '--database' => 'mysql']);
        Artisan::call('db:seed', ['--force' => true, '--database' => 'mysql']);
    }

    public function render()
    {
        return view('livewire.setup-application', $this->data);
    }
}

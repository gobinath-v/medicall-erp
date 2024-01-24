<?php


use App\Http\Livewire\AnnouncementSummary;
use App\Models\User;
use App\Models\Visitor;

use App\Models\Exhibitor;
use Illuminate\Http\Request;
use App\Http\Livewire\HallLayout;
use App\Models\UserLoginActivity;
use Illuminate\Support\Facades\DB;
use App\Http\Livewire\FindProducts;
use Illuminate\Support\Facades\Log;
use App\Http\Livewire\EditExhibitor;
use App\Http\Livewire\EventsSummary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Livewire\MyAppointments;
use App\Http\Livewire\ProductSummary;
use App\Http\Livewire\VisitorHandler;
use App\Http\Livewire\VisitorProfile;
use App\Http\Livewire\VisitorSummary;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\CategorySummary;
use App\Http\Livewire\VisitorWishlist;
use App\Http\Livewire\EventInformation;
use App\Http\Livewire\ExhibitorHandler;
use App\Http\Livewire\ExhibitorProfile;
use App\Http\Livewire\ExhibitorSummary;
use App\Http\Livewire\MyProductSummary;
use Illuminate\Support\Facades\Artisan;
use App\Http\Livewire\AppointmentSummary;
use App\Http\Livewire\ExhibitorDirectory;
use App\Http\Livewire\VisitorRegistration;
use App\Http\Livewire\EventExhibitorProfile;
use App\Http\Controllers\MigrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Livewire\EventFormSummary;
use App\Http\Livewire\Profile\UserProfileSettings;
use App\Http\Livewire\Import\Visitors as ImportVisitors;
use App\Http\Livewire\Settings\Employee\EmployeeSummary;
use App\Http\Livewire\Import\Exhibitors as ImportExhibitors;
use App\Http\Livewire\MappingToExhibitor;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', function () {
    return redirect()->route('login');
    // return view('welcome');
});

Route::get('/admin-login', function () {
    return view('auth.admin-login');
})->name('admin-login-form');

Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
Route::post('/request-otp', [LoginController::class, 'requestOtp'])->name('request-otp');
Route::post('/admin-login', [LoginController::class, 'authenticateAdmin'])->name('admin.login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware([
    // 'auth:sanctum',
    // config('jetstream.auth_session'),
    // 'verified',
    // 'auth',
    'web',
])->group(function () {

    // Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

    Route::get('/dashboard', function () {

        if (Auth::guard('exhibitor')->check()) {
            return redirect()->route('dashboard.exhibitor');
        }

        if (Auth::guard('visitor')->check()) {
            return redirect()->route('dashboard.visitor');
        }

        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard.user');
        }

        return "Not Allowed";
    })->name('dashboard');
    Route::get('/exhibitor/summary', ExhibitorSummary::class)->name('exhibitor.summary');
    Route::get('/exhibitor/edit/{exhibitorId}/{eventId?}', EditExhibitor::class)->name('exhibitor.edit');
    Route::get('/products', ProductSummary::class)->name('products');
    Route::get('/events', EventsSummary::class)->name('events');
    Route::get('/products', ProductSummary::class)->name('products');
    Route::get('/settings/user-profile', UserProfileSettings::class)->name('user.profile');
    Route::get('/settings/employees', EmployeeSummary::class)->name('employees.index');
    Route::get('/category', CategorySummary::class)->name('category');
    Route::get('/visitors', VisitorSummary::class)->name('visitors.summary');
    Route::get('/visitors/{visitorId}/edit/{eventId?}', VisitorHandler::class)->name('visitors.edit');
    Route::get('/myappointments', MyAppointments::class)->name('myappointments');
    Route::get('/event_information', EventInformation::class)->name('event-informations');
    Route::get('/hall-layout', HallLayout::class)->name('hall-layout');
    Route::get('/exhibitor/directory', ExhibitorDirectory::class)->name('exhibitor.directory');

    Route::get('/import/exhibitors', ImportExhibitors::class)->name('import.exhibitors');
    Route::get('/sales_person_mapping', MappingToExhibitor::class)->name('sales-person-mapping');
});

Route::middleware(['auth:exhibitor'])->group(function () {
    Route::get('/exhibitor/profile', ExhibitorProfile::class)->name('exhibitor.profile');
    Route::get('/myproducts', MyProductSummary::class)->name('myproducts');
    Route::view('/exhibitor-dashboard', 'dashboards.exhibitor-dashboard')->name('dashboard.exhibitor');
});

Route::middleware(['auth:web'])->group(function () {

    Route::view('/user-dashboard/{eventId?}', 'dashboards.user-dashboard')->name('dashboard.user');
    Route::get('/admin-dashboard/{eventId?}', EventFormSummary::class)->name('admin-dashboard');

    Route::get('/appointment', AppointmentSummary::class)->name('appointment.summary');
});

Route::middleware(['auth:visitor'])->group(function () {

    Route::view('/visitor-dashboard', 'dashboards.visitor-dashboard')->name('dashboard.visitor');
    Route::get('/find-products', FindProducts::class)->name('visitor.find-products');
    Route::get('/visitor/profile', VisitorProfile::class)->name('visitor.profile');
    Route::get('/wishlists/{eventId}', VisitorWishlist::class)->name('visitor.wishlists');
    Route::get('/events/{eventId}/exhibitors/{exhibitorId}/profile', EventExhibitorProfile::class)->name('eventexhibitor.profile');
});

Route::get('/visitor-registration', VisitorRegistration::class)->name('visitor-registration');
Route::get('/exhibitor/registration', ExhibitorHandler::class)->name('exhibitor.registration');

Route::get('cls', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cache is cleared";
});

Route::get('symlink', function () {
    Artisan::call('storage:link');
    return "Sym link created";
});

Route::get('migrate-tables', function () {
    Artisan::call('migrate', ['--force' => true]);
    return "Tables migrated";
});

Route::get('check-otp', function () {
    $result = sendLoginOtp('9787480936', '369369');
    dd($result);
});

Route::get('check-current-event', function () {
    $result = getCurrentEvent();
    return json_encode($result, JSON_PRETTY_PRINT);
});


Route::get('/set-default-password', function () {

    User::where('id', '>', 0)->update([
        'password' => Hash::make(config('app.default_user_password')),
    ]);

    Exhibitor::where('id', '>', 0)->update([
        'password' => Hash::make(config('app.default_user_password')),
    ]);

    Visitor::where('id', '>', 0)->update([
        'password' => Hash::make(config('app.default_user_password')),
    ]);

    echo "Done, set default password for all users";
});


Route::get('/send-exhibitor-welcome-notification', function () {
    $exhibitors = Exhibitor::where('id', '>', 0)->get();
    foreach ($exhibitors as $exhibitor) {
        $result = sendWelcomeMessageThroughWhatsappBot($exhibitor->mobile_number, 'exhibitor');
        Log::info("Result");
        Log::info($result);
    }
});

Route::get('import/visitors', [ImportVisitors::class, 'import']);

// Send Notifications
Route::get('/send-remainders-to-all-users', [NotificationController::class, 'sendRemainderNotificationsToAllUsers']);
Route::get('/send-greetings-to-participated-visitors', [NotificationController::class, 'sendGreetingsNotificationsToParticipatedVisitors']);

// Below routes are for migration purpose only
Route::get('/send-visitor-welcome-notifications-through-bot', [MigrationController::class, 'sendVisitorsWelcomeNotification']);
Route::get('/update-event-products-in-string-format', [MigrationController::class, 'updateEventProductsInStringFormat']);
Route::get('/update-designation-field-in-1ot-visitors', [MigrationController::class, 'updateDesignationFieldIn10TVisitorsTable']);
Route::get('/registering-visitors-who-makes-appointments-without-register', [MigrationController::class, 'registeringVisitorsWhoMakesAppointmentsWithoutRegister']);
// Import missing 10T visitors
Route::get('/insert-missing-10t-visitors', [ImportVisitors::class, 'insertMissing10TVisitors']);

Route::get('/login-logs', function () {
    $visitorLogs = UserLoginActivity::where('userable_type', 'App\Models\Visitor')
        ->select('userable_id', DB::raw('count(*) as login_count'))
        ->groupBy('userable_id')
        ->get();

    $exhibitorLogs = UserLoginActivity::where('userable_type', 'App\Models\Exhibitor')
        ->select('userable_id', DB::raw('count(*) as login_count'))
        ->groupBy('userable_id')
        ->get();

    $totalVisitorLoginsCount = count($visitorLogs);
    $totalExhibitorLoginsCount = count($exhibitorLogs);

    $html = '<p><strong>Visitors -- (' . $totalVisitorLoginsCount . ')</strong></p>';

    $html .= '<ul>';
    foreach ($visitorLogs as $visitorLog) {
        $visitor = Visitor::find($visitorLog->userable_id);
        $html .= '<li>' . ($visitor->name ?? '') . ' - ' . ($visitorLog->login_count) . ' Times</li>';
    }
    $html .= '</ul>';
    $html .= '<p><strong>Exhibitors -- (' . $totalExhibitorLoginsCount . ')</strong></p>';

    $html .= '<ul>';
    foreach ($exhibitorLogs as $exhibitorLog) {
        $exibitor = Exhibitor::find($exhibitorLog->userable_id);
        $html .= '<li>' . ($exibitor->name ?? '') . ' - ' . ($exhibitorLog->login_count) . ' Times</li>';
    }

    $html .= '</ul>';

    echo $html;
});

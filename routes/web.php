<?php

use Illuminate\Support\Facades\Route;

// ==========================================
// CONTROLADORES PÚBLICOS (ESTUDIANTES)
// ==========================================
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ScheduleController;
use App\Http\Controllers\Public\RegistrationController;
use App\Http\Controllers\Public\CertificateController as PublicCertificateController;
use App\Http\Controllers\Public\GalleryController as PublicGalleryController;
use App\Http\Controllers\Public\SurveyController;

// ==========================================
// CONTROLADORES DE ADMINISTRACIÓN (CMS)
// ==========================================
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SpeakerController;
use App\Http\Controllers\Admin\ScheduleDayController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\SurveyController as AdminSurveyController;


/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (El sitio web que ve el alumno)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cronograma', [ScheduleController::class, 'index'])->name('schedule');
Route::get('/registro', [RegistrationController::class, 'create'])->name('registration.create');
Route::post('/registro', [RegistrationController::class, 'store'])->name('registration.store');
Route::get('/galeria', [PublicGalleryController::class, 'index'])->name('gallery');
Route::get('/encuesta', [SurveyController::class, 'create'])->name('survey.create');
Route::post('/encuesta', [SurveyController::class, 'store'])->name('survey.store');

// Rutas para Certificados (Público - Búsqueda y Descarga)
Route::get('/certificados', [PublicCertificateController::class, 'search'])->name('certificates.search');
Route::post('/certificados/buscar', [PublicCertificateController::class, 'find'])->name('certificates.find');
// Aquí irá la ruta de descarga PDF más adelante


/*
|--------------------------------------------------------------------------
| RUTAS DE ADMINISTRACIÓN (CMS)
|--------------------------------------------------------------------------
*/

// Rutas de Login (No requieren estar logueado)
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Rutas Protegidas (Solo para administradores logueados)
Route::prefix('admin')->middleware('admin')->group(function () {
    
    // Dashboard Principal
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // CRUDs Básicos
    Route::resource('speakers', SpeakerController::class)->names('admin.speakers');
    Route::resource('schedule-days', ScheduleDayController::class)->names('admin.schedule-days');
    Route::resource('activities', ActivityController::class)->names('admin.activities');
    Route::resource('students', StudentController::class)->names('admin.students');
    Route::resource('attendances', AttendanceController::class)->names('admin.attendances');
    Route::resource('payments', PaymentController::class)->names('admin.payments');
    Route::resource('gallery', AdminGalleryController::class)->names('admin.gallery');
    Route::resource('materials', MaterialController::class)->names('admin.materials');
    Route::resource('surveys', AdminSurveyController::class)->names('admin.surveys');

    // Certificados (Lógica Especial)
    Route::resource('certificates', AdminCertificateController::class)->names('admin.certificates')->except(['create', 'edit', 'show']);
    Route::post('certificates/{certificate}/toggle-status', [AdminCertificateController::class, 'toggleStatus'])->name('admin.certificates.toggle');

    // Configuración del sitio (CMS Settings)
    Route::get('settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('admin.settings.update');
});
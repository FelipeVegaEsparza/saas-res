<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class LandingController extends Controller
{
    public function __construct()
    {
        // Compartir configuraciones con todas las vistas del landing
        View::composer('landing.*', function ($view) {
            $view->with([
                'companyName' => SystemSetting::get('company_name', 'RestaurantSaaS'),
                'companyLogo' => SystemSetting::get('company_logo', ''),
                'companyPhone' => SystemSetting::get('company_phone', '+56 9 1234 5678'),
                'companyEmail' => SystemSetting::get('company_email', 'info@restaurantsaas.com'),
                'companyAddress' => SystemSetting::get('company_address', ''),
                'companyDescription' => SystemSetting::get('company_description', 'Sistema completo de gestión para restaurantes.'),
                'socialFacebook' => SystemSetting::get('social_facebook', ''),
                'socialInstagram' => SystemSetting::get('social_instagram', ''),
                'socialTwitter' => SystemSetting::get('social_twitter', ''),
                'socialLinkedin' => SystemSetting::get('social_linkedin', ''),
                'socialYoutube' => SystemSetting::get('social_youtube', ''),
            ]);
        });
    }

    public function index()
    {
        return view('landing.index');
    }

    public function features()
    {
        return view('landing.features');
    }

    public function pricing()
    {
        $plans = Plan::where('active', true)->orderBy('price')->get();

        return view('landing.pricing', compact('plans'));
    }

    public function contact()
    {
        return view('landing.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        // Aquí puedes enviar un email o guardar en base de datos
        // Por ahora solo retornamos éxito

        return back()->with('success', '¡Gracias por contactarnos! Te responderemos pronto.');
    }

    public function tutorials()
    {
        $categories = \App\Models\TutorialCategory::where('is_active', true)
            ->with(['activeTutorials' => function($query) {
                $query->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        return view('landing.tutorials', compact('categories'));
    }
}

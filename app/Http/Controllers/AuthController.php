<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\Sales;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::viaRemember()) {
                return redirect()->route('dashboard');
            }
            return $next($request);
        })->except(['logout', 'login', 'index', 'create', 'store']);
    }

    public function index() {
        return view('auth.login');
    }

    public function create()
    {
        $sales = Sales::all();
        return view('auth.register', compact('sales'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            $request->session()->put('user', $user);

            if ($request->boolean('remember')) {
                $cookie = Cookie::make('user', $user, 1440);
                return redirect()->route('dashboard')->withCookie($cookie);
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Clear user's session
        Auth::logout();
        
        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // Remove remember me cookie if exists
        if ($cookie = Cookie::forget('user')) {
            return redirect()
                ->route('auth.login')
                ->with('logout', 'Logout berhasil !')
                ->withCookie($cookie);
        }

        return redirect()
            ->route('auth.login')
            ->with('logout', 'Logout berhasil !');
    }

    public function store(Request $request)
    {
        $rules = [
            'nama' => 'required|string|min:5|max:50',
            'email' => 'required|email|unique:users',
            'telepon' => 'required|string|min:10|max:13',
            'password' => 'required|string|min:8|max:35',
            'tahunMasuk' => 'required|string',
            'divisi' => 'required|string',
            'lokasi' => 'required|string',
            'terms' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('auth.register')
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        // Map roles to a single check
        $roles = [
            'roleProduksi', 'roleSales', 'roleDesain', 
            'roleKeuangan', 'roleLogistik', 'roleManajemen'
        ];
        
        $role = null;
        foreach ($roles as $roleKey) {
            if ($request->$roleKey) {
                $role = $request->$roleKey;
                break;
            }
        }

        // Map locations to codes using constant array
        $locationCodes = [
            'Surabaya' => '1',
            'Malang' => '2',
            'Kediri' => '3',
            'Sidoarjo' => '4'
        ];

        $tempatKerja = $locationCodes[$request->lokasi] ?? null;
        $tahunMasuk = substr($request->tahunMasuk, -2);

        try {
            // Begin transaction
            DB::beginTransaction();

            $user = User::create([
                'name' => ucwords(strtolower($request->nama)),
                'email' => $request->email,
                'phone' => $request->telepon,
                'password' => bcrypt($request->password),
                'role' => $role,
                'divisi' => $request->divisi
            ]);

            $nip = $tempatKerja . $tahunMasuk . $user->id;

            Employee::create([
                'nip' => $nip,
                'name' => ucwords(strtolower($request->nama)),
                'email' => $request->email,
                'phone' => $request->telepon,
                'division' => ucwords($request->divisi),
                'office' => $request->lokasi,
                'user_id' => $user->id
            ]);

            if ($request->roleSales && $request->salesApa) {
                Sales::where('id', $request->salesApa)
                    ->update(['user_id' => $user->id]);
            }

            $user->createToken('authToken')->plainTextToken;
            DB::commit();

            return redirect()->route('auth.login')
                ->with('success-register', 'Registrasi berhasil, silahkan login');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('auth.register')
                ->with('error', 'Terjadi kesalahan saat registrasi')
                ->withInput($request->except('password'));
        }
    }

    public function generateToken()
    {
        $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
            "instanceId" => "0958376f-0b36-4f59-adae-c1e55ff3b848",
            "secretKey" => "9F1455F4576C09A1DE06CBD4E9B3804F9184EF91978F3A9A92D7AD4B71656109",
        ));

        $userId = "user-" . Auth::user()->id;
        $token = $beamsClient->generateToken($userId);

        $user = User::find(Auth::user()->id);
        $user->beams_token = $token;
        $user->save();

        //Return the token to the client
        return response()->json($token);
    }

}

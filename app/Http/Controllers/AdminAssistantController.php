<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAssistantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (! $request->user() || $request->user()->role !== 'supervisor') {
                abort(403);
            }

            return $next($request);
        });
    }

    public function index()
    {
        $user = request()->user();
        $assistants = User::where('role', 'asisten')->orderBy('nim')->get();

        return view('admin_assistants.index', compact('user', 'assistants'));
    }

    public function create()
    {
        return view('admin_assistants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nim' => ['required', 'string', 'max:255', 'unique:users,nim'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'gender' => ['nullable', 'in:Laki-Laki,Perempuan'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'gender' => $data['gender'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'asisten',
        ]);

        return redirect()->route('admin.assistants.index')->with('success', 'Asisten berhasil ditambahkan.');
    }

    public function edit(User $assistant)
    {
        abort_if($assistant->role !== 'asisten', 404);

        return view('admin_assistants.edit', compact('assistant'));
    }

    public function update(Request $request, User $assistant)
    {
        abort_if($assistant->role !== 'asisten', 404);
$data = $request->validate([
    'nim' => ['required', 'string', 'max:255', 'unique:users,nim,' . $assistant->nim . ',nim'],
    'nama' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $assistant->nim . ',nim'],
    'gender' => ['nullable', 'in:Laki-Laki,Perempuan'],
    'password' => ['nullable', 'string', 'min:8', 'confirmed'],
]);

        $assistant->nim = $data['nim'];
        $assistant->nama = $data['nama'];
        $assistant->email = $data['email'];
        $assistant->gender = $data['gender'] ?? null;

        if (!empty($data['password'])) {
            $assistant->password = Hash::make($data['password']);
        }

        $assistant->save();

        return redirect()->route('admin.assistants.index')->with('success', 'Data asisten berhasil diperbarui.');
    }

    public function destroy(User $assistant)
    {
        abort_if($assistant->role !== 'asisten', 404);

        $assistant->delete();

        return redirect()->route('admin.assistants.index')->with('success', 'Asisten berhasil dihapus.');
    }
}

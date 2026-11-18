<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OperatorController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'operator')->withCount([
            'tasksAssigned as active_tasks_count' => function ($q) {
                $q->whereIn('status', ['assigned', 'accepted', 'in_progress']);
            },
            'tasksAssigned as completed_tasks_count' => function ($q) {
                $q->where('status', 'completed');
            }
        ]);

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(npk) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(username) LIKE ?', ["%{$search}%"]);
            });
        }

        $operators = $query->latest()->paginate(10);
        $totalOperators = User::where('role', 'operator')->count();

        return view('supervisor.operators.index', compact('operators', 'totalOperators'));
    }

    public function create()
    {
        return view('supervisor.operators.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'npk' => 'required|string|max:50|unique:users,npk',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:4',
            'shift' => 'required|string',
        ], [
            'name.required' => 'Nama lengkap operator wajib diisi.',
            'npk.required' => 'NPK wajib diisi.',
            'npk.unique' => 'NPK ini sudah terdaftar pada operator lain.',
            'username.required' => 'Username login operator wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan.',
            'password.required' => 'Password akun wajib diisi.',
            'password.min' => 'Password minimal 4 karakter.',
            'shift.required' => 'Shift default wajib dipilih.',
        ]);

        $email = $validated['email'] ?? strtolower($validated['username']) . '@fni.com';

        User::create([
            'name' => $validated['name'],
            'npk' => strtoupper($validated['npk']),
            'username' => strtolower($validated['username']),
            'email' => $email,
            'password' => Hash::make($validated['password']),
            'role' => 'operator',
            'shift' => $validated['shift'],
        ]);

        return redirect()->route('supervisor.operators.index')
            ->with('success', "Operator {$validated['name']} (NPK: {$validated['npk']}) berhasil didaftarkan.");
    }

    public function edit(User $operator)
    {
        if ($operator->role !== 'operator') {
            abort(404);
        }
        return view('supervisor.operators.edit', compact('operator'));
    }

    public function update(Request $request, User $operator)
    {
        if ($operator->role !== 'operator') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'npk' => ['required', 'string', 'max:50', Rule::unique('users', 'npk')->ignore($operator->id)],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($operator->id)],
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($operator->id)],
            'password' => 'nullable|string|min:4',
            'shift' => 'required|string',
        ]);

        $data = [
            'name' => $validated['name'],
            'npk' => strtoupper($validated['npk']),
            'username' => strtolower($validated['username']),
            'shift' => $validated['shift'],
        ];

        if (!empty($validated['email'])) {
            $data['email'] = $validated['email'];
        }

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $operator->update($data);

        return redirect()->route('supervisor.operators.index')
            ->with('success', "Data operator {$operator->name} berhasil diperbarui.");
    }

    public function destroy(User $operator)
    {
        if ($operator->role !== 'operator') {
            abort(404);
        }

        // Cek jika operator memiliki tugas yang sedang berjalan
        $activeTasks = $operator->tasksAssigned()
            ->whereIn('status', ['assigned', 'accepted', 'in_progress'])
            ->count();

        if ($activeTasks > 0) {
            return back()->with('error', "Operator {$operator->name} tidak dapat dihapus karena masih memiliki {$activeTasks} tugas aktif.");
        }

        $operator->delete();

        return redirect()->route('supervisor.operators.index')
            ->with('success', "Akun operator {$operator->name} berhasil dihapus dari sistem.");
    }
}

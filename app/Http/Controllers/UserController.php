<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\roles;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $roles = roles::all();
        return view('users.index', compact('roles'));
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
        $users = User::with('role')->select('users.*');

        return Datatables::of($users)
            ->addColumn('photo', function($row){
                return $row->photo ? '<img src="/storage/'.$row->photo.'" width="50" height="50"/>' : '';
            })
            ->addColumn('action', function($row){
                $btn = '<button class="btn btn-success btn-sm editUser" data-id="'.$row->id.'">Edit</button> ';
                $btn .= '<button class="btn btn-danger btn-sm deleteUser" data-id="'.$row->id.'">Hapus</button> ';
                $btn .= '<button class="btn btn-info btn-sm detailUser" data-id="'.$row->id.'">Detail</button> ';
                $btn .= $row->is_active
                    ? '<button class="btn btn-warning btn-sm deactivateUser" data-id="'.$row->id.'">Nonaktifkan</button>'
                    : '<button class="btn btn-primary btn-sm activateUser" data-id="'.$row->id.'">Aktifkan</button>';

                return $btn;
            })
            ->rawColumns(['photo', 'action'])
            ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'required|email|unique:users,email',
            'address' => 'nullable|string',
            'photo'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        $user = User::create([
            'role_id' => $request->role_id,
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'address' => $request->address,
            'photo'   => $photoPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User Berhasil ditambahkan!',
            'data'  => $user
        ]);
    }

    public function edit($id)
    {
        $user = User::with('role')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::FindOrFail($id);

        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'required|email|unique:users,email,'. $id,
            'address' => 'nullable|string',
            'photo'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $photoPath = $request->file('photo')->store('photos', 'public');
            $user->photo = $photoPath;
        }

        $user->role_id = $request->role_id;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate!',
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus!'
        ]);
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = true;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User diaktifkan!'
        ]);
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = false;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User dinonaktifkan!'
        ]);
    }


}
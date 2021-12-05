<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function data()
    {
        $user = User::isNotAdmin()->orderBy('id', 'desc')->get();

        return datatables()
            ->of($user)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('user.update', $user->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData('.$user->id.')" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->level = 2;
        $user->foto = 'dist/img/avatar04.png';
        $user->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id)
    {
        $user = User::find($id);

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->has('password') && $request->password != ""){
            $user->password = bcrypt($request->password);
        }
        $user->update();

        return response()->json('Data berhasil diupdate', 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        if ($user) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }

    public function profil()
    {
        $profil = auth()->user();
        return view('user.profil', compact('profil'));
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $user->name = $request->name;
        if($request->has('password') && $request->password != "")
        {
            if(Hash::check($request->old_password, $user->password))
            {
                if($request->password == $request->password_confirmation) {
                    $user->password = bcrypt($request->password);
                }
                else {
                    return response()->json('Konfirmasi password tidak sesuai', 422);
                }
            } else {
                return response()->json('Password lama tidak sesuai', 422);
            }
        }
        $image_foto = $user->foto;
        if($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama = 'logo-' . date('Y-m-dHis') . $file->getClientOriginalExtension();
            $file->move('img/',$nama);

            $user->foto = "/img/$nama";
            $image_foto = $user->foto;
        }
        $user->foto = $image_foto;
        $user->update();

        return response()->json($user, 200);
    }
}

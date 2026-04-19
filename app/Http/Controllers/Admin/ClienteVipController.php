<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClienteVip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ClienteVipController extends Controller
{
    public function index()
    {
        $clientes = ClienteVip::orderBy('created_at', 'desc')->get();
        return view('admin.vips.index', compact('clientes'));
    }

    public function create() { return redirect()->route('admin.vips.index'); }
    public function show(ClienteVip $vip) { return redirect()->route('admin.vips.index'); }
    public function edit(ClienteVip $vip) { return redirect()->route('admin.vips.index'); }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:100',
            'numero_tarjeta'=> 'required|string|unique:clientes_vip',
            'nip'           => 'required|string|min:4',
            'foto'          => 'nullable|image|max:2048',
            'fecha_nacimiento' => 'nullable|date',
        ]);

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('vips', 'public');
        }

        ClienteVip::create([
            'nombre'         => $request->nombre,
            'apodo'          => $request->apodo,
            'telefono'       => $request->telefono,
            'correo'         => $request->correo,
            'numero_tarjeta' => $request->numero_tarjeta,
            'nip'            => Hash::make($request->nip),
            'foto'           => $foto,
            'puntos'         => 0,
            'nivel'          => 'Silver',
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'activo'         => true,
        ]);

        return back()->with('success', 'Cliente VIP creado correctamente');
    }

    public function update(Request $request, ClienteVip $vip)
    {
        $request->validate(['nombre' => 'required|string|max:100']);

        $foto = $vip->foto;
        if ($request->hasFile('foto')) {
            if ($foto) Storage::disk('public')->delete($foto);
            $foto = $request->file('foto')->store('vips', 'public');
        }

        $data = [
            'nombre'    => $request->nombre,
            'apodo'     => $request->apodo,
            'telefono'  => $request->telefono,
            'correo'    => $request->correo,
            'foto'      => $foto,
            'puntos'    => $request->puntos ?? $vip->puntos,
            'activo'    => $request->has('activo'),
            'fecha_nacimiento' => $request->fecha_nacimiento,
        ];

        if ($request->filled('nip')) {
            $data['nip'] = Hash::make($request->nip);
        }

        // Actualizar nivel automáticamente
        $puntos = $data['puntos'];
        $data['nivel'] = $puntos >= 500 ? 'Platinum' : ($puntos >= 200 ? 'Gold' : 'Silver');

        $vip->update($data);
        return back()->with('success', 'Cliente VIP actualizado');
    }

    public function destroy(ClienteVip $vip)
    {
        if ($vip->foto) Storage::disk('public')->delete($vip->foto);
        $vip->delete();
        return back()->with('success', 'Cliente eliminado');
    }
}
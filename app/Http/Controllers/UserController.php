<?php

namespace App\Http\Controllers;

use App\Models\Dependencia;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->has('rol_id') OR !$request->has('name') OR !$request->has('apellidos')
            or !$request->has('codigo') OR !$request->has('email') OR !$request->has('dependencia_id')) {
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'errror'
            ], 400);
        }

        $rol = Rol::where(['id' => $request->get('rol_id')])->first();

        if (is_null($rol))
            return response()->json([
                'message' => 'Rol no encontrado',
                'data' => $request->toArray(),
                'status' => 'errror'
            ], 404);

        $dependencia = Dependencia::where(['id' => $request->get('dependencia_id')])->first();

        if (is_null($dependencia))
            return response()->json([
                'message' => 'Dependencia no encontrada',
                'data' => $request->toArray(),
                'status' => 'errror'
            ], 404);

        $usuario = Usuario::orWhere([
            'codigo' => $request->get('codigo'), 'email' => $request->get('email')
        ])->first();

        if (!is_null($usuario))
            return response()->json([
                'message' => 'Ya existe un usuario con ese correo y/o codigo',
                'data' => [],
                'status' => 'error'
            ], 400);

        $usuario = new Usuario([
            'rol_id' => $request->get('rol_id'),
            'name' => $request->get('name'),
            'apellidos' => $request->get('apellidos'),
            'codigo' => $request->get('codigo'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('codigo')),
            'dependencia_id' => $request->get('dependencia_id')
        ]);

        if ($usuario->save())
            return response()->json([
                'message' => 'Usuario registrado',
                'data' => [$usuario->toArray()],
                'status' => 'ok'
            ], 201);
        else
            return response()->json([
                'message' => 'Ha ocurido un error',
                'data' => [],
                'status' => 'error'
            ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param string $codigo
     * @return \Illuminate\Http\Response
     */
    public function show($codigo)
    {
        $eje = Usuario::where(['codigo' => $codigo])->with(['dependencia', 'rol', 'actividadesUsuarios'])->get()->toArray();

        if (count($eje) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $eje[0],
                'status' => 'ok'
            ], 200);
        else
            return response()->json([
                'message' => 'No existen registros',
                'data' => [],
                'status' => 'error'
            ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Usuario::where('id', $id)->first();

        if (is_null($user))
            return response()->json([
                'message' => 'No existe el usuario',
                'data' => [],
                'status' => 'error'
            ], 404);

        $rol = Rol::where(['id' => $request->get('rol_id')])->first();

        if (is_null($rol))
            return response()->json([
                'message' => 'Rol no encontrado',
                'data' => $request->toArray(),
                'status' => 'errror'
            ], 404);

        $dependencia = Dependencia::where(['id' => $request->get('dependencia_id')])->first();

        if (is_null($dependencia))
            return response()->json([
                'message' => 'Dependencia no encontrada',
                'data' => $request->toArray(),
                'status' => 'errror'
            ], 404);

        $usuario = Usuario::orWhere([
            'codigo' => $request->get('codigo'), 'email' => $request->get('email')
        ])->first();

        if (!is_null($usuario))
            return response()->json([
                'message' => 'Ya existe un usuario con ese correo y/o codigo',
                'data' => [],
                'status' => 'error'
            ], 400);

        $columnas = [];

        if ($request->has('rol_id')) $columnas['rol_id'] = $request->get('rol_id');
        if ($request->has('dependencia_id')) $columnas['dependencia_id'] = $request->get('dependencia_id');
        if ($request->has('name')) $columnas['name'] = $request->get('name');
        if ($request->has('apellidos')) $columnas['apellidos'] = $request->get('apellidos');
        if ($request->has('codigo')) $columnas['codigo'] = $request->get('codigo');
        if ($request->has('email')) $columnas['email'] = $request->get('email');

        if (count($columnas) == 0)
            return response()->json([
                'message' => 'Sin datos para modificar',
                'data' => [],
                'status' => 'ok'
            ], 200);

        if ($user->update($columnas))
            return response()->json([
                'message' => 'Actualización exitosa',
                'data' => [],
                'status' => 'ok'
            ], 200);
        else return response()->json([
            'message' => 'Ha ocurido un error',
            'data' => [],
            'status' => 'error'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getRoles()
    {
        return response()->json(['message' => 'Consulta exitosa', 'data' => Rol::with(['users'])->get()->toArray(), 'status' => 'ok']);
    }
}

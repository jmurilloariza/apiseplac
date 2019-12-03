<?php

namespace App\Http\Controllers;

use App\Mail\PasswordReset;
use App\Models\ActividadUsuario;
use App\Models\ProgramaAcademico;
use App\Models\Rol;
use App\Models\Usuario;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */
class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['passwordReset', 'passwordResetChange']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => 'Consulta exitosa',
            'data' => Usuario::with(['programaAcademico', 'rol', 'actividadesUsuarios'])->get()->toArray(),
            'status' => 'ok'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        if (!$request->has('rol_id') or !$request->has('name') or !$request->has('apellidos') or !$request->has('contrato') 
            or !$request->has('codigo') or !$request->has('email') or !$request->has('programa_academico_id')) {
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'errror'
            ], 200);
        }

        $rol = Rol::where(['id' => $request->get('rol_id')])->first();

        if (is_null($rol))
            return response()->json([
                'message' => 'Rol no encontrado',
                'data' => $request->toArray(),
                'status' => 'errror'
            ], 200);

        if($request->get('programa_academico_id') != null){
            $programaAcademico = ProgramaAcademico::where(['id' => $request->get('programa_academico_id')])->first();

            if (is_null($programaAcademico))
                return response()->json([
                    'message' => 'Programa no encontrado',
                    'data' => $request->toArray(),
                    'status' => 'errror'
                ], 200);
        }

        $usuario = Usuario::orWhere([
            'codigo' => $request->get('codigo'), 'email' => $request->get('email')
        ])->first();

        if (!is_null($usuario))
            return response()->json([
                'message' => 'Ya existe un usuario con ese correo y/o codigo',
                'data' => [],
                'status' => 'error'
            ], 200);

        $usuario = new Usuario([
            'rol_id' => $request->get('rol_id'),
            'name' => $request->get('name'),
            'apellidos' => $request->get('apellidos'),
            'codigo' => $request->get('codigo'),
            'email' => $request->get('email'),
            'contrato' => $request->get('contrato'),
            'password' => Hash::make($request->get('codigo')),
            'programa_academico_id' => $request->get('programa_academico_id')
        ]);

        if ($usuario->save())
            return response()->json([
                'message' => 'Usuario registrado',
                'data' => [$usuario->toArray()],
                'status' => 'ok'
            ], 201);

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
        $eje = Usuario::where(['codigo' => $codigo])->with(['programaAcademico', 'rol', 'actividadesUsuarios'])->get()->toArray();

        if (count($eje) > 0)
            return response()->json([
                'message' => 'Consulta exitosa',
                'data' => $eje[0],
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'No existen registros',
            'data' => [],
            'status' => 'error'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if (!$request->has('rol_id') or !$request->has('name') or !$request->has('apellidos') or !$request->has('contrato')
            or !$request->has('codigo') or !$request->has('email') or !$request->has('programa_academico_id')) {
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'errror'
            ], 200);
        }

        $user = Usuario::where('id', $id);

        if (!$user->exists())
            return response()->json([
                'message' => 'No existe el usuario',
                'data' => [],
                'status' => 'error'
            ], 200);

        $rol = Rol::where(['id' => $request->get('rol_id')])->first();

        if (is_null($rol))
            return response()->json([
                'message' => 'Rol no encontrado',
                'data' => $request->toArray(),
                'status' => 'errror'
            ], 200);

        if($request->get('programa_academico_id') != null){
            $programaAcademico = ProgramaAcademico::where(['id' => $request->get('programa_academico_id')])->first();

            if (is_null($programaAcademico))
                return response()->json([
                    'message' => 'Programa no encontrado',
                    'data' => $request->toArray(),
                    'status' => 'errror'
                ], 200);
        }


        $usuario = $user->get()->toArray()[0];

        if($request->get('email') != $usuario['email']){
            $usuario = Usuario::orWhere(['email' => $request->get('email')])->first();

            if (!is_null($usuario))
                return response()->json([
                    'message' => 'Ya existe un usuario con ese correo',
                    'data' => [],
                    'status' => 'error'
                ], 200);

        }

        if($request->get('codigo') != $usuario['codigo']){
            $usuario = Usuario::where(['codigo' => $request->get('codigo')])->first();

            if (!is_null($usuario))
                return response()->json([
                    'message' => 'Ya existe un usuario con ese codigo',
                    'data' => [$usuario],
                    'status' => 'error'
                ], 200);

        }
        
        $columnas = [];

        if ($request->has('rol_id')) $columnas['rol_id'] = $request->get('rol_id');
        if ($request->has('programa_academico_id')) $columnas['programa_academico_id'] = $request->get('programa_academico_id');
        if ($request->has('name')) $columnas['name'] = $request->get('name');
        if ($request->has('apellidos')) $columnas['apellidos'] = $request->get('apellidos');
        if ($request->has('codigo')) $columnas['codigo'] = $request->get('codigo');
        if ($request->has('email')) $columnas['email'] = $request->get('email');
        if ($request->has('contrato')) $columnas['contrato'] = $request->get('contrato');

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

        return response()->json([
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
        $usuario = Usuario::where(['id' => $id]);

        if ($usuario->delete()) {
            ActividadUsuario::where(['usuario_id' => $id])->delete();
            return response()->json([
                'message' => 'Usuario eliminado',
                'data' => [],
                'status' => 'ok'
            ], 200);
        }

        return response()->json([
            'message' => 'Ocurrió un error',
            'data' => [],
            'status' => 'error'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRoles()
    {
        return response()->json([
            'message' => 'Consulta exitosa',
            'data' => Rol::with(['users'])->get()->toArray(),
            'status' => 'ok'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $rol
     * @return \Illuminate\Http\Response
     */
    public function getUserRol($rol){
        return [
            'message' => 'Consulta exitosa', 
            'data' => Usuario::where(['rol_id' => $rol])->with(['programaAcademico'])->get()->toArray(), 
            'status' => 'ok'
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function passwordReset(Request $request)
    {
        if(!$request->has('email') or !$request->has('codigo'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'errror'
            ], 200);

        $usuario = Usuario::where(['email' => $request->get('email'), 'codigo' => $request->get('codigo')]);

        if(!$usuario->exists())
            return response()->json([
                'message' => 'No existe el usuario',
                'data' => [$request->toArray()],
                'status' => 'error'
            ], 200);
        
        $token = md5(time());
        $usuario->update(['remember_token' => $token]);

        Mail::to($request->get('email'), 'SEPLAC UFPS')->send(new PasswordReset($request->get('email'), $token));

        return [
            'message' => 'Hemos enviado un correo a '.$request->get('email').' para terminar el proceso de recuperación de contraseña', 
            'data' => [], 
            'status' => 'ok'
        ]; 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function passwordResetChange(Request $request)
    {
        if(!$request->has('token') or !$request->has('password') or !$request->has('email'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'errror'
            ], 200);

        $usuario = Usuario::where(['email' => $request->get('email'), 'remember_token' => $request->get('token')]);

        if(!$usuario->exists())
            return response()->json([
                'message' => 'No existe el usuario o se ha vencido su token',
                'data' => [],
                'status' => 'error'
            ], 200);
        
        $update = $usuario->update([
            'password' => Hash::make($request->get('password')), 
            'remember_token' => null
        ]);

        if($update)
            return response()->json([
                'message' => 'Actualización exitosa',
                'data' => [],
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'Ha ocurido un error',
            'data' => [],
            'status' => 'error'
        ], 500);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function passwordChange(Request $request, $id)
    {
        if(!$request->has('current_password') or !$request->has('new_password') or !$request->has('email'))
            return response()->json([
                'message' => 'Faltan datos',
                'data' => $request->toArray(),
                'status' => 'errror'
            ], 200);

        $usuario = User::where(['email' => $request->get('email'), 'id' => $id]);

        if(!$usuario->exists())
            return response()->json([
                'message' => 'No existe el usuario',
                'data' => [],
                'status' => 'error'
            ], 200);

        $values = $usuario->get()->toArray()[0];

        if (!Hash::check($request->get('current_password'), $values['password'])) 
            return response()->json([
                'message' => 'Contraseña incorrecta',
                'data' => [],
                'status' => 'error'
            ], 200);
        
        $update = $usuario->update([
            'password' => Hash::make($request->get('new_password')), 
        ]);

        if($update)
            return response()->json([
                'message' => 'Contraseña actualizada',
                'data' => [],
                'status' => 'ok'
            ], 200);

        return response()->json([
            'message' => 'Ha ocurido un error',
            'data' => [],
            'status' => 'error'
        ], 500);
    }
}

<?php

namespace App\Controllers;

use App\Models\AccionModel;
use App\Models\UsuarioModel;

class Principal extends BaseController
{
    protected $userModel;
    protected $accionModel;
    
    public function index()
    {
        return view('Estructura/body');
    }

    public function verAdministracion(){
        return view('Estructura/administracion');
    }

    public function mostrarRegistros()
    {
        $this->userModel = new UsuarioModel();
        $registros = $this->userModel->where('estado', 'activo')->findAll();
        $data['registros'] = $registros;
        return view('Estructura/administracion', $data);
    }

    public function agregarAdministracion(){
        return view('Estructura/registroAdministracion');
    }

    public function insertAdministracion(){
        $this->userModel = new UsuarioModel();
        $this->accionModel = new AccionModel();

        $nombre = $this->request->getPost("nombre");
        $apellidoPat = $this->request->getPost("apePat");
        $apellidoMat = $this->request->getPost("apeMat");
        $usuario = $this->request->getPost("usuario");
        $password = $this->request->getPost("passw");
        $rol = $this->request->getPost("rol");
        $celular = $this->request->getPost("celular");
        $direccion = $this->request->getPost("direccion");

        $dataUsuario = [
            'nombre' => $nombre,
            'apellidoPat' => $apellidoPat,
            'apellidoMat' => $apellidoMat,
            'usuario' => $usuario,
            'password' => $password,
            'rol' => $rol,
            'celular' => $celular,
            'direccion' => $direccion,
            'estado' => 'activo'
        ];

        $this->userModel->insert($dataUsuario);

       // $tipoAccion = $this->request->getPost("tipoAccion");
        $costo = $this->request->getPost("costo");
        $nroEstatura = $this->request->getPost("nroEstatura");
        $idUsuarioUlt = $this->userModel->getIdUsuarioUltimo();
        $idUsuarioUlt = $idUsuarioUlt[0]['idUsuario'];

        $dataAccion = [
            //'tipoAccion' => $tipoAccion,
            'costo' => $costo,
            'nroEstatura' => $nroEstatura,
            'estado' => 'activo',
            'Usuario_idUsuario' => $idUsuarioUlt
        ];

        $this->accionModel->insert($dataAccion);

        $mensaje = [
            'tipo' => 'success',
            'mensaje' => 'El registro se realizó de forma exitosa!'
        ];

        return view('Estructura/registroAdministracion', $mensaje);
    }

    public function updateFormAdministracion($id){
        $this->userModel = new UsuarioModel();
        $this->accionModel = new AccionModel();

        $datosUsuario = $this->userModel->getDatosUsuarioUpdate($id);
        $datosAccion = $this->accionModel->getDatosAccionUpdate($id);

        $data = [
            'datos' => $datosUsuario,
            'datosAccion' => $datosAccion
        ];
        return view('Estructura/formUpdateAdministracion', $data);
    }

    public function updateRegistro($idUsu){
        $this->userModel = new UsuarioModel();
        $this->accionModel = new AccionModel();

        $nombre = $this->request->getPost("nombre");
        $apellidoPat = $this->request->getPost("apePat");
        $apellidoMat = $this->request->getPost("apeMat");
        $usuario = $this->request->getPost("usuario");
        $password = $this->request->getPost("passw");
        $rol = $this->request->getPost("rol");
        $celular = $this->request->getPost("celular");
        $direccion = $this->request->getPost("direccion");

        $dataUsuario1 = [
            'nombre' => $nombre,
            'apellidoPat' => $apellidoPat,
            'apellidoMat' => $apellidoMat,
            'usuario' => $usuario,
            'password' => $password,
            'rol' => $rol,
            'celular' => $celular,
            'direccion' => $direccion
        ];

        $this->userModel->update($idUsu, $dataUsuario1);

        $tipoAccion = $this->request->getPost("tipoAccion");
        $costo = $this->request->getPost("costo");
        $nroEstatura = $this->request->getPost("nroEstatura");

        $idAccion = $this->accionModel->getDatosAccionUpdate($idUsu);

        if (!empty($idAccion) && isset($idAccion[0]['idAccion'])) {
            $dataAccion = [
                'tipoAccion' => $tipoAccion,
                'costo' => $costo,
                'nroEstatura' => $nroEstatura
            ];

            $this->accionModel->update($idAccion[0]['idAccion'], $dataAccion);
        } else {
            echo "Error: idAccion no está definido.";
        }

        return redirect()->to(base_url().'administracion');
    }

    public function deleteRegistroAdministracion($id){
        $this->userModel = new UsuarioModel();
        $this->accionModel = new AccionModel();

        $dataUsuario = [
            'estado' => 'inactivo'
        ];

        $this->userModel->update($id, $dataUsuario);
        return redirect()->to(base_url().'administracion');
    }
}

?>

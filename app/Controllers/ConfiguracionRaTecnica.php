<?php

namespace App\Controllers;

use App\Models\ConfiguracionRaTecnicaModel;

class ConfiguracionRaTecnica extends BaseController
{
    protected $raModel;

    public function __construct()
    {
        $this->raModel = new ConfiguracionRaTecnicaModel();
    }

    /**
     * Vista principal
     */
    public function index()
    {
        $data = [
            'titulo' => 'Configuración de Resultados de Aprendizaje'
        ];

        return view('calificaciones/configurarra', $data);
    }

    /**
     * Obtener RA configurados (AJAX)
     */
    public function obtener()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $idDistribucion = $this->request->getGet('id_distribucion_asignatura');
        $idSchoolYear   = $this->request->getGet('id_schoolyear');

        $ras = $this->raModel->getRasConfigurados(
            $idDistribucion,
            $idSchoolYear
        );

        return $this->response->setJSON($ras);
    }

    /**
     * Guardar configuración
     */
    public function guardarra()
    {
        $idDistribucion = $this->request->getPost('id_distribucion_asignatura');
        $idSchoolYear   = $this->request->getPost('id_schoolyear');

        $ras = $this->request->getPost('ra');

        if (empty($ras)) {
            return redirect()
                ->back()
                ->with('error', 'No se recibieron Resultados de Aprendizaje.');
        }

        $usuario = session('usuario_data.personal_id') ?? null;

        foreach ($ras as $numeroRa => $datos) {

            $valor = (float) ($datos['valor'] ?? 0);
            $minimo = ceil($valor * 0.70);

            $existente = $this->raModel->existeRa(
                $idDistribucion,
                $idSchoolYear,
                $numeroRa
            );

            $data = [
                'id_distribucion_asignatura' => $idDistribucion,
                'id_schoolyear'             => $idSchoolYear,
                'numero_ra'                 => $numeroRa,
                'valor_ra'                  => $valor,
                'minimo_ra'                 => $minimo,
                'activo'                    => 1,
                'updated_by'                => $usuario,
            ];

            if ($existente) {

                $data['fecha_edit'] = date('Y-m-d H:i:s');

                $this->raModel->update(
                    $existente['id'],
                    $data
                );

            } else {

                $data['created_by'] = $usuario;
                $data['fecha_alta'] = date('Y-m-d H:i:s');

                $this->raModel->insert($data);
            }
        }

        return redirect()
            ->back()
            ->with('mensaje', 'Configuración de RA guardada correctamente.');
    }
}
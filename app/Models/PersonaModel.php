<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonaModel extends Model
{
    protected $table            = 'personas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'numero',
        'nacionalidad',
        'cedula',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'sexo',
        'fecha_nacimiento',
        'edad',
        'telefono1',
        'telefono2',
        'correo_electronico',
        'carrera',
        'ano_semestre',
        'posee_beca',
        'sede',
        'estado',
        'siglas_universidad',
        'tipo_ieu',
        'nivel_academico',
        'urbanismo',
        'municipio',
        'parroquia',
        'tiene_hijos',
        'cantidad_hijos',
        'posee_discapacidad',
        'descripcion_discapacidad',
        'presenta_enfermedad',
        'condicion_medica',
        'medicamentos',
        'trabaja',
        'tipo_empleo',
        'medio_transporte',
        'inscrito_cne',
        'centro_electoral',
        'comuna',
        'estado_civil',
        'talla_camisa',
        'talla_zapatos',
        'talla_pantalon',
        'altura',
        'peso',
        'tipo_sangre',
        'carga_familiar',
        'fotos',
        'observaciones',
        'fecha_registro',
        'estado_registro',
        'usuario_registro',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'cedula'            => 'required|is_unique[personas.cedula]',
        'primer_nombre'     => 'required|min_length[2]|max_length[50]',
        'primer_apellido'   => 'required|min_length[2]|max_length[50]',
        'correo_electronico' => 'valid_email',
    ];

    protected $validationMessages = [
        'cedula' => [
            'required'   => 'La cédula es requerida',
            'is_unique'  => 'Ya existe una persona registrada con esta cédula',
        ],
        'primer_nombre' => [
            'required'    => 'El primer nombre es requerido',
            'min_length' => 'El nombre debe tener al menos 2 caracteres',
        ],
        'primer_apellido' => [
            'required'    => 'El primer apellido es requerido',
            'min_length'  => 'El apellido debe tener al menos 2 caracteres',
        ],
    ];

    /**
     * Obtiene el nombre completo de la persona
     */
    public function getNombreCompleto($id)
    {
        $persona = $this->find($id);
        if ($persona) {
            return trim($persona['primer_nombre'] . ' ' . 
                $persona['segundo_nombre'] . ' ' . 
                $persona['primer_apellido'] . ' ' . 
                $persona['segundo_apellido']);
        }
        return '';
    }

    /**
     * Busca personas por número de cédula
     */
    public function buscarPorCedula($cedula)
    {
        return $this->where('cedula', $cedula)->first();
    }

    /**
     * Obtiene personas con estado activo
     */
    public function getPersonasActivas()
    {
        return $this->where('estado_registro', 'ACTIVO')->findAll();
    }

    /**
     * Calcula la edad a partir de la fecha de nacimiento
     */
    public function calcularEdad($fechaNacimiento)
    {
        $nacimiento = new \DateTime($fechaNacimiento);
        $hoy = new \DateTime();
        return $hoy->diff($nacimiento)->y;
    }

    /**
     * Obtiene estadísticas del sistema
     */
    public function getEstadisticas()
    {
        $total = $this->countAll();
        $activos = $this->where('estado_registro', 'ACTIVO')->countAllResults();
        
        return [
            'total'     => $total,
            'activos'   => $activos,
            'inactivos' => $total - $activos,
        ];
    }
}

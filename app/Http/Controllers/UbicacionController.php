<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use PDO;

class UbicacionController extends Controller
{
    public function departamentos() {
        $db = DB::getConnection();
        $stmt = $db->query("SELECT * FROM si_solm.DBO.ADMI_DEPARTAMENTO order by DEPA_DESCRIPCION");
        return response()->json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function provincias($departamento_id) {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT * FROM si_solm.DBO.ADMI_PROVINCIA WHERE DEPA_CODIGO = ? ORDER BY PROVI_DESCRIPCION");
        $stmt->execute([$departamento_id]);
        return response()->json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function distritos($provincia_id) {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT * FROM si_solm.DBO.ADMI_DISTRITO WHERE PROVI_CODIGO = ? ORDER BY DIST_DESCRIPCION");
        $stmt->execute([$provincia_id]);
        return response()->json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}

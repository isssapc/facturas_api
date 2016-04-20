<?php

class Lote_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function add_nombre($nombre, $id_manzana, $id_prototipo) {

        $data = [];

        $data["nombre"] = $nombre;
        $data["id_manzana"] = $id_manzana;

        if ($id_prototipo != "") {
            $data["id_prototipo"] = $id_prototipo;
        }

        $this->db->insert('lote', $data);
        $id_lote = $this->db->insert_id();
        return $id_lote;
    }

    public function add_rango($id_manzana, $lote_ini, $lote_fin, $id_prototipo) {
        $datos = [];
        $this->db->trans_start();
        for ($i = $lote_ini; $i <= $lote_fin; $i++) {
            //insertamos
            $nombre = "Lote " . $i;
            $id_lote = $this->add_nombre($nombre, $id_manzana, $id_prototipo);
            //guardamos los ids
            $datos[] = array("id_lote" => $id_lote, "nombre" => $nombre, "id_manzana" => $id_manzana, "id_prototipo" => $id_prototipo);
        }
        $this->db->trans_complete();

        return $datos;
    }

    public function del_lote($id_lote) {

        $delete_avances = "DELETE FROM avancelote WHERE id_lote =$id_lote";
        $delete_trabajadores = "DELETE FROM especialidad_lote WHERE id_lote =$id_lote";
        $delete_lote = "DELETE FROM lote WHERE id_lote =$id_lote LIMIT 1";
        
        $this->db->query($delete_avances);
        $this->db->query($delete_trabajadores);
        $this->db->query($delete_lote);
        $count= $this->db->affected_rows();
        return $count;
    }

    public function add_avance($ids, $id_lote) {

        $now = date("Y-m-d");
        $sql = "INSERT INTO avancelote (id_lote, id_partida, fecha_fin) VALUES (?,?,?)";
        $count = 0;

        $this->db->trans_start();

        foreach ($ids as $id_partida) {
            $this->db->query($sql, array($id_lote, $id_partida, $now));
            $count+=$this->db->affected_rows();
        }

        $this->db->trans_complete();

        return array("count" => $count, "fecha" => $now);
    }

    public function add_liberacion_partida($ids, $id_lote) {

        $now = date("Y-m-d");
        $sql = "UPDATE avancelote SET fecha_liberacion=? where id_lote=? AND id_partida=?";
        $count = 0;


        $this->db->trans_start();

        foreach ($ids as $id_partida) {

            $this->db->query($sql, array($now, $id_lote, $id_partida));
            $count+=$this->db->affected_rows();
        }

        $this->db->trans_complete();

        return array("count" => $count, "fecha" => $now);
    }

    public function del_avance($ids, $id_lote) {

        $sql = "DELETE FROM avancelote WHERE id_lote=? AND id_partida=? LIMIT 1";
        $count = 0;

        $this->db->trans_start();

        foreach ($ids as $id_partida) {
            $this->db->query($sql, array($id_lote, $id_partida));
            $count+=$this->db->affected_rows();
        }

        $this->db->trans_complete();

        return array("count" => $count);
    }

    public function del_liberacion_partida($ids, $id_lote) {

        $sql = "UPDATE avancelote SET fecha_liberacion=NULL where id_lote=? AND id_partida=?";
        $count = 0;


        $this->db->trans_start();

        foreach ($ids as $id_partida) {

            $this->db->query($sql, array($id_lote, $id_partida));
            $count+=$this->db->affected_rows();
        }

        $this->db->trans_complete();

        return array("count" => $count);
    }

    public function AddTrabajador($ids, $id_trabajador) {

        $this->db->trans_start();
        $insert = "INSERT INTO especialidad_lote (id_trabajador, id_lote, id_familiainsumo) VALUES (:id_trabajador, :id_lote, :id_familia);";
        $statement = $this->conexion->prepare($insert);
        foreach ($ids as $id_lote_familia) {
            //$respuesta = $this->AddEspecialidad($id_lote_familia["id_lote"], $id_lote_familia["id_familia"], $id_trabajador);
            $respuesta = $statement->execute(array(":id_trabajador" => $id_trabajador,
                ":id_lote" => $id_lote_familia["id_lote"],
                ":id_familia" => $id_lote_familia["id_familia"]));
        }
        $this->db->trans_complete();

        return $respuesta;
    }

    public function add_especialidad_lote($id_lote, $familias, $id_trabajador) {

        $sql = "SELECT * FROM especialidad_lote WHERE id_lote=? AND id_familia_insumo=? LIMIT 1";
        $update = "UPDATE especialidad_lote SET id_trabajador=? WHERE id_lote=? AND id_familia_insumo=?";

        $count = 0;

        $this->db->trans_start();
        foreach ($familias as $id_familia) {
            $query = $this->db->query($sql, array($id_lote, $id_familia));
            $row = $query->row();
            if (isset($row)) {
                $this->db->query($update, array($id_trabajador, $id_lote, $id_familia));
                $count += $this->db->affected_rows();
            } else {
                $data = array("id_trabajador" => $id_trabajador, "id_lote" => $id_lote, "id_familia_insumo" => $id_familia);
                $this->db->insert('especialidad_lote', $data);
                $count += $this->db->affected_rows();
            }
        }
        $this->db->trans_complete();
        return $count;
    }

    public function GetPartidasAvanceAsJson($id_lote) {

        $sql = "SELECT 
    p.id_partida,
    p.codigo,
    p.nombre,
    s.id_partida AS id_subpartida,
    s.codigo AS codigo_subpartida,
    s.nombre AS nombre_subpartida,
    a.fecha_fin,
    a.fecha_liberacion
FROM
    partida p
        LEFT JOIN
    partida s ON p.id_partida = s.partida
        LEFT JOIN
    avancelote a ON a.id_lote = $id_lote
        AND (a.id_partida = p.id_partida
        OR a.id_partida = s.id_partida)
WHERE
    p.partida IS NULL
        AND p.id_prototipo = (SELECT 
            id_prototipo
        FROM
            lote
        WHERE
            id_lote = $id_lote)";


        $statement = $this->conexion->prepare($sql);
        $statement->execute(array());

        $partidas = [];

// si el contador no se mueve entonces no tiene prototipo asignado
        $numPartidas = 0;
        while ($fila = $statement->fetch()) {
            $numPartidas++;
//si no existe la partida
            if (!array_key_exists($fila["id_partida"], $partidas)) {
//agregamos la partida
                $partidas[$fila["id_partida"]] = array("id_partida" => $fila["id_partida"], "codigo" => $fila["codigo"], "nombre" => $fila["nombre"], "fecha_fin" => $fila["fecha_fin"], "fecha_liberacion" => $fila["fecha_liberacion"], "subpartidas" => array());
//si tiene subpartidas
                if ($fila["id_subpartida"] != NULL) {
//agregamos la subpartida
                    $partidas[$fila["id_partida"]]["subpartidas"][] = array("id_subpartida" => $fila["id_subpartida"], "codigo_subpartida" => $fila["codigo_subpartida"], "nombre_subpartida" => $fila["nombre_subpartida"], "fecha_fin" => $fila["fecha_fin"], "fecha_liberacion" => $fila["fecha_liberacion"]);
                }
            } else {
//ya hemos agregado la partida
//agregamos otra subpartida
                $partidas[$fila["id_partida"]]["subpartidas"][] = array("id_subpartida" => $fila["id_subpartida"], "codigo_subpartida" => $fila["codigo_subpartida"], "nombre_subpartida" => $fila["nombre_subpartida"], "fecha_fin" => $fila["fecha_fin"], "fecha_liberacion" => $fila["fecha_liberacion"]);
            }
        }
        return json_encode(array("partidas" => $partidas, "count" => $numPartidas));
    }

    public function set_prototipo($ids, $id_prototipo) {

        $update = "UPDATE lote SET id_prototipo=$id_prototipo WHERE id_lote = ?";
        $count = 0;
        $this->db->trans_start();

        foreach ($ids as $id_lote) {
            $this->db->query($update, array($id_lote));
            $count += $this->db->affected_rows();
        }
        $this->db->trans_complete();


        return $count;
    }

    public function get_especialidades($id_lote, &$prototipo) {
        $sql = "SELECT p.nombre
FROM lote l JOIN prototipo p 
ON p.id_prototipo = l.id_prototipo
WHERE l.id_lote = $id_lote;";

        $query = $this->db->query($sql);
        $row_prototipo = $query->row();

        if (isset($row_prototipo)) {
            $prototipo = $row_prototipo->nombre;

            $sql = "SELECT DISTINCT
    (i.id_familia),
    f.familia,
    f.especialidad,
    CONCAT_WS(' ',
            t.nombre,
            t.ap_paterno,
            t.ap_materno) AS nombre_trabajador
FROM
    partida pa
        JOIN
    insumo_partida ip ON ip.id_partida = pa.id_partida
        JOIN
    insumo i ON i.id_insumo = ip.id_insumo
        JOIN
    familia_insumo f ON f.id_familia = i.id_familia
        LEFT JOIN
    especialidad_lote el ON el.id_familia_insumo = i.id_familia AND el.id_lote= $id_lote
		LEFT JOIN
    trabajador t ON t.id_trabajador = el.id_trabajador
WHERE
    pa.id_prototipo = (SELECT id_prototipo FROM lote WHERE id_lote = $id_lote)
        AND (i.id_familia > 200
        AND i.id_familia < 300
        OR i.id_familia > 500
        AND i.id_familia < 600)	
ORDER BY f.familia , f.especialidad;";



            $query = $this->db->query($sql);

            return $query->result_array();
        } else {
            return [];
        }
        
    }

    public function GetPrototipo($id_lote) {
        $sql = "SELECT p.nombre
FROM lote l JOIN prototipo p 
ON p.id_prototipo = l.id_prototipo
WHERE l.id_lote = $id_lote;";

        $statement = $this->conexion->query($sql);
        $prototipo = $statement->fetch();
        return $prototipo['nombre'];
    }

    public function get_avances($id_lote) {
        $sql = "SELECT 
    p.id_partida,    
    p.nombre as partida,
    s.id_partida AS id_subpartida,   
    s.nombre AS subpartida,
    a.fecha_fin,
    a.fecha_liberacion
FROM
    partida p
        LEFT JOIN
    partida s ON p.id_partida = s.partida
        LEFT JOIN
    avancelote a ON (a.id_partida = p.id_partida OR a.id_partida = s.id_partida) and a.id_lote=$id_lote
WHERE
    p.partida IS NULL 
        AND p.id_prototipo = (SELECT id_prototipo FROM lote WHERE id_lote =$id_lote);";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

}

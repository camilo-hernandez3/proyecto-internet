<?php
require_once('db.php');
date_default_timezone_set('America/Bogota');

class Equipo extends Database
{


	public function index($usuario)
	{

		$rol = $_SESSION['rol'];
		$query = "";

		$query = $this->pdo->prepare('SELECT 
        p.id_piso,
        p.nombre,
        JSON_ARRAYAGG(
            JSON_OBJECT(
                "id_equipo", e.id_equipo,
                "nombre_equipo", e.descripcion,
                "ram", e.ram,
                "procesador", e.procesador,
                "almacenamiento", e.almacenamiento,
                "usuario_equipos", (
                    SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            "id_usuario", ue.usuarios_id_usuario,
                            "status", ue.status_,
                            "nombre_user", u.nombre,
                            "fecha_inicio", ue.fecha_inicio
                        )
                    )
                    FROM usuario_equipo ue
                    JOIN usuarios u ON ue.usuarios_id_usuario = u.id_usuario
                    WHERE ue.equipos_id_equipo = e.id_equipo AND ue.status_ = 1 AND ue.fecha_final IS NULL
                )
            )
        ) AS equipos,
        (SELECT COUNT(*) > 0 
         FROM usuarios_has_piso uh
         WHERE uh.piso_id_piso = p.id_piso AND uh.usuarios_id_usuario = :usuario_id) AS supervisa
    FROM 
        piso p
    LEFT JOIN 
        equipos e ON p.id_piso = e.piso_id_piso
    GROUP BY 
        p.id_piso');

			// Asignar el valor del parámetro
			$query->bindParam(':usuario_id',$usuario, PDO::PARAM_INT);

			// Ejecutar la consulta
			$query->execute();

			// Obtener los resultados
			return $query->fetchAll(PDO::FETCH_ASSOC);



		/* if ($rol === 1) {

			$query = $this->pdo->query('SELECT 
        p.id_piso,
        p.nombre,
        JSON_ARRAYAGG(
            JSON_OBJECT(
                "id_equipo", e.id_equipo,
                "nombre_equipo", e.descripcion,
                "ram", e.ram,
                "procesador", e.procesador,
                "almacenamiento", e.almacenamiento,
                "usuario_equipos", (
                    SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            "id_usuario", ue.usuarios_id_usuario,
                            "status", ue.status_,
							"nombre_user", u.nombre,
							"fecha_inicio", ue.fecha_inicio
                        )
                    )
                    FROM usuario_equipo ue
					JOIN usuarios u ON ue.usuarios_id_usuario = u.id_usuario
                    WHERE ue.equipos_id_equipo = e.id_equipo AND ue.status_ = 1 AND ue.fecha_final <> NULL
                )
            )
			) AS equipos
		FROM 
			piso p
		LEFT JOIN 
			equipos e ON p.id_piso = e.piso_id_piso
		GROUP BY 
			p.id_piso');

		} else {

			$query = $this->pdo->prepare('SELECT 
        p.id_piso,
        p.nombre,
        JSON_ARRAYAGG(
            JSON_OBJECT(
                "id_equipo", e.id_equipo,
                "nombre_equipo", e.descripcion,
                "ram", e.ram,
                "procesador", e.procesador,
                "almacenamiento", e.almacenamiento,
                "usuario_equipos", (
                    SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            "id_usuario", ue.usuarios_id_usuario,
                            "status", ue.status_,
                            "nombre_user", u.nombre,
                            "fecha_inicio", ue.fecha_inicio
                        )
                    )
                    FROM usuario_equipo ue
                    JOIN usuarios u ON ue.usuarios_id_usuario = u.id_usuario
                    WHERE ue.equipos_id_equipo = e.id_equipo AND ue.status_ = 1 AND ue.fecha_final <> NULL
                )
            )
        ) AS equipos,
        (SELECT COUNT(*) > 0 
         FROM usuarios_has_piso uh
         WHERE uh.piso_id_piso = p.id_piso AND uh.usuarios_id_usuario = :usuario_id) AS supervisa
    FROM 
        piso p
    LEFT JOIN 
        equipos e ON p.id_piso = e.piso_id_piso
    GROUP BY 
        p.id_piso');


			$query->bindParam(':usuario_id', $usuario, PDO::PARAM_INT);


			$query->execute();


			return $query->fetchAll(PDO::FETCH_ASSOC);

		}




		return $query->fetchAll(); */
	}

	public function destroy($id)
	{
		$query = $this->pdo->prepare('UPDATE equipos set status_ = 0 where id_equipo =' . $id);
		$query->execute();
	}


	public function ListadoEquipos()
	{
		$query = $this->pdo->query('SELECT * from equipos where status_ = 1');
		return $query->fetchAll();
	}


	public function show($id_equipo)
	{
		$query = $this->pdo->query('SELECT * FROM equipo where id_equipo =' . $id_equipo);
		return $query->fetch();
	}
	public function historial($id_equipo)
	{
		$query = $this->pdo->query('SELECT * FROM usuario_equipo where equipos_id_equipo =' . $id_equipo);
		return $query->fetchAll();
	}

	public function store($usuario)
	{

		$usuario = json_decode($usuario);

		$query = $this->pdo->prepare('INSERT INTO equipos (descripcion, ip_address,mac_address,piso_id_piso) VALUES (:descripcion, :ip_address, :mac_address, :piso_id_piso)');


		$query->bindParam(':descripcion', $usuario->nombres);

		$query->bindParam(':ip_address', $usuario->email);
		$query->bindParam(':mac_address', $usuario->password);
		$query->bindParam(':piso_id_piso', $usuario->selected_rol);

		$query->execute();
		$lastInsertId = $this->pdo->lastInsertId();
		return $this->show($lastInsertId);
	}

}

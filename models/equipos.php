<?php
require_once('db.php');
date_default_timezone_set('America/Bogota');

class Equipo extends Database
{


    public function pisos(){
        $query = $this->pdo->query('SELECT * FROM piso');
		return $query->fetchAll();
    }


    public function masUsed()
    {


        $usuario = $_SESSION['id_usuario'];

        $q = $this->pdo->prepare('SELECT piso_id_piso FROM usuarios_has_piso WHERE usuarios_id_usuario = :usuario');
        $q->execute(['usuario' => $usuario]);
        $pisoIds = $q->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($pisoIds)) {
            $pisoIdsString = implode(',', $pisoIds);



            $query = $this->pdo->query('SELECT e.descripcion, e.piso_id_piso, 
            SUM(TIMESTAMPDIFF(SECOND, ue.fecha_inicio, ue.fecha_final)) AS tiempo_usado
            FROM usuario_equipo ue
            JOIN equipos e ON ue.equipos_id_equipo = e.id_equipo
            WHERE e.piso_id_piso IN (' . $pisoIdsString . ')
            GROUP BY e.id_equipo
            ORDER BY tiempo_usado DESC
            LIMIT 1;');
            return $query->fetch();

        } else {
            return null;
        }

    }


    public function sinUso()
    {

        $usuario = $_SESSION['id_usuario'];

        $q = $this->pdo->prepare('SELECT piso_id_piso FROM usuarios_has_piso WHERE usuarios_id_usuario = :usuario');
        $q->execute(['usuario' => $usuario]);
        $pisoIds = $q->fetchAll(PDO::FETCH_COLUMN);


        if (!empty($pisoIds)) {
            $pisoIdsString = implode(',', $pisoIds);

            $query = $this->pdo->query('SELECT COUNT(*) AS equipos_sin_uso
            FROM equipos e
            WHERE  NOT EXISTS (
                SELECT 1
                FROM usuario_equipo ue
                WHERE ue.equipos_id_equipo = e.id_equipo
            ) AND piso_id_piso IN (' . $pisoIdsString . ');');

        return $query->fetch();

        }else{

            return null;
        }


       


    }



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
                    WHERE ue.equipos_id_equipo = e.id_equipo AND ue.status_ = 1 AND e.status_ = 1 AND ue.fecha_final IS NULL
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
    WHERE  e.status_ = 1
    GROUP BY 
        p.id_piso');

        // Asignar el valor del parámetro
        $query->bindParam(':usuario_id', $usuario, PDO::PARAM_INT);

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
        $usuario = $_SESSION['id_usuario'];

        $q = $this->pdo->prepare('SELECT piso_id_piso FROM usuarios_has_piso WHERE usuarios_id_usuario = :usuario');
        $q->execute(['usuario' => $usuario]);
        $pisoIds = $q->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($pisoIds)) {
            $pisoIdsString = implode(',', $pisoIds);
            $query = $this->pdo->query('SELECT * from equipos where status_ = 1 AND piso_id_piso IN (' . $pisoIdsString . ')');
            return $query->fetchAll();

        } else {
            return [];
        }



    }


    public function show($id_equipo)
    {
        $query = $this->pdo->query('SELECT * FROM equipos where id_equipo =' . $id_equipo);
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

        $query = $this->pdo->prepare('INSERT INTO equipos (descripcion, ip_address,mac_adress,piso_id_piso, ram, procesador, almacenamiento) VALUES (:descripcion, :ip_address, :mac_address, :piso_id_piso, :ram, :procesador, :almacenamiento)');


        $query->bindParam(':descripcion', $usuario->description);

        $query->bindParam(':ip_address', $usuario->ip_address);
        $query->bindParam(':mac_address', $usuario->mac_address);
        $query->bindParam(':piso_id_piso', $usuario->piso);
        $query->bindParam(':ram', $usuario->ram);
        $query->bindParam(':procesador', $usuario->procesador);
        $query->bindParam(':almacenamiento', $usuario->almacenamiento);

        $query->execute();
        $lastInsertId = $this->pdo->lastInsertId();
        return $this->show($lastInsertId);
    }

    public function EditEquipo($equipo){
        $editProduct = json_decode($equipo);
		$updateQuery = $this->pdo->prepare('UPDATE equipos SET descripcion = :descripcion, ip_address = :ip_address, mac_adress = :mac_adress, piso_id_piso = :piso_id_piso, ram = :ram, procesador = :procesador, almacenamiento = :almacenamiento  WHERE id_equipo = :id');

	
		$updateQuery->bindParam(':descripcion', $editProduct->description);
		$updateQuery->bindParam(':ip_address', $editProduct->ip_address);

		$updateQuery->bindParam(':mac_adress', $editProduct->mac_address);
		+
		$updateQuery->bindParam(':piso_id_piso', $editProduct->piso);
		$updateQuery->bindParam(':ram', $editProduct->ram);
		$updateQuery->bindParam(':procesador', $editProduct->procesador); 
		$updateQuery->bindParam(':almacenamiento', $editProduct->almacenamiento); 
		

		$updateQuery->bindParam(':id', $editProduct->id_equipo);

		
		$updateQuery->execute();
    }

}

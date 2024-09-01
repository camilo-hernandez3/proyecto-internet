<?php

class Database
{
	private $servidor = "localhost";
	private $db = "proyecto";
	private $port = 3306;
	private $charset = "utf8";
	private $usuario = "root";
	private $contrasena = "root";
	public $pdo = null;
	private $opciones = [PDO::ATTR_CASE => PDO::CASE_LOWER, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ];
	function __construct()
	{
		$this->pdo = new PDO("mysql:dbname={$this->db};host={$this->servidor};port={$this->port};charset={$this->charset}", $this->usuario, $this->contrasena, $this->opciones);
	}

	public function getServidor():string
	{
		return $this->servidor;
	}

		public function getdb():string
	{
		return $this->db;
	}

		public function getPort():string
	{
		return $this->port;
	}

		public function getCharset():string
	{
		return $this->charset;
	}

		public function getUsuario():string
	{
		return $this->usuario;
	}

}
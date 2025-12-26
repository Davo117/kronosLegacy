<?php

/* Clase encargada de gestionar las conexiones a la base de datos */
  Class Db{

    private $usuario;
    private $pass_word;
    private $servidor;
    private $base_datos;
    private $link;
    private $stmt;
    private $array;

    static $_instance;

  /*La función construct es privada para evitar que el objeto pueda ser creado mediante new*/
    private function __construct(){
      $this->setConexion();
      $this->conectar();
    }

  /*Método para establecer los parámetros de la conexión*/
    private function setConexion(){
      $conf = Conf::getInstance();
      $this->servidor=$conf->getHostDB();
      $this->base_datos=$conf->getDB();
      $this->usuario=$conf->getUserDB();
      $this->pass_word=$conf->getPassDB();
    }

  /*Evitamos el clonaje del objeto. Patrón Singleton*/
    private function __clone(){ }

  /*Función encargada de crear, si es necesario, el objeto. 
    Esta es la función que debemos llamar desde fuera de la clase para instanciar el objeto, 
    y así poder utilizar sus métodos*/
    public static function getInstance(){
      if (!(self::$_instance instanceof self)){
        self::$_instance=new self();
      }
      
      return self::$_instance;
    }

  /*Realiza la conexión a la base de datos.*/
    private function conectar(){
      $this->link=mysqli_connect($this->servidor, $this->usuario, $this->pass_word, $this->base_datos); }

  /*Método para ejecutar una sentencia sql*/
    public function ejecutar($sql){
      $this->stmt = mysqli_query($this->link,$sql);
      return $this->stmt;
    }
    
    public function sistModulo($cdgmodulo){ 
      $sql = "
        SELECT modulo FROM sistmodulo
        WHERE cdgmodulo = '".$cdgmodulo."' AND 
          sttmodulo >= '1'";
          
      $sistModulo = mysqli_query($this->link,$sql);
      if ($sistModulo->num_rows > 0)
      { $regSistModulo = $sistModulo->fetch_object(); 

        $sistModulo_modulo = $regSistModulo->modulo; }
      else
      { $sistModulo_modulo = ''; }

      return $sistModulo_modulo;
    }

    public function sistPermiso($cdgmodulo, $cdgusuario)
    { $sql = "
        SELECT sistpermiso.permiso
        FROM sistpermiso,
          sistperfil,
          rechempleado
        WHERE (sistpermiso.cdgmodulo = '".$cdgmodulo."' AND 
          sistpermiso.cdgperfil = sistperfil.cdgperfil) AND 
         (rechempleado.cdgempleado = '".$cdgusuario."' AND 
          sistperfil.cdgperfil = rechempleado.cdgperfil)";
          
      $sistPermisoSelect = mysqli_query($this->link,$sql);
      if ($sistPermisoSelect->num_rows > 0)
      { $regSistPermiso = $sistPermisoSelect->fetch_object(); 

        $sistModulo_permiso = $regSistPermiso->permiso; }
      else
      { $sistModulo_permiso = ''; }

      return $sistModulo_permiso; 
    }
  }
  
?>
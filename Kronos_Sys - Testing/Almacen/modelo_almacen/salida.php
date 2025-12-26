<?php
class salida
  {
    private $codigo;
    private $nombre;
    private $cantidad;
    private $fecha;
    private $hora;
    private $precio;
    private $hascode;
    private $unidades;
    private $codigoB;
    private $operador;

    public function setNombre($nom)
    {
      $this->nombre=$nom;
    }
    public function getNombre()
    {
      return $this->nombre;
    }
    public function setCodigo($cod)
    {
      $this->codigo=$cod;
    }
    public function getCodigo()
    {
      return $this->codigo;
    }
    public function setCantidad($cant)
    {
      $this->cantidad=$cant;
    }
    public function getCantidad()
    {
      return $this->cantidad;
    }
    public function setFecha($fech)
    {
      $this->fecha=$fech;
    }
    public function getFecha()
    {
      return $this->fecha;
    }
    public function setHora($hr)
    {
      $this->hora=$hr;
    }
    public function getHora()
    {
      return $this->hora;
    }
    public function setPrecio($pr)
    {
      $this->precio=$pr;
    }
    public function getPrecio()
    {
      return $this->precio;
    }
    public function setHascode($hc)
    {
      $this->hascode=$hc;
    }
    public function getHascode()
    {
      return $this->hascode;
    }
    public function setUnidades($un)
    {
      $this->unidades=$un;
    }
    public function getUnidades()
    {
      return $this->unidades;
    }
     public function setCodigoB($codB)
    {
      $this->codigoB=$codB;
    }
    public function getCodigoB()
    {
      return $this->codigoB;
    }
     public function setOperador($ope)
    {
      $this->operador=$ope;
    }
    public function getOperador()
    {
      return $this->operador;
    }
    
  }

  ?>
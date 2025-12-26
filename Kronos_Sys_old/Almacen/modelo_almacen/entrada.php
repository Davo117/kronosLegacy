<?php
class entrada
  {
    private $codigo;
    private $nombre;
    private $cantidad;
    private $fecha;
    private $hora;

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
    
  }

  ?>
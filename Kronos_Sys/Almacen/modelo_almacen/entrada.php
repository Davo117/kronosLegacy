<?php
class entrada
  {
    private $codigo;
    private $nombre;
    private $cantidad;
    private $fecha;
    private $precio;
    private $hascode;
    private $unidades;
    private $unidades2;
    private $codigoB;
    private $lote;
    
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
    public function setUnidades2($un2)
    {
      $this->unidades2=$un2;
    }
    public function getUnidades2()
    {
      return $this->unidades2;
    }

    public function setCodigoB($codB)
    {
      $this->codigoB=$codB;
    }
    public function getCodigoB()
    {
      return $this->codigoB;
    }
    public function setLote($nlot)
    {
      $this->lote=$nlot;
    }
    public function getLote()
    {
      return $this->lote;
    }
  }

  ?>
<?php
namespace MinTra;

use Illuminate\Support\Collection;

class mintra
{
	function __construct()
	{
		$this->cc = new \CURL\cURL();
		$this->cc->setReferer('http://senep.trabajo.gob.pe:8080/');
	}
	function getCode( $dni )
	{
		if ($dni!="" || strlen($dni) == 8)
		{
			$suma = 0;
			$hash = array(5, 4, 3, 2, 7, 6, 5, 4, 3, 2);
			$suma = 5;
			for( $i=2; $i<10; $i++ )
			{
				$suma += ( $dni[$i-2] * $hash[$i] );
			}
			$entero = (int)($suma/11);

			$digito = 11 - ( $suma - $entero*11);

			if ($digito == 10)
			{
				$digito = 0;
			}
			else if ($digito == 11)
			{
				$digito = 1;
			}
			return $digito;
		}
		return "";
	}
	function search( $dni )
	{
		if( strlen($dni)!=8 )
		{
			$response = new Collection(array(
				'success' => false,
				'message' => 'DNI tiene 8 digitos.'
			));
			return $response;
		}
		$url = 'http://senep.trabajo.gob.pe:8080/empleoperu/Ajax.do?method=obtenerCiudadanotoXML&POST_NUMDOCUM='.$dni;
		$response = $this->cc->send( $url );
		if($this->cc->getHttpStatus()==200 && $response!="")
		{
			$xml = new \SimpleXMLElement($response);
			
			$persona = $xml->CIUDADANO;
			if( $dni == (string)$persona->DNI )
			{
				$sexo = ( (string)$persona->SEXO == '1' ) ? "Masculino" : "Femenino";

				$response = new Collection();
				$response->put('success', true);
				$response->put('source', 'trabajo.gob.pe');
				$response->put('dni', (string)$persona->DNI);
				$response->put('verificacion', $this->getCode((string)$persona->DNI));
				$response->put('paterno', (string)$persona->APELLIDOPAT);
				$response->put('materno', (string)$persona->APELLIDOMAT);
				$response->put('nombre', (string)$persona->NOMBRES);
				$response->put('sexo', (string)$sexo);
				$response->put('nacimiento', (string)$persona->FECHANAC);
				$response->put('gvotacion', (string)$persona->POST_GVOTACION);
				return $response;
			}
			else
			{
				$response = new Collection(array(
					'success' => false,
					'message' => 'Datos no encontrados.'
				));
				return $response;
			}
		}
		$response = new Collection(array(
			'success' => false,
			'message' => 'Coneccion fallida.'
		));
		return $response;
	}
}

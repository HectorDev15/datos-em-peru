# datos-em-peru
Datos Principales de Ciudadanos Peruanos adscritos en Essalud y Mintra

# Uso
```sh
<?php
	$essalud = new \EsSalud\EsSalud();
	$mintra = new \MinTra\mintra();
	
	$dni = "xxxxxxxxx";
	
    $search1 = $essalud->search( $dni );
	$search2 = $mintra->search( $dni );
    
    if( $search1->success == true )
	{
		echo "Hola: " . $search1->nombre;
	}
	
	if( $search2->success == true )
	{
		echo "Hola: " . $search2->nombre;
	}
?>
```
#Resultado
```sh
	$search->dni;
	$search->verificacion;
	$search->nombre;
	$search->paterno;
	$search->materno;
	$search->sexo;
	$search->nacimiento;
```

Agradecimiento Josue Mazco https://twitter.com/JossMP777
GNU General Public License 3 (http://www.gnu.org/licenses/)
<?php
/*
    This file is part of SelvaVista©.

    SelvaVista© is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License GPL-3.0-only
    as published by the Free Software Foundation.

    SelvaVista© is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with SelvaVista©.  If not,
   	see <https://www.gnu.org/licenses/gpl-3.0.html>.
 */

  require_once( "includes/SelvaVistaFunctions.inc.php" );
  require( "includes/SelvaVistaConfig.php" );
  require_once( "/etc/GoSelvaVista.inc.php" );
  require_once( "includes/SelvaVistaEnviron.inc.php" );

  if( $_SESSION["LANG"] == 'es' )
    $__LANG__ = 'es';
  else
    $__LANG__ = 'en';

  if( $_SERVER['SERVER_PORT'] != 443 )
  {
    header( "Location: " . SSLURL );
    exit();
  }

  switch( @$_POST['Accion'] )
  {
    case 'Familias':
    case 'Families':
      $_POST['Accion'] = 'AgregarFamilias';
      break;
    case 'Familia':
    case 'Family':
      $_POST['Accion'] = 'EditarFamilia';
      break;
    case 'Nombres':
    case 'Names':
      $_POST['Accion'] = 'AgregarNombres';
      break;
    case 'Nombre':
    case 'Name':
      $_POST['Accion'] = 'EditarNombre';
      break;
    case 'Fotos':
    case 'Photos':
      $_POST['Accion'] = 'AgregarFotos';
      break;
    case 'Foto':
    case 'Photo':
      $_POST['Accion'] = 'EditarFoto';
      break;
    case 'Ubicaciones':
    case 'Locations':
      $_POST['Accion'] = 'AgregarUbicaciones';
      break;
    case 'Ubicación':
    case 'Location':
      $_POST['Accion'] = 'EditarUbicacion';
      break;
    case 'Notas':
    case 'Notes':
      $_POST['Accion'] = 'AgregarNotas';
      break;
    case 'Nota':
    case 'Note':
      $_POST['Accion'] = 'EditarNota';
      break;
    case 'Proveedores':
    case 'Vendors':
      $_POST['Accion'] = 'AgregarProveedores';
      break;
    case 'Proveedor':
    case 'Vendor':
      $_POST['Accion'] = 'EditarProveedor';
      break;
    case 'Nombres Vulgares':
    case 'Common Names':
      $_POST['Accion'] = 'AgregarNombresVulgares';
      break;
    case 'Nombre Vulgar':
    case 'Common Name':
      $_POST['Accion'] = 'EditarNombreVulgar';
      break;
  }

  if( @$_POST['Submit'] && !@$_POST['Accion'] )
  {
    header( "Location: MensajeError.php?Errno=3001" );
             //Faltan Selection en Menu
    exit();
  }

  elseif( @$_POST['AccionNombreID'] == 'EditarNombreID' &&
      !@$_POST['CheckNombreID'] )
  {
    header( "Location: MensajeError.php?Errno=3002" );
             //Faltan CheckNombreID
    exit();
  }
  if( @$_POST['Accion'] == "EditarNombreID" && @$_POST['NukeID'] )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType, 'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3137" );
      exit();
    }

	$NukeQuery = "DELETE a.*, b.*, c.*, d.*, e.* FROM Notas a
	              LEFT JOIN Fotos b on b.NombreID = a.NombreID
				  LEFT JOIN Ubicaciones c on c.NombreID = a.NombreID
                  LEFT JOIN NombresVulgares d on d.NombreID = a.NombreID
                  LEFT JOIN Nombres e on e.NombreID = a.NombreID
				  WHERE a.NombreID = {$_POST['NukeID']}";

    if( !mysqli_query( $Conn, $NukeQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3138&Var={$_POST['NukeID']}" );
               //No Puede Nuke
      exit();
    }
    if( !mysqli_affected_rows( $Conn ) )
    {
	  mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3139" );
               // No se borra registros
      exit();
    }

    LogIT( $Conn, $NukeQuery );
    mysqli_close( $Conn );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
                                        color:white; text-align:center;\">";
    $Block .= "-=&nbsp;NUKE {$_POST['NukeID']}
                <br />";
    if( $__LANG__ == "en" )
      $Block .="&nbsp;CONFIRMED!&nbsp;";
    else
      $Block .="&iexcl;&nbsp;CONFIRMADO&nbsp;!";
    $Block .= "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                  <input  type=\"hidden\" name=\"Accion\"
                          value=\"AgregarNombres\" />
                  <input type=\"submit\" name=\"Submit\"
                         class=\"SubTitleFont\"
                         style=\"font-weight:bold; color:#0000bb;\" ";
    if( $__LANG__ == "en" )
      $Block .=         "value=\"Click HERE to Continue\" />";
    else
      $Block .=         "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }
  // Agregar Nombres STAGE 1
  // Editar Nombre   Stage 2
  elseif( @$_POST['Accion'] == 'AgregarNombres' ||
	  ( @$_POST['AccionNombreID'] == 'EditarNombreID' &&
        !@$_POST['NukeID'] ) )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3003" );
             //No puedo connect
      exit();
    }

    $FamiliasQuery = "select FamiliaID, Familia from Familias";

    if( !$FamiliasRes = mysqli_query( $Conn, $FamiliasQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3004" );
               //No Puede select
      exit();
    }

    if( mysqli_num_rows( $FamiliasRes ) < 1 )
    {
      header( "Location: MensajeError.php?Errno=3005" );
      mysqli_free_result( $FamiliaRes  );
               // No Tiene Familias a Desplegar
      exit();
    }

    $ProveedoresQuery = "select ProveedorID, Nombre from Proveedores";

    if( !$ProveedoresRes = mysqli_query( $Conn, $ProveedoresQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3006" );
               //No Puede select
      exit();
    }

    if( mysqli_num_rows( $ProveedoresRes ) < 1 )
    {
      header( "Location: MensajeError.php?Errno=3007" );
      mysqli_free_result( $FamiliaRes  );
               // No Tiene Proveedores a Desplegar
      exit();
    }

    if( @$_POST['AccionNombreID'] == 'EditarNombreID' )
    {
      $NombresQuery = "select * from Nombres
                         where NombreID = {$_POST['CheckNombreID']}";

      if( !$NombresRes = mysqli_query( $Conn, $NombresQuery ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=3008" );
                 //No Puede select
        exit();
      }

      if( mysqli_num_rows( $NombresRes ) != 1 )
      {
        header( "Location: MensajeError.php?Errno=3009" );
        mysqli_free_result( $FamiliaRes  );
                 // No Tiene Nombres a Desplegar
        exit();
      }

      $NombreRec = mysqli_fetch_array( $NombresRes );
    }

    $Block = "<p class=\"SubTitleFont\" style=\"text-align:center;
                                                color:white;\">";
    if( @$_POST['Accion'] == 'AgregarNombres' )
	  if( $__LANG__ == 'en' )
        $Block .="Add a New Name to the Catalog";
	  else
        $Block .="Agregar un Nombre Nuevo al Catálogo";
    else
	  if( $__LANG__ == 'en' )
        $Block .="Editing a Name in the Catalogo: {$NombreRec['NombreID']} -
                                      {$NombreRec['Nombre']}";
	  else
        $Block .="Editar un Nombre en el Catálogo: {$NombreRec['NombreID']} -
                                      {$NombreRec['Nombre']}";
    $Block .="</p>
            <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
			  <p class=\"LargeTextFont\" style=\"color:white;\">";
    if( $__LANG__ == 'en' )
      $Block .="<em>*</em>&nbspName&nbsp;";
	else
      $Block .="<em>*</em>&nbspNombre&nbsp;";
    $Block .=  "<input type=\"text\" name=\"Nombre\" size=\"60\"
                       maxlength=\"75\" ";
    if( @$_POST['Accion'] == 'AgregarNombres' )
      $Block .=                         " \>";
    else
      $Block .=       "value=\"{$NombreRec['Nombre']}\" \>";

    $Block .="</p>
			  <p class=\"LargeTextFont\" style=\"color:white;\">";
    if( $__LANG__ == 'en' )
      $Block .="<em>*</em>&nbsp;Family&nbsp;";
	else
      $Block .="<em>*</em>&nbsp;Familia&nbsp;";
    $Block .=  "<select name=\"FamiliaID\" size=\"1\">
				  <option value=\"1\">";
    if( $__LANG__ == 'en' )
      $Block .=    "Select a Family";
    else
      $Block .=    "Selecciona una Familia";
    $Block .=    "</option>";
    while( $FamiliaRec = mysqli_fetch_array( $FamiliasRes ) )
    {
      $Block .=  "<option value=\"{$FamiliaRec['FamiliaID']}\" ";
      if( @$_POST['AccionNombreID'] == 'EditarNombreID' )
        if( $NombreRec['FamiliaID'] == $FamiliaRec['FamiliaID'] )
          $Block .=      "selected = \"selected\" ";
      $Block .=    ">{$FamiliaRec['Familia']}
                  </option>";
    }
    $Block .=  "</select>";
	if( $__LANG__ == 'en' )
      $Block .="&nbsp;&nbsp;<em>*</em>&nbsp;Vendor&nbsp;";
	else
      $Block .="&nbsp;&nbsp;<em>*</em>&nbsp;Proveedor&nbsp;";
    $Block .=  "<select name=\"ProveedorID\" size=\"1\">
				  <option value=\"1\">";
    if( $__LANG__ == 'en' )
      $Block .=    "Select a Vendor";
	else
      $Block .=    "Selecciona un Proveedor";
    $Block .=    "</option>";
    while( $ProveedorRec = mysqli_fetch_array( $ProveedoresRes ) )
    {
      $Block .=  "<option value=\"{$ProveedorRec['ProveedorID']}\" ";
      if( @$_POST['AccionNombreID'] == 'EditarNombreID' )
        if( $NombreRec['ProveedorID'] == $ProveedorRec['ProveedorID'] )
          $Block .=      "selected = \"selected\" ";
      $Block .=    ">{$ProveedorRec['Nombre']}
                  </option>";
    }
    $Block .=  "</select>
              </p>
			  <p class=\"LargeTextFont\" style=\"color:white;\">";
    if( $__LANG__ == 'en' )
      $Block .="<em>*</em>&nbspDate (yyyy-mm-dd)&nbsp;";
	else
      $Block .="<em>*</em>&nbspFecha (yyyy-mm-dd)&nbsp;";
    $Block .=  "<input type=\"text\" name=\"Fecha\"
                       size=\"10\" maxlength=\"10\" ";
    $FechaAhorra = date( 'Y-m-d' );
    if( @$_POST['Accion'] == 'AgregarNombres' )
      $Block .=       "value=\"{$FechaAhorra}\"> ";
    else
      $Block .=       "value=\"{$NombreRec['Fecha']}\"> ";

	if( $__LANG__ == 'en' )
      $Block .="&nbsp;&nbsp;<em>*</em>&nbsp;Price&nbsp;";
	else
      $Block .="&nbsp;&nbsp;<em>*</em>&nbsp;Precio&nbsp;";
    $Block .=       "<input type=\"text\" name=\"Precio\" ";

    if( @$_POST['AccionNombreID'] == 'EditarNombreID' )
      $Block .=       "value=\"{$NombreRec['Precio']}\" ";
    $Block .=         "size=\"8\" maxlength=\"8\" />

                    &nbsp;&nbsp;<input type=\"checkbox\" name=\"Inactivo\" ";
    if( @$_POST['AccionNombreID'] == 'EditarNombreID' && $NombreRec['Inactivo'] )
      $Block .=      "checked=\"checked\" ";
	if( $__LANG__ == 'en' )
      $Block .=        "/>Inactive";
	else
      $Block .=        "/>Inactivo";
    $Block .= "</p>";

    if( @$_POST['Accion'] == 'AgregarNombres' )
	{
      $Block .=
             "<p class=\"LargeTextFont\" style=\"text-align:center;\">
                <br />
                <input type=\"submit\" name=\"SubmitNombreNuevo\" ";
      if( $__LANG__ == 'en' )
        $Block .=     "value=\"A P P L Y\" class=\"LargeTextFont\" />";
	  else
        $Block .=     "value=\"A P L I C A R\" class=\"LargeTextFont\" />";
	$Block .=
             "</p>";
	}
    else
	{
      $Block .=
              "<p class=\"LargeTextFont\" style=\"color:white;\">
				<input type=\"checkbox\" name=\"NukeID\"
                       value=\"{$_POST['CheckNombreID']}\"
					   class=\"LargeTextFont\">";
      if( $__LANG__ == 'en' )
        $Block .="NUKE NameID {$_POST['CheckNombreID']} and it's Notes,
				  Locations, Photos and Common Names irrevocably
                  from all of the Catalogs";
      else
        $Block .="NUKE NombreID {$_POST['CheckNombreID']} y sus Notas,
				  Ubicaciones, Fotos y Nombres Vulgares irrevocablemente
                  de todos los Catálogos";
      $Block .=
             " </p>
              <p class=\"LargeTextFont\" style=\"text-align:center;\">
                <br />
                <input type=\"hidden\" name=\"CheckNombreID\"
                       value=\"{$_POST['CheckNombreID']}\" />
                <input type=\"hidden\" name=\"Accion\"
                       value=\"EditarNombreID\" />
                <input type=\"submit\" name=\"Submit\" ";
      if( $__LANG__ == 'en' )
        $Block .=     "value=\"A P P L Y\" class=\"LargeTextFont\" />";
	  else
        $Block .=     "value=\"A P L I C A R\" class=\"LargeTextFont\" />";
	  $Block .=
		     "</p>";
	}
    $Block .=
           "</form>
              <p style=\"text-align:center; color:white;\">";
    if( $__LANG__ == 'en' )
      $Block .="<em>*</em> Mandatory Field";
	else
      $Block .="<em>*</em> Campos Obligatorios";
	$Block .="</p>";
    mysqli_close( $Conn );
    mysqli_free_result( $FamiliasRes );
    mysqli_free_result( $ProveedoresRes );
  }

  // Editar Nombre STAGE 1
  // Borrar Nombre STAGE 1
  elseif( @$_POST['Accion'] == "EditarNombre" )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3010" );
               //No puedo connect
      exit();
    }

    $NombresQuery = "select NombreID, Nombre from Nombres order by Nombre";

    if( !$NombresRes = mysqli_query( $Conn, $NombresQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3011" );
               //No Puede select
      exit();
    }

    if( ( $NumRows = mysqli_num_rows( $NombresRes ) ) < 1 )
    {
      header( "Location: MensajeError.php?Errno=3012" );
      mysqli_free_result( $FamiliasRes );
               // No Tiene Nombres a Desplegar
      exit();
    }

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"font-weight:bold; text-align:center;
                   color:white;\" class=\"SubTitleFont\">";
    if( $__LANG__ == 'en' )
      $Block .=  "Select an Action and a Name to manage";
    else
      $Block .=  "Selecciona un Acción y un Nombre a manejar";
    $Block .=  "</p>
                <table border=\"1\" bgcolor=\"#96cf96;\"
                       style=\"text-align:center; margin:auto;
                               border-style:ridge; border-width:thick;\">
                  <tr style=\"background:#9edcff;\">
                    <th style=\"margin-left:25px; text-align:center;\">";
    if( $__LANG__ == 'en' )
      $Block .=       "Name";
    else
      $Block .=       "Nombre";
    $Block .=      "</th>
                  </tr>";
    $LineCount = 0;

    while( $NombreRec = mysqli_fetch_array( $NombresRes ) )
    {
      if( $NombreRec['NombreID'] == 1 )
        continue;
      $Block .=  "<tr>
                    <td style=\"text-align:left;\">
                      <input type=\"radio\" name=\"CheckNombreID\"
                             value=\"{$NombreRec['NombreID']}\" />
                      &nbsp;{$NombreRec['Nombre']}&nbsp;
                    </td>
                  </tr>";
      $LineCount++;
      if( !( $LineCount % LineasEnSeccion )  || $NumRows == $LineCount + 1 )
      {
        $Block .= "<tr style=\"background:#9edcff;\">
                    <th colspan=\"6\" style=\"white-space:nowrap;\">";
      if( $__LANG__ == 'en' )
        $Block .=    "&nbsp;&nbsp;&nbsp;&nbsp;Edit";
      else
        $Block .=    "&nbsp;&nbsp;&nbsp;&nbsp;Editar";
      $Block .=      "<input type=\"radio\" name=\"AccionNombreID\"
                             value=\"EditarNombreID\"
                             checked=\"checked\" />
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type=\"submit\" name=\"ManNombres\"";
      if( $__LANG__ == 'en' )
        $Block .=           "value=\"A P P L Y\" />";
      else
        $Block .=           "value=\"A P L I C A R\" />";
      $Block .=      "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type=\"radio\" name=\"AccionNombreID\"
                             value=\"BorrarNombreID\" />";
      if( $__LANG__ == 'en' )
        $Block .=    "Delete&nbsp;&nbsp;&nbsp;&nbsp;";
      else
        $Block .=    "Borrar&nbsp;&nbsp;&nbsp;&nbsp;";
      $Block .=    "</th>
                  </tr>";
      }
    }
    $Block .= " </table>
              </form>";
    mysqli_free_result( $NombresRes );
    mysqli_close( $Conn );
  }

  // Agregar Nombre STAGE 2
  // Editar Nombre STAGE 3
  elseif( @$_POST['SubmitNombreNuevo'] ||
          @$_POST['Accion'] == "EditarNombreID" )
  {
    if( !@$_POST['Nombre'] || !@$_POST['Fecha'] || !@$_POST['Precio'] )
    {
      header( "Location: MensajeError.php?Errno=3013" );
               //Falta Campo
      exit();
    }

    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3014" );
      exit();
    }

    $Nombre = htmlspecialchars( $_POST['Nombre'], ENT_QUOTES, "UTF-8" );

    if( @$_POST['FamiliaID'] == 1 )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3015" );
      exit();
    }

    if( @$_POST['ProveedorID'] == 1 )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3016" );
      exit();
    }

    if( !IsValidMySQLDate( $_POST['Fecha'] ) && $_POST['Fecha'] != "0000-00-00" )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3017" );
      exit();
    }
    // Fecha invalida

    if( !IsValidDouble( $_POST['Precio'], 8, $Precision = 2 ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3018" );
      exit();
    }
    // Precio invalido

	if( @$_POST['Inactivo'] )
      $Inactivo = 1;
	else
      $Inactivo = 0;


    if( @$_POST['SubmitNombreNuevo'] )
      $Query = "insert into Nombres values ( NULL, {$_POST['FamiliaID']},
                                                  '{$_POST['Nombre']}',
                                                  '{$_POST['Fecha']}',
                                                   {$_POST['ProveedorID']},
                                                   {$_POST['Precio']},
                                                   $Inactivo )";
    else
      $Query =  "update Nombres set FamiliaID   =  {$_POST['FamiliaID']},
                                    Nombre      = '{$_POST['Nombre']}',
                                    Fecha       = '{$_POST['Fecha']}',
                                    ProveedorID =  {$_POST['ProveedorID']},
                                    Precio      =  {$_POST['Precio']},
                                    Inactivo    =  $Inactivo
                 where NombreID = {$_POST['CheckNombreID']}";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3019" );
               //No Puede insert o update into Nombres
      exit();
    }

    LogIT( $Conn, $Query );
    mysqli_close( $Conn );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
                                        color:white; text-align:center;\">";
    if( @$_POST['SubmitNombreNuevo'] )
      if( $__LANG__ == "en" )
        $Block .="-=&nbsp;Adding a New Name to the Catalog";
      else
        $Block .="-=&nbsp;Agregado al Catálogo, Nombre Nuevo";
    else
      if( $__LANG__ == "en" )
        $Block .="-=&nbsp;Editing a Name in the Catalog";
      else
        $Block .="-=&nbsp;Editar al Catálogo, Nombre";
    $Block .=    "<br />
                  {$_POST['Nombre']}
                  <br />";
    if( $__LANG__ == "en" )
      $Block .=  "&nbsp;CONFIRMED&nbsp;!";
    else
      $Block .=  "&iexcl;&nbsp;CONFIRMADO&nbsp;!";
    $Block .=    "<br /><br />
                </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                  <input  type=\"hidden\" name=\"Accion\"
                          value=\"AgregarNombres\" />
                  <input type=\"submit\" name=\"Submit\"
                         class=\"SubTitleFont\"
                         style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == "en" )
      $Block .=         "value=\"Click HERE to Continue\" />";
    else
      $Block .=         "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }
  // Borrar Nombre STAGE 2
  elseif( @$_POST['AccionNombreID'] == 'BorrarNombreID' )
  {                            //BORRAR Notas
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3020" );
               //No puedo connect
      exit();
    }

    $Query = "delete from Nombres
              where NombreID = {$_POST['CheckNombreID']}";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3021&Var={$_POST['CheckNombreID']}" );
               //No Puede Borrar Nombre
      exit();
    }

    LogIT( $Conn, $Query );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
                 color:white; text-align:center;\">";
    if( $__LANG__ == "en" )
      $Block .="-=&nbsp;Deleting NameID
                      {$_POST['CheckNombreID']}&nbsp;=-
                <br />
                &iexcl;&nbsp;CONFIRMED&nbsp;!";
    else
      $Block .="-=&nbsp;Borrado de NombreID
                      {$_POST['CheckNombreID']}&nbsp;=-
                <br />
                &iexcl;&nbsp;CONFIRMADO&nbsp;!";
    $Block .=  "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                   <input  type=\"hidden\" name=\"Accion\"
                           value=\"EditarNombres\" />
                   <input type=\"submit\" name=\"Submit\"
                          class=\"SubTitleFont\"
                          style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == "en" )
      $Block .=          "value=\"Click HERE to Continue\" />";
    else
      $Block .=          "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }
  elseif( @$_POST['ManNotas']
      && !@$_POST['AccionNotaID'] == 'BorrarNotaID'
      && !@$_POST['AccionNotaID'] == 'EditarNotaID' )
  {
    header( "Location: MensajeError.php?Errno=3022" );
             //Faltan Acción
    exit();
  }

  elseif( @$_POST['AccionNotaID'] == 'BorrarNotaID'
      && !@$_POST['CheckNotaID'] )
  {
    header( "Location: MensajeError.php?Errno=3023" );
             //Faltan NotaID
    exit();
  }

  elseif( @$_POST['AccionNotaID'] == 'EditarNotaID'
      && !@$_POST['CheckNotaID'] )
  {
    header( "Location: MensajeError.php?Errno=3024" );
             //Faltan NotaID
    exit();
  }

  // Agregar Ubicación STAGE 1
  // Editar  Ubicación STAGE 1
  // Borrar  Ubicación STAGE 1
  elseif( @$_POST['Accion'] == "AgregarUbicaciones"  ||
          @$_POST['Accion'] == "EditarUbicacion" )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3025" );
             //No puedo connect
      exit();
    }

    $NombresQuery = "Select NombreID, Nombre from Nombres
                     where NombreID > 1 order by Nombre";

    if( !$NombresRes = mysqli_query( $Conn, $NombresQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3026" );
               //No Puede select
      exit();
    }

    if( mysqli_num_rows( $NombresRes ) < 1 )
    {
      mysqli_close( $Conn );
      mysqli_free_result( $NombresRes );
      header( "Location: MensajeError.php?Errno=3027" );
               // No Tiene Nombres a desplegar
      exit();
    }

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center; color:white;\"
                   class=\"SubTitleFont\">";
    if( @$_POST['Accion'] == "EditarUbicacion" )
      if( $__LANG__ == 'en' )
        $Block .=  "Edit/Delete a Location in the Catalog";
      else
        $Block .=  "Editar/Borrar una Ubicación en el Catálogo";
    else
      if( $__LANG__ == 'en' )
        $Block .=  "Add a New Location to the Catalog";
      else
        $Block .=  "Agregar una Ubicación Nueva al Catálogo";
    $Block .= " </p>";

    $Block .=  "<p class=\"LargeTextFont\" style=\"color:white;\">";
    if( $__LANG__ == 'en' )
      $Block .=  "<em>*</em> Plant&nbsp;&nbsp;";
    else
      $Block .=  "<em>*</em> Planta&nbsp;&nbsp;";
    $Block .=    "<select name=\"UbicacionNombreInfo\" size=\"1\"
                          class=\"LargeTextFont\">
                    <option value=\"1\"> ";

    if( @$_POST['Accion'] == "AgregarUbicaciones" )
      if( $__LANG__ == 'en' )
        $Block .=    "Select a plant for which you wish to add a Location";
      else
        $Block .=    "Selecciona una planta para la que deseas
                      agregar Ubicaciones";
    else
      if( $__LANG__ == 'en' )
        $Block .=    "Select a plant whose Location you wish to manage";
      else
        $Block .=    "Selecciona una planta cuyas Ubicación quieres manejar";
    $Block .=      "</option>";

    $NombreID = @$_POST['UbicacionNombreID'];

    while( $NombreRec = mysqli_fetch_array( $NombresRes ) )
    {
      $Block .=  "<option value=\"{$NombreRec['NombreID']}-{$NombreRec['Nombre']}\" ";
      if( @$NombreID == $NombreRec['NombreID'] )
        $Block .=  "selected=\"selected\" ";
      $Block .=    " >{$NombreRec['Nombre']}
                  </option>";
    }
    mysqli_free_result( $NombresRes );

    $Block .=    "</select>
                </p>";

    if( @$_POST['Accion'] == "AgregarUbicaciones" )
    {
      $Block .=
               "<p class=\"LargeTextFont\" style=\"color:white;\">";
      if( $__LANG__ == "en" )
        $Block .="<em>*</em> Location&nbsp;&nbsp;";
      else
        $Block .="<em>*</em> Ubicacion&nbsp;&nbsp;";
      $Block .=    "<input type=\"text\" name=\"Ubicacion\"
                         size=\"18\" maxlength=\"18\" />
                </p>

				<p class=\"LargeTextFont\" style=\"text-align:center;
                                           color:white;\">
                  <br />
                  <input type=\"submit\" name=\"SubmitUbicacionNueva\" ";
      if( $__LANG__ == "en" )
        $Block .=       "value=\"A P P L Y\" class=\"LargeTextFont\" />";
      else
        $Block .=       "value=\"A P L I C A R\" class=\"LargeTextFont\" />";
      $Block .="</p>";
    }
    else
    {
      $Block .="<p class=\"LargeTextFont\" style=\"text-align:center;\">
                  <br />
                  <input type=\"hidden\" name=\"Accion\"
                         value=\"EditarUbicacionID\" />
                  <input type=\"submit\" name=\"Submit\" ";
      if( $__LANG__ == "en" )
        $Block .=       "value=\"A P P L Y\" class=\"LargeTextFont\" />";
      else
        $Block .=       "value=\"A P L I C A R\" class=\"LargeTextFont\" />";
      $Block .="</p>";
    }
    $Block .="</form>
                <p style=\"text-align:center; color:white;\">";
    if( $__LANG__ == "en" )
      $Block .=  "<em>*</em> Mandatory Field";
    else
      $Block .=  "<em>*</em> Campos Obligatorios";
    $Block .=  "</p>";
    mysqli_close( $Conn );
  }

  // Agregar Nota STAGE 1
  // Editar  Nota STAGE 1
  // Borrar  Nota STAGE 1
  elseif( @$_POST['Accion'] == "AgregarNotas"  ||
          @$_POST['Accion'] == "EditarNota" )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3028" );
             //No puedo connect
      exit();
    }

    $NombresQuery = "Select NombreID, Nombre from Nombres
                     where NombreID > 1 order by Nombre";

    if( !$NombresRes = mysqli_query( $Conn, $NombresQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3029" );
               //No Puede select
      exit();
    }

    if( mysqli_num_rows( $NombresRes ) < 1 )
    {
      mysqli_close( $Conn );
      mysqli_free_result( $NombresRes );
      header( "Location: MensajeError.php?Errno=3030" );
               // No Tiene Nombres a desplegar
      exit();
    }

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
				<p style=\"text-align:center; color:white;\"
                   class=\"SubTitleFont\">";
    if( @$_POST['Accion'] == "EditarNota" )
	  if( $__LANG__ == 'en' )
        $Block .=  "Edit/Delete a Note in the Catalog";
	  else
        $Block .=  "Editar/Borrar una Nota en el Catálogo";
    else
	  if( $__LANG__ == 'en' )
        $Block .=  "Add a New Note to the Catalog";
	  else
        $Block .=  "Agregar una Nota Nueva al Catálogo";
    $Block .= " </p>";

    $Block .=  "<p style=\"color:white;\" class=\"LargeTextFont\">";
	if( $__LANG__ == 'en' )
      $Block .=  "<em>*</em> Plant&nbsp;&nbsp;";
	else
      $Block .=  "<em>*</em> Planta&nbsp;&nbsp;";
    $Block .=    "<select name=\"NotaNombreInfo\" size=\"1\"
                          class=\"LargeTextFont\">
                    <option value=\"1\"> ";

    if( @$_POST['Accion'] == "AgregarNotas" )
	  if( $__LANG__ == 'en' )
        $Block .=    "Select a plant for which you want to add a New Note";
	  else
        $Block .=    "Selecciona una planta para la que deseas agregar notas";
    else
	  if( $__LANG__ == 'en' )
        $Block .=    "Select a plant whose Note you wish to Edit or Delete";
	  else
        $Block .=    "Selecciona una planta cuyas Nota quieres manejar";
    $Block .=      "</option>";

    $NombreID = @$_POST['NotaNombreID'];

    while( $NombreRec = mysqli_fetch_array( $NombresRes ) )
    {
      $Block .=  "<option value=\"{$NombreRec['NombreID']}-{$NombreRec['Nombre']}\" ";
      if( @$NombreID == $NombreRec['NombreID'] )
        $Block .=    "selected=\"selected\" ";
      $Block .=    " >{$NombreRec['Nombre']}
                    </option>";
    }
    mysqli_free_result( $NombresRes );

    $Block .=    "</select>
                </p>";

    if( @$_POST['Accion'] == "AgregarNotas" )
	{
      $Block .=
               "<p style=\"color:white;\" class=\"LargeTextFont\">";
	  if( $__LANG__ == 'en' )
        $Block .="<em>*</em> Note&nbsp;&nbsp;";
	  else
        $Block .="<em>*</em> Nota&nbsp;&nbsp;";
      $Block .=  "<input type=\"text\" name=\"Nota\"
                         size=\"75\" maxlength=\"250\" />
                </p>

                <p class=\"LargeTextFont\" style=\"text-align:center;\">
                  <br />
                  <input type=\"submit\" name=\"SubmitNotaNueva\" ";
	  if( $__LANG__ == 'en' )
        $Block .=  "value=\"A P P L Y\" class=\"LargeTextFont\" />";
	  else
        $Block .=  "value=\"A P L I C A R\" class=\"LargeTextFont\" />";
      $Block .="</p>";
	}
    else
	{
      $Block .="<p class=\"LargeTextFont\" style=\"text-align:center;\">
                  <br />
                  <input type=\"hidden\" name=\"Accion\"
                         value=\"EditarNotaID\" />
                  <input type=\"submit\" name=\"Submit\" ";
	  if( $__LANG__ == 'en' )
        $Block .=       "value=\"A P P L Y\" class=\"LargeTextFont\" />";
	  else
        $Block .=       "value=\"A P L I C A R\" class=\"LargeTextFont\" />";
      $Block .="</p>";
	}
    $Block .="</form>
                <p style=\"text-align:center; color:white;\">";
	if( $__LANG__ == 'en' )
      $Block .=  "<em>*</em> Mandatory Field";
	else
      $Block .=  "<em>*</em> Campos Obligatorios";
    $Block .=  "</p>";
    mysqli_close( $Conn );
  }

  // Agregar Ubicación STAGE 2
  elseif( @$_POST['SubmitUbicacionNueva'] )
  {
    if( !@$_POST['Ubicacion'] )
    {
      header( "Location: MensajeError.php?Errno=3031" );
               //Falta Campo
      exit();
    }

    $Ubicacion = htmlspecialchars( $_POST['Ubicacion'], ENT_QUOTES, "UTF-8" );
    $NombreInfo = explode( '-', $_POST['UbicacionNombreInfo'] );

	if( $NombreInfo['0'] < 2 )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3144" );
      exit();
    } //Debe elegir una Planta para la Ubicación


    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3032" );
      exit();
    }

    $Query = "insert into Ubicaciones values ( NULL, {$NombreInfo['0']},
                                                    '{$Ubicacion}' )";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3033" );
               //No Puede insert o update into Ubicaciones
      exit();
    }

    LogIT( $Conn, $Query );
    mysqli_close( $Conn );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
				 color:white; text-align:center;\">";
    if( $__LANG__ == 'en' )
      $Block .="-=&nbsp;Adding a New Location to the Catalog for
                <br />
                {$NombreInfo['0']} - {$NombreInfo['1']}&nbsp;=-
                <br />
                &nbsp;CONFIRMED&nbsp;!";
	else
      $Block .="-=&nbsp;Agregado al Catálogo, Ubicación Nueva para
                <br />
                {$NombreInfo['0']} - {$NombreInfo['1']}&nbsp;=-
                <br />
                &iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=  "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                  <input  type=\"hidden\" name=\"Accion\"
                          value=\"AgregarUbicaciones\" />
                  <input type=\"hidden\" name=\"UbicacionNombreID\"
                         value=\"{$NombreInfo['0']}\" />";
    $Block .= "   <input type=\"submit\" name=\"Submit\"
                         class=\"SubTitleFont\"
						 style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=         "value=\"Click HERE to Continue\" />";
	else
      $Block .=         "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }

  // Agregar Nota STAGE 2
  elseif( @$_POST['SubmitNotaNueva'] )
  {
    if( !@$_POST['Nota'] )
    {
      header( "Location: MensajeError.php?Errno=3034" );
               //Falta Campo
      exit();
    }

    $Nota = htmlspecialchars( $_POST['Nota'], ENT_QUOTES, "UTF-8" );
    $NombreInfo = explode( '-', $_POST['NotaNombreInfo'] );

	if( $NombreInfo['0'] < 2 )
    {
      header( "Location: MensajeError.php?Errno=3142" );
               //Falta Campo
      exit();
    }

    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3035" );
      exit();
    }

    $Query = "insert into Notas values ( NULL, {$NombreInfo['0']}, '{$Nota}' )";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3036" );
               //No Puede insert o update into Notas
      exit();
    }

    LogIT( $Conn, $Query );
    mysqli_close( $Conn );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
                 color:white; text-align:center;\">";
    if( $__LANG__ == 'en' )
      $Block .="-=&nbsp;Adding a New Note to the Catalogo for
                <br />
                {$NombreInfo['0']} - {$NombreInfo['1']}&nbsp;=-
                <br />
                &nbsp;CONFIRMED&nbsp;!";
    else
      $Block .="-=&nbsp;Agregado al Catálogo, Nota Nueva para
                <br />
                {$NombreInfo['0']} - {$NombreInfo['1']}&nbsp;=-
                <br />
                &iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=  "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                  <input  type=\"hidden\" name=\"Accion\"
                          value=\"AgregarNotas\" />
                  <input type=\"hidden\" name=\"NotaNombreID\"
                         value=\"{$NombreInfo['0']}\" />
                  <input type=\"submit\" name=\"Submit\"
                         class=\"SubTitleFont\"
						 style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=         "value=\"Click HERE to Continue\" />";
	else
      $Block .=         "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }

  // Editar Ubicación STAGE 2
  // Borrar Ubicación STAGE 2
  elseif( @$_POST['Accion'] == "EditarUbicacionID" )
  {
    $NombreInfo = explode( '-', $_POST['UbicacionNombreInfo']);

    if( $NombreInfo['0'] <2 )
    {
      header( "Location: MensajeError.php?Errno=3148" );
      exit();
    } // Debe elegir una Planta

    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3037" );
               //No puedo connect
      exit();
    }

    $UbicacionesQuery = "select UbiID, Ubicacion from Ubicaciones
                    where NombreID = {$NombreInfo['0']}";

    if( !$UbicacionesQueryRes = mysqli_query( $Conn, $UbicacionesQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3038" );
               //No Puede select
      exit();
    }

    if( ( $NumRows = mysqli_num_rows( $UbicacionesQueryRes ) ) < 1 )
      $NoUbicaciones = 'Y';
               // No Tiene Ubicaciones a Desplegar

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"font-weight:bold; text-align:center; color:white;\"
                   class=\"SubTitleFont\">";
    if( $__LANG__ == "en" )
      $Block .=  "Select an Action and a Location to manage";
    else
      $Block .=  "Selecciona un Acción y una Ubicación a manejar";
    $Block .=    "<br />
                  {$NombreInfo['0']} - {$NombreInfo['1']}
                </p>
                <table border=\"1\"  bgcolor=\"#96cf96;\"
                       style=\"text-align:center; margin:auto;
                       border-style:ridge; border-width:thick;\"
                       class=\"LargeTextFont\">
                  <tr style=\"background:#9edcff;\">
                    <th style=\"text-align:left; padding-left:3%\">";
    if( $__LANG__ == "en" )
      $Block .=       "LocationID - Location";
    else
      $Block .=       "UbicacionID - Ubicacion";
    $Block .=      "</th>
                  </tr>";
    $LineCount = 0;

    if( @$NoUbicaciones == 'Y' )
      $Block .=  "<tr>
                    <td style=\"text-align:left;\">
                      No hay Ubicaciones a manejar
                    </td>
                  </tr>";
    else
    {
      while( $UbicacionRec = mysqli_fetch_array( $UbicacionesQueryRes ) )
      {
        if( $UbicacionRec['UbiID'] == 1 )
          continue;
        $Block .="<tr>
                    <td style=\"text-align:left;\">
                      <input type=\"radio\" name=\"CheckUbicacionID\"
                             value=\"{$UbicacionRec['UbiID']}\" />
                      &nbsp;{$UbicacionRec['UbiID']} - {$UbicacionRec['Ubicacion']}&nbsp;
                    </td>
                  </tr>";
        $LineCount++;
        if( !( $LineCount % LineasEnSeccion )  || $NumRows == $LineCount )
        {
          $Block .=
                 "<tr style=\"background:#9edcff;\">
					<th colspan=\"6\" style=\"white-space:nowrap;\">";
          if( $__LANG__ == 'en' )
            $Block .="&nbsp;&nbsp;&nbsp;&nbsp;Edit";
		  else
            $Block .="&nbsp;&nbsp;&nbsp;&nbsp;Editar";
          $Block .=  "<input type=\"radio\" name=\"AccionUbicacionID\"
                             value=\"EditarUbicacionID\"
                             checked=\"checked\" />
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					  <input type=\"submit\" name=\"ManUbicaciones\"
                             style=\"font-weight:bold;\" ";
          if( $__LANG__ == 'en' )
            $Block .=       "value=\"A P P L Y\" />";
		  else
            $Block .=       "value=\"A P L I C A R\" />";
          $Block .=  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type=\"radio\" name=\"AccionUbicacionID\"
							 value=\"BorrarUbicacionID\" />";
          if( $__LANG__ == 'en' )
            $Block .="Delete&nbsp;&nbsp;&nbsp;&nbsp;";
		  else
            $Block .="Borrar&nbsp;&nbsp;&nbsp;&nbsp;";
          $Block .="</th>
                  </tr>";
        }
      }
      $Block .= " </table>
                </form>";
      mysqli_free_result( $UbicacionesQueryRes );
      mysqli_close( $Conn );
    }
  }

  //Editar Ubicación STAGE 3
  elseif( @$_POST['AccionUbicacionID'] == 'EditarUbicacionID' )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3039" );
      exit();
      //No puede connect
    }

    $UbicacionesQuery = "select Ubicacion from Ubicaciones
                    where UbiID = {$_POST['CheckUbicacionID']}";

    if( !$UbicacionesQueryRes = mysqli_query( $Conn, $UbicacionesQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3040" );
               //No Puede select from Ubicaciones
      exit();
    }

    if( mysqli_num_rows( $UbicacionesQueryRes ) != 1 )
    {
      header( "Location: MensajeError.php?Errno=3041&Var={$_POST['CheckUbicacionID']}" );
      mysqli_free_result( $UbicacionesQueryRes );
               // La Ubicación que quieres no existe
      exit();
    }

    $UbicacionQueryRec = mysqli_fetch_array( $UbicacionesQueryRes );

    mysqli_free_result( $UbicacionesQueryRes );

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
				<p style=\"text-align:center; color:white;\"
                   class=\"SubTitleFont\">";
    if( $__LANG__ == 'en' )
      $Block .=  "Edit a Location in the Catálogo";
	else
      $Block .=  "Editar al Catálogo; Ubicación";
    $Block .=  "</p>

				<p style=\"text-align:center; color:white;\"
                   class=\"LargeTextFont\">
                  <br />";
	if( $__LANG__== 'en' )
      $Block .=  "Location&nbsp;&nbsp;";
	else
      $Block .=  "Ubicacion&nbsp;&nbsp;";
    $Block .=    "<input type=\"text\" name=\"Ubicacion\"
                         size=\"75\" maxlength=\"256\"
                         value=\"{$UbicacionQueryRec['Ubicacion']}\" />

                <p style=\"text-align:center;\">
                  <input  type=\"hidden\" name=\"Accion\"
                          value=\"GuardaUbicacion\" />
                  <input  type=\"hidden\" name=\"CheckUbicacionID\"
                          value=\"{$_POST['CheckUbicacionID']}\" />
                  <input type=\"submit\" name=\"Submit\"
                         class=\"LargeTextFont\" ";
    if( $__LANG__ == 'en' )
      $Block .=         "value=\"A P P L Y\" />";
	else
      $Block .=         "value=\"A P L I C A R\" />";
    $Block .=  "</p>
              </form>";
  }

  //Editar Ubicación STAGE 4
  elseif( @$_POST['Accion'] == "GuardaUbicacion" )
  {
    if( !$_POST['CheckUbicacionID'] )
    {
      header( "Location: MensajeError.php?Errno=3042" );
               //No hay CheckUbicacionID a guardar
      exit();
    }

    $Ubicacion = htmlspecialchars( $_POST['Ubicacion'], ENT_QUOTES, "UTF-8" );
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3043" );
               //No puedo connect
      exit();
    }

    $Query = "update Ubicaciones set Ubicacion = '{$Ubicacion}'
                where UbiID =  {$_POST['CheckUbicacionID']}";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3044&Var={$_POST['CheckUbicacionID']}" );
               //No Puede Editar Ubicacion
      exit();
    }

    LogIT( $Conn, $Query );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
				 color:white; text-align:center;\">";
    if( $__LANG__ == 'en' )
      $Block .="-=&nbsp;Editing LocationID
                      {$_POST['CheckUbicacionID']}&nbsp;=-
                <br />
                &nbsp;CONFIRMED&nbsp;!";
	else
      $Block .="-=&nbsp;Editado de UbicacionID
                      {$_POST['CheckUbicacionID']}&nbsp;=-
                <br />
                &iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=  "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                   <input  type=\"hidden\" name=\"Accion\"
                           value=\"EditarUbicaciones\" />
                   <input type=\"submit\" name=\"Submit\"
                          class=\"SubTitleFont\"
						  style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=          "value=\"Click HERE to Continue\" />";
	else
      $Block .=          "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }

  //Borrar Ubicación STAGE 3
  elseif( @$_POST['AccionUbicacionID'] == 'BorrarUbicacionID' )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3045" );
               //No puedo connect
      exit();
    }

    $Query = "delete from Ubicaciones
              where UbiID = {$_POST['CheckUbicacionID']}";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3046&Var={$_POST['CheckUbicacionID']}" );
               //No Puede Borrar Ubicacion
      exit();
    }

    LogIT( $Conn, $Query );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
				 color:white; text-align:center;\">";
    if( $__LANG__ == 'en' )
      $Block .="-=&nbsp;Deleting of LocationID
                      {$_POST['CheckUbicacionID']}&nbsp;=-
                <br />
                &nbsp;CONFIRMED&nbsp;!";
	else
      $Block .="-=&nbsp;Borrado de UbicacionID
                      {$_POST['CheckUbicacionID']}&nbsp;=-
                <br />
                &iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=  "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                   <input  type=\"hidden\" name=\"Accion\"
                           value=\"EditarUbicaciones\" />
                   <input type=\"submit\" name=\"Submit\"
                          class=\"SubTitleFont\"
						  style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=          "value=\"Click HERE to Continue\" />";
	else
      $Block .=          "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }

  // Editar Nota STAGE 2
  // Borrar Nota STAGE 2
  elseif( @$_POST['Accion'] == "EditarNotaID" )
  {
    $NombreInfo = explode( '-', $_POST['NotaNombreInfo']);

	if( $NombreInfo['0'] < 2 )
    {
      header( "Location: MensajeError.php?Errno=3147" );
      exit();
    } // Debe elegir una Planta

    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3047" );
               //No puedo connect
      exit();
    }

    $NotaQuery = "select NotaID, Nota from Notas
                    where NombreID = {$NombreInfo['0']}";

    if( !$NotasRes = mysqli_query( $Conn, $NotaQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3048" );
               //No Puede select
      exit();
    }

    if( ( $NumRows = mysqli_num_rows( $NotasRes ) ) < 1 )
      $NoNotas = 'Y';
               // No Tiene Notas a Desplegar

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"font-weight:bold; text-align:center; color:white;\"
                   class=\"SubTitleFont\">";
    if( $__LANG__ == 'en' )
      $Block .=  "Select an Action and a Note to manage";
	else
      $Block .=  "Selecciona un Acción y una Nota a manejar";
    $Block .=    "<br />
                  {$NombreInfo['0']} - {$NombreInfo['1']}
                </p>
                <table border=\"1\"  bgcolor=\"#96cf96;\"
                       style=\"text-align:center; margin:auto;
                       border-style:ridge; border-width:thick;\"
                       class=\"LargeTextFont\">
                  <tr style=\"background:#9edcff;\">
                    <th style=\"text-align:left; padding-left:3%;\">";
    if( $__LANG__ == 'en' )
      $Block .=       "NoteID - Note";
	else
      $Block .=       "NotaID - Nota";
    $Block .=      "</th>
                  </tr>";
    $LineCount = 0;

    if( @$NoNotas == 'Y' )
      $Block .=  "<tr>
                    <td style=\"text-align:left;\">
                      No hay Notas a manejar
                    </td>
                  </tr>";
    else
    {
      while( $NotaRec = mysqli_fetch_array( $NotasRes ) )
      {
        if( $NotaRec['NotaID'] == 1 )
          continue;
        $Block .="<tr>
                    <td style=\"text-align:left;\">
                      <input type=\"radio\" name=\"CheckNotaID\"
                             value=\"{$NotaRec['NotaID']}\" />
                      &nbsp;{$NotaRec['NotaID']} - {$NotaRec['Nota']}&nbsp;
                    </td>
                  </tr>";
        $LineCount++;
        if( !( $LineCount % LineasEnSeccion )  || $NumRows == $LineCount )
        {
          $Block .=
                 "<tr style=\"background:#9edcff;\">
					<th colspan=\"6\" style=\"white-space:nowrap;\">";
          if( $__LANG__ == 'en' )
            $Block .="&nbsp;&nbsp;&nbsp;&nbsp;Edit";
		  else
            $Block .="&nbsp;&nbsp;&nbsp;&nbsp;Editar";
          $Block .=  "<input type=\"radio\" name=\"AccionNotaID\"
                             value=\"EditarNotaID\"
                             checked=\"checked\" />
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type=\"submit\" name=\"ManNotas\" ";
          if( $__LANG__ == 'en' )
            $Block .=       "value=\"A P P L Y\" />";
		  else
            $Block .=       "value=\"A P L I C A R\" />";
          $Block .=  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type=\"radio\" name=\"AccionNotaID\"
                             value=\"BorrarNotaID\" />";
          if( $__LANG__ == 'en' )
            $Block .="Delete&nbsp;&nbsp;&nbsp;&nbsp;";
		  else
            $Block .="Borrar&nbsp;&nbsp;&nbsp;&nbsp;";
          $Block .="</th>
                  </tr>";
        }
      }
      $Block .= " </table>
                </form>";
      mysqli_free_result( $NotasRes );
      mysqli_close( $Conn );
    }
  }

  //Editar Nota STAGE 3
  elseif( @$_POST['AccionNotaID'] == 'EditarNotaID' )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3049" );
      exit();
      //No puede connect
    }

    $NotaQuery = "select Nota from Notas
                    where NotaID = {$_POST['CheckNotaID']}";

    if( !$NotaQueryRes = mysqli_query( $Conn, $NotaQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3050" );
               //No Puede select from Notas
      exit();
    }

    if( mysqli_num_rows( $NotaQueryRes ) != 1 )
    {
      header( "Location: MensajeError.php?Errno=3051&Var={$_POST['CheckNotaID']}" );
      mysqli_free_result( $NotaQueryRes );
               // No existe esta Nota
      exit();
    }

    $NotaQueryRec = mysqli_fetch_array( $NotaQueryRes );

    mysqli_free_result( $NotaQueryRes );

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
				<p style=\"text-align:center; color:white;\"
                   class=\"SubTitleFont\">";
    if( $__LANG__ == 'en' )
      $Block .=  "Editing a Note in the Catalog";
	else
      $Block .=  "Editando una Nota en el Catálogo";
    $Block .=  "</p>
				<p style=\"text-align:center; color:white;\"
                   class=\"LargeTextFont\">
                  <br />";
    if( $__LANG__ == 'en' )
      $Block .=  "Note&nbsp;&nbsp;";
	else
      $Block .=  "Nota&nbsp;&nbsp;";
    $Block .=    "<input type=\"text\" name=\"Nota\"
                         size=\"75\" maxlength=\"256\"
                         value=\"{$NotaQueryRec['Nota']}\" />
                </p>

                <p style=\"text-align:center;\">
                  <input  type=\"hidden\" name=\"Accion\"
                          value=\"GuardaNota\" />
                  <input  type=\"hidden\" name=\"CheckNotaID\"
                          value=\"{$_POST['CheckNotaID']}\" />
                  <input type=\"submit\" name=\"Submit\"
						 class=\"LargeTextFont\" ";
    if( $__LANG__ == 'en' )
      $Block .=         "value=\"A P P L Y\" />";
	else
      $Block .=         "value=\"A P L I C A R\" />";
    $Block .=  "</p>
              </form>";
  }

  //Editar Nota STAGE 4
  elseif( @$_POST['Accion'] == "GuardaNota" )
  {
    if( !$_POST['CheckNotaID'] )
    {
      header( "Location: MensajeError.php?Errno=3052" );
               //No hay CheckNotaID a guardar
      exit();
    }

    $Nota = htmlspecialchars( $_POST['Nota'], ENT_QUOTES, "UTF-8" );
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3053" );
               //No puedo connect
      exit();
    }

    $Query = "update Notas set Nota = '{$Nota}'
                where NotaID =  {$_POST['CheckNotaID']}";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3054&Var={$_POST['CheckNotaID']}" );
               //No Puede Editar Nota
      exit();
    }

    LogIT( $Conn, $Query );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
				 color:white; text-align:center;\">";
    if( $__LANG__ == 'en' )
      $Block .="-=&nbsp;Editing of NoteID
                      {$_POST['CheckNotaID']}&nbsp;=-
                <br />
                &nbsp;CONFIRMED&nbsp;!";
	else
      $Block .="-=&nbsp;Editado de NotaID
                      {$_POST['CheckNotaID']}&nbsp;=-
                <br />
                &iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=  "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                   <input  type=\"hidden\" name=\"Accion\"
                           value=\"EditarNotas\" />
                   <input type=\"submit\" name=\"Submit\"
                          class=\"SubTitleFont\"
						  style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=          "value=\"Click HERE to Continue\" />";
	else
      $Block .=          "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }

  //Borrar Nota STAGE 3
  elseif( @$_POST['AccionNotaID'] == 'BorrarNotaID' )
  {                            //BORRAR Notas
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3055" );
               //No puedo connect
      exit();
    }

    $Query = "delete from Notas
              where NotaID = {$_POST['CheckNotaID']}";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3056&Var={$_POST['CheckNotaID']}" );
               //No Puede Borrar Nota
      exit();
    }

    LogIT( $Conn, $Query );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
				 color:white; text-align:center;\">";
    if( $__LANG__ == 'en' )
      $Block .="-=&nbsp;Deleting NoteID
                      {$_POST['CheckNotaID']}&nbsp;=-
                <br />
                &nbsp;CONFIRMED&nbsp;!";
      $Block .="-=&nbsp;Borrado de NotaID
                      {$_POST['CheckNotaID']}&nbsp;=-
                <br />
                &iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=  "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                   <input  type=\"hidden\" name=\"Accion\"
                           value=\"EditarNotas\" />
                   <input type=\"submit\" name=\"Submit\"
                          class=\"SubTitleFont\"
                          style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=          "value=\"Click HERE to Continue\" />";
	else
      $Block .=          "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }

  elseif( @$_POST['ManNombresVulgares']
      && !@$_POST['AccionNombreVulgarID'] == 'BorrarNombreVulgarID'
      && !@$_POST['AccionNombreVulgarID'] == 'EditarNombreVulgarID' )
  {
    header( "Location: MensajeError.php?Errno=3057" );
             //Faltan Acción
    exit();
  }

  elseif( @$_POST['AccionNombreVulgarID'] == 'BorrarNombreVulgarID'
      && !@$_POST['CheckNombreVulgarID'] )
  {
    header( "Location: MensajeError.php?Errno=3058" );
             //Faltan CheckNombreVulgarID
    exit();
  }

  elseif( @$_POST['AccionNombreVulgarID'] == 'EditarNombreVulgarID'
      && !@$_POST['CheckNombreVulgarID'] )
  {
    header( "Location: MensajeError.php?Errno=3059" );
             //Faltan CheckNombreVulgarID
    exit();
  }

  // Agregar NombreVulgar STAGE 1
  // Editar  NombreVulgar STAGE 1
  // Borrar  NombreVulgar STAGE 1
  elseif( @$_POST['Accion'] == "AgregarNombresVulgares"  ||
          @$_POST['Accion'] == "EditarNombreVulgar" )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3060" );
             //No puedo connect
      exit();
    }

    $NombresQuery = "Select NombreID, Nombre from Nombres
                     where NombreID > 1 order by Nombre";

    if( !$NombresRes = mysqli_query( $Conn, $NombresQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3061" );
               //No Puede select
      exit();
    }

    if( mysqli_num_rows( $NombresRes ) < 1 )
    {
      mysqli_close( $Conn );
      mysqli_free_result( $NombresRes );
      header( "Location: MensajeError.php?Errno=3062" );
               // No Tiene Nombres a desplegar
      exit();
    }

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
				<p style=\"text-align:center; color:white;\"
                   class=\"SubTitleFont\">";
    if( @$_POST['Accion'] == "EditarNombreVulgar" )
	  if( $__LANG__ == 'en' )
        $Block .="Edit/Delete a Common Name in the Catalog";
	  else
        $Block .="Editar/Borrar un Nombre Vulgar en el Catálogo";
    else
	  if( $__LANG__ == 'en' )
        $Block .="Add a New Common Name to the Catalog";
	  else
        $Block .="Agregar un Nombre Vulgar Nuevo al Catálogo";
    $Block .=  "</p>

                <p class=\"LargeTextFont\" style=\"color:white;\">";
    if( $__LANG__ == 'en' )
      $Block .=  "<em>*</em> Plant&nbsp;&nbsp;";
	else
      $Block .=  "<em>*</em> Planta&nbsp;&nbsp;";
    $Block .=    "<select name=\"NVulgarNombreInfo\" size=\"1\"
                          class=\"LargeTextFont\">
                    <option value=\"1\">";

    if( @$_POST['Accion'] == "AgregarNombresVulgares" )
      if( $__LANG__ == 'en' )
        $Block .=    "Select a plant for which you wish to add a Common Name";
	  else
        $Block .=    "Selecciona una planta para la que deseas agregar un nombre vulgar";
    else
      if( $__LANG__ == 'en' )
        $Block .=    "Select a plant whose Common Name you wish to edit";
	  else
        $Block .=    "Selecciona una planta cuyas Nombres Vulgares  quieras manejar";
    $Block .=      "</option>";

    $NombreID = @$_POST['NVulgarNombreID'];

    while( $NombreRec = mysqli_fetch_array( $NombresRes ) )
    {
      $Block .=  "<option value=\"{$NombreRec['NombreID']}-{$NombreRec['Nombre']}\" ";
      if( @$NombreID == $NombreRec['NombreID'] )
        $Block .=  "selected=\"selected\" ";
      $Block .=    " >{$NombreRec['Nombre']}
                  </option>";
    }
    mysqli_free_result( $NombresRes );

    $Block .=    "</select>
                </p>";

    if( @$_POST['Accion'] == "AgregarNombresVulgares" )
	{
      $Block .=
               "<p class=\"LargeTextFont\" style=\"color:white;\">";
      if( $__LANG__ == 'en' )
        $Block .="<em>*</em> Common Name&nbsp;&nbsp;";
	  else
        $Block .="<em>*</em> Nombre Vulgar&nbsp;&nbsp;";
      $Block .=  "<input type=\"text\" name=\"NombreVulgar\"
                         size=\"50\" maxlength=\"50\" />
                </p>

				<p class=\"LargeTextFont\" style=\"text-align:center;
                                           color:white;\">
                  <br />
                  <input type=\"submit\" name=\"SubmitNombreVulgarNuevo\" ";
      if( $__LANG__ == 'en' )
        $Block .=       "value=\"A P P L Y\" class=\"LargeTextFont\" />";
	  else
        $Block .=       "value=\"A P L I C A R\" class=\"LargeTextFont\" />";
      $Block .="</p>";
	}
    else
	{
      $Block .="<p class=\"LargeTextFont\" style=\"text-align:center;
                                           color:white;\">
                  <br />
                  <input type=\"hidden\" name=\"Accion\"
                         value=\"EditarNombreVulgarID\" />
                  <input type=\"submit\" name=\"Submit\" ";
      if( $__LANG__ == 'en' )
        $Block .=       "value=\"A P P L Y\" class=\"LargeTextFont\" />";
	  else
        $Block .=       "value=\"A P L I C A R\" class=\"LargeTextFont\" />";
      $Block .="</p>";
	}
    $Block .="</form>
                <p style=\"text-align:center; color:white;\">";
    if( $__LANG__ == 'en' )
      $Block .=  "<em>*</em> Mandatory Field";
	else
      $Block .=  "<em>*</em> Campos Obligatorios";
    $Block .=  "</p>";
    mysqli_close( $Conn );
  }

  // Agregar NombreVulgar STAGE 2
  elseif( @$_POST['SubmitNombreVulgarNuevo'] )
  {
    if( !@$_POST['NombreVulgar'] )
    {
      header( "Location: MensajeError.php?Errno=3063" );
               //Falta Campo
      exit();
    }

    $NombreVulgar = htmlspecialchars( $_POST['NombreVulgar'],
                                        ENT_QUOTES, "UTF-8" );
    $NombreInfo = explode( '-', $_POST['NVulgarNombreInfo'] );

    if( $NombreInfo['0'] < 2 )
    {
      header( "Location: MensajeError.php?Errno=3145" );
      exit();
    } // Debe elegir una Planta para el NombreVulgar

    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3064" );
      exit();
    }

    $Query = "insert into NombresVulgares values ( NULL, {$NombreInfo['0']},
                                                        '{$NombreVulgar}' )";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3065" );
               //No Puede insert o update into NombresVulgares
      exit();
    }

    LogIT( $Conn, $Query );
    mysqli_close( $Conn );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
                 color:white; text-align:center;\">";
    if( $__LANG__ == 'en' )
      $Block .="-=&nbsp;Adding the Common Name to the Catalog for
                <br />
                {$NombreInfo['0']} - {$NombreInfo['1']}&nbsp;=-
                <br />
                &nbsp;CONFIRMED&nbsp;!";
	else
      $Block .="-=&nbsp;Agregado al Catálogo, Nombre Vulgar Nuevo para
                <br />
                {$NombreInfo['0']} - {$NombreInfo['1']}&nbsp;=-
                <br />
                &iexcl;&nbsp;CONFIRMADO&nbsp;!";
    $Block .=  "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                  <input  type=\"hidden\" name=\"Accion\"
                          value=\"AgregarNombresVulgares\" />
                  <input type=\"hidden\" name=\"NVulgarNombreID\"
                         value=\"{$NombreInfo['0']}\" />";
    $Block .= "   <input type=\"submit\" name=\"Submit\"
                         class=\"SubTitleFont\"
						 style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=         "value=\"Click HERE to Continue\" />";
	else
      $Block .=         "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }

  // Editar NombreVulgar STAGE 2
  elseif( @$_POST['Accion'] == "EditarNombreVulgarID" )
  {
    $NombreInfo = explode( '-', $_POST['NVulgarNombreInfo']);

    if( $NombreInfo['0'] < 2 )
    {
      header( "Location: MensajeError.php?Errno=3146" );
      exit();
    } // Debe elegir una Planta

    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3066" );
               //No puedo connect
      exit();
    }

    $NombreVulgarQuery = "select NombreVulgarID, NombreVulgar
                            from NombresVulgares
                          where NombreID = {$NombreInfo['0']}";

    if( !$NombresVulgaresRes = mysqli_query( $Conn, $NombreVulgarQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3067" );
               //No Puede select
      exit();
    }

    if( ( $NumRows = mysqli_num_rows( $NombresVulgaresRes ) ) )
      $NombresVulgares = 'Y';
               // No Tiene NombresVulgares a Desplegar

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"font-weight:bold; text-align:center; color:white;\"
				   class=\"SubTitleFont\">";
    if( $__LANG__ == 'en' )
      $Block .=  "Select an Action and a Common Name to manage";
	else
      $Block .=  "Selecciona un Acción y un Nombre Vulgar a manejar";
    $Block .=    "<br />
                  {$NombreInfo['0']} - {$NombreInfo['1']}
                </p>
                <table border=\"1\"  bgcolor=\"#96cf96;\"
                       style=\"text-align:center; margin:auto;
                       border-style:ridge; border-width:thick;\"
                       class=\"LargeTextFont\">
                  <tr style=\"background:#9edcff;\">
                    <th style=\"text-align:left; padding-left:3%;\">";
    if( $__LANG__ == 'en' )
      $Block .=       " CommonNameID  -  CommonName ";
	else
      $Block .=       "NombreVulgarID - NombreVulgar";
    $Block .=      "</th>
                  </tr>";
    $LineCount = 0;

    if( @$NombresVulgares != 'Y' )
	{
      $Block .=  "<tr>
					<td style=\"text-align:left;\">";
      if( $__LANG__ == 'en' )
        $Block .=    "There are NO Common Names to manage";
	  else
        $Block .=    "No hay Nombres Vulgares a manejar";
      $Block .=    "</td>
                  </tr>";
	}
    else
    {
      while( $NombreVulgarRec = mysqli_fetch_array( $NombresVulgaresRes ) )
      {
        $Block .="<tr>
                    <td style=\"text-align:left;\">
                      <input type=\"radio\" name=\"CheckNombreVulgarID\"
                             value=\"{$NombreVulgarRec['NombreVulgarID']}\" />
                      &nbsp;{$NombreVulgarRec['NombreVulgarID']} - {$NombreVulgarRec['NombreVulgar']}&nbsp;
                    </td>
                  </tr>";
        $LineCount++;
        if( !( $LineCount % LineasEnSeccion )  || $NumRows == $LineCount )
        {
          $Block .=
                 "<tr style=\"background:#9edcff;\">
					<th colspan=\"6\" style=\"white-space:nowrap;\">";
          if( $__LANG__ == 'en' )
            $Block .="&nbsp;&nbsp;&nbsp;&nbsp;Edit";
		  else
            $Block .="&nbsp;&nbsp;&nbsp;&nbsp;Editar";
          $Block .=  "<input type=\"radio\" name=\"AccionNombreVulgarID\"
                             value=\"EditarNombreVulgarID\"
                             checked=\"checked\" />
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					  <input type=\"submit\" name=\"ManNombresVulgares\"
                             style=\"font-weight:bold;\" ";
          if( $__LANG__ == 'en' )
            $Block .=       "value=\"A P P L Y\" />";
		  else
            $Block .=       "value=\"A P L I C A R\" />";
          $Block .=  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type=\"radio\" name=\"AccionNombreVulgarID\"
							 value=\"BorrarNombreVulgarID\" />";
          if( $__LANG__ == 'en' )
            $Block .="Delete&nbsp;&nbsp;&nbsp;&nbsp;";
		  else
            $Block .="Borrar&nbsp;&nbsp;&nbsp;&nbsp;";
          $Block .="</th>
                  </tr>";
        }
      }
      $Block .= " </table>
                </form>";
      mysqli_free_result( $NombresVulgaresRes );
      mysqli_close( $Conn );
    }
  }

  // Editar NombreVulgar STAGE 3
  elseif( @$_POST['AccionNombreVulgarID'] == 'EditarNombreVulgarID' )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3068" );
      exit();
      //No puede connect
    }

    $NombreVulgarQuery = "select NombreVulgar from NombresVulgares
                            where NombreVulgarID = {$_POST['CheckNombreVulgarID']}";

    if( !$NombresVulgaresRes = mysqli_query( $Conn, $NombreVulgarQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3069" );
               //No Puede select from Notas
      exit();
    }

    if( mysqli_num_rows( $NombresVulgaresRes ) != 1 )
    {
      header( "Location: MensajeError.php?Errno=3070&Var={$_POST['CheckNombreVulgarID']}" );
      mysqli_free_result( $NombresVulgaresRes );
               // El orden que quieremos borrar no existe
      exit();
    }

    $NombreVulgarRec = mysqli_fetch_array( $NombresVulgaresRes );

    mysqli_free_result( $NombresVulgaresRes );

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
				<p style=\"text-align:center; color:white;\"
				   class=\"SubTitleFont\">";
    if( $__LANG__ == 'en' )
      $Block .=  "Edit a Common Name in the Catalog";
	else
      $Block .=  "Editar al Catálogo; Nombre Vulgar";
    $Block .=  "</p>

				<p style=\"text-align:center; color:white;\"
                   class=\"LargeTextFont\">
                  <br />";
    if( $__LANG__ == 'en' )
      $Block .=  "Common Name&nbsp;&nbsp;";
	else
      $Block .=  "Nombre Vulgar&nbsp;&nbsp;";
    $Block .=    "<input type=\"text\" name=\"NombreVulgar\"
                         size=\"75\" maxlength=\"256\"
                         value=\"{$NombreVulgarRec['NombreVulgar']}\" />

                <p style=\"text-align:center;\">
                  <input  type=\"hidden\" name=\"Accion\"
                          value=\"GuardaNombreVulgar\" />
                  <input  type=\"hidden\" name=\"CheckNombreVulgarID\"
                          value=\"{$_POST['CheckNombreVulgarID']}\" />
                  <input type=\"submit\" name=\"Submit\"
						 class=\"LargeTextFont\" ";
    if( $__LANG__ == 'en' )
      $Block .=         "value=\"A P P L Y\" />";
	else
      $Block .=         "value=\"A P L I C A R\" />";
    $Block .=  "</p>
              </form>";
  }

  // Editar NombreVulgar STAGE 4
  elseif( @$_POST['Accion'] == "GuardaNombreVulgar" )
  {
    if( !$_POST['CheckNombreVulgarID'] )
    {
      header( "Location: MensajeError.php?Errno=3071" );
               //No hay CheckNombreVulgarID a guardar
      exit();
    }

    $NombreVulgar = htmlspecialchars( $_POST['NombreVulgar'],
                                                ENT_QUOTES, "UTF-8" );
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3072" );
               //No puedo connect
      exit();
    }

    $Query = "update NombresVulgares set NombreVulgar = '{$NombreVulgar}'
                where NombreVulgarID =  {$_POST['CheckNombreVulgarID']}";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3073&Var={$_POST['CheckNombreVulgarID']}" );
               //No Puede Editar NombreVulgar
      exit();
    }

    LogIT( $Conn, $Query );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
				 color:white; text-align:center;\">";
    if( $__LANG__ == 'en' )
      $Block .="-=&nbsp;Editing of CommanNameID
                      {$_POST['CheckNombreVulgarID']}&nbsp;=-
                <br />
                &nbsp;CONFIRMED&nbsp;!";
	else
      $Block .="-=&nbsp;Editado de NombreVulgarID
                      {$_POST['CheckNombreVulgarID']}&nbsp;=-
                <br />
                &iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=  "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                   <input  type=\"hidden\" name=\"Accion\"
                           value=\"EditarNombresVulgares\" />
                   <input type=\"submit\" name=\"Submit\"
                          class=\"SubTitleFont\"
						  style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=          "value=\"Click HERE to Continue\" />";
	else
      $Block .=          "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }

  // Borrar NombreVulgar STAGE 3
  elseif( @$_POST['AccionNombreVulgarID'] == 'BorrarNombreVulgarID' )
  {                            //BORRAR Notas
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3074" );
               //No puedo connect
      exit();
    }

    $Query = "delete from NombresVulgares
              where NombreVulgarID = {$_POST['CheckNombreVulgarID']}";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3075&Var={$_POST['CheckNombreVulgarID']}" );
               //No Puede Borrar Nombre Vulgar
      exit();
    }

    LogIT( $Conn, $Query );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
				 color:white; text-align:center;\">";
    if( $__LANG__ == 'en' )
      $Block .="-=&nbsp;Deleting of CommonNameID
                      {$_POST['CheckNombreVulgarID']}&nbsp;=-
                <br />
                &nbsp;CONFIRMED&nbsp;!";
	else
      $Block .="-=&nbsp;Borrado de NombreVulgarID
                      {$_POST['CheckNombreVulgarID']}&nbsp;=-
                <br />
                &iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=  "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                   <input  type=\"hidden\" name=\"Accion\"
                           value=\"EditarNombresVulgares\" />
                   <input type=\"submit\" name=\"Submit\"
                          class=\"SubTitleFont\"
                          style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=          "value=\"Click HERE to Continue\" />";
	else
      $Block .=          "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }

  elseif( @$_POST['ManFotos']
      && !@$_POST['AccionFotoID'] == 'BorrarFotoID'
      && !@$_POST['AccionFotoID'] == 'EditarFotoID' )
  {
    header( "Location: MensajeError.php?Errno=3076" );
             //Faltan Acción
    exit();
  }

  elseif( @$_POST['AccionFotoID'] == 'BorrarFotoID'
      && !@$_POST['CheckFotoID'] )
  {
    header( "Location: MensajeError.php?Errno=3077" );
             //Faltan FotoID
    exit();
  }

  elseif( @$_POST['AccionFotoID'] == 'EditarFotoID'
      && !@$_POST['CheckFotoID'] )
  {
    header( "Location: MensajeError.php?Errno=3078" );
             //Faltan FotoID
    exit();
  }

  // Agregar Foto STAGE 1
  // Editar  Foto STAGE 1
  elseif( @$_POST['Accion'] == "AgregarFotos"  ||
          @$_POST['Accion'] == "EditarFoto" )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3079" );
             //No puedo connect
      exit();
    }

    $NombresQuery = "Select NombreID, Nombre from Nombres
                     order by Nombre";

    if( !$NombresRes = mysqli_query( $Conn, $NombresQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3080" );
               //No Puede select
      exit();
    }

    if( mysqli_num_rows( $NombresRes ) < 1 )
    {
      mysqli_close( $Conn );
      mysqli_free_result( $NombresRes );
      header( "Location: MensajeError.php?Errno=3081" );
               // No Tiene Nombres a desplegar
      exit();
    }

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
				<p style=\"text-align:center; color:white;\"
                   class=\"SubTitleFont\">";
    if( @$_POST['Accion'] == "EditarFoto" )
	  if( $__LANG__ == 'en' )
        $Block .=  "Edit/Delete a Photo in the Catalog";
	  else
        $Block .=  "Editar/Borrar un Foto en el Catálogo";
    else
	  if( $__LANG__ == 'en' )
      $Block .=  "Add a New Photo to the Catalog";
	  else
      $Block .=  "Agregar un Foto Nuevo al Catálogo";
    $Block .=  "</p>
                <p class=\"LargeTextFont\" style=\"color:white;\">";
    if( $__LANG__ == 'en' )
      $Block .=  "<em>*</em> Plant&nbsp;&nbsp;";
	else
      $Block .=  "<em>*</em> Planta&nbsp;&nbsp;";
    $Block .=    "<select name=\"FotoNombreInfo\" size=\"1\"
                          class=\"LargeTextFont\">
                    <option value=\"1\">";
    if( @$_POST['Accion'] == "AgregarFotos" )
	  if( $__LANG__ == 'en' )
        $Block .=      "Select a plant for which you want to add a Photo";
	  else
        $Block .=      "Selecciona una planta para la que deseas agregar un Foto";
    else
	  if( $__LANG__ == 'en' )
        $Block .=      "Select a plant whose Photo you want to Edit/Delete";
	  else
        $Block .=      "Selecciona una planta cuyas Foto quieras manejar";
    $Block .=      "</option>";

    $NombreID = @$_POST['FotoNombreID'];

    while( $NombreRec = mysqli_fetch_array( $NombresRes ) )
    {
      if( $NombreRec['NombreID'] == 1 )
        continue;
      $Block .=  "<option value=\"{$NombreRec['NombreID']}-{$NombreRec['Nombre']}\" ";
      if( @$NombreID == $NombreRec['NombreID'] )
        $Block .=  "selected=\"selected\" ";
      $Block .=    " >{$NombreRec['Nombre']}
                  </option>";
    }
    mysqli_free_result( $NombresRes );

    $Block .=    "</select>
                </p>";

    if( @$_POST['Accion'] == "AgregarFotos" )
	{
      $Block .=
               "<p class=\"LargeTextFont\" style=\"color:white;\">";
      if( $__LANG__ == 'en' )
        $Block .="<em>*</em> Photo Location&nbsp;&nbsp;";
	  else
        $Block .="<em>*</em> Dirección del Foto&nbsp;&nbsp;";
      $Block .=  "<input type=\"text\" name=\"Foto\"
                         size=\"75\" maxlength=\"75\" />
                </p>

				<p style=\"color:white;\" class=\"LargeTextFont\">";
    $Block .=   "<input type=\"checkbox\" name=\"HayVideo\" value=\"1\">";
    if( $__LANG__ == 'en' )
	  $Block .=  "Has Video&nbsp;&nbsp;&nbsp;&nbsp;Video Location&nbsp;&nbsp;";
	else
	  $Block .=  "Hay Video&nbsp;&nbsp;&nbsp;&nbsp;Dirección del Video&nbsp;&nbsp;";
	$Block .=      "<input type=\"text\" name=\"Video\"
	                       size=\"75\" maxlength=\"256\" />
                </p>

                <p class=\"LargeTextFont\" style=\"text-align:center;\">
                  <br />
                  <input type=\"submit\" name=\"SubmitFotoNuevo\" ";
	}
    else
      $Block .="<p class=\"LargeTextFont\" style=\"text-align:center;\">
                  <br />
                  <input type=\"hidden\" name=\"Accion\"
                         value=\"EditarFotoID\" />
				  <input type=\"submit\" name=\"Submit\" ";
      if( $__LANG__ == 'en' )
        $Block .=       "value=\"A P P L Y\" class=\"LargeTextFont\" />";
	  else
        $Block .=       "value=\"A P L I C A R\" class=\"LargeTextFont\" />";
      $Block .="</p>
              </form>
                <p style=\"text-align:center; color:white;\">";
      if( $__LANG__ == 'en' )
        $Block .="<em>*</em> Mandatory Fields";
		else
        $Block .="<em>*</em> Campos Obligatorios";
      $Block .="</p>";
    mysqli_close( $Conn );
  }

  // Agregar Foto STAGE 2
  elseif( @$_POST['SubmitFotoNuevo'] )
  {
    if( !@$_POST['Foto'] )
    {
      header( "Location: MensajeError.php?Errno=3082" );
               //Falta Campo
      exit();
    }

	if( @$_POST['HayVideo'] && !@$_POST['Video'] ||
	   !@$_POST['HayVideo'] &&  @$_POST['Video'] )
    {
      header( "Location: MensajeError.php?Errno=3140" );
               //Falta Campo
      exit();
    }

    $Video = htmlspecialchars( $_POST['Video'], ENT_QUOTES, "UTF-8" );
    $Foto = htmlspecialchars( $_POST['Foto'], ENT_QUOTES, "UTF-8" );
    $NombreInfo = explode( '-', $_POST['FotoNombreInfo'] );

	if( $NombreInfo['0'] < 2 )
    {
      header( "Location: MensajeError.php?Errno=3143" );
      exit();
    }     // Debe elegir una Planta

    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3083" );
      exit();
    }

    $ExisteFotoQuery = "select Direccion from Fotos
                          where NombreID = {$NombreInfo['0']}";

    if( !$ExisteFotoRes = mysqli_query( $Conn, $ExisteFotoQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3084" );
               //No Puede select de Fotos
      exit();
    }

    if( mysqli_num_rows( $ExisteFotoRes ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3085" );
               //Existe Foto debes editarlo
      exit();
    }

	if( !@$_POST['HayVideo'] )
	  $HayVideo = '0';
	else
	  $HayVideo = '1';

	$Query = "insert into Fotos values ( NULL, {$NombreInfo['0']}, '{$Foto}',
                          '{$HayVideo}', '{$Video}' )";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3086" );
               //No Puede insert into Fotos
      exit();
    }

    LogIT( $Conn, $Query );
    mysqli_close( $Conn );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
				 color:white; text-align:center;\">";
    if( $__LANG__ == 'en' )
      $Block .="-=&nbsp;Adding a New Photo to the Catalog for
                <br />
                {$NombreInfo['0']} - {$NombreInfo['1']}&nbsp;=-
                <br />
                &nbsp;CONFIRMED&nbsp;!";
	else
      $Block .="-=&nbsp;Agregado al Catálogo, Foto Nuevo para
                <br />
                {$NombreInfo['0']} - {$NombreInfo['1']}&nbsp;=-
                <br />
                &iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=  "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                  <input  type=\"hidden\" name=\"Accion\"
                          value=\"AgregarFotos\" />
                  <input type=\"hidden\" name=\"FotoNombreID\"
                         value=\"{$NombreInfo['0']}\" />";
    $Block .= "   <input type=\"submit\" name=\"Submit\"
                         class=\"SubTitleFont\"
						 style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=         "value=\"Click HERE to Continue\" />";
	else
      $Block .=         "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }

  // Editar Foto STAGE 2
  // Borrar Foto STAGE 2
  elseif( @$_POST['Accion'] == "EditarFotoID" )
  {
    $NombreInfo = explode( '-', $_POST['FotoNombreInfo']);

    if( $NombreInfo['0'] < 2 )
    {
      header( "Location: MensajeError.php?Errno=3149" );
      exit();
    } // Debe elegir una Planta

    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3087" );
               //No puedo connect
      exit();
    }

    $FotoQuery = "select FotoID, Direccion from Fotos
                    where NombreID = {$NombreInfo['0']}";

    if( !$FotosRes = mysqli_query( $Conn, $FotoQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3088" );
               //No Puede select
      exit();
    }

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"font-weight:bold; text-align:center; color:white;\"
                   class=\"SubTitleFont\">";
    if( $__LANG__ == 'en' )
      $Block .=  "Select an Action and a Photo to manage ";
	else
      $Block .=  "Selecciona un Acción y un Foto a manejar ";
    $Block .=    "<br />
                  {$NombreInfo['0']} - {$NombreInfo['1']}
                </p>
                <table border=\"1\"  bgcolor=\"#96cf96;\"
                       style=\"text-align:center; margin:auto;
                       border-style:ridge; border-width:thick;\"
                       class=\"LargeTextFont\">
                  <tr style=\"background:#9edcff;\">
                    <th style=\"text-align:left; padding-left:3%;\">
                       FotoID - Dirección
                    </th>
                  </tr>";
    $LineCount = 0;
    $FotoRec = mysqli_fetch_array( $FotosRes );
    do
    {
      $Block .=  "<tr>
					<td style=\"text-align:left;\">";
      if( !$FotoRec['FotoID'] )
        $Block .=    "No Hay Foto a elegir";
      else
        $Block .=    "<input type=\"radio\" name=\"CheckFotoID\"
                             value=\"{$FotoRec['FotoID']}\" />
                      &nbsp;{$FotoRec['FotoID']} - {$FotoRec['Direccion']}&nbsp;";
      $Block .=    "</td>
                  </tr>";
      $LineCount++;
      $Block .=  "<tr style=\"background:#9edcff;\">
                    <th colspan=\"6\" style=\"white-space:nowrap;\">
                      &nbsp;&nbsp;&nbsp;&nbsp;Editar
                      <input type=\"radio\" name=\"AccionFotoID\"
                             value=\"EditarFotoID\"
                             checked=\"checked\" />
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type=\"submit\" name=\"ManFotos\"
                             value=\"A P L I C A R\" />
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type=\"radio\" name=\"AccionFotoID\"
                             value=\"BorrarFotoID\" />
                      Borrar&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                  </tr>";
    } while( $FotoRec = mysqli_fetch_array( $FotosRes ) );
    $Block .= " </table>
              </form>";
    mysqli_free_result( $FotosRes );
    mysqli_close( $Conn );
  }

  // Editar Foto STAGE 3
  elseif( @$_POST['AccionFotoID'] == 'EditarFotoID' )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3089" );
      exit();
      //No puede connect
    }

    $FotoQuery = "select Direccion, HayVideo, Video from Fotos
                    where FotoID = {$_POST['CheckFotoID']}";

    if( !$FotoQueryRes = mysqli_query( $Conn, $FotoQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3090" );
               //No Puede select from Fotos
      exit();
    }

    if( mysqli_num_rows( $FotoQueryRes ) != 1 )
    {
      header( "Location: MensajeError.php?Errno=3091&Var={$_POST['CheckFotoID']}" );
      mysqli_free_result( $FotoQueryRes );
               // El Foto que quieremos editar no existe
      exit();
    }

    $FotoQueryRec = mysqli_fetch_array( $FotoQueryRes );

    mysqli_free_result( $FotoQueryRes );

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
				<p style=\"text-align:center; color:white;\"
                   class=\"SubTitleFont\">";
    if( $__LANG__ == 'en' )
      $Block .=  "Editing a Photo location in the Catalog";
	else
      $Block .=  "Editando un Dirección de Foto en el Catálogo";
    $Block .=  "</p>

				<p style=\"text-align:center; color:white;\"
                   class=\"LargeTextFont\">
                  <br />";
    if( $__LANG__ == 'en' )
      $Block .=  "Photo Location&nbsp;&nbsp;";
	else
      $Block .=  "Dirección del Foto&nbsp;&nbsp;";
    $Block .=    "<input type=\"text\" name=\"Foto\"
                         size=\"75\" maxlength=\"256\"
                         value=\"{$FotoQueryRec['Direccion']}\" />
                </p>
				<p style=\"text-align:center; color:white;\"
                   class=\"LargeTextFont\">";
    $Block .=   "<input type=\"checkbox\" name=\"HayVideo\" value=\"1\" ";
	if( $FotoQueryRec['HayVideo'] )
	  $Block .=   	   "checked=\"checked\" />";
	else
	  $Block .=   	    " />";
    if( $__LANG__ == 'en' )
	  $Block .=  "Has Video&nbsp;&nbsp;&nbsp;&nbsp;Video Location&nbsp;&nbsp;";
	else
	  $Block .=  "Hay Video&nbsp;&nbsp;&nbsp;&nbsp;Dirección del Video&nbsp;&nbsp;";
	$Block .=      "<input type=\"text\" name=\"Video\"
	                       size=\"75\" maxlength=\"256\"
                           value=\"{$FotoQueryRec['Video']}\" />
                </p>

                <p style=\"text-align:center;\">
                  <input  type=\"hidden\" name=\"Accion\"
                          value=\"GuardaFoto\" />
                  <input  type=\"hidden\" name=\"CheckFotoID\"
                          value=\"{$_POST['CheckFotoID']}\" />
                  <input type=\"submit\" name=\"Submit\"
                         class=\"LargeTextFont\" ";
    if( $__LANG__ == 'en' )
      $Block .=         "value=\"A P P L Y\" />";
	else
      $Block .=         "value=\"A P L I C A R\" />";
    $Block .=  "</p>
              </form>";
  }

  // Editar Foto STAGE 4
  elseif( @$_POST['Accion'] == "GuardaFoto" )
  {
    if( !$_POST['CheckFotoID'] )
    {
      header( "Location: MensajeError.php?Errno=3092" );
               //No hay CheckFotoID a guardar
      exit();
    }

    $Foto = htmlspecialchars( $_POST['Foto'], ENT_QUOTES, "UTF-8" );
    $Video = htmlspecialchars( $_POST['Video'], ENT_QUOTES, "UTF-8" );
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3093" );
               //No puedo connect
      exit();
    }

	if( @$_POST['HayVideo'] && !@$_POST['Video'] ||
	   !@$_POST['HayVideo'] &&  @$_POST['Video'] )
    {
      header( "Location: MensajeError.php?Errno=3141" );
               //Falta Campo
      exit();
    }

	if( !@$_POST['HayVideo'] )
	  $HayVideo = '0';
	else
	  $HayVideo = '1';

	$Query = "update Fotos set Direccion = '{$Foto}',
	                           HayVideo ='{$HayVideo}',
				   Video = '{$_POST['Video']}'
                where FotoID =  {$_POST['CheckFotoID']}";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3094&Var={$_POST['CheckFotoID']}" );
               //No Puede Editar Foto
      exit();
    }

    LogIT( $Conn, $Query );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
				 color:white; text-align:center;\">";
    if( $__LANG__ == 'en' )
      $Block .="-=&nbsp;Editing of FotoID
                      {$_POST['CheckFotoID']}&nbsp;=-
                <br />
                &nbsp;CONFIRMED&nbsp;!";
	else
      $Block .="-=&nbsp;Editado de FotoID
                      {$_POST['CheckFotoID']}&nbsp;=-
                <br />
                &iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=  "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                   <input  type=\"hidden\" name=\"Accion\"
                           value=\"EditarFotos\" />
                   <input type=\"submit\" name=\"Submit\"
                          class=\"SubTitleFont\"
                          style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=          "value=\"Click HERE to Continue\" />";
	else
      $Block .=          "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }

  // Borrar Foto STAGE 3
  elseif( @$_POST['AccionFotoID'] == 'BorrarFotoID' )
  {                            //BORRAR Fotos
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3095" );
               //No puedo connect
      exit();
    }

    $Query = "delete from Fotos
              where FotoID = {$_POST['CheckFotoID']}";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3096&Var={$_POST['CheckFotoID']}" );
               //No Puede Borrar Foto
      exit();
    }

    LogIT( $Conn, $Query );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
                 color:#0000aa; text-align:center;\">";
    if( $__LANG__ == 'en' )
      $Block .="-=&nbsp;Deleting of FotoID
                      {$_POST['CheckFotoID']}&nbsp;=-
                <br />
                &nbsp;CONFIRMED&nbsp;!";
	else
      $Block .="-=&nbsp;Borrado de FotoID
                      {$_POST['CheckFotoID']}&nbsp;=-
                <br />
                &iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=  "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                   <input  type=\"hidden\" name=\"Accion\"
                           value=\"EditarFotos\" />
                   <input type=\"submit\" name=\"Submit\"
                          class=\"SubTitleFont\"
                          style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=          "value=\"Click HERE to Continue\" />";
	else
      $Block .=          "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }

  elseif( @$_POST['AccionFamiliaID'] == "EditarFamiliaID"
      && !@$_POST['CheckFamiliaID'] )
  {
    header( "Location: MensajeError.php?Errno=3097" );
             //Faltan FamiliaID
    exit();
  }

  elseif( @$_POST['ManFamilias']
      && !@$_POST['AccionFamiliaID'] == 'BorrarFamiliaID'
      && !@$_POST['AccionFamiliaID'] == 'EditarFamiliaID' )
  {
    header( "Location: MensajeError.php?Errno=3098" );
             //Faltan Acción
    exit();
  }

  elseif( @$_POST['AccionFamiliaID'] == 'BorrarFamiliaID'
      && !@$_POST['CheckFamiliaID'] )
  {
    header( "Location: MensajeError.php?Errno=3099" );
             //Faltan FamiliaID
    exit();
  }

  // Agregar Familia STAGE 1
  // Editar  Familia STAGE 2
  elseif( @$_POST['Accion'] == "AgregarFamilias"
       || @$_POST['AccionFamiliaID'] == "EditarFamiliaID" )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3100" );
             //No puedo connect
    exit();
    }

    if( @$_POST['AccionFamiliaID'] == "EditarFamiliaID" )
    {
      $Query = "select * from Familias
                where FamiliaID = {$_POST['CheckFamiliaID']}";

      if( !$FamiliasRes = mysqli_query( $Conn, $Query ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=3101" );
                 //No Puede select
        exit();
      }

      if( mysqli_num_rows( $FamiliasRes ) != 1 )
      {
        header( "Location: MensajeError.php?Errno=3102" );
        mysqli_free_result( $FamiliaRes  );
                 // No Tiene Familias a Desplegar
        exit();
      }

      $Familias = mysqli_fetch_array( $FamiliasRes );
    }
    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
				<p style=\"text-align:center; color:white;\"
                   class=\"SubTitleFont\">";
    if( @$_POST['AccionFamiliaID'] == "EditarFamiliaID" )
	  if( $__LANG__ == 'en' )
        $Block .=  "Edit Families in the Catalog";
	  else
        $Block .=  "Editar las Familias en el Catálogo";
    else
	  if( $__LANG__ == 'en' )
        $Block .=  "Add a New Family to the Catalog";
	  else
        $Block .=  "Agregar una Familia Nueva al Catálogo";
    $Block .= " </p>

				<p style=\"text-align:center; color:white;\"
                   class=\"LargeTextFont\">
				  <br />";
    if( $__LANG__ == 'en' )
      $Block .=  "<em>*</em> FamilyID&nbsp;&nbsp;";
	else
      $Block .=  "<em>*</em> FamiliaID&nbsp;&nbsp;";
    $Block .=    "<input type=\"text\" name=\"Familia\"";
    if( @$_POST['AccionFamiliaID'] == "EditarFamiliaID" )
      $Block .= "        value=\"{$Familias['FamiliaID']}\" ";
    else
    {
      $SiguienteiFamiliaIDQuery = "SELECT AUTO_INCREMENT as NextID
                                   FROM information_schema.TABLES
                                   WHERE TABLE_SCHEMA = \"Selva\" AND
                                   TABLE_NAME = \"Familias\" ";
      if( !$SiguienteFamiliaIDRes = mysqli_query( $Conn, $SiguienteiFamiliaIDQuery) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=3104" );
                 //No Puede SELECT de Familias
        exit();
      }

      if( mysqli_num_rows( $SiguienteFamiliaIDRes ) != 1 )
      {
        header( "Location: MensajeError.php?Errno=3105" );
        mysqli_free_result( $FamiliaRes  );
                 // No Puede obtener siguiente ID de Familias
        exit();
      }

      $SiguienteFamiliaIDRec = mysqli_fetch_array( $SiguienteFamiliaIDRes );
      $SiguienteFamiliaID = $SiguienteFamiliaIDRec['NextID'];
      $Block .= "        value=\"$SiguienteFamiliaID\" ";
    }
    $Block .= "          readonly=\"readonly\"
						 size=\"4\" maxlength=\"4\" />";
    if( $__LANG__ == 'en' )
      $Block .=  "&nbsp;&nbsp;&nbsp;&nbsp;<em>*</em> Name&nbsp;&nbsp;";
	else
      $Block .=  "&nbsp;&nbsp;&nbsp;&nbsp;<em>*</em> Nombre&nbsp;&nbsp;";
    $Block .=    "<input type=\"text\" name=\"FamiliaNombre\"";
    if( @$_POST['AccionFamiliaID'] == "EditarFamiliaID" )
      $Block .= "        value=\"{$Familias['Familia']}\"";
    $Block .= "          size=\"50\" maxlength=\"75\" />
                </p>

				<p class=\"LargeTextFont\" style=\"text-align:center;
                                           color:white;\">";
    if( @$_POST['AccionFamiliaID'] == "EditarFamiliaID" )
      $Block .= "<input type=\"hidden\" name=\"EditarFamiliaID\"
                        value=\"{$_POST['CheckFamiliaID']}\" />";
    $Block .= "   <br />
				  <input type=\"submit\" name=\"SubmitFamiliaNueva\" ";
    if( $__LANG__ == 'en' )
      $Block .=         "value=\"A P P L Y\" class=\"LargeTextFont\" />";
	else
      $Block .=         "value=\"A P L I C A R\" class=\"LargeTextFont\" />";
    $Block .=  "</p>

				<p style=\"text-align:center; color:white;\">";
    if( $__LANG__ == 'en' )
      $Block .=  "<em>*</em> Mandatory Field";
	else
      $Block .=  "<em>*</em> Campos Obligatorios";
    $Block .=  "</p>
              </form>";
    if( @$_POST['AccionFamiliaID'] == "EditarFamiliaID" )
    {
      mysqli_close( $Conn );
      mysqli_free_result( $FamiliasRes );
    }
  }

  // Editar Familia STAGE 1
  // Borrar Familia STAGE 1
  elseif( @$_POST['Accion'] == "EditarFamilia" )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3106" );
               //No puedo connect
      exit();
    }

    $Query = "select * from Familias order by Familia";

    if( !$FamiliasRes = mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3107" );
               //No Puede select
      exit();
    }

    if( ( $NumRows = mysqli_num_rows( $FamiliasRes ) ) < 1 )
    {
      header( "Location: MensajeError.php?Errno=3108" );
      mysqli_free_result( $FamiliasRes );
               // No Tiene Familias a Desplegar
      exit();
    }

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"font-weight:bold; text-align:center; color:white;\"
                   class=\"SubTitleFont\">";
    if( $__LANG__ == "en" )
      $Block .=  "Select an Action and a Family to manage";
    else
      $Block .=  "Selecciona un Acción y una Familia a manejar";
    $Block .=  "</p>
                <table border=\"1\"  bgcolor=\"#96cf96;\"
                       style=\"text-align:center; margin:auto;
                               border-style:ridge; border-width:thick;\">
                  <tr style=\"background:#9edcff;\">
                    <th>";
    if( $__LANG__ == "en" )
      $Block .=       " FamilyID - Family ";
    else
      $Block .=       "FamiliaID - Familia";
    $Block .=      "</th>
                  </tr>";
    $LineCount = 0;

    while( $FamiliaRec = mysqli_fetch_array( $FamiliasRes ) )
    {
      if( $FamiliaRec['FamiliaID'] == 1 )
        continue;
      $Block .=  "<tr>
                    <td style=\"text-align:left;\">
                      <input type=\"radio\" name=\"CheckFamiliaID\"
                             value=\"{$FamiliaRec['FamiliaID']}\" />
                      &nbsp;{$FamiliaRec['FamiliaID']} - {$FamiliaRec['Familia']}&nbsp;
                    </td>
                  </tr>";
      $LineCount++;
      if( !( $LineCount % LineasEnSeccion )  || $NumRows == $LineCount + 1 )
      {
        $Block .= "<tr style=\"background:#9edcff;\">
                    <th colspan=\"6\" style=\"white-space:nowrap;\">";
        if( $__LANG__ == "en" )
          $Block .=  "&nbsp;&nbsp;&nbsp;&nbsp;Edit";
        else
          $Block .=  "&nbsp;&nbsp;&nbsp;&nbsp;Editar";
        $Block .=    "<input type=\"radio\" name=\"AccionFamiliaID\"
                             value=\"EditarFamiliaID\"
                             checked=\"checked\" />
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type=\"submit\" name=\"ManFamilias\" ";
        if( $__LANG__ == "en" )
          $Block .=         "value=\"A P P L Y\" />";
        else
          $Block .=         "value=\"A P L I C A R\" />";
        $Block .=    "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type=\"radio\" name=\"AccionFamiliaID\"
                             value=\"BorrarFamiliaID\" />";
        if( $__LANG__ == "en" )
          $Block .=  "Delete&nbsp;&nbsp;&nbsp;&nbsp;";
        else
          $Block .=  "Borrar&nbsp;&nbsp;&nbsp;&nbsp;";
        $Block .=  "</th>
                  </tr>";
      }
    }
    $Block .= " </table>
              </form>";
    mysqli_free_result( $FamiliasRes );
    mysqli_close( $Conn );
  }

  // Agregar Familia STAGE 2
  // Editar  Familia STAGE 3
  elseif( @$_POST['SubmitFamiliaNueva'] )
  {
    if( !@$_POST['Familia'] || !@$_POST['FamiliaNombre'] )
    {
      header( "Location: MensajeError.php?Errno=3109" );
               //Faltan Campos
      exit();
    }

    if( strlen( $_POST['Familia'] ) < 1 ||
        strlen( $_POST['Familia'] ) > 3 )
    {
      header( "Location: MensajeError.php?Errno=3110" );
      exit();
      //Familia código inválido
    }

    if( !IsValidInt( $_POST['Familia'], 3 ) )
    {
      header( "Location: MensajeError.php?Errno=3111" );
      exit();
      //Familia código inválido
    }

    $FamiliaNombre = htmlspecialchars( $_POST['FamiliaNombre'],
                                            ENT_QUOTES, "UTF-8" );

    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3112" );
      exit();
      //No puede connect
    }

    if( @$_POST['EditarFamiliaID'] )
      $Query = "update Familias set Familia = '{$FamiliaNombre}'
                                 where FamiliaID = {$_POST['EditarFamiliaID']}";
    else
      $Query = "insert into Familias values ( NULL, '{$_POST['FamiliaNombre']}' )";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3113" );
               //No Puede insert o update into Familias
      exit();
    }

    LogIT( $Conn, $Query );
    mysqli_close( $Conn );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
                 color:white; text-align:center;\">";
    if( @$_POST['EditarFamiliaID'] )
      if( $__LANG__ == "en" )
        $Block .="-=&nbsp;Editing a Family in the Catalog -";
      else
        $Block .="-=&nbsp;Editado una Familia en el Catálogo -";
    else
      if( $__LANG__ == "en" )
        $Block .="-=&nbsp;Adding a New Family to the Catalog -";
      else
        $Block .="-=&nbsp;Agregado una Familia Nueva al Catálogo -";
    $Block .= "        \"{$_POST['Familia']}\"&nbsp;=-
                <br />";
      if( $__LANG__ == "en" )
        $Block .="&iexcl;&nbsp;CONFIRMED&nbsp;!";
      else
        $Block .="&iexcl;&nbsp;CONFIRMADA&nbsp;!";
      $Block .="<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                  <input  type=\"hidden\" name=\"Accion\"";
    if( @$_POST['EditarFamiliaID'] )
      $Block .= "         value=\"EditarFamilia\" />";
    else
      $Block .= "         value=\"AgregarFamilias\" />";
    $Block .= "   <input type=\"submit\" name=\"Submit\"
                         class=\"SubTitleFont\"
                         style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == "en" )
      $Block .=         "value=\"Click HERE to Continue\" />";
    else
      $Block .=         "value=\"Presiona aquí para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }

  // Borrar Familia STAGE 2
  elseif( @$_POST['AccionFamiliaID'] == 'BorrarFamiliaID' )
  {                            //BORRAR Familias
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3114" );
               //No puedo connect
      exit();
    }

    $Query = "delete from Familias where FamiliaID = {$_POST['CheckFamiliaID']}";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3115&Var={$_POST['CheckFamiliaID']}" );
               //No Puede Borrar Familia
      exit();
    }

    LogIT( $Conn, $Query );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
                 color:white; text-align:center;\">";
    if( $__LANG__ == "en" )
      $Block .="-=&nbsp;Deleting FamiliaID {$_POST['CheckFamiliaID']}&nbsp;=-";
    else
      $Block .="-=&nbsp;Borrado de FamiliaID {$_POST['CheckFamiliaID']}&nbsp;=-";
    $Block .=  "<br />";
    if( $__LANG__ == "en" )
      $Block .="&iexcl;&nbsp;CONFIRMED&nbsp;!";
    else
      $Block .="&iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=  "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                   <input  type=\"hidden\" name=\"Accion\"
                           value=\"EditarFamilia\" />
                   <input type=\"submit\" name=\"Submit\"
                          class=\"SubTitleFEnt\"
                          style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == "en" )
      $Block .=          "value=\"Click HERE to Continue\" />";
    else
      $Block .=          "value=\"Presiona aquí para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }

  elseif( @$_POST['ManProveedores']
      && !@$_POST['AccionProveedorID'] == 'BorrarProveedorID'
      && !@$_POST['AccionProveedorID'] == 'EditarProveedorID' )
  {
    header( "Location: MensajeError.php?Errno=3116" );
             //Faltan Acción
    exit();
  }

  elseif( @$_POST['AccionProveedorID'] == 'BorrarProveedorID' &&
         !@$_POST['CheckProveedorID'] )
  {
    header( "Location: MensajeError.php?Errno=3117" );
             //Faltan ProveedorID
    exit();
  }

  // Agregar Proveedor STAGE 1
  // Editar  Proveedor STAGE 2
  elseif( @$_POST['Accion'] == "AgregarProveedores"
       || @$_POST['AccionProveedorID'] == "EditarProveedorID" )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3118" );
             //No puedo connect
    exit();
    }

    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
    {
      if( !$_POST['CheckProveedorID'] )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=3119" );
                 //Faltan ProveedorID
        exit();
      }

      $Query = "select * from Proveedores
                where ProveedorID = {$_POST['CheckProveedorID']}";

      if( !$ProveedoresRes = mysqli_query( $Conn, $Query ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=3120" );
                 //No Puede select
        exit();
      }

      if( mysqli_num_rows( $ProveedoresRes ) != 1 )
      {
        header( "Location: MensajeError.php?Errno=3121" );
        mysqli_free_result( $ProveedoresRes );
                 // No Tiene proveedores a Desplegar
        exit();
      }

      $ProveedorRec = mysqli_fetch_array( $ProveedoresRes );
    }
    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
				<p style=\"text-align:center; color:white;\"
                   class=\"SubTitleFont\">";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
	  if( $__LANG__ == 'en' )
        $Block .="Edit a Vendor in the Catalog";
	  else
        $Block .="Editar un Proveedor en el Catálogo";
    else
	  if( $__LANG__ == 'en' )
        $Block .=  "Add a New Vendor to the Catalog";
	  else
        $Block .=  "Agregar un Proveedor Nuevo al Catálago";
    $Block .= " </p>

                <table style=\"margin:auto; color:white;\" class=\"LargeTextFont\">
                  <tr>
                    <td colspan=\"4\">";
    if( $__LANG__ == 'en' )
      $Block .=      "<em>*</em> Vendor Name&nbsp;&nbsp;";
	else
      $Block .=      "<em>*</em> Proveedor Nombre&nbsp;&nbsp;";
    $Block .=        "<input type=\"text\" name=\"CatProvNombre\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Nombre']}\"
                             readonly=\"readonly\" ";
    $Block .=               "size=\"35\" maxlength=\"75\" />
                    </td>
                    <td colspan=\"2\">
                      RFC
                      <input type=\"text\" name=\"CatProvRFC\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['RFC']}\" ";
    $Block .=               "size=\"16\" maxlength=\"15\" />
                    </td>
                  </tr>
                  <tr>
					<td colspan=\"2\">";
    if( $__LANG__ == 'en' )
      $Block .=      "Address&nbsp;&nbsp;";
	else
      $Block .=      "Dirección&nbsp;&nbsp;";
    $Block .=        "<input type=\"text\" name=\"CatProvDireccion\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Direccion']}\" ";
    $Block .=               "size=\"20\" maxlength=\"50\" />
                    </td>
                    <td colspan=\"2\">";
    if( $__LANG__ == 'en' )
      $Block .=      "City&nbsp;&nbsp;";
	else
      $Block .=      "Ciudad&nbsp;&nbsp;";
    $Block .=        "<input type=\"text\" name=\"CatProvCiudad\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Ciudad']}\" ";
    $Block .=               "size=\"20\" maxlength=\"50\" />
                    </td>
                    <td colspan=\"2\">
                      Colonia&nbsp;&nbsp;
                      <input type=\"text\" name=\"CatProvColonia\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Colonia']}\" ";
    $Block .=               "size=\"20\" maxlength=\"35\" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan=\"2\">";
    if( $__LANG__ == 'en' )
      $Block .=      "State&nbsp;&nbsp;";
	else
      $Block .=      "Estado&nbsp;&nbsp;";
    $Block .=        "<input type=\"text\" name=\"CatProvEstado\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Estado']}\" ";
    $Block .=               "size=\25\" maxlength=\"25\" />
                    </td>
                    <td colspan=\"2\">";
    if( $__LANG__ == 'en' )
      $Block .=      "Country&nbsp;&nbsp;";
	else
      $Block .=      "Pais&nbsp;&nbsp;";
    $Block .=        "<input type=\"text\" name=\"CatProvPais\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Pais']}\" ";
    $Block .=               "size=\"15\" maxlength=\"15\" />
                    </td>
					<td colspan=\"2\">";
    if( $__LANG__ == 'en' )
      $Block .=      "Zip Code&nbsp;&nbsp;";
	else
      $Block .=      "Código Postal&nbsp;&nbsp;";
    $Block .=        "<input type=\"text\" name=\"CatProvCP\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['CP']}\" ";
    $Block .=               "size=\"9\" maxlength=\"8\" />
                    </td>
                  </tr>
                  <tr>
					<td colspan=\"2\">
                      Tel 1&nbsp;&nbsp;
                      <input type=\"text\" name=\"CatProvTel1\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Tel1']}\" ";
    $Block .=               "size=\"15\" maxlength=\"20\" />
                    </td>
                    <td colspan=\"2\">
                      Tel 2&nbsp;&nbsp;
                      <input type=\"text\" name=\"CatProvTel2\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Tel2']}\" ";
    $Block .=               "size=\"15\" maxlength=\"20\" />
                    </td>
                    <td colspan=\"2\">
                      URL&nbsp;&nbsp;
                      <input type=\"text\" name=\"CatProvURL\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['URL']}\" ";
    $Block .=               "size=\"25\" maxlength=\"70\" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan=\"6\" style=\"text-align:center;\" >
                      <script type=\"text/javascript\">
                        function limitText(limitField, limitCount, limitNum)
                        {
                          if (limitField.value.length > limitNum)
                          {
                            limitField.value =
                                  limitField.value.substring(0, limitNum);
                          }
                          else
                          {
                            limitCount.value =
                                  limitNum - limitField.value.length;
                          }
                        }
                      </script>

					  <br />";
    if( $__LANG__ == 'en' )
      $Block .=      "Observations";
	else
      $Block .=      "Observaciones";
    $Block .=        "<br />
                      <textarea name=\"Observaciones\" cols=\"60\" rows=\"5\"
                      onkeydown=\"limitText(this.form.Observaciones,this.form.countdown,500);\" ";
    if( isset( $ProveedorRec['Observaciones'] ) )
      $Block .=        "onkeyup=\"limitText(this.form.Observaciones,this.form.countdown,500);\">{$ProveedorRec['Observaciones']}</textarea> ";
    else
      $Block .=        "onkeyup=\"limitText(this.form.Observaciones,this.form.countdown,500);\"></textarea> ";
    $Block .=        "<br />";
    if( $__LANG__ == 'en' )
      $Block .=      "You have <input readonly=\"readonly\" ";
	else
      $Block .=      "Tienes <input readonly=\"readonly\" ";
    $Block .=                 "type=\"text\" size=\"4\"
                               style=\"text-align:center;\"
                               name=\"countdown\" value=\"500\" />";
    if( $__LANG__ == 'en' )
      $Block .=      " characters left";
	else
      $Block .=      " characteres restante";
    $Block .=      "</td>
                  </tr>
                  <tr>
                    <td colspan=\"6\" style=\"text-align:center;\">
                      <input type=\"checkbox\" name=\"Desactivado\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" &&
        @$ProveedorRec['Desactivado'] == 'Y' )
      $Block .=             "checked=\"checked\" ";
    $Block .=                        "/>";
    if( $__LANG__ == 'en' )
      $Block .=      "&nbsp;Vendor Inactive";
	else
      $Block .=      "&nbsp;Proveedor Desactivado";
    $Block .=      "</td>
                  </tr>
                  <tr class=\"LargeTextFont\" style=\"text-align:center;\">
                    <td colspan=\"6\">
                 <br />";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .= "<input type=\"hidden\" name=\"EditarProveedorID\"
                        value=\"{$_POST['CheckProveedorID']}\" />";
    $Block .=    "<input type=\"submit\" name=\"SubmitProveedorNuevo\" ";
    if( $__LANG__ == 'en' )
      $Block .=         "value=\"A P P L Y\" class=\"LargeTextFont\" />";
	else
      $Block .=         "value=\"A p l i c a r\" class=\"LargeTextFont\" />";
    $Block .=      "</td>
                  </tr>
                  <tr>
                    <td colspan=\"6\">
                      <hr />
                    </td>
                  </tr>
                  <tr>
                    <td colspan=\"3\">";
    if( $__LANG__ == 'en' )
      $Block .=      "Contact 1 Name&nbsp;&nbsp;";
	else
      $Block .=      "Contacto 1 Nombre&nbsp;&nbsp;";
    $Block .=        "<input type=\"text\" name=\"CatProvContacto1Nombre\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Contacto1Nombre']}\" ";
    $Block .=               "size=\"30\" maxlength=\"50\" />
                    </td>
                    <td colspan=\"3\">";
    if( $__LANG__ == 'en' )
      $Block .=      "&nbsp;&nbsp;&nbsp;&nbsp;Contact 1 Telephone&nbsp;&nbsp;";
	else
      $Block .=      "&nbsp;&nbsp;&nbsp;&nbsp;Contacto 1 Teléfono&nbsp;&nbsp;";
    $Block .=        "<input type=\"text\" name=\"CatProvContacto1Tel\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Contacto1Tel']}\" ";
    $Block .=               "size=\"15\" maxlength=\"20\" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan=\"2\">";
    if( $__LANG__ == 'en' )
      $Block .=      "Contact 1 Celular&nbsp;&nbsp;";
	else
      $Block .=      "Contacto 1 Celular&nbsp;&nbsp;";
    $Block .=        "<input type=\"text\" name=\"CatProvContacto1Cel\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Contacto1Cel']}\" ";
    $Block .=               "size=\"15\" maxlength=\"20\" />
                    </td>
                    <td colspan=\"4\">";
    if( $__LANG__ == 'en' )
      $Block .=      "&nbsp;&nbsp;&nbsp;Contact 1 E-Mail&nbsp;&nbsp;";
	else
      $Block .=      "&nbsp;&nbsp;&nbsp;Contacto 1 E-Mail&nbsp;&nbsp;";
    $Block .=        "<input type=\"text\" name=\"CatProvContacto1EMail\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Contacto1EMail']}\" ";
    $Block .=               "size=\"30\" maxlength=\"50\" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan=\"6\">
                      <hr />
                    </td>
                  </tr>
                  <tr>
                    <td colspan=\"3\">";
    if( $__LANG__ == 'en' )
      $Block .=      "Contact 2 Name&nbsp;&nbsp;";
	else
      $Block .=      "Contacto 2 Nombre&nbsp;&nbsp;";
    $Block .=        "<input type=\"text\" name=\"CatProvContacto2Nombre\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Contacto2Nombre']}\" ";
    $Block .=               "size=\"30\" maxlength=\"50\" />
                    </td>
                    <td colspan=\"3\">";
    if( $__LANG__ == 'en' )
      $Block .=      "&nbsp;&nbsp;&nbsp;&nbsp;Contact 2 Telephone&nbsp;&nbsp;";
	else
      $Block .=      "&nbsp;&nbsp;&nbsp;&nbsp;Contacto 2 Teléfono&nbsp;&nbsp;";
    $Block .=        "<input type=\"text\" name=\"CatProvContacto2Tel\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Contacto2Tel']}\" ";
    $Block .=               "size=\"15\" maxlength=\"20\" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan=\"2\">";
    if( $__LANG__ == 'en' )
      $Block .=      "Contact 2 Celular&nbsp;&nbsp;";
	else
      $Block .=      "Contacto 2 Celular&nbsp;&nbsp;";
    $Block .=        "<input type=\"text\" name=\"CatProvContacto2Cel\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Contacto2Cel']}\" ";
    $Block .=               "size=\"15\" maxlength=\"20\" />
                    </td>
                    <td colspan=\"4\">";
    if( $__LANG__ == 'en' )
      $Block .=      "&nbsp;&nbsp;&nbsp;Contact 2 E-Mail&nbsp;&nbsp;";
	else
      $Block .=      "&nbsp;&nbsp;&nbsp;Contacto 2 E-Mail&nbsp;&nbsp;";
    $Block .=        "<input type=\"text\" name=\"CatProvContacto2EMail\" ";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .=             "value=\"{$ProveedorRec['Contacto2EMail']}\" ";
    $Block .=               "size=\"30\" maxlength=\"50\" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan=\"6\">
                      <hr />
                    </td>
                  </tr>
                  <tr class=\"LargeTextFont\" style=\"text-align:center;\">
                    <td colspan=\"6\">";
    if( @$_POST['AccionProveedorID'] == "EditarProveedorID" )
      $Block .= "<input type=\"hidden\" name=\"EditarProveedorID\"
                        value=\"{$_POST['CheckProveedorID']}\" />";
	$Block .=    "<input type=\"submit\" name=\"SubmitProveedorNuevo\" ";
	if( $__LANG__ == 'en' )
      $Block .=         "value=\"A P P L Y\" class=\"LargeTextFont\" />";
	else
      $Block .=         "value=\"A p l i c a r\" class=\"LargeTextFont\" />";
    $Block .=      "</td>
                  </tr>
                </table>
                <p style=\"text-align:center; color:white;\">";
	if( $__LANG__ == 'en' )
      $Block .=  "<em>*</em> Mandatory Field";
	else
      $Block .=  "<em>*</em> Campos Obligatorios";
    $Block .=  "</p>
              </form>";
  }

  // Agregar Proveedor STAGE 2
  // Editar  Proveedor STAGE 3
  elseif( @$_POST['SubmitProveedorNuevo'] )
  {

    if( !@$_POST['CatProvNombre'] )
    {
      header( "Location: MensajeError.php?Errno=3122" );
               //Faltan Proveedor Nombre
      exit();
    }

    $CatProvNombre = htmlspecialchars( $_POST['CatProvNombre'],
                                            ENT_QUOTES, "UTF-8" );

    if( @$_POST['CatProvRFC'] && strlen( $_POST['CatProvRFC'] ) < 12 )
    {
      header( "Location: MensajeError.php?Errno=3123" );
               //RFC demaciado pequeña
      exit();
    }
    elseif( @$_POST['CatProvRFC'] )
      $CatProvRFC = htmlspecialchars( $_POST['CatProvRFC'],
                                            ENT_QUOTES, "UTF-8" );
    else
      $CatProvRFC = NULL;

    if( @$_POST['CatProvDireccion'] )
      $CatProvDireccion = htmlspecialchars( $_POST['CatProvDireccion'],
                                            ENT_QUOTES, "UTF-8" );
    else
      $CatProvDireccion = NULL;

    if( @$_POST['CatProvCiudad'] )
      $CatProvCiudad = htmlspecialchars( $_POST['CatProvCiudad'],
                                            ENT_QUOTES, "UTF-8" );
    else
      $CatProvCiudad = NULL;

    if( @$_POST['CatProvColonia'] )
      $CatProvColonia = htmlspecialchars( $_POST['CatProvColonia'],
                                            ENT_QUOTES, "UTF-8" );
    else
      $CatProvColonia = NULL;

    if( @$_POST['CatProvEstado'] )
      $CatProvEstado = htmlspecialchars( $_POST['CatProvEstado'],
                                            ENT_QUOTES, "UTF-8" );
    else
      $CatProvEstado = NULL;

    if( @$_POST['CatProvPais'] )
      $CatProvPais = htmlspecialchars( $_POST['CatProvPais'],
                                            ENT_QUOTES, "UTF-8" );
    else
      $CatProvPais = NULL;

    if( @$_POST['CatProvCP'] && strlen( $_POST['CatProvCP'] ) < 5 )
    {
      header( "Location: MensajeError.php?Errno=3124" );
                 //CP invalido
      exit();
    }
    elseif( @$_POST['CatProvCP'] )
      $CatProvCP = htmlspecialchars( $_POST['CatProvCP'],
                                            ENT_QUOTES, "UTF-8" );
    else
      $CatProvCP = NULL;


    if( @$_POST['CatProvTel1'] && !IsValidTel( $_POST['CatProvTel1'] ) )
    {
      header( "Location: MensajeError.php?Errno=3125" );
                 //Caracteres invalidos en Número de Tel
      exit();
    }
    elseif( $_POST['CatProvTel1'] )
      $CatProvTel1 = $_POST['CatProvTel1'];
    else
      $CatProvTel1 = NULL;


    if( @$_POST['CatProvTel2'] && !IsValidTel( $_POST['CatProvTel2'] ) )
    {
      header( "Location: MensajeError.php?Errno=3126" );
                 //Caracteres invalidos en Número de Tel
      exit();
    }
    elseif( $_POST['CatProvTel2'] )
      $CatProvTel2 = $_POST['CatProvTel2'];
    else
      $CatProvTel2 = NULL;

    if( $_POST['CatProvURL'] )
      $CatProvURL = htmlspecialchars( $_POST['CatProvURL'],
                    ENT_QUOTES, "UTF-8" );
    else
      $CatProvURL = NULL;

    if( @$_POST['Desactivado'] )
      $CatProvDesactivado = 'Y';
    else
      $CatProvDesactivado = 'N';

    if( $_POST['CatProvContacto1Nombre'] )
      $CatProvContacto1Nombre =
                htmlspecialchars( $_POST['CatProvContacto1Nombre'],
                    ENT_QUOTES, "UTF-8" );
    else
      $CatProvContacto1Nombre = NULL;

    if( @$_POST['CatProvContacto1Tel'] &&
        !IsValidTel( $_POST['CatProvContacto1Tel'] ) )
    {
      header( "Location: MensajeError.php?Errno=3127" );
                 //Caracteres invalidos en Número de Tel
      exit();
    }
    elseif( $_POST['CatProvContacto1Tel'] )
      $CatProvContacto1Tel = $_POST['CatProvContacto1Tel'];
    else
      $CatProvContacto1Tel = NULL;

    if( @$_POST['CatProvContacto1Cel'] &&
        !IsValidTel( $_POST['CatProvContacto1Cel'] ) )
    {
      header( "Location: MensajeError.php?Errno=3128" );
                 //Caracteres invalidos en Número de Cel
      exit();
    }
    elseif( $_POST['CatProvContacto1Cel'] )
      $CatProvContacto1Cel = $_POST['CatProvContacto1Cel'];
    else
      $CatProvContacto1Cel = NULL;

    if( $_POST['CatProvContacto1EMail'] )
      $CatProvContacto1EMail =
                htmlspecialchars( $_POST['CatProvContacto1EMail'],
                    ENT_QUOTES, "UTF-8" );
    else
      $CatProvContacto1EMail = NULL;

    if( $_POST['CatProvContacto2Nombre'] )
      $CatProvContacto2Nombre =
                htmlspecialchars( $_POST['CatProvContacto2Nombre'],
                    ENT_QUOTES, "UTF-8" );
    else
      $CatProvContacto2Nombre = NULL;

    if( @$_POST['CatProvContacto2Tel'] &&
        !IsValidTel( $_POST['CatProvContacto2Tel'] ) )
    {
      header( "Location: MensajeError.php?Errno=3134" );
                 //Caracteres invalidos en Número de Tel
      exit();
    }
    elseif( $_POST['CatProvContacto2Tel'] )
      $CatProvContacto2Tel = $_POST['CatProvContacto2Tel'];
    else
      $CatProvContacto2Tel = NULL;

    if( @$_POST['CatProvContacto2Cel'] &&
        !IsValidTel( $_POST['CatProvContacto2Cel'] ) )
    {
      header( "Location: MensajeError.php?Errno=3129" );
                 //Caracteres invalidos en Número de Cel
      exit();
    }
    elseif( $_POST['CatProvContacto2Cel'] )
      $CatProvContacto2Cel = $_POST['CatProvContacto2Cel'];
    else
      $CatProvContacto2Cel = NULL;

    if( $_POST['CatProvContacto2EMail'] )
      $CatProvContacto2EMail =
                htmlspecialchars( $_POST['CatProvContacto2EMail'],
                    ENT_QUOTES, "UTF-8" );
    else
      $CatProvContacto2EMail = NULL;

    if( $_POST['Observaciones'] )
      $Observaciones =
                htmlspecialchars( $_POST['Observaciones'],
                    ENT_QUOTES, "UTF-8" );
    else
      $Observaciones = NULL;

    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3130" );
             //No puedo connect
    exit();
    }

    if( @$_POST['EditarProveedorID'] )
      $Query = "update Proveedores set
                           Nombre          = '{$CatProvNombre}',
                           RFC             = '{$CatProvRFC}',
                           Direccion       = '{$CatProvDireccion}',
                           Ciudad          = '{$CatProvCiudad}',
                           Colonia         = '{$CatProvColonia}',
                           Estado          = '{$CatProvEstado}',
                           Pais            = '{$CatProvPais}',
                           CP              = '{$CatProvCP}',
                           Tel1            = '{$CatProvTel1}',
                           Tel2            = '{$CatProvTel2}',
                           URL             = '{$CatProvURL}',
                           Desactivado     = '{$CatProvDesactivado}',
                           Contacto1Nombre = '{$CatProvContacto1Nombre}',
                           Contacto1Tel    = '{$CatProvContacto1Tel}',
                           Contacto1Cel    = '{$CatProvContacto1Cel}',
                           Contacto1EMail  = '{$CatProvContacto1EMail}',
                           Contacto2Nombre = '{$CatProvContacto2Nombre}',
                           Contacto2Tel    = '{$CatProvContacto2Tel}',
                           Contacto2Cel    = '{$CatProvContacto2Cel}',
                           Contacto2EMail  = '{$CatProvContacto2EMail}',
                           Observaciones   = '{$Observaciones}'
                  where ProveedorID = {$_POST['EditarProveedorID']}";
    else
      $Query = "insert into Proveedores values
                  ( NULL, '{$CatProvNombre}',    '{$CatProvRFC}',
                          '{$CatProvDireccion}', '{$CatProvColonia}',
                          '{$CatProvCiudad}',    '{$CatProvEstado}',
                          '{$CatProvPais}',      '{$CatProvCP}',
                          '{$CatProvTel1}',      '{$CatProvTel2}',
                          '{$CatProvURL}',
                          '{$CatProvDesactivado}',
                          '{$Observaciones}',
                          '{$CatProvContacto1Nombre}',
                          '{$CatProvContacto1Tel}',
                          '{$CatProvContacto1Cel}',
                          '{$CatProvContacto1EMail}',
                          '{$CatProvContacto2Nombre}',
                          '{$CatProvContacto2Tel}',
                          '{$CatProvContacto2Cel}',
                          '{$CatProvContacto2EMail}' )";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3131" );
               //No Puede insert o update into Proveedores
      exit();
    }

    LogIT( $Conn, $Query );
    mysqli_close( $Conn );

    $Block = "  <p class=\"SubTitleFont\" style=\"font-weight:bold;
                   color:white; text-align:center;\">";
    if( @$_POST['EditarProveedorID'] )
      if( $__LANG__ == 'en' )
        $Block .="-=&nbsp;Editing of a Vendor in the Catalog";
	  else
        $Block .="-=&nbsp;Editado de un Proveedor en el Catálogo";
    else
      if( $__LANG__ == 'en' )
        $Block .="-=&nbsp;Adding a new Vendor to the Catalog";
	  else
        $Block .="-=&nbsp;Agregado de un Proveedor Nuevo al Catálogo";
    $Block .=    "<br />";
    if( $__LANG__ == 'en' )
      $Block .=  "&nbsp;CONFIRMED&nbsp;!";
	else
      $Block .=  "&iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=    "<br /><br />
                </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                  <input  type=\"hidden\" name=\"Accion\"";
    if( @$_POST['EditarProveedorID'] )
      $Block .= "         value=\"EditarProveedores\" />";
    else
      $Block .= "         value=\"AgregarProveedores\ />";
    $Block .= "   <input type=\"submit\" name=\"Submit\"
                         class=\"SubTitleFont\"
                         style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=         "value=\"Click HERE to Continue\" />";
	else
      $Block .=         "value=\"Click AQUÍ para Continuar\" />";
    $Block .=  "</p>
              </form>";
  }

  // Editar Proveedor STAGE 1
  // Borrar Proveedor STAGE 1
  elseif( @$_POST['Accion'] == "EditarProveedor" )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3132" );
               //No puedo connect
      exit();
    }

    $Query = "select ProveedorID, Nombre, Desactivado, Observaciones
                from Proveedores order by Nombre";

    if( !$ProveedoresRes = mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3133" );
               //No Puede select
      exit();
    }

    if( ( $NumRows = mysqli_num_rows( $ProveedoresRes ) ) < 1 )
    {
      header( "Location: MensajeError.php?Errno=3134" );
      mysqli_free_result( $CatArticulosRes );
               // No Tiene Proveedores a Desplegar
      exit();
    }

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"font-weight:bold; text-align:center; color:white;\"
				   class=\"SubTitleFont\">";
    if( $__LANG__ == 'en' )
      $Block .=  "Select an Action and a Vendor to manage";
	else
      $Block .=  "Selecciona un Acción y un Proveedor a manejar";
    $Block .=  "</p>
                <table cellspacing=\"1\" cellpadding=\"0\" border=\"5\"
                       bgcolor=\"#96cf96;\"
                       style=\"text-align:center; margin-left:auto;
                               margin-right:auto;\" class=\"SmallTextFont\">
                  <tr style=\"background:#9edcff;\">
					<th style=\"white-space:nowrap;\">";
    if( $__LANG__ == 'en' )
      $Block .=      "&nbsp;VendorID&nbsp;";
	else
      $Block .=      "&nbsp;ProveedorID&nbsp;";
    $Block .=      "</th>
                    <th style=\"white-space:nowrap;\">";
    if( $__LANG__ == 'en' )
      $Block .=      "&nbsp;Name&nbsp;";
	else
      $Block .=      "&nbsp;Nombre&nbsp;";
    $Block .=      "</th>
                    <th style=\"white-space:nowrap;\">";
    if( $__LANG__ == 'en' )
      $Block .=      "&nbsp;Inactive&nbsp;";
	else
      $Block .=      "&nbsp;Desactivado&nbsp;";
    $Block .=      "</th>
                    <th style=\"white-space:nowrap;\">";
    if( $__LANG__ == 'en' )
      $Block .=      "&nbsp;Observations&nbsp;";
	else
      $Block .=      "&nbsp;Observaciones&nbsp;";
    $Block .=      "</th>
                  </tr>";
    $LineCount = 0;
    while( $Proveedor = mysqli_fetch_array( $ProveedoresRes ) )
    {
      $Block .= " <tr>
                    <td style=\"white-space:nowrap; text-align:left;\">
                      <input type=\"radio\" name=\"CheckProveedorID\"
                             value=\"{$Proveedor['ProveedorID']}\" />
                      &nbsp;{$Proveedor['ProveedorID']}&nbsp;
                    </td>
                    <td style=\"text-align:left; white-space:nowrap;\">
                      &nbsp;{$Proveedor['Nombre']}&nbsp;
                    </td>
                    <td style=\"text-align:center; white-space:nowrap;\">
                      &nbsp;{$Proveedor['Desactivado']}&nbsp;
                    </td>
                    <td style=\"text-align:left; white-space:nowrap;\">
                      &nbsp;{$Proveedor['Observaciones']}&nbsp;
                    </td>
                  </tr>";
      $LineCount++;
      if( !( $LineCount % LineasEnSeccion )  || $NumRows == $LineCount )
	  {
        $Block .= " <tr>
                      <th colspan=\"4\" style=\"white-space:nowrap;\">
                        &nbsp;&nbsp;&nbsp;&nbsp;Editar
                        <input type=\"radio\" name=\"AccionProveedorID\"
                               value=\"EditarProveedorID\"
                               checked=\"checked\" />
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"submit\" name=\"ManProveedores\" ";
        if( $__LANG__ == 'en' )
          $Block .=           "value=\"A P P L Y\" />";
		else
          $Block .=           "value=\"A P L I C A R\" />";
        $Block .=      "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type=\"radio\" name=\"AccionProveedorID\"
                               value=\"BorrarProveedorID\" />";
        if( $__LANG__ == 'en' )
          $Block .=    "Delete&nbsp;&nbsp;&nbsp;&nbsp;";
		else
          $Block .=    "Borrar&nbsp;&nbsp;&nbsp;&nbsp;";
        $Block .=    "</th>
                    </tr>";
      }
    }
    $Block .= " </table>
              </form>";
    mysqli_free_result( $ProveedoresRes );
    mysqli_close( $Conn );
  }

  // Borrar Proveedor STAGE 2
  elseif( @$_POST['AccionProveedorID'] == 'BorrarProveedorID' )
  {                            //BORRAR Proveedor
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva' );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3135" );
               //No puedo connect
      exit();
    }

    $Query = "delete from Proveedores where ProveedorID = {$_POST['CheckProveedorID']}";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=3136&Var={$_POST['CheckProveedorID']}" );
               //No Puede Borrar Proveedor
      exit();
    }

    LogIT( $Conn, $Query );

    $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
				 color:white; text-align:center;\">";
    if( $__LANG__ == 'en' )
      $Block .="-= Deleting of Vendor =-";
	else
      $Block .="-= Borrado de Proveedor =-";
    $Block .=  "<br />";
    if( $__LANG__ == 'en' )
      $Block .="&nbsp;CONFIRMED&nbsp;!";
	else
      $Block .="&iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=  "<br /><br />
              </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center;\">
                   <input  type=\"hidden\" name=\"Accion\"
                           value=\"EditarProveedor\" />
                   <input type=\"submit\" name=\"Submit\"
                          class=\"SubTitleFont\"
                          style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=          "value=\"Click HERE to Continue\" />";
	else
      $Block .=          "value=\"Click AQUÍ para Continuar\" />";
     $Block .= "</p>
              </form>";
  }
  elseif( !@$_SESSION['Nivel'] )
  {
    $Block =   "<p style=\"text-align:center; color:white;\"
                   class=\"SubTitleFont\">" .
        LOCATION_NAME .
               "</p>
                <p style=\"text-align:center; color:white;\"
                   class=\"SubTitleFont\">
                  <br />";
    if( $__LANG__  == 'en' )
      $Block .=  "Welcome to the SelvaVista© Administration Center
                  <br />
				  Please Login
				  <br />
                  <br />
                  If you were logged in, you probably were disconnected
                  <br />
				  by the Inactivity Time-out security feature
                  <br />
                  defined in includes/SelvaVistaConfig.php";
    else
      $Block .=  "Bienvenido al Centro de Administración de SelvaVista©
                  <br />
                  Por favor Login
                  <br />
                  <br />
	              Si inició una sesión, probablemente se desconectó
                  <br />
				  por la función de seguridad; &quot;Tiempo de Inactividad&quot;
                  <br />
				  definido en includes/SelvaVistaConfig.php";
  }
?>

<!DOCTYPE HTML>
  <head>
    <meta charset="UTF-8" />
    <meta name="keywords" content="SelvaVista, SelvaCabal" />
    <meta http-equiv="default-style" content="text/css" />
    <title>
      <?php echo LOCATION_NAME ?>
    </title>
    <link rel="stylesheet" type="text/css" href="includes/SelvaVista.css" />
  </head>
  <body>
    <div class="content">
      <?php
        if( @$_SESSION[ 'PHPSESSID'] )
        {
          if( @$_GET["LANG"] == 'es'  )
          {
            $_SESSION["LANG"] = 'es';
            $__LANG__ = 'es';
          }
          elseif( @$_GET["LANG"] == 'en' )
          {
            $_SESSION["LANG"] = 'en';
            $__LANG__ = 'en';
          }
        }

        if( $__LANG__ == 'en' || $SESSION[''] == 'en' )
        {        
          echo( "<p style=\"text-align: right\">
                   <img src=\"imagenes/usa.gif\" alt=\"usa.gif\" />
                     <a style=\"color:white;\" href=\"{$_SERVER['SCRIPT_NAME']}?LANG=es\">Español</a>
                   <img src=\"imagenes/mexico.gif\" alt=\"mexico.gif\" />
                 </p>" );
        }
        else
        {
          echo( "<p style=\"text-align: right\">
                  <img src=\"imagenes/mexico.gif\" alt=\"mexico.gif\" />
                    <a style=\"color:white;\" href=\"{$_SERVER['SCRIPT_NAME']}?LANG=en\">English</a>
                  <img src=\"imagenes/usa.gif\" alt=\"usa.gif\" /> 
                 </p>" );
        }

          require( "Menu.php" );

          echo
            "<form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\">
                <div class=\"SubMenu\" style=\"margin-top:-50px;\">
                  <p style=\"margin-left:44px;\">
					<span style=\"color:white;\" class=\"LargeTextFont\">";
          if( $__LANG__ == 'en' )
            echo     "Add to the Catalog: ";
		  else
            echo     "Agregar al Catálogo: ";
          echo     "</span>
                    &nbsp;
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\" ";
          if( $__LANG__ == 'en' )
            echo          "value=\"Families\" />&nbsp;";
          else
            echo          "value=\"Familias\" />&nbsp";
          echo     "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\" ";
          if( $__LANG__ == 'en' )
            echo          "value=\"Names\" />&nbsp;";
          else
            echo          "value=\"Nombres\" />&nbsp;";
          echo     "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\" ";
          if( $__LANG__ == 'en' )
            echo          "value=\"Photos\" />&nbsp;";
		  else
            echo          "value=\"Fotos\" />&nbsp;";
          echo     "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\" ";
          if( $__LANG__ == 'en' )
            echo          "value=\"Locations\" />&nbsp;";
          else
            echo          "value=\"Ubicaciones\" />&nbsp;";
          echo     "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\" ";
          if( $__LANG__ == 'en' )
            echo          "value=\"Notes\" />&nbsp;";
          else
            echo          "value=\"Notas\" />&nbsp;";
          echo     "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\" ";
          if( $__LANG__ == 'en' )
            echo          "value=\"Vendors\" />&nbsp;";
          else
            echo          "value=\"Proveedores\" />&nbsp;";
          echo     "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\" ";
          if( $__LANG__ == 'en' )
            echo          "value=\"Common Names\" />";
          else
            echo          "value=\"Nombres Vulgares\" />";
          echo   "<p style=\"margin-left:44px; margin-top:-10px;\">
                    <span style=\"color:white;\" class=\"LargeTextFont\">";
		  if( $__LANG__ == 'en' )
		    echo     "Edit/Delete in the Catalog:&nbsp;&nbsp;";
		  else
		    echo     "Editar/Borrar al Catálogo:&nbsp;&nbsp;";
          echo     "</span>
                    &nbsp;<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
								   background:#91cfff;\" ";
          if( $__LANG__ == 'en' )
            echo          "value=\"Family\" />&nbsp;";
		  else
            echo          "value=\"Familia\" />&nbsp;";
          echo     "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\" ";
          if( $__LANG__ == 'en' )
            echo          "value=\"Name\" />&nbsp;";
		  else
            echo          "value=\"Nombre\" />&nbsp;";
          echo     "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
								   background:#91cfff;\" ";
          if( $__LANG__ =='en' )
            echo          "value=\"Photo\" />&nbsp;";
		  else
            echo          "value=\"Foto\" />&nbsp;";
		  echo     "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\" ";
          if( $__LANG__ =='en' )
            echo          "value=\"Location\" />&nbsp;";
          else
            echo          "value=\"Ubicación\" />&nbsp;";
          echo     "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\" ";
          if( $__LANG__ =='en' )
            echo          "value=\"Note\" />&nbsp;";
          else
            echo          "value=\"Nota\" />&nbsp;";
          echo     "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\" ";
          if( $__LANG__ =='en' )
            echo          "value=\"Vendor\" />&nbsp;";
          else
            echo          "value=\"Proveedor\" />&nbsp;";
          echo     "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\" ";
          if( $__LANG__ =='en' )
            echo          "value=\"Common Name\" />";
          else
            echo          "value=\"Nombre Vulgar\" />";
          echo   "</p>
                </div>
              </form>";
        if( isset( $Block ) )
          echo( "$Block" );
        echo   "<p style=\"text-align:center; color:#ffffff;\">
                  <br />";
        if( $__LANG__ == 'en' )
		  echo( "SelvaVista© v. " . VERSION .
		        " Copyright 2012-2020 by Richard Couture" );
        else
		  echo( "SelvaVista© v. " . VERSION .
		        " Copyright 2012-2020 por Richard Couture" );
        echo     "<br />
                  rrc@LinuxCabal.mx
                  <br />
                  <img src=\"imagenes/gplv3-127x51.png\" alt=\"gplv3-127x51.png\" />
                </p>";
      ?>
    </div>
  </body>
</html>

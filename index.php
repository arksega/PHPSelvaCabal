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
  require_once( "includes/SelvaVistaConfig.php" );
  include( "includes/SelvaVistaEnviron.inc.php" );
  require_once( "/etc/GoSelvaVista.inc.php" );

  if( $_SERVER['SERVER_PORT'] != 443 )
  {
    header( "Location: " . SSLURL );
    exit();
  }

  // <<<---- Manejar Usuarios ---->>>>
  if( @$_POST['Accion'] == "DesplegarMenú" )
  {
    header( "Location: " . INICIOURL );
    exit();
  }

  if( @$_POST['Accion'] == "Usuarios" || @$_POST['Accion'] == "Users" )
  {
    header( "Location: " . USUARIOSURL );
    exit();
  }

  if( @$_POST['Accion'] == "Catálogos" || @$_POST['Accion'] == "Catalogs")
  {
    header( "Location: " . CATALOGOURL );
    exit();
  }

  if( @$_GET['Accion'] == "LogOut" || @$_POST['Accion'] == "LogOut" )
    DestruyeSession();

  if( @$_GET['Accion'] == "Sitio" || @$_GET['Accion'] == "Site" )
    header( "Location: {$_SERVER['PHP_SELF']}" );

  if( @$_POST['Accion'] == "Logs" || @$_POST['Submit'] == "Mostrar Logs" ||
      @$_POST['Submit'] == "Show Logs" )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva', MYSQLI_CLIENT_SSL );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=1007" );
               //No puedo connect
      exit();
    }

    if( @$_POST['Accion'] == "Logs" )
    {
      $Query = "select Tiempo, Query, Login from Logs
                inner join Usuarios on UsuarioID = UID
                order by Tiempo DESC limit 1000";
    }
	elseif( @$_POST['Submit'] == "Mostrar Logs" ||
	        @$_POST['Submit'] == "Show Logs" )
    {
      $Query = "select Tiempo, Query, Login from Logs
                inner join Usuarios on UsuarioID = UID";
      if( $_POST['LogUsuario'] )
        $Query .= " where UsuarioID = {$_POST['LogUsuario']}";

      if( $_POST['LogTipo'] )
        if( $_POST['LogUsuario'] )
          $Query .= " and";
        else
          $Query .= " where";
        switch( $_POST['LogTipo'] )
        {
          case "Update":
            $Query .= " Query like 'update %'";
          break;
          case "Insert":
            $Query .= " Query like 'insert into %'";
          break;
          case "Delete":
            $Query .= " Query like 'delete from %'";
          break;
          case "Login":
            $Query .= " Query like 'Login %'";
          break;
        }
      $Query .= " and
                Tiempo between
                 '{$_POST['LogUsuariosDelAno']}-{$_POST['LogUsuariosDelMes']}-{$_POST['LogUsuariosDelDia']}' AND '{$_POST['LogUsuariosAlAno']}-{$_POST['LogUsuariosAlMes']}-{$_POST['LogUsuariosAlDia']} 23:59:59'
                order by Tiempo DESC limit 1000";
    }

    if( !$LogRes = mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=1008" );
               //No Puede select
      exit();
    }

    if( ( $NumRows = mysqli_num_rows( $LogRes ) ) < 1 )
    {
      mysqli_free_result( $LogRes );
      header( "Location:MensajeError.php?Errno=1009" );
               // No Tiene Usuarios a mostrar
      exit();
    }

    date_default_timezone_set( 'America/Mexico_City' );
    $Today = getdate();

    $LogUsuariosQuery = "select distinct  UID, Login from Usuarios
                where Nivel = 'Capturista' OR Nivel = 'PedOrdInv'
                OR Nivel = 'Admin' order by Login;";

    if( !$LogUsuariosRes = mysqli_query( $Conn, $LogUsuariosQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=10073" );
               //No Puede select
      exit();
    }

    if( !mysqli_num_rows( $LogUsuariosRes ) )
    {
      mysqli_free_result( $LogUsuariosRes );
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=10074" );
               //No LogUsuarios a desplegar
      exit();
    }

    $Block = "<p>
                <br />";
      include( "Menu.php");
    $Block .="</p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
				<p style=\"text-align: center;color:#ffffff;\"
                   class=\"LargeTextFont\">";
    if( $__LANG__ == 'en' )
      $Block .=  "Logs for: ";
	else
      $Block .=  "Logs por: ";
    $Block .=    "<select name=\"LogUsuario\" size=\"1\">
                    <option value=\"0\">";
    if( $__LANG__ == 'en' )
      $Block .=      " = Everyone =-";
	else
      $Block .=      " = Todos =-";
    $Block .=      "</option>";

    while( $LogUsuarioRec = mysqli_fetch_array( $LogUsuariosRes ) )
      $Block .=    "<option value=\"{$LogUsuarioRec['UID']}\">
                      {$LogUsuarioRec['Login']}
                    </option>";
    $Block .=    "</select>";

    mysqli_free_result( $LogUsuariosRes );

	if( $__LANG__ == 'en' )
      $Block .=  " For Day: ";
	else
      $Block .=  " del Día: ";
    $Block .=    "<select name=\"LogUsuariosDelDia\" size=\"1\">";
      for( $i = 1; $i < 32; $i ++ )
        $Block .=  "<option value=\"$i\">
                      $i
                    </option>";
      $Block .=  "</select>";
	if( $__LANG__ == 'en' )
      $Block .=  " Month: ";
	else
      $Block .=  " Mes: ";
    $Block .=    "<select name=\"LogUsuariosDelMes\" size=\"1\">";
      for( $i = 1; $i < 13; $i ++ )
        $Block .=  "<option value=\"$i\">
                      $i
                    </option>";
      $Block .=  "</select>";
	if( $__LANG__ == 'en' )
      $Block .=  " Year: ";
	else
      $Block .=  " Año: ";
    $Block .=    "<select name=\"LogUsuariosDelAno\" size=\"1\">";
      for( $i = 2012; $i < 2037; $i ++ )
        $Block .=  "<option value=\"$i\">
                      $i
                    </option>";
      $Block .=  "</select>";
	if( $__LANG__ == 'en' )
      $Block .=  " &lt;&lt;-- Through --&gt;&gt; Day: ";
	else
      $Block .=  " &lt;&lt;-- al --&gt;&gt; Día: ";
    $Block .=    "<select name=\"LogUsuariosAlDia\" size=\"1\">";
    for( $i = 1; $i < 32; $i ++ )
    {
      $Block .=    "<option value=\"$i\" ";
      if( $Today['mday'] == $i )
        $Block .=   "selected=\"selected\" ";
      $Block .=   " >
                      $i
                    </option>";
    }
    $Block .=  "</select>";
	if( $__LANG__ == 'en' )
	  $Block .=  " Month: ";
	else
	  $Block .=  " Mes: ";
    $Block .=  "<select name=\"LogUsuariosAlMes\" size=\"1\">";
    for( $i = 1; $i < 13; $i ++ )
    {
      $Block .=  "<option value=\"$i\" ";
      switch( $Today['month'] )
      {
        case "January":
          $Month = 1;
        break;
        case "February":
          $Month = 2;
        break;
        case "March":
          $Month = 3;
        break;
        case "April":
          $Month = 4;
        break;
        case "May":
          $Month = 5;
        break;
        case "June":
          $Month = 6;
        break;
        case "July":
          $Month = 7;
        break;
        case "August":
          $Month = 8;
        break;
        case "September":
          $Month = 9;
        break;
        case "October":
          $Month = 10;
        break;
        case "November":
          $Month = 11;
        break;
        case "December":
          $Month = 12;
        break;
      }

      if( $Month == $i )
        $Block .= "selected=\"selected\" ";
      $Block .=   " >
                      $i
                    </option>";
    }
    $Block .=    "</select>";
	if( $__LANG__ == 'en' )
      $Block .=  " Year: ";
	else
      $Block .=  " Año: ";
    $Block .=    "<select name=\"LogUsuariosAlAno\" size=\"1\">";
    for( $i = 2012; $i < 2037; $i ++ )
    {
      $Block .=    "<option value=\"$i\" ";
      if( $Today['year'] == $i )
        $Block .=   "selected=\"selected\" ";
      $Block .=     ">
                      $i
                    </option>";
    }
    $Block .=    "</select>";
	if( $__LANG__ == 'en' )
      $Block .=  " of Type: ";
	else
      $Block .=  " de Tipo: ";
    $Block .=    "<select name=\"LogTipo\" size=\"1\">
                    <option value=\"0\">";
	if( $__LANG__ == 'en' )
      $Block .=      "-= All =-";
	else
      $Block .=      "-= Todos =-";
    $Block .=      "</option>
                    <option value=\"Insert\">
                      -= Insert into =-
                    </option>
                    <option value=\"Update\">
                      -= Update =-
                    </option>
                    <option value=\"Delete\">
                      -= Delete from =-
                    </option>
                    <option value=\"Login\">
                      -= Login =-
                    </option>
                  </select>
                </p>";

    $Block .=  "<p style=\"text-align: center;\" class=\"LargeTextFont\">
                  <input type=\"submit\" name=\"Submit\" ";
    if( $__LANG__ == 'en' )
      $Block .=         "value =\"Show Logs\" />";
	else
      $Block .=         "value =\"Mostrar Logs\" />";
    $Block .=  "</p>

				<p style=\"font-weight:bold; text-align: center;
                   color:#ffffff;\" class=\"SubTitleFont\">";
    if( $__LANG__ == 'en' )
      $Block .=  "Logs for the last 1000 Transactions";
	else
      $Block .=  "Log de los últimos 1000 Transacciones";
    $Block .=  "</p>

                <table border=\"1\"  bgcolor=\"#96cf96;\"
                       style=\"text-align:center; margin:auto; width:98%;
                               border-style:ridge; border-width:thick;\">
                  <tr style=\"background:#9edcff;\">
                    <th style=\"white-space:nowrap;\">";
    if( $__LANG__ == 'en' )
      $Block .=      "Date - Time";
	else
      $Block .=      "Fecha - Hora";
    $Block .=      "</th>
                    <th style=\"white-space:nowrap;\">
                     &nbsp;&nbsp;Login&nbsp;&nbsp;
                    </th>
                    <th style=\"white-space:nowrap;\">";
    if( $__LANG__ == 'en' )
      $Block .=      "Transaction";
	else
      $Block .=      "Transacción";
    $Block .=      "</th>
                  </tr>";
   while( $LogEntrada = mysqli_fetch_array( $LogRes ) )
   {
     $Block .= "  <tr>
                    <td style=\"text-align:center; white-space:nowrap;\">
                      &nbsp;{$LogEntrada['Tiempo']}&nbsp;
                    </td>
                    <td style=\"text-align:center; white-space:nowrap;\">
                      &nbsp;{$LogEntrada['Login']}&nbsp;
                    </td>
                    <td>
                      &nbsp;{$LogEntrada['Query']}&nbsp;
                    </td>
                  </tr>";
    }
    $Block .= " </table>";
    mysqli_free_result( $LogRes );
    mysqli_close( $Conn );
  }

  else
  {
  $Conn = mysqli_init();
  mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
  mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
  mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                       'goselva', MYSQLI_CLIENT_SSL );

  if( mysqli_connect_errno() )
  {
    mysqli_close( $Conn );
    header( "Location: MensajeError.php?Errno=10001" );
             //No puedo connect
    exit();
  }

  if( @$_POST['OrdenarPor'] == 'SoloUnaUbicacion' )
  {
    if( $_POST['UbicacionSelect'] == '1' )
	{
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=10098" );
             //Debe seleccionar una Ubicación
      exit(1);
    }
  }

  if( @$_POST['OrdenarPor'] == 'SoloUnaFamilia' )
  {
    $FamiliaNombre = explode( "-", $_POST['FamiliaSelect'] );
    if( $FamiliaNombre[0] == '1' )
	{
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=10099" );
             //Debe seleccionar una Familia
      exit(1);
    }
  }

  if( @$_POST['OrdenarPor'] == 'SoloUnProveedor' )
  {
    $ProveedorInfo = explode( "-", $_POST['ProveedorSelect'] );
    if( $ProveedorInfo[0] == '1' )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=11004" );
             //Debe seleccionar un Proveedor
      exit(1);
    }
  }

  date_default_timezone_set( 'America/Mexico_City' );
  $Today = getdate();

  $SelvaQuery = "select distinct Nombres.NombreID as N_NombreID,
                        Nombres.Nombre as N_Nombre,
                        Fecha,
                        Familia,
                        Fotos.Direccion as F_Direccion,
                        Fotos.HayVideo,
                        Fotos.Video,
                        Proveedores.Nombre as P_Nombre, Precio, Inactivo
                   from Nombres
                 inner join Familias
                   on Nombres.FamiliaID=Familias.FamiliaID
                 left join Fotos
                   on Nombres.NombreID = Fotos.NombreID
                 left join Proveedores
                   on Nombres.ProveedorID = Proveedores.ProveedorID
                 left join Ubicaciones
                   on Nombres.NombreID = Ubicaciones.NombreID ";
  if( @$_POST['OrdenarPor'] == 'Familia' )
          $SelvaQuery .= "where Nombres.NombreID > 1 AND !Nombres.Inactivo
                          order by Familia, N_Nombre";
  else if( @$_POST['OrdenarPor'] == 'SoloUnaUbicacion' )
  {
    $UbicacionNombre = $_POST['UbicacionSelect'];
    $SelvaQuery .= "where Ubicacion = '{$UbicacionNombre}' AND Nombres.NombreID > 1 AND !Nombres.Inactivo
                    order by N_Nombre";
  }
  else if( @$_POST['OrdenarPor'] == 'SoloUnaFamilia' )
  {
    $SelvaQuery .= "where Familia = '{$FamiliaNombre[1]}' AND Nombres.NombreID > 1 AND !Nombres.Inactivo
                    order by N_Nombre";
  }
  else if( @$_POST['OrdenarPor'] == 'SoloUnProveedor' )
  {
    $SelvaQuery .= "where Proveedores.Nombre = '{$ProveedorInfo[1]}' AND Nombres.NombreID > 1 AND !Nombres.Inactivo
                    order by N_Nombre";
  }
  elseif( @$_POST['OrdenarPor'] == 'Fecha' )
    $SelvaQuery .= "where Nombres.NombreID > 1 AND !Nombres.Inactivo
                    order by Fecha DESC";
  elseif( @$_POST['OrdenarPor'] == 'Ubicacion' )
    $SelvaQuery .= "where Nombres.NombreID > 1 AND !Nombres.Inactivo
                    order by Ubicacion DESC, N_Nombre";
  elseif( @$_POST['OrdenarPor'] == 'Inactivo' )
    $SelvaQuery .= "where Nombres.NombreID > 1 AND Nombres.Inactivo
                    order by N_Nombre";
  else
    $SelvaQuery .= "where Nombres.NombreID > 1 AND !Nombres.Inactivo
                    order by N_Nombre";

  if( !$SelvaQueryRes = mysqli_query( $Conn, $SelvaQuery ) )
  {
    mysqli_close( $Conn );
    header( "Location: MensajeError.php?Errno=10004" );
             //No Puede select
    exit();
  }

  if( !mysqli_num_rows( $SelvaQueryRes ) )
  {
    mysqli_free_result( $SelvaQueryRes );
    mysqli_close( $Conn );
    header( "Location: MensajeError.php?Errno=10005" );
             //No Plantas a desplegar
    exit();
  }

  $Block = "<div style=\"float:left; width:15%;\">
            </div>
	        <div style=\"margin-left:45%; width:70%; margin-top:20px; font-weight:bold;
                         color:white; font:24pt helvetica;\">"
	        . LOCATION_NAME .
	       "</div>";

  if( @$_GET["LANG"] == 'es' )
    $__LANG__ = 'es';
  elseif( @$_GET["LANG"] == 'en' )
    $__LANG__ = 'en';

  $Block .=  "<p style=\"text-align: center; color:white\" class=\"SubTitleFont\">";

  if( @$_POST['OrdenarPor'] == 'Familia' )
    if( $__LANG__ == 'en' )
      $Block .= "Sorted by Family";
	else
      $Block .= "Ordenado por Familia";

  else if( @$_POST['OrdenarPor'] == 'SoloUnaUbicacion' )
    if( $__LANG__ == 'en' )
      $Block .= "In Location: &quot;{$_POST['UbicacionSelect']}&quot;";
    else
      $Block .= "En la Ubicacion: &quot;{$_POST['UbicacionSelect']}&quot;";

  else if( @$_POST['OrdenarPor'] == 'SoloUnaFamilia' )
  {
    $FamiliaNombre = explode( "-", $_POST['FamiliaSelect'] );
    if( $__LANG__ == 'en' )
      $Block .= "In the Family: &quot;{$FamiliaNombre[1]}&quot;";
	else
      $Block .= "En la Familia: &quot;{$FamiliaNombre[1]}&quot;";
  }
  else if( @$_POST['OrdenarPor'] == 'SoloUnProveedor' )
  {
    $ProveedorInfo = explode( "-", $_POST['ProveedorSelect'] );
    if( $__LANG__ == 'en' )
      $Block .= "By Vendor: &quot;{$ProveedorInfo[1]}&quot;";
	else
      $Block .= "Por Proveedor: &quot;{$ProveedorSelect[1]}&quot;";
  }
  else if( @$_POST['OrdenarPor'] == 'Fecha' )
  {
    if( $__LANG__ == 'en' )
      $Block .=  "Inverse sorted by Acquisition Date";
    else
      $Block .=  "Inverso ordenado por Fecha de Adquisición";
  }
  else if( @$_POST['OrdenarPor'] == 'Inactivo' )
  {
    if( $__LANG__ == 'en' )
      $Block .=  "Disabled Plants";
    else
      $Block .=  "Plantas Inactivados";
  }
  else
    if( $__LANG__ == 'en' )
      $Block .=  "Sorted by Genus / Species";
    else
      $Block .=  "Ordenado por Genero / Especie";

  $Block .=  "</p>
            <form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\">
              <p class=\"LargeTextFont\" style = \"text-align:center;
                                         color:white;\">
                <input type=\"radio\" name=\"OrdenarPor\"
                       style=\"margin-left:20px;\" ";
  if( @$_POST['OrdenarPor'] == 'Genero' || !@$_POST['OrdenarPor'] )
    $Block .=                        "checked=\"checked\" ";
  $Block .=                          "value=\"Genero\" />";
  if( $__LANG__ == 'en')
    $Block .=    "by Genus/Species";
  else
    $Block .=    "por Genero/Especie";

  $Block .=    "&nbsp;&nbsp;<input type=\"radio\" name=\"OrdenarPor\" ";
  if( @$_POST['OrdenarPor'] == 'Familia' )
    $Block .=                        "checked=\"checked\" ";
  $Block .=                          "value=\"Familia\" />";
  if( $__LANG__ == 'en' )
    $Block .=    "by Family";
  else
    $Block .=    "por Familia";

  $Block .=    "<input type=\"radio\" name=\"OrdenarPor\"
                       style=\"margin-left:20px;\" ";
  if( @$_POST['OrdenarPor'] == 'Fecha' )
    $Block .=                        "checked=\"checked\" ";
  $Block .=                          "value=\"Fecha\" />";
  if( $__LANG__ == 'en' )
    $Block .=    "by Date";
  else
	  $Block .=    "por Fecha&nbsp;&nbsp;";

  $Block .=    "<input type=\"radio\" name=\"OrdenarPor\"
                       style=\"margin-left:20px;\" ";
  if( @$_POST['OrdenarPor'] == 'Inactivo' )
    $Block .=                        "checked=\"checked\" ";
  $Block .=                          "value=\"Inactivo\" />";
  if( $__LANG__ == 'en' )
    $Block .=    "Disabled";
  else
    $Block .=    "Inactivo";
  $Block .=  "</p>

              <p class=\"LargeTextFont\" style = \"text-align:center;
                                         color:white;\">";

  $UbicacionesQuery = "select distinct Ubicacion from Ubicaciones
                       order by Ubicacion";

  if( !$UbicacionesRes = mysqli_query( $Conn, $UbicacionesQuery ) )
  {
    mysqli_close( $Conn );
    header( "Location: MensajeError.php?Errno=10096" );
             //No Puede select
    exit();
  }

  if( !mysqli_num_rows( $UbicacionesRes ) )
  {
    header( "Location:MensajeError.php?Errno=10097" );
    mysqli_free_result( $UbicacionesRes );
           // No Tiene Ubicaciones a mostrar
    exit();
  }

  $Block .=    "<input type=\"radio\" name=\"OrdenarPor\"
                       style=\"margin-left:20px;\" ";
  if( @$_POST['OrdenarPor'] == 'SoloUnaUbicacion' )
    $Block .=                        "checked=\"checked\" ";
  $Block .=                          "value=\"SoloUnaUbicacion\" />";
  if( $__LANG__ == 'en' )
    $Block .=  "in Location&nbsp;";
  else
    $Block .=  "en la Ubicación&nbsp;";
  $Block .=    "<select name=\"UbicacionSelect\"
                        size=\"1\" class=\"LargeTextFont\">

                  <option value=\"1\">";
  if( $__LANG__ == 'en' )
	$Block .=      "Select a Location";
  else
    $Block .=      "Selecciona una Ubicación";
  $Block .=      "</option> ";
  while( $UbicacionRec = mysqli_fetch_array( $UbicacionesRes ) )
  {
    $Block .=    "<option value=\"{$UbicacionRec['Ubicacion']}\" ";
    if( @$_POST['UbicacionSelect'] ==
        "{$UbicacionRec['Ubicacion']}" )
      $Block .=          "selected ";
    $Block .= ">
                    {$UbicacionRec['Ubicacion']}
                  </option> ";
  }
  $Block .=    "</select>";

  $FamiliaQuery = "select * from Familias order by Familia";

  if( !$FamiliaRes = mysqli_query( $Conn, $FamiliaQuery ) )
  {
    mysqli_close( $Conn );
    header( "Location: MensajeError.php?Errno=10002" );
             //No Puede select
    exit();
  }

  if( !mysqli_num_rows( $FamiliaRes ) )
  {
    header( "Location:MensajeError.php?Errno=10003" );
    mysqli_free_result( $FamiliaRes );
           // No Tiene Familias a mostrar
    exit();
  }

  $Block .=    "<input type=\"radio\" name=\"OrdenarPor\"
                       style=\"margin-left:20px;\" ";
  if( @$_POST['OrdenarPor'] == 'SoloUnaFamilia' )
    $Block .=                        "checked=\"checked\" ";
  $Block .=                          "value=\"SoloUnaFamilia\" />";
  if( $__LANG__ == 'en' )
    $Block .=  "in the Family&nbsp;";
  else
    $Block .=  "en la Familia&nbsp;";
  $Block .=    "<select name=\"FamiliaSelect\"
                        size=\"1\" class=\"LargeTextFont\">
                  <option value=\"1\">";
  if( $__LANG__ == 'en' )
	$Block .=      "Select a Family";
  else
    $Block .=      "Selecciona una Familia";
  $Block .=      "</option> ";
  while( $FamiliaRec = mysqli_fetch_array( $FamiliaRes ) )
  {
    $Block .=    "<option value=\"{$FamiliaRec['FamiliaID']}-{$FamiliaRec['Familia']}\" ";
    if( @$_POST['FamiliaSelect'] ==
        "{$FamiliaRec['FamiliaID']}-{$FamiliaRec['Familia']}" )
      $Block .=          "selected ";
    $Block .= ">
                    {$FamiliaRec['Familia']}
                  </option> ";
  }

  $Block .=    "</select>
              </p>";

  if( @$_SESSION['Nivel'] == 'Admin' )
  {
    $ProveedoresQuery = "select ProveedorID, Nombre from Proveedores
                         order by Nombre";

    if( !$ProveedoresRes = mysqli_query( $Conn, $ProveedoresQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=11003" );
             //No Puede select
      exit();
    }

    if( !mysqli_num_rows( $ProveedoresRes ) )
    {
      header( "Location:MensajeError.php?Errno=11004" );
      mysqli_free_result( $ProveedoresRes );
           // No Tiene Proveedores a mostrar
      exit();
    }

    $Block .="<p class=\"LargeTextFont\" style = \"text-align:center;
                                         color:white;\">
                <input type=\"radio\" name=\"OrdenarPor\"
                       style=\"margin-left:20px;\" ";
    if( @$_POST['OrdenarPor'] == 'SoloUnProveedor' )
      $Block .=                      "checked=\"checked\" ";
    $Block .=                        "value=\"SoloUnProveedor\" />";
    if( $__LANG__ == 'en' )
      $Block .=  "from Vendor&nbsp;";
    else
      $Block .=  "del Proveedor&nbsp;";
    $Block .=  "<select name=\"ProveedorSelect\"
                        size=\"1\" class=\"LargeTextFont\">
                  <option value=\"1\">";
    if( $__LANG__ == 'en' )
      $Block .=    "Select a Vendor";
    else
      $Block .=    "Selecciona un Proveedor";
    $Block .=    "</option> ";

    while( $ProveedorRec = mysqli_fetch_array( $ProveedoresRes ) )
    {
      $Block .=  "<option value=\"{$ProveedorRec['ProveedorID']}-{$ProveedorRec['Nombre']}\" ";
      if( @$_POST['ProveedorSelect'] ==
           "{$ProveedorRec['ProveedorID']}-{$ProveedorRec['Nombre']}" )
        $Block .=        "selected ";
      $Block .= ">
                    {$ProveedorRec['Nombre']}
                  </option> ";
    }
    $Block .=  "</select>
              </p>";
  }

  $Block .=  "<p style=\"text-align:center; color:#ffffff;\">
		<input type=\"submit\" style=\"font-weight:bold;\"
                       value=\" ";
  if( $__LANG__ == 'en' )
    $Block .=    "Refresh Screen\" />";
  else
    $Block .=    "Presione para reordenar\" />";
  $Block .=  "</p>
              <p style=\"text-align:center; color:#ffffff;\">";
  if( $__LANG__ == "en" )
    $Block .=  "Please advise us of any errors at ";
  else
    $Block .=  "Por favor informe errores de cualquier tipo a ";
  $Block .=    "<a href=\"mailto:rrc@selva.cabal.mx\" style=\"color:#88ff88;\">
	                      rrc@selva.cabal.mx</a>";
  if( $__LANG__ == "en" )
    $Block .=    " in order to improve the quality of the site.";
  else
    $Block .=    " para aumentar la calidad del sitio.";
  $Block .=  "</p>
            </form>
              <table  bgcolor=\"#96cf96;\"
                      style=\"text-align:center; border:1px solid black;
                              margin:auto; width:98%; border-style:ridge;
                              border-width:thick;\">
                <tr style=\"background:#9edcff;\">
				  <th style=\"white-space:nowrap;\" class=\"LargeTextFont\">";
 if( $__LANG__ == 'en' )
    $Block .=      "Genus, Species and Photo";
   else
    $Block .=      "Genero, Especie y Foto";
  $Block .=      "</th>
                  <th style=\"white-space:nowrap;\" class=\"LargeTextFont\">
                    <div style=\"border-bottom:1px solid black;\">";
 if( $__LANG__ == 'en' )
    $Block .=        "Family";
   else
    $Block .=        "Familia";
  $Block .=        "</div>
					<div style=\"border-bottom:1px solid black;\">";
  if( $__LANG__ == 'en' )
   $Block .=         "Location(s)";
  else
    $Block .=        "Ubicación(es)";
  $Block .=        "</div>
                    <div style=\"border-bottom:1px solid black;\">";
  if( $__LANG__ == 'en' )
    $Block .=        "Common Name(s) / Sinonym(s)";
  else
    $Block .=        "Nombre(s) Vulgar(es) / Sinónimo(s)";
  $Block .=        "</div>";
  if( @$_SESSION['Nivel'] == 'Admin' )
  {
    $Block .=      "<div style=\"border-bottom:1px solid black;\">";
    if( $__LANG__ == 'en' )
	  $Block .=      "Provider";
    else
      $Block .=      "Proveedor";
    $Block .=      "</div>
                    <div style=\"border-bottom:1px solid black;\">";
    if( $__LANG__ == 'en' )
      $Block .=      "Price";
    else
      $Block .=      "Precio";
    $Block .=      "</div>";
  }
  else
    $Block .=      "<div>";
    if( $__LANG__ == 'en' )
      $Block .=      "Adquisition Date";
    else
      $Block .=      "Fecha de Adquisición";
  $Block .=        "</div>
                  </th>
                  <th class=\"LargeTextFont\"
                      style=\"min-width:500px;\">";
  if( $__LANG__ == 'en' )
	$Block .=      "Notes";
  else
    $Block .=      "Notas";
  $Block .=      "</th>
                </tr>";
  while( $SelvaQueryRec = mysqli_fetch_array( $SelvaQueryRes ) )
  {
    $NombreID = $SelvaQueryRec['N_NombreID'];
    $Block .=  "<tr>
                  <td style=\"background-color:#77aa44;
                              color:#ffffff;\"
                      class=\"SubTitleFont\">
                    &nbsp;{$SelvaQueryRec['N_Nombre']}&nbsp;
					<br />";
	if( $SelvaQueryRec['HayVideo'] )
      $Block .=    "<video width=\"1000\" height=\"500\"
					  poster=\"{$SelvaQueryRec['F_Direccion']}\" controls>
					  <source src=\"{$SelvaQueryRec['Video']} \"
					          type=\"video/mp4\">
					  <source src=\"{$SelvaQueryRec['Video']} \"
					          type=\"video/webm\">
                    </video>";
    else
      $Block .=  "<img src=\"{$SelvaQueryRec['F_Direccion']}\"
	                   alt=\"{$SelvaQueryRec['F_Direccion']}\">";
    $Block .=    "</td>
                  <td style=\"background-color:#66aa33;
                              white-space:nowrap;\"
                       class=\"LargeTextFont\">
                    <div style=\"border-bottom:1px solid;\">
                      <p style=\"margin-bottom:33%;\">
                        &nbsp;&nbsp;{$SelvaQueryRec['Familia']}&nbsp;&nbsp;
                      </p>
                    </div>
                    <div style=\"border-bottom:1px solid;\">
                      <ul>";
    $UbicacionesQuery = "SELECT Ubicacion FROM Ubicaciones
                            WHERE NombreID = $NombreID";
    if( !$UbicacionesRes = mysqli_query( $Conn, $UbicacionesQuery ) )
      $Block .=        "&nbsp;";
    else
      while( $UbicacionRec = mysqli_fetch_array( $UbicacionesRes ) )
        $Block .=      "<li style=\"text-align:left;\">
                          &nbsp;&nbsp;{$UbicacionRec['Ubicacion']}&nbsp;&nbsp;<br />
                        </li>";
    $Block .=        "</ul>

                    </div>
                    <div style=\"border-bottom:1px solid;\">
                      <ul>";
    $NombreVulgarQuery = "SELECT NombreVulgar FROM NombresVulgares
                            WHERE NombreID = $NombreID";
    if( !$NombresVulgaresRes = mysqli_query( $Conn, $NombreVulgarQuery ) )
      $Block .=        "&nbsp;";
    else
      while( $NombreVulgarRec = mysqli_fetch_array( $NombresVulgaresRes ) )
        $Block .=      "<li style=\"text-align:left;\">
                          &nbsp;&nbsp;{$NombreVulgarRec['NombreVulgar']}&nbsp;&nbsp;<br />
                        </li>";
    $Block .=        "</ul>

                    </div>";
  if( @$_SESSION['Nivel'] == 'Admin' )
    $Block .=      "<div style=\"border-bottom:1px solid;\">
                      <p>
                        &nbsp;&nbsp;{$SelvaQueryRec['Fecha']}&nbsp;&nbsp;
                      </p>
                    </div>
                    <div style=\"border-bottom:1px solid;\">
                      <p>
                        &nbsp;&nbsp;{$SelvaQueryRec['P_Nombre']}&nbsp;&nbsp;
                      </p>
                    </div>
                    <div>
                      <p>
                        &nbsp;&nbsp;{$SelvaQueryRec['Precio']}&nbsp;&nbsp;
                      </p>
                    </div>";
  else
    $Block .=      "<div>
                      <p>
                        &nbsp;&nbsp;{$SelvaQueryRec['Fecha']}&nbsp;&nbsp;
                      </p>
                    </div>";
  $Block .=      "</td>
                  <td style=\"background-color:#44aa11;\"
                      class=\"LargeTextFont\">
                    <ul style=\"text-align:left;\">";
    $NotasQuery = "SELECT Nota FROM Notas WHERE NombreID = $NombreID";
    if( !$NotasQueryRes = mysqli_query( $Conn, $NotasQuery ) )
      $Block .=      "&nbsp;";
    else
      while( $NotasQueryRec = mysqli_fetch_array( $NotasQueryRes ) )
        $Block .=    "<li>
                        {$NotasQueryRec['Nota']}<br />
                      </li>";
    $Block .=     " </ul>
                  </td>
                </tr>";
  }
  $Block .= " </table>";
  mysqli_free_result( $SelvaQueryRes );
  mysqli_close( $Conn );
  }
?>

<!DOCTYPE HTML>
  <head>
    <meta charset="UTF-8" />
    <meta name="keywords" content="SelvaVista, SelvaCabal" />
    <meta http-equiv="default-style" content="text/css" />
    <script type="text/javascript">
      function ReadOnlyCheckBox()
      {
        return false;
      }
    </script>

    <title>
    <?php echo LOCATION_NAME ?>
    </title>
    <link rel="stylesheet" type="text/css" href="includes/SelvaVista.css" />
    <style>
      input[type=text]:disabled
      {
        background: #ffffff;
      }
    </style>
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

        if( $__LANG__ == 'en' || @$SESSION['LANG'] == 'en' )
        {        
          echo( "<p style=\"text-align: right\">
                   <img src=\"imagenes/usa.gif\" alt=\"usa.gif\" />
                     <a style=\"color:white;\" href=\"{$_SERVER['SCRIPT_NAME']}?LANG=es\">Español</a>
                   <img src=\"imagenes/mexico.gif\" alt=\"mexico.gif\" />
              <br />
			  <a style=\"color:white;\" href=\"https://goselva.cabal.mx/Manejar.php\">Login</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 </p>" );
        }
        else
        {
          echo( "<p style=\"text-align: right\">
                  <img src=\"imagenes/mexico.gif\" alt=\"mexico.gif\" />
                    <a style=\"color:white;\" href=\"{$_SERVER['SCRIPT_NAME']}?LANG=en\">English</a>
                  <img src=\"imagenes/usa.gif\" alt=\"usa.gif\" /> 
              <br />
			  <a style=\"color:white;\" href=\"https://selva.cabal.mx/Manejar.php\">Login</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 </p>" );
        }

        if( @$_SESSION['Nivel'] )
          require( "Menu.php" );
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

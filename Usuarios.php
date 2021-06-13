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

  if( $_SERVER['SERVER_PORT'] != 443 )
  {
    header( "Location: " . SSLURL );
    exit();
  }

  @session_start();

  if( @$_GET["LANG"] == 'es' )
  {
    $_SESSION["LANG"] = 'es';
    $__LANG__ = 'es';
  }
  elseif( @$_GET["LANG"] == 'en'  )
  {
    $_SESSION["LANG"] = 'en';
    $__LANG__ = 'en';
  }

// <<<--- APLICAR PWD --->>>>

  if( @$_POST['Submit'] && @$_POST['Accion'] == "AplicarPWD" )
  {
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva', MYSQLI_CLIENT_SSL );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2001" );
      exit();
    }

    $Query = "select PWD from Usuarios where UID = '{$_SESSION['UID']}'";

    if( !$QueryRes = mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2002" );
               //No Puede select
      exit();
    }

    $PWDRec = mysqli_fetch_Array( $QueryRes );
    $PCur = $PWDRec['PWD'];
    mysqli_free_result( $QueryRes );

    #if( sha1( $_POST['PCur'] ) != $PCur )
	if( !password_verify( $_POST['PCur'] ,$PCur ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2003" );
               //PWD No está Valido
      exit();
    }

    if( @$_POST['AgregarUsuarios'] )
    {
      $Query = "select UID from Usuarios
                       where Login = '{$_POST['Login']}'";
      if( !$QueryRes = mysqli_query( $Conn, $Query ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2004" );
                 //No Puede select
        exit();
      }

      if( mysqli_num_rows( $QueryRes ) )
      {
        mysqli_free_result( $QueryRes );
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2005" );
                 //Cuenta Existe
        exit();
      }

      mysqli_free_result( $QueryRes );

      if( !@$_POST['ApellidoPaterno'] || !@$_POST['Nombres']
       || !@$_POST['Nivel']  || !@$_POST['Login']
       || !@$_POST['PNueva'] || !@$_POST['PVer'] )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2006" );
                 //Faltan Campos Obligatorios
        exit();
      }
    }
    elseif( @$_POST['EditarUsuarioID'] )
    {
      if( @$_POST['PNueva'] || @$_POST['PVer'] )
      {
        if( !@$_POST['ApellidoPaterno'] || !@$_POST['Nombres']
         || !@$_POST['Nivel'] || !@$_POST['UsuarioID']
         || !@$_POST['Login'] || !@$_POST['PNueva'] || !@$_POST['PVer'] )
        {
          mysqli_close( $Conn );
          header( "Location: MensajeError.php?Errno=2008" );
                   //Faltan Campos Obligatorios
          exit();
        }
      }
      else
      {
        if( !@$_POST['ApellidoPaterno'] || !@$_POST['Nombres']
            || !@$_POST['Nivel'] || !@$_POST['UsuarioID']
            || !@$_POST['Login'] )
        {
          mysqli_close( $Conn );
          header( "Location: MensajeError.php?Errno=2009" );
                   //Faltan Campos Obligatorios
          exit();
        }
      }
    }
    else
    {
      if( !@$_POST['PNueva'] || !@$_POST['PVer'] )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2010" );
                 //Faltan Campos Obligatorios
        exit();
      }
    }

    if( @$_POST['CambiarTuPWD'] || @$_POST['AgregarUsuarios'] ||
      ( @$_POST['EditarUsuarioID'] && @$_POST['PNueva'] ) )
    {
      $PNueva = htmlspecialchars( $_POST['PNueva'], ENT_QUOTES, "UTF-8" );
      if( !IsPWDSeguro( $PNueva ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2011" );
                 //PWD no es Seguro
        exit();
      }

      if( @$_POST['PNueva'] !== $_POST['PVer'] )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2012" );
                 //Contraseñas No son Iguales
        exit();
      }
      #$PWD = sha1( $_POST['PNueva'] );
      $PWD = password_hash( $_POST['PNueva'], PASSWORD_BCRYPT );
    }

    if( @$_POST['AgregarUsuarios'] || @$_POST['ManejarTuPerfil']
     || @$_POST['EditarUsuarioID'] )
    {
      $ApellidoPaterno = htmlspecialchars( $_POST['ApellidoPaterno'],
                         ENT_QUOTES, "UTF-8" );
      $ApellidoMaterno = htmlspecialchars( $_POST['ApellidoMaterno'],
                         ENT_QUOTES, "UTF-8" );
      $Nombres = htmlspecialchars( $_POST['Nombres'], ENT_QUOTES, "UTF-8" );
      if( @$_POST['AgregarUsuarios'] )
        $Login = htmlspecialchars( $_POST['Login'], ENT_QUOTES, "UTF-8" );
      $Nivel = $_POST['Nivel'];

      if( @$_POST['Deshabilitado'] )
        $Deshabilitado = 'Y';
      else
        $Deshabilitado = 'N';

      if( @$_POST['AgregarUsuarios'] )
        $Query = "insert into Usuarios values ( NULL,
                                              '{$ApellidoPaterno}',
                                              '{$ApellidoMaterno}',
                                              '{$Nombres}',
                                              '{$Login}',
                                              '{$PWD}',
                                                CURDATE(),
                                              '{$Nivel}',
                                              '{$Deshabilitado}' )";
      else
      {
        $Query = "update Usuarios set
                                    ApellidoPaterno = '{$ApellidoPaterno}',
                                    ApellidoMaterno = '{$ApellidoMaterno}',
                                    Nombres         = '{$Nombres}',
                                    Fecha           =  CURDATE(),
                                    Nivel           = '{$Nivel}',
                                    Deshabilitado   = '{$Deshabilitado}'";
        if( @$_POST['EditarUsuarioID'] && @$_POST['PNueva'] )
          $Query .= "             , PWD =             '{$PWD}'
                       where UID = '{$_POST['UsuarioID']}'";
        elseif( @$_POST['EditarUsuarioID'] )
          $Query .= "  where UID = '{$_POST['UsuarioID']}'";
        else
          $Query .= "  where UID = '{$_SESSION['UID']}'";
      }

      if( !mysqli_query( $Conn, $Query ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2013" );
                 //No Puede insert o update
        exit();
      }
      LogIT( $Conn, $Query );
    }
    else
    {
      $Query = "update Usuarios set PWD = '{$PWD}', Fecha = CURDATE()
                  where UID = {$_SESSION['UID']}";

      if( !mysqli_query( $Conn, $Query ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2014" );
                 //No puede update PWD
        exit();
      }
      LogIT( $Conn, $Query );
    }
    mysqli_close( $Conn );

    $Block = "  <p class=\"SubTitleFont\" style=\"font-weight:bold;
                    color:#0000aa; text-align:center;\">";
    if( @$_POST['AgregarUsuarios'] )
	  if( $__LANG__ == 'en' )
        $Block .="-=&nbsp;Creation of a New Account&nbsp;=-";
	  else
        $Block .="-=&nbsp;Creación de la Cuenta Nueva&nbsp;=-";
    elseif( @$_POST['CambiarTuPWD'] )
	  if( $__LANG__ == 'en' )
        $Block .="-=&nbsp;Your Password Change&nbsp;=-";
	  else
        $Block .="-=&nbsp;Cambio de Tu Contraseña&nbsp;=-";
    elseif( @$_POST['EditarUsuarioID'] )
	  if( $__LANG__ == 'en' )
        $Block .="-=&nbsp;Editing User - ID# {$_POST['UsuarioID']}&nbsp;=-";
	  else
        $Block .="-=&nbsp;Editar de Usuario - ID# {$_POST['UsuarioID']}&nbsp;=-";
    else
	  if( $__LANG__ == 'en' )
        $Block .="-=&nbsp;Updating Your Profile&nbsp;=-";
	  else
        $Block .="-=&nbsp;Actualización de Tu Perfil&nbsp;=-";
    $Block .= "   <br />";
    if( $__LANG__ == 'en' )
      $Block .=  "&nbsp;CONFIRMED&nbsp;!";
	else
      $Block .=  "&iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=    "<br /><br />
                </p>
                  <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                    <p style=\"text-align: center;\">
                      <input  type=\"hidden\" name=\"Accion\"
                              value=\"Entra\" />
                      <input type=\"submit\" name=\"Submit\"
                             class=\"SubTitleFont\"
							 style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=             "value=\"Click HERE to Continue\" />";
	else
      $Block .=             "value=\"Click AQUÍ para Continuar\" />";
    $Block .=      "</p>
                  </form>";
  }
  elseif( @$_POST['ManUser'] && !( @$_POST['ActionUsuarioID']
       && @$_POST['CheckUsuarioID'] ) )
  {
    header( "Location: MensajeError.php?Errno=2015" );
             // Falta action y/o UID
    exit();
  }
  elseif( @$_POST['CheckUsuarioID']
       && @$_POST['ActionUsuarioID'] == 'BorrarUsuarioID' )
  {                            //BORRAR USUARIO
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva', MYSQLI_CLIENT_SSL );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2016" );
               //No puedo connect
      exit();
    }

    $Query = "delete from Usuarios where UID = {$_POST['CheckUsuarioID']}";

    if( !mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2017&Var={$_POST['CheckUsuarioID']}" );
               //No Puede Borrar Usuario
      exit();
    }

    LogIT( $Conn, $Query );

    $Block = "  <p class=\"SubTitleFont\" style=\"font-weight:bold;
				   color:white; text-align:center;\">";
    if( $__LANG__ == 'en' )
      $Block .=  "-=&nbsp;Deleting UID {$_POST['CheckUsuarioID']}&nbsp;=-
                  <br />
                  &nbsp;CONFIRMED&nbsp;!";
	else
      $Block .=  "-=&nbsp;Borrado de UID {$_POST['CheckUsuarioID']}&nbsp;=-
                  <br />
                  &iexcl;&nbsp;CONFIRMADA&nbsp;!";
    $Block .=    "<br /><br />
                </p>
                <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                  <p style=\"text-align: center;\">
                    <input  type=\"hidden\" name=\"Accion\"
                            value=\"Entra\" />
                    <input type=\"submit\" name=\"Submit\"
                           class=\"SubTitleFont\"
                           style=\"font-weight:bold; color:#0000aa;\" ";
    if( $__LANG__ == 'en' )
      $Block .=           "value=\"Click HERE to Continue\" />";
	else
      $Block .=           "value=\"Click AQUÍ para Continuar\" />";
    $Block .=    "</p>
                </form>";
  }
  elseif( @$_POST['Accion'] == 'Manejar/Borrar Usuarios' ||
          @$_POST['Accion'] == 'Manage/Delete Users' )
  {                               // <<<<---- MANEJAR USUARIOS ---->>>>
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                         'goselva', MYSQLI_CLIENT_SSL );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2018" );
               //No puedo connect
      exit();
    }

    $Query = "select * from Usuarios";

    if( !$UsuariosRes = mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=2019" );
               //No Puede select
      exit();
    }

    if( ( $NumRows = mysqli_num_rows( $UsuariosRes ) ) < 1 )
    {
      mysqli_free_result( $UsuariosRes );
      mysqli_close( $Conn );
      header( "Location:MensajeError.php?Errno=2020" );
               // No Tiene Usuarios a mostrar
      exit();
    }

    $Block = "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"font-weight:bold; text-align: center; color:#ffffff;\"
                   class=\"SubTitleFont\">";
    if( $__LANG__ == 'en' )
      $Block .=  "Select an Action and a User to manage";
    else
      $Block .=  "Selecciona un Acción y un Usuario a Manejar";
    $Block .=  "</p>
                <table border=\"1\" bgcolor=\"#96cf96\"
                       style=\"text-align:center; margin:auto;
                               width:98%; border-style:ridge;
                               border-width:thick;\">
                  <tr style=\"background:#9edcff;\">
                    <th style=\"white-space:nowrap;\">";
    if( $__LANG__ == 'en' )
      $Block .=       "&nbsp;&nbsp;UID - Login&nbsp;&nbsp;";
	else
      $Block .=       "&nbsp;&nbsp;UID - Login&nbsp;&nbsp;";
    $Block .=      "</th>
                    <th style=\"white-space:nowrap;\">";
    if( $__LANG__ == 'en' )
      $Block .=       "&nbsp;&nbsp;Last Name&nbsp;&nbsp;";
	else
      $Block .=       "&nbsp;&nbsp;Apellido Paterno&nbsp;&nbsp;";
    $Block .=      "</th>
                    <th style=\"white-space:nowrap;\">";
	if( $__LANG__ == 'en' )
      $Block .=     " &nbsp;&nbsp;Mother's Maiden Name&nbsp;&nbsp;";
	 else
      $Block .=     " &nbsp;&nbsp;Apellido Materno&nbsp;&nbsp;";
    $Block .=      "</th>
                    <th style=\"white-space:nowrap;\">";
    if( $__LANG__ == 'en' )
      $Block .=      "&nbsp;&nbsp;Name(s)&nbsp;&nbsp;";
	else
      $Block .=      "&nbsp;&nbsp;Nombre(s)&nbsp;&nbsp;";
    $Block .=      "</th>
                    <th style=\"white-space:nowrap;\">";
    if( $__LANG__ == 'en' )
      $Block .=      "&nbsp;&nbsp;Level&nbsp;&nbsp;";
	else
      $Block .=      "&nbsp;&nbsp;Nivel&nbsp;&nbsp;";
    $Block .=      "</th>
                    <th style=\"white-space:nowrap;\">";
    if( $__LANG__ == 'en' )
      $Block .=      "&nbsp;&nbsp;Disabled&nbsp;&nbsp;";
	else
      $Block .=      "&nbsp;&nbsp;Deshabilitado&nbsp;&nbsp;";
    $Block .=      "</th>
                  </tr>";
    $LineCount = 0;

    while( $Usuario = mysqli_fetch_array( $UsuariosRes ) )
    {
      if( $Usuario['Deshabilitado'] == 'Y' )
        $Block .=
                 "<tr style=\"color:#666666; background:#dddddd\">";
      else
        $Block .=
                 "<tr>";
      $Block .= "   <td style=\"white-space:nowrap; text-align:left;\">
                      <input type=\"radio\" name=\"CheckUsuarioID\"
                             value=\"{$Usuario['UID']}\" />
                      &nbsp;{$Usuario['UID']} - {$Usuario['Login']}&nbsp;
                    </td>
                    <td style=\"text-align:center; white-space:nowrap;\">
                      &nbsp;{$Usuario['ApellidoPaterno']}&nbsp;
                    </td>
                    <td style=\"text-align:center; white-space:nowrap;\">
                      &nbsp;{$Usuario['ApellidoMaterno']}&nbsp;
                    </td>
                    <td style=\"white-space:nowrap;\">
                      &nbsp;{$Usuario['Nombres']}&nbsp;
                    </td>
					<td style=\"white-space:nowrap;\">";
	  if( $Usuario['Nivel'] == "Consulta" )
		if( $__LANG__ == 'en' )
          $Block .=  "Guest";
        else
          $Block .=  "Huésped";
	  elseif( $Usuario['Nivel'] == "Admin" )
        $Block .=    "Admin";
      $Block .=    "</td>
                    <td style=\"white-space:nowrap;\">
                      &nbsp;{$Usuario['Deshabilitado']}&nbsp;
                    </td>
                  </tr>";
      $LineCount++;
      if( !( $LineCount % LineasEnSeccion )  || $NumRows == $LineCount )
      {
        $Block .=
                 "<tr style=\"background:#9edcff;\">
                    <th colspan=\"6\" style=\"white-space:nowrap;\">";
        if( $__LANG__ == 'en' )
          $Block .=  "&nbsp;&nbsp;&nbsp;&nbsp;Edit";
        else
          $Block .=  "&nbsp;&nbsp;&nbsp;&nbsp;Editar";
        $Block .=    "<input type=\"radio\" name=\"ActionUsuarioID\"
                             value=\"EditarUsuarioID\"
                             checked=\"checked\" />
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					  <input type=\"submit\" name=\"ManUser\"
                             style=\"font-weight:bold;\" ";
        if( $__LANG__ == 'en' )
          $Block .=         "value=\"A P P L Y\" />";
		else
          $Block .=         "value=\"A P L I C A R\" />";
        $Block .=    "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type=\"radio\" name=\"ActionUsuarioID\"
                             value=\"BorrarUsuarioID\" />";
        if( $__LANG__ == 'en' )
          $Block .=  "Delete&nbsp;&nbsp;&nbsp;&nbsp;";
		else
          $Block .=  "Borrar&nbsp;&nbsp;&nbsp;&nbsp;";
        $Block .=  "</th>
                  </tr>";
      }
    }
    $Block .= " </table>
              </form>";
    mysqli_free_result( $UsuariosRes );
    mysqli_close( $Conn );
  }                                        // <<<<---- UNIRLE ---->>>>
  elseif( @$_POST['Accion'] == "Agregar Usuarios" ||
          @$_POST['Accion'] == "Add Users" ||
          @$_POST['Accion'] == "Cambiar Tu Contraseña" ||
          @$_POST['Accion'] == "Change Your Password" ||
          ( @$_POST['CheckUsuarioID'] ||
            @$_POST['ActionUsuarioID'] == 'EditarUsuarioID' ) )

  {

    $Block = "  <p style=\"font-weigth:bold; color:#ffffff;
                   text-align: center;\" class=\"SubTitleFont\">";

	if( @$_POST['Accion'] == "Agregar Usuarios" ||
	   	@$_POST['Accion'] == "Add Users" )
	  if( $__LANG__ == 'en' )
        $Block .=  "Add a New User ";
	  else
        $Block .=  "Agregar un Usuario Nuevo ";
    elseif( @$_POST['Accion'] == "Cambiar Tu Contraseña" )
      $Block .=  "Cambiar Tu Contraseña";
    elseif( @$_POST['Accion'] == "Change Your Password" )
      $Block .=  "Change Your Password";
    elseif( @$_POST['ActionUsuarioID'] == 'EditarUsuarioID' )
	    if( $__LANG__ == 'en' )
          $Block .=  "Editing User - ID# {$_POST['CheckUsuarioID']}";
  	    else
          $Block .=  "Editar un Usuario - ID# {$_POST['CheckUsuarioID']}";
    else
      $Block .=  "Hay un problema aquí";

    $Block .= " </p>
              <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <table style=\"margin:auto; color:#ffffff;\" class=\"LargeTextFont\">
                  <tr>
                    <td>";
    if( $__LANG__ == 'en' )
      $Block .=      "<em>*</em> Your Current Password";
    else
      $Block .=      "<em>*</em> Tu Contraseña Ahora";
    $Block  .=     "</td>
                    <td>
                      <input type=\"password\" name=\"PCur\"
                             size=\"25\" maxlength=\"15\" />
                    </td>
                  </tr>";

	if( @$_POST['Accion'] != "Agregar Usuarios" ||
        @$_POST['Accion'] != "Add Users" )
    {
      $Conn = mysqli_init();
      mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
      mysqli_options($Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
      mysqli_real_connect( $Conn, 'localhost', $ClaseRO, $AccessTypeRO,
                           'goselva', MYSQLI_CLIENT_SSL );

      if( mysqli_connect_errno() )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2021" );
        //No puedo connect
        exit();
      }

      if( @$_POST['ActionUsuarioID'] == 'EditarUsuarioID' )
        $Query = "select * from Usuarios
          where UID = {$_POST['CheckUsuarioID']}";
      else
        $Query = "select * from Usuarios where UID = {$_SESSION['UID']}";

      if( !$QueryRes = mysqli_query( $Conn, $Query ) )
      {
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=2022" );
        //No Puede select
        exit();
      }

      $UIDRec = mysqli_fetch_Array( $QueryRes );
      mysqli_close( $Conn );
      mysqli_free_result( $QueryRes );
    }

    if( @$_POST['Accion'] == "Agregar Usuarios" ||
        @$_POST['Accion'] == "Add Users" ||
        @$_POST['ActionUsuarioID'] == "EditarUsuarioID" )
    {
      $Block .=  "<tr>
                    <td>";
      if( $__LANG__ == 'en' )
        $Block .=    "<em>*</em> Last Name";
	  else
        $Block .=    "<em>*</em> Apellido Paterno";
      $Block .=    "</td>
                    <td>
                      <input type=\"text\" name=\"ApellidoPaterno\" ";
	  if( @$UIDRec['ApellidoPaterno'] &&
		  @$_POST['ActionUsuarioID'] == "EditarUsuarioID"  )
        $Block .=             "value=\"{$UIDRec['ApellidoPaterno']}\" ";
      $Block .=               "size=\"25\" maxlength=\"15\" />
                    </td>
                  </tr>
                  <tr>
                    <td>";
      if( $__LANG__ == 'en' )
        $Block .=    "&nbsp;&nbsp;Mother's Maiden Name";
	  else
        $Block .=    "&nbsp;&nbsp;Apellido Materno";
      $Block .=    "</td>
                    <td>
                      <input type=\"text\" name=\"ApellidoMaterno\" ";
      if( @$UIDRec['ApellidoMaterno'] && @$_POST['ActionUsuarioID'] == "EditarUsuarioID" )
        $Block .=             "value=\"{$UIDRec['ApellidoMaterno']}\" ";
      $Block .=               "size=\"25\" maxlength=\"15\" />
                    </td>
                  </tr>
                  <tr>
                    <td>";
      if( $__LANG__ == 'en' )
        $Block .=    "<em>*</em> Name(s)";
	  else
        $Block .=    "<em>*</em> Nombre(s)";
      $Block .=    "</td>
                    <td>
                      <input type=\"text\" name=\"Nombres\" ";
      if( @$UIDRec['Nombres'] && @$_POST['ActionUsuarioID'] == "EditarUsuarioID" )
        $Block .=             "value=\"{$UIDRec['Nombres']}\" ";
      $Block .=               "size=\"25\" maxlength=\"20\" />
                    </td>
                  </tr>
                  <tr>
                    <td>";
      if( $__LANG__ == 'en' )
        $Block .=    "<em>*</em> Access Level";
	  else
        $Block .=    "<em>*</em> Nivel de Acceso";
      $Block .=    "</td>
                    <td>";

      switch( @$UIDRec['Nivel'] )
      {
        case "Consulta":
          $Index = 0;
        break;
        case "Admin":
          $Index = 4;
        break;
        default:
          $Index = 0;
      }
      $Block .=      "<select name=\"Nivel\" size=\"1\"
                              class=\"LargeTextFont\">
                        <option value=\"Consulta\"> ";
      if( $__LANG__ == 'en'  )
        $Block .=        "Guest";
	  else
        $Block .=        "Huésped";
      $Block .=        "</option>
                        <option value=\"Admin\" ";
	  if( $__LANG__ == 'en' )
        $Block .=        ">Administrator";
	  else
        $Block .=        ">Administrador";
      $Block .=        "</option>
                     </select>
                   </td>
                  </tr>
                  <tr>
                    <td>
                      <em>*</em> Login
                    </td>
                    <td>
                      <input type=\"text\" name=\"Login\" ";
      if( @$UIDRec['Login'] && @$_POST['ActionUsuarioID'] == "EditarUsuarioID" )
        $Block .=           "value=\"{$UIDRec['Login']}\" ";

      if( @$_POST['ActionUsuarioID'] == 'EditarUsuarioID' )
        $Block .=           "readonly=\"readonly\" ";
      $Block .=             "size=\"25\" maxlength=\"25\" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan=\"2\" style=\"text-align:center;\">
                      <input type=\"checkbox\" name=\"Deshabilitado\" ";
      if( @$UIDRec['Deshabilitado'] == 'Y' && @$_POST['ActionUsuarioID'] == "EditarUsuarioID" )
        $Block .=           "checked=\"checked\" ";
	if( $__LANG__ == 'en' )
      $Block .=        "/>Account Disabled";
	else
      $Block .=        "/>Cuenta Deshabilitada";
    $Block .=      "</td>
                  </tr>";
    }

    $Block   .=  "<tr>
                    <td>";
    if( @$_POST['ActionUsuarioID'] == 'EditarUsuarioID' )
	  if( $__LANG__ == 'en' )
        $Block   .=  "&nbsp;&nbsp;Enter a New Password";
	  else
        $Block   .=  "&nbsp;&nbsp;Contraseña Nueva";
    else
	  if( $__LANG__ == 'en' )
        $Block   .=  "<em>*</em> Enter a New Password";
	  else
        $Block   .=  "<em>*</em> Contraseña Nueva";
    $Block   .=    "</td>
                    <td>
                      <input type=\"password\" name=\"PNueva\"
                             size=\"25\" maxlength=\"15\" />
                    </td>
                  </tr>
                  <tr>
                    <td>";
    if( @$_POST['ActionUsuarioID'] == 'EditarUsuarioID' )
      if( $__LANG__ == 'en' )
        $Block   .=  "&nbsp;&nbsp;Re-enter the New Password ";
	  else
        $Block   .=  "&nbsp;&nbsp;Contraseña Nueva ";
    else
      if( $__LANG__ == 'en' )
        $Block   .=  "<em>*</em> Re-enter the New Password ";
      else
        $Block   .=  "<em>*</em> Ingrese Nuevamente la Contraseña ";
    $Block   .= "   </td>
                    <td>
                      <input type=\"password\" name=\"PVer\"
                             size=\"25\" maxlength=\"15\" />
                    </td>
                  </tr>";

	$Block .=    "<tr>
                    <td colspan=\"2\" style=\"text-align:center;
                        font-weight:bold;\">";
     if( $__LANG__ == 'en' )
      $Block .=      "<em>*</em> Mandatory Field";
	else
      $Block .=      "<em>*</em> Campos Obligatorios";
	if( @$_POST['Accion'] == "Cambiar Tu Contraseña" ||
	   	@$_POST['Accion'] == "Change Your Password" )
      $Block .=      "<input type=\"hidden\" name=\"CambiarTuPWD\"
                             value=\"1\" />";
	elseif( @$_POST['Accion'] == "Agregar Usuarios" ||
	        @$_POST['Accion'] == "Add Users" )
      $Block .=      "<input type=\"hidden\" name=\"AgregarUsuarios\"
                             value=\"1\" />";
    elseif( @$_POST['ActionUsuarioID'] == 'EditarUsuarioID' )
      $Block .=      "<input type=\"hidden\" name=\"EditarUsuarioID\"
                             value=\"1\" />";
    if( @$_POST['CheckUsuarioID'] )
      $Block .=      "<input type=\"hidden\" name=\"UsuarioID\"
                             value=\"{$_POST['CheckUsuarioID']}\" />";
    $Block .= "     </td>
                  </tr>
                  <tr>
                    <td colspan=\"2\" style=\"text-align:center;\">
                      <input type=\"hidden\" name=\"Accion\"
                             value=\"AplicarPWD\" />
                      <input type=\"submit\" name=\"Submit\"
                             style=\"font-weight:bold\" ";
    if( $__LANG__ == 'en' )
      $Block .=             "value =\"A P P L Y\" />";
	else
      $Block .=             "value =\"A P L I C A R\" />";
    $Block .=        "&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type=\"reset\" value=\"Reset\" />
                    </td>
                  </tr>
                </table>
              </form>";
  }
  elseif( !@$_SESSION['Nivel'] )
  {
    $Block =   "<p style=\"text-align:center; color:#ffffff;\"
                   class=\"SubTitleFont\">" .
		LOCATION_NAME .
               "</p>
                <p style=\"text-align:center; color:#ffffff;\"
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
				  por la función de seguridad Tiempo de Inactividad
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

        if( $__LANG__ == 'en' || $SESSION['LANG'] == 'en' )
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
                <div class=\"SubMenu\" style=\"margin-top:-50px;
                                               margin-left:75px;\">
                  <p>
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\" ";
          if( @$__LANG__ == 'en' )
            echo          "value=\"Change Your Password\" /> ";
		  else
            echo          "value=\"Cambiar Tu Contraseña\" /> ";
          if( $_SESSION['Nivel'] == 'Admin' )
		  {
            echo   " <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\" ";
            if( @$__LANG__ == 'en' )
              echo        " value=\"Add Users\" />";
            else
              echo        " value=\"Agregar Usuarios\" />";
            echo   " <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\" ";
            if( @$__LANG__ == 'en' )
              echo        "value=\"Manage/Delete Users\" />";
            else
              echo        "value=\"Manejar/Borrar Usuarios\" />";
		  }
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

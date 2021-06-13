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
  require_once( "includes/SelvaVistaEnviron.inc.php" );
  require_once( "includes/SelvaVistaConfig.php" );
  require_once( "/etc/GoSelvaVista.inc.php" );

  if( $_SERVER['SERVER_PORT'] != 443 )
  {
    header( "Location: " . SSLURL );
    exit();
  }

  if( @$_POST['Accion'] == "Usuarios" )
  {
    header( "Location: " . USUARIOSURL );
    exit();
  }

  if( @$_POST['Accion'] == "Catálogo" )
  {
    header( "Location: " . CATALOGOURL );
    exit();
  }

  if( @$_GET["LANG"] == 'en' || @$_POST["LANG"] == 'en' || @$__LANG__ == 'en' || @$_SESSION["LANG"] == 'en'  )
  {
    $_SESSION["LANG"] = 'en';
    $__LANG__ = 'en';
  }
  else
  {
    $_SESSION["LANG"] = 'es';
    $__LANG__ = 'es';
  }

  if( @$_GET['Accion'] == "LogOut" || @$_POST['Accion'] == "LogOut" )
    DestruyeSession();

  elseif( @$_POST['Accion'] == "Entra" || @$_POST['Accion'] == "Inicio" )
  {
	@session_start();
/*
	if( @$_GET["LANG"] == 'es' || @$_POST["LANG"] == 'es' || @$__LANG__ == 'es' || @$_SESSION["LANG"] == 'es'  )
    {
      $_SESSION["LANG"] = 'es';
      $__LANG__ = 'es';
	}
    else
	{
      $_SESSION["LANG"] = 'en';
      $__LANG__ = 'en';
	}

    if( $__LANG__ == 'en' )
      $Block = "<p style=\"text-align: right;\">
                  <img style=\"margin-right: -10px\"; src=\"imagenes/usa.gif\" alt=\"usa.gif\" />
                    <a style=\"color:white;\" href=\"{$_SERVER['SCRIPT_NAME']}?LANG=es\">Español</a>
                  <img src=\"imagenes/mexico.gif\" alt=\"mexico.gif\" />
                </p>";
    else
      $Block = "<p style=\"text-align: right;\">
                  <img src=\"imagenes/mexico.gif\" alt=\"mexico.gif\" />
                    <a style=\"color:white;\" href=\"{$_SERVER['SCRIPT_NAME']}?LANG=en\">English</a>
                  <img src=\"imagenes/usa.gif\" alt=\"usa.gif\" />
                </p>";
 */

	$Block =  
	       "<p style=\"text-align:center; color:#ffffff;\" class=\"SubTitleFont\">";
	if( $__LANG__ == 'en' )
      $Block .=  "Welcome to the SelvaVista© Administration Panel";
    else
      $Block .=  "Bienvenido al Centro de Administración de SelvaVista©";
    $Block .=  "</p>
                <p style=\"text-align:center; color:#ffffff;\">
                  <br />
				  SelvaVista© Versión: " . VERSION .
                 "<br />
                  Copyright 2012-2020 by Richard Couture
                  <br />
                  rrc@SelvaCabal.mx
                  <br />
                  <img src=\"imagenes/gplv3-127x51.png\" alt=\"gplv3-127x51.png\" />
                </p>";
  }                                            // <<<<---- VERIFICA ---->>>>


  elseif( @$_POST['Submit'] && @$_POST['Accion'] == "Verifica" )
  {
/*    if(isset($_POST['g-recaptcha-response']))
      $captcha=$_POST['g-recaptcha-response'];

    if(!$captcha)
    {
      header( "Location: MensajeError.php?Errno=1001" );
                 //Captcha Validation Error
      exit();
    }

    $response=file_get_contents("https://www.recaptcha.net/recaptcha/api/siteverify?secret=".$CaptchaPrivKey.$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
 */
    $Conn = mysqli_init();
    mysqli_options($Conn, MYSQLI_INIT_COMMAND, "SET AUTOCOMMIT=1");
    mysqli_options( $Conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5 );
    mysqli_real_connect( $Conn, 'localhost', $Clase, $AccessType,
                         'goselva', MYSQLI_CLIENT_SSL );

    if( mysqli_connect_errno() )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=1002" );
               //No puedo connect
      exit();
    }

    $Query = "select UID, Nombres, PWD, Nivel, Deshabilitado from Usuarios
              where Login = '{$_POST['Login']}'";

    if( !$QueryRes = mysqli_query( $Conn, $Query ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=1003" );
               //No Puedo select
      exit();
    }

    if( mysqli_num_rows( $QueryRes ) != 1 )
    {
      mysqli_free_result( $QueryRes );
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=1004" );
               //No Tienes Cuenta
      exit();
    }

    $UIDRec = mysqli_fetch_Array( $QueryRes );

    if( $UIDRec['Deshabilitado'] == 'Y' )
    {
      mysqli_free_result( $QueryRes );
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=1005" );
               // Cuenta Deshabilitada
      exit();
    }

    #if( $UIDRec['PWD'] != sha1( $_POST['PWD'] ) )
    if( !password_verify( $_POST['PWD'], $UIDRec['PWD'] ) )
    {
      mysqli_free_result( $QueryRes );
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=1006" );
               //Entrada Denegada
      exit();
    }


    setcookie( "Login", $_POST['Login'] );
    @ini_set( "session.cache_expire", SesCacheExpire );
    @ini_set( "session.cookie_lifetime", SesCookieLife );
    @ini_set( "session.gc_maxlifetime", SesGCMaxLife );
    @session_start();
/*	if( @$_GET["LANG"] == 'es' || @$__LANG__ == 'es' || @$_SESSION["LANG"] == 'es'  )
    {
      $_SESSION["LANG"] = 'es';
      $__LANG__ = 'es';
	}
    else
	{
      $_SESSION["LANG"] = 'en';
      $__LANG__ = 'en';
	}

    if( $__LANG__ == 'en' )
      $Block =
             "<img src=\"imagenes/usa.gif\" alt=\"usa.gif\" />
                <a style=\"color:white;\" href=\"{$_SERVER['SCRIPT_NAME']}?LANG=es\">Español</a>
              <img src=\"imagenes/mexico.gif\" alt=\"mexico.gif\" />";
    else
      $Block =
             "<img src=\"imagenes/mexico.gif\" alt=\"mexico.gif\" />
                <a style=\"color:white;\" href=\"{$_SERVER['SCRIPT_NAME']}?LANG=en\">English</a>
              <img src=\"imagenes/usa.gif\" alt=\"usa.gif\" />";
 */
    @$_SESSION['PHPSESSID'] = session_id();
    @$_SESSION['Login'] = $_POST['Login'];
    @$_SESSION['UID'] = $UIDRec['UID'];
    @$_SESSION['Nivel'] = $UIDRec['Nivel'];

    LogIT( $Conn, "Login por {$_POST['Login']}" );
    mysqli_free_result( $QueryRes );
    mysqli_close( $Conn );
    $Block = "<noscript>
                <p style=\"font-weight:bold; color:#0000aa;
                   text-align: center;\" class=\"SubTitleFont\">
                  -=&nbsp;Biénvenido&nbsp;{$UIDRec['Nombres']}&nbsp;=-
                </p>
              </noscript>
              <form name=\"AutoContinue\" method=\"post\"
                    action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align: center;\">
                  <input  type=\"hidden\" name=\"Accion\"
                          value=\"Entra\" />
                  <noscript>
                    <input type=\"submit\" name=\"Submit\"
                           style=\"font-weight:bold; color:#0000aa;\"
                           class=\"SubTitleFont\"
                           value=\"Presiona aquí para entrar - Debes
                                   habilitar JavaScript\" />
                  </noscript>
                </p>
              </form>
              <script type=\"text/javascript\" language=\"JavaScript\">
                <!--
                  document.AutoContinue.Accion.value = \"Entra\";
                  document.AutoContinue.submit();
                //-->
              </script>";
  }
  else //                               DESPLEGA VENTANA DE LOGIN
  {
	if( @$_GET["LANG"] == 'es' || @$__LANG__ == 'es' || @$_SESSION["LANG"] == 'es'  )
    {
      $_SESSION["LANG"] = 'es';
      $__LANG__ = 'es';
    }
    elseif( @$_GET["LANG"] == 'en' || @$__LANG__ == 'en' || @$_SESSION["LANG"] == 'en'  )
    {
      $_SESSION["LANG"] = 'en';
      $__LANG__ = 'en';
    }

    if( $__LANG__ == 'en' )
      $Block = "<p style=\"text-align:right;\">
                  <img src=\"imagenes/usa.gif\" alt=\"usa.gif\" />
                    <a style=\"color:white;\" href=\"{$_SERVER['SCRIPT_NAME']}?LANG=es\">Español</a>
                  <img src=\"imagenes/mexico.gif\" alt=\"mexico.gif\" /
                </p>";
    else
      $Block = "<p style=\"text-align:right;\">
                  <img src=\"imagenes/mexico.gif\" alt=\"mexico.gif\" />
                    <a style=\"color:white;\" href=\"{$_SERVER['SCRIPT_NAME']}?LANG=en\">English</a>
		  <img src=\"imagenes/usa.gif\" alt=\"usa.gif\" />
                </p>";
    $Block ="
            <div style=\"position: absolute; left: 50%;\">
              <div style=\"position: relative; left: -50%;\">
                <form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">
                <p style=\"text-align:center; color:#ffffff;\" class=\"TitleFont\">";
    if( @$__LANG__ == 'en' )
	  $Block .=  "SelvaVista© Administration Center";
    else
      $Block .=  "Centro de Administración de SelvaVista©";
    $Block .=  "</p>";
    $Block .=  "<p style=\"text-align:center; color:#ffffff;\" class=\"LargeTextFont\">
                  Login:&nbsp;&nbsp;<input type=\"text\" name=\"Login\"
                                           size=\"15\" maxlength=\"15\" />
                </p>
                <p style=\"text-align:center; color:#ffffff;\" class=\"LargeTextFont\">";
    if( @$__LANG__ == 'en' )
	  $Block .=  "Password:&nbsp;&nbsp;";
    else
	  $Block .=  "Contraseña:&nbsp;&nbsp;";
    $Block .=    "<input type=\"password\" name=\"PWD\"
                         size=\"10\" maxlength=\"15\" />
                  <input type=\"hidden\" name=\"Accion\" value=\"Verifica\" />
                </p>

//                <div style=\"margin: 0 auto; width:304px;\" class=\"g-recaptcha\" data-sitekey=\"{$CaptchaPubKey}\" data-theme=\"dark\"></div>

                <p style=\"text-align:center;\">
                  <input type=\"submit\" name=\"Submit\"
						 style=\"font-weight:bold\" ";
    if( @$__LANG__ == 'en' )
      $Block .=         "value =\"A P P L Y\" />";
	else
      $Block .=         "value =\"A P L I C A R\" />";
    $Block .=           "&nbsp;&nbsp;&nbsp;&nbsp;
	              <input type=\"hidden\" name=\"LANG\" value=\"$__LANG__\">
                  <input type=\"reset\" value=\"Reset\" />
                </p>
                <p style=\"text-align:center; color:#ffffff;\">
                  <br />
				  SelvaVista© Versión: " . VERSION
                  ."<br />";
    if( $__LANG__ == 'en' )
      $Block .=  "Copyright 2012-2020 by Richard Couture";
    else
      $Block .=  "Copyright 2012-2020 por Richard Couture";
    $Block .=    "<br />
				  rrc@SelvaCabal.mx
                  <br />
                  <img src=\"imagenes/gplv3-127x51.png\" alt=\"gplv3-127x51.png\" />
				</p>
              </form>
            </div>
          </div>";
  }
?>





<!DOCTYPE HTML>
  <head>
    <meta charset="UTF-8" />
    <meta name="keywords" content="SelvaCabal, SelvaVista" />
    <meta http-equiv="default-style" content="text/css" />
    <script type="text/javascript">
      function ReadOnlyCheckBox()
      {
        return false;
      }
    </script>

<?php
  /*
    if( $__LANG__ == 'en' )
      echo "<script src=\"https://www.recaptcha.net/recaptcha/api.js?hl=en\"
                         async defer>
            </script>";
    else
      echo "<script src=\"https://www.recaptcha.net/recaptcha/api.js?hl=es\"
                         async defer>
            </script>";
   */ 
?>
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

        if( @$__LANG__ == 'en' || @$SESSION[''] == 'en' )
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

        if( @$_SESSION['Nivel'] )
          require( "Menu.php" );
        if( isset( $Block ) )
          echo( "$Block" );
      ?>
    </div>
  </body>
</html>

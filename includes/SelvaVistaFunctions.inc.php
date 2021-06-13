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

  function Continuar( $Archivo, $AccionValue )
  {
    echo(      "<noscript>
                  <p style=\"font-weight:bold; color:#0000aa;
                     text-align: center;\" class=\"SubTitleFont\">
                    -=&nbsp;Continuamos&nbsp;=-
                  </p>
                </noscript>
                <form name=\"AutoContinue\" method=\"post\"
                      action=\"{$Archivo}\">
                  <p style=\"text-align: center;\">
                    <input  type=\"hidden\" name=\"Accion\"
                            value=\"{$AccionValue}\" />
                    <noscript>
                      <input type=\"submit\" name=\"Submit\"
                             style=\"font-weight:bold; color:#0000aa;\"
                             class=\"SubTitleFont\"
                             value=\"Presiona para Continuar\" />
                    </noscript>
                  </p>
                <noscript>
                  <p style=\"text-align: center;\">
                    Debes habilitar JavaScript para usar este programa
                  </p>
                </noscript>
                </form>
                <script type=\"text/javascript\" language=\"JavaScript\">
                  <!--
                    document.AutoContinue.Accion.value = \"{$AccionValue}\";
                    document.AutoContinue.submit();
                  //-->
                </script>" );
  }

  function IsValidTel( $Tel )
  {
    $ParenAbierto = 0;
    for( $i = 0; $i < strlen( $Tel ); $i++ )
    {
      if( $Tel[$i] < '0' || $Tel[$i] > '9' )
      {
        if( $i == 0 && $Tel[$i] == '+' )
          continue;

        if( $Tel[$i] == ' ' || $Tel[$i] == '-' )
          continue;

        if( $Tel[$i] == '(' & $ParenAbierto == 0 )
        {
          $ParenAbierto++;
          continue;
        }

        if( $Tel[$i] == ')' & $ParenAbierto == 1 )
        {
          $ParenAbierto--;
          continue;
        }
      }
      if( $Tel[$i] < '0' || $Tel[$i] > '9' )
      {
        return( 0 );
      }
    }
    if( $ParenAbierto || $i < 8 )
      return( 0 );
    else
      return( 1 );
  }

  function IsValidMexTel( $DeDonde, $Num, $Size )
  {
    if( strlen( $Num ) != $Size )
    {
      header( "location:MensajeError.php?Errno=2023&Var=$DeDonde" );
      exit();
    }

    for( $i = 0; $i < strlen( $Num ); $i++ )
      if( ( ( $Num[$i] < "0" ) || ( $Num[$i]> "9" ) ) && ( $Num[0] != "-" ) )
      {
        header( "location:MensajeError.php?Errno=2024&Var=$DeDonde" );
        exit();
      }
    return( 1 );
  }

  function IsValidMySQLDate( $Date )
  {
    if( strlen( $Date ) != 10 )
      return( 0 );

    if( $Date[4] != '-' && $Date[7] != '-' )
      return( 0 );

    $yy = substr( $Date, 0, 4 );

    if( $Date[5] == 0 )
      $mm = substr( $Date, 6, 1 );
    else
      $mm = substr( $Date, 5, 2 );

    if( $Date[8] == 0 )
      $dd = substr( $Date, 9, 1 );
    else
      $dd = substr( $Date, 8, 2 );

    //check year
    if( $yy >= 1900 && $yy <= 9999 )
    {
      //check month
      if( $mm >= 1 && $mm <= 12 )
      {
        //check days
        if( ( $dd >=1 && $dd <= 31 ) && ( $mm == 1 || $mm == 3 || $mm == 5 || $mm == 7 || $mm == 8 || $mm == 10 || $mm == 12 ) )
          return 1;
        else if(( $dd >= 1 && $dd <= 30) && ( $mm == 4 || $mm == 6 || $mm == 9 || $mm == 11 ) )
          return 1;
        else if( ( $dd >= 1 && $dd <= 28) && ( $mm == 2 ) )
          return 1;
        else if( $dd == 29 && $mm == 2 && ( $yy % 400 == 0 || ( $yy % 4 == 0 && $yy % 100 != 0 ) ) )
          return 1;
        else
          return 0;
      }
      else
        return 0;
    }
    else
      return 0;

    return 0;
  }

  function DestruyeSession()
  {
    @session_start();

    $_SESSION = array();
    if( isset( $_COOKIE['session_name()' ] ) )
    {
      setcookie( session_name(), '', time() - 42000, '/');
      setcookie( "Login", '', time() - 42000, '/');
    }
    session_destroy();
  }

  function IsValidCorreo( $Correo )
  {
    $Arroba = 0;
    $Punto = 0;
    $ValChar = 0;

    if( ( $Len = strlen( "$Correo" ) ) < 6 )
      return 0;

    for( $i = 0; $i < $Len; $i++ )
      if( $Correo[$i] == '@' )
      {
        $Arroba++;
        $ValChar++;
      }
      else if( $Correo[$i] == '.' )
      {
        $Punto++;
        $ValChar++;
      }
      else if( ( $Correo[$i] >= '0' && $Correo[$i] <= '9' )
            || ( $Correo[$i] >= 'a' && $Correo[$i] <= 'z' )
            || ( $Correo[$i] >= 'A' && $Correo[$i] <= 'Z' )
            ||   $Correo[$i] == '-' || $Correo[$i] == '_' )
        $ValChar++;

    if( $Arroba != 1 || $Punto == 0 || $ValChar != $Len )
      return 0;
    else
      return 1;
  }

  function IsPWDSeguro( $Password )
  {
    $Min = 0;
    $May = 0;
    $Num = 0;
    $Pun = 0;

    if( ( $Len = strlen( "$Password" ) ) < 6 )
      return 0;

    for( $i = 0; $i < $Len; $i++ )
    {
      if( $Password[$i] >= 'a' && $Password[$i] <= 'z' )
        $Min++;
      else if( $Password[$i] >= 'A' && $Password[$i] <= 'Z' )
        $May++;
      else if( $Password[$i] >= 0 && $Password[$i] <= 9 )
        $Num++;
      else
        $Pun++;
    }

    if( ( $Min && $May && $Num ) && !$Pun )
      return 1;
    else
      return 0;
  }

  function SuperTrim( $Input )
  {
    $i = $j = 0;
    $Out = $Input;

    while( $Input[$i] == ' '  || $Input[$i] == '\t' || $Input[$i] == '\n'
        || $Input[$i] == '\r' || $Input[$i] == '\0' || $Input[$i] == '\x0B' )
      $i++;
    while( $i < strlen( $Input ) )
    {
      if(    $Input[$i] != ' '  && $Input[$i] != '\t' && $Input[$i] != '\n'
          && $Input[$i] != '\r' && $Input[$i] != '\0' && $Input[$i] != '\x0B' )
        $Out[$j++] = $Input[$i++];
      else
      {
        $Out[$j++] = $Input[$i++];
        while( $Input[$i] == ' ' || $Input[$i] == '\t' || $Input[$i] == '\n'
           || $Input[$i] == '\r' || $Input[$i] == '\0' || $Input[$i] == '\x0B' )
         $i++;
      }
    }
    while( $j < strlen( $Input ) )
      $Out[$j++] = ' ';
    return( trim( $Out ) );
  }

  function DiasDesdeMySQLFecha( $Fecha )
  {
    settype( $Fecha, 'string' );
    eregi( '(....)(.)(..)(.)(..)', $Fecha, $Matches );
    array_shift( $Matches );
    foreach( array( 'year', 't1', 'month', 't2', 'day' ) as $Var )
      $$Var = array_shift( $Matches );
    $Edad =  ( mktime() - mktime( 0, 0, 0, $month, $day, $year ) ) / 86400;
    settype( $Edad, 'integer' );
    return( $Edad );
  }

  function DirList( $Dir="../ProdFotos" )
  {
    $DirRes = array();
    $Handler = opendir( $Dir );
    while ( $File = readdir( $Handler ) )
      if ( ( $File != '.' ) && ( $File != '..' ) )
        $DirRes[] = $File;
    closedir( $Handler );
    return $DirRes;
  }

  function IsValidInt( $Num=0, $Size=0 )
  {
    if( strlen( $Num ) > $Size )
      return( 0 );

    for( $i = 0; $i < strlen( $Num ); $i++ )
      if( ( ( $Num[$i] < "0" ) || ( $Num[$i]> "9" ) ) && ( $Num[0] != "-" ) )
        return( 0 );
    return( 1 );
  }

  function IsValidDouble( $Num, $Size, $Precision = 3 )
  {
    settype( $Num, "string" );
    $ExistPunto = 0;

    if( strlen( $Num ) > $Size)
      return( 0 );

    for( $i = 0; $i < strlen( $Num ); $i++ )
    {
      if( ( ( $Num[$i] < "0" ) || ( $Num[$i]> "9" ) ) && ( $Num[0] != "-" ) )
      {
        if( $Num[$i] == "." )
        {
          if( $i > $Size - $Precision - 1 )
            return( 0 );
          else
          {
            $ExistPunto++;
            $PuntoPos = $i;
          }
        }
        else
          return( 0 );
      }
    }
    if( !$ExistPunto && ( strlen( $Num ) > ( $Size - $Precision - 1 ) ) )
      return( 0 );

    if( $ExistPunto > 1 )
      return( 0 );

    if( $ExistPunto && ( ( $i - $PuntoPos -1 ) > $Precision ) )
      return( 0 );

    return( 1 );
  }

  function LogIT( $Conn, $Query, $CommitLocal = 0 )
  {
    $Query = htmlspecialchars( $Query, ENT_QUOTES, "UTF-8" );

    $LogQuery = "insert into Logs values ( NULL, NOW(), {$_SESSION['UID']},
                                          '{$Query}' )";

    if( !mysqli_query( $Conn, $LogQuery ) )
    {
      mysqli_close( $Conn );
      header( "Location: MensajeError.php?Errno=5001" );
      //No Puede insert into Logs
      exit();
    }

    if( $CommitLocal )
    {
      if( !mysqli_commit( $Conn ) )
      {
        mysqli_rollback( $Conn );
        mysqli_close( $Conn );
        header( "Location: MensajeError.php?Errno=5002" );
        exit();
      }
    }
  }

  function SetRowColor( $LastState )
  {
    if( $LastState == GROUPCOLOR1 )
      return ( GROUPCOLOR2 );
    else
      return ( GROUPCOLOR1 );
  }

  function Pesos2Palabras($Numero)
  {
    $Num = "$Numero";
    $numero = "";
    for( $i = 0; $i < strlen( $Num ); $i++ )
      if( $Num[$i] != '.' )
        $numero .= $Num[$i];
      else
      {
        if( $i + 1 < strlen( $Num ) )
        {
          $i++;
          $Centavos = "$Num[$i]";
        }
        if( $i + 1 < strlen( $Num ) )
        {
          $i++;
          $Centavos .= "$Num[$i]";
        }
        break;
      }
      if( !$Centavos )
        $Centavos = "00";
      if( $Centavos > 0 && $Centavos <= 9 )
        $Centavos *= 10;

    $unidades=array(
        'un',
        'dos',
        'tres',
        'cuatro',
        'cinco',
        'seis',
        'siete',
        'ocho',
        'nueve',
        'diez',
        'once',
        'doce',
        'trece',
        'catorce',
        'quince',
        'dieciséis',
        'diecisiete',
        'dieciocho',
        'diecinueve',
        'veinte',
        'veintiuno',
        'veintidós',
        'veintitrés',
        'veinticuatro',
        'veinticinco',
        'veintiséis',
        'veintisiete',
        'veintiocho',
        'veintinueve');

     $decenas=array(
        'diez',
        'veinte',
        'treinta',
        'cuarenta',
        'cincuenta',
        'sesenta',
        'setenta',
        'ochenta',
        'noventa');

     $centenas=array(
        'ciento',
        'doscientos',
        'trescientos',
        'cuatrocientos',
        'quinientos',
        'seiscientos',
        'setecientos',
        'ochocientos',
        'novecientos');

     // Acá iremos construyendo el número en palabras.
     $palabras="";


     if ($numero==0) { return "cero"; }
     if ($numero<0)
     {
        $palabras="menos";
        $numero=abs($numero);
     }

     $numero_str=$numero;

     while (strlen($numero_str) % 3 != 0)
     {
        $numero_str="0".$numero_str;
     }

     $grupos_centenas=str_split($numero_str, 3);
     $num_grupos=count($grupos_centenas);

     foreach ($grupos_centenas as $i)
     {
        $i_num=(int)$i;
        $cent=floor($i_num/100);
        if ($i_num>=100)
        {
           // Determinamos las centenas
           // Excepción: Si el número 100 va
           // solo, sin decenas ni unidades, es
           // "cien". Si no, es "ciento" (lo que
           // está en el array).
           if ($i_num==100)
           {
              $palabras=$palabras." cien";
           }
           else
           {
              $palabras=$palabras." ".$centenas[$cent-1];
           }
        }
        // Ahora las decenas y unidades
        $i_num=$i_num-$cent*100;
        if ($i_num<30 && $i_num>0)
        {
           $palabras=$palabras." ".$unidades[$i_num-1];
        }
        else
        {
           $dec=floor($i_num/10);
           $i_num=$i_num-$dec*10;

           if ($dec>0) { $palabras=$palabras." ".$decenas[$dec-1]; }
           if ($i_num>0) { $palabras=$palabras." y ".$unidades[$i_num-1]; }
        }

        // Finalmente, para cada grupo hay que
        // agregar el orden de magnitud correspondiente
        // (miles, millón/millones... creo que por ahora
        // basta con eso).

        switch($num_grupos)
        {
           case 2:
              // Miles
              $palabras=$palabras." mil";
              break;

           case 3:
              // Millones
              if ((int)$i==1)
              {
                 $palabras=$palabras." millón";
              }
              else
              {
                 $palabras=$palabras." millones";
              }
              break;

           case 4:
              // Miles de millones
              $palabras=$palabras." mil";
              break;

           case 5:
              // Billones
              if ((int)$i==1)
              {
                 $palabras=$palabras." billón";
              }
              else
              {
                 $palabras=$palabras." billones";
              }
              break;
        }
        $num_grupos--;
     }

     return ltrim($palabras . " pesos y {$Centavos}/100 MN" );
  }
?>

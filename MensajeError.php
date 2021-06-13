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

  require_once( "includes/SelvaVistaConfig.php" );
  require_once( "includes/SelvaVistaEnviron.inc.php" );

  $Block = "<p class=\"SubTitleFont\" style=\"font-weight:bold;
               color:#0000aa; text-align:center;\">";
  switch( $_GET['Errno'] )
  {
    case 1001:
      $Block .= "No puedo verificar tu humanidad
                 <br />
                 Regressa para enviar las dos palabras que te pide
                 <br />";
      break;
    case 1002:
      $Block .= "No puedo connect a la base de datos: Selva
                        <br />";
      break;
    case 1003:
      $Block .= "No puedo select UserID de Usuarios
                        <br />";
      break;
    case 1004:
      $Block .= "&iexcl;Tu Login y/o Contraseña son invalidos!
                 <br />";
      break;
    case 1005:
      $Block .= "Tu cuenta está deshabilitada
                 <br />
                 Solo el administrador puede habilitarla
                 <br />";
      break;
    case 1006:
      $Block .= "Entrada Denegada
                        <br />
                        Tu Login y/o tu Contraseña son invalidos
                        <br />";
      break;
    case 1007:
      $Block .= "No puedo conectar a la base de datos: Selva
                        <br />";
      break;
    case 1008:
      $Block .= "No puedo select de Logs
                        <br />";
      break;
    case 1009:
      $Block .= "No tiene registros a desplegar
                 <br />";
      break;
    case 2001:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 2002:
      $Block .= "No puede select PWD de Usuarios
                        <br />";
      break;
    case 2003:
      $Block .= "&iexcl;Tu contraseña es invalida!
                        <br />";
      break;
    case 2004:
      $Block .= "No puedo select de Usuarios
                        <br />";
      break;
    case 2005:
      $Block .= "Un usuario con este nombre existe
                 <br />";
      break;
    case 2006:
      $Block .= "&iexcl;Faltan Campos Obligatorios!
                        <br />";
      break;
    case 2007:
      $Block .= "&iexcl;Faltan Campos Obligatorios!
                        <br />";
      break;
    case 2008:
      $Block .= "&iexcl;Faltan Campos Obligatorios!
                        <br />";
      break;
    case 2009:
      $Block .= "&iexcl;Faltan Campos Obligatorios!
                 <br />";
      break;
    case 2010:
      $Block .= "&iexcl;Faltan Campos Obligatorios!
                        <br />";
      break;
    case 2011:
      $Block .= "&iexcl;El Password no es aceptable!
                        </p>
                        <p style=\"font-weight:bold; color:#000090;
                           text-align:center;\" class=\"LargeTextFont\">
                        Tu Password debe tener:
                        </p>
                        <p style=\"font-weight:bold; color:#000090;
                           text-align:center;\" class=\"LargeTextFont\">
                          6 caracteres, como minimo
                        </p>
                        <p style=\"font-weight:bold; color:#000090;
                           text-align:center;\" class=\"LargeTextFont\">
                          que son una combinación de
                        </p>
                        <p style=\"font-weight:bold; color:#000090;
                           text-align:center;\" class=\"LargeTextFont\">
                          minúsculas,
                          <br />
                          MAYÚSCULAS,
                          <br />
                          y
                          <br />
                          Números
                          <br />
                          No se permite otra tipos de caracteres
                          <br />";
      break;
    case 2012:
      $Block .= "El \"Password Nuevo\" y el \"Verificar Password\" no son
                 iguales
                 <br />";
      break;
    case 2013:
      $Block .= "No puedo insert into o update Usuarios
                 <br />";
      break;
    case 2014:
      $Block .= "No puedo update Usuarios
                        <br />";
      break;
    case 2015:
      $Block .= "Debe elejir un Login
                 <br />
                 y
                 <br />
                 un Acción, Editar o Borrar, para APLICAR
                 <br />";
      break;
    case 2016:
      $Block .= "No puedo conectar a la base de datos: Selva
                        <br />";
      break;
    case 2017:
      $Block .= "No puedo Borrar UserID \"{$_GET['Var']}\" de Usuarios
                 <br />
                 Tal vez tiene registros en su nombre (Relaciones)
                 <br />";
      break;
    case 2018:
      $Block .= "No puedo conectar a la base de datos: Selva
                        <br />";
      break;
    case 2019:
      $Block .= "No puedo select UserID de Usuarios
                        <br />";
      break;
    case 2020:
      $Block .= "No tiene registros a desplegar
                 <br />";
      break;
    case 2021:
      $Block .= "No puedo conectar a la base de datos: Selva
                        <br />";
      break;
    case 2022:
      $Block .= "No puedo select de Usuarios
                        <br />";
      break;
    case 3001:
      $Block .= "Debes elejir un Acción
                 <br />";
      break;
    case 3002:
      $Block .= "Faltan CheckNombreID
                 <br />";
      break;
    case 3003:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3004:
      $Block .= "No puedo select de Familias
                 <br />";
      break;
    case 3005:
      $Block .= "No tiene Familias a desplegar
                 <br />";
      break;
    case 3006:
      $Block .= "No puedo select de Proveedores
                 <br />";
      break;
    case 3007:
      $Block .= "No tiene Proveedres a desplegar
                 <br />";
      break;
    case 3008:
      $Block .= "No puedo select de Nombres
                 <br />";
      break;
    case 3009:
      $Block .= "No tiene Nombres a desplegar
                 <br />";
      break;
    case 3010:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3011:
      $Block .= "No puedo select de Nombres
                 <br />";
      break;
    case 3012:
      $Block .= "No tiene Nombres a desplegar
                 <br />";
      break;
    case 3013:
      $Block .= "&iexcl;Faltan campos obligatorios!
                 <br />";
      break;
    case 3014:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3015:
      $Block .= "Debes elegir una Famila
                 <br />";
      break;
    case 3016:
      $Block .= "Debes elegir un Proveedor
                 <br />";
      break;
    case 3017:
      $Block .= "Fecha invalida debe ser en formato YYYY-MM-DD
				 <br />
                 0000-00-00 para indicar no fecha";
      break;
    case 3018:
      $Block .= "Precio invalida debe ser en formato nn.nn
                 <br />";
      break;
    case 3019:
      $Block .= "No puedo insert/update en Nombres
                 <br />";
      break;
    case 3020:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3021:
      $Block .= "No puedo Borrar NombreID \"{$_GET['Var']}\"
                 de Nombres
                 <br />";
      break;
    case 3022:
      $Block .= "Debe elejir una Nota
                 <br />
                 y
                 <br />
                 un Acción, Editar o Borrar, para APLICAR
                 <br />";
      break;
    case 3023:
      $Block .= "Falta CheckNotaID
                 <br />";
      break;
    case 3024:
      $Block .= "Falta CheckNotaID
                 <br />";
      break;
    case 3025:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3026:
      $Block .= "No puedo select from Nombres
                 <br />";
      break;
    case 3027:
      $Block .= "No tiene Nombres a desplegar
                 <br />";
      break;
    case 3028:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3029:
      $Block .= "No puedo select from Nombres
                 <br />";
      break;
    case 3030:
      $Block .= "No tiene Nombres a desplegar
                 <br />";
      break;
    case 3031:
      $Block .= "Falta Ubicaciòn
                 <br />";
      break;
    case 3032:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3033:
      $Block .= "No puedo insert en Ubicaciones
                 <br />";
      break;
    case 3034:
      $Block .= "Falta Nota
                 <br />";
      break;
    case 3035:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3036:
      $Block .= "No puedo insert en Notas
                 <br />";
      break;
    case 3037:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3038:
      $Block .= "No puedo select de Ubicaciones
                 <br />";
      break;
    case 3039:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3040:
      $Block .= "No puedo select de Ubicaciones
                 <br />";
      break;
    case 3041:
      $Block .= "La Ubicación que elegistes no existe
                 <br />";
      break;
    case 3042:
      $Block .= "Falta CheckUbicaciónID
                 <br />";
      break;
    case 3043:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3044:
      $Block .= "No puedo Editar UbicaciónID \"{$_GET['Var']}}\"
                 <br />";
      break;
    case 3045:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3046:
      $Block .= "No puedo delete UbicaciónID \"{$_GET['Var']}\"
                 <br />";
      break;
    case 3047:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3048:
      $Block .= "No puedo select de Notas
                 <br />";
      break;
    case 3049:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3050:
      $Block .= "No puedo select de Notas
                 <br />";
      break;
    case 3051:
      $Block .= "No Existe NotaID {$_GET['Var']}
                 <br />";
      break;
    case 3052:
      $Block .= "Falta CheckNotaID
                 <br />";
      break;
    case 3053:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3054:
      $Block .= "No pudo update NotaID {$_GET['Var']}
                 <br />";
      break;
    case 3055:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3056:
      $Block .= "No pudo Delete NotaID {$_GET['Var']}
                 <br />";
      break;
    case 3057:
      $Block .= "Debe elejir un Nombre Vulgar
                 <br />
                 y
                 <br />
                 un Acción, Editar o Borrar, para APLICAR
                 <br />";
      break;
    case 3058:
      $Block .= "Debe elejir un Nombre Vulgar
                 <br />";
      break;
    case 3059:
      $Block .= "Debe elejir un Nombre Vulgar
                 <br />";
      break;
    case 3060:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3061:
      $Block .= "No puedo select de Nombres
                 <br />";
      break;
    case 3062:
      $Block .= "No hay Nombres a despligar
                 <br />";
      break;
    case 3063:
      $Block .= "Debe elejir un Nombre Vulgar
                 <br />";
      break;
    case 3064:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3065:
      $Block .= "No puedo insert into NombresVulgares
                 <br />";
      break;
    case 3066:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3067:
      $Block .= "No puedo Select from NombresVulgares
                 <br />";
      break;
    case 3068:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3069:
      $Block .= "No puedo Select from NombresVulgares
                 <br />";
      break;
    case 3070:
      $Block .= "El NombreVulgar {$_GET['Var']} no existe
                 <br />";
      break;
    case 3071:
      $Block .= "Falta NombreVulgar
                 <br />";
      break;
    case 3072:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3073:
      $Block .= "No puedo Update NombreVulgar {$_GET['Var']}
                 <br />";
      break;
    case 3074:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3075:
      $Block .= "No puedo Borrar NombreVulgar {$_GET['Var']}
                 <br />";
      break;
    case 3076:
      $Block .= "Faltan Acción Borrar o Editar
                 <br />";
      break;
    case 3077:
      $Block .= "Faltan CheckFotoID
                 <br />";
      break;
    case 3078:
      $Block .= "Faltan CheckFotoID
                 <br />";
      break;
    case 3079:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3080:
      $Block .= "No puedo Select from Nombres
                 <br />";
      break;
    case 3081:
      $Block .= "No hay Nombres a mostrar
                 <br />";
      break;
    case 3082:
      $Block .= "Falta Foto
                 <br />";
      break;
    case 3083:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3084:
      $Block .= "No puedo Select from Fotos
                 <br />";
      break;
    case 3085:
      $Block .= "Existe este Foto; debes editarlo
                 <br />";
      break;
    case 3086:
      $Block .= "No puedo insert into Fotos
                 <br />";
      break;
    case 3087:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3088:
      $Block .= "No puedo Select from Fotos
                 <br />";
      break;
    case 3089:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3090:
      $Block .= "No puedo Select from Fotos
                 <br />";
      break;
    case 3091:
      $Block .= "El FotoID {$_GET['Var']} no existe
                 <br />";
      break;
    case 3092:
      $Block .= "No hay CheckFotoID a guardar
                 <br />";
      break;
    case 3093:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3094:
      $Block .= "No puedo Update FotoID \"{$_GET['Var']}\"
                 <br />";
      break;
    case 3095:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3096:
      $Block .= "No puedo Borrar FotoID \"{$_GET['Var']}\" de Fotos
                 <br />";
      break;
    case 3097:
      $Block .= "Falta CheckFamiliaID
                 <br />";
      break;
    case 3098:
      $Block .= "Debes elegir Borrar o Editar
                 <br />";
      break;
    case 3099:
      $Block .= "Falta CheckFamiliaID
                 <br />";
      break;
    case 3100:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3101:
      $Block .= "No puedo Select from Familias
                 <br />";
      break;
    case 3102:
      $Block .= "No hay Familias a desplegar
                 <br />";
      break;
    case 3104:
      $Block .= "No puedo Select NextID
                 <br />";
      break;
    case 3105:
      $Block .= "No Puede obtener siguiente ID de Familias
                 <br />";
      break;
    case 3106:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3107:
      $Block .= "No puedo Select from Familias
                 <br />";
      break;
    case 3108:
      $Block .= "No hay Familias a desplegar
                 <br />";
      break;
    case 3109:
      $Block .= "Falta campos
                 <br />";
      break;
    case 3110:
    case 3111:
      $Block .= "FamiliaCodigo invalido
                 <br />";
      break;
    case 3112:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3113:
      $Block .= "No puedo insert o update into Familias
                 <br />";
      break;
    case 3114:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3115:
      $Block .= "No puedo Borrar FamiliaID \"{$_GET['Var']}\" de Familias                <br />";
      break;
    case 3116:
    case 3117:
      $Block .= "Debe elejir un Proveedor
                 <br />
                 y
                 <br />
                 un Acción, Editar o Borrar, para APLICAR
                 <br />";
      break;
    case 3118:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3119:
      $Block .= "Falta CheckProveedorID
                 <br />";
      break;
    case 3120:
      $Block .= "No puedo select de Proveedores
                 <br />";
      break;
    case 3121:
      $Block .= "No hay Proveedores a desplegar
                 <br />";
      break;
    case 3122:
      $Block .= "&iexcl;Falta el Nombre del Proveedor
                 <br />";
      break;
    case 3123:
      $Block .= "&iexcl;RFC demaciado pequeña!
                 <br />";
      break;
    case 3124:
      $Block .= "&iexcl;CP demaciado pequeña!
                 <br />";
      break;
    case 3125:
    case 3126:
    case 3127:
    case 3128:
    case 3129:
      $Block .= "&iexcl;Número de Tel incorrecto!
                 <br />";
      break;
    case 3130:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3131:
      $Block .= "No Puede insert o update into Proveedores
                 <br />";
      break;
    case 3132:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3133:
      $Block .= "No puedo select de Proveedores
                 <br />";
      break;
    case 3134:
      $Block .= "No tiene Proveedores a desplegar
                 <br />";
      break;
    case 3135:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 3136:
      $Block .= "No puedo Borrar ProveedorID \"{$_GET['Var']}\" de Proveedores
                 <br />";
      break;
    case 3137:
      $Block .= "No puedo connect a la base de datos: Selva
                 <br />";
      break;
    case 3138:
      $Block .= "No puedo Nuke NombreID \"{$_GET['Var']}\"
                 <br />";
      break;
    case 3139:
      $Block .= "No se borra registros - Necesitas minimo una Nota
                 <br />";
      break;
    case 3140:
      $Block .= "Falta &quot;Hay Video&quot; o &quot;Dirección de Video&quot;
                 <br />";
      break;
    case 3141:
      $Block .= "Falta &quot;Hay Video&quot; o &quot;Dirección de Video&quot;
                 <br />";
      break;
    case 3142:
      $Block .= "Debe elegir una Planta para la Nota
                 <br />";
      break;
    case 3143:
      $Block .= "Debe elegir una Planta para el Foto
                 <br />";
      break;
    case 3144:
      $Block .= "Debe elegir una Planta para la Ubicación
                 <br />";
      break;
    case 3145:
      $Block .= "Debe elegir una Planta para el Nombre Vulgar
                 <br />";
      break;
    case 3146:
      $Block .= "Debe elegir una Planta para el Nombre Vulgar
                 <br />";
      break;
    case 3147:
      $Block .= "Debe elegir una Planta para la Nota
                 <br />";
      break;
    case 3148:
      $Block .= "Debe elegir una Planta para la Ubicación
                 <br />";
      break;
    case 3149:
      $Block .= "Debe elegir una Planta para el Foto
                 <br />";
      break;
    case 4002:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 4003:
      $Block .= "No puede select de CatArticulos
                        <br />";
      break;
    case 4004:
      $Block .= "No tiene Articulos a desplegar
                 <br />";
      break;
    case 4023:
      $Block .= "\"Pedido #\" invalido,
                 <br />
                 debe ser un número
                 <br />
                 positivo y unico.
                 <br />";
      break;
    case 4024:
      $Block .= "&iexcl;Faltan Campos Obligatorios!
                        <br />";
      break;
    case 4025:
      $Block .= "Fecha de Pedido Invalida
                 <br />
                 debe ser en formato yyyy-mm-dd
                 <br />";
      break;
    case 4026:
      $Block .= "Usuario ID Invalido
                 <br />";
      break;
    case 4027:
      $Block .= "No puedo insert into PedidosIndex
                 <br />
                 Tal vez el Número de Pedido existe
                 <br />";
      break;
    case 4028:
      $Block .= "No puedo insert into ArticulosPedidos
                 <br />";
      break;
    case 4029:
      $Block .= "No puedo Commit
                 <br />";
      break;
    case 4031:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 4035:
      $Block .= "\"Cantidad\" invalida para \"{$_GET['Var']}\",
                 <br />
                 debe ser 0 o mayor y 99999999.999 o menor
                 <br />
                 sin comas, \"-\" o comillas
                 <br />
                 no más de 3 numerales después del punto
                 <br />
                 si existe uno y solo uno punto.";
      break;
    case 4036:
      $Block .= "No puede select de PedidosIndex
                        <br />";
      break;
    case 4037:
      $Block .= "No tiene Pedidos a desplegar
                 <br />";
      break;
    case 4038:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 4039:
      $Block .= "No puede select de PedidosIndex
                        <br />";
      break;
    case 4040:
      $Block .= "No puedo desplegar su Pedido
                 <br />";
      break;
    case 4041:
      $Block .= "No puede select de PedidosIndex
                        <br />";
      break;
    case 4042:
      $Block .= "No tengo registros a mostrar
                 <br />";
      break;
    case 4043:
      $Block .= "Faltan el Número de la Factura a Recibir
                 <br />";
      break;
    case 4044:
      $Block .= "No puedo guardar sin Cantidad Recibida
                 <br />";
      break;
    case 4045:
      $Block .= "\"Cantidad Recibida\" invalida para \"{$_GET['Var']}\",
                 <br />
                 debe ser 0 o mayor y 99999999.999 o menor
                 <br />
                 sin comas, \"-\" o comillas
                 <br />
                 no más de 3 numerales después del punto
                 <br />
                 si existe uno y solo uno punto.";
      break;
    case 4046:
      $Block .= "\"Costo\" invalido para \"{$_GET['Var']},
                 debe ser 0 o mayor y 99999999.99999 o menor
                 <br />
                 sin comas, \"-\" o comillas
                 <br />
                 no más de 5 numerales después del punto
                 <br />
                 si existe uno y solo uno punto.";
      break;
    case 4047:
      $Block .= "Faltan Costo
                 <br />";
      break;
    case 4048:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 4049:
      $Block .= "No puede insert into ArticulosPedidos
                        <br />";
      break;
    case 4050:
      $Block .= "No puede Select Recibido SUM
                        <br />";
      break;
    case 4051:
      $Block .= "No podemos recibir más que ordinamos
                        <br />";
      break;
    case 4052:
      $Block .= "No podemos update PedidosIndex
                        <br />";
      break;
    case 4053:
      $Block .= "No puede update CatArticulos
                        <br />";
      break;
    case 4054:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 4055:
      $Block .= "No puedo Borrar de ArticulosPedidos
                 <br />";
      break;
    case 4055:
      $Block .= "No puedo Borrar de PedidosIndex
                 <br />";
      break;
    case 4056:
      $Block .= "No puedo Select de ArticulosPedidos
                 <br />";
      break;
    case 4057:
      $Block .= "No puedo borrar pedidos con actividad de recibo
                 <br />";
      break;
    case 4058:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 4059:
      $Block .= "No puede update PedidosIndex
                        <br />";
      break;
    case 4060:
      $Block .= "&iexcl;Faltan Proveedor!
                        <br />";
      break;
    case 4062:
      $Block .= "&iexcl;Faltan NumDePedido!
                        <br />";
      break;
    case 4063:
      $Block .= "No puede select de CatAreas
                        <br />";
      break;
    case 4064:
      $Block .= "No tiene Áreas a desplegar
                 <br />";
      break;
    case 4065:
      $Block .= "No puede select de CatAreas
                        <br />";
      break;
    case 4066:
      $Block .= "No tiene Áreas a desplegar
                 <br />";
      break;
    case 4067:
      $Block .= "&iexcl;Faltan Área!
                        <br />";
      break;
    case 4068:
      $Block .= "&iexcl;AreaID Invalido!
                        <br />";
      break;
    case 4069:
      $Block .= "&iexcl;Faltan Orden!
                        <br />";
      break;
    case 4070:
      $Block .= "&iexcl;OrdenID Invalido!
                        <br />";
      break;
    case 4071:
      $Block .= "No puede select nombre de CatProveedores/Areas/Ordenes
                        <br />";
      break;
    case 4072:
      $Block .= "No tiene Proveedor/Area/Orden nombres a desplegar
                 <br />";
      break;
    case 4073:
      $Block .= "No puede select de CatAreas
                        <br />";
      break;
    case 4074:
      $Block .= "No puedo desplegar su Área
                 <br />";
      break;
    case 4075:
      $Block .= "No puede select de CatOrdenes
                        <br />";
      break;
    case 4076:
      $Block .= "No puedo desplegar su Orden
                 <br />";
      break;
    case 4079:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 4080:
      $Block .= "No puedo desplegar su SUM
                 <br />";
      break;
    case 4081:
      $Block .= "No puede select IncAreaID
                        <br />";
      break;
    case 4082:
      $Block .= "Más de 2 o menos de 0 articulos iquales en este area
                        <br />";
      break;
    case 4083:
      $Block .= "No puedo insert into InvAreas
                        <br />";
      break;
    case 4084:
      $Block .= "No puedo disminuir disponibles en CatArticulos
                        <br />";
      break;
    case 4085:
      $Block .= "No puedo update into InvAreas
                        <br />";
      break;
    case 4086:
      $Block .= "No puedo select en CatArticulos
                        <br />";
      break;
    case 4087:
      $Block .= "No tenemos articulos a desplegar
                        <br />";
      break;
    case 4088:
      $Block .= "No tenemos suficientes articulos desponibles a recibirlo
                        <br />";
      break;
    case 4089:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 4090:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 4091:
      $Block .= "No puede select ArticulosDisponibles
                        <br />";
      break;
    case 4092:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 4093:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 4094:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 4095:
        $Block .="Tenemos solo {$_GET['VD']} Virtualmente Disponibles
                 <br />
                 Debe ordinar mínimo de " . ( $_GET['PC'] - $_GET['VD'] ) . " más del proveedor o Área
                 <br />
                 de Articulo Código \"{$_GET['Var']}\" antes de pedirlo porque
                 <br />
                 CantidadDisponibles + CantidadNuevaPedida
                 <br />
                 - CantidadNuevaRecibida - CantidadReservadaPedida
                 <br />
                 + CantidadReservadaRecibida < CantidadPedida ({$_GET['PC']})
                 <br />";
      break;
    case 4096:
      $Block .= "No puede select DesgloseOrdenID
                        <br />";
      break;
    case 4097:
      $Block .= "No puedo update into DesgloseDeOrdenes
                        <br />";
      break;
    case 4098:
      $Block .= "Más de 2 o menos de 0 proyectos iquales en este area
                        <br />";
      break;
    case 4099:
      $Block .= "No puedo select en CatArticulos
                        <br />";
      break;
    case 4100:
      $Block .= "No tenemos articulos a desplegar
                        <br />";
      break;
    case 4101:
      $Block .= "No tenemos suficientes articulos desponibles a recibirlo
                        <br />";
      break;
    case 4102:
      $Block .= "No puedo disminuir disponibles en CatArticulos
                        <br />";
      break;
    case 4104:
      $Block .= "No puede select de CatArticulos
                        <br />";
      break;
    case 4105:
      $Block .= "No tiene Artículos a desplegar
                 <br />";
      break;
    case 4106:
      $Block .= "No puede Select Observasion
                        <br />";
      break;
    case 4107:
      $Block .= "No puede update CatArticulos
                        <br />";
      break;
    case 4108:
      $Block .= "No puedo select en ContenidosDeFabricados
                        <br />";
      break;
    case 4109:
      $Block .= "No tenemos contenidos a desplegar
                        <br />";
      break;
    case 4110:
      $Block .= "No puedo select en InvAreas
                        <br />";
      break;
    case 4111:
      $Block .= "No existe este articuloID en este Area
                        <br />";
      break;
    case 4112:
      $Block .= "No tiene cantidad suficiente a recibirlo
                        <br />";
      break;
    case 4115:
      $Block .= "No puedo update into InvAreas
                        <br />";
      break;
    case 4129:
      $Block .= "No puedo Select de PedidosIndex
                 <br />";
      break;
    case 4130:
      $Block .= "No puede select de CatProveedores
                        <br />";
      break;
    case 4131:
      $Block .= "No Proveedores a mostrar
                        <br />";
      break;
    case 4132:
      $Block .= "\"Precio Cotizado\" invalido para ArticuloID \"{$_GET['Var']}\"
                 debe ser 0 o mayor y 99999999.99999 o menor
                 <br />
                 sin comas, \"-\" o comillas
                 <br />
                 no más de 5 numerales después del punto
                 <br />
                 si existe uno y solo uno punto.";
      break;
    case 4133:
      $Block .= "No puede Select Costo
                        <br />";
      break;
    case 4134:
      $Block .= "Debe seleccionar un Proveedor
                 <br />";
      break;
    case 4135:
      $Block .= "Debe elegir articulos a incluir en este Pedido
                 <br />";
      break;
    case 4136:
      $Block .= "&iexcl;ProveedorID Invalido!
                        <br />";
      break;
    case 4137:
      $Block .= "No puede select de CatProveedores
                        <br />";
      break;
    case 4138:
      $Block .= "No puedo desplegar su Proveedor
                 <br />";
      break;
    case 4139:
      $Block .= "No puede select de Usuarios
                        <br />";
      break;
    case 4140:
      $Block .= "No Usuarios a desplegar
                 <br />";
      break;
    case 4141:
      $Block .= "\"Cantidad\" invalida, para \"{$_GET['Var']}\",
                 <br />
                 debe ser 0 o mayor y 99999999.999 o menor
                 <br />
                 sin comas, \"-\" o comillas
                 <br />
                 no más de 3 numerales después del punto
                 <br />
                 si existe uno y solo uno punto.";
      break;
    case 4142:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 4143:
      $Block .= "No puede select ModelosIndex
                        <br />";
      break;
    case 4144:
      $Block .= "No Modelos Habilitados a desplegar
                 <br />";
      break;
    case 4145:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 4146:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 4147:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 4148:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 4149:
      $Block .= "No se puede declarar &quot;No Suma&quot;
                 y también &quot;Suma Sin IVA&quot;
                        <br />";
      break;
    case 5001:
      $Block .= "No puedo insert en Logs
                 <br />";
      break;
    case 5002:
      $Block .= "No puedo Commit Local
                 <br />";
      break;
    case 6001:
      $Block .= "Debe elejir un Acción para Enviar
                 <br />";
      break;
    case 6002:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 6003:
      $Block .= "Faltan SolicitudID a borrar
                 <br />";
      break;
    case 6004:
      $Block .= "No puedo Borrar de ArticulosSolicitados
                 <br />";
      break;
    case 6005:
      $Block .= "No puedo Borrar de SolicitudesIndex
                 <br />";
      break;
    case 6006:
      $Block .= "No puedo Commit
                 <br />";
      break;
    case 6007:
      $Block .= "Faltan SolicitudID a desplegar
                        <br />";
      break;
    case 6008:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 6009:
      $Block .= "No puede select de SolicitudesIndex
                        <br />";
      break;
    case 6010:
      $Block .= "No puedo desplegar su Solicitud
                 <br />";
      break;
    case 6011:
      $Block .= "No puede select de ArticulosSolicitados
                        <br />";
      break;
    case 6012:
      $Block .= "No tengo Articulos a mostrar
                 <br />";
      break;
    case 6013:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 6014:
      $Block .= "No puede select de SolicitudesIndex
                        <br />";
      break;
    case 6015:
      $Block .= "No tiene Solicitudes a desplegar
                 <br />";
      break;
    case 6016:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 6017:
      $Block .= "No puedo insert into SolicitudesIndex
                 <br />";
      break;
    case 6018:
      $Block .= "No puedo insert into ArticulosSolicitados
                 <br />";
      break;
    case 6019:
      $Block .= "No puedo commit
                 <br />";
      break;
    case 6020:
      $Block .= "&iexcl;Faltan Campos Obligatorios!
                        <br />";
      break;
    case 6021:
      $Block .= "Fecha de Pedido Invalida
                 <br />
                 debe ser en formato yyyy-mm-dd
                 <br />";
      break;
    case 6022:
      $Block .= "Usuario ID Invalido
                 <br />";
      break;
    case 6023:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 6024:
      $Block .= "\"Cantidad\" invalido para \"{$_GET['Var']}\",
                 <br />
                 debe ser 0 o mayor y 99999999.999 o menor
                 <br />
                 sin comas, \"-\" o comillas
                 <br />
                 no más de 3 numerales después del punto
                 <br />
                 si existe uno y solo uno punto.";
      break;
    case 6025:
      $Block .= "No puede select de CatArticulos
                        <br />";
      break;
    case 6026:
      $Block .= "No tiene Artículos a desplegar
                 <br />";
      break;
    case 6027:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 6028:
      $Block .= "Faltan Solicitud ID
                 <br />";
      break;
    case 6029:
      $Block .= "Faltan Solicitud Estado
                 <br />";
      break;
    case 6030:
      $Block .= "No puedo update SolicitudesIndex
                 <br />";
      break;
    case 6031:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 6032:
      $Block .= "No puedo select de CatArticulos
                 <br />";
      break;
    case 6033:
      $Block .= "No tiene registros a desplegar
                 <br />";
      break;
    case 6034:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 6035:
      $Block .= "Faltan SolicitudTipo
                 <br />";
      break;
    case 6036:
      $Block .= "\"Cantidad\" invalida para \"{$_GET['Var']}\",
                 <br />
                 debe ser 0 o mayor y 99999999.999 o menor
                 <br />
                 sin comas, \"-\" o comillas
                 <br />
                 no más de 3 numerales después del punto
                 <br />
                 si existe uno y solo uno punto.";
      break;
    case 6037:
      $Block .= "No tengo Articulos a mostrar
                 <br />";
      break;
    case 6038:
      $Block .= "No puedo select de ModelosIndex
                 <br />";
    case 6039:
      $Block .= "No tiene Modelos a desplegar
                 <br />";
      break;
    case 6040:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 6041:
      $Block .= "No puede select ModelosIndex
                        <br />";
      break;
    case 6042:
      $Block .= "No Modelos Habilitados a desplegar
                 <br />";
      break;
    case 7001:
      $Block .= "Faltan selección en menu
                 <br />";
      break;
    case 7002:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 7003:
      $Block .= "No puede select de CatOrdenes
                        <br />";
      break;
    case 7004:
      $Block .= "No tiene Ordenes a desplegar
                 <br />";
      break;
    case 7005:
      $Block .= "&iexcl;Faltan Orden!
                        <br />";
      break;
    case 7006:
      $Block .= "&iexcl;OrdenID Invalido!
                        <br />";
      break;
    case 7007:
      $Block .= "No puede select inner join de CatArticulos
                        <br />";
      break;
    case 7008:
      $Block .= "No tiene Articulos a desplegar
                 <br />";
      break;
    case 7009:
      $Block .= "Fecha de Pedido Invalida
                 <br />
                 debe ser en formato yyyy-mm-dd
                 <br />";
      break;
    case 7010:
      $Block .= "Usuario ID Invalido
                 <br />";
      break;
    case 7011:
      $Block .= "\"Cantidad\" invalido
                 <br />
                 No puedes devolver más articulos
                 <br />
                 que existe en el proyecto.
                 <br />";
      break;
    case 7012:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 7013:
      $Block .= "No puedo insert into DevolucionesIndex
                 <br />";
      break;
    case 7014:
      $Block .= "No puedo insert into ArticulosDevueltos
                 <br />";
      break;
    case 7015:
      $Block .= "No puedo Commit
                 <br />";
      break;
    case 7016:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 7017:
      $Block .= "No puede select de DevolucionesIndex
                        <br />";
      break;
    case 7018:
      $Block .= "No tiene Devoluciones a desplegar
                 <br />";
      break;
    case 7019:
      $Block .= "No puede select nombre de CatAreas/Ordenes
                        <br />";
      break;
    case 7020:
      $Block .= "No tiene Area/Orden nombres a desplegar
                 <br />";
      break;
    case 7021:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 7022:
      $Block .= "No puedo Select de ArticulosDevuletos
                 <br />";
      break;
    case 7023:
      $Block .= "No puedo borrar Devoluciones con actividad de recibo
                 <br />";
      break;
    case 7024:
      $Block .= "No puedo Borrar de ArticulosDevueltos
                 <br />";
      break;
    case 7025:
      $Block .= "No puedo Select de DevolucionesIndex
                 <br />";
      break;
    case 7026:
      $Block .= "No puedo Commit
                 <br />";
      break;
    case 7027:
      $Block .= "Faltan DevolicionID
                 <br />";
      break;
    case 7028:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 7029:
      $Block .= "No puede select de DevolucionesIndex
                 <br />";
      break;
    case 7030:
      $Block .= "No puedo desplegar su Devolucion
                 <br />";
      break;
    case 7031:
      $Block .= "No puede select iner join
                 <br />";
      break;
    case 7032:
      $Block .= "No tengo registros a mostrar
                 <br />";
      break;
    case 7033:
      $Block .= "No puedo Select Recibido SUM
                        <br />";
      break;
    case 7034:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 7035:
      $Block .= "No puede update DevolucionesIndex
                        <br />";
      break;
    case 7036:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 7037:
      $Block .= "Cantidad a Recibir es invalida
                 <br />";
      break;
    case 7038:
      $Block .= "SUM Recibida es invalida
                 <br />";
      break;
    case 7039:
      $Block .= "Cantidad Devuelta es invalida
                 <br />";
      break;
    case 7040:
      $Block .= "DevolucionID es invalida
                 <br />";
      break;
    case 7041:
      $Block .= "No podemos recibir más que ordenamos a devolver
                 <br />";
      break;
    case 7042:
      $Block .= "No puede insert into ArticulosDevueltos
                 <br />";
      break;
    case 7043:
      $Block .= "No puede select DesgloseOrdenID
                 <br />";
      break;
    case 7044:
      $Block .= "No puedo update into DesgloseDeOrdenes
                 <br />";
      break;
    case 7045:
      $Block .= "El Articulo no se encuentra en el DesgloseDeOrdenes
                 <br />";
      break;
    case 7046:
      $Block .= "No puedo select en CatArticulos
                        <br />";
      break;
    case 7047:
      $Block .= "Parece que el articulo que quieres devolver no existe
                        <br />";
      break;
    case 7048:
      $Block .= "No puedo incrementar disponibles en CatArticulos
                        <br />";
      break;
    case 7049:
      $Block .= "No puedo update DevolucionesIndex
                        <br />";
      break;
    case 7050:
      $Block .= "No puedo Commit
                 <br />";
      break;
    case 7051:
      $Block .= "No tengo cantidades de articulos a devolver
                 <br />";
      break;
    case 7052:
      $Block .= "No puedo update DevolucionesIndex
                 <br />";
      break;
    case 7053:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 7054:
      $Block .= "No puede select de CatOrdenes
                        <br />";
      break;
    case 7055:
      $Block .= "No tiene Ordenes a desplegar
                 <br />";
      break;
    case 7056:
      $Block .= "&iexcl;Faltan Orden!
                        <br />";
      break;
    case 7057:
      $Block .= "&iexcl;OrdenID Invalido!
                        <br />";
      break;
    case 7058:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 7059:
      $Block .= "No puedo select de inner join
                 <br />";
      break;
    case 7060:
      $Block .= "No tiene registros a desplegar
                 <br />";
      break;
    case 7061:
      $Block .= "&iexcl;Faltan Campos Obligatorios!
                        <br />";
      break;
    case 7062:
      $Block .= "Fecha de Pedido Invalida
                 <br />
                 debe ser en formato yyyy-mm-dd
                 <br />";
      break;
    case 7063:
      $Block .= "Usuario ID Invalido
                 <br />";
      break;
    case 7064:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 7065:
      $Block .= "\"Cantidad\" invalida, para \"{$_GET['Var']}\",
                 <br />
                 debe ser 0 o mayor y 99999999.999 o menor
                 <br />
                 sin comas, \"-\" o comillas
                 <br />
                 no más de 3 numerales después del punto
                 <br />
                 si existe uno y solo uno punto.";
      break;
    case 7066:
      $Block .= "\"Cantidad\" invalida para \"{$_GET['Var']}\",
                 <br />
                 debe ser 0 o mayor y 99999999.999 o menor
                 <br />
                 sin comas, \"-\" o comillas
                 <br />
                 no más de 3 numerales después del punto
                 <br />
                 si existe uno y solo uno punto.";
      break;
    case 7067:
      $Block .= "No puede select de CatArticulos
                        <br />";
      break;
    case 7068:
      $Block .= "No tengo Articulos a mostrar
                 <br />";
      break;
    case 7069:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 7070:
      $Block .= "Faltan SolicitudID a borrar
                 <br />";
      break;
    case 7071:
      $Block .= "No puedo Borrar de ArticulosSolicitados
                 <br />";
      break;
    case 7072:
      $Block .= "No puedo Borrar de SolicitudesIndex
                 <br />";
      break;
    case 7073:
      $Block .= "No puedo Commit
                 <br />";
      break;
    case 7074:
      $Block .= "Faltan SolicitudID a desplegar
                        <br />";
      break;
    case 7075:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 7076:
      $Block .= "No puede select de SolicitudesIndex
                        <br />";
      break;
    case 7077:
      $Block .= "No puedo desplegar su Solicitud
                 <br />";
      break;
    case 7078:
      $Block .= "No puede select inner join de ArticulosSolicitados
                        <br />";
      break;
    case 7079:
      $Block .= "No tengo Articulos a mostrar
                 <br />";
      break;
    case 7080:
      $Block .= "No puedo conectar a la base de datos: Selva
                 <br />";
      break;
    case 7081:
      $Block .= "Faltan SolicitudTipo
                 <br />";
      break;
    case 7082:
      $Block .= "No puede select de SolicitudesIndex
                        <br />";
      break;
    case 7083:
      $Block .= "No tiene Solicitudes a desplegar
                 <br />";
      break;
    case 7084:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 7085:
      $Block .= "Faltan Solicitud ID
                 <br />";
      break;
    case 7086:
      $Block .= "Faltan Solicitud Estado
                 <br />";
      break;
    case 7087:
      $Block .= "No puedo update SolicitudesIndex
                 <br />";
      break;
    case 7088:
      $Block .= "Usuario ID Invalido
                 <br />";
      break;
    case 7089:
      $Block .= "\"Cantidad\" invalida, para \"{$_GET['Var']}\",
                 <br />
                 debe ser 0 o mayor y 99999999.999 o menor
                 <br />
                 sin comas, \"-\" o comillas
                 <br />
                 no más de 3 numerales después del punto
                 <br />
                 si existe uno y solo uno punto.";
      break;
    case 8001:
      $Block .= "Faltan DevolucionID
                 <br />";
      break;
    case 8002:
      $Block .= "No puedo select de CatOrdenes
                 <br />";
      break;
    case 8003:
      $Block .= "No tengo proyectos a desplegar
                 <br />";
      break;
    case 8004:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 8005:
      $Block .= "No puede select de Devoluciones/PedidosIndex
                        <br />";
      break;
    case 8006:
      $Block .= "No puedo desplegar su Devolución/Pedido
                 <br />";
      break;
    case 8007:
      $Block .= "No puede select de CatAreas
                        <br />";
      break;
    case 8008:
      $Block .= "No puedo desplegar su Área
                 <br />";
      break;
    case 8009:
      $Block .= "No puede select de CatOrdenes
                        <br />";
      break;
    case 8010:
      $Block .= "No puedo desplegar su Orden
                 <br />";
      break;
    case 8011:
      $Block .= "No puede select de PedidosIndex
                        <br />";
      break;
    case 8012:
      $Block .= "No tengo registros a mostrar
                 <br />";
      break;
    case 8013:
      $Block .= "No puede Select Recibido SUM
                        <br />";
      break;
    case 8014:
      $Block .= "&iexcl;ProveedorID Invalido!
                        <br />";
      break;
    case 8015:
      $Block .= "&iexcl;ProveedorID Invalido!
                        <br />";
      break;
    case 8016:
      $Block .= "No puede select de CatProveedores
                        <br />";
      break;
    case 8017:
      $Block .= "No puedo desplegar su Proveedor
                 <br />";
      break;
    case 8018:
      $Block .= "No puede select de CatProveedores
                        <br />";
      break;
    case 8019:
      $Block .= "No puedo desplegar su Proveedor
                 <br />";
      break;
    case 9001:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 9002:
      $Block .= "No puede select de CatAreas
                        <br />";
      break;
    case 9003:
      $Block .= "No tiene Areas a desplegar
                 <br />";
      break;
    case 9004:
      $Block .= "No puede select de CatArticulos
                        <br />";
      break;
    case 9005:
      $Block .= "No tiene Articulos a desplegar
                 <br />";
      break;
    case 9006:
      $Block .= "Faltan selección en menu
                 <br />";
      break;
    case 10001:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 10002:
      $Block .= "No puede select inner join
                        <br />";
      break;
    case 10003:
      $Block .= "No Familias a desplegar
                 <br />";
      break;
    case 10004:
      $Block .= "No puede select Inner Join
                 <br />";
      break;
    case 10005:
      $Block .= "No plantas a desplegar
                 <br />";
      break;
    case 10006:
      $Block .= "No Puede Select Inner Join Articulos en Pedidos
                        <br />";
      break;
    case 10007:
      $Block .= "No Articulos a Desplegar en Pedido Número {$_GET['VAR']}
                        <br />";
      break;
    case 10008:
      $Block .= "Debe Elegir un Reporte a Desplegar
                 <br />";
      break;
    case 10009:
      $Block .= "Debe Elegir una Solicitud a Examinar
                 <br />";
      break;
    case 10010:
      $Block .= "Debe Engresar una Cantidad y un Articulo Codigo a Examinar
                 <br />";
      break;
    case 10011:
      $Block .= "\"Cantidad Pedida\" invalida,
                 <br />
                 debe ser 0 o mayor y 99999999 o menor
                 <br />
                 sin comas, puntos o comillas.
                 <br />";
      break;
    case 10012:
      $Block .= "Articulo Codigo Invalido
                 <br />";
      break;
    case 10013:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 10014:
      $Block .= "No puede select de CatArticulos
                        <br />";
      break;
    case 10015:
      $Block .= "No tiene Artículos a desplegar
                 <br />";
      break;
    case 10016:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 10017:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 10018:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 10019:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 10020:
      $Block .= "No puede select inner join de ArticulosSolicitados
                        <br />";
      break;
    case 10021:
      $Block .= "No Articulos a desplegar
                 <br />";
      break;
    case 10022:
      $Block .= "No puede select ModelosIndex
                        <br />";
      break;
    case 10023:
      $Block .= "No Modelos Habilitados a desplegar
                 <br />";
      break;
    case 10024:
      $Block .= "Debe Elegir un Modelo a Examinar
                 <br />";
      break;
    case 10025:
      $Block .= "No puede select CatAreas
                        <br />";
      break;
    case 10026:
      $Block .= "No Areas Habilitados a desplegar
                 <br />";
      break;
    case 10027:
      $Block .= "Debe Elegir una Área
                 <br />";
      break;
    case 10028:
      $Block .= "No puede select
                 <br />";
      break;
    case 10029:
      $Block .= "No Artículos a desplegar
                 <br />";
      break;
    case 10030:
      $Block .= "No puede select de CatOrdenes
                        <br />";
      break;
    case 10031:
      $Block .= "No Ordenes a desplegar
                 <br />";
      break;
    case 10032:
      $Block .= "Debe Elegir una Orden a Desplegar
                 <br />";
      break;
    case 10033:
      $Block .= "No puedo select de CatOrdenes
                 <br />";
      break;
    case 10034:
      $Block .= "No tiene ordenes a desplegar
                 <br />";
      break;
    case 10035:
      $Block .= "No puedo select de CatOrdenes
                 <br />";
      break;
    case 10036:
      $Block .= "No tiene ordenes a desplegar
                 <br />";
      break;
    case 10037:
      $Block .= "No puede select de CatArticulos
                        <br />";
      break;
    case 10038:
      $Block .= "No tiene Artículos a desplegar
                 <br />";
      break;
    case 10039:
      $Block .= "No puede inner join DesgloseDeOrdenes con CatArticulos en \$i = {$_GET['Var']}
                        <br />";
      break;
    case 10040:
      $Block .= "No tiene Artículos a desplegar en \$i = {$_GET['Var']}
                 <br />";
      break;
    case 10041:
      $Block .= "No puede select CatUniProc
                        <br />";
      break;
    case 10042:
      $Block .= "No Unidades de Preoceso a desplegar
                 <br />";
      break;
    case 10043:
      $Block .= "Debe Capturar un Código
                 <br />";
      break;
    case 10044:
      $Block .= "No puede select inner join
                        <br />";
      break;
    case 10045:
      $Block .= "No tiene Artículos a desplegar
                 <br />";
      break;
    case 10046:
      $Block .= "No puede select de CatProveedores
                        <br />";
      break;
    case 10047:
      $Block .= "No Proveedores a desplegar
                 <br />";
      break;
    case 10048:
      $Block .= "Debe Elegir un Proveedor
                 <br />";
      break;
    case 10049:
      $Block .= "No puede select de PedidosIndex
                 <br />";
      break;
    case 10050:
      $Block .= "No Pedidos a desplegar
                 <br />";
      break;
    case 10051:
      $Block .= "Debe Capturar un Código Y un Pedido Tipo
                 <br />";
      break;
    case 10052:
      $Block .= "No puede left join
                 <br />";
      break;
    case 10053:
      $Block .= "No Articulos a desplegar
                 <br />";
      break;
    case 10054:
      $Block .= "Debe Capturar un Código
                 <br />";
      break;
    case 10055:
      $Block .= "No puede select inner join
                        <br />";
      break;
    case 10056:
      $Block .= "No tiene Devoluciones para \"{$_GET['Var']}\" a desplegar
                 <br />";
      break;
    case 10057:
      $Block .= "Debe Elegir una Orden a Desplegar
                 <br />";
      break;
    case 10058:
      $Block .= "No puede select from CatArticulos
                        <br />";
      break;
    case 10059:
      $Block .= "No tiene Artículos a desplegar
                 <br />";
      break;
    case 10060:
      $Block .= "No puede select inner join
                        <br />";
      break;
    case 10061:
      $Block .= "No tiene Movimientos a desplegar
                 <br />";
      break;
    case 10062:
      $Block .= "No puede select de CatOrdenes
                        <br />";
      break;
    case 10063:
      $Block .= "No tiene Ordenes a desplegar
                 <br />";
      break;
    case 10064:
      $Block .= "No puede select de CatOrdenes
                        <br />";
      break;
    case 10065:
      $Block .= "No Instaladores a desplegar
                 <br />";
      break;
    case 10066:
      $Block .= "Debe Elegir un Instalador
                 <br />";
      break;
    case 10067:
      $Block .= "No puede select de CatOrdenes
                        <br />";
      break;
    case 10068:
      $Block .= "No Vendedores a desplegar
                 <br />";
      break;
    case 10069:
      $Block .= "No puede select proveedores
                        <br />";
      break;
    case 10070:
      $Block .= "No tiene Proveedores a desplegar
                 <br />";
      break;
    case 10071:
      $Block .= "No Modelos Habilitados a desplegar
                 <br />";
      break;
    case 10072:
      $Block .= "Debe Engresar una Cantidad y un Articulo Codigo a Examinar
                 <br />";
      break;
    case 10073:
      $Block .= "No puede select de Usuarios
                        <br />";
      break;
    case 10074:
      $Block .= "No Capturistas a desplegar
                 <br />";
      break;
    case 10075:
      $Block .= "No puede select Inner Join - Pedidos Por
                 <br />";
      break;
    case 10076:
      $Block .= "No Pedidos a desplegar
                 <br />";
      break;
    case 10077:
      $Block .= "No Puede Select Inner Join Articulos en Pedidos
                        <br />";
      break;
    case 10078:
      $Block .= "No Articulos a Desplegar en Orden Número {$_GET['Var']}
                        <br />";
      break;
    case 10079:
      $Block .= "No puede drop InvArticulos
                        <br />";
      break;
    case 10080:
      $Block .= "No puede crear InvArticulos
                        <br />";
      break;
    case 10081:
      $Block .= "No puede alter InvArticulos
                        <br />";
      break;
    case 10082:
      $Block .= "No puede select de CatProveedores
                        <br />";
      break;
    case 10083:
      $Block .= "No Proveedores a desplegar
                 <br />";
      break;
    case 10084:
      $Block .= "Debe Elegir un Proveedor
                 <br />";
      break;
    case 10085:
      $Block .= "No puede select de ArticulosPedidos
                 <br />";
      break;
    case 10086:
      $Block .= "No Pedidos a desplegar
                 <br />";
      break;
    case 10087:
      $Block .= "Debe Elegir un Proveedor
                 <br />";
      break;
    case 10088:
      $Block .= "No puede select de ArticulosPedidos
                 <br />";
      break;
    case 10089:
      $Block .= "No Pedidos a desplegar
                 <br />";
      break;
    case 10090:
      $Block .= "No puede select de CatArticulos
                        <br />";
      break;
    case 10091:
      $Block .= "No tiene Artículos a desplegar
                 <br />";
      break;
    case 10092:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 10093:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 10094:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 10095:
      $Block .= "No puede select el inner join
                        <br />";
      break;
    case 10096:
      $Block .= "No puede select from Ubicaciones
                 <br />";
      break;
    case 10097:
      $Block .= "No hay Ubicaciones a mostrar
                 <br />";
      break;
    case 10098:
      $Block .= "Debes elegir una Ubicación
                 <br />";
      break;
    case 10099:
      $Block .= "Debes elegir una Familia
                 <br />";
      break;
    case 11001:
      $Block .= "No puedo Connect al la base de datos Selva
                 <br />";
      break;
    case 11002:
      $Block .= "No puede update
                 <br />";
      break;
    case 11003:
      $Block .= "No puede select from Proveedores
                 <br />";
      break;
    case 11004:
      $Block .= "No hay Proveedores a mostrar
                 <br />";
      break;
    case 11004:
      $Block .= "Debes elegir un Proveedor
                 <br />";
      break;
  }
?>

<!DOCTYPE HTML>
  <head>
    <meta charset="UTF-8" />
    <meta name="keywords" content="SelvaCabal, SelvaVista" />
    <meta http-equiv="default-style" content="text/css" />
    <title>
    <?php echo LOCATION_NAME ?>
    </title>
    <link rel="stylesheet" type="text/css" href="includes/SelvaVista.css" />
  </head>
  <body>
    <div class="content">
      <?php
        if( @$_SESSION['Nivel'] )
        {
          require( "Menu.php" );
          if( $_SERVER['HTTP_REFERER'] == "https://SelvaCabal/Catalogo.php" )
          {
            echo
             "<form action=\"https://SelvaCabal/Catalogo.php\"
                    method=\"post\">
                <div class=\"SubMenu\" style=\"margin-top:-50px;
                                               margin-left:44px;\">
                  <p>
                    <span style=\"font-weight:bold;\">
                      Agregar al Catálogo:
                    </span>
                    &nbsp;
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Grupos\" />
                    &nbsp;
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Artículos\" />
                    &nbsp;&nbsp;
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Áreas\" />
                    &nbsp;&nbsp;
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Proveedores\" />
                    &nbsp;
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Modelos\" />";
            if( $_SESSION['Nivel'] == 'Admin' )
              echo "&nbsp;&nbsp;&nbsp;
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Unidades de Proceso\" />";
            echo   "&nbsp;&nbsp;
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Ordenes\" />
                    <br />
                    <span style=\"font-weight:bold;\">
                      Manejar al Catálogo:&nbsp;&nbsp;
                    </span>
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Grupo\" />
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Artículo Sin Deshabils\" />
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Artículo Con Deshabils\" />
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Área\" />
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Proveedor\" />
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Modelo\" />";
          if( $_SESSION['Nivel'] == 'Admin' )
            echo   "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Unidad de Proceso\" />";
          echo     "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Orden\" />
                  </p>";
          }
          elseif( $_SERVER['HTTP_REFERER'] ==
                          "https://SelvaCabal/Devoluciones.php" )
          {
            echo
             "<form action=\"https://SelvaCabal/Devoluciones.php\"
                    method=\"post\">
                <div class=\"SubMenu\" style=\"margin-top:-50px;
                                               margin-left:55px;\">
                  <p>
                    <span style=\"font-weight:bold;\">
                      Devoluciones:
                    </span>";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Capturista' )
              echo "&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Crear\" />
                    &nbsp;
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Manejar\" />
                    &nbsp;&nbsp;
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Recibir\" />";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Capturista' ||
                $_SESSION['Nivel'] == 'Almacenista' )
              echo "&nbsp;&nbsp;
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Recibo PDF\" />";
            echo   "<br />
                    (Orden a Inventario)";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Capturista' )
              echo "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Manejar una Solicitud de Devolución\" />";
            echo   "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Solicitar una Devolución\" />
                  </p>";
          }
          elseif( $_SERVER['HTTP_REFERER'] ==
                          "https://SelvaCabal/Inventario.php" )
          {
            echo
             "<form action=\"https://SelvaCabal/Inventario.php\"
                    method=\"post\">
                <div class=\"SubMenu\" style=\"margin-top:-50px;
                                               margin-left:15px \">
                  <p>
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Contando\" />";
            if( $_SESSION['Nivel'] == 'Admin' )
              echo "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Comparar\" />";
            echo "</p>";
          }
          elseif( $_SERVER['HTTP_REFERER'] == "https://SelvaCabal/Pedidos.php" )
          {
            echo
             "<form action=\"https://SelvaCabal/Pedidos.php\"
                    method=\"post\">
                <div class=\"SubMenu\" style=\"margin-top:-50px;
                                               margin-left:15px \">
                  <p>
                    <span style=\"font-weight:bold;\">
                      Inv a Prov:
                    </span>";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Capturista' )
              echo "<input type=\"submit\" name=\"IPAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Crear\" />
                    <input type=\"submit\" name=\"IPAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Manejar\" />
                    <input type=\"submit\" name=\"IPAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Recibir\" />";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Almacenista' ||
                $_SESSION['Nivel'] == 'Capturista' )
            echo   "<input type=\"submit\" name=\"IPAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Recibo PDF\" />";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Capturista' )
              echo "<input type=\"submit\" name=\"IPAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                    background:#91cfff;\"
                           value=\"Orden de Compra\" />";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Almacenista' ||
                $_SESSION['Nivel'] == 'Capturista' )
            echo   "<span style=\"font-weight:bold;\">
                      Inv al Área:
                    </span>";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Capturista' )
              echo "<input type=\"submit\" name=\"IAAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Crear\" />
                    <input type=\"submit\" name=\"IAAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Manejar\" />
                    <input type=\"submit\" name=\"IAAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Recibir\" />";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Almacenista' ||
                $_SESSION['Nivel'] == 'Capturista' )
            echo   "<input type=\"submit\" name=\"IAAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Recibo PDF\" />";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Capturista' )
              echo "<input type=\"submit\" name=\"IAAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"PDF\" />";

            echo "<br />";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Almacenista' ||
                $_SESSION['Nivel'] == 'Capturista' )
              echo
                   "<span style=\"font-weight:bold;\">
                      Área a Inv:
                    </span>";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Capturista' )
              echo "<input type=\"submit\" name=\"AIAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Crear\" />
                    <input type=\"submit\" name=\"AIAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Manejar\" />
                    <input type=\"submit\" name=\"AIAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Recibir\" />";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Almacenista' ||
                $_SESSION['Nivel'] == 'Capturista' )
            echo   "<input type=\"submit\" name=\"AIAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Recibo PDF\" />";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Capturista' )
              echo "<input type=\"submit\" name=\"AIAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"PDF\" />";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Almacenista' ||
                $_SESSION['Nivel'] == 'Capturista' )
            echo   "<span style=\"font-weight:bold;\">
                      Orden a Inv:
                    </span>";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Capturista' ||
                $_SESSION['Nivel'] == 'PedOrdInv' )
              echo "<input type=\"submit\" name=\"OIAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Crear\" />
                    <input type=\"submit\" name=\"OIAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Manejar\" />
                    <input type=\"submit\" name=\"OIAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Recibir\" />";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Almacenista' ||
                $_SESSION['Nivel'] == 'PedOrdInv' ||
                $_SESSION['Nivel'] == 'Capturista' )
            echo   "<input type=\"submit\" name=\"OIAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Recibo PDF\" />";
            if( $_SESSION['Nivel'] == 'Admin' ||
                $_SESSION['Nivel'] == 'Capturista' )
              echo "<input type=\"submit\" name=\"OIAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"PDF\" />
                    <input type=\"submit\" name=\"OIAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Crear/Modelo\" />";
            echo   "<br />
                    <input type=\"submit\" name=\"SPAccion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;
                                   margin-left:73px; \"
                           value=\"Manejar Solicitudes de Pedidos\" />
                  </p>";
          }
          elseif( $_SERVER['HTTP_REFERER'] ==
                  "https://SelvaCabal/Usuarios.php" )
          {
            echo
             "<form action=\"https://SelvaCabal/Usuarios.php\"
                    method=\"post\">
                <div class=\"SubMenu\" style=\"margin-top:-50px;
                                               margin-left:75px;\">
                  <p>
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Manejar Tu Perfil\" />";
            if( $_SESSION['Nivel'] == 'Admin' )
              echo "<input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Cambiar Tu PWD\" />
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Agregar Usuarios\" />
                    <input type=\"submit\" name=\"Accion\"
                           style=\"font:10pt helvetica; color:#450065;
                                   background:#91cfff;\"
                           value=\"Manejar/Borar Usuarios\" />";
            echo "</p>";
          }
          echo "</div>
              </form>";
        }
        else
          print( "<p style=\"text-align:center; margin-top:3px;\"
                     class=TitleFont>" .
                    LOCATION_NAME .
                 "</p>" );
      ?>
      <div class="content-head">
        <p class="TitleFont">
          -=&nbsp;Mensaje de
          <span style="color:#bb0000; text-decoration:blink;">
            ERROR
          </span>
          &nbsp;=-
          <br/><br />
          <?php
  print( "Error Numero: <em>{$_GET['Errno']}</em>" );
          ?>
        </p>
      </div>
      <div class="content-main">
        <?php
          echo( "$Block" );
        ?>
        </p>

        <p style="text-align: center;" class="LargeTextFont">
          Presiona el botón, en tu navegador,
          <br />
          para regresar a la ultima página
          <br />
          para corregir el problema
        </p>
      </div>
    </div>
  </body>
</html>


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

  @session_start();

  if( @$_GET["LANG"] == 'es' )
  {
    $_SESSION["LANG"] = 'es';
    $__LANG__ = 'es';
  }
  elseif( @$_GET["LANG"] == 'en' )
  {
    $_SESSION["LANG"] = 'en';
    $__LANG__ = 'en';
  }

  echo       "<form action=\"index.php\" method=\"post\">
                <div class=\"Menu\">
                  <p style=\"margin-left:1em; margin-top:-35px;\">
                    <span style=\"vertical-align:43px; color:#ffffff;\"
                            class=\"LargeTextFont\">
                          SelvaVista© Menu:&nbsp;&nbsp;&nbsp;
                        <input type=\"submit\" name=\"Accion\"
                               style=\"font:10pt helvetica; color:#450065;
                                       background:#91cfff;\" ";
  if( @$__LANG__ == 'en' )
    echo                      "value=\"Users\" />";
  else
    echo                      "value=\"Usuarios\" />";
  if( $_SESSION['Nivel'] == 'Admin' )
  {
    echo              " <input type=\"submit\" name=\"Accion\"
                               style=\"font:10pt helvetica; color:#450065;
                                       background:#91cfff;\" ";
    if( @$__LANG__ == 'en' )
      echo                    "value=\"Catalogs\" />";
    else
      echo                    "value=\"Catálogos\" />";
    echo              " <input type=\"submit\" name=\"Accion\"
                               style=\"font:10pt helvetica; color:#450065;
                                       background:#91cfff;\"
                               value=\"Logs\" /> ";
  }
  echo                " <input type=\"submit\" name=\"Accion\"
                               style=\"font:10pt helvetica; color:#450065;
                                       background:#91cfff;\" ";
  if( @$__LANG__ == 'en' )
    echo                      "value=\"Site\" />";
  else
    echo                      "value=\"Sitio\" />";
  echo                " <input type=\"submit\" name=\"Accion\"
                               style=\"font:10pt helvetica; color:#450065;
                                       background:#91cfff;\"
                               value=\"LogOut\" />
                      </span>
                    </p>
                  </div>
              </form>";
?>

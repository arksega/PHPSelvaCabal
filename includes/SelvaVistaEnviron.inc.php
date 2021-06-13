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

  require_once( "/var/www/SelvaCabal/includes/SelvaVistaConfig.php" );
  @ini_set( "session.cache_expire", SesCacheExpire );
  @ini_set( "session.cookie_lifetime", SesCookieLife );
  @ini_set( "session.gc_maxlifetime", SesGCMaxLife );
  @session_start();

  $__LANG__ = @substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 );

?>

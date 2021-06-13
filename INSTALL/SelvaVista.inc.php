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

  Note I run Apache, MySQL and PHP 7.3 on GNU Linux. If you run some other
  combination, you will need to adapt these instructions to fit your
  installation.

  MOVE this file to the location, OUTSIDE of the HTTP ROOT, that you specified
  in all of the php files. The default location is /etc and the file should
  have apache.root owners.group and 600 (rw-------)rights.
 */

  $Clase = "";// Name of the MySQL user with all privs except GRANT
  $ClaseRO = "";// Name of the MySQL User with only SELECT priv
  $AccessType ="";// Password of the MySQL user with all privs except GRANT
  $AccessTypeRO ="";// Password of the MySQL User with only SELECT priv
  $CaptchaPubKey = "";
  $CaptchaPrivKey = "";
?>

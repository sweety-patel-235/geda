# $Id: sessions.sql 2 2018-02-13 08:22:46Z kalpak_p $
#
# Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
#								1785 E. Sahara Avenue, Suite 490-204
#								Las Vegas, Nevada 89104
#
# Licensed under The MIT License
# For full copyright and license information, please see the LICENSE.txt
# Redistributions of files must retain the above copyright notice.
# MIT License (http://www.opensource.org/licenses/mit-license.php)

CREATE TABLE sessions (
  id varchar(40) NOT NULL default '',
  data text,
  expires INT(11) NOT NULL,
  PRIMARY KEY  (id)
);

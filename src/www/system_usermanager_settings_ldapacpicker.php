<?php
/*
	Copyright (C) 2014-2015 Deciso B.V.
	Copyright (C) 2007 Scott Ullrich <sullrich@gmail.com>
	All rights reserved.

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are met:

    1. Redistributions of source code must retain the above copyright notice,
       this list of conditions and the following disclaimer.

    2. Redistributions in binary form must reproduce the above copyright
       notice, this list of conditions and the following disclaimer in the
       documentation and/or other materials provided with the distribution.

    THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
    INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
    AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
    AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
    OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
    SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
    INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
    CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
    ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
    POSSIBILITY OF SUCH DAMAGE.
*/

require("guiconfig.inc");
require_once("auth.inc");
include('head.inc');

$ous = array();

if($_GET) {
	$authcfg = array();
	$authcfg['ldap_port'] = $_GET['port'];
	$authcfg['ldap_basedn'] = $_GET['basedn'];
	$authcfg['host'] = $_GET['host'];
	$authcfg['ldap_scope'] = $_GET['scope'];
	$authcfg['ldap_binddn'] = $_GET['binddn'];
	$authcfg['ldap_bindpw'] = $_GET['bindpw'];
	$authcfg['ldap_urltype'] = $_GET['urltype'];
	$authcfg['ldap_protver'] = $_GET['proto'];
	$authcfg['ldap_authcn'] = explode(";", $_GET['authcn']);
	$authcfg['ldap_caref'] = $_GET['cert'];
	$ous = ldap_get_user_ous(true, $authcfg);
}

?>

 <body>
	<script type="text/javascript">
function post_choices() {

	var ous = <?php echo count($ous); ?>;
	var i;
	var values = jQuery("#ou:checked").map(function(){
	return jQuery(this).val();
    }).get().join(';');
	window.opener.document.getElementById('ldapauthcontainers').value=values;
	window.close();
}
</script>
 <form method="post" action="system_usermanager_settings_ldapacpicker.php">
<?php if (empty($ous)): ?>
	<p><?=gettext("Could not connect to the LDAP server. Please check your LDAP configuration.");?></p>
	<input type='button' class="btn btn-default" value='<?=gettext("Close"); ?>' onClick="window.close();">
<?php else: ?>
	<table class="table table-striped">
		<tbody>
			<tr>
			<th>
				<?=gettext("Please select which containers to Authenticate against:");?>
			</th>
			</tr>
			<?php
				if(is_array($ous)) {
					foreach($ous as $ou) {
						if(in_array($ou, $authcfg['ldap_authcn']))
							$CHECKED=" CHECKED";
						else
							$CHECKED="";
						echo "			<tr><td><input type='checkbox' value='{$ou}' id='ou' name='ou[]'{$CHECKED}> {$ou}</td></tr>\n";
					}
				}
			?>
			<tr>
				<td align="right">
					<input type='button' class="btn btn-primary" value='<?=gettext("Save");?>' onClick="post_choices();">
				</td>
			</tr>
		</tbody>
	</table>
<?php endif; ?>
 </form>
 </body>
</html>

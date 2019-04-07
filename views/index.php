<br/><br/>
	<form action="<?php echo $root_dir;?>list_databases" method="post">
		<table class="table table-striped">
			<tr>
				<td align="center" colspan="2" style="font-size:25px;"><strong><u>Enter Database Details</u></strong></td>
			</tr>
			<tr>
				<td align="center" style="font-size:18px;"><strong>Database Type</strong></td>
				<td>
					<select name="rdbms" class="form-control" required>
						<option value="">Select Database Type</option>
						<option value="mysql">MySQL</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="center" style="font-size:18px;"><strong>Database IP</strong></td>
				<td>
					<input type="text" name="host" class="form-control" required/>
					<span style="padding-left:0px;color:#446cb3;">* Please enter your system's IP . e.g.: 127.0.0.1</span>
				</td>
			</tr>
			<tr>
				<td align="center" style="font-size:18px;"><strong>Database Username</strong></td>
				<td>
					<input type="text" name="username" class="form-control" required/>
				</td>
			</tr>
			<tr>
				<td align="center" style="font-size:18px;"><strong>Database Password</strong></td>
				<td>
					<input type="password" name="password" class="form-control"/>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="Submit" name="getTables" value="Show Databases" class = "lt_save btn btn-primary btn-md"/>
				</td>
			</tr>
		</table>
		<div class="row">
			<span style="font-size:21px;"></span>
		</div>
	</form>
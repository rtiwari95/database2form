
	<table class="table table-striped">
		<tr>
			<td align="right" colspan="2"><a href="/database2form">
				<img src="<?php echo $root_dir1;?>/public/images/home.png" height="31" width="31">
			</a></td>
		</tr>
		<?php if(!empty($data) && is_array($data)){?>
		<tr>
			<th style="font-size:21px;">S.No.</th>
			<th style="font-size:21px;">Database Name</th>
		</tr>
		<?php
			for($i=0;$i<count($data);$i++){?>
				<tr>
					<td><?php echo ($i+1);?></td>
					<td>
						<form method="post" action="list_tables">
							<input type="hidden" name="rdbms" value="<?php echo $_REQUEST['rdbms']?>">
							<input type="hidden" name="host" value="<?php echo $_REQUEST['host']?>">
							<input type="hidden" name="username" value="<?php echo $_REQUEST['username']?>">
							<input type="hidden" name="password" value="<?php echo $_REQUEST['password']?>">
							<input type="hidden" name="database" value="<?php echo $data[$i]['Database'];?>">
							<input type="submit" name="proceed" value="<?php echo ucwords(strtolower($data[$i]['Database']));?>" class="btn btn-primary">
						</form>
					</td>
				</tr>
		<?php
			}
}else if($data){
		?>
		<tr>
			<td align="center" style="color: red;"><h3><?php echo $data.'.';?><h3></h3></td>
		</tr>
		<?php 
            }else{
		?>
		<tr>
			<td align="center" style="color: red;"><h3>Error in connection.<h3></h3></td>
		</tr>
		<?php }?>
	</table>

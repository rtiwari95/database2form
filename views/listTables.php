
<form action="<?php echo $root_dir;?>selected_tables" method="POST">
	<table class="table table-striped">
		<tr>
			<td align="left"><a href="javascript:history.back()">
				<img src="<?php echo $root_dir1;?>/public/images/goBack.png" height="31" width="101"  style="margin-left:-11px;">
			</a></td>
			<td align="right" colspan="2"><a href="/database2form">
				<img src="<?php echo $root_dir1;?>/public/images/home.png" height="31" width="31"  style="margin-right:-11px;">
			</a></td>
			
		</tr>
		<tr>
			<th style="font-size:21px;">S.No.</th>
			<th style="font-size:21px;"><input type="checkbox" disabled></th>
			<th style="font-size:21px;">Table Name</th>
		</tr>
		<tr>
			<td>1</td>
			<td>
			<input type="hidden" name="rdbms" value="<?php echo $_REQUEST['rdbms']?>">
							<input type="hidden" name="host" value="<?php echo $_REQUEST['host']?>">
							<input type="hidden" name="username" value="<?php echo $_REQUEST['username']?>">
							<input type="hidden" name="password" value="<?php echo $_REQUEST['password']?>">
							
			<input type="hidden" name="database" id="database" value="<?php echo $_REQUEST['database'];?>">
				<input type="checkbox" id="select_all" class="form-control"/>
			</td>
			<td>Select All</td>
		</tr>
		<?php
			for($i=0;$i<count($data);$i++){?>
				<tr>
					<td><?php echo ($i+2);?></td>
					<td>
						<input type="checkbox" class="checkbox form-control" name="table[]" id="<?php echo $data[$i];?>"  value="<?php echo $data[$i];?>"  />
					</td>
					<td><?php echo ucwords(strtolower($data[$i]));?></td>
				</tr>
		<?php
			}
		?>
		<tr>
			<td colspan="3" align="center">
				<input type="submit" value="Proceed" name="proceed" id="proceed" class="btn btn-primary">
			</td>
		</tr>
		
	</table>
	</form>
	<script>
	$("#select_all").change(function(){ 
	    $(".checkbox").prop('checked', $(this).prop("checked")); 
	});

	
	$('.checkbox').change(function(){ 
		
	    if(false == $(this).prop("checked")){ 
	        $("#select_all").prop('checked', false); 
	    }
		
		if ($('.checkbox:checked').length == $('.checkbox').length ){
			$("#select_all").prop('checked', true);
		}
	});


				
				$(document).ready(function () {
				    $('#proceed').click(function() {
				      checked = $("input[type=checkbox]:checked").length;

				      if(!checked) {
				        alert("You must check at least one checkbox.");
				        return false;
				      }

				    });
				});

				
		</script>

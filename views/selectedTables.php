
<form action="<?php echo $root_dir;?>createProject" method="POST">
	<table class="table table-striped">
		<tr>
			<td align="left">
				<a href="javascript:history.back()">
					<img src="<?php echo $root_dir1;?>/public/images/goBack.png"  height="31" width="101" style="margin-left:-11px;">
				</a>
			</td>
			<td align="center">
				<input type="text" name="project_title" class="form-control" placeholder="Project Title">
			</td>
			<td align="center">
				<label for="loginRequirment">
					<input type="checkbox" name="loginRequirment" value="true" class="form-control">Required Login Page
				</label>
			</td>
			<td align="right" colspan="2">
				<a href="/database2form">
					<img src="<?php echo $root_dir1;?>/public/images/home.png" height="31" width="31" style="margin-right:-11px;">
				</a>
			</td>
			
		</tr>
		
		<tr>
			<th style="font-size:21px;align:center;width:7%">S.No.</th>
			<th style="font-size:21px;align:center;width:21%">Table Name</th>
			<th style="font-size:21px;align:center;width:*%">Columns</th>
		</tr>
		<?php
			for($i=0;$i<count($data);$i++){?>
				<tr>
					<td><?php echo ($i+1);?></td>
					<td style="font-size:16px;">
					<strong><?php echo ucwords(strtolower($data[$i]));?></strong>
					</td>
					
					<td>
						<table class="table table-bordered">
							<tr>
								<td style="width:5%" align="center"><strong>S.No.</strong></td>
								<td style="width:41%" align="center"><strong>Column Name</strong></td>
								<td style="width:*%;" align="center"><strong>Field Type</strong></td>
							</tr>
							<tr>
								<td></td>
								<td align="center"><label><input type="checkbox" id="select_all_<?php echo $i;?>" class="form-control allFields"/> Select All</label>
									<input type="hidden" name="id[]" id="id" value="<?php echo explode("-",$cols[$i][0])[0];?>"></td>
								<td align="center">
									<select disabled class="form-control">
										<option>Select</option>
									</select>
								</td>
							</tr>
				<?php
					for($k = 1;$k<count($cols[$i]);$k++){
					    $checked = '';
					    $inputType = '';
					    $fieldArr = explode("-",$cols[$i][$k]);
					    $fieldType = substr($fieldArr[1],0,4);
					    
						$value = $cols[$i][$k];
						
						if(strcmp($fieldArr[2],'NO')==0){//used for the fields in database in which 'NULL' can't be inserted.
						    $checked = '<input type="checkbox" class="checkbox form-control" name="'.$data[$i].'[]" id="select_'.$i.'_'.$k.'" value="'.$value.'" checked disabled />'.ucwords(strtolower($fieldArr[0])).'
                                        <input type="hidden"  name="'.$data[$i].'[]" id="'.$fieldArr[0].'"  value="'.$value.'">';
						}else{
						    $checked = '<input type="checkbox" class="checkbox_select_all_'.$i.' checkbox form-control" name="'.$data[$i].'[]" id="select_'.$i.'_'.$k.'"  value="'.$value.'" />'.ucwords(strtolower($fieldArr[0])).'';
						}
						
						if((strcmp($fieldType,'enum')==0)&&(strcmp($fieldArr[2],'NO')==0)){ //used to get input type of form.
						   
						    $inputType = '<td align="center">
                                            <select name="fieldType[]" id="dropdown_select_'.$i.'_'.$k.'" class="dropdown_enum form-control">
	                                           <option value="">Input Type</option>
	                                           <option value="radio">Radio Button</option>
	                                           <option value="dropdown" selected="selected">Dropdown List</option>
                                             </select>
                                           </td>';
						}else if(strcmp($fieldType,'enum')==0){ //used to get input type of form.
						    
						    $inputType = '<td align="center">
                                            <select name="fieldType[]" id="dropdown_select_'.$i.'_'.$k.'" class="dropdown_select_all_'.$i.' form-control">
	                                           <option value="">Input Type</option>
	                                           <option value="radio">Radio Button</option>
	                                           <option value="dropdown" selected="selected">Dropdown List</option>
                                             </select>
                                           </td>';
						}else if((strcmp($fieldType,'date')==0)&&(strcmp($fieldArr[2],'NO')==0)){ //used to get input type of form.
						    
						    $inputType = '<td align="center">
                                            <select name="fieldType[]" id="dropdown_select_'.$i.'_'.$k.'" class="dropdown_enum form-control">
	                                           <option value="">Input Type</option>
	                                           <option value="text">Text</option>
	                                           <option value="date" selected="selected">Date</option>
                                             </select>
                                           </td>';
						}else if(strcmp($fieldType,'date')==0){ //used to get input type of form.
						    
						    $inputType = '<td align="center">
                                            <select name="fieldType[]" id="dropdown_select_'.$i.'_'.$k.'" class="dropdown_select_all_'.$i.' form-control">
	                                           <option value="">Input Type</option>
	                                           <option value="text">Text</option>
	                                           <option value="date" selected="selected">Date</option>
                                             </select>
                                           </td>';
						}
						else if(strcmp($fieldArr[2],'NO')==0){
						    $inputType = '<td align="center">
                                            <select name="fieldType[]" id="dropdown_select_'.$i.'_'.$k.'" class="dropdown_required form-control">
	                                           <option value="">Input Type</option>
	                                           <option value="text" selected="selected">Text</option>
	                                           <option value="email">Email</option>
	                                           <option value="number">Number</option>
	                                           <option value="password">Password</option>
                                               <option value="date">Date</option>
                                             </select>
                                           </td>';
						}else{
						    $inputType = '<td align="center">
                                            <select name="fieldType[]" id="dropdown_select_'.$i.'_'.$k.'" class="dropdown_select_all_'.$i.' form-control">
	                                           <option value="">Input Type</option>
	                                           <option value="text" selected="selected">Text</option>
	                                           <option value="email">Email</option>
	                                           <option value="number">Number</option>
	                                           <option value="password">Password</option>
                                               <option value="date">Date</option>
                                             </select>
                                           </td>';
						}
						echo '<tr id="select_all_'.$i.'">
							<td align="center">'.$k.'</td>
							<td align="center"><label>'.$checked.'</label>
                             </td>
                            '.$inputType.'
						</tr>';
						
					}
				?>
						</table>
					</td>
				</tr>
		<?php
			}
		?>
		
    		<tr>
    			<td colspan="3" align="center">
    			<?php for($i=0;$i<count($data);$i++){?>
    				<input type="hidden" name="table[]" value="<?php echo $data[$i];?>">
    			<?php }?>
    				<input type="hidden" name="rdbms" value="<?php echo $_REQUEST['rdbms']?>">
    				<input type="hidden" name="host" value="<?php echo $_REQUEST['host']?>">
					<input type="hidden" name="username" value="<?php echo $_REQUEST['username']?>">
					<input type="hidden" name="password" value="<?php echo $_REQUEST['password']?>">
    				<input type="hidden" name="database" value="<?php echo $database;?>">
    				<input type="submit" value="Create Form" name="createProject" id="createProject" class="btn btn-primary">
    			</td>
    		</tr>
		
	</table>
	</form>
	<script>
	$(".dropdown").prop("disabled",true);
	$(document).ready(function(){
		var k = $(".allFields").attr('id');
		$(".dropdown_"+k).prop("disabled",true);
		$(".allFields").change(function(){
			var x = $(this).attr('id');
			$(".checkbox_"+x).prop('checked', $(this).prop("checked"));
			if ($("#"+x).is(":checked")) {
				$(".dropdown_"+x).prop("disabled",false);
   		 	}else {
   		    	$(".dropdown_"+x).prop("disabled",true);  
   		 	}
		});
	}); 
	$(document).ready(function() {
		$(".dropdown").prop("disabled",true);
		$(".checkbox").click(function(){
			var x = $(this).closest('tr').attr('id');
			$(".checkbox_"+x).change(function(){
				var y  = $(this).attr('id');
    			if ($("#"+y).is(":checked")) {
    			    $("#dropdown_"+y).prop("disabled",false);
    			} else {
    		    	$("#dropdown_"+y).prop("disabled",true); 
    		 	} 
	 		});
			
		}); 		
	});
	
	</script>

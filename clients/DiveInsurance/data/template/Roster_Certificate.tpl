{assign var=list value=$groupPL|json_decode:true}
{assign var=certificateLevel value=array("freediveInstructor" => "Free Diver Instructor","tecRecfreediveInstructor" => "TecRec Free Diver Instructor","tecRecRetiredInstructor"=>"TecRec Retired Instructor","tecRecNonteachingSupervisoryInstructor"=>"TecRec Nonteaching / Supervisory Instructor","instructor" => "Instructor","tecRecInstructor"=>"TecRec Instructor","nonteachingSupervisoryInstructor" => "Nonteaching / Supervisory Instructor","retiredInstructor" => "Retired Instructor","assistantInstructor"=>"Assistant Instructor","divemasterAssistantInstructorAssistantOnly" => "Divemaster / Assistant Instructor ASSISTING ONLY","tecRecDivemasterAssistantInstructorAssistantOnly" => "TecRec Divemaster / Assistant Instructor ASSISTING ONLY","divemaster" => "Divemaster","tecRecDivemaster" => "TecRec Divemaster","emergencyFirstResponseInstructor" => "Emergency First Response Instructor","swimInstructor" => "Swim Instructor")}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divebtemplate_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div_lp">
			<div class = "ai_spacing"></div>

			<table>
				<tr>
					<th class = 'r_headerRow' align = "left">Member Name&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
					<th class = 'r_headerRow' align = "left">Member Number&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
					<th class = 'r_headerRow' align = "left">Status&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
				</tr>

				{foreach from=$list item=$additional}
	    		<tr>
	    			<td>{$additional.firstname}&nbsp{$additional.lastname}</td>
	    			<td>{$additional.padi}</td>
	    			<td>{if isset($additional.status) && isset($certificateLevel[$additional.status])}{$certificateLevel[$additional.status]} {else}{$additional.status}{/if}</td>
	    		</tr>
    			{/foreach}
			</table>

	</div>
</body>
</html>

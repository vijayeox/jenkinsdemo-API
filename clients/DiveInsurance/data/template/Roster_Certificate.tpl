{assign var=list value=$groupPL|json_decode:true}
{assign var=certificateLevel value=array("freediveInstructor" => "Free Diver Instructor","instructor" => "Instructor","nonteachingSupervisoryInstructor" => "Nonteaching / Supervisory Instructor","retiredInstructor" => "Retired Instructor","assistantInstructor"=>"Assistant Instructor","divemasterAssistantInstructorAssistantOnly" => "Divemaster / Assistant Instructor ASSISTING ONLY","divemaster" => "Divemaster","emergencyFirstResponseInstructor" => "Emergency First Response Instructor","swimInstructor" => "Swim Instructor")}
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
					<th class = 'r_headerRow'>Member Name&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp/th>
					<th class = 'r_headerRow'>Member Number&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
					<th class = 'r_headerRow'>Status&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
				</tr>

				{foreach from=$list item=$additional}
	    		<tr>
	    			<td>{$additional.firstname}&nbsp{$additional.lastname}</td>
	    			<td>{$additional.padi}</td>
	    			<td>{$certificateLevel[$additional.status]}</td>
	    		</tr>
    			{/foreach}
			</table>
	    	
	</div>
</body>
</html>


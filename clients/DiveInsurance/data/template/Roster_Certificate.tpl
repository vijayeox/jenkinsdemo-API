{assign var=list value=$groupPL|json_decode:true}
{assign var=certificateLevel value=array("freediveInstructor" => "Free Diver Instructor","tecRecfreediveInstructor" => "TecRec Free Diver Instructor","tecRecRetiredInstructor"=>"TecRec Retired Instructor","tecRecNonteachingSupervisoryInstructor"=>"TecRec Nonteaching / Supervisory Instructor","instructor" => "Instructor","tecRecInstructor"=>"TecRec Instructor","nonteachingSupervisoryInstructor" => "Nonteaching / Supervisory Instructor","retiredInstructor" => "Retired Instructor","assistantInstructor"=>"Assistant Instructor","tecRecAssistantInstructor"=>"TecRec Assistant Instructor","divemasterAssistantInstructorAssistingOnly" => "Divemaster / Assistant Instructor ASSISTING ONLY","divemasterAssistantInstructorAssistantOnly" => "Divemaster / Assistant Instructor ASSISTING ONLY","tecRecDivemasterAssistantInstructorAssistantOnly" => "TecRec Divemaster / Assistant Instructor ASSISTING ONLY","divemaster" => "Divemaster","tecRecDivemaster" => "TecRec Divemaster","emergencyFirstResponseInstructor" => "Emergency First Response Instructor","swimInstructor" => "Swim Instructor")}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divebtemplate_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div_lp">
			<div class = "ai_spacing"></div>
			<table>
				<tr class = "title_row">
					<th class = 't_title1 title_font' align = "left">Member#&nbsp&nbsp</th>
					<th class = 't_title2 title_font' align = "left">Name&nbsp&nbsp</th>
					<th class = 't_title3 title_font' align = "left">Status&nbsp&nbsp</th>
					<th class = 't_title4 title_font' align = "left">Eff.Date</th>
					<th class = 't_title5 title_font' align = "left">Upg</th>
					<th class = 't_title6 title_font' align = "left">Upg Eff Date&nbsp&nbsp</th>
					<th class = 't_title7 title_font' align = "left">Cancel Date<th>
				</tr>
				{foreach from=$list item=$value}
		    		<tr>
						<td class = 't_title' align = "left">{$value.padi}</td>
						<td class = 't_title' align = "left">{$value.firstname}&nbsp{$value.lastname}</td>
						<td class = 't_title' align = "left">{$certificateLevel[$value.status]}</td>
						<td class = 't_title' align = "left">{$value.start_date|date_format:"%m/%d/%Y"}</td>
						<td class = 't_title' align = "left">{if isset($value.upgradeStatus) && ($value.upgradeStatus == true || $value.upgradeStatus == 'true')}
					      Yes{else}No{/if}</td>
					    <td class = 't_title' align = "left">
					    {if isset($value.upgradeStatus) && ($value.upgradeStatus == true || $value.upgradeStatus == 'true')}
					      			{if isset($value.update_date) && $value.update_date != ''}
					      				{$value.update_date|date_format:"%m/%d/%Y"}
					      			{else}
					      				&nbsp
					      			{/if}
					      {else}
					      			&nbsp
					      {/if}
					      </td>
					      <td class = 't_title' align = "left">
					      {if isset($value.cancel) && ($value.cancel == true || $value.cancel == 'true')}
					      			{if isset($value.cancel_date) && $value.cancel_date != ''}
					      				{$value.cancel_date|date_format:"%m/%d/%Y"}
					      			{else}
					      				&nbsp
					      			{/if}
					      {else}
					      			&nbsp
					      {/if}
					      </td>
					</tr>
    			{/foreach}
			</table>

	</div>
</body>
</html>

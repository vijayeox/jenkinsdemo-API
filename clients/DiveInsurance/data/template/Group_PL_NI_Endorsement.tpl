{assign var=list value=$groupPL|json_decode:true}
{assign var=certificateLevel value=array("freediveInstructor" => "Free Diver Instructor","tecRecfreediveInstructor" => "TecRec Free Diver Instructor","tecRecRetiredInstructor"=>"TecRec Retired Instructor","tecRecNonteachingSupervisoryInstructor"=>"TecRec Nonteaching / Supervisory Instructor","instructor" => "Instructor","tecRecInstructor"=>"TecRec Instructor","nonteachingSupervisoryInstructor" => "Nonteaching / Supervisory Instructor","retiredInstructor" => "Retired Instructor","assistantInstructor"=>"Assistant Instructor","tecRecAssistantInstructor"=>"TecRec Assistant Instructor","divemasterAssistantInstructorAssistingOnly" => "Divemaster / Assistant Instructor ASSISTING ONLY","divemasterAssistantInstructorAssistantOnly" => "Divemaster / Assistant Instructor ASSISTING ONLY","tecRecDivemasterAssistantInstructorAssistantOnly" => "TecRec Divemaster / Assistant Instructor ASSISTING ONLY","divemaster" => "Divemaster","tecRecDivemaster" => "TecRec Divemaster","emergencyFirstResponseInstructor" => "Emergency First Response Instructor","swimInstructor" => "Swim Instructor",'snorklingInstructor' => 'Snorkling Instructor')}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/template_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div_lp">
		<b><p style = "font-size: 15px;margin-bottom:0px;">NAMED INSURED IS HEREBY AMENDED TO INCLUDE:</p></b>
		<table style = "width:100%">
		<tr>
			<th class = 't_title1' align = "left">Member#&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
			<th class = 't_title1' align = "left">Name&nbsp&nbsp</th>
			<th class = 't_title1' align = "left">Status&nbsp&nbsp&nbsp</th>
			<th class = 't_title1' align = "left">Eff.Date&nbsp&nbsp&nbsp</th>
			<th class = 't_title1' align = "left">Upg </th>
			<th class = 't_title1' align = "left">Upg Eff Date&nbsp</th>
			<th class = 't_title1' align = "left">Cancel Date&nbsp&nbsp<th>
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

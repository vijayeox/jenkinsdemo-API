{assign var=list value=$groupPL|json_decode:true}
{assign var=certificateLevel value=array("freediveInstructor" => "Free Diver Instructor","tecRecfreediveInstructor" => "TecRec Free Diver Instructor","tecRecRetiredInstructor"=>"TecRec Retired Instructor","tecRecNonteachingSupervisoryInstructor"=>"TecRec Nonteaching / Supervisory Instructor","instructor" => "Instructor","tecRecInstructor"=>"TecRec Instructor","nonteachingSupervisoryInstructor" => "Nonteaching / Supervisory Instructor","retiredInstructor" => "Retired Instructor","assistantInstructor"=>"Assistant Instructor","divemasterAssistantInstructorAssistantOnly" => "Divemaster / Assistant Instructor ASSISTING ONLY","tecRecdivemasterAssistantInstructorAssistantOnly" => "TecRec Divemaster / Assistant Instructor ASSISTING ONLY","divemaster" => "Divemaster","emergencyFirstResponseInstructor" => "Emergency First Response Instructor","swimInstructor" => "Swim Instructor")}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/template_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div_lp">
		{foreach from=$list item=$value}
			<div class = "named_div">
			      <div class = "named_div1"><p class ="t_title">{$value.padi}</p></div>

			      <div class = "named_div2"><p class ="t_title" style="text-tranform:uppercase;">{$value.firstname}&nbsp{$value.lastname}</p></div>

			      <div class = "named_div3"><p class ="t_title">{$certificateLevel[$value.status]}</p></div>

			      <div class = "named_div4"><p class ="t_title">{$value.start_date|date_format:"%m/%d/%Y"}</p></div>
			      <div class = "named_div5"><p class ="t_title">{if isset($value.upgradeStatus) && ($value.upgradeStatus == true || $value.upgradeStatus == 'true')}
			      Yes{else}No{/if}</p>
			      </div>

			      <div class = "named_div6"><p class ="t_title">
			      {if isset($value.upgradeStatus) && ($value.upgradeStatus == true || $value.upgradeStatus == 'true')}
			      			{if isset($value.update_date) && $value.update_date != ''}
			      				{$value.update_date|date_format:"%m/%d/%Y"}
			      			{else}
			      				&nbsp
			      			{/if}
			      {else}
			      			&nbsp
			      {/if}
			      </p>
			      </div>

			      <div class = "named_div7"><p class ="t_title">
			      {if isset($value.cancel) && ($value.cancel == true || $value.cancel == 'true')}
			      			{if isset($value.cancel_date) && $value.cancel_date != ''}
			      				{$value.cancel_date|date_format:"%m/%d/%Y"}
			      			{else}
			      				&nbsp
			      			{/if}
			      {else}
			      			&nbsp
			      {/if}
			      </p>
				  </div>
			</div>
	    {/foreach}
	</div>
</body>
</html>


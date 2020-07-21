{assign var=list value=$additionalInsured|json_decode:true}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div_ai">
<br/>

		<b><p class ="grp_add">Additional Insured (Additional Insured status only applies when required by written contract per attached Additional Insured - Blanket Form PI-MANU-1 (01/100)):</p></b>
<br/>
	    	{foreach $list as $additional}
	    		<p class = "ai_list" style = "text-transform:uppercase;">
	    			{$additional.name} {if (isset($additional.businessRelation) && $additional.businessRelation != "")}(
		    			{if $additional.businessRelation == "confinedWaterTrainingLocation"}
		    			 	Confined Water Training Location 
		    			{elseif $additional.businessRelation == "openWaterTrainingLocation"} 
		    				Open Water Training Location 
		    			{elseif $additional.businessRelation == "diveBoatOwner"} 
		    				Dive Boat Owner
		    			{elseif $additional.businessRelation == "mortgageeLossPayee"} 
		    				Mortgagee / Loss Payee
		    			{elseif $additional.businessRelation == "landlord"}
		    				Landlord
		    			{elseif $additional.businessRelation == "governmentEntityPermitRequirement"} 
		    			    Government Entity - Permit Requirement
		    			{elseif $additional.businessRelation == "diveStore"} 
		    			   Dive Store
		    			{elseif $additional.businessRelation == "trainingAgency"} 
		    			   Training Agency
		    			{elseif $additional.businessRelation == "cruiseLine"} 
		    			   Cruise Line
		    			{elseif $additional.businessRelation == "landOwner"} 
		    			   Land Owner
		    			{elseif $additional.businessRelation == "bookingAgent"} 
		    			   Booking Agent
		    			{elseif $additional.businessRelation == "other"} 									   {$additional.businessRelationOther}
		    			{/if})
		    			{/if}
	    		</p>
    		{/foreach}
	</div>
</body>
</html>
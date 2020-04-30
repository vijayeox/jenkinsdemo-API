{$Header=$data["Header"]}
{function looprows level=0}
{if $level==0}
	{$indenttext=""}
{/if}
{for $i=0 to $level}
	{$indenttext=$indenttext|cat:'&nbsp;'}
{/for}
{foreach key=key item=value from=$data}
    {if isset($value['type']) }
       {$type=$value['type']}
	{else}
	   {$type=''}
	{/if}
	{if $type eq 'Section'}
		{looprows data=$value level=$level+1}
	{else}
		{if $type eq 'Data'}
			{$trflag = 0}
				{foreach key=k item=v  from=$value['ColData']}
					{if isset($v['value']) && !empty($v['value'])}
						{if $trflag == 0}{$trflag =1}
							<tr>
						{/if}
						{if $k == 0}
						<td style = "padding: 8px 0px 8px 0px;">{$indenttext}{$v['value']}</td>
						{elseif $k ==1}
						<td style = "text-align:right;padding: 8px 0px 8px 0px;">${$v['value']|number_format:2:".":","}</td>
						{/if}
					{/if}
				{/foreach}
				{if $trflag == 1}
					</tr>
				{/if}
				{else}
				{if $key eq 'Header'}
					{if isset($value['ColData']) && !empty($value['ColData'])}
						<tr>
						{foreach key=k item=v from=$value['ColData']}
							{if !empty($v['value'])}
								{if $k == 0}
									<td style = "padding: 8px 0px 8px 0px;">{$indenttext}{$v['value']}</td>
								{elseif $k ==1}
									<td style = "text-align:right;padding: 8px 0px 8px 0px;">${$v['value']|number_format:2:".":","}</td>
								{/if}
							{/if}
						{/foreach}
						</tr>
					{/if}
					{elseif $key eq 'Rows'}
					{looprows data=$value['Row'] level=$level+1}
					{elseif $key eq 'Summary'}
					{if isset($value['ColData']) && !empty($value['ColData'])}
					<tr>
						{foreach key=k2 item=v2 from=$value['ColData']}
							{if !empty($v2['value'])}
								{if $k2 == 0}
									<td style = "padding: 8px 0px 8px 0px;"><hr style="height:1px;border-width:0;color:gray;background-color:gray"><b>{$indenttext}{$v2['value']}</b></td>
								{elseif $k2 == 1}
									<td style = "text-align:right;padding: 8px 0px 8px 0px;"><hr style="height:1px;border-width:0;color:gray;background-color:gray"><b>${$v2['value']|number_format:2:".":","}</b></td>
								{/if}
							{/if}
						{/foreach}
						</tr>
						{/if}
					 {else}
					 {if isset($value['ColData']) && !empty($value['ColData'])}
					 <tr>
						{foreach key=k2 item=v2 from=$value['ColData']}
							{if $k2 == 0}
								<td style = "padding: 8px 0px 8px 0px;"><hrstyle="height:1px;border-width:0;color:gray;background-color:gray"><b>{$indenttext}{$v2['value']}</b></td>
							{elseif $k2 == 1}
								<td style = "text-align:right;padding: 8px 0px 8px 0px;"><hr style="height:1px;border-width:0;color:gray;background-color:gray"><b>${$v2['value']|number_format:2:".":","}</b></td>
							{/if}
						{/foreach}
					</tr>
					{/if}
				{/if}
			{/if}
		{/if}
	{/foreach}
{/function}{function createrows} 
	{foreach key=key item=value from=$data}
		{if (isset($value) && ($value!=empty))}
		   {looprows data=$value}
		{/if}
	{/foreach}
{/function}<table style = "border-spacing: 0; width: 100%; font-size: 28px; text-align: left;"><tr><th>Report Name : {$Header['ReportName']}</th></tr></table>
<table style = "border-spacing: 0; width: 100%; font-size: 26px;">
	<tr>{if isset($Header['StartPeriod']) && !empty($Header['StartPeriod'])}<th style = "text-align: left;">Period : {$Header['StartPeriod']} to {$Header['EndPeriod']}</th>{/if}
		{if isset ($Header['ReportBasis']) && !empty($Header['ReportBasis'])}<th style = "text-align: center;">Payment Type : {$Header['ReportBasis']}</th>{/if}
		{if isset ($Header['Currency']) && !empty($Header['Currency'])}<th style = "text-align: right;">Currency : {$Header['Currency']}</th>{/if}
	</tr>
</table>
<hr>
<div style = 'border-width:5px;border-bottom-style:double;'>
<table style = "border-spacing: 0; width: 100%; padding 8px;">
	<thead>
		<tr>{foreach name=outer item=column from=$data["Columns"]}
				{foreach key=key item=item from=$column}
					{if $key == 0}
						<th>{$item['ColTitle']}</th>
					{elseif $key ==1}
						<th style = "text-align:right; padding: 8px 0px 8px 0px; font-size: 18px;">{$item['ColTitle']}</th>
					{/if}
				{/foreach}
			{/foreach}
		</tr>
	</thead>
	<tbody>{createrows data=$data['Rows']}</tbody>
</table>
</div>
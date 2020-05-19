{$Header=$data["Header"]}
{function looprows level=0}
{$TAFlag = 0}
{$indenttext=""}
{for $i=1 to $level}
    {$indenttext=$indenttext|cat:'&nbsp;&nbsp;&nbsp;&nbsp;'}
{/for}
{foreach key=key item=value from=$data}
{if isset($value['type']) }
{$type=$value['type']}
{else}
{$type=''}
{/if}
{if $type eq 'Section'}
{looprows data=$value level=$level}
{else}
{if $type eq 'Data'}
{$trflag = 0}
{foreach key=k item=v from=$value['ColData']}
{if isset($v['value']) && !empty($v['value'])}
{if $trflag == 0}{$trflag =1}
<tr>
    {/if}
    {if $k == 0}
    <td style="padding: 8px 0px 8px 0px;">{$indenttext}{$v['value']}</td>
    {elseif $k ==1}
    <td style="text-align:right;padding: 8px 0px 8px 0px;">$&nbsp;{$v['value']|number_format:2:".":","}</td>
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
    <td style="padding: 8px 0px 8px 0px;">{$indenttext}{$v['value']}</td>
    {elseif $k ==1}
    <td style="text-align:right;padding: 8px 0px 8px 0px;">$&nbsp;{$v['value']|number_format:2:".":","}</td>
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
    <td style="padding: 8px 0px 8px 0px;">
        <hr style="height:1px;border-width:0;color:gray;background-color:gray"><b>{$indenttext}{$v2['value']}</b>{if $v2['value'] == 'TOTAL ASSETS'}<div style = 'border-width:5px;border-bottom-style:double;margin-top:5px;'></div>{$TAFlag = 1}{/if}</td>
    {elseif $k2 == 1}
    <td style="text-align:right;padding: 8px 0px 8px 0px;">
        <hr style="height:1px;border-width:0;color:gray;background-color:gray"><b>$&nbsp;{$v2['value']|number_format:2:".":","}</b>{if $TAFlag == 1}<div style = 'border-width:5px;border-bottom-style:double;margin-top:5px;'></div>{$TAFlag = 0}{/if}</td>
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
    <td style="padding: 8px 0px 8px 0px;">
        <hrstyle="height:1px;border-width:0;color:gray;background-color:gray"><b>{$indenttext}{$v2['value']}</b>
    </td>
    {elseif $k2 == 1}
    <td style="text-align:right;padding: 8px 0px 8px 0px;">
        <hr style="height:1px;border-width:0;color:gray;background-color:gray"><b>$&nbsp;{$v2['value']|number_format:2:".":","}</b></td>
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
{/function}<div class="oxzion-widget-content" style='border-width:5px;border-bottom-style:double;'>
    <table style="border-spacing: 0; width: 100%; padding 8px;">
        <tbody>{createrows data=$data['Rows']}</tbody>
    </table>
</div>
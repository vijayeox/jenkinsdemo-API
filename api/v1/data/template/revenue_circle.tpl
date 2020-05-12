<div class="oxzion-widget-content">
{$result=$data}
{$resultratio=$result/$target}
{$curve=2*3.14*60*$resultratio}
{if $resultratio<0.35}
  {$color='#f1230b'}
  {$Fontcolor='#f1230b'}{*Red*}
  {elseif $resultratio<.70}
    {$color = 'yellow'}
    {$Fontcolor='#d6c319'}{*Yellow*}
  {else}
    {$color='royalblue'}
    {$Fontcolor='mediumblue'}{*blue*}
{/if}
<svg height="150" width="150">
  <circle cx="75" cy="72" r="50" stroke="rgba(211, 211, 211, 0.45)" stroke-dasharray="314, 314" stroke-width="5" fill=none  />  
  <circle cx="75" cy="72" r="60" stroke="{$color}" stroke-linecap="round" stroke-dasharray="{$curve}, 376.8" stroke-width="15" fill=none  />  
    Sorry, your browser does not support inline SVG.  
    <text x="50%" y="50%" text-anchor="middle"  fill="{$Fontcolor}" stroke-width="2px" dy=".3em" style=" font: 20px;">$ {$result} M</text>
</svg>
</div>
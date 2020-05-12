<div>
{$result=$data}
{$resultratio=$result/$target}
{$curve=2*3.14*60*$resultratio}
{if $resultratio<0.35}
  {$color='#f7614f'}
  {$Fontcolor='#f1230b'}{*lightred*}
  {elseif $resultratio<.70}
    {$color = '#f3412c'}
    {$Fontcolor='#f1230b'}{*mediumred*}
  {else}
    {$color='#f1230b'}
    {$Fontcolor='#f1230b'}{*darkred*}
{/if}
<svg height="150" width="150">
  <circle cx="75" cy="72" r="50" stroke="rgba(211, 211, 211, 0.45)" stroke-dasharray="314, 314" stroke-width="5" fill=none  />  
  <circle cx="75" cy="72" r="60" stroke="{$color}" stroke-dasharray="{$curve}, 376.8" stroke-width="15" fill=none  />  
    Sorry, your browser does not support inline SVG.  
    <text x="50%" y="50%" text-anchor="middle" fill="{$Fontcolor}" stroke-width="2px" dy=".3em" style=" font: 20px;">$ {$result} M</text>
</svg>
</div>
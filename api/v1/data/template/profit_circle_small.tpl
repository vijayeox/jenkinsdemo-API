<div>
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
    {$color='limegreen'}
    {$Fontcolor='#1d9c1d'}{*green*}
{/if}
<svg height="160" width="160">
  <circle cx="80" cy="80" r="60" stroke="rgba(211, 211, 211, 0.45)" stroke-dasharray="376.8, 376.8" stroke-width="15" fill=none  />  
  <circle cx="80" cy="80" r="60" stroke="{$color}" stroke-linecap="round" stroke-dasharray="{$curve}, 376.8" stroke-width="15" fill=none  />  
    Sorry, your browser does not support inline SVG.  
    <text x="50%" y="50%" text-anchor="middle"  fill="{$Fontcolor}" stroke-width="2px" dy=".3em" style=" font: 20px;">$ {$result} M</text>
</svg>
</div>
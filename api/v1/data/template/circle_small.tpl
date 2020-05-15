<div class="oxzion-widget-content">
    {$result=$data}
    {$resultratio=$result/$target}
    {$curve=2*3.14*60*$resultratio}
    {if $resultratio<0.35} {$color='red' } {*Red*} {elseif $resultratio<.70} {$color='#ffee55' } {*Yellow*} {else} {$color='#35bd35' } {*Green*} {/if} <svg height="150" width="150">
        <circle cx="75" cy="72" fill="none" r="50" stroke="rgba(211, 211, 211, 0.45)" stroke-dasharray="628, 628" stroke-width="5" ;></circle>
        <circle cx="75" cy="72" fill="none" r="60" stroke="${color}" stroke-dasharray="{$curve}, 628" stroke-linecap="round" stroke-width="15"></circle>
        Sorry, your browser does not support inline SVG.
        <text dy=".3em" stroke-width="2px" style="font: 20px serif;" fill="{$color}" text-anchor="middle" x="50%" y="50%">{$result}</text>
        </svg>
</div>
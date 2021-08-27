{$result=$data}
{$resultratio=$result/$target}
{$curve=2*3.14*100*$resultratio}
{if $resultratio<0.35} {$color='red' } {elseif $resultratio<.70} {$color='yellow' } {else} {$color='green' } {/if}<div class="oxzion-widget-content"><svg height="250" width="250">
        <circle cx="120" cy="120" r="100" stroke="grey" stroke-dasharray="628, 628" stroke-width="30" fill=none />
        <circle cx="120" cy="120" r="100" stroke="{$color}" stroke-dasharray="{$curve}, 628" stroke-width="30" fill=none />
        Sorry, your browser does not support inline SVG.
        <text x="50%" y="50%" text-anchor="middle" stroke-width="2px" dy=".3em" style=" font: 40px serif;">{$result}</text>
    </svg></div>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>ABEXCESS</title>
    <style>
        * {
            line-height: 220% !important;
            text-align: left;
        }

        .section_black {
            background-color: #000;
            color: #bad421;
        }

        .bold {
            font-weight: bold;
        }

        .m-4 {
            margin: 4px;
        }

        .inline {
            display: inline-block;
        }

        .float-left {
            float: left;
        }

        .float-right {
            float: right;
        }

        .ml-4 {
            margin-left: 4px;
        }

        .ml-6 {
            margin-left: 6px;
        }

        .mr-4 {
            margin-right: 4px;
        }

        .mt-4 {
            margin-top: 4px;
        }

        .mb-4 {
            margin-bottom: 4px;
        }

        .pr-4 {
            padding-right: 4px;
        }

        .pl-4 {
            padding-left: 4px;
        }

        .pt-4 {
            padding-top: 4px;
        }

        .pb-4 {
            padding-bottom: 4px;
        }

        .m-4 {
            margin: 4px;
        }

        .p-4 {
            padding: 4px;
        }

        .underlined {
            text-decoration: underline;
        }

        .continued {
            color: #646464;
            font-style: italic;
            font-weight: bold;
        }
    </style>
    <style>
        /* #inline {
        display: flex;
        flex-direction: row;
    } */

        #p1img1 {
            width: 20%;
            height: 20%;
        }

        .ft0 {
            font-family: "Arial Black";
            font-weight: bold;
            text-align: left;
            padding-left: 40em;
            margin-top: 109px;
        }

        .rotate {

            transform: rotate(-90deg);


            /* Legacy vendor prefixes that you probably don't need... */

            /* Safari */
            -webkit-transform: rotate(-90deg);

            /* Firefox */
            -moz-transform: rotate(-90deg);

            /* IE */
            -ms-transform: rotate(-90deg);

            /* Opera */
            -o-transform: rotate(-90deg);

        }

        #sectionContent {
            font: 13px "Calibri";
            margin-left: 5%;
        }

        #driverInfo {
            position: relative;
            display: inline-block;
            width: calc(100% - 17px);
            width: -webkit-calc(100% - 17px);
            width: -moz-calc(100% - 17px);
        }

        /* .tr0 {
        height: 18px;
    }

    .td0 {
        padding: 0px;
        margin: 0px;
        width: 80px;
        vertical-align: bottom;
        background: #000000;
    }

    .td1 {
    padding: 0px;
    margin: 0px;
    width: 208px;
    vertical-align: bottom;
  } */

        .p1 {
            text-align: left;
            padding-left: 4px;
            margin-top: 0px;
            margin-bottom: 0px;
            white-space: nowrap;
            background: #000000;
        }

        .p2 {
            text-align: left;
            padding-left: 7px;
            margin-top: 0px;
            margin-bottom: 0px;
            white-space: nowrap;
        }

        .ft1 {
            font-: bold 12px "Arial Black";
            color: #bbd422;
            line-height: 17px;
        }

        .ft2 {
            font: bold 12px "Arial Black";
            line-height: 17px;
        }

        .underline {
            text-decoration: underline;
            margin-right: 2%;
            margin-right: 2%;
        }

        .to-be-deleted {
            color: lightgrey !important;
        }
    </style>
</head>

<body>
    <div id="inline">
        <img src="data:image/png;base64,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Q4YM2bJli/s9m826R6flaGtrAyCEIIS8+mqfe1U//fRTVd+xr8Cto90HaaHtODNmzOhrymDDhg0PPPDA888/n4v2zi7zGzDTp0/vdfe000774x//mH9l9erV7mwqpTQUCSOvjUSxwzj8ww8/vLNvB5AibV/2RS/p9GLn6ezcVHtBqqqqct93bWDXtu0zzyxwzEou3By5tejuT75169ZejufMmbOzD08//bRhGOPGjfvlL3+5cuXKnD87p8ht7PZFJLKbUwQIIbmo5l9vaGjY9YP7mQFWXqbTuzuF6fOUlpbu4qSz5uYdh9kUtMU6d+45jz76OABCyDPPPtfQ0FBfX9/LTU1NzXcu/86O/4W7aZEQAkpoNBrt5b5gQHPmzHErfc55TU3NvHnzjj766EMPPfS2225zByZz1NXVNTY2ut9XrVo1bty4/LvvvfdeX4kVQvzi579wOxQ7otrDyJG7t32yPylSXUqSJAotKLFt2+1C5t91q86CXdExY8Zs2bKlr7sNDQ25jm3+YmTBLUrpz3/+8+9///vJRNezzz5LCKFEjB49euelJJIkXXvNtXuZTgBAZ2enrutuDO+9994LLrggdyuT6X2oxIknnvj222+7hfG3v/3tRYsW5W4xxt54441eVX80Gu3s7KQgXIjvXHbZriuc4qFI63G3VffEE587dOe73/1uLtOnTJmyJ/787Gc/czsZkiStXr06/9Y777zj1mVCCJ/Pt3ONeeGFFwLoFYdzzjnH/VJaWuq+G5s2beol1lgsVlZWFo1Gy8vL9ySSuWOohRDf/ObnjvR75ZVXejm+/PLLc+/Y0qVL//SnbpsljLGCeXLLLbe47gkhf/jDH/Jvbd682Y1nWVnZzu2NA0uR6tLlggsuMAzjW9/61ty5c91ho9w43DvvvLMnPkyaNOncc89Fz88WiUTOP//8efPm+f3+E044IbdoKBbr08wQISSdTrtDkrIsP/300+eddx6A9evX5/QRDoerqqouuOCCCy64IBQKVVZWplKpZDI5dOjQPYlkOBzOhTVo0KBNmzbFYrHVq1dXVVWtW7eul+NAIPD444+7rwSl9Morr3Q74H6//7PPPst5leNb3/rWwQcf7L7n7hzE7NmzL7roooMPPnjkyJGpVCoej5988sk1NTUoKvZn539PmDdvXm7cJ3+OJzc50Wt8JDd+qShKX34ecsghufkVn8+X76Esy42NjTmX2WzWva4oSm4eUghxzz335MZrFEXZtm2bEKKxsdEdr8n30O/3u5HUNI0x5j7+s5/9LOeg16CsC6W0oCeKouQu5o+C3Xfffe4klmEY7jCqoigzZ868/vrr3Sj1moesrq52J8PcKS4XN2+rq6v/3d9oP1B05aUQgnPOGHMc57nnnhM9xu9yvPnmm71GNNyZG9H3AucPPvjgz3/+s9NDzqujjjoqHo/X1dX18s3NmvyLF198cTAYdBujlNJhw4YBqKura21tnThxYn703CC+9rWvpdPpXHm820P7nn/+edfzfE9mzZq1devW3MWnnnoq537evHmpVOquu+464YQTDj300P/5n/9pbGz8xz/+0VcObNmy5fvf/36v5DuOc9lll+VGyoqKoluvvjNNTU3Lly9PJBJTp04dPnxPjyEryCeffLJ+/XpCyKhRo3r1ZL8gy5Yta2ho0DRt9OjRe923Xb9+/YoVK4QQY8aMGTNmTEE3nHN3QRoAVVV7rag/+eST33jjDQBf/epXFy5cuPPjW7ZsWbVqVSwWq62tPfLII/cunvuBAaBLj3xSqVQkEskNI+Qv1ctms+Fw2L3+7LPPFuOs9x7j6XLgEQwG7R47YOFw+Fe/+lVdXd2aNWu+973vAXB7YzsPMA0sPF0OPBKJREVFhbsYyl1C5TZb3b9CiMbGxsrKygMdzS9E0fV7PHZLMBhMpVLHH3+820XL9aXcImbRokUDXZTwysuBTiwWc2fMFUUJBAKa1u+WKfcPni49ihGvHvcoRr6Euty6deuKFSsOdCwODOl0etmyZQc2DitWrNgHq+a+yGTRTTfd5PP56urqamtrKaUPP/ywEKLgfOAVV1zxxBNP9Lo4efLkLxK6EGLNmjWBQCD3r2mahmEceuihs2bNcjfE7IWf4XC44HU3Xddee+1bb73V17Pu8E1dXV1dXV0gEKivr9+LCOwdnPPRo0fX1tbOnTuXEPLhhx/u8yB6qeXFF1+cNWtW/pUHHnhAkqQTTjhh2rRpmqa5Wyv3Mqy9flIIcdNNN5199tm5f91titOnT89kMjNmzHAvrly58uqrr7799tvdSe05c+bU1dW5irnwwgtdN/fff39dXd3UqVPdva1XXnnlsmXLRo4c+YMf/KBXiNdee21dXd0xxxxjWdb69euHDx8+ZMiQ3N2f/OQnp59+eu7fSy65RAhxzDHH/PWvf62rq/v973/vXl+xYsXQoUMnTZoUi8WEEIyx0047ra6u7r/+67+EECeffLLrbO7cuXV1dYcffrht20IIRVGefvrp8vLy8ePHP/LII++9957r7LTTTkulUu53V5e5CEyaNMlxnIsuuugXv/jFV77yFdu2TzzxxLq6uly62traJk2aNGbMmJkzZwohTj/99G3btrkbkW+++ea6urpRo0atW7fOTcXjjz8+bNiwjRs3nnnmmXV1dX/5y1/ycyYSibiba12GDh0qhLjqqqts23Z3evztb3+rr6+fOHHi5s2bhRBXX3216/KGG25YtmzZW2+9dcsttxx++OF1dXU7lyAuu9blq6++mj/Vnk6nczm5F+xLXd56662ip1yJRCKZTEYIcfjhh2/cuNEtL6+55prrrrsum826k7NueXn//ffX1tam0+m1a9e6KylnzJgxduzYdDpdVlbmrp50+cY3vvHVr341k8m8/PLLiqLYtn3rrbfOnz8/5yCZTFJKS0tLr7zyyo8++qg7hcAZZ5yRyWSqqqqefPLJzZs3h0KhZDK5adMmd2qktrb2sccey2azl1122XnnneeWl+ecc86CBQuy2exvfvObb3/72266MpnMnDlzHn300XQ6PWbMGCEEYywUCuUikK/LxsZGWZZt29Z1/Zprruno6NA07ZlnnslmsyeddNLpp5/uOt60aZO7zk0IUVVVFY1GN27cuHTp0ilTpmQymQ0bNuQ2Ztx2221Lly4FsGXLltwjO37Inn87Ojqam5ubm5s558cff7zf71+1atVLL73kpnrz5s0AUqnUV77yFdf9WWed9frrry9cuFCSpI6Ojng8Ho1G33777Z1/7l3r8owzzrjjjjt2emgv2ZfrgvPX3v7ud7+76qqr/vjHPy5btiy3xvvkk0+eNWtWMpmMx+O59Y4//elPX3zxRcMwRo4cOXPmzJdffhnAvffeaxhGTU1NR0dHbgnWk08+mUqldF3/yle+EggEmpubA4FA/r4Cv9/PGGtoaHjppZdOOeUUSqn7Mzz00EPufsjZs2dPnTr15JNPdm1BVVdXt7a2NjU1uasq7777bvRsRXjsscc2btz4yCOPLFiw4Oijj3b9z23uNgzDXYF255137myRYsSIEQDchg0Ay7Juu+020zQZY6eeeiqAl156iRCyfPnyI444YvDgwa5j99nPPvssEonU19e/8sorL774ots0cm9dc801AAKBwK7XpF177bVLly79+OOP3YX6zz777NixYy+++OJXX33V7/f7/f65c+fubLYEwFlnnVVSUgLgqaee+uEPf/j222/vIpSdEX0svt479mW/Z8GCBbnvc+fOXbBgwdKlS93ljy4zZ860LGv27NnBYLCpqcm9yDnPmUbWdd0tRfpad5P7/SilO29AmzFjxubNm+vr6y+77LKtW7e2tbW5+3tc3zRNsyyLEDJ27Nijjz766KOPfvLJJ30+X25jgxAit8tnwoQJN9100/Tp0wv+hACuvvrqhx566IYbbrjiiit63Vq3bt26devWrl179tlnA3BHuYUQvWzE2XlnCuXE55r5e+SRRw455JCamppHH320l+d95Yyu6+7KoHvuueejjz7KzxMAjuO4PgPw+XwFDeDkxj51XU+l9visoB7OOuusO+64I/evaZpfyB75Fylsc/V4KpU677zz/H6/yOv3jBgxYsiQIZ2dnaKn33P22Wf/7ne/E0IMHz78448/duvxu+++260Tm5ub3Yp1xowZixcvFkJMmjTpk08+yQU3a9asb33rW0KITz/91N1Ncdddd+WvSrz33nvLy8vd5qC7CcZ9g8877zwhxIQJE/7yl780NDREo1HOubslwzTNcDjstncvvfTSq666yq3HAbitz+nTp7uPu+maN2/e7bff7iZZluWDDjooP0N6tS/dK1VVVe53SZLcPeyXXnrpiSee6K5y3759u/tFCFFVVeVG/pRTTnnssceEEPfccw96VOt64lo6EDvVqq+//rqu665RF3flcjKZPP744927CxcuLC8v55zH43H3ltu+zGazkiS59XhuF3l9ff2rr74qhPjhD3+YHwR2WY+7WZ1rxA8ePPgXv/iF2Fu+kC7/8Ic/jBs3bty4cRMmTLj++uvdLdi5/dQfffSRu1JaCPHzn//8pZdeYowde+yxkUjkyiuvFELMmTPHvXvzzTeXl5cPHz7cbbnPmzfPbR3Onj177dq1+SGec845JSUlY8eObW1tFUI8/vjjd999d76DO++8s6KiIhQKjR07tqmpSQgB4Ne//nVFRcVVV13lunn44Yerqqpqampcoz9dXV1TpkwpKSk588wzhRCHHXaYEOLll18eNGjQ0KFDly5dWl5enkvXp59+OmHCBLd6rampWbNmTX7ojuOMGzeu15Wc6ZjOzk43oHPPPdddMvzZZ5+NHz9+woQJ7nb44447zs3Dtra2YcOGVVRU3HnnnZWVlW+99VbO22nTprlfegUkhFi8ePGQIUNCodBtt912+eWXp9Ppiy66KHf39ttvHzRoUH19/ZIlS4QQpmlGo9HJkyffcsst77///sKFC+fNmzdmzJjy8vKbbrrJfST3DuRCzHHssce+/fbb+VeEEKlUatq0aaFQKBqNugXQXvPln+9xN0Ls81MULMvKbRbbOzKZzIgRIzZv3kwI0XV9/xtHzufpoq8TdgAAAN1JREFUp59+7bXXem0AGjVqVC97O/uNIt0PuQ+pqanpD9PDfr//n//85xfxwTCMm2++2RX3LjYT7x8Mw9h57/maNWsOSGTgzY97FCdfwnlIjy8Bni49ihFPlx7FiKdLj2LE06VHMeLp0qMY8XTpUYx4uvQoRjxdehQjni49ihFPlx7FiKdLj2LE06VHMeLp0qMY8XTpUYx4uvQoRjxdehQjni49ihFPlx7FiKdLj2LE06VHMeLp0qMY8XTpUYx4uvQoRjxdehQjni49ihFPlx7FiKdLj2LE06VHMfL/AWkAOQ75lSQTAAAAAElFTkSuQmCC"
            id="p1img1" />
        <p class="p0 ft0">Excess Coverage Application</p>
    </div>

    {* Section 1 *}
    <section class="mb-4">
        <div class="mb-4">
            <h3 class="bold"><span class="section_black mr-4">SECTION 1</span> GENERAL INFORMATION </h3>
        </div>

        <div id="sectionContent">
            <p>
                <strong>INSURED NAME: </strong><span class="underline">{$insuredName}</span>
                <strong>Quote by Date: </strong><span class="underline">{$quoteByDateFormatted}</span>
                <br>

                <strong>Address: </strong><span class="underline">{$address}</span>
                <strong>Desired Policy Effective Date: </strong><span
                    class="underline">{$desiredPolicyEffectiveDate}</span>
                <br>

                <strong>City/State/Zip: </strong><span class="underline">{$csz}</span>
                <br>
                <div class="mt-4 mb-4">
                    {if $corporation == true || $corporation == "true"}
                        <input id="Corporation" type="checkbox" name="Corporation" checked readonly />
                        <label for="Corporation">Corporation</label>
                    {else}
                        <input id="Corporation" type="checkbox" name="Corporation" readonly />
                        <label for="Corporation">Corporation</label>
                    {/if}


                    {if $partnership == true || $partnership == "true"}
                        <input id="Partnership" type="checkbox" name="Partnership" checked readonly />
                        <label for="Partnership">Partnership</label>
                    {else}
                        <input id="Partnership" type="checkbox" name="Partnership" readonly />
                        <label for="Partnership">Partnership</label>
                    {/if}

                    {if $proprietorship == true || $proprietorship == "true"}
                        <input id="Proprietorship" type="checkbox" name="Proprietorship" checked readonly />
                        <label for="Proprietorship">Proprietorship</label>
                    {else}
                        <input id="Proprietorship" type="checkbox" name="Proprietorship" readonly />
                        <label for="Proprietorship">Proprietorship</label>
                    {/if}

                    {if $llc == true || $llc == "true"}
                        <input id="LLC" type="checkbox" name="LLC" checked readonly />
                        <label for="LLC">LLC</label>
                    {else}
                        <input id="LLC" type="checkbox" name="LLC" readonly />
                        <label for="LLC">LLC</label>
                    {/if}
                    <br>

                    {if $commonCarrier == true || $commonCarrier == "true"}
                        <input id="Common Carrier" type="checkbox" name="Common Carrier" checked readonly />
                        <label for="Common Carrier">Common Carrier</label>
                    {else}
                        <input id="Common Carrier" type="checkbox" name="Common Carrier" readonly />
                        <label for="Common Carrier">Common Carrier</label>
                    {/if}

                    {if $contractCarrier == true || $contractCarrier == "true"}
                        <input id="Contract Carrier" type="checkbox" name="Contract Carrier" checked readonly />
                        <label for="Contract Carrier">Contract Carrier</label>
                    {else}
                        <input id="Contract Carrier" type="checkbox" name="Contract Carrier" readonly />
                        <label for="Contract Carrier">Contract Carrier</label>
                    {/if}

                    {if $privateCarrier == true || $privateCarrier == "true"}
                        <input id="Private Carrier" type="checkbox" name="Private Carrier" checked readonly />
                        <label for="Private Carrier">Private Carrier</label>
                    {else}
                        <input id="Private Carrier" type="checkbox" name="Private Carrier" readonly />
                        <label for="Private Carrier">Private Carrier</label>
                    {/if}

                    {if $freightBroker == true || $freightBroker == "true"}
                        <input id="Freight Broker" type="checkbox" name="Freight Broker" checked readonly />
                        <label for="Freight Broker">Freight Broker</label>
                    {else}
                        <input id="Freight Broker" type="checkbox" name="Freight Broker" readonly />
                        <label for="Freight Broker">Freight Broker</label>
                    {/if}
                    <br>
                </div>
                

                {if isset($usDot)}
                    <strong>US DOT#: </strong><span class="underline">{$usDot}</span>
                {else}
                    <strong>US DOT#: </strong><span class="underline"> </span>
                {/if}
                <strong>MC Docket: </strong><span class="underline">{$mcDocket}</span><br>

                <strong>ELD Provider: </strong><span class="underline">{$eldProvider}</span>
                <strong>ELD Account #: </strong><span class="underline">{$eldAccountNumber}</span><br>
            </p>
            <p>
                <strong>AGENT INFORMATION</strong><br>
                <strong>Hub Office: </strong><span class="underline">{$producerRegion}</span>
                <strong>Producer’s Name: </strong><span class="underline">{$producersName}</span><br>
                <strong>Hub Office Address: </strong><span class="underline">{$hubOfficeAddress}</span>
            </p>
            <p>
                Is this quote in excess of the Primary Limit?
                {if $isThisQuoteInExcessOfThePrimaryLimit == "yes"}
                    <input id="isThisQuoteInExcessOfThePrimaryLimit_Y" type="checkbox"
                        name="isThisQuoteInExcessOfThePrimaryLimit_Y" checked readonly />
                    <label for="isThisQuoteInExcessOfThePrimaryLimit_Y">Y</label>
                    <input id="isThisQuoteInExcessOfThePrimaryLimit_N" type="checkbox"
                        name="isThisQuoteInExcessOfThePrimaryLimit_N" readonly />
                    <label for="isThisQuoteInExcessOfThePrimaryLimit_N">N</label>
                {elseif $isThisQuoteInExcessOfThePrimaryLimit == "no"}
                    <input id="isThisQuoteInExcessOfThePrimaryLimit_Y" type="checkbox"
                        name="isThisQuoteInExcessOfThePrimaryLimit_Y" readonly />
                    <label for="isThisQuoteInExcessOfThePrimaryLimit_Y">Y</label>
                    <input id="isThisQuoteInExcessOfThePrimaryLimit_N" type="checkbox"
                        name="isThisQuoteInExcessOfThePrimaryLimit_N" checked readonly />
                    <label for="isThisQuoteInExcessOfThePrimaryLimit_N">N</label>
                {else}
                    <input id="isThisQuoteInExcessOfThePrimaryLimit_Y" type="checkbox"
                        name="isThisQuoteInExcessOfThePrimaryLimit_Y" readonly />
                    <label for="isThisQuoteInExcessOfThePrimaryLimit_Y">Y</label>
                    <input id="isThisQuoteInExcessOfThePrimaryLimit_N" type="checkbox"
                        name="isThisQuoteInExcessOfThePrimaryLimit_N" readonly />
                    <label for="isThisQuoteInExcessOfThePrimaryLimit_N">N</label>
                {/if}
            </p>
            <p>
                If No, advises what limit is this quote excess of:
                {if isset($ifNoAdviseWhatLimitIsThisQuoteExcessOf)}
                    <span class="underline">{$ifNoAdviseWhatLimitIsThisQuoteExcessOf}</span>
                {/if}
            </p>
            <p>
                <strong>Coverage(s) Requested:</strong><br>
                <strong>Limits Needed in Excess Layer: </strong><span class="underline">{$select5}</span>
            </p>
            <p>
                {if $excessCgl == true || $excessCgl == "true"}
                    <input id="Excess CGL Only" type="checkbox" name="Excess CGL Only" checked readonly />
                    <label for="Excess CGL Only">Excess CGL Only</label>
                {else}
                    <input id="Excess CGL Only" type="checkbox" name="Excess CGL Only" readonly />
                    <label for="Excess CGL Only">Excess CGL Only</label>
                {/if}
                <br>
                {if $excessAutoLiability == true || $excessAutoLiability == "true"}
                    <input id="Excess Auto Liability Only" type="checkbox" name="Excess Auto Liability Only" checked
                        readonly />
                    <label for="Excess Auto Liability Only">Excess Auto Liability Only</label>
                {else}
                    <input id="Excess Auto Liability Only" type="checkbox" name="Excess Auto Liability Only" readonly />
                    <label for="Excess Auto Liability Only">Excess Auto Liability Only</label>
                {/if}
                <br>
                {if $excessEmployersLiability == true || $excessEmployersLiability == "true"}
                    <input id="Excess GL/Auto/WC EL" type="checkbox" name="Excess GL/Auto/WC EL" checked readonly />
                    <label for="Excess GL/Auto/WC EL">Excess GL/Auto/WC EL</label>
                {else}
                    <input id="Excess GL/Auto/WC EL" type="checkbox" name="Excess GL/Auto/WC EL" readonly />
                    <label for="Excess GL/Auto/WC EL">Excess GL/Auto/WC EL</label>
                {/if}
                <br>
                {if $excessGlAl == true || $excessGlAl == "true"}
                    <input id="Excess (GL + AL only)" type="checkbox" name="Excess (GL + AL only)" checked readonly />
                    <label for="Excess (GL + AL only)">Excess (GL + AL only)</label>
                {else}
                    <input id="Excess (GL + AL only)" type="checkbox" name="Excess (GL + AL only)" readonly />
                    <label for="Excess (GL + AL only)">Excess (GL + AL only)</label>
                {/if}
                <br>
            </p>
            <p>
                <strong>Target Premium: </strong><span class="underline">
                    {if isset($targetPremium)}
                        {$targetPremium}
                    {/if}
                </span>
            </p>
            <table>
                <tr>
                    <th><strong>Prior Excess Coverage(s)<strong> (Check all that apply):</th>
                    <th><strong>Carrier</strong></th>
                    <th><strong>Expiring Premium</strong></th>
                </tr>
                <tr>
                    <td>
                        {if $cgl == true || $cgl == "true"}
                            <input id="Excess CGL" type="checkbox" name="Excess CGL" checked readonly />
                            <label for="Excess CGL">Excess CGL</label>
                        {else}
                            <input id="Excess CGL" type="checkbox" name="Excess CGL" readonly />
                            <label for="Excess CGL">Excess CGL</label>
                        {/if}
                    </td>
                    <td>
                        {$carier}
                    </td>
                    <td>
                        {if isset($currency)}
                            {$currency}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        {if $commercialAutoLiability == true || $commercialAutoLiability == "true"}
                            <input id="Excess Auto Liability" type="checkbox" name="Excess Auto Liability" checked
                                readonly />
                            <label for="Excess Auto Liability">Excess Auto Liability</label>
                        {else}
                            <input id="Excess Auto Liability" type="checkbox" name="Excess Auto Liability" readonly />
                            <label for="Excess Auto Liability">Excess Auto Liability</label>
                        {/if}
                    </td>
                    <td>
                        {$carrier2}
                    </td>
                    <td>
                        {if isset($currency1)}
                            {$currency1}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        {if $employersLiability == true || $employersLiability == "true"}
                            <input id="Excess GL/Auto/WC EL" type="checkbox" name="Excess GL/Auto/WC EL" checked readonly />
                            <label for="Excess GL/Auto/WC EL">Excess GL/Auto/WC EL</label>
                        {else}
                            <input id="Excess GL/Auto/WC EL" type="checkbox" name="Excess GL/Auto/WC EL" readonly />
                            <label for="Excess GL/Auto/WC EL">Excess GL/Auto/WC EL</label>
                        {/if}
                    </td>
                    <td>
                        {$carrier3}
                    </td>
                    <td>
                        {if isset($currency2)}
                            {$currency2}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        {if $others == true || $others == "true"}
                            <input id="Excess (GL + AL only)" type="checkbox" name="Excess (GL + AL only)" checked
                                readonly />
                            <label for="Excess (GL + AL only)">Excess (GL + AL only)</label>
                        {else}
                            <input id="Excess (GL + AL only)" type="checkbox" name="Excess (GL + AL only)" readonly />
                            <label for="Excess (GL + AL only)">Excess (GL + AL only)</label>
                        {/if}
                    </td>
                    <td>
                        {$carrier4}
                    </td>
                    <td>
                        {if isset($currency3)}
                            {$currency3}
                        {/if}
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <th><strong>Summary of Underlying Coverage(s):</strong></th>
                    <th><strong>Deductible</strong></th>
                    <th><strong>Current Carrier</strong></th>
                    <th><strong>Premium</strong></th>
                    <th><strong>Limit</strong></th>
                </tr>
                <tr>
                    <td>
                        {if $autoLiability == true || $autoLiability == "true"}
                            <input id="Auto Liability" type="checkbox" name="Auto Liability" checked readonly />
                            <label for="Auto Liability">Auto Liability</label>
                        {else}
                            <input id="Auto Liability" type="checkbox" name="Auto Liability" readonly />
                            <label for="Auto Liability">Auto Liability</label>
                        {/if}
                    </td>
                    <td>
                        {if isset($deductible1)}
                            <span class="underline">${$deductible1}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($carrier1)}
                            <span class="underline">{$carrier1}</span>
                        {else}

                        {/if}
                    </td>
                    <td>
                        {if isset($premium1)}
                            <span class="underline">${$premium1}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($limit1)}
                            <span class="underline">${$limit1}</span>
                        {else}
                            $
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        {if $personalInjuryProtectionPip == true || $personalInjuryProtectionPip == "true"}
                            <input id="Personal Injury Protection (PIP)" type="checkbox"
                                name="Personal Injury Protection (PIP)" checked readonly />
                            <label for="Personal Injury Protection (PIP)">Personal Injury Protection (PIP)</label>
                        {else}
                            <input id="Personal Injury Protection (PIP)" type="checkbox"
                                name="Personal Injury Protection (PIP)" readonly />
                            <label for="Personal Injury Protection (PIP)">Personal Injury Protection (PIP)</label>
                        {/if}
                    </td>
                    <td>
                        {if isset($deductible2)}
                            <span class="underline">${$deductible2}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($carrier5)}
                            <span class="underline">{$carrier5}</span>
                        {else}

                        {/if}
                    </td>
                    <td>
                        {if isset($premium2)}
                            <span class="underline">${$premium2}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($limit2)}
                            <span class="underline">${$limit2}</span>
                        {else}
                            $
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        {if $umUim == true || $umUim == "true"}
                            <input id="UM/UIM" type="checkbox" name="UM/UIM" checked readonly />
                            <label for="UM/UIM">UM/UIM</label>
                        {else}
                            <input id="UM/UIM" type="checkbox" name="UM/UIM" readonly />
                            <label for="UM/UIM">UM/UIM</label>
                        {/if}
                    </td>
                    <td>
                        {if isset($deductible3)}
                            <span class="underline">${$deductible3}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($carrier6)}
                            <span class="underline">{$carrier6}</span>
                        {else}

                        {/if}
                    </td>
                    <td>
                        {if isset($premium3)}
                            <span class="underline">${$premium3}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($limit3)}
                            <span class="underline">${$limit3}</span>
                        {else}
                            $
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        {if $companyPhysicalDamage == true || $companyPhysicalDamage == "true"}
                            <input id="Company Physical Damage" type="checkbox" name="Company Physical Damage" checked
                                readonly />
                            <label for="Company Physical Damage">Company Physical Damage</label>
                        {else}
                            <input id="Company Physical Damage" type="checkbox" name="Company Physical Damage" readonly />
                            <label for="Company Physical Damage">Company Physical Damage</label>
                        {/if}
                    </td>
                    <td>
                        {if isset($deductible4)}
                            <span class="underline">${$deductible4}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($carrier7)}
                            <span class="underline">{$carrier7}</span>
                        {else}

                        {/if}
                    </td>
                    <td>
                        {if isset($premium4)}
                            <span class="underline">${$premium4}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($limit4)}
                            <span class="underline">${$limit4}</span>
                        {else}
                            $
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        {if $hiredAuto == true || $hiredAuto == "true"}
                            <input id="Hired Auto" type="checkbox" name="Hired Auto" checked readonly />
                            <label for="Hired Auto">Hired Auto</label>
                        {else}
                            <input id="Hired Auto" type="checkbox" name="Hired Auto" readonly />
                            <label for="Hired Auto">Hired Auto</label>
                        {/if}
                    </td>
                    <td>
                        {if isset($deductible5)}
                            <span class="underline">${$deductible5}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($carrier8)}
                            <span class="underline">{$carrier8}</span>
                        {else}

                        {/if}
                    </td>
                    <td>
                        {if isset($premium5)}
                            <span class="underline">${$premium5}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($limit5)}
                            <span class="underline">${$limit5}</span>
                        {else}
                            $
                        {/if}
                    </td>
                <tr>
                    <td>
                        {if $trailerInterchange == true || $trailerInterchange == "true"}
                            <input id="Trailer Interchange" type="checkbox" name="Trailer Interchange" checked readonly />
                            <label for="Trailer Interchange">Trailer Interchange</label>
                        {else}
                            <input id="Trailer Interchange" type="checkbox" name="Trailer Interchange" readonly />
                            <label for="Trailer Interchange">Trailer Interchange</label>
                        {/if}
                    </td>
                    <td>
                        {if isset($deductible6)}
                            <span class="underline">${$deductible6}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($carrier9)}
                            <span class="underline">{$carrier9}</span>
                        {else}

                        {/if}
                    </td>
                    <td>
                        {if isset($premium6)}
                            <span class="underline">${$premium6}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($limit6)}
                            <span class="underline">${$limit6}</span>
                        {else}
                            $
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        {if $generalLiability == true || $generalLiability == "true"}
                            <input id="General Liability" type="checkbox" name="General Liability" checked readonly />
                            <label for="General Liability">General Liability</label>
                        {else}
                            <input id="General Liability" type="checkbox" name="General Liability" readonly />
                            <label for="General Liability">General Liability</label>
                        {/if}
                    </td>
                    <td>
                        {if isset($deductible7)}
                            <span class="underline">${$deductible7}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($carrier10)}
                            <span class="underline">{$carrier10}</span>
                        {else}

                        {/if}
                    </td>
                    <td>
                        {if isset($premium7)}
                            <span class="underline">${$premium7}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($limit7)}
                            <span class="underline">${$limit7}</span>
                        {else}
                            $
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        {if $umbrella == true || $umbrella == "true"}
                            <input id="Umbrella" type="checkbox" name="Umbrella" checked readonly />
                            <label for="Umbrella">Umbrella</label>
                        {else}
                            <input id="Umbrella" type="checkbox" name="Umbrella" readonly />
                            <label for="Umbrella">Umbrella</label>
                        {/if}
                    </td>
                    <td>
                        {if isset($deductible9)}
                            <span class="underline">${$deductible9}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($carrier12)}
                            <span class="underline">{$carrier12}</span>
                        {else}

                        {/if}
                    </td>
                    <td>
                        {if isset($premium9)}
                            <span class="underline">${$premium9}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($limit9)}
                            <span class="underline">${$limit9}</span>
                        {else}
                            $
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        {if $employersLiability1 == true || $employersLiability1 == "true"}
                            <input id="Employee Liability" type="checkbox" name="Employee Liability" checked readonly />
                            <label for="Employee Liability">Employee Liability</label>
                        {else}
                            <input id="Employee Liability" type="checkbox" name="Employee Liability" readonly />
                            <label for="Employee Liability">Employee Liability</label>
                        {/if}
                    </td>
                    <td>
                        {if isset($deductible8)}
                            <span class="underline">${$deductible8}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($carrier11)}
                            <span class="underline">{$carrier11}</span>
                        {else}

                        {/if}
                    </td>
                    <td>
                        {if isset($premium8)}
                            <span class="underline">${$premium8}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($limit8)}
                            <span class="underline">${$limit8}</span>
                        {else}
                            $
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        {if $autoLiability == true || $autoLiability == "true"}
                            <input id="Garage Liability" type="checkbox" name="Garage Liability" checked readonly />
                            <label for="Garage Liability">Garage Liability</label>
                        {else}
                            <input id="Garage Liability" type="checkbox" name="Garage Liability" readonly />
                            <label for="Garage Liability">Garage Liability</label>
                        {/if}
                    </td>
                    <td>
                        {if isset($deductible10)}
                            <span class="underline">${$deductible10}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($carrier13)}
                            <span class="underline">{$carrier13}</span>
                        {else}

                        {/if}
                    </td>
                    <td>
                        {if isset($premium10)}
                            <span class="underline">${$premium10}</span>
                        {else}
                            $
                        {/if}
                    </td>
                    <td>
                        {if isset($limit10)}
                            <span class="underline">${$limit10}</span>
                        {else}
                            $
                        {/if}
                    </td>
                </tr>
            </table>
            {if isset($totalUnits)}
                Total Units <span class="underline">{$totalUnits}</span><br>
            {else}
                Total Units <br>
            {/if}
            {if isset($garageLiabilityRevenue)}
                Garage Liability Revenue <span class="underline">{$garageLiabilityRevenue}</span><br>
            {else}
                Garage Liability Revenue <br>
            {/if}
            <p class="continued">SECTION 1: GENERAL INFORMATION, continued</p>
            <p>
                <strong>Provide brief description of Operations and Ownership/Management experience:
                </strong><br>{$provideBriefDescriptionOfOperationsAndOwnershipManagementExperience}<br>
            </p>
            <strong>Garage Locations:</strong><br>
            <table>
                <tr>
                    <th><strong>Location</strong></th>
                    <th><strong>Address (Street, City, State, Zip Code)</strong></th>
                    <th><strong># Units Each Location</strong></th>
                </tr>
                {foreach from=$dataGrid item=item key=key}
                    <tr>
                        <td>{$key+1}</td>
                        <td>{$item.address1},{$item.city1},{if isset($item.state1.name)}{$item.state1.name}{/if},{if isset($item.zipCode1)}{$item.zipCode1}{/if}
                        </td>
                        <td>{$item.unitsEachLocation}</td>
                    </tr>
                {/foreach}
            </table>
            <table>
                <tr>
                    <th><strong>Type of Operation</strong></th>
                    <th><strong>Radius of Operation</strong></th>
                    <th><strong>Type of Units</strong></th>
                    <th><strong># of Units</strong></th>
                </tr>
                <tr>
                    <td>
                        Flatbed
                        {if isset($flatbed)}
                            <span class="underline">{$flatbed}</span>%
                        {else}
                            <span class="underline"> </span>%
                        {/if}
                    </td>
                    <td>
                        0-50 Local
                        {if isset($Local)}
                            <span class="underline">{$Local}</span>%
                        {else}
                            <span class="underline"> </span>%
                        {/if}
                    </td>
                    <td>Private Passenger</td>
                    <td>
                        {if isset($noOfPrivatePassengers)}
                            {$noOfPrivatePassengers}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        Intermodal
                        {if isset($intermodal)}
                            <span class="underline">{$intermodal}</span>%
                        {else}
                            <span class="underline"> </span>%
                        {/if}
                    </td>
                    <td>
                        51-200 Intermediate
                        {if isset($Intermediate)}
                            <span class="underline">{$Intermediate}</span>%
                        {else}
                            <span class="underline"> </span>%
                        {/if}
                    </td>
                    <td>Light Truck</td>
                    <td>
                        {if isset($noOfLightTruck)}
                            {$noOfLightTruck}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        Dry van
                        {if isset($dryVan)}
                            <span class="underline">{$dryVan}</span>%
                        {else}
                            <span class="underline"> </span>%
                        {/if}
                    </td>
                    <td>
                        201-Over Long Haul
                        {if isset($flatbed)}
                            <span class="underline">{$Local}</span>%
                        {else}
                            <span class="underline"> </span>%
                        {/if}
                    </td>
                    <td>Medium Truck</td>
                    <td>
                        {if isset($noOfMediumTruck)}
                            {$noOfMediumTruck}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        Tanker
                        {if isset($tanker)}
                            <span class="underline">{$tanker}</span>%
                        {else}
                            <span class="underline"> </span>%
                        {/if}
                    </td>
                    <td></td>
                    <td>Heavy Truck</td>
                    <td>
                        {if isset($noOfHeavyTruck)}
                            {$noOfHeavyTruck}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        Refrigerated
                        {if isset($refrigerated)}
                            <span class="underline">{$refrigerated}</span>%
                        {else}
                            <span class="underline"> </span>%
                        {/if}
                    </td>
                    <td></td>
                    <td>Extra Heavy</td>
                    <td>
                        {if isset($noOfExtraHeavy)}
                            {$noOfExtraHeavy}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        Dump
                        {if isset($dump)}
                            <span class="underline">{$dump}</span>%
                        {else}
                            <span class="underline"> </span>%
                        {/if}
                    </td>
                    <td></td>
                    <td>Cargo Van / Sprinter</td>
                    <td>
                        {if isset($noOfCargoVan)}
                            {$noOfCargoVan}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Other</td>
                    <td>
                        {if isset($otherContent)}
                            {$otherContent}
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        Total
                        {if isset($total)}
                            <span class="underline">{$total}</span>%
                        {else}
                            <span class="underline"> </span>%
                        {/if}
                    </td>
                    <td></td>
                    <td>Total</td>
                    <td>
                        {if isset($finalTotal)}
                            {$finalTotal}
                        {/if}
                    </td>
                </tr>
            </table>
            <strong>Overall Description of Commodities Hauled:</strong><br>
            <table>
                <tr>
                    <th><strong>Mileage & Revenues</strong>
                    <th>
                    <th><strong>Trucking
                            Revenue</strong>
                    <th>
                    <th><strong>Brokerage
                            Revenue</strong>
                    <th>
                    <th><strong>Total Miles</strong>
                    <th>
                    <th><strong># Company
                            Owned
                            Power Units</strong>
                    <th>
                    <th><strong># Owner/
                            Operator
                            Units</strong>
                    <th>
                    <th><strong>#
                            Subhaulers</strong>
                    <th>
                        <hr />
                </tr>
                <tr>
                    <td>Projection (next 12 mos.)</td>
                    <td>
                        {if isset($truckingRevenue1)}
                            $<span class="underline">{$truckingRevenue1}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($brokerageRevenue1)}
                            $<span class="underline">{$brokerageRevenue1}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($totalMiles1)}
                            <span class="underline">{$totalMiles1}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($companyOwnedPowerUnits1)}
                            <span class="underline">{$companyOwnedPowerUnits1}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($ownerOperatorUnits1)}
                            <span class="underline">{$ownerOperatorUnits1}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($subhaulers1)}
                            <span class="underline">{$subhaulers1}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>Current Policy Year</td>
                    <td>
                        {if isset($truckingRevenue2)}
                            $<span class="underline">{$truckingRevenue2}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($brokerageRevenue2)}
                            $<span class="underline">{$brokerageRevenue2}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($totalMiles2)}
                            <span class="underline">{$totalMiles2}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($companyOwnedPowerUnits2)}
                            <span class="underline">{$companyOwnedPowerUnits2}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($ownerOperatorUnits2)}
                            <span class="underline">{$ownerOperatorUnits2}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($subhaulers2)}
                            <span class="underline">{$subhaulers2}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>1st Prior Year</td>
                    <td>
                        {if isset($truckingRevenue3)}
                            $<span class="underline">{$truckingRevenue3}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($brokerageRevenue3)}
                            $<span class="underline">{$brokerageRevenue3}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($totalMiles3)}
                            <span class="underline">{$totalMiles3}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($companyOwnedPowerUnits3)}
                            <span class="underline">{$companyOwnedPowerUnits3}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($ownerOperatorUnits3)}
                            <span class="underline">{$ownerOperatorUnits3}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($subhaulers3)}
                            <span class="underline">{$subhaulers3}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>2nd Prior Year</td>
                    <td>
                        {if isset($truckingRevenue4)}
                            $<span class="underline">{$truckingRevenue4}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($brokerageRevenue4)}
                            $<span class="underline">{$brokerageRevenue4}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($totalMiles4)}
                            <span class="underline">{$totalMiles4}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($companyOwnedPowerUnits4)}
                            <span class="underline">{$companyOwnedPowerUnits4}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($ownerOperatorUnits4)}
                            <span class="underline">{$ownerOperatorUnits4}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($subhaulers4)}
                            <span class="underline">{$subhaulers4}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>3rd Prior Year</td>
                    <td>
                        {if isset($truckingRevenue5)}
                            $<span class="underline">{$truckingRevenue5}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($brokerageRevenue5)}
                            $<span class="underline">{$brokerageRevenue5}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($totalMiles5)}
                            <span class="underline">{$totalMiles5}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($companyOwnedPowerUnits5)}
                            <span class="underline">{$companyOwnedPowerUnits5}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($ownerOperatorUnits5)}
                            <span class="underline">{$ownerOperatorUnits5}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($subhaulers5)}
                            <span class="underline">{$subhaulers5}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>4th Prior Year</td>
                    <td>
                        {if isset($truckingRevenue6)}
                            $<span class="underline">{$truckingRevenue6}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($brokerageRevenue6)}
                            $<span class="underline">{$brokerageRevenue6}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($totalMiles6)}
                            <span class="underline">{$totalMiles6}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($companyOwnedPowerUnits6)}
                            <span class="underline">{$companyOwnedPowerUnits6}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($ownerOperatorUnits6)}
                            <span class="underline">{$ownerOperatorUnits6}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                    <td>
                        {if isset($subhaulers6)}
                            <span class="underline">{$subhaulers6}</span>
                        {else}
                            <span class="underline"> </span>
                        {/if}
                    </td>
                </tr>
            </table>
            <p class="continued">SECTION 1: GENERAL INFORMATION, continued</p>
            <strong>General Questions for ALL Operations:</strong>
            <table>
                <tr>
                    <td>1</td>
                    <td>Insurance been cancelled or non-renewed in the last 5 years for any reason?</td>
                    {if isset($survey1['insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason'])}
                        <td>
                            {if $survey1.insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason == "yes"}
                                <input id="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" type="checkbox"
                                    name="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" checked readonly />
                                <label for="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason">Y</label>
                            {else}
                                <input id="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" type="checkbox"
                                    name="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" readonly />
                                <label for="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey1.insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason == "no"}
                                <input id="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" type="checkbox"
                                    name="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" checked readonly />
                                <label for="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason">N</label>
                            {else}
                                <input id="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" type="checkbox"
                                    name="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" readonly />
                                <label for="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" type="checkbox"
                                name="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" readonly />
                            <label for="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason">Y</label>
                            <input id="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" type="checkbox"
                                name="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" readonly />
                            <label for="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>2</td>
                    <td>Involved in the fracking industry?</td>
                    {if isset($survey1['involvedInTheFrackingIndustry'])}
                        <td>
                            {if $survey1.involvedInTheFrackingIndustry == "yes"}
                                <input id="involvedInTheFrackingIndustry" type="checkbox" name="involvedInTheFrackingIndustry"
                                    checked readonly />
                                <label for="involvedInTheFrackingIndustry">Y</label>
                            {else}
                                <input id="involvedInTheFrackingIndustry" type="checkbox" name="involvedInTheFrackingIndustry"
                                    readonly />
                                <label for="involvedInTheFrackingIndustry">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey1.involvedInTheFrackingIndustry == "no"}
                                <input id="involvedInTheFrackingIndustry" type="checkbox" name="involvedInTheFrackingIndustry"
                                    checked readonly />
                                <label for="involvedInTheFrackingIndustry">N</label>
                            {else}
                                <input id="involvedInTheFrackingIndustry" type="checkbox" name="involvedInTheFrackingIndustry"
                                    readonly />
                                <label for="involvedInTheFrackingIndustry">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="involvedInTheFrackingIndustry" type="checkbox" name="involvedInTheFrackingIndustry"
                                readonly />
                            <label for="involvedInTheFrackingIndustry">Y</label>
                            <input id="involvedInTheFrackingIndustry" type="checkbox" name="involvedInTheFrackingIndustry"
                                readonly />
                            <label for="involvedInTheFrackingIndustry">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>3</td>
                    <td>Have any interline, intermodal or interchange arrangements?</td>
                    {if isset($survey1['haveAnyInterlineIntermodalOrInterchangeArrangements'])}
                        <td>
                            {if $survey1.haveAnyInterlineIntermodalOrInterchangeArrangements == "yes"}
                                <input id="haveAnyInterlineIntermodalOrInterchangeArrangements" type="checkbox"
                                    name="haveAnyInterlineIntermodalOrInterchangeArrangements" checked readonly />
                                <label for="haveAnyInterlineIntermodalOrInterchangeArrangements">Y</label>
                            {else}
                                <input id="haveAnyInterlineIntermodalOrInterchangeArrangements" type="checkbox"
                                    name="haveAnyInterlineIntermodalOrInterchangeArrangements" readonly />
                                <label for="haveAnyInterlineIntermodalOrInterchangeArrangements">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey1.haveAnyInterlineIntermodalOrInterchangeArrangements == "no"}
                                <input id="haveAnyInterlineIntermodalOrInterchangeArrangements" type="checkbox"
                                    name="haveAnyInterlineIntermodalOrInterchangeArrangements" checked readonly />
                                <label for="haveAnyInterlineIntermodalOrInterchangeArrangements">N</label>
                            {else}
                                <input id="haveAnyInterlineIntermodalOrInterchangeArrangements" type="checkbox"
                                    name="haveAnyInterlineIntermodalOrInterchangeArrangements" readonly />
                                <label for="haveAnyInterlineIntermodalOrInterchangeArrangements">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="haveAnyInterlineIntermodalOrInterchangeArrangements" type="checkbox"
                                name="haveAnyInterlineIntermodalOrInterchangeArrangements" readonly />
                            <label for="haveAnyInterlineIntermodalOrInterchangeArrangements">Y</label>
                            <input id="haveAnyInterlineIntermodalOrInterchangeArrangements" type="checkbox"
                                name="haveAnyInterlineIntermodalOrInterchangeArrangements" readonly />
                            <label for="haveAnyInterlineIntermodalOrInterchangeArrangements">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>4</td>
                    <td>Haul any noxious, caustic, toxic, flammable or explosive commodities?</td>
                    {if isset($survey1['haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities'])}
                        <td>
                            {if $survey1.haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities == "yes"}
                                <input id="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" type="checkbox"
                                    name="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" checked readonly />
                                <label for="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities">Y</label>
                            {else}
                                <input id="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" type="checkbox"
                                    name="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" readonly />
                                <label for="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey1.haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities == "no"}
                                <input id="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" type="checkbox"
                                    name="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" checked readonly />
                                <label for="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities">N</label>
                            {else}
                                <input id="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" type="checkbox"
                                    name="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" readonly />
                                <label for="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" type="checkbox"
                                name="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" readonly />
                            <label for="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities">Y</label>
                            <input id="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" type="checkbox"
                                name="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" readonly />
                            <label for="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>5</td>
                    <td>Operate as a broker or freight forwarder?</td>
                    {if isset($survey1['operateAsABrokerOrFreightForwarder'])}
                        <td>
                            {if $survey1.operateAsABrokerOrFreightForwarder == "yes"}
                                <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                    name="operateAsABrokerOrFreightForwarder" checked readonly />
                                <label for="operateAsABrokerOrFreightForwarder">Y</label>
                            {else}
                                <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                    name="operateAsABrokerOrFreightForwarder" readonly />
                                <label for="operateAsABrokerOrFreightForwarder">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey1.operateAsABrokerOrFreightForwarder == "no"}
                                <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                    name="operateAsABrokerOrFreightForwarder" checked readonly />
                                <label for="operateAsABrokerOrFreightForwarder">N</label>
                            {else}
                                <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                    name="operateAsABrokerOrFreightForwarder" readonly />
                                <label for="operateAsABrokerOrFreightForwarder">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                name="operateAsABrokerOrFreightForwarder" readonly />
                            <label for="operateAsABrokerOrFreightForwarder">Y</label>
                            <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                name="operateAsABrokerOrFreightForwarder" readonly />
                            <label for="operateAsABrokerOrFreightForwarder">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>5</td>
                    <td>Operate as a broker or freight forwarder?</td>
                    {if isset($survey1['operateAsABrokerOrFreightForwarder'])}
                        <td>
                            {if $survey1.operateAsABrokerOrFreightForwarder == "yes"}
                                <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                    name="operateAsABrokerOrFreightForwarder" checked readonly />
                                <label for="operateAsABrokerOrFreightForwarder">Y</label>
                            {else}
                                <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                    name="operateAsABrokerOrFreightForwarder" readonly />
                                <label for="operateAsABrokerOrFreightForwarder">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey1.operateAsABrokerOrFreightForwarder == "no"}
                                <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                    name="operateAsABrokerOrFreightForwarder" checked readonly />
                                <label for="operateAsABrokerOrFreightForwarder">N</label>
                            {else}
                                <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                    name="operateAsABrokerOrFreightForwarder" readonly />
                                <label for="operateAsABrokerOrFreightForwarder">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                name="operateAsABrokerOrFreightForwarder" readonly />
                            <label for="operateAsABrokerOrFreightForwarder">Y</label>
                            <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                name="operateAsABrokerOrFreightForwarder" readonly />
                            <label for="operateAsABrokerOrFreightForwarder">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>6</td>
                    <td>Any other operations under control or authority?</td>
                    {if $anyOtherOperationsUnderControlOrAuthority == "yes"}
                        <td>
                            <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                name="operateAsABrokerOrFreightForwarder" checked readonly />
                            <label for="operateAsABrokerOrFreightForwarder">Y</label>
                        </td>
                        <td>
                            <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                name="operateAsABrokerOrFreightForwarder" readonly />
                            <label for="operateAsABrokerOrFreightForwarder">N</label>
                        </td>
                    {elseif $anyOtherOperationsUnderControlOrAuthority == "no"}
                        <td>
                            <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                name="operateAsABrokerOrFreightForwarder" readonly />
                            <label for="operateAsABrokerOrFreightForwarder">Y</label>
                        </td>
                        <td>
                            <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                name="operateAsABrokerOrFreightForwarder" checked readonly />
                            <label for="operateAsABrokerOrFreightForwarder">N</label>
                        </td>
                    {else}
                        <td>
                            <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                name="operateAsABrokerOrFreightForwarder" readonly />
                            <label for="operateAsABrokerOrFreightForwarder">Y</label>
                        </td>
                        <td>
                            <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                name="operateAsABrokerOrFreightForwarder" readonly />
                            <label for="operateAsABrokerOrFreightForwarder">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td></td>
                    <td>
                        If yes, please provide name and DOT # (if applicable)
                        <span class="underline">
                            {if isset($pleaseProvideName)}
                                {$pleaseProvideName}
                            {/if}
                            {if isset($dot)}
                                {$dot}
                            {/if}
                        </span>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>Team Drivers?</td>
                    {if isset($survey1['teamDrivers'])}
                        <td>
                            {if $survey1.teamDrivers == "yes"}
                                <input id="teamDrivers" type="checkbox" name="teamDrivers" checked readonly />
                                <label for="teamDrivers">Y</label>
                            {else}
                                <input id="teamDrivers" type="checkbox" name="teamDrivers" readonly />
                                <label for="teamDrivers">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey1.teamDrivers == "no"}
                                <input id="teamDrivers" type="checkbox" name="teamDrivers" checked readonly />
                                <label for="teamDrivers">N</label>
                            {else}
                                <input id="teamDrivers" type="checkbox" name="teamDrivers" readonly />
                                <label for="teamDrivers">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="teamDrivers" type="checkbox" name="teamDrivers" readonly />
                            <label for="teamDrivers">Y</label>
                            <input id="teamDrivers" type="checkbox" name="teamDrivers" readonly />
                            <label for="teamDrivers">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>8</td>
                    <td>Haul Doubles or Triples?</td>
                    {if isset($survey1['haulDoublesOrTriples'])}
                        <td>
                            {if $survey1.haulDoublesOrTriples == "yes"}
                                <input id="haulDoublesOrTriples" type="checkbox" name="haulDoublesOrTriples" checked readonly />
                                <label for="haulDoublesOrTriples">Y</label>
                            {else}
                                <input id="haulDoublesOrTriples" type="checkbox" name="haulDoublesOrTriples" readonly />
                                <label for="haulDoublesOrTriples">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey1.haulDoublesOrTriples == "no"}
                                <input id="haulDoublesOrTriples" type="checkbox" name="haulDoublesOrTriples" checked readonly />
                                <label for="haulDoublesOrTriples">N</label>
                            {else}
                                <input id="haulDoublesOrTriples" type="checkbox" name="haulDoublesOrTriples" readonly />
                                <label for="haulDoublesOrTriples">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="haulDoublesOrTriples" type="checkbox" name="haulDoublesOrTriples" readonly />
                            <label for="haulDoublesOrTriples">Y</label>
                            <input id="haulDoublesOrTriples" type="checkbox" name="haulDoublesOrTriples" readonly />
                            <label for="haulDoublesOrTriples">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>9</td>
                    <td>Do you loan, lease or rent vehicles to others with or without drivers?</td>
                    {if isset($survey1['doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers'])}
                        <td>
                            {if $survey1.doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers == "yes"}
                                <input id="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" type="checkbox"
                                    name="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" checked readonly />
                                <label for="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers">Y</label>
                            {else}
                                <input id="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" type="checkbox"
                                    name="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" readonly />
                                <label for="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey1.doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers == "no"}
                                <input id="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" type="checkbox"
                                    name="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" checked readonly />
                                <label for="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers">N</label>
                            {else}
                                <input id="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" type="checkbox"
                                    name="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" readonly />
                                <label for="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" type="checkbox"
                                name="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" readonly />
                            <label for="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers">Y</label>
                            <input id="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" type="checkbox"
                                name="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" readonly />
                            <label for="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>10</td>
                    <td>Brokerage authority? (if yes, answer a., b., and c.)</td>
                    {if $brokerageAuthority == "yes"}
                        <td>
                            <input id="brokerageAuthority" type="checkbox" name="brokerageAuthority" checked readonly />
                            <label for="brokerageAuthority">Y</label>
                        </td>
                        <td>
                            <input id="brokerageAuthority" type="checkbox" name="brokerageAuthority" readonly />
                            <label for="brokerageAuthority">N</label>
                        </td>
                    {elseif $brokerageAuthority == "no"}
                        <td>
                            <input id="brokerageAuthority" type="checkbox" name="brokerageAuthority" readonly />
                            <label for="brokerageAuthority">Y</label>
                        </td>
                        <td>
                            <input id="brokerageAuthority" type="checkbox" name="brokerageAuthority" checked readonly />
                            <label for="brokerageAuthority">N</label>
                        </td>
                    {else}
                        <td>
                            <input id="brokerageAuthority" type="checkbox" name="brokerageAuthority" readonly />
                            <label for="brokerageAuthority">Y</label>
                        </td>
                        <td>
                            <input id="brokerageAuthority" type="checkbox" name="brokerageAuthority" readonly />
                            <label for="brokerageAuthority">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td></td>
                    <td>a. Under same name, if yes, % of operations?
                        {if isset($ofOperations)}
                            <span class="underline">{$ofOperations}</span>
                        </td>
                    {/if}
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>b. Are brokerage operations conducted on a contract basis?</td>
                    {if isset($areBrokerageOperationsConductedOnAContractBasis)}
                        {if $areBrokerageOperationsConductedOnAContractBasis == "yes"}
                            <td>
                                <input id="areBrokerageOperationsConductedOnAContractBasis" type="checkbox"
                                    name="areBrokerageOperationsConductedOnAContractBasis" checked readonly />
                                <label for="areBrokerageOperationsConductedOnAContractBasis">Y</label>
                            </td>
                            <td>
                                <input id="areBrokerageOperationsConductedOnAContractBasis" type="checkbox"
                                    name="areBrokerageOperationsConductedOnAContractBasis" readonly />
                                <label for="areBrokerageOperationsConductedOnAContractBasis">N</label>
                            </td>
                        {else $areBrokerageOperationsConductedOnAContractBasis == "no"}
                            <td>
                                <input id="areBrokerageOperationsConductedOnAContractBasis" type="checkbox"
                                    name="areBrokerageOperationsConductedOnAContractBasis" readonly />
                                <label for="areBrokerageOperationsConductedOnAContractBasis">Y</label>
                            </td>
                            <td>
                                <input id="areBrokerageOperationsConductedOnAContractBasis" type="checkbox"
                                    name="areBrokerageOperationsConductedOnAContractBasis" checked readonly />
                                <label for="areBrokerageOperationsConductedOnAContractBasis">N</label>
                            </td>
                        {/if}
                    {else}
                        <td>
                            <input id="areBrokerageOperationsConductedOnAContractBasis" type="checkbox"
                                name="areBrokerageOperationsConductedOnAContractBasis" readonly />
                            <label for="areBrokerageOperationsConductedOnAContractBasis">Y</label>
                        </td>
                        <td>
                            <input id="areBrokerageOperationsConductedOnAContractBasis" type="checkbox"
                                name="areBrokerageOperationsConductedOnAContractBasis" readonly />
                            <label for="areBrokerageOperationsConductedOnAContractBasis">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td></td>
                    <td>c. Is Insurance verified with these contracts?</td>
                    {if isset($isInsuranceVerifiedWithTheseContracts)}
                        {if $isInsuranceVerifiedWithTheseContracts == "yes"}
                            <td>
                                <input id="isInsuranceVerifiedWithTheseContracts" type="checkbox"
                                    name="isInsuranceVerifiedWithTheseContracts" checked readonly />
                                <label for="isInsuranceVerifiedWithTheseContracts">Y</label>
                            </td>
                            <td>
                                <input id="isInsuranceVerifiedWithTheseContracts" type="checkbox"
                                    name="isInsuranceVerifiedWithTheseContracts" readonly />
                                <label for="isInsuranceVerifiedWithTheseContracts">N</label>
                            </td>
                        {else $isInsuranceVerifiedWithTheseContracts == "no"}
                            <td>
                                <input id="isInsuranceVerifiedWithTheseContracts" type="checkbox"
                                    name="isInsuranceVerifiedWithTheseContracts" readonly />
                                <label for="isInsuranceVerifiedWithTheseContracts">Y</label>
                            </td>
                            <td>
                                <input id="isInsuranceVerifiedWithTheseContracts" type="checkbox"
                                    name="isInsuranceVerifiedWithTheseContracts" checked readonly />
                                <label for="isInsuranceVerifiedWithTheseContracts">N</label>
                            </td>
                        {/if}
                    {else}
                        <td>
                            <input id="isInsuranceVerifiedWithTheseContracts" type="checkbox"
                                name="isInsuranceVerifiedWithTheseContracts" readonly />
                            <label for="isInsuranceVerifiedWithTheseContracts">Y</label>
                        </td>
                        <td>
                            <input id="isInsuranceVerifiedWithTheseContracts" type="checkbox"
                                name="isInsuranceVerifiedWithTheseContracts" readonly />
                            <label for="isInsuranceVerifiedWithTheseContracts">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>11</td>
                    <td>Passengers Allowed?</td>
                    {if isset($isInsuranceVerifiedWithTheseContracts1)}
                        {if $isInsuranceVerifiedWithTheseContracts1 == "yes"}
                            <td>
                                <input id="isInsuranceVerifiedWithTheseContracts1" type="checkbox"
                                    name="isInsuranceVerifiedWithTheseContracts1" checked readonly />
                                <label for="isInsuranceVerifiedWithTheseContracts1">Y</label>
                            </td>
                            <td>
                                <input id="isInsuranceVerifiedWithTheseContracts1" type="checkbox"
                                    name="isInsuranceVerifiedWithTheseContracts1" readonly />
                                <label for="isInsuranceVerifiedWithTheseContracts1">N</label>
                            </td>
                        {else $isInsuranceVerifiedWithTheseContracts1 == "no"}
                            <td>
                                <input id="isInsuranceVerifiedWithTheseContracts1" type="checkbox"
                                    name="isInsuranceVerifiedWithTheseContracts1" readonly />
                                <label for="isInsuranceVerifiedWithTheseContracts1">Y</label>
                            </td>
                            <td>
                                <input id="isInsuranceVerifiedWithTheseContracts1" type="checkbox"
                                    name="isInsuranceVerifiedWithTheseContracts1" checked readonly />
                                <label for="isInsuranceVerifiedWithTheseContracts1">N</label>
                            </td>
                        {/if}
                    {else}
                        <td>
                            <input id="isInsuranceVerifiedWithTheseContracts1" type="checkbox"
                                name="isInsuranceVerifiedWithTheseContracts1" readonly />
                            <label for="isInsuranceVerifiedWithTheseContracts1">Y</label>
                        </td>
                        <td>
                            <input id="isInsuranceVerifiedWithTheseContracts1" type="checkbox"
                                name="isInsuranceVerifiedWithTheseContracts1" readonly />
                            <label for="isInsuranceVerifiedWithTheseContracts1">N</label>
                        </td>
                    {/if}
                </tr>
            </table>
        </div>

    </section>
    {* Section 1 *}

    {* Section 2 *}
    <section class="mb-4">
        <div class="mb-4">
            <h3 class="bold"><span class="section_black mr-4">SECTION 2</span> DRIVER INFORMATION </h3>
        </div>
        <div id="sectionContent">
            <table width="100%" id="driverInfo">
                <tr>
                    <td width="33%">
                        <table width="100%">
                            <tr>
                                <th><strong>Driver Types</strong></th>
                                <th><strong>How Many #</strong></th>
                            </tr>
                            <tr>
                                <td>Employees</td>
                                <td>
                                    {if isset($employees1)}
                                        {$employees1}
                                    {/if}
                                </td>
                            </tr>
                            <tr>
                                <td>Owner Operators</td>
                                <td>
                                    {if isset($ownerOperators1)}
                                        {$ownerOperators1}
                                    {/if}
                                </td>
                            </tr>
                            <tr>
                                <td>Subhaulers</td>
                                <td>
                                    {if isset($subhaulers8)}
                                        {$subhaulers8}
                                    {/if}
                                </td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td>
                                    {if isset($total3)}
                                        {$total3}
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="33%">
                        <table>
                            <tr>
                                <th><strong>In the past year, how many drivers:</strong></th>
                            </tr>
                            <tr>
                                <td>Hired</td>
                                <td>
                                    {if isset($hired1)}
                                        {$hired1}
                                    {/if}
                                </td>
                            </tr>
                            <tr>
                                <td>Terminated</td>
                                <td>
                                    {if isset($terminated1)}
                                        {$terminated1}
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="34%">
                        <table>
                            <tr>
                                <th><strong>What amount of experience is required:</strong></th>
                            </tr>
                            <tr>
                                <td>Miles driven</td>
                                <td>
                                    {if isset($milesDriven1)}
                                        {$milesDriven1}
                                    {/if}
                                </td>
                            </tr>
                            <tr>
                                <td>Years of driving</td>
                                <td>
                                    {if isset($yearsOfDriving1)}
                                        {$yearsOfDriving1}
                                    {/if}
                                </td>
                            </tr>
                            <tr>
                                <td>Minimum Age</td>
                                <td>
                                    {if isset($minimumAge1)}
                                        {$minimumAge1}
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <br>
            <strong>Driver selection procedures include</strong>
            <table>
                <tr>
                    <th>the use of:
                        <hr>
                    </th>
                    <th>Wages base on:
                        <hr>
                    </th>
                </tr>
                <tr>
                    <td>
                        <input id="writtenApplication" type="checkbox" name="writtenApplication" readonly />
                        <label for="writtenApplication">Written Application</label>
                    </td>
                    <td>
                        <input id="hours" type="checkbox" name="hours" readonly />
                        <label for="hours">Hours</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id="drivingTests" type="checkbox" name="drivingTests" readonly />
                        <label for="drivingTests">Driving Tests</label>
                    </td>
                    <td>
                        <input id="miles" type="checkbox" name="miles" readonly />
                        <label for="miles">Miles</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id="drivingTests" type="checkbox" name="drivingTests" readonly />
                        <label for="drivingTests">Driving Tests</label>
                    </td>
                    <td>
                        <input id="miles" type="checkbox" name="miles" readonly />
                        <label for="miles">Miles</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id="interview" type="checkbox" name="interview" readonly />
                        <label for="interview">Interview</label>
                    </td>
                    <td>
                        <input id="miles" type="checkbox" name="miles" readonly />
                        <label for="miles">Revenue</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id="drugTest" type="checkbox" name="drugTest" readonly />
                        <label for="drugTest">Drug Test</label>
                    </td>
                    <td>
                        <input id="trips" type="checkbox" name="trips" readonly />
                        <label for="trips">Trips</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id="writtenTest" type="checkbox" name="writtenTest" readonly />
                        <label for="writtenTest">Written Test</label>
                    </td>
                    <td>
                        <strong>Average annual driver pay: $</strong>
                        {if isset($averageAnnualDriverPay)}
                            <span class="underline">{$averageAnnualDriverPay}</span>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id="preHirePhysical" type="checkbox" name="preHirePhysical" readonly />
                        <label for="preHirePhysical">Pre-Hire Physical</label>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <input id="referenceCheck" type="checkbox" name="referenceCheck" readonly />
                        <label for="referenceCheck">Reference Check</label>
                    </td>
                    <td>
                        <strong>How often are drivers home?</strong>
                        {if isset($howOftenAreDriversHome)}
                            <span class="underline">{$howOftenAreDriversHome}</span>
                        {/if}
                    </td>
                </tr>
            </table>
            <p class="continued">SECTION 2: DRIVER INFROMATION, continued</p>
            <strong>If Owner/Operators are used:</strong><br>
            <table>
                <tr>
                    <td>1</td>
                    <td>Are permanent/exclusive lease agreements used?</td>
                    {if isset($survey2['arePermanentExclusiveLeaseAgreementsUsed'])}
                        <td>
                            {if $survey2.arePermanentExclusiveLeaseAgreementsUsed == "yes"}
                                <input id="arePermanentExclusiveLeaseAgreementsUsed" type="checkbox"
                                    name="arePermanentExclusiveLeaseAgreementsUsed" checked readonly />
                                <label for="arePermanentExclusiveLeaseAgreementsUsed">Y</label>
                            {else}
                                <input id="arePermanentExclusiveLeaseAgreementsUsed" type="checkbox"
                                    name="arePermanentExclusiveLeaseAgreementsUsed" readonly />
                                <label for="arePermanentExclusiveLeaseAgreementsUsed">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey2.arePermanentExclusiveLeaseAgreementsUsed == "no"}
                                <input id="arePermanentExclusiveLeaseAgreementsUsed" type="checkbox"
                                    name="arePermanentExclusiveLeaseAgreementsUsed" checked readonly />
                                <label for="arePermanentExclusiveLeaseAgreementsUsed">N</label>
                            {else}
                                <input id="arePermanentExclusiveLeaseAgreementsUsed" type="checkbox"
                                    name="arePermanentExclusiveLeaseAgreementsUsed" readonly />
                                <label for="arePermanentExclusiveLeaseAgreementsUsed">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="arePermanentExclusiveLeaseAgreementsUsed" type="checkbox"
                                name="arePermanentExclusiveLeaseAgreementsUsed" readonly />
                            <label for="arePermanentExclusiveLeaseAgreementsUsed">Y</label>
                            <input id="arePermanentExclusiveLeaseAgreementsUsed" type="checkbox"
                                name="arePermanentExclusiveLeaseAgreementsUsed" readonly />
                            <label for="arePermanentExclusiveLeaseAgreementsUsed">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>2</td>
                    <td>Are drivers subject to the same driver training as company drivers?</td>
                    {if isset($survey2['areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers'])}
                        <td>
                            {if $survey2.areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers == "yes"}
                                <input id="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                    type="checkbox" name="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                    checked readonly />
                                <label for="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers">Y</label>
                            {else}
                                <input id="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                    type="checkbox" name="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                    readonly />
                                <label for="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey2.areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers == "no"}
                                <input id="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                    type="checkbox" name="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                    checked readonly />
                                <label for="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers">N</label>
                            {else}
                                <input id="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                    type="checkbox" name="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                    readonly />
                                <label for="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                type="checkbox" name="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                readonly />
                            <label for="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers">Y</label>
                            <input id="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                type="checkbox" name="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                readonly />
                            <label for="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>3</td>
                    <td>Are trip lease agreements used?</td>
                    {if isset($survey2['areTripLeaseAgreementsUsed'])}
                        <td>
                            {if $survey2.areTripLeaseAgreementsUsed == "yes"}
                                <input id="areTripLeaseAgreementsUsed" type="checkbox" name="areTripLeaseAgreementsUsed" checked
                                    readonly />
                                <label for="areTripLeaseAgreementsUsed">Y</label>
                            {else}
                                <input id="areTripLeaseAgreementsUsed" type="checkbox" name="areTripLeaseAgreementsUsed"
                                    readonly />
                                <label for="areTripLeaseAgreementsUsed">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey2.areTripLeaseAgreementsUsed == "no"}
                                <input id="areTripLeaseAgreementsUsed" type="checkbox" name="areTripLeaseAgreementsUsed" checked
                                    readonly />
                                <label for="areTripLeaseAgreementsUsed">N</label>
                            {else}
                                <input id="areTripLeaseAgreementsUsed" type="checkbox" name="areTripLeaseAgreementsUsed"
                                    readonly />
                                <label for="areTripLeaseAgreementsUsed">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="areTripLeaseAgreementsUsed" type="checkbox" name="areTripLeaseAgreementsUsed"
                                readonly />
                            <label for="areTripLeaseAgreementsUsed">Y</label>
                            <input id="areTripLeaseAgreementsUsed" type="checkbox" name="areTripLeaseAgreementsUsed"
                                readonly />
                            <label for="areTripLeaseAgreementsUsed">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>4</td>
                    <td>Are driver files maintained by the insured?</td>
                    {if isset($survey2['areOwnerOperatorDriverFilesMaintainedByTheInsured'])}
                        <td>
                            {if $survey2.areOwnerOperatorDriverFilesMaintainedByTheInsured == "yes"}
                                <input id="areOwnerOperatorDriverFilesMaintainedByTheInsured" type="checkbox"
                                    name="areOwnerOperatorDriverFilesMaintainedByTheInsured" checked readonly />
                                <label for="areOwnerOperatorDriverFilesMaintainedByTheInsured">Y</label>
                            {else}
                                <input id="areOwnerOperatorDriverFilesMaintainedByTheInsured" type="checkbox"
                                    name="areOwnerOperatorDriverFilesMaintainedByTheInsured" readonly />
                                <label for="areOwnerOperatorDriverFilesMaintainedByTheInsured">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey2.areOwnerOperatorDriverFilesMaintainedByTheInsured == "no"}
                                <input id="areOwnerOperatorDriverFilesMaintainedByTheInsured" type="checkbox"
                                    name="areOwnerOperatorDriverFilesMaintainedByTheInsured" checked readonly />
                                <label for="areOwnerOperatorDriverFilesMaintainedByTheInsured">N</label>
                            {else}
                                <input id="areOwnerOperatorDriverFilesMaintainedByTheInsured" type="checkbox"
                                    name="areOwnerOperatorDriverFilesMaintainedByTheInsured" readonly />
                                <label for="areOwnerOperatorDriverFilesMaintainedByTheInsured">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="areOwnerOperatorDriverFilesMaintainedByTheInsured" type="checkbox"
                                name="areOwnerOperatorDriverFilesMaintainedByTheInsured" readonly />
                            <label for="areOwnerOperatorDriverFilesMaintainedByTheInsured">Y</label>
                            <input id="areOwnerOperatorDriverFilesMaintainedByTheInsured" type="checkbox"
                                name="areOwnerOperatorDriverFilesMaintainedByTheInsured" readonly />
                            <label for="areOwnerOperatorDriverFilesMaintainedByTheInsured">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>5</td>
                    <td>Is equipment inspected by the insured?</td>
                    {if isset($survey2['isEquipmentInspectedByTheInsured'])}
                        <td>
                            {if $survey2.isEquipmentInspectedByTheInsured == "yes"}
                                <input id="isEquipmentInspectedByTheInsured" type="checkbox"
                                    name="isEquipmentInspectedByTheInsured" checked readonly />
                                <label for="isEquipmentInspectedByTheInsured">Y</label>
                            {else}
                                <input id="isEquipmentInspectedByTheInsured" type="checkbox"
                                    name="isEquipmentInspectedByTheInsured" readonly />
                                <label for="isEquipmentInspectedByTheInsured">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey2.isEquipmentInspectedByTheInsured == "no"}
                                <input id="isEquipmentInspectedByTheInsured" type="checkbox"
                                    name="isEquipmentInspectedByTheInsured" checked readonly />
                                <label for="isEquipmentInspectedByTheInsured">N</label>
                            {else}
                                <input id="isEquipmentInspectedByTheInsured" type="checkbox"
                                    name="isEquipmentInspectedByTheInsured" readonly />
                                <label for="isEquipmentInspectedByTheInsured">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="isEquipmentInspectedByTheInsured" type="checkbox"
                                name="isEquipmentInspectedByTheInsured" readonly />
                            <label for="isEquipmentInspectedByTheInsured">Y</label>
                            <input id="isEquipmentInspectedByTheInsured" type="checkbox"
                                name="isEquipmentInspectedByTheInsured" readonly />
                            <label for="isEquipmentInspectedByTheInsured">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>6</td>
                    <td>Are drivers subject to the same maintenance program as the owned equipment?</td>
                    {if isset($survey2['areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment'])}
                        <td>
                            {if $survey2.areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment == "yes"}
                                <input id="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" type="checkbox"
                                    name="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" checked readonly />
                                <label for="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment">Y</label>
                            {else}
                                <input id="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" type="checkbox"
                                    name="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" readonly />
                                <label for="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey2.areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment == "no"}
                                <input id="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" type="checkbox"
                                    name="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" checked readonly />
                                <label for="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment">N</label>
                            {else}
                                <input id="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" type="checkbox"
                                    name="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" readonly />
                                <label for="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" type="checkbox"
                                name="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" readonly />
                            <label for="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment">Y</label>
                            <input id="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" type="checkbox"
                                name="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" readonly />
                            <label for="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>7</td>
                    <td>Are all owner/operators required to carry at least $500,000 non-trucking liability?</td>
                    {if isset($survey2['areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability'])}
                        <td>
                            {if $survey2.areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability == "yes"}
                                <input id="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" type="checkbox"
                                    name="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" checked
                                    readonly />
                                <label for="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability">Y</label>
                            {else}
                                <input id="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" type="checkbox"
                                    name="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" readonly />
                                <label for="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey2.areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability == "no"}
                                <input id="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" type="checkbox"
                                    name="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" checked
                                    readonly />
                                <label for="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability">N</label>
                            {else}
                                <input id="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" type="checkbox"
                                    name="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" readonly />
                                <label for="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" type="checkbox"
                                name="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" readonly />
                            <label for="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability">Y</label>
                            <input id="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" type="checkbox"
                                name="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" readonly />
                            <label for="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>8</td>
                    <td>Are certificates on file?</td>
                    {if isset($survey2['areCertificatesOnFile'])}
                        <td>
                            {if $survey2.areCertificatesOnFile == "yes"}
                                <input id="areCertificatesOnFile" type="checkbox" name="areCertificatesOnFile" checked
                                    readonly />
                                <label for="areCertificatesOnFile">Y</label>
                            {else}
                                <input id="areCertificatesOnFile" type="checkbox" name="areCertificatesOnFile" readonly />
                                <label for="areCertificatesOnFile">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey2.areCertificatesOnFile == "no"}
                                <input id="areCertificatesOnFile" type="checkbox" name="areCertificatesOnFile" checked
                                    readonly />
                                <label for="areCertificatesOnFile">N</label>
                            {else}
                                <input id="areCertificatesOnFile" type="checkbox" name="areCertificatesOnFile" readonly />
                                <label for="areCertificatesOnFile">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="areCertificatesOnFile" type="checkbox" name="areCertificatesOnFile" readonly />
                            <label for="areCertificatesOnFile">Y</label>
                            <input id="areCertificatesOnFile" type="checkbox" name="areCertificatesOnFile" readonly />
                            <label for="areCertificatesOnFile">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>9</td>
                    <td>Is the insured listed as an additional insured?</td>
                    {if isset($survey2['isTheInsuredListedAsAnAdditionalInsured'])}
                        <td>
                            {if $survey2.isTheInsuredListedAsAnAdditionalInsured == "yes"}
                                <input id="isTheInsuredListedAsAnAdditionalInsured" type="checkbox"
                                    name="isTheInsuredListedAsAnAdditionalInsured" checked readonly />
                                <label for="isTheInsuredListedAsAnAdditionalInsured">Y</label>
                            {else}
                                <input id="isTheInsuredListedAsAnAdditionalInsured" type="checkbox"
                                    name="isTheInsuredListedAsAnAdditionalInsured" readonly />
                                <label for="isTheInsuredListedAsAnAdditionalInsured">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey2.isTheInsuredListedAsAnAdditionalInsured == "no"}
                                <input id="isTheInsuredListedAsAnAdditionalInsured" type="checkbox"
                                    name="isTheInsuredListedAsAnAdditionalInsured" checked readonly />
                                <label for="isTheInsuredListedAsAnAdditionalInsured">N</label>
                            {else}
                                <input id="isTheInsuredListedAsAnAdditionalInsured" type="checkbox"
                                    name="isTheInsuredListedAsAnAdditionalInsured" readonly />
                                <label for="isTheInsuredListedAsAnAdditionalInsured">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="isTheInsuredListedAsAnAdditionalInsured" type="checkbox"
                                name="isTheInsuredListedAsAnAdditionalInsured" readonly />
                            <label for="isTheInsuredListedAsAnAdditionalInsured">Y</label>
                            <input id="isTheInsuredListedAsAnAdditionalInsured" type="checkbox"
                                name="isTheInsuredListedAsAnAdditionalInsured" readonly />
                            <label for="isTheInsuredListedAsAnAdditionalInsured">N</label>
                        </td>
                    {/if}
                </tr>
            </table>
        </div>
    </section>

    {* Section 2 *}

    {* Section 3 *}
    <section class="mb-4">
        <div class="mb-4">
            <h3 class="bold"><span class="section_black mr-4">SECTION 3</span> DRIVER HIRING </h3>
        </div>
        <div id="sectionContent">
            <strong>Please provide your driver training program.</strong><br>
            <table>
                <tr>
                    <td>1</td>
                    <td>Is a background check performed prior to hiring?</td>
                    {if isset($survey3['isABackgroundCheckPerformedPriorToHiring'])}
                        <td>
                            {if $survey3.isABackgroundCheckPerformedPriorToHiring == "yes"}
                                <input id="isABackgroundCheckPerformedPriorToHiring" type="checkbox"
                                    name="isABackgroundCheckPerformedPriorToHiring" checked readonly />
                                <label for="isABackgroundCheckPerformedPriorToHiring">Y</label>
                            {else}
                                <input id="isABackgroundCheckPerformedPriorToHiring" type="checkbox"
                                    name="isABackgroundCheckPerformedPriorToHiring" readonly />
                                <label for="isABackgroundCheckPerformedPriorToHiring">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey3.isABackgroundCheckPerformedPriorToHiring == "no"}
                                <input id="isABackgroundCheckPerformedPriorToHiring" type="checkbox"
                                    name="isABackgroundCheckPerformedPriorToHiring" checked readonly />
                                <label for="isABackgroundCheckPerformedPriorToHiring">N</label>
                            {else}
                                <input id="isABackgroundCheckPerformedPriorToHiring" type="checkbox"
                                    name="isABackgroundCheckPerformedPriorToHiring" readonly />
                                <label for="isABackgroundCheckPerformedPriorToHiring">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="isABackgroundCheckPerformedPriorToHiring" type="checkbox"
                                name="isABackgroundCheckPerformedPriorToHiring" readonly />
                            <label for="isABackgroundCheckPerformedPriorToHiring">Y</label>
                            <input id="isABackgroundCheckPerformedPriorToHiring" type="checkbox"
                                name="isABackgroundCheckPerformedPriorToHiring" readonly />
                            <label for="isABackgroundCheckPerformedPriorToHiring">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>2</td>
                    <td>Do you allow drivers with major violations?</td>
                    {if isset($survey3['doYouAllowDriversWithMajorViolations'])}
                        <td>
                            {if $survey3.doYouAllowDriversWithMajorViolations == "yes"}
                                <input id="doYouAllowDriversWithMajorViolations" type="checkbox"
                                    name="doYouAllowDriversWithMajorViolations" checked readonly />
                                <label for="doYouAllowDriversWithMajorViolations">Y</label>
                            {else}
                                <input id="doYouAllowDriversWithMajorViolations" type="checkbox"
                                    name="doYouAllowDriversWithMajorViolations" readonly />
                                <label for="doYouAllowDriversWithMajorViolations">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey3.doYouAllowDriversWithMajorViolations == "no"}
                                <input id="doYouAllowDriversWithMajorViolations" type="checkbox"
                                    name="doYouAllowDriversWithMajorViolations" checked readonly />
                                <label for="doYouAllowDriversWithMajorViolations">N</label>
                            {else}
                                <input id="doYouAllowDriversWithMajorViolations" type="checkbox"
                                    name="doYouAllowDriversWithMajorViolations" readonly />
                                <label for="doYouAllowDriversWithMajorViolations">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="doYouAllowDriversWithMajorViolations" type="checkbox"
                                name="doYouAllowDriversWithMajorViolations" readonly />
                            <label for="doYouAllowDriversWithMajorViolations">Y</label>
                            <input id="doYouAllowDriversWithMajorViolations" type="checkbox"
                                name="doYouAllowDriversWithMajorViolations" readonly />
                            <label for="doYouAllowDriversWithMajorViolations">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>3</td>
                    <td>Do you order MVRs prior to hiring?</td>
                    {if isset($survey3['doYouOrderMvRsPriorToHiring'])}
                        <td>
                            {if $survey3.doYouOrderMvRsPriorToHiring == "yes"}
                                <input id="doYouOrderMvRsPriorToHiring" type="checkbox" name="doYouOrderMvRsPriorToHiring"
                                    checked readonly />
                                <label for="doYouOrderMvRsPriorToHiring">Y</label>
                            {else}
                                <input id="doYouOrderMvRsPriorToHiring" type="checkbox" name="doYouOrderMvRsPriorToHiring"
                                    readonly />
                                <label for="doYouOrderMvRsPriorToHiring">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey3.doYouOrderMvRsPriorToHiring == "no"}
                                <input id="doYouOrderMvRsPriorToHiring" type="checkbox" name="doYouOrderMvRsPriorToHiring"
                                    checked readonly />
                                <label for="doYouOrderMvRsPriorToHiring">N</label>
                            {else}
                                <input id="doYouOrderMvRsPriorToHiring" type="checkbox" name="doYouOrderMvRsPriorToHiring"
                                    readonly />
                                <label for="doYouOrderMvRsPriorToHiring">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="doYouOrderMvRsPriorToHiring" type="checkbox" name="doYouOrderMvRsPriorToHiring"
                                readonly />
                            <label for="doYouOrderMvRsPriorToHiring">Y</label>
                            <input id="doYouOrderMvRsPriorToHiring" type="checkbox" name="doYouOrderMvRsPriorToHiring"
                                readonly />
                            <label for="doYouOrderMvRsPriorToHiring">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>4</td>
                    <td>
                        How often are MVRs reviewed?
                        {if isset($howOftenAreMvRsReviewed)}
                            <span class="underline">{$howOftenAreMvRsReviewed}</span>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Are driver files updated annually with information including new MVRs?</td>
                    {if isset($survey3['areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs'])}
                        <td>
                            {if $survey3.areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs == "yes"}
                                <input id="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" type="checkbox"
                                    name="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" checked readonly />
                                <label for="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs">Y</label>
                            {else}
                                <input id="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" type="checkbox"
                                    name="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" readonly />
                                <label for="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey3.areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs == "no"}
                                <input id="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" type="checkbox"
                                    name="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" checked readonly />
                                <label for="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs">N</label>
                            {else}
                                <input id="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" type="checkbox"
                                    name="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" readonly />
                                <label for="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" type="checkbox"
                                name="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" readonly />
                            <label for="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs">Y</label>
                            <input id="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" type="checkbox"
                                name="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" readonly />
                            <label for="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>6</td>
                    <td>Do you exclude drivers with citations for DWI, DUI, or reckless operations?</td>
                    {if isset($survey3['doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations'])}
                        <td>
                            {if $survey3.doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations == "yes"}
                                <input id="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" type="checkbox"
                                    name="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" checked readonly />
                                <label for="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations">Y</label>
                            {else}
                                <input id="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" type="checkbox"
                                    name="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" readonly />
                                <label for="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey3.doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations == "no"}
                                <input id="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" type="checkbox"
                                    name="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" checked readonly />
                                <label for="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations">N</label>
                            {else}
                                <input id="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" type="checkbox"
                                    name="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" readonly />
                                <label for="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" type="checkbox"
                                name="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" readonly />
                            <label for="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations">Y</label>
                            <input id="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" type="checkbox"
                                name="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" readonly />
                            <label for="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>7</td>
                    <td>What action is taken when drivers develop unacceptable records?
                        {if isset($whatActionIsTakenWhenDriversDevelopUnacceptableRecords)}
                            <span class="underline">{$whatActionIsTakenWhenDriversDevelopUnacceptableRecords}</span>
                        {/if}
                    </td>
                </tr>
            </table>
        </div>
    </section>
    {* Section 3 *}

    {* Section 4 *}
    <section class="mb-4">
        <div class="mb-4">
            <h3 class="bold"><span class="section_black mr-4">SECTION 4</span> MAINTENANCE PROGRAM </h3>
        </div>

        <div id="sectionContent">
            <table>
                <tr>
                    <td>1</td>
                    <td>Is there a written maintenance program?</td>
                    {if isset($survey4['isThereAWrittenMaintenanceProgram'])}
                        <td>
                            {if $survey4.isThereAWrittenMaintenanceProgram == "yes"}
                                <input id="isThereAWrittenMaintenanceProgram" type="checkbox"
                                    name="isThereAWrittenMaintenanceProgram" checked readonly />
                                <label for="isThereAWrittenMaintenanceProgram">Y</label>
                            {else}
                                <input id="isThereAWrittenMaintenanceProgram" type="checkbox"
                                    name="isThereAWrittenMaintenanceProgram" readonly />
                                <label for="isThereAWrittenMaintenanceProgram">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey4.isThereAWrittenMaintenanceProgram == "no"}
                                <input id="isThereAWrittenMaintenanceProgram" type="checkbox"
                                    name="isThereAWrittenMaintenanceProgram" checked readonly />
                                <label for="isThereAWrittenMaintenanceProgram">N</label>
                            {else}
                                <input id="isThereAWrittenMaintenanceProgram" type="checkbox"
                                    name="isThereAWrittenMaintenanceProgram" readonly />
                                <label for="isThereAWrittenMaintenanceProgram">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="isThereAWrittenMaintenanceProgram" type="checkbox"
                                name="isThereAWrittenMaintenanceProgram" readonly />
                            <label for="isThereAWrittenMaintenanceProgram">Y</label>
                            <input id="isThereAWrittenMaintenanceProgram" type="checkbox"
                                name="isThereAWrittenMaintenanceProgram" readonly />
                            <label for="isThereAWrittenMaintenanceProgram">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>2</td>
                    <td>
                        Name of Maintenance Manager:
                        {if isset($nameOfMaintenanceManager)}
                            {$nameOfMaintenanceManager}
                        {/if}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>
                        Years with company:
                        {if isset($yearsWithCompany)}
                            {$yearsWithCompany}
                        {/if}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>
                        Years in maintenance:
                        {if isset($yearsInMaintenance)}
                            {$yearsInMaintenance}
                        {/if}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>
                        # of full-time maintenance personnel:
                        {if isset($ofFullTimeMaintenancePersonnel)}
                            {$ofFullTimeMaintenancePersonnel}
                        {/if}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>
                        Maintenance program is provided for: (Check all that apply)<br>
                        {if isset($companyVehicles) && $companyVehicles == true}
                            <input id="companyVehicles" type="checkbox" name="companyVehicles" checked readonly />
                            <label for="companyVehicles">Company Vehicles</label><br>
                        {else}
                            <input id="companyVehicles" type="checkbox" name="companyVehicles" readonly />
                            <label for="companyVehicles">Company Vehicles</label><br>
                        {/if}
                        {if isset($ownerOperators2) && $ownerOperators2 == true}
                            <input id="ownerOperators2" type="checkbox" name="ownerOperators2" checked readonly />
                            <label for="ownerOperators2">Owner/Operators</label><br>
                        {else}
                            <input id="ownerOperators2" type="checkbox" name="ownerOperators2" readonly />
                            <label for="ownerOperators2">Owner/Operators</label><br>
                        {/if}
                        {if isset($openToThePublic) && $openToThePublic == true}
                            <input id="openToThePublic" type="checkbox" name="openToThePublic" checked readonly />
                            <label for="openToThePublic">Open to the public</label>
                        {else}
                            <input id="openToThePublic" type="checkbox" name="openToThePublic" readonly />
                            <label for="openToThePublic">Open to the public</label>
                        {/if}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>
                        Vehicle Maintenance is: (Check all that apply)<br>
                        {if isset($internal) && $internal == true}
                            <input id="internal" type="checkbox" name="internal" checked readonly />
                            <label for="internal">Internal</label><br>
                        {else}
                            <input id="internal" type="checkbox" name="internal" readonly />
                            <label for="internal">Internal</label><br>
                        {/if}
                        {if isset($externalBody) && $externalBody == true}
                            <input id="externalBody" type="checkbox" name="externalBody" checked readonly />
                            <label for="externalBody">External (Body)</label><br>
                        {else}
                            <input id="externalBody" type="checkbox" name="externalBody" readonly />
                            <label for="externalBody">External (Body)</label><br>
                        {/if}
                        {if isset($both) && $both == true}
                            <input id="both" type="checkbox" name="both" checked readonly />
                            <label for="both">Both</label>
                        {else}
                            <input id="both" type="checkbox" name="both" readonly />
                            <label for="both">Both</label>
                        {/if}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>
                        Indicate which of the following you have: (Check all that apply)<br>
                        {if isset($partsDepartment) && $partsDepartment == true}
                            <input id="partsDepartment" type="checkbox" name="partsDepartment" checked readonly />
                            <label for="partsDepartment">Parts department</label><br>
                        {else}
                            <input id="partsDepartment" type="checkbox" name="partsDepartment" readonly />
                            <label for="partsDepartment">Parts department</label><br>
                        {/if}
                        {if isset($bodyShop) && $bodyShop == true}
                            <input id="bodyShop" type="checkbox" name="bodyShop" checked readonly />
                            <label for="bodyShop">Body shop</label><br>
                        {else}
                            <input id="bodyShop" type="checkbox" name="bodyShop" readonly />
                            <label for="bodyShop">Body shop</label><br>
                        {/if}
                        {if isset($serviceBays) && $serviceBays == true}
                            <input id="serviceBays" type="checkbox" name="serviceBays" checked readonly />
                            <label for="serviceBays">Service bays</label><br>
                        {else}
                            <input id="serviceBays" type="checkbox" name="serviceBays" readonly />
                            <label for="serviceBays">Service bays</label><br>
                        {/if}
                        {if isset($controlledInspectionReports) && $controlledInspectionReports == true}
                            <input id="controlledInspectionReports" type="checkbox" name="controlledInspectionReports"
                                checked readonly />
                            <label for="controlledInspectionReports">Controlled inspection reports</label>
                        {else}
                            <input id="controlledInspectionReports" type="checkbox" name="controlledInspectionReports"
                                readonly />
                            <label for="controlledInspectionReports">Controlled inspection reports</label>
                        {/if}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>Are pre/post trip inspections made regularly?</td>
                    {if isset($survey4['arePrePostTripInspectionsMadeRegularly'])}
                        <td>
                            {if $survey4.arePrePostTripInspectionsMadeRegularly == "yes"}
                                <input id="arePrePostTripInspectionsMadeRegularly" type="checkbox"
                                    name="arePrePostTripInspectionsMadeRegularly" checked readonly />
                                <label for="arePrePostTripInspectionsMadeRegularly">Y</label>
                            {else}
                                <input id="arePrePostTripInspectionsMadeRegularly" type="checkbox"
                                    name="arePrePostTripInspectionsMadeRegularly" readonly />
                                <label for="arePrePostTripInspectionsMadeRegularly">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey4.arePrePostTripInspectionsMadeRegularly == "no"}
                                <input id="arePrePostTripInspectionsMadeRegularly" type="checkbox"
                                    name="arePrePostTripInspectionsMadeRegularly" checked readonly />
                                <label for="arePrePostTripInspectionsMadeRegularly">N</label>
                            {else}
                                <input id="arePrePostTripInspectionsMadeRegularly" type="checkbox"
                                    name="arePrePostTripInspectionsMadeRegularly" readonly />
                                <label for="arePrePostTripInspectionsMadeRegularly">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="arePrePostTripInspectionsMadeRegularly" type="checkbox"
                                name="arePrePostTripInspectionsMadeRegularly" readonly />
                            <label for="arePrePostTripInspectionsMadeRegularly">Y</label>
                            <input id="arePrePostTripInspectionsMadeRegularly" type="checkbox"
                                name="arePrePostTripInspectionsMadeRegularly" readonly />
                            <label for="arePrePostTripInspectionsMadeRegularly">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>10</td>
                    <td>Are all maintenance records on file?</td>
                    {if isset($survey4['areAllMaintenanceRecordsOnFile'])}
                        <td>
                            {if $survey4.areAllMaintenanceRecordsOnFile == "yes"}
                                <input id="areAllMaintenanceRecordsOnFile" type="checkbox" name="areAllMaintenanceRecordsOnFile"
                                    checked readonly />
                                <label for="areAllMaintenanceRecordsOnFile">Y</label>
                            {else}
                                <input id="areAllMaintenanceRecordsOnFile" type="checkbox" name="areAllMaintenanceRecordsOnFile"
                                    readonly />
                                <label for="areAllMaintenanceRecordsOnFile">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey4.areAllMaintenanceRecordsOnFile == "no"}
                                <input id="areAllMaintenanceRecordsOnFile" type="checkbox" name="areAllMaintenanceRecordsOnFile"
                                    checked readonly />
                                <label for="areAllMaintenanceRecordsOnFile">N</label>
                            {else}
                                <input id="areAllMaintenanceRecordsOnFile" type="checkbox" name="areAllMaintenanceRecordsOnFile"
                                    readonly />
                                <label for="areAllMaintenanceRecordsOnFile">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="areAllMaintenanceRecordsOnFile" type="checkbox" name="areAllMaintenanceRecordsOnFile"
                                readonly />
                            <label for="areAllMaintenanceRecordsOnFile">Y</label>
                            <input id="areAllMaintenanceRecordsOnFile" type="checkbox" name="areAllMaintenanceRecordsOnFile"
                                readonly />
                            <label for="areAllMaintenanceRecordsOnFile">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>11</td>
                    <td>Are re-treads used?</td>
                    {if isset($survey4['areReTreadsUsed'])}
                        <td>
                            {if $survey4.areReTreadsUsed == "yes"}
                                <input id="areReTreadsUsed" type="checkbox" name="areReTreadsUsed" checked readonly />
                                <label for="areReTreadsUsed">Y</label>
                            {else}
                                <input id="areReTreadsUsed" type="checkbox" name="areReTreadsUsed" readonly />
                                <label for="areReTreadsUsed">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey4.areReTreadsUsed == "no"}
                                <input id="areReTreadsUsed" type="checkbox" name="areReTreadsUsed" checked readonly />
                                <label for="areReTreadsUsed">N</label>
                            {else}
                                <input id="areReTreadsUsed" type="checkbox" name="areReTreadsUsed" readonly />
                                <label for="areReTreadsUsed">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="areReTreadsUsed" type="checkbox" name="areReTreadsUsed" readonly />
                            <label for="areReTreadsUsed">Y</label>
                            <input id="areReTreadsUsed" type="checkbox" name="areReTreadsUsed" readonly />
                            <label for="areReTreadsUsed">N</label>
                        </td>
                    {/if}
                </tr>
            </table>
        </div>

    </section>
    {* Section 4 *}

    {* Section 5 *}
    <section class="mb-4">
        <div class="mb-4">
            <h3 class="bold"><span class="section_black mr-4">SECTION 5</span> SAFETY </h3>
        </div>

        <div id="sectionContent">
            <strong>Attach copy of safety program</strong><br>
            <table>
                <tr>
                    <td>1</td>
                    <td>
                        Name of Safety Director:
                        {if isset($nameOfSafetyDirector)}
                            <span class="underline">{$nameOfSafetyDirector}</span>
                        {/if}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>
                        Years with company:
                        {if isset($yearsWithCompany1)}
                            <span class="underline">{$yearsWithCompany1}</span>
                        {/if}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>
                        Years in safety field:
                        {if isset($yearsInSafetyField)}
                            <span class="underline">{$yearsInSafetyField}</span>
                        {/if}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>
                        Safety director reports to:
                        {if isset($safetyDirectorReportsTo)}
                            <span class="underline">{$safetyDirectorReportsTo}</span>
                        {/if}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>
                        % of time spent on Safety:
                        {if isset($ofTimeSpentOnSafety)}
                            <span class="underline">{$ofTimeSpentOnSafety}</span>%
                        {/if}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Do you have a safety award program?</td>
                    {if isset($survey5['doYouHaveASafetyAwardPrograms'])}
                        <td>
                            {if $survey5.doYouHaveASafetyAwardPrograms == "yes"}
                                <input id="doYouHaveASafetyAwardPrograms" type="checkbox" name="doYouHaveASafetyAwardPrograms"
                                    checked readonly />
                                <label for="doYouHaveASafetyAwardPrograms">Y</label>
                            {else}
                                <input id="doYouHaveASafetyAwardPrograms" type="checkbox" name="doYouHaveASafetyAwardPrograms"
                                    readonly />
                                <label for="doYouHaveASafetyAwardPrograms">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey5.doYouHaveASafetyAwardPrograms == "no"}
                                <input id="doYouHaveASafetyAwardPrograms" type="checkbox" name="doYouHaveASafetyAwardPrograms"
                                    checked readonly />
                                <label for="doYouHaveASafetyAwardPrograms">N</label>
                            {else}
                                <input id="doYouHaveASafetyAwardPrograms" type="checkbox" name="doYouHaveASafetyAwardPrograms"
                                    readonly />
                                <label for="doYouHaveASafetyAwardPrograms">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="doYouHaveASafetyAwardPrograms" type="checkbox" name="doYouHaveASafetyAwardPrograms"
                                readonly />
                            <label for="doYouHaveASafetyAwardPrograms">Y</label>
                            <input id="doYouHaveASafetyAwardPrograms" type="checkbox" name="doYouHaveASafetyAwardPrograms"
                                readonly />
                            <label for="doYouHaveASafetyAwardPrograms">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>7</td>
                    <td>
                        How often are safety meetings held?
                        {if isset($howOftenAreSafetyMeetingsHeld)}
                            <span class="underline">{$howOftenAreSafetyMeetingsHeld}</span>
                        {/if}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>Are safety meetings mandatory?</td>
                    {if isset($survey5['areSafetyMeetingsMandatory'])}
                        <td>
                            {if $survey5.areSafetyMeetingsMandatory == "yes"}
                                <input id="areSafetyMeetingsMandatory" type="checkbox" name="areSafetyMeetingsMandatory" checked
                                    readonly />
                                <label for="areSafetyMeetingsMandatory">Y</label>
                            {else}
                                <input id="areSafetyMeetingsMandatory" type="checkbox" name="areSafetyMeetingsMandatory"
                                    readonly />
                                <label for="areSafetyMeetingsMandatory">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey5.areSafetyMeetingsMandatory == "no"}
                                <input id="areSafetyMeetingsMandatory" type="checkbox" name="areSafetyMeetingsMandatory" checked
                                    readonly />
                                <label for="areSafetyMeetingsMandatory">N</label>
                            {else}
                                <input id="areSafetyMeetingsMandatory" type="checkbox" name="areSafetyMeetingsMandatory"
                                    readonly />
                                <label for="areSafetyMeetingsMandatory">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="areSafetyMeetingsMandatory" type="checkbox" name="areSafetyMeetingsMandatory"
                                readonly />
                            <label for="areSafetyMeetingsMandatory">Y</label>
                            <input id="areSafetyMeetingsMandatory" type="checkbox" name="areSafetyMeetingsMandatory"
                                readonly />
                            <label for="areSafetyMeetingsMandatory">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>9</td>
                    <td>Is remedial training required for drivers with accidents/speeding?</td>
                    {if isset($survey5['isRemedialTrainingRequiredForDriversWithAccidentsSpeeding'])}
                        <td>
                            {if $survey5.isRemedialTrainingRequiredForDriversWithAccidentsSpeeding == "yes"}
                                <input id="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" type="checkbox"
                                    name="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" checked readonly />
                                <label for="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding">Y</label>
                            {else}
                                <input id="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" type="checkbox"
                                    name="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" readonly />
                                <label for="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey5.isRemedialTrainingRequiredForDriversWithAccidentsSpeeding == "no"}
                                <input id="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" type="checkbox"
                                    name="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" checked readonly />
                                <label for="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding">N</label>
                            {else}
                                <input id="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" type="checkbox"
                                    name="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" readonly />
                                <label for="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" type="checkbox"
                                name="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" readonly />
                            <label for="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding">Y</label>
                            <input id="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" type="checkbox"
                                name="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" readonly />
                            <label for="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>10</td>
                    <td>Do you maintain an accident register & conduct periodic accident analysis?</td>
                    {if isset($survey5['doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis'])}
                        <td>
                            {if $survey5.doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis == "yes"}
                                <input id="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" type="checkbox"
                                    name="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" checked readonly />
                                <label for="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis">Y</label>
                            {else}
                                <input id="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" type="checkbox"
                                    name="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" readonly />
                                <label for="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey5.doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis == "no"}
                                <input id="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" type="checkbox"
                                    name="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" checked readonly />
                                <label for="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis">N</label>
                            {else}
                                <input id="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" type="checkbox"
                                    name="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" readonly />
                                <label for="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" type="checkbox"
                                name="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" readonly />
                            <label for="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis">Y</label>
                            <input id="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" type="checkbox"
                                name="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" readonly />
                            <label for="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis">N</label>
                        </td>
                    {/if}
                </tr>
            </table>
            <p>
                <strong>What safety technology devices are you using?</strong><br>
            <table>
                <tr>
                    <th></th>
                    <th>% of Fleet</th>
                    <th>Date Installed</th>
                </tr>
                <tr>
                    <td>Accident Event Recorder - self managed:</td>
                    <td>
                        {if isset($number1)}
                            <span class="underline">{$number1}</span>%
                        {/if}
                    </td>
                    <td>
                        {if isset($dateTime)}
                            <span class="underline">{$dateTime}</span>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>Accident Event Recorder - third party reporting:</td>
                    <td>
                        {if isset($number2)}
                            <span class="underline">{$number2}</span>%
                        {/if}
                    </td>
                    <td>
                        {if isset($dateTime2)}
                            <span class="underline">{$dateTime2}</span>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>Electronic Logging Device:</td>
                    <td>
                        {if isset($number3)}
                            <span class="underline">{$number3}</span>%
                        {/if}
                    </td>
                    <td>
                        {if isset($dateTime3)}
                            <span class="underline">{$dateTime3}</span>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>Collision Avoidance:</td>
                    <td>
                        {if isset($number4)}
                            <span class="underline">{$number4}</span>%
                        {/if}
                    </td>
                    <td>
                        {if isset($dateTime5)}
                            <span class="underline">{$dateTime5}</span>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>In Vehicle Camera:</td>
                    <td>
                        {if isset($number5)}
                            <span class="underline">{$number5}</span>%
                        {/if}
                    </td>
                    <td>
                        {if isset($dateTime6)}
                            <span class="underline">{$dateTime6}</span>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>Anti-Rollover Device:</td>
                    <td>
                        {if isset($number6)}
                            <span class="underline">{$number6}</span>%
                        {/if}
                    </td>
                    <td>
                        {if isset($dateTime7)}
                            <span class="underline">{$dateTime7}</span>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>Telematics:</td>
                    <td>
                        {if isset($number7)}
                            <span class="underline">{$number7}</span>%
                        {/if}
                    </td>
                    <td>
                        {if isset($dateTime8)}
                            <span class="underline">{$dateTime8}</span>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td>Other:</td>
                    <td>
                        {if isset($number8)}
                            <span class="underline">{$number8}</span>%
                        {/if}
                    </td>
                    <td>
                        {if isset($dateTime9)}
                            <span class="underline">{$dateTime9}</span>
                        {/if}
                    </td>
                </tr>
            </table>
            </p>
        </div>

    </section>
    {* Section 5 *}

    {* Section 6 *}
    <section class="mb-4">
        <div class="mb-4">
            <h3 class="bold"><span class="section_black mr-4">SECTION 6</span> GENERAL LIABILITY </h3>
        </div>

        <div id="sectionContent">
            <table>
                <tr>
                    <th>Loc.</th>
                    <th>Address</th>
                    <th>Function</th>
                    <th>Fenced</th>
                    <th>Guarded</th>
                    <th>Public Access</th>
                    <th>Lighted</th>
                    <th># of Employees</th>
                    <th>Owned or Leased</th>
                </tr>
                {foreach from=$dataGrid1 item=item key=key}
                    <tr>
                        <td>
                            {$key+1}
                        </td>
                        <td>
                            {if isset($item['address7']['display_name'])}
                                {$item.address7.display_name}
                            {/if}
                        </td>
                        <td>
                            {if isset($item['number13'])}
                                {$item.number13}
                            {/if}
                        </td>
                        <td>
                            {if isset($item['fenced']) && ($item['fenced'] === "true" || $item['fenced'] === true)}
                                <input id="fenced" type="checkbox" name="fenced" checked readonly />
                            {/if}
                        </td>
                        <td>
                            {if isset($item['guarded1']) && ($item['guarded1'] === "true" || $item['guarded1'] === true)}
                                <input id="guarded1" type="checkbox" name="guarded1" checked readonly />
                            {/if}
                        </td>
                        <td>
                            {if isset($item['publicAccess']) && ($item['publicAccess'] === "true" || $item['publicAccess'] === true)}
                                <input id="publicAccess" type="checkbox" name="publicAccess" checked readonly />
                            {/if}
                        </td>
                        <td>
                            {if isset($item['lighted']) && ($item['lighted'] === "true" || $item['lighted'] === true)}
                                <input id="lighted" type="checkbox" name="lighted" checked readonly />
                            {/if}
                        </td>
                        <td>
                            {if isset($item['guardDogS']) && ($item['guardDogS'] === "true" || $item['guardDogS'] === true)}
                                <input id="guardDogS" type="checkbox" name="guardDogS" checked readonly />
                            {/if}
                        </td>
                        <td>
                            {if isset($item['ofEmployees'])}
                                {$item.ofEmployees}
                            {/if}
                        </td>
                        <td>
                            {if isset($item['ownedLeased'])}
                                {$item.ownedLeased}
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            </table>
            <table>
                <tr>
                    <td>1.</td>
                    <td>Is Insured involved in any business activity other than trucking?</td>
                    {if isset($survey6['isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking'])}
                        <td>
                            {if $survey6.isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking == "yes"}
                                <input id="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" type="checkbox"
                                    name="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" checked readonly />
                                <label for="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking">Y</label>
                            {else}
                                <input id="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" type="checkbox"
                                    name="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" readonly />
                                <label for="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey6.isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking == "no"}
                                <input id="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" type="checkbox"
                                    name="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" checked readonly />
                                <label for="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking">N</label>
                            {else}
                                <input id="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" type="checkbox"
                                    name="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" readonly />
                                <label for="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" type="checkbox"
                                name="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" readonly />
                            <label for="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking">Y</label>
                            <input id="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" type="checkbox"
                                name="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" readonly />
                            <label for="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Does applicant have underground or above ground storage facilities?</td>
                    {if isset($survey6['doesApplicantHaveUndergroundOrAboveGroundStorageFacilities'])}
                        <td>
                            {if $survey6.doesApplicantHaveUndergroundOrAboveGroundStorageFacilities == "yes"}
                                <input id="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" type="checkbox"
                                    name="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" checked readonly />
                                <label for="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities">Y</label>
                            {else}
                                <input id="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" type="checkbox"
                                    name="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" readonly />
                                <label for="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey6.doesApplicantHaveUndergroundOrAboveGroundStorageFacilities == "no"}
                                <input id="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" type="checkbox"
                                    name="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" checked readonly />
                                <label for="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities">N</label>
                            {else}
                                <input id="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" type="checkbox"
                                    name="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" readonly />
                                <label for="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" type="checkbox"
                                name="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" readonly />
                            <label for="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities">Y</label>
                            <input id="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" type="checkbox"
                                name="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" readonly />
                            <label for="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Does insured have mobile equipment?</td>
                    {if isset($survey6['doesInsuredHaveMobileEquipment'])}
                        <td>
                            {if $survey6.doesInsuredHaveMobileEquipment == "yes"}
                                <input id="doesInsuredHaveMobileEquipment" type="checkbox" name="doesInsuredHaveMobileEquipment"
                                    checked readonly />
                                <label for="doesInsuredHaveMobileEquipment">Y</label>
                            {else}
                                <input id="doesInsuredHaveMobileEquipment" type="checkbox" name="doesInsuredHaveMobileEquipment"
                                    readonly />
                                <label for="doesInsuredHaveMobileEquipment">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey6.doesInsuredHaveMobileEquipment == "no"}
                                <input id="doesInsuredHaveMobileEquipment" type="checkbox" name="doesInsuredHaveMobileEquipment"
                                    checked readonly />
                                <label for="doesInsuredHaveMobileEquipment">N</label>
                            {else}
                                <input id="doesInsuredHaveMobileEquipment" type="checkbox" name="doesInsuredHaveMobileEquipment"
                                    readonly />
                                <label for="doesInsuredHaveMobileEquipment">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="doesInsuredHaveMobileEquipment" type="checkbox" name="doesInsuredHaveMobileEquipment"
                                readonly />
                            <label for="doesInsuredHaveMobileEquipment">Y</label>
                            <input id="doesInsuredHaveMobileEquipment" type="checkbox" name="doesInsuredHaveMobileEquipment"
                                readonly />
                            <label for="doesInsuredHaveMobileEquipment">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Does applicant sell any product either wholesale or retail?</td>
                    {if isset($survey6['doesApplicantSellAnyProductEitherWholesaleOrRetail'])}
                        <td>
                            {if $survey6.doesApplicantSellAnyProductEitherWholesaleOrRetail == "yes"}
                                <input id="doesApplicantSellAnyProductEitherWholesaleOrRetail" type="checkbox"
                                    name="doesApplicantSellAnyProductEitherWholesaleOrRetail" checked readonly />
                                <label for="doesApplicantSellAnyProductEitherWholesaleOrRetail">Y</label>
                            {else}
                                <input id="doesApplicantSellAnyProductEitherWholesaleOrRetail" type="checkbox"
                                    name="doesApplicantSellAnyProductEitherWholesaleOrRetail" readonly />
                                <label for="doesApplicantSellAnyProductEitherWholesaleOrRetail">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey6.doesApplicantSellAnyProductEitherWholesaleOrRetail == "no"}
                                <input id="doesApplicantSellAnyProductEitherWholesaleOrRetail" type="checkbox"
                                    name="doesApplicantSellAnyProductEitherWholesaleOrRetail" checked readonly />
                                <label for="doesApplicantSellAnyProductEitherWholesaleOrRetail">N</label>
                            {else}
                                <input id="doesApplicantSellAnyProductEitherWholesaleOrRetail" type="checkbox"
                                    name="doesApplicantSellAnyProductEitherWholesaleOrRetail" readonly />
                                <label for="doesApplicantSellAnyProductEitherWholesaleOrRetail">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="doesApplicantSellAnyProductEitherWholesaleOrRetail" type="checkbox"
                                name="doesApplicantSellAnyProductEitherWholesaleOrRetail" readonly />
                            <label for="doesApplicantSellAnyProductEitherWholesaleOrRetail">Y</label>
                            <input id="doesApplicantSellAnyProductEitherWholesaleOrRetail" type="checkbox"
                                name="doesApplicantSellAnyProductEitherWholesaleOrRetail" readonly />
                            <label for="doesApplicantSellAnyProductEitherWholesaleOrRetail">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Does insured lease mobile equipment?</td>
                    {if isset($survey6['doesInsuredLeaseMobileEquipment'])}
                        <td>
                            {if $survey6.doesInsuredLeaseMobileEquipment == "yes"}
                                <input id="doesInsuredLeaseMobileEquipment" type="checkbox"
                                    name="doesInsuredLeaseMobileEquipment" checked readonly />
                                <label for="doesInsuredLeaseMobileEquipment">Y</label>
                            {else}
                                <input id="doesInsuredLeaseMobileEquipment" type="checkbox"
                                    name="doesInsuredLeaseMobileEquipment" readonly />
                                <label for="doesInsuredLeaseMobileEquipment">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey6.doesInsuredLeaseMobileEquipment == "no"}
                                <input id="doesInsuredLeaseMobileEquipment" type="checkbox"
                                    name="doesInsuredLeaseMobileEquipment" checked readonly />
                                <label for="doesInsuredLeaseMobileEquipment">N</label>
                            {else}
                                <input id="doesInsuredLeaseMobileEquipment" type="checkbox"
                                    name="doesInsuredLeaseMobileEquipment" readonly />
                                <label for="doesInsuredLeaseMobileEquipment">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="doesInsuredLeaseMobileEquipment" type="checkbox"
                                name="doesInsuredLeaseMobileEquipment" readonly />
                            <label for="doesInsuredLeaseMobileEquipment">Y</label>
                            <input id="doesInsuredLeaseMobileEquipment" type="checkbox"
                                name="doesInsuredLeaseMobileEquipment" readonly />
                            <label for="doesInsuredLeaseMobileEquipment">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>6.</td>
                    <td>Does applicant do any rigging?</td>
                    {if isset($survey6['doesApplicantDoAnyRigging'])}
                        <td>
                            {if $survey6.doesApplicantDoAnyRigging == "yes"}
                                <input id="doesApplicantDoAnyRigging" type="checkbox" name="doesApplicantDoAnyRigging" checked
                                    readonly />
                                <label for="doesApplicantDoAnyRigging">Y</label>
                            {else}
                                <input id="doesApplicantDoAnyRigging" type="checkbox" name="doesApplicantDoAnyRigging"
                                    readonly />
                                <label for="doesApplicantDoAnyRigging">Y</label>
                            {/if}
                        </td>
                        <td>
                            {if $survey6.doesApplicantDoAnyRigging == "no"}
                                <input id="doesApplicantDoAnyRigging" type="checkbox" name="doesApplicantDoAnyRigging" checked
                                    readonly />
                                <label for="doesApplicantDoAnyRigging">N</label>
                            {else}
                                <input id="doesApplicantDoAnyRigging" type="checkbox" name="doesApplicantDoAnyRigging"
                                    readonly />
                                <label for="doesApplicantDoAnyRigging">N</label>
                            {/if}
                        </td>
                    {else}
                        <td>
                            <input id="doesApplicantDoAnyRigging" type="checkbox" name="doesApplicantDoAnyRigging"
                                readonly />
                            <label for="doesApplicantDoAnyRigging">Y</label>
                            <input id="doesApplicantDoAnyRigging" type="checkbox" name="doesApplicantDoAnyRigging"
                                readonly />
                            <label for="doesApplicantDoAnyRigging">N</label>
                        </td>
                    {/if}
                </tr>
                <tr>
                    <td>7.</td>
                    <td>If yes to any of the above please explain:
                        {if isset($ifYesToAnyOfTheAbovePleaseExplain1)}
                            {$ifYesToAnyOfTheAbovePleaseExplain1}
                        {/if}
                    </td>
                </tr>
            </table>
            <p class="continued">SECTION 6: GENERAL LIABILITY, continued</p>
            <strong>Limits of coverage:</strong><br>
            General Aggregate Limit (other than products-completed operations):
            {if isset($generalAggregateLimitOtherThanProductsCompletedOperations)}
                <span class="underline">{$generalAggregateLimitOtherThanProductsCompletedOperations}<span>
                    {/if}
                    <br>
                    Product-completed Operations Aggregate Limit:
                    {if isset($productCompletedOperationsAggregateLimit)}
                        <span class="underline">{$productCompletedOperationsAggregateLimit}<span>
                            {/if}
                            <br>
                            Personal & Advertising Injury Limit:
                            {if isset($personalAdvertisingInjuryLimit)}
                                <span class="underline">{$personalAdvertisingInjuryLimit}<span>
                                    {/if}
                                    <br>
                                    Each Occurrence Limit:
                                    {if isset($eachOccurrenceLimit)}
                                        <span class="underline">{$eachOccurrenceLimit}<span>
                                            {/if}
                                            <br>
                                            Fire Damage Limit:
                                            {if isset($fireDamageLimit)}
                                                <span class="underline">{$fireDamageLimit}<span>
                                                    {/if}
                                                    <br>
                                                    Medical Expense Limit:
                                                    {if isset($medicalExpenseLimit)}
                                                        <span class="underline">{$medicalExpenseLimit}<span>
                                                            {/if}
                                                            <br>
        </div>

    </section>
    {* Section 6 *}
</body>

</html>